<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = new User;
        $user->id = Str::uuid();
        $user->name = $request->get('name', $user->name);
        $user->email = $request->get('email', $user->email);
        $user->password = Hash::make($request->get('password', $user->password));

        $user->save();

        return response(null, 204);
    }
}
