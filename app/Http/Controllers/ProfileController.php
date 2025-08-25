<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $user = auth()->user();
            return view('profile.index', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading profile: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'current_password' => 'nullable|required_with:new_password',
                'new_password' => 'nullable|min:8|confirmed',
                'bio' => 'nullable|string|max:1000',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
            ]);

            // Update basic information
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->bio = $request->bio;
            $user->date_of_birth = $request->date_of_birth;
            $user->gender = $request->gender;

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
            }

            // Handle password change
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Current password is incorrect']);
                }
                
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            return back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function deleteAvatar()
    {
        try {
            $user = auth()->user();
            
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $user->avatar = null;
            $user->save();

            return back()->with('success', 'Avatar deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting avatar: ' . $e->getMessage());
        }
    }

    public function preferences()
    {
        try {
            $user = auth()->user();
            return view('profile.preferences', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading preferences: ' . $e->getMessage());
        }
    }

    public function updatePreferences(Request $request)
    {
        try {
            $user = auth()->user();
            
            $request->validate([
                'language' => 'nullable|string|in:th,en',
                'timezone' => 'nullable|string',
                'notifications_email' => 'boolean',
                'notifications_sms' => 'boolean',
                'theme_preference' => 'nullable|string|in:light,dark,auto',
            ]);

            $user->language = $request->language ?? 'th';
            $user->timezone = $request->timezone ?? 'Asia/Bangkok';
            $user->notifications_email = $request->boolean('notifications_email');
            $user->notifications_sms = $request->boolean('notifications_sms');
            $user->theme_preference = $request->theme_preference ?? 'auto';

            $user->save();

            return back()->with('success', 'Preferences updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating preferences: ' . $e->getMessage());
        }
    }
}
