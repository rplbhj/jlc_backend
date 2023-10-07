<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password' => 'required|min:3|max:255',
            'role' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors()
            ], 401);
        }

        $password = Hash::make($request->password);

        User::create([
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Register Berhasil'
        ], 200);
    }

    public function userLogin(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message_validate' => $validate->errors()
            ], 401);
        }

        if (Auth::attempt($request->only(['email', 'password']))) {
            $dataUser = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil login',
                'token' => $dataUser->createToken('user_login')->plainTextToken,
                'email' => $dataUser->email
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }
    }

    public function index()
    {
        return response()->json([
            'status' => false,
            'message' => 'You need Authentication'
        ], 401);
    }

    public function Logout(Request $request)
    {
        $logout = $request->user()->tokens()->delete();
        if ($logout) {
            return response()->json([
                'status' => true,
                'message' => 'berhasil logout',
            ], 200);
        }
    }

    public function me()
    {
        return response()->json([
            'status' => true,
            'message' => 'User',
            'user' => Auth()->user()
        ], 200);
    }
}
