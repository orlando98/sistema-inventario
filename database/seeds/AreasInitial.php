<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasInitial extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->insert([
            'lugar' => 'Clínica',
            'estado' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('areas')->insert([
            'lugar' => 'Centro',
            'estado' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
