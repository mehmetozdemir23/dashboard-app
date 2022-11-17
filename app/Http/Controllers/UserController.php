<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUser(Request $request){
        return $request->user();
    }
    public function update(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required|confirmed|regex:/^[0-9a-zA-Z]{7,}$/'
        ]);


        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        return $user;
    }
}
