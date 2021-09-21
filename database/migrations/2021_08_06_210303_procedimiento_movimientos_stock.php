<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProcedimientoMovimientosStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS movimientos_stock;

        CREATE PROCEDURE movimientos_stock (
        IN usuario_fk BIGINT,
        IN producto_fk BIGINT,
        IN producto_area_fk BIGINT,
        IN area_fk BIGINT,
        IN tipo_area_fk BIGINT,
        IN paciente_fk BIGINT,
        IN tipo_movimiento VARCHAR(100),
        IN cantidad  INT,
        IN lote  VARCHAR(100),
        IN fecha_cadu  DATETIME,
        IN created_at  DATETIME,
        IN updated_at  DATETIME
        )
        BEGIN
            INSERT INTO `movimientos`(usuario_fk, producto_fk, producto_area_fk, area_fk, tipo_area_fk, paciente_fk, tipo_movimiento, cantidad, lote, fecha_cadu, created_at, updated_at)
            VALUES (usuario_fk, producto_fk, producto_area_fk, area_fk, tipo_area_fk, paciente_fk, tipo_movimiento, cantidad, lote, fecha_cadu, created_at, updated_at);
        END
        ;
        ";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $down = "DROP PROCEDURE IF EXISTS movimientos_stock";
        DB::unprepared($down);
    }
}
