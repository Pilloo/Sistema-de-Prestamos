<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Departamento;
use App\Models\Seccione;
use App\Models\User;
use Illuminate\Contracts\Cache\Store;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(["seccion.caracteristica", "departamento.caracteristica"])->latest()->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        $departamentos = Departamento::join('caracteristicas as c', 'departamentos.caracteristica_id', '=', 'c.id')
            ->select('departamentos.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $secciones = Seccione::join('caracteristicas as c', 'secciones.caracteristica_id', '=', 'c.id')
            ->select('secciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('users.create', compact('roles', 'departamentos', 'secciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try{
            DB::beginTransaction();
            $fieldHash = Hash::make($request->password);
            $request->merge(['password' => $fieldHash]);

            
            $data = $request->all();
            if ($request->hasFile('img_path')) {
                $data['img_path'] = $this->handleUploadImage($request->file('img_path'));
            }

            $user = User::create($data);
            $user->assignRole($request->role);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try{
            DB::beginTransaction();
            $data = $request->all();
            if(empty($request->password)){
                $data = Arr::except($data, ['password']);
            } else {
                $data['password'] = Hash::make($request->password);
            }

            $rutaImagenes = public_path('img/users');
            if ($request->hasFile('img_path')) {
                // Eliminar imagen anterior si existe
                if ($user->img_path && file_exists($rutaImagenes . '/' . $user->img_path)) {
                    unlink($rutaImagenes . '/' . $user->img_path);
                }
                $data['img_path'] = $this->handleUploadImage($request->file('img_path'));
            }

            $user->update($data);
            $user->syncRoles($request->role);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar el usuario: ' . $e->getMessage()]);
        }
        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $rolUser = $user->getRoleNames()->first();
        $user->removeRole($rolUser);
        // Eliminar imagen si existe
        $rutaImagenes = public_path('img/users');
        if ($user->img_path && file_exists($rutaImagenes . '/' . $user->img_path)) {
            unlink($rutaImagenes . '/' . $user->img_path);
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente');
    }

    protected function handleUploadImage($file)
    {
        $rutaImagenes = public_path('img/users');
        if (!file_exists($rutaImagenes)) {
            mkdir($rutaImagenes, 0777, true);
        }
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($rutaImagenes, $filename);
        return $filename;
    }

}
