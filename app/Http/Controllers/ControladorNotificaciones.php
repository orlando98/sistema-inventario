<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControladorNotificaciones extends Controller{


    public function notificacionesView(){
        $notificaciones = DB::table('notificaciones')->orderBy('estado','desc')->orderBy('idNotificacion','desc')->get();

        return view('notificaciones.notificaciones', compact('notificaciones'));
    }

    public function notificacionesDetailView($id){
        $consulta = DB::table('notificaciones')->where('idNotificacion', $id)->first();
        if($consulta){
            $detalle = DB::table('notificaciones')->where('idNotificacion', $id)->first();

            $productos = DB::table('notificaciones_pivote')
                ->leftJoin('producto_area','notificaciones_pivote.producto_area_fk','=','producto_area.idProductoArea')
                ->leftJoin('productos','notificaciones_pivote.producto_fk','=','productos.idProducto')
                ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                ->select(
                    'productos.idProducto as prod_id',
                    'productos.nombre as prod_nombre',
                    'productos.descripcion as prod_descripcion',
                    'tipos_area.nombre as tipo_area_nombre',
                    'areas.lugar as area_nombre',
                    'producto_area.lote as arprod_lote',
                    'producto_area.fecha_c as arprod_fecha_c'
                )
                ->orderByDesc('area_nombre')
                ->orderByDesc('tipo_area_nombre')
                ->orderByDesc('prod_nombre')
                ->orderByDesc('prod_descripcion')
                ->where('notificaciones_pivote.notificacion_fk', $id)
            ->get();

            return view('notificaciones.detail_notificacion', compact('detalle', 'productos'));
        }else{
            return view('errors.error');
        }
    }

    //##### FETCH
    public function notificacionLeida(Request $request){
        DB::table('notificaciones')->where('idNotificacion', $request->id)->update([
            'estado' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json('notificación leida con exito.');
    }
}
