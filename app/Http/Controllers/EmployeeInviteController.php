<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EmployeeInviteController extends Controller
{
    /**
     * Display employee management page for HRD
     */
    public function index()
    {
        $user = Auth::user();
        $client = $user->ownedClient;

        if (! $client) {
            return redirect()->route('dashboard.index')
                ->with('error', 'You do not have a company profile.');
        }

        // Get all employees (users with this client_id)
        $employees = User::where('client_id', $client->id)
            ->select('id', 'name', 'email', 'picture', 'company_role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get pending invites
        $pendingInvites = EmployeeInvite::where('client_id', $client->id)
            ->pending()
            ->with('inviter:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        // Count for limit check
        $currentEmployeeCount = $employees->count();
        $employeeLimit = $client->employee_limit ?? 50;

        return Inertia::render('Client/Employees/Index', [
            'client' => $client->only('id', 'company_name', 'employee_limit'),
            'employees' => $employees,
            'pendingInvites' => $pendingInvites,
            'stats' => [
                'current' => $currentEmployeeCount,
                'limit' => $employeeLimit,
                'pendingCount' => $pendingInvites->count(),
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Create new invite
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $client = $user->ownedClient;

        if (! $client) {
            return redirect()->back()->with('error', 'You do not have a company profile.');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email = strtolower($request->email);

        // Check if email already registered
        if (User::where('email', $email)->exists()) {
            return redirect()->back()->with('error', 'This email is already registered.');
        }

        // Check if already invited (pending)
        if (EmployeeInvite::where('client_id', $client->id)->where('email', $email)->pending()->exists()) {
            return redirect()->back()->with('error', 'This email already has a pending invite.');
        }

        // Check employee limit
        $currentCount = User::where('client_id', $client->id)->count();
        $pendingCount = EmployeeInvite::where('client_id', $client->id)->pending()->count();
        $limit = $client->employee_limit ?? 50;

        if (($currentCount + $pendingCount) >= $limit) {
            return redirect()->back()->with('error', "Employee limit reached ({$limit}). Please contact support to increase.");
        }

        // Create invite
        $invite = EmployeeInvite::create([
            'client_id' => $client->id,
            'email' => $email,
            'token' => EmployeeInvite::generateToken(),
            'invited_by' => $user->id,
            'status' => 'pending',
            'expires_at' => now()->addHours(24),
        ]);

        $inviteUrl = route('invite.verify', $invite->token);

        return redirect()->back()->with('success', "Invite sent! Share this link: {$inviteUrl}");
    }

    /**
     * Revoke pending invite
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $client = $user->ownedClient;

        $invite = EmployeeInvite::where('id', $id)
            ->where('client_id', $client->id)
            ->where('status', 'pending')
            ->first();

        if (! $invite) {
            return redirect()->back()->with('error', 'Invite not found or already used.');
        }

        $invite->revoke();

        return redirect()->back()->with('success', 'Invite has been revoked.');
    }

    /**
     * Verify invite token and show registration form
     */
    public function verify($token)
    {
        $invite = EmployeeInvite::where('token', $token)
            ->with('client:id,company_name,logo')
            ->first();

        if (! $invite) {
            return Inertia::render('Auth/InviteError', [
                'error' => 'Invalid invite link.',
            ]);
        }

        if ($invite->status === 'accepted') {
            return Inertia::render('Auth/InviteError', [
                'error' => 'This invite has already been used.',
            ]);
        }

        if ($invite->status === 'revoked') {
            return Inertia::render('Auth/InviteError', [
                'error' => 'This invite has been revoked.',
            ]);
        }

        if ($invite->isExpired()) {
            $invite->update(['status' => 'expired']);

            return Inertia::render('Auth/InviteError', [
                'error' => 'This invite has expired. Please request a new one from your HR.',
            ]);
        }

        return Inertia::render('Auth/InviteRegister', [
            'invite' => [
                'token' => $invite->token,
                'email' => $invite->email,
                'company' => $invite->client->company_name,
                'logo' => $invite->client->logo,
            ],
        ]);
    }

    /**
     * Register user via invite
     */
    public function register(Request $request, $token)
    {
        $invite = EmployeeInvite::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (! $invite || $invite->isExpired()) {
            return redirect()->route('login')->with('error', 'Invalid or expired invite.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $invite->email,
            'password' => Hash::make($request->password),
            'roles' => ['user'],
            'client_id' => $invite->client_id,
            'company_role' => 'employee',
            'email_verified_at' => now(), // Auto-verify since invited
        ]);

        // Mark invite as accepted
        $invite->markAsAccepted();

        // Login the user
        Auth::login($user);

        return redirect()->route('dashboard.index')
            ->with('success', 'Welcome! You have successfully joined the company.');
    }
}
