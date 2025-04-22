<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request)
    {   
        $user = User::withTrashed()->where('email', $request->email)->first();

        if ($user) {
            if ($user->trashed()) {
                $user->restore();
                $user->update([
                    'name' => $request->name,
                    'password' => md5($request->password),
                ]);
                $user->tokens()->delete();
            }

            return response()->json(['success' => false, 'message' => 'Email already exists.'], 409);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }       

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => md5($request->password), // MD5 encryption 
        ]);

        $user->assignRole('employee');

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => $user,
            'token' => $token,
        ], 200);
    }

}
