<?php

use App\Mail\stock_productos;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorChat;


Route::group(['middleware' => ['auth']], function () {
    Route::get('/chat', [ControladorChat::class, 'chatView'])->name('chatView');

    Route::post('/enviar/mensajes/chat/post', [ControladorChat::class, 'envioMensaje']);
    Route::get('/consultar/mensajes/chat/{id}', [ControladorChat::class, 'consultarMensaje']);
});
