<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }

    public function getSystemSettings(Request $request)
    {
        if (!$request->user()->isSystemAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // In a real application, you would fetch these from a database
        $settings = [
            'site_name' => 'User Management System',
            'site_description' => 'Multi-role user management platform',
            'maintenance_mode' => false,
            'user_registration' => true,
            'max_file_size' => 10,
            'session_timeout' => 60,
            'email_notifications' => true,
            'system_notifications' => true,
        ];

        return response()->json($settings);
    }

    public function updateSystemSettings(Request $request)
    {
        if (!$request->user()->isSystemAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'site_name' => 'sometimes|string|max:255',
            'site_description' => 'sometimes|string|max:500',
            'maintenance_mode' => 'sometimes|boolean',
            'user_registration' => 'sometimes|boolean',
            'max_file_size' => 'sometimes|integer|min:1|max:100',
            'session_timeout' => 'sometimes|integer|min:1|max:480',
            'email_notifications' => 'sometimes|boolean',
            'system_notifications' => 'sometimes|boolean',
        ]);

        // In a real application, you would save these to a database
        // For now, we'll just return the validated data
        return response()->json([
            'message' => 'System settings updated successfully',
            'settings' => $validated
        ]);
    }

    public function getAccountSettings(Request $request)
    {
        $user = $request->user();
        
        $settings = [
            'name' => $user->name,
            'email' => $user->email,
            'email_notifications' => true, // Default values
            'push_notifications' => false,
            'security_alerts' => true,
            'newsletter' => false,
        ];

        return response()->json($settings);
    }

    public function updateAccountSettings(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'email_notifications' => 'sometimes|boolean',
            'push_notifications' => 'sometimes|boolean',
            'security_alerts' => 'sometimes|boolean',
            'newsletter' => 'sometimes|boolean',
        ]);

        // Update user basic info
        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }

        $user->save();

        // In a real app, you'd save notification preferences to a separate table
        return response()->json([
            'message' => 'Account settings updated successfully',
            'user' => $user
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
}