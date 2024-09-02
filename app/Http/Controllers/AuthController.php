<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            if ($validate->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validate->errors()
                ], 400);
            } else {
                $user = User::where('email', $request->email)->first();

                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'password or email and nim is wrong',
                    ], 400);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'login success',
                    'token' => $user->createToken($request->ip())->plainTextToken
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th,
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "role_id" => "required|integer",
            "email" => "required|string|email:dns|unique:users",
            "phone" => "required",
            "password" => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validate->errors(),
                'data' => null
            ], 400);
        } else {
            $data = $validate->validated();
            $data['password'] = Hash::make($data['password']);

            try {
                $user = User::create([
                    'name' => $data['name'],
                    'role_id' => $data['role_id'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'status_accont' => "active",
                    'password' => $data['password'],
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Register successfull',
                    'data' => $user
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 500,
                    'message' => $th->getMessage(),
                    'data' => null
                ], 500);
            }
        }
    }

    public function getRoles(Request $request)
    {
        try {
            $roles = Role::get();

            return response()->json([
                'status' => 200,
                'message' => "Success get data roles.",
                'data' => [
                    "roles" => $roles
                ]
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
