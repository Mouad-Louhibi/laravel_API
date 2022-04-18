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
        $user = User::whereEmail($request->email)->first();
        if ($user->id) {
            if (Hash::check($request->password, $user->password)) {
            } else {
                return response()->json([
                    'message' => 'Invalid Credentials',
                    404
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Invalid Credentials',
                404
            ]);
        }
    }
}
