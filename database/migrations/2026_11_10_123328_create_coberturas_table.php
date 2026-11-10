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
        Schema::create('coberturas', function (Blueprint $table) {
            $table->id();
            $table->string('comuna')->unique();   // Ej: "Osorno". Cada comuna la atiende un solo operador.
            $table->string('operador');           // Quién la atiende en operaciones. Ej: "4N RM", "4N Temuco", "Johanna Oyarzo (Osorno)".
                                                  // Si es "4N RM" o "4N Temuco", después hay que mirar el repartidor.
            $table->foreignId('proveedor_id')     // A quién se le paga (apunta a la tabla proveedores).
                ->constrained('proveedores')
                ->restrictOnDelete();             // No deja borrar un proveedor que todavía atiende comunas.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coberturas');
    }
};
