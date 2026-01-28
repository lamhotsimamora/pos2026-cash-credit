<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = md5($request->input('password'));

        $admin = \App\Models\Admins::where('username', $username)
            ->where('password', $password)
            ->first();

        if ($admin) {
            return $this->responseSuccess('Login successful', $admin);
        } else {
            return $this->responseError('Login Failed');
        }
    }

    public function logout(Request $request)
    {
        return response()->json(['message' => 'Logout successful'], 200);
    }
}
