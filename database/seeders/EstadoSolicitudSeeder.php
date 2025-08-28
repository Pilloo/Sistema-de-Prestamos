<?php

namespace Database\Seeders;

use App\Models\EstadoSolicitud;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            'Pendiente',
            'Aprobado',
            'Rechazado',
        ];

        foreach ($estados as $estado) {
            EstadoSolicitud::firstOrCreate(['nombre' => $estado]);
        }
    }
}
