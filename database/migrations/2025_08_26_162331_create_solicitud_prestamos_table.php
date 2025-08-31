<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitud_prestamos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_solicitud');
            $table->date('fecha_limite_solicitada');
            $table->string('detalle',255);
            $table->foreignId('id_solicitante')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_estado_solicitud')->constrained('estado_solicitudes');

            // Campos de préstamo
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_devolucion')->nullable();
            $table->string('observaciones_entrega',255)->nullable();
            $table->string('observaciones_devolucion',255)->nullable();
            $table->foreignId('id_tecnico_aprobador')->nullable()->constrained('users');
            $table->string('estado_prestamo', 50)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_prestamos');
    }
};
