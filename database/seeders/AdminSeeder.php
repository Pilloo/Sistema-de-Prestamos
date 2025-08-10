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
        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'primer_apellido' => 'Principal',
            'segundo_apellido' => 'Sistema',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('admin1234'),
            'seccion_id' => null,
            'departamento_id' => null,
        ]);

        // Crear rol admin si no existe
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Crear todos los permisos si no existen y asignar al rol
        $permissions = Permission::pluck('name');
        if ($permissions->isEmpty()) {
            $defaultPermissions = [
                'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
                'ver roles', 'crear roles', 'editar roles', 'eliminar roles',
                'ver equipos', 'crear equipos', 'editar equipos', 'eliminar equipos',
                'ver prestamos', 'crear prestamos', 'editar prestamos', 'eliminar prestamos',
                // Agrega aquí todos los permisos que tu sistema requiera
            ];
            foreach ($defaultPermissions as $perm) {
                Permission::firstOrCreate(['name' => $perm]);
            }
            $permissions = Permission::pluck('name');
        }
        $role->syncPermissions($permissions);

        // Asignar rol admin al usuario
        $admin->assignRole($role);
    }
}
