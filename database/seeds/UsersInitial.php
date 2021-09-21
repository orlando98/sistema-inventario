<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersInitial extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date_actual = date('Y-m-d H:i:s');
        $password = '123456';

        DB::table('users')->insert([
            'rol' => "Administrador",
            'nombres' => "Administrador",
            'email' => 'administrador@gmail.com',
            'username' => "Administrador",
            'token' => Str::random(20),
            'password' => Hash::make($password),
            'created_at' =>  $date_actual
        ]);

        DB::table('users')->insert([
            'rol' => "Centro",
            'nombres' => "Centro",
            'email' => 'centro@gmail.com',
            'username' => "Centro",
            'token' => Str::random(20),
            'password' => Hash::make($password),
            'created_at' =>  $date_actual
        ]);

        DB::table('users')->insert([
            'rol' => "Clinica",
            'nombres' => "Clinica",
            'email' => 'clinica@gmail.com',
            'username' => "Clinica",
            'token' => Str::random(20),
            'password' => Hash::make($password),
            'created_at' =>  $date_actual
        ]);

        DB::table('users')->insert([
            'rol' => "Usuario",
            'nombres' => "Usuario",
            'email' => 'usuario@gmail.com',
            'username' => "Usuario",
            'token' => Str::random(20),
            'password' => Hash::make($password),
            'created_at' =>  $date_actual
        ]);
    }
}
