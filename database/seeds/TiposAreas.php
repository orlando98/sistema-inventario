<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposAreas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date_actual = date('Y-m-d H:i:s');

        //CLINICA
        DB::table('tipos_area')->insert([
            'area_fk' => 1,
            'nombre' => "Medicina general",
            'created_at' =>  $date_actual
        ]);

        DB::table('tipos_area')->insert([
            'area_fk' => 1,
            'nombre' => "Insumos médicos",
            'created_at' =>  $date_actual
        ]);

        DB::table('tipos_area')->insert([
            'area_fk' => 1,
            'nombre' => "Insumos de diálisis",
            'created_at' =>  $date_actual
        ]);

        DB::table('tipos_area')->insert([
            'area_fk' => 1,
            'nombre' => "Insumos oficina",
            'created_at' =>  $date_actual
        ]);

        DB::table('tipos_area')->insert([
            'area_fk' => 1,
            'nombre' => "Insumos limpieza",
            'created_at' =>  $date_actual
        ]);

        DB::table('tipos_area')->insert([
            'area_fk' => 1,
            'nombre' => "Bodega",
            'created_at' =>  $date_actual
        ]);

        //CENTRO
        DB::table('tipos_area')->insert([
            'area_fk' => 2,
            'nombre' => "Medicina general",
            'created_at' =>  $date_actual
        ]);
        DB::table('tipos_area')->insert([
            'area_fk' => 2,
            'nombre' => 'Insumos médicos',
            'created_at' =>  $date_actual
        ]);
        DB::table('tipos_area')->insert([
            'area_fk' => 2,
            'nombre' => "Insumos limpieza",
            'created_at' =>  $date_actual
        ]);
        DB::table('tipos_area')->insert([
            'area_fk' => 2,
            'nombre' => "Bodega",
            'created_at' =>  $date_actual
        ]);

    }
}
