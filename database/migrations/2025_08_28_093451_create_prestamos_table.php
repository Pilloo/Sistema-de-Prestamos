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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_solicitante')->constrained('users');
            $table->foreignId('id_aprobador')->nullable()->constrained('users');
            $table->foreignId('id_estado_prestamo')->constrained('estado_prestamos');
            $table->foreignId('id_solicitud')->constrained('solicitud_prestamos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
