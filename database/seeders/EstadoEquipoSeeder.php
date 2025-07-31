<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoEquipo;

class EstadoEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            'Disponible',
            'En préstamo',
            'Reservado',
            'En mantenimiento',
            'Dañado / Reportado con fallas',
            'Extraviado / No devuelto',
            'Baja / Retirado',
        ];

        foreach ($estados as $estado) {
            EstadoEquipo::firstOrCreate(['nombre' => $estado]);
        }
    }
}
