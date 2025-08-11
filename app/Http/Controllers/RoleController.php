<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\traits\HasRoles;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permission::all();
        return view('roles.create', compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);

        try{
            DB::beginTransaction();
            $role = Role::create(['name' => $request->name]);
            $permissions = Permission::whereIn('id', $request->permission)->get();
            $role->syncPermissions($permissions);
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Rol creado correctamente');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors('error', 'Error al crear el rol: ' . $e->getMessage());
        }
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
    public function edit(Role $role)
    {
        $permisos = Permission::all();
        return view('roles.edit', compact('role', 'permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permission' => 'required|array',
        ]);

        try{
            DB::beginTransaction();
            $role->update(['name' => $request->name]);
            $permissions = Permission::whereIn('id', $request->permission)->get();
            $role->syncPermissions($permissions);
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente');
    }
}
