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
            $table->increments('id');
            $table->string('tarjeta');
            $table->date('fecha');
            $table->string('CVV');
            $table->decimal('fondos',10,2);
            $table->timestamps();
        });

        // Tabla objetos
        Schema::create('objetos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('producto');
            $table->decimal('costo',10,2);
            $table->timestamps();
        });

        // Tabla accesos
        Schema::create('accesos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tarjeta')->unsigned();
            $table->integer('num_intento');
            $table->foreign('id_tarjeta')->references('id')->on('tarjetas');
            $table->timestamps();
        });

        // Tabla pagos
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_objeto')->unsigned();
            $table->integer('id_tarjeta')->unsigned();
            $table->string('intentos');
            $table->string('balance');
            $table->string('estatus');
            $table->foreign('id_objeto')->references('id')->on('objetos');
            $table->foreign('id_tarjeta')->references('id')->on('tarjetas');
            $table->timestamps();  
        });

        DB::table('tarjetas')->insert([
            'tarjeta' => '1234567890123456',
            'fecha' => '2024-12-31',
            'CVV' => 123,
            'fondos' => 10000.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('objetos')->insert([
            'producto' => 'Laptop',
            'costo' => 1200.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('accesos');
        Schema::dropIfExists('objetos');
        Schema::dropIfExists('tarjetas');
    }
};
