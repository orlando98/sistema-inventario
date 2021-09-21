<?php

namespace App\Http\Controllers;

use Movimientos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControladorReporte extends Controller
{
    //###################### REPORTE INGRESO ######################
        public function reporteIngresoView(){

            if(Auth::user()->rol == "Administrador"){
                $areas = DB::table('areas')->where('estado', [1])->get();
                $tipos = DB::table('tipos_area')->get();

                return view('reportes.general.reporteIngreso', compact('areas', 'tipos'));
            }

            if(Auth::user()->rol == "Clinica"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', 'Clínica')->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , [1])->get();

                return view('reportes.clinica.reporteClinicaIngreso', compact('areas', 'tipos'));
            }

            if(Auth::user()->rol == "Centro"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', 'Centro')->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , [2])->get();

                return view('reportes.centro.reporteCentroIngreso', compact('areas', 'tipos'));
            }

            if(Auth::user()->rol == "" || Auth::user()->rol == "Usuario" ){
                return view('errors.error');
            }
        }

        //ADMIN
        public function filtroMovimientosIngresoReporte($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento',['INGRESO'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            elseif($area == 'Clínica'){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            elseif($area == 'Centro'){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            return response()->json($movimientos);
        }

        public function reporteIngresoPDF($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:01' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));
            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    // ->leftJoin('producto_area', 'movimientos.producto_fk', '=', 'producto_area.producto_fk')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            elseif($area == 'Clínica'){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    // ->leftJoin('producto_area', 'movimientos.producto_fk', '=', 'producto_area.producto_fk')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            elseif($area == 'Centro'){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    // ->leftJoin('producto_area', 'movimientos.producto_fk', '=', 'producto_area.producto_fk')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $area = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    // ->leftJoin('producto_area', 'movimientos.producto_fk', '=', 'producto_area.producto_fk')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('movimientos.area_fk', $area)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteIngresoPDF',[
                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'movimientos' => $movimientos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("MOVIMIENTO INGRESOS.pdf");
        }

        //CLINICA
        public function filtroMovimientosIngresoReporteCLINICA($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    // ->leftJoin('producto_area', 'movimientos.producto_fk', '=', 'producto_area.producto_fk')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            return response()->json($movimientos);
        }

        public function reporteIngresoPDFCLINICA($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    // ->leftJoin('producto_area', 'movimientos.producto_fk', '=', 'producto_area.producto_fk')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteIngresoPDF',[
                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'movimientos' => $movimientos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("MOVIMIENTO INGRESOS CLÍNICA.pdf");
        }

        //CENTRO
        public function filtroMovimientosIngresoReporteCENTRO($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    // ->leftJoin('producto_area', 'movimientos.producto_fk', '=', 'producto_area.producto_fk')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            return response()->json($movimientos);
        }

        public function reporteIngresoPDFCENTRO($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_ingreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo'
                    )
                    ->where('movimientos.tipo_movimiento', ['INGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteIngresoPDF',[
                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'movimientos' => $movimientos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("MOVIMIENTO INGRESOS CENTRO.pdf");
        }

    //#############################################################

    //###################### REPORTE EGRESO #######################
        public function reporteEgresoView(){
            if(Auth::user()->rol == "Administrador"){
                $areas = DB::table('areas')->where('estado', [1])->get();
                $tipos = DB::table('tipos_area')->get();

                return view('reportes.general.reporteEgreso', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol == "Clinica"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', ['Clínica'])->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , $areas->idArea)->get();

                return view('reportes.clinica.reporteEgresoClinica', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol == "Centro"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', ['Centro'])->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , $areas->idArea)->get();

                return view('reportes.centro.reporteEgresoCentro', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol != "Administrador" || Auth::user()->rol == "Clinica" || Auth::user()->rol == "Centro"){
                return view('errors.error');
            }
        }

        //ADMIN
        public function filtroMovimientosEgresoReporte($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento',['EGRESO'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            elseif($area == 'Clínica'){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            elseif($area == 'Centro'){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            return response()->json($movimientos);
        }

        public function reporteEgresoPDF($fecha_inicio, $fecha_fin, $area, $pacientes){
            $fecha_i = date('Y-m-d 00:00:01' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();

                $repetidos = DB::table('movimientos')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->select(
                        'productos.nombre as producto_nombre',
                        DB::raw("sum(movimientos.cantidad) as cantidad_total")
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->groupBy('producto_nombre')
                ->get();
            }
            elseif($area == 'Clínica'){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();

                $repetidos = DB::table('movimientos')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->select(
                        'productos.nombre as producto_nombre',
                        DB::raw("sum(movimientos.cantidad) as cantidad_total")
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->groupBy('producto_nombre')
                ->get();
            }
            elseif($area == 'Centro'){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();

                $repetidos = DB::table('movimientos')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->select(
                        'productos.nombre as producto_nombre',
                        DB::raw("sum(movimientos.cantidad) as cantidad_total")
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->groupBy('producto_nombre')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $area = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('movimientos.area_fk', $area)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();

                $repetidos = DB::table('movimientos')
                ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                ->select(
                    'productos.nombre as producto_nombre',
                    DB::raw("sum(movimientos.cantidad) as cantidad_total")
                )
                ->where('movimientos.tipo_movimiento', ['EGRESO'])
                ->where('movimientos.area_fk', $area)
                ->where('movimientos.tipo_area_fk', $tipo_area)
                ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                ->groupBy('producto_nombre')
            ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteEgresoPDF',[
                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'movimientos' => $movimientos,
                'pacientes' => $pacientes,
                'repetidos' => $repetidos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("MOVIMIENTO EGRESOS.pdf");
        }

        //CLINICA
        public function filtroMovimientosEgresoReporteCLINICA($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    // ->leftJoin('producto_area', 'movimientos.producto_fk', '=', 'producto_area.producto_fk')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            return response()->json($movimientos);
        }

        public function reporteEgresoPDFCLINICA($fecha_inicio, $fecha_fin, $area, $pacientes){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();

                $repetidos = DB::table('movimientos')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->select(
                        'productos.nombre as producto_nombre',
                        DB::raw("sum(movimientos.cantidad) as cantidad_total")
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->groupBy('producto_nombre')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();

                $repetidos = DB::table('movimientos')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->select(
                        'productos.nombre as producto_nombre',
                        DB::raw("sum(movimientos.cantidad) as cantidad_total")
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->groupBy('producto_nombre')
                ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteEgresoPDF',[
                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'movimientos' => $movimientos,
                'pacientes' => $pacientes,
                'repetidos' => $repetidos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("MOVIMIENTO EGRESOS CLÍNICA.pdf");
        }

        //CENTRO
        public function filtroMovimientosEgresoReporteCENTRO($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();
            }

            return response()->json($movimientos);
        }

        public function reporteEgresoPDFCENTRO($fecha_inicio, $fecha_fin, $area, $pacientes){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();

                $repetidos = DB::table('movimientos')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->select(
                        'productos.nombre as producto_nombre',
                        DB::raw("sum(movimientos.cantidad) as cantidad_total")
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->groupBy('producto_nombre')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $movimientos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->select(
                        'movimientos.*',
                        'movimientos.created_at as fecha_egreso',
                        'movimientos.fecha_cadu',

                        'users.nombres as usuario_nombre',
                        'users.apellidos as usuario_apellido',
                        'users.username as usuario_username',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        'pacientes.nombres as paciente_nombre',
                        'pacientes.apellidos as paciente_apellido'
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('area_nombre')
                    ->orderBy('area_tipo')
                ->get();

                $repetidos = DB::table('movimientos')
                    ->leftJoin('productos', 'movimientos.producto_fk', '=', 'productos.idProducto')
                    ->leftJoin('areas', 'movimientos.area_fk', '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk', '=', 'tipos_area.idTipo')
                    ->select(
                        'productos.nombre as producto_nombre',
                        DB::raw("sum(movimientos.cantidad) as cantidad_total")
                    )
                    ->where('movimientos.tipo_movimiento', ['EGRESO'])
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->groupBy('producto_nombre')
                ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteEgresoPDF',[
                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'movimientos' => $movimientos,
                'pacientes' => $pacientes,
                'repetidos' => $repetidos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("MOVIMIENTO EGRESOS CENTRO.pdf");
        }

    //#############################################################

    //###################### REPORTE STOCK ########################
        public function reporteStockView(){
            if(Auth::user()->rol == "Administrador"){
                $areas = DB::table('areas')->where('estado', [1])->get();
                $tipos = DB::table('tipos_area')->get();

                return view('reportes.general.reporteStock', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol == "Clinica"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', ['Clínica'])->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , $areas->idArea)->get();

                return view('reportes.clinica.reporteStockClinica', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol == "Centro"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', ['Centro'])->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , $areas->idArea)->get();

                return view('reportes.centro.reporteStockCentro', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol == "" || Auth::user()->rol == "Usuario"){
                return view('errors.error');
            }
        }

        //ADMIN
        public function filtroStockReporte($area){
            if($area == "todas"){
                
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();

            }
            elseif($area == 'Clínica'){
                
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('areas.lugar' , ['Clínica'])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            elseif($area == 'Centro'){
                
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('areas.lugar' , ['Centro'])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('producto_area.area_fk', $areas)
                    ->where('producto_area.tipo_area_fk', $tipo_area)
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();

            }

            return response()->json($productos);
        }

        public function reporteStockPDF($area){
            if($area == "todas"){
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            elseif($area == 'Clínica'){
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('areas.lugar' , ['Clínica'])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            elseif($area == 'Centro'){
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('areas.lugar' , ['Centro'])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('producto_area.area_fk', $areas)
                    ->where('producto_area.tipo_area_fk', $tipo_area)
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteStockPDF',[
                'productos' => $productos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("REPORTE DE STOCK DE PRODUCTOS.pdf");
        }

        //CLINICA
        public function filtroStockReporteCLINICA($area){
            if($area == "todas"){
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar' , ['Clínica'])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('producto_area.area_fk', $areas)
                    ->where('producto_area.tipo_area_fk', $tipo_area)
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }

            return response()->json($productos);
        }

        public function reporteStockPDFCLINICA($area){
            if($area == "todas"){
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar' , ['Clínica'])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('producto_area.area_fk', $areas)
                    ->where('producto_area.tipo_area_fk', $tipo_area)
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteStockPDF',[
                'productos' => $productos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("REPORTE DE STOCK DE PRODUCTOS.pdf");
        }

        //CENTRO
        public function filtroStockReporteCENTRO($area){
            if($area == "todas"){
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar' , ['Centro'])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('producto_area.area_fk', $areas)
                    ->where('producto_area.tipo_area_fk', $tipo_area)
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            return response()->json($productos);
        }

        public function reporteStockPDFCENTRO($area){
            if($area == "todas"){
                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar' , ['Centro'])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo

                $productos = DB::table('producto_area')
                    ->leftJoin('productos', 'producto_area.producto_fk' , '=', 'productos.idProducto')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'producto_area.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->select(
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_cadu',
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->havingRaw('sum(producto_area.stock) > ?', [0])
                    ->where('producto_area.area_fk', $areas)
                    ->where('producto_area.tipo_area_fk', $tipo_area)
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('fecha_cadu')
                    ->groupBy('lote')
                ->get();
            }

            $pdf = \PDF::loadView('reportes.pdf.reporteStockPDF',[
                'productos' => $productos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("REPORTE DE STOCK DE PRODUCTOS.pdf");
        }
    //#############################################################

    //###################### REPORTE KARDEX ########################
        public function reporteKardexView(){
            if(Auth::user()->rol == "Administrador"){
                $areas = DB::table('areas')->where('estado', [1])->get();
                $tipos = DB::table('tipos_area')->get();

                return view('reportes.general.reporteKardex', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol == "Clinica"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', ['Clínica'])->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , $areas->idArea)->get();

                return view('reportes.clinica.reporteClinicaKardex', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol == "Centro"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', ['Centro'])->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , $areas->idArea)->get();

                return view('reportes.centro.reporteCentroKardex', compact('areas' , 'tipos'));
            }

            if(Auth::user()->rol == "" || Auth::user()->rol == "Usuario"){
                return view('errors.error');
            }
        }

        //ADMIN
        public function filtroKardexReporte($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $añadir = "";
                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();

            }
            elseif($area == 'Clínica'){
                $añadir = "areas.lugar = 'Clínica' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();

            }
            elseif($area == 'Centro'){
                $añadir = "areas.lugar = 'Centro' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();

            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo
                
                $añadir = "movimientos.area_fk = '$areas' and movimientos.tipo_area_fk = '$tipo_area' and ";
                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();


            }
            $ingreso = $añadir."movimientos.tipo_movimiento = 'INGRESO' and movimientos.created_at < '$fecha_i'";
            $egreso = $añadir."movimientos.tipo_movimiento = 'EGRESO' and movimientos.created_at < '$fecha_i'";

            $productos_ingresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($ingreso)
            ->first();

            $productos_egresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($egreso)
            ->first();

            $total = $productos_ingresos->sum_cantidad - $productos_egresos->sum_cantidad;

            return response()->json(["productos" => $productos, "total_anterior" => $total]);
        }

        public function reporteKardexPDF($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));

            if($area == "todas"){
                $añadir = "";
                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();

            }
            elseif($area == 'Clínica'){
                $añadir = "areas.lugar = 'Clínica' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();

            }
            elseif($area == 'Centro'){
                $añadir = "areas.lugar = 'Centro' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();

            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo
                
                $añadir = "movimientos.area_fk = '$areas' and movimientos.tipo_area_fk = '$tipo_area' and ";
                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();


            }
            $ingreso = $añadir."movimientos.tipo_movimiento = 'INGRESO' and movimientos.created_at < '$fecha_i'";
            $egreso = $añadir."movimientos.tipo_movimiento = 'EGRESO' and movimientos.created_at < '$fecha_i'";

            $productos_ingresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($ingreso)
            ->first();

            $productos_egresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($egreso)
            ->first();

            $total = $productos_ingresos->sum_cantidad - $productos_egresos->sum_cantidad;


            $pdf = \PDF::loadView('reportes.pdf.reporteKardexPDF',[

                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'total' => $total,
                'productos' => $productos
            ])
            ->setPaper('A4', 'landscape'); //horizontal
            
            return $pdf->stream("REPORTE DE STOCK DE PRODUCTOS.pdf");
        }

        //CLINICA
        public function filtroKardexReporteCLINICA($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));
            if($area == "todas"){
                $añadir = "areas.lugar = 'Clínica' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();

            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo
                $añadir = "movimientos.area_fk = '$areas' and movimientos.tipo_area_fk = '$tipo_area' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();
            }

            $ingreso = $añadir."movimientos.tipo_movimiento = 'INGRESO' and movimientos.created_at < '$fecha_i'";
            $egreso = $añadir."movimientos.tipo_movimiento = 'EGRESO' and movimientos.created_at < '$fecha_i'";

            $productos_ingresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($ingreso)
            ->first();

            $productos_egresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($egreso)
            ->first();

            $total = $productos_ingresos->sum_cantidad - $productos_egresos->sum_cantidad;

            return response()->json(["productos" => $productos, "total_anterior" => $total]);
        }

        public function reporteKardexPDFCLINICA($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));
            if($area == "todas"){
                $añadir = "areas.lugar = 'Clínica' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();

            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo
                $añadir = "movimientos.area_fk = '$areas' and movimientos.tipo_area_fk = '$tipo_area' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();
            }

            $ingreso = $añadir."movimientos.tipo_movimiento = 'INGRESO' and movimientos.created_at < '$fecha_i'";
            $egreso = $añadir."movimientos.tipo_movimiento = 'EGRESO' and movimientos.created_at < '$fecha_i'";

            $productos_ingresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($ingreso)
            ->first();

            $productos_egresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($egreso)
            ->first();

            $total = $productos_ingresos->sum_cantidad - $productos_egresos->sum_cantidad;


            $pdf = \PDF::loadView('reportes.pdf.reporteKardexPDF',[
                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'total' => $total,
                'productos' => $productos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("REPORTE DE STOCK DE PRODUCTOS.pdf");
        }

        //CENTRO
        public function filtroKardexReporteCENTRO($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));
            if($area == "todas"){
                $añadir = "areas.lugar = 'Centro' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo
                $añadir = "movimientos.area_fk = '$areas' and movimientos.tipo_area_fk = '$tipo_area' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();
            }

            $ingreso = $añadir."movimientos.tipo_movimiento = 'INGRESO' and movimientos.created_at < '$fecha_i'";
            $egreso = $añadir."movimientos.tipo_movimiento = 'EGRESO' and movimientos.created_at < '$fecha_i'";

            $productos_ingresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($ingreso)
            ->first();

            $productos_egresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($egreso)
            ->first();

            $total = $productos_ingresos->sum_cantidad - $productos_egresos->sum_cantidad;
            return response()->json(["productos" => $productos, "total_anterior" => $total]);

            // return response()->json($productos);
        }

        public function reporteKardexPDFCENTRO($fecha_inicio, $fecha_fin, $area){
            $fecha_i = date('Y-m-d 00:00:00' , strtotime($fecha_inicio));
            $fecha_f = date('Y-m-d 23:59:59' , strtotime($fecha_fin));
            if($area == "todas"){
                $añadir = "areas.lugar = 'Centro' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('areas.lugar' , ['Centro'])
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();
            }
            else{
                $receptor_area = explode("-", $area);
                $areas = $receptor_area[0]; //idArea
                $tipo_area = $receptor_area[1]; //idTipo
                $añadir = "movimientos.area_fk = '$areas' and movimientos.tipo_area_fk = '$tipo_area' and ";

                $productos = DB::table('movimientos')
                    ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                    ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                    ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                    ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                    ->select(
                        "users.username as username",
                        "productos.nombre as producto",
                        "areas.lugar as area",
                        "tipos_area.nombre as tipo_area",
                        "pacientes.nombres as paciente_nombres",
                        "pacientes.apellidos as paciente_apelldios",
                        "movimientos.*"
                    )
                    ->where('movimientos.area_fk', $areas)
                    ->where('movimientos.tipo_area_fk', $tipo_area)
                    ->whereBetween('movimientos.created_at' , [$fecha_i, $fecha_f])
                    ->orderBy('movimientos.created_at')
                ->get();
            }

            $ingreso = $añadir."movimientos.tipo_movimiento = 'INGRESO' and movimientos.created_at < '$fecha_i'";
            $egreso = $añadir."movimientos.tipo_movimiento = 'EGRESO' and movimientos.created_at < '$fecha_i'";

            $productos_ingresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($ingreso)
            ->first();

            $productos_egresos = DB::table('movimientos')
                ->leftJoin('users', 'movimientos.usuario_fk' , '=', 'users.id')
                ->leftJoin('areas', 'movimientos.area_fk' , '=', 'areas.idArea')
                ->leftJoin('tipos_area', 'movimientos.tipo_area_fk' , '=', 'tipos_area.idTipo')
                ->leftJoin('pacientes', 'movimientos.paciente_fk' , '=', 'pacientes.idPaciente')
                ->leftJoin('productos', 'movimientos.producto_fk' , '=', 'productos.idProducto')
                ->select(
                    DB::raw("ifnull(SUM(movimientos.cantidad), 0) as sum_cantidad")
                )
                ->whereRaw($egreso)
            ->first();

            $total = $productos_ingresos->sum_cantidad - $productos_egresos->sum_cantidad;

            $pdf = \PDF::loadView('reportes.pdf.reporteKardexPDF',[                      
                'fecha_inicio' => $fecha_i,
                'fecha_fin' => $fecha_f,
                'area' => $area,
                'total' => $total,
                'productos' => $productos
            ])
            ->setPaper('A4', 'landscape'); //horizontal

            return $pdf->stream("REPORTE DE STOCK DE PRODUCTOS.pdf");
        }
    //#############################################################
}
