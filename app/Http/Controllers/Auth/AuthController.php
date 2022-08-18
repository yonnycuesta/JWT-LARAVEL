<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUser;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone'=> 'string|max:200',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            // create a token for the user
            $token = JWTAuth::fromUser($user);

            // Detalles del mensaje
            $details = [
                'title' => 'Welcome to our application',
                'body' => 'You have successfully created an account!', 'We look forward to seeing you again.',
            ];
            // Enviar correo con menaje de bienvenida
            Mail::to($request->email)->send(new WelcomeUser($details));

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating new user',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid Credentials',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Could not create token',
            ], 500);
        }

        return response()->json([
            'message' => 'User logged in successfully',
            'token' => $token,
        ]);
    }
}
