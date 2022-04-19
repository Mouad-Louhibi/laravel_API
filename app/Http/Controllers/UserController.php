<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' =>  'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return new UserResource($user);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' =>  'required',
            'password' => 'required'
        ]);

        $user = User::whereEmail($request->email)->first();
        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Connected Successfully',
                    'token' => $token
                ]);
            } else {
                return response()->json([
                    'message' => 'Invalid Credentials'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Invalid Credentials'
            ]);
        }
    }

    public function profile()
    {
        return new UserResource(auth()->user());
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Successfully'
        ]);
    }
}
