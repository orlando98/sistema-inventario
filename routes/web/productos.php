<?php

use App\Http\Controllers\ControladorProductos;
use App\Http\Controllers\ControladorUsuarios;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth']], function () {
    Route::get('/productos', [ControladorProductos::class, 'productosView'])->name('productosView');
    Route::post('/crear/producto/post', [ControladorProductos::class, 'nuevoProductoPost'])->name('nuevoProductoPost');
    Route::post('/editar/producto/post', [ControladorProductos::class, 'editarProductoPost'])->name('editarProductoPost');
    Route::get('/eliminar/producto/{id}', [ControladorProductos::class, 'eliminarProducto'])->name('eliminarProducto');

    //######################### INVENTARIO (INGRESO) ########################
    Route::get('/ingreso/productos', [ControladorProductos::class, 'ingresoProductoView'])->name('ingresoProductoView');
    Route::post('/ingreso/productos/post', [ControladorProductos::class, 'ingresoProductoPost'])->name('ingresoProductoPost');


    //######################### INVENTARIO (EGRESO) ########################
    Route::get('/egreso/productos', [ControladorProductos::class, 'egresoProductoView'])->name('egresoProductoView');
    Route::post('/egreso/productos/post', [ControladorProductos::class, 'egresoProductoPost'])->name('egresoProductoPost');

    Route::get('/consulta/productos/{id}' , [ControladorProductos::class, 'consultaProductoArea']);
    Route::get('/detalles/producto/{id}/opcion' , [ControladorProductos::class, 'consultaDetalleProductoArea']);


    //######################### STOCK ########################
    Route::get('/stock/productos', [ControladorProductos::class, 'stockView'])->name('stockView');
    

    //######################### PRODUCTOS POR VENCER ########################
    Route::get('/stock/productos/por/vencer', [ControladorProductos::class, 'productosPorVencerView'])->name('productosPorVencerView');

});
