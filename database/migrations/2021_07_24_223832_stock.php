<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Stock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('producto_area', function (Blueprint $table) {
            $table->id('idProductoArea');

            $table->integer('stock')->nullable(); //cantidad
            $table->dateTime('fecha_c')->nullable();
            $table->string('lote')->nullable();

            $table->unsignedBigInteger('producto_fk')->nullable();
            $table->unsignedBigInteger('tipo_area_fk')->nullable();
            $table->unsignedBigInteger('area_fk')->nullable();
            $table->unsignedBigInteger('usuario_fk')->nullable();

            $table->foreign('producto_fk')->references('idProducto')->on('productos');
            $table->foreign('tipo_area_fk')->references('idTipo')->on('tipos_area');
            $table->foreign('area_fk')->references('idArea')->on('areas');

            $table->foreign('usuario_fk')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('producto_area');
    }
}
