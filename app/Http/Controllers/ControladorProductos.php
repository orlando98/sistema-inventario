<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\stock_productos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ControladorProductos extends Controller{
     //####################################  PRODUCTOS  ##################################
        public function productosView(){
            $productos = DB::table('productos')
                ->leftJoin('producto_area' ,'productos.idProducto','=' , 'producto_area.producto_fk')

                ->select(
                    'productos.idProducto',
                    'productos.nombre',
                    'productos.descripcion',
                    DB::raw("sum(producto_area.stock) as stock_producto")
                )
                ->where('productos.estado', [1])
                ->groupBy('productos.idProducto')
                ->groupBy('productos.nombre')
                ->groupBy('productos.descripcion')

                ->orderBy('productos.nombre')
            ->get();

            return view('productos.productos' , compact('productos'));
        }

        public function nuevoProductoPost(Request $request){
            $consulta = DB::table('productos')->where('slug' , Str::slug($request->nombre))->first();

            if($consulta){
                return response()->json(['warning' => 'Ya existe un producto con ese nombre.']);
            }else{
                $success = DB::table('productos')->insert([
                    'nombre' => $request->nombre,
                    'slug' => Str::slug($request->nombre),
                    'descripcion' => $request->descripcion,

                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                if($success){
                    return response()->json(['success' => 'Producto creado con éxito.']);
                }else{
                    return response()->json(['error' => 'No se pudo crear el producto, vuelva a intentarlo.']);
                }
            }
        }

        public function editarProductoPost(Request $request){

            $idProducto = $request->idProducto;
            $consulta = DB::table('productos')->where('slug' , Str::slug($request->nombre))->first();
            $existencia = DB::table('productos')->where('idProducto' , $idProducto)->first();

            if($existencia){
                if($existencia->slug == Str::slug($request->nombre)){
                    $registro = DB::table('productos')->where('idProducto' , $idProducto)->update([
                        'nombre' => $request->nombre,
                        'slug' => Str::slug($request->nombre),
                        'descripcion' => $request->descripcion,

                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    if($registro){
                        return response()->json(['success' => 'Producto actualizado con éxito']);
                    }else{
                        return response()->json(['error' => 'No se pudo registrar, vuelva a intentarlo.']);
                    }
                }else{
                    if($consulta){
                        return response()->json(['error' => 'Ya existe un producto con ese nombre.']);
                    }else{
                        $registro = DB::table('productos')->where('idProducto' , $idProducto)->update([
                            'nombre' => $request->nombre,
                            'slug' => Str::slug($request->nombre),
                            'descripcion' => $request->descripcion,

                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                        if($registro){
                            return response()->json(['success' => 'Producto actualizado con éxito']);
                        }else{
                            return response()->json(['error' => 'No se pudo registrar, vuelva a intentarlo.']);
                        }
                    }
                }
            }else{
                return response()->json(['error' => 'No se encontro el registro, refrescar y vuelva a intentarlo.']);
            }
        }

        public function eliminarProducto($identificador){
            DB::table('productos')->where('idProducto', $identificador)->update(['estado' => 66]);
            return redirect()->back();
        }
    //####################################################################################

    //################################  INVENTARIO (INGRESO)  ############################
        public function ingresoProductoView(){
            $productos = DB::table('productos')->where('estado', [1])->get();

            if(Auth::user()->rol == "Administrador"){
                $areas = DB::table('areas')->where('estado', [1])->get();
                $tipos = DB::table('tipos_area')->get();

                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.idProducto as prod_id',
                        'productos.nombre as prod_nombre',
                        'productos.descripcion as prod_descripcion',
                        'tipos_area.nombre as tipo_area_nombre',
                        'areas.lugar as area_nombre',
                        'producto_area.lote as arprod_lote',
                        'producto_area.fecha_c as arprod_fecha_c',
                        DB::raw("sum(producto_area.stock) as stock_producto")
                    )
                    ->where('productos.estado' , [1])
                    ->groupBy('prod_id')
                    ->groupBy('prod_nombre')
                    ->groupBy('prod_descripcion')

                    ->groupBy('tipo_area_nombre')
                    ->groupBy('area_nombre')

                    ->groupBy('arprod_lote')

                    ->groupBy('arprod_fecha_c')

                    ->orderByDesc('area_nombre')
                    ->orderByDesc('tipo_area_nombre')
                    ->orderByDesc('prod_nombre')
                    ->orderByDesc('prod_descripcion')
                ->get();

                return view('inventario.ingreso.ingreso_stock' , compact('productos', 'areas','tipos', 'productos_stock'));
            }

            if(Auth::user()->rol == "Centro"){
                $areas = DB::table('areas')->where('estado', 1)->where('lugar','Centro')->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , [$areas->idArea])->get();

                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.idProducto as prod_id',
                        'productos.nombre as prod_nombre',
                        'productos.descripcion as prod_descripcion',
                        'tipos_area.nombre as tipo_area_nombre',
                        'areas.lugar as area_nombre',
                        'producto_area.lote as arprod_lote',
                        'producto_area.fecha_c as arprod_fecha_c',
                        DB::raw("sum(producto_area.stock) as stock_producto")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar', ['Centro'])
                    ->groupBy('prod_id')
                    ->groupBy('prod_nombre')
                    ->groupBy('prod_descripcion')

                    ->groupBy('tipo_area_nombre')
                    ->groupBy('area_nombre')

                    ->groupBy('arprod_lote')

                    ->groupBy('arprod_fecha_c')

                    ->orderByDesc('area_nombre')
                    ->orderByDesc('tipo_area_nombre')
                    ->orderByDesc('prod_nombre')
                    ->orderByDesc('prod_descripcion')
                ->get();

                return view('inventario.ingreso.ingreso_stock_centro' , compact('productos', 'areas','tipos', 'productos_stock'));
            }

            if(Auth::user()->rol == "Clinica"){
                $areas = DB::table('areas')->where('estado', 1)->where('lugar','Clinica')->first();
                $tipos = DB::table('tipos_area')->where('area_fk' , [$areas->idArea])->get();

                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.idProducto as prod_id',
                        'productos.nombre as prod_nombre',
                        'productos.descripcion as prod_descripcion',
                        'tipos_area.nombre as tipo_area_nombre',
                        'areas.lugar as area_nombre',
                        'producto_area.lote as arprod_lote',
                        'producto_area.fecha_c as arprod_fecha_c',
                        DB::raw("sum(producto_area.stock) as stock_producto")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar', ['Clinica'])
                    ->groupBy('prod_id')
                    ->groupBy('prod_nombre')
                    ->groupBy('prod_descripcion')

                    ->groupBy('tipo_area_nombre')
                    ->groupBy('area_nombre')

                    ->groupBy('arprod_lote')

                    ->groupBy('arprod_fecha_c')


                    ->orderByDesc('area_nombre')
                    ->orderByDesc('tipo_area_nombre')
                    ->orderByDesc('prod_nombre')
                    ->orderByDesc('prod_descripcion')
                ->get();

                return view('inventario.ingreso.ingreso_stock_clinica' , compact('productos', 'areas','tipos', 'productos_stock'));
            }

            if(Auth::user()->rol == "" || Auth::user()->rol == "Usuario"){
                return view('errors.error');
            }
        }

        public function ingresoProductoPost(Request $request){
            $date_actual = date('Y-m-d H:i:s');
            $lugar = DB::table('tipos_area')->where('idTipo' , $request->area_fk)->first();
            $registro = DB::table('producto_area')->insert([
                'tipo_area_fk' => $request->area_fk,
                'area_fk' => $lugar->area_fk,
                'lote' => $request->lote,
                'fecha_c' => $request->fecha_c,
                'producto_fk' => $request->producto_fk,
                'stock' => $request->cantidad,
                'usuario_fk' => Auth::user()->id,
                'created_at' => $date_actual
            ]);

            $usuario_fk = Auth::user()->id;
            $producto_fk = $request->producto_fk;
            $producto_area_fk = null;
            $area_fk = $lugar->area_fk;
            $tipo_area_fk = $request->area_fk;
            $paciente_fk = null;
            $tipo_movimiento = "INGRESO";
            $cantidad = $request->cantidad;
            $lote = $request->lote;
            $fecha_cadu = $request->fecha_c;
            $created_at =  date('Y-m-d H:i:s');

            DB::statement("CALL movimientos_stock(?,?,?,?,?,?,?,?,?,?,?,?)", [$usuario_fk, $producto_fk, $producto_area_fk, $area_fk, $tipo_area_fk, $paciente_fk, $tipo_movimiento, $cantidad, $lote, $fecha_cadu, $created_at, $created_at]);

            if($registro){
                return response()->json(['success' => 'Stock agregado con éxito.']);
            }else{
                return response()->json(['error' => 'No se pudo agregar stock al producto, vuelva a intentarlo.']);
            }

        }
    //####################################################################################

    //################################  INVENTARIO (EGRESO)  #############################
        public function egresoProductoView(){
            if(Auth::user()->rol == "Administrador"){
                $areas = DB::table('areas')->where('estado', [1])->get();
                $tipos = DB::table('tipos_area')->get();

                $pacientes = DB::table('pacientes')->leftJoin('areas', 'pacientes.area_fk' ,'=', 'areas.idArea')->select('pacientes.*', 'areas.lugar as nombre_area')
                    ->where('pacientes.estado',[1])
                    ->orderBy('pacientes.apellidos', 'asc')
                    ->orderBy('pacientes.nombres', 'asc')
                ->get();

                $movimientos = DB::table('movimientos')
                    ->leftJoin('producto_area' , 'movimientos.producto_area_fk' , '=' , 'producto_area.idProductoArea')
                    ->leftJoin('productos' , 'producto_area.producto_fk' , '=' , 'productos.idProducto')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->leftJoin('areas', 'pacientes.area_fk' , '=' , 'areas.idArea')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->select(
                        'movimientos.cantidad as cantidad_egresada',
                        'movimientos.fecha_cadu',
                        'movimientos.lote',

                        'users.username as username',
                        'pacientes.nombres as paciente_nombres',
                        'pacientes.apellidos as paciente_apellidos',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre'
                    )
                    ->where('movimientos.tipo_movimiento' , ['EGRESO'])
                    ->orderByDesc('movimientos.idMovimiento')
                ->get();

                return view('inventario.egreso.egreso_stock' , compact('movimientos', 'areas','tipos', 'pacientes'));
            }

            if(Auth::user()->rol == "Clinica"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', 'Clínica')->first();
                $tipos = DB::table('tipos_area')->where('area_fk', $areas->idArea)->get();

                $pacientes = DB::table('pacientes')->leftJoin('areas', 'pacientes.area_fk' ,'=', 'areas.idArea')->select('pacientes.*', 'areas.lugar as nombre_area')
                    ->where('pacientes.estado',[1])
                    ->where('areas.lugar',['Clínica'])
                    ->orderBy('pacientes.apellidos', 'asc')
                    ->orderBy('pacientes.nombres', 'asc')
                ->get();

                $movimientos = DB::table('movimientos')
                    ->leftJoin('producto_area' , 'movimientos.producto_area_fk' , '=' , 'producto_area.idProductoArea')
                    ->leftJoin('productos' , 'producto_area.producto_fk' , '=' , 'productos.idProducto')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->leftJoin('areas', 'pacientes.area_fk' , '=' , 'areas.idArea')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->select(
                        'movimientos.cantidad as cantidad_egresada',
                        'movimientos.fecha_cadu',
                        'movimientos.lote',

                        'users.username as username',
                        'pacientes.nombres as paciente_nombres',
                        'pacientes.apellidos as paciente_apellidos',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre'
                    )
                    ->where('movimientos.tipo_movimiento' , ['EGRESO'])
                    ->where('areas.lugar' , ['Clínica'])
                    ->orderByDesc('movimientos.idMovimiento')
                ->get();

                return view('inventario.egreso.egreso_stock_clinica' , compact('movimientos', 'areas','tipos', 'pacientes'));
            }

            if(Auth::user()->rol == "Centro"){
                $areas = DB::table('areas')->where('estado', [1])->where('lugar', 'Centro')->first();
                $tipos = DB::table('tipos_area')->where('area_fk', $areas->idArea)->get();

                $pacientes = DB::table('pacientes')->leftJoin('areas', 'pacientes.area_fk' ,'=', 'areas.idArea')->select('pacientes.*', 'areas.lugar as nombre_area')
                    ->where('pacientes.estado',[1])
                    ->where('areas.lugar',['Centro'])
                    ->orderBy('pacientes.apellidos', 'asc')
                    ->orderBy('pacientes.nombres', 'asc')
                ->get();

                $movimientos = DB::table('movimientos')
                    ->leftJoin('producto_area' , 'movimientos.producto_area_fk' , '=' , 'producto_area.idProductoArea')
                    ->leftJoin('productos' , 'producto_area.producto_fk' , '=' , 'productos.idProducto')
                    ->leftJoin('pacientes', 'movimientos.paciente_fk', '=', 'pacientes.idPaciente')
                    ->leftJoin('areas', 'pacientes.area_fk' , '=' , 'areas.idArea')
                    ->leftJoin('users', 'movimientos.usuario_fk', '=', 'users.id')
                    ->select(
                        'movimientos.cantidad as cantidad_egresada',
                        'movimientos.fecha_cadu',
                        'movimientos.lote',

                        'users.username as username',
                        'pacientes.nombres as paciente_nombres',
                        'pacientes.apellidos as paciente_apellidos',

                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',

                        'areas.lugar as area_nombre'
                    )
                    ->where('movimientos.tipo_movimiento' , ['EGRESO'])
                    ->where('areas.lugar' , ['Centro'])
                    ->orderByDesc('movimientos.idMovimiento')
                ->get();

                return view('inventario.egreso.egreso_stock_centro' , compact('movimientos', 'areas','tipos', 'pacientes'));
            }

            if(Auth::user()->rol != "Administrador" || Auth::user()->rol != "Clinica" || Auth::user()->rol != "Centro"){
                return view('errors.error');
            }
        }

        public function egresoProductoPost(Request $request){
            $date_actual = date('Y-m-d H:i:s');
            $idPaciente = $request->paciente_fk;
            $productos = json_decode($request->contenido);

            $contador = 0 ;
            for ($i=0; $i < sizeof($productos) ; $i++) {

                // return response()->json([$productos[$i]->id]);
                $detalle = DB::table('producto_area')->where('idProductoArea', $productos[$i]->id)->first();
                $cantidad = $productos[$i]->cantidad;
                $cantidad_nueva = $detalle->stock - $cantidad;

                DB::table('producto_area')->where('idProductoArea', $productos[$i]->id)->update([
                    'stock' => $cantidad_nueva,
                    'updated_at' => $date_actual
                ]);

                $usuario_fk = Auth::user()->id;
                $producto_fk = $detalle->producto_fk; //id Producto
                $producto_area_fk = $detalle->idProductoArea; //no es el id del producto es el id del producto_de_area
                $area_fk = $detalle->area_fk;
                $tipo_area_fk = $detalle->tipo_area_fk;
                $paciente_fk = $idPaciente;
                $tipo_movimiento = "EGRESO";
                $cantidad_envi = $cantidad;
                $lote = $detalle->lote;
                $fecha_cadu = $detalle->fecha_c;
                $created_at =  date('Y-m-d H:i:s');

                DB::statement("CALL movimientos_stock(?,?,?,?,?,?,?,?,?,?,?,?)", [$usuario_fk, $producto_fk, $producto_area_fk, $area_fk, $tipo_area_fk, $paciente_fk, $tipo_movimiento, $cantidad_envi,  $lote, $fecha_cadu, $created_at, $created_at]);
                $contador++;
            }

            $stock_agotandose = DB::table('producto_area')
                ->leftJoin('productos' , 'producto_area.producto_fk', '=', 'productos.idProducto')
                ->leftJoin('areas' , 'producto_area.area_fk', '=', 'areas.idArea')
                ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk', '=', 'tipos_area.idTipo')

                ->select(
                    'productos.nombre as producto_nombre',
                    'productos.descripcion as producto_descripcion',

                    'areas.lugar as area_nombre',
                    'tipos_area.nombre as area_tipo',

                    'producto_area.fecha_c as fecha_caduc',
                    'producto_area.stock as stock',
                    'producto_area.lote as lote'
                )

                ->where('productos.estado' , [1])
                ->where('producto_area.stock' , '<=', [30])

                ->orderByDesc('producto_nombre')
                ->orderByDesc('producto_descripcion')
                ->orderByDesc('area_nombre')
                ->orderByDesc('area_tipo')
                ->orderByDesc('fecha_caduc')
                ->orderByDesc('lote')


            ->get();

            if(sizeof($stock_agotandose) > 0){

                //COREOS DE PATRONATO
                $destinos = [
                    'patronatomontecristi2014@hotmail.com',
                    'patronatomontecristi2019@hotmail.com',
                    'orlandosantana98@gmail.com'
                ];

                Mail::to($destinos)->send(new stock_productos($stock_agotandose));
            }

            if($contador == sizeof($productos)){
                return response()->json(['success' => 'Stock agregado con éxito.']);
            }else{
                return response()->json(['error' => 'No se pudo agregar stock al producto, vuelva a intentarlo.']);
            }
        }

        public function consultaProductoArea($identificador){
            $producto = DB::table('pacientes')
                ->leftJoin('producto_area' , 'pacientes.area_fk' , '=', 'producto_area.area_fk')
                ->leftJoin('productos','producto_area.producto_fk', '=' , 'productos.idProducto')
                ->select(
                    'producto_area.idProductoArea as producto_id',
                    'productos.nombre as producto_nombre',
                    'producto_area.stock as producto_stock',
                    'producto_area.fecha_c as fecha_expira'
                )
                ->where('pacientes.idPaciente' , $identificador)
                ->where('producto_area.stock' , '>' ,[0])
                ->orderBy('producto_nombre', 'asc')
                ->orderBy('producto_stock', 'asc')
            ->get();

            return response()->json($producto);
        }

        public function consultaDetalleProductoArea($identificador){
            $producto = DB::table('productos')
                ->leftJoin('producto_area' , 'productos.idProducto' , '=', 'producto_area.producto_fk')
                ->select(
                    'productos.idProducto as producto_id',
                    'productos.nombre as producto_nombre',
                    'producto_area.stock as producto_stock'
                )
                ->where('producto_area.idProductoArea' , $identificador)
                ->orderBy('producto_nombre', 'asc')
                ->orderBy('producto_stock', 'asc')
            ->first();

            return response()->json($producto);
        }
    //####################################################################################

    //#####################################  STOCK  ######################################
        public function stockView(){
            if(Auth::user()->rol == "Administrador"){
                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.idProducto as prod_id',
                        'productos.nombre as prod_nombre',
                        'productos.descripcion as prod_descripcion',
                        'tipos_area.nombre as tipo_area_nombre',
                        'areas.lugar as area_nombre',
                        'producto_area.lote as arprod_lote',
                        'producto_area.fecha_c as arprod_fecha_c',
                        DB::raw("sum(producto_area.stock) as stock_producto")
                    )
                    ->where('productos.estado' , [1])
                    ->groupBy('prod_id')
                    ->groupBy('prod_nombre')
                    ->groupBy('prod_descripcion')

                    ->groupBy('tipo_area_nombre')
                    ->groupBy('area_nombre')

                    ->groupBy('arprod_lote')

                    ->groupBy('arprod_fecha_c')
                ->get();

                return view('inventario.stock.stock' , compact('productos_stock'));
            }

            if(Auth::user()->rol == "Clinica"){
                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.idProducto as prod_id',
                        'productos.nombre as prod_nombre',
                        'productos.descripcion as prod_descripcion',
                        'tipos_area.nombre as tipo_area_nombre',
                        'areas.lugar as area_nombre',
                        'producto_area.lote as arprod_lote',
                        'producto_area.fecha_c as arprod_fecha_c',
                        DB::raw("sum(producto_area.stock) as stock_producto")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar' , ['Clinica'])
                    ->groupBy('prod_id')
                    ->groupBy('prod_nombre')
                    ->groupBy('prod_descripcion')

                    ->groupBy('tipo_area_nombre')
                    ->groupBy('area_nombre')

                    ->groupBy('arprod_lote')

                    ->groupBy('arprod_fecha_c')
                ->get();

                return view('inventario.stock.stock_clinica' , compact('productos_stock'));
            }

            if(Auth::user()->rol == "Centro"){
                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.idProducto as prod_id',
                        'productos.nombre as prod_nombre',
                        'productos.descripcion as prod_descripcion',
                        'tipos_area.nombre as tipo_area_nombre',
                        'areas.lugar as area_nombre',
                        'producto_area.lote as arprod_lote',
                        'producto_area.fecha_c as arprod_fecha_c',
                        DB::raw("sum(producto_area.stock) as stock_producto")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar' , ['Centro'])
                    ->groupBy('prod_id')
                    ->groupBy('prod_nombre')
                    ->groupBy('prod_descripcion')

                    ->groupBy('tipo_area_nombre')
                    ->groupBy('area_nombre')

                    ->groupBy('arprod_lote')

                    ->groupBy('arprod_fecha_c')
                ->get();

                return view('inventario.stock.stock_centro' , compact('productos_stock'));
            }

            if(Auth::user()->rol = "" || Auth::user()->rol = "Usuario"){
                return view('errors.error');
            }
        }
    //####################################################################################

    //###############################  PRODUCTOS POR VENCER  #############################
        public function productosPorVencerView(){
            if(Auth::user()->rol == "Administrador"){
                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_caduc',
                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->whereDate('producto_area.fecha_c', '<=', Carbon::now()->add(30, 'day')->format('Y-m-d'))
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('lote')
                    ->groupBy('fecha_caduc')

                ->get();

                return view('productos.productos_por_vencer' , compact('productos_stock'));
            }

            if(Auth::user()->rol == "Clinica"){
                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_caduc',
                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar' , ['Clínica'])
                    ->whereDate('producto_area.fecha_c', '<=', Carbon::now()->add(30, 'day')->format('Y-m-d'))
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('lote')
                    ->groupBy('fecha_caduc')

                ->get();

                return view('productos.clinica.productos_por_vencerClinica' , compact('productos_stock'));
            }

            if(Auth::user()->rol == "Centro"){
                $productos_stock = DB::table('productos')
                    ->leftJoin('producto_area', 'productos.idProducto', '=','producto_area.producto_fk')
                    ->leftJoin('tipos_area' , 'producto_area.tipo_area_fk' , '=' , 'tipos_area.idTipo')
                    ->leftJoin('areas', 'producto_area.area_fk' , '=' , 'areas.idArea')
                    ->select(
                        'productos.nombre as producto_nombre',
                        'productos.descripcion as producto_descripcion',
                        'producto_area.lote',
                        'producto_area.fecha_c as fecha_caduc',
                        'areas.lugar as area_nombre',
                        'tipos_area.nombre as area_tipo',

                        DB::raw("sum(producto_area.stock) as cantidad")
                    )
                    ->where('productos.estado' , [1])
                    ->where('areas.lugar' , ['Centro'])
                    ->whereDate('producto_area.fecha_c', '<=', Carbon::now()->add(30, 'day')->format('Y-m-d'))
                    ->groupBy('producto_nombre')
                    ->groupBy('producto_descripcion')
                    ->groupBy('area_nombre')
                    ->groupBy('area_tipo')
                    ->groupBy('lote')
                    ->groupBy('fecha_caduc')

                ->get();

                return view('productos.centro.productos_por_vencerCentro' , compact('productos_stock'));
            }

            if(Auth::user()->rol = "" || Auth::user()->rol = "Usuario"){
                return view('errors.error');
            }
        }
    //####################################################################################
}
