<?php

namespace Database\Seeders;

use App\Models\EstadoPrestamo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoPrestamoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            'Prestado / Entregado',
            'Devuelto',
            'Atrasado / Vencido',
            'Perdido',
            'Dañado',
        ];

        foreach ($estados as $estado) {
            EstadoPrestamo::firstOrCreate(['nombre' => $estado]);
        }
    }
}
