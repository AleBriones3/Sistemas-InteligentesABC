<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla tarjetas
        Schema::create('tarjetas', function (Blueprint $table) {
            $table->id();
            $table->string('tarjeta');
            $table->date('fecha');
            $table->integer('CVV');
            $table->decimal('fondos');
        });

        // Tabla objetos
        Schema::create('objetos', function (Blueprint $table) {
            $table->id();
            $table->string('producto');
            $table->decimal('costo');
        });

        // Tabla accesos
        Schema::create('accesos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tarjeta');
            $table->integer('num_intento');
        });

        // Tabla pagos
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_objeto');
            $table->integer('id_tarjeta');
            $table->string('intentos');
            $table->string('balance');
            $table->string('estatus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('accesos');
        Schema::dropIfExists('objetos');
        Schema::dropIfExists('tarjetas');
    }
};
