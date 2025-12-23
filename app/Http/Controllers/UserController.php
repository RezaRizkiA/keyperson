<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users
     *
     * @param Request $request
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['search', 'role']);
            $data = $this->userService->getPaginatedUsers($filters);

            return Inertia::render('Administrator/Users/Index', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load users: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard.index')
                ->withErrors(['error' => 'Failed to load users. Please try again.']);
        }
    }

    /**
     * Display the specified user
     *
     * @param int $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        try {
            $data = $this->userService->getUserDetail($id);

            return Inertia::render('Administrator/Users/Show', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load user details: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard.users.index')
                ->withErrors(['error' => 'User not found.']);
        }
    }

    /**
     * Show the form for editing the specified user
     *
     * @param int $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            $data = $this->userService->getUserForEdit($id);

            return Inertia::render('Administrator/Users/Edit', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load user for edit: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard.users.index')
                ->withErrors(['error' => 'User not found.']);
        }
    }

    /**
     * Update the specified user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:administrator,user,expert,client',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            $this->userService->updateUser($id, $request->all());

            return redirect()->route('dashboard.users.show', $id)
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'Failed to update user. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified user
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        try {
            // Get user info before deletion for flash message
            $user = \App\Models\User::findOrFail($id);
            $userName = $user->name;
            $userId = '#U-' . str_pad($id, 3, '0', STR_PAD_LEFT);
            
            $this->userService->deleteUser($id);

            return redirect()->route('dashboard.users.index')
                ->with('success', "The user {$userName} ({$userId}) has been permanently removed from the system.");
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
}
