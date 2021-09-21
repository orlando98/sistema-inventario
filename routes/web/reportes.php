<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorReporte;

Route::group(['middleware' => ['auth']], function () {

    //======================== INGRESO ======================================================
        Route::get('/reportes/ingreso', [ControladorReporte::class, 'reporteIngresoView'])->name('reporteIngresoView');

        //ADMIN
        Route::get('/filtro/reporte/ingreso/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroMovimientosIngresoReporte']);//filtro
        Route::get('/PDF/movimiento/ingreso/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'reporteIngresoPDF']);//filtro

        //CLINICA
        Route::get('/filtro/reporte/ingreso/clinica/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroMovimientosIngresoReporteCLINICA']);//filtro
        Route::get('/PDF/movimiento/ingreso/clinica/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'reporteIngresoPDFCLINICA']);//filtro

        //CENTRO
        Route::get('/filtro/reporte/ingreso/centro/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroMovimientosIngresoReporteCENTRO']);//filtro
        Route::get('/PDF/movimiento/ingreso/centro/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'reporteIngresoPDFCENTRO']);//filtro
    //=============================================================================================

    //======================== EGRESO ======================================================
        Route::get('/reportes/egreso', [ControladorReporte::class, 'reporteEgresoView'])->name('reporteEgresoView');

        //ADMIN
        Route::get('/filtro/reporte/egreso/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroMovimientosEgresoReporte']);//filtro
        Route::get('/PDF/movimiento/egreso/{fecha_inicio}/{fecha_fin}/{area}/{pacientes}', [ControladorReporte::class, 'reporteEgresoPDF']);//filtro

        //CLINICA
        Route::get('/filtro/reporte/egreso/clinica/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroMovimientosEgresoReporteCLINICA']);//filtro
        Route::get('/PDF/movimiento/egreso/clinica/{fecha_inicio}/{fecha_fin}/{area}/{pacientes}', [ControladorReporte::class, 'reporteEgresoPDFCLINICA']);//filtro

        //CENTRO
        Route::get('/filtro/reporte/egreso/centro/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroMovimientosEgresoReporteCENTRO']);//filtro
        Route::get('/PDF/movimiento/egreso/centro/{fecha_inicio}/{fecha_fin}/{area}/{pacientes}', [ControladorReporte::class, 'reporteEgresoPDFCENTRO']);//filtro
    //=============================================================================================

    //======================== STOCK ======================================================
        Route::get('/reportes/stock', [ControladorReporte::class, 'reporteStockView'])->name('reporteStockView');

        //ADMIN
        Route::get('/filtro/reporte/stock/parametros/{area}', [ControladorReporte::class, 'filtroStockReporte']);//filtro
        Route::get('/PDF/reporte/stock/{area}', [ControladorReporte::class, 'reporteStockPDF']);//filtro

        //CLINICA
        Route::get('/filtro/reporte/stock/clinica/parametros/{area}', [ControladorReporte::class, 'filtroStockReporteCLINICA']);//filtro
        Route::get('/PDF/reporte/stock/clinica/{area}', [ControladorReporte::class, 'reporteStockPDFCLINICA']);//filtro

        //CENTRO
        Route::get('/filtro/reporte/stock/centro/parametros/{area}', [ControladorReporte::class, 'filtroStockReporteCENTRO']);//filtro
        Route::get('/PDF/reporte/stock/centro/{area}', [ControladorReporte::class, 'reporteStockPDFCENTRO']);//filtro
    //=============================================================================================

    //======================== KARDEX ======================================================
        Route::get('/reportes/kardex', [ControladorReporte::class, 'reporteKardexView'])->name('reporteKardexView');

        //ADMIN
        Route::get('/filtro/reporte/kardex/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroKardexReporte']);//filtro
        Route::get('/PDF/reporte/kardex/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'reporteKardexPDF']);//filtro

        //CLINICA
        Route::get('/filtro/reporte/kardex/clinica/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroKardexReporteCLINICA']);//filtro
        Route::get('/PDF/reporte/kardex/clinica/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'reporteKardexPDFCLINICA']);//filtro

        //CENTRO
        Route::get('/filtro/reporte/kardex/centro/parametros/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'filtroKardexReporteCENTRO']);//filtro
        Route::get('/PDF/reporte/kardex/centro/{fecha_inicio}/{fecha_fin}/{area}', [ControladorReporte::class, 'reporteKardexPDFCENTRO']);//filtro
    //=============================================================================================
});
