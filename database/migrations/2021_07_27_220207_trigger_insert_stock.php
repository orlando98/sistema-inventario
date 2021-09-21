<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerInsertStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        // DB::unprepared('DROP TRIGGER IF EXISTS `stockProducto`');
        // DB::unprepared('CREATE TRIGGER stockProducto AFTER INSERT ON `producto_area` FOR EACH ROW
        //     BEGIN
        //         INSERT INTO `movimientos`(`tipo_movimiento`, `tipo_area_fk`, `producto_fk`, `usuario_fk`, `created_at`)
        //         VALUES (tipo_movimiento, tipo_area_fk, producto_fk, usuario_fk,  created_at);
        //     END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::unprepared('DROP TRIGGER IF EXISTS `stockProducto`');
    }
}
