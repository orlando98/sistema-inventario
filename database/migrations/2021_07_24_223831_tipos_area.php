<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TiposArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('tipos_area', function (Blueprint $table) {
            $table->id('idTipo');

            $table->text('nombre')->nullable();

            $table->unsignedBigInteger('area_fk')->nullable();
            $table->foreign('area_fk')->references('idArea')->on('areas');

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
        Schema::dropIfExists('tipos_area');
    }
}
