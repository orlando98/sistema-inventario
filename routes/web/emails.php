<?php

use App\Mail\stock_productos;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth']], function () {
    // Route::get('emails/productos/stock/por/agotarse', [ControladorUsuarios::class, 'perfilView'])->name('perfilView');
    Route::get('emails/productos/stock/por/agotarse', function () {

        // Mail::to('duval.ad26@gmail.com')->send(new stock_productos);


        return "enviado";
    });
});
