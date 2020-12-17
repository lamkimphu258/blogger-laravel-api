<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function store(StoreUserRequest $request)
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
