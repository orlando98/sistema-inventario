<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacientesFake extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date_actual = date('Y-m-d H:i:s');

        for ($i=1; $i <= 20 ; $i++) {
            DB::table('pacientes')->insert([
                // 'cedula' => "12345678$i",
                'area_fk' => random_int(1, 2),
                'nombres' => "Paciente $i",
                'apellidos' => "Apellido $i",
                'created_at' =>  $date_actual
            ]);
        }
    }
}
