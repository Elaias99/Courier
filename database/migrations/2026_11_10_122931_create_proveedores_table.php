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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('rut', 12)->unique();           // Ej: 77390761-7 (sin puntos, con guion). Uno por empresa.
            $table->string('razon_social');                // Nombre legal: a quién se le emite el pago.
            $table->string('nombre_operacional')->nullable(); // Cómo se le conoce en operaciones. Ej: "Nelson Cisterna (Arica)".
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
