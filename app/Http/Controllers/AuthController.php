<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate user input
        $fields = $request->validate([
            'email'           => 'required|email|unique:users',
            'password'        => 'required|confirmed',
            'first_name'      => 'required|max:255',
            'last_name'       => 'required|max:255',
        ]);

        // Add default values
        $fields['password'] = Hash::make($fields['password']);
        $fields['role']            = 'student';
        $fields['profile_picture'] = 'https://via.placeholder.com/200x200.png/00dd55?text=people+illum';

        // Create the user
        $user = User::create($fields);

        // Generate a token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Authenticate user and issue a token
     */
    public function login(Request $request)
    {
        // Validate user input
        $fields = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $fields['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate a token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Logout user by revoking tokens
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out.'
        ], 200);
    }
}
