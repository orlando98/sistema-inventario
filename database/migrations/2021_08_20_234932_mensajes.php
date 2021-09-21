<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mensajes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sala', function (Blueprint $table) {
            $table->id('idSala');
            $table->text('key_firebase')->nullable();

            $table->unsignedBigInteger('usuario_1')->nullable();
            $table->unsignedBigInteger('usuario_2')->nullable();

            $table->foreign('usuario_1')->references('id')->on('users');
            $table->foreign('usuario_2')->references('id')->on('users');

            $table->integer('estado')->default(1)->nullable()->comment('1 Activo, 0 Eliminado');

            $table->timestamps();
        });

        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('idMensaje');
            $table->text('mensaje')->nullable();
            $table->unsignedBigInteger('usuario_fk')->nullable();
            $table->unsignedBigInteger('sala_fk')->nullable();
            $table->text('key_sala_fk')->nullable();

            $table->foreign('usuario_fk')->references('id')->on('users');
            $table->foreign('sala_fk')->references('idSala')->on('sala');
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
        Schema::dropIfExists('sala');
        Schema::dropIfExists('mensajes');
    }
}
