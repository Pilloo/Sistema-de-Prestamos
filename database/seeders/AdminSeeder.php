<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'primer_apellido' => 'Principal',
            'segundo_apellido' => 'Sistema',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('admin1234'),
            'seccion_id' => null,
            'departamento_id' => null,
        ]);

        $role = Role::firstOrCreate(['name' => 'admin']);

        $permissions = Permission::pluck('name');
        if ($permissions->isEmpty()) {
            $defaultPermissions = [
                'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
                'ver roles', 'crear roles', 'editar roles', 'eliminar roles',
                'ver equipos', 'crear equipos', 'editar equipos', 'eliminar equipos',
                'ver prestamos', 'crear prestamos', 'editar prestamos', 'eliminar prestamos',
                'ver lotes', 'crear lotes', 'editar lotes', 'eliminar lotes',
                'ver inventario', 'crear inventario', 'editar inventario', 'eliminar inventario',
                'ver solicitudes', 'crear solicitudes', 'editar solicitudes', 'eliminar solicitudes',
                'ver mis solicitudes', 'ver mis prestamos', 'gestionar solicitudes',
            ];
            foreach ($defaultPermissions as $perm) {
                Permission::firstOrCreate(['name' => $perm]);
            }
            $permissions = Permission::pluck('name');
        }
        $role->syncPermissions($permissions);

        $admin->assignRole($role);
    }
}
