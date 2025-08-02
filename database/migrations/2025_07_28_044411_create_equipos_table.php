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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo',128)->nullable();
            $table->string('numero_serie',128)->nullable();
            $table->string('contenido_etiqueta',128)->nullable();
            $table->string('detalle',512)->nullable();
            $table->integer('cantidad_total')->unsigned();
            $table->integer('cantidad_disponible')->unsigned();
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade')->nullable();
            $table->foreignId('estado_equipo_id')->constrained('estado_equipos')->onDelete('cascade');
            $table->string('img_path',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
