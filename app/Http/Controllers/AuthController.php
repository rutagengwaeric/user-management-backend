<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller 
{
    use HasApiTokens, Notifiable;

   public function register(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'sometimes|in:system_admin,local_leader,policy_maker,citizen',
            // Citizen-specific fields
            'national_id' => 'required_if:role,citizen|string|unique:citizens',
            'full_name' => 'required_if:role,citizen|string|max:255',
            'date_of_birth' => 'required_if:role,citizen|date',
            'address' => 'required_if:role,citizen|string',
            'phone_number' => 'required_if:role,citizen|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Default role is citizen for public registration
        $role = $request->role ?? 'citizen';

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        // If registering as citizen, create citizen profile
        if ($role === 'citizen' && $request->has(['national_id', 'full_name', 'date_of_birth', 'address', 'phone_number'])) {
            Citizen::create([
                'user_id' => $user->id,
                'national_id' => $request->national_id,
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'verification_status' => 'pending', // Default status for new citizens
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Registration successful'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
