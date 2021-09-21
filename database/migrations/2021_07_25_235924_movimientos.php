<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Movimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id('idMovimiento');

            $table->text('tipo_movimiento')->nullable();
            $table->unsignedBigInteger('usuario_fk')->nullable();
            $table->unsignedBigInteger('producto_fk')->nullable();
            $table->unsignedBigInteger('producto_area_fk')->nullable();
            $table->unsignedBigInteger('area_fk')->nullable();
            $table->unsignedBigInteger('tipo_area_fk')->nullable();
            $table->unsignedBigInteger('paciente_fk')->nullable();

            $table->integer('cantidad')->nullable();
            $table->string('lote')->nullable();
            $table->dateTime('fecha_cadu')->nullable();

            $table->foreign('usuario_fk')->references('id')->on('users');
            $table->foreign('producto_fk')->references('idProducto')->on('productos');
            $table->foreign('producto_area_fk')->references('idProductoArea')->on('producto_area');


            $table->foreign('area_fk')->references('idArea')->on('areas');
            $table->foreign('tipo_area_fk')->references('idTipo')->on('tipos_area');
            $table->foreign('paciente_fk')->references('idPaciente')->on('pacientes');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
}
