<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lote_equipos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo',128)->nullable();
            $table->string('contenido_etiqueta',128)->nullable();
            $table->string('detalle',512)->nullable();
            $table->integer('cantidad_total')->unsigned();
            $table->integer('cantidad_disponible')->unsigned();
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade')->nullable();
            $table->string('img_path',255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lote_equipos');
    }
};