<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('idNotificacion');

            $table->string('titulo')->nullable();
            $table->text('detalle')->nullable();

            $table->integer('estado')->default(1)->nullable()->comment('1 No leido, 0 Leido');

            $table->unsignedBigInteger('usuario_fk')->nullable();
            $table->foreign('usuario_fk')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('notificaciones_pivote', function (Blueprint $table) {
            $table->id('idPivote');

            $table->unsignedBigInteger('notificacion_fk')->nullable();
            $table->unsignedBigInteger('producto_fk')->nullable();
            $table->unsignedBigInteger('producto_area_fk')->nullable();

            $table->foreign('notificacion_fk')->references('idNotificacion')->on('notificaciones');
            $table->foreign('producto_fk')->references('producto_fk')->on('producto_area');
            $table->foreign('producto_area_fk')->references('idProductoArea')->on('producto_area');

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
        Schema::dropIfExists('notificaciones_pivote');
        Schema::dropIfExists('notificaciones');
    }
}
