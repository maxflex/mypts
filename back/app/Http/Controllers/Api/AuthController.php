<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'docs' => ['required', 'exists:users,email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->docs)->firstOrFail();

        if (!Hash::check($request->password, $user->password)) {
            return response(null, 404);
        }

        return $user;
    }

    public function getUser()
    {
        return auth()->user();
    }
}
