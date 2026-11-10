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
        Schema::create('repartidores', function (Blueprint $table) {
            $table->id();
            $table->string('operador');           // Base de 4 Nortes donde trabaja: "4N RM" o "4N Temuco" (igual que en coberturas).
            $table->string('nombre');             // Como viene en Geolize, columna "Nombre del repartidor". Ej: "Juan Pérez".
            $table->foreignId('proveedor_id')     // La empresa a la que pertenece: a quién se le paga.
                ->constrained('proveedores')
                ->restrictOnDelete();             // No deja borrar un proveedor que todavía tiene repartidores.
            $table->timestamps();

            $table->unique(['operador', 'nombre']); // Un repartidor no puede estar dos veces en la misma base.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repartidores');
    }
};
