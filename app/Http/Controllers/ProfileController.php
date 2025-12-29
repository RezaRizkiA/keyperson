<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProfileController extends Controller
{
    protected $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * Display profile settings page based on user role
     * Route: profile/settings (name: profile.edit)
     */
    public function edit()
    {
        $user = Auth::user();
        $user->load([
            'expert:id,user_id,slug',
            'client:id,user_id,slug',
        ]);
        $roles = $user->roles ?? [];

        // Determine view based on role priority
        $view = match (true) {
            in_array('administrator', $roles) => 'Administrator/Settings/Index',
            in_array('expert', $roles) => 'Expert/Settings/Index',
            in_array('client', $roles) => 'Client/Settings/Index',
            default => 'User/Settings/Index',
        };

        return Inertia::render($view, [
            'user' => $user,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Update user profile data
     * Route: profile/update (name: profile.update)
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $roles = $user->roles ?? [];

        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ];

        // Conditional slug validation - only for Expert or Client
        if (in_array('expert', $roles) && $user->expert) {
            $rules['slug_name'] = 'nullable|alpha_dash|unique:experts,slug,'.$user->expert->id;
        } elseif (in_array('client', $roles) && $user->client) {
            $rules['slug_name'] = 'nullable|alpha_dash|unique:clients,slug,'.$user->client->id;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->service->updateProfile($user, $request->all());

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update user password
     * Route: profile/password (name: profile.password.update)
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'new_password' => 'required|min:8|confirmed',
        ];

        if ($user->password !== null) {
            $rules['current_password'] = 'required|current_password';
        } else {
            $rules['current_password'] = 'nullable|current_password';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->service->updatePassword($user, $request->current_password, $request->new_password);

        return redirect()->back()->with('success', 'Password has been updated successfully!');
    }

    /**
     * Update user profile picture
     * Route: profile/picture (name: profile.picture.update)
     */
    public function updatePicture(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('picture')) {
            $this->service->updateAvatar($user, $request->file('picture'));
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
}
