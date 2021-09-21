<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Areas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('areas', function (Blueprint $table) {
            $table->id('idArea');
            $table->text('lugar')->nullable();

            $table->integer('estado')->nullable()->comment('0 Inactivo , 1 Activo, 66 Eliminado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('areas');
    }
}
