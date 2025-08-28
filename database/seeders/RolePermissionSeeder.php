<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'administrador']);
        $guestRole = Role::firstOrCreate(['name' => 'invitado']);

        $permisos = [
            'ver categorias',
            'crear categorias',
            'editar categorias',
            'eliminar categorias',
            'ver marcas',
            'crear marcas',
            'editar marcas',
            'eliminar marcas',
            'ver equipos',
            'crear equipos',
            'editar equipos',
            'eliminar equipos',
            'ver solicitudes',
            'crear solicitudes',
            'editar solicitudes',
            'eliminar solicitudes',
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'ver roles',
            'crear roles',
            'editar roles',
            'eliminar roles',
            'ver prestamos',
            'crear prestamos',
            'editar prestamos',
            'eliminar prestamos',
            'ver secciones',
            'crear secciones',
            'editar secciones',
            'eliminar secciones',
            'ver departamentos',
            'crear departamentos',
            'editar departamentos',
            'eliminar departamentos',
            'ver lotes',
            'crear lotes',
            'editar lotes',
            'eliminar lotes',
            'ver inventario',
            'crear inventario',
            'editar inventario',
            'eliminar inventario',
            'ver solicitudes',
            'crear solicitudes',
            'editar solicitudes',
            'eliminar solicitudes',
            'ver mis solicitudes',
            'ver mis prestamos',
            'gestionar solicitudes',

        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name' => $permiso,
                'guard_name' => 'web',
            ]);
        }

        $adminRole->givePermissionTo(Permission::all());
        $guestRole->syncPermissions([
            'ver mis solicitudes',
            'ver mis prestamos',
            'crear solicitudes',
        ]);
    }
}
