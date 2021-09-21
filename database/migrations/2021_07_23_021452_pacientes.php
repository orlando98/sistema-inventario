<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('idPaciente');
            $table->string('cedula')->nullable();
            $table->text('nombres')->nullable();
            $table->text('apellidos')->nullable();
            $table->integer('edad')->nullable();
            $table->integer('genero')->nullable()->comment('1 Masculino, 2 Femenino, 3 Otros');
            $table->integer('estado')->default(1)->nullable()->comment('0 Bloqueado , 1 Activo, 66 Eliminado');
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('area_fk');
            $table->foreign('area_fk')->references('idArea')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('pacientes');
    }
}
