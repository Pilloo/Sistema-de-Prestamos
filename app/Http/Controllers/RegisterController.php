<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('img_path')) {
            $file = $request->file('img_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/users'), $filename);
            $data['img_path'] = $filename;
        }

        $user = User::create($data);
        Auth::login($user);
        return redirect()->route('home')->with('success', 'Usuario registrado correctamente.');
    }
}
