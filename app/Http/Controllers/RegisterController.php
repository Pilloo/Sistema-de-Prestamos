<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Models\Seccione;
use App\Models\Departamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class RegisterController extends Controller
{
    public function index()
    {

        $departamentos = Departamento::join('caracteristicas as c', 'departamentos.caracteristica_id', '=', 'c.id')
            ->select('departamentos.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $secciones = Seccione::join('caracteristicas as c', 'secciones.caracteristica_id', '=', 'c.id')
            ->select('secciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('auth.register', compact('departamentos', 'secciones'));
    }

    public function register(RegisterUserRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            if ($request->hasFile('img_path')) {
                $file = $request->file('img_path');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('img/users'), $filename);
                $data['img_path'] = $filename;
            }

            $user = User::create($data);
            $user->assignRole('invitado');
            Auth::login($user);
            DB::commit();
            return redirect()->route('home')->with('success', 'Usuario registrado correctamente.');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar usuario: ' . $e->getMessage());
        }
    }
}
