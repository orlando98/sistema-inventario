<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->text('nombres')->nullable();
            $table->text('apellidos')->nullable();
            $table->integer('genero')->nullable()->comment('1 Masculino, 2 Femenino, 3 Otros');
            $table->integer('estado')->default(1)->nullable()->comment('0 Bloqueado , 1 Activo, 66 Eliminado');
            $table->enum('rol', ['Administrador', 'Centro', 'Clinica', 'Usuario'])->default('Usuario');

            $table->string('username')->unique()->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('token', 20)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
