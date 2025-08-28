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
        Schema::create('equipo_solicitud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_solicitud')->constrained('solicitud_prestamos')->onDelete('cascade');
            $table->foreignId('id_equipo')->constrained('equipos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_solicitud');
    }
};
