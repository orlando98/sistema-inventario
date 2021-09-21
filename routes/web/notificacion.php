<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorNotificaciones;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/notificaciones', [ControladorNotificaciones::class, 'notificacionesView'])->name('notificacionesView');
    Route::get('/notificacion/{id}', [ControladorNotificaciones::class, 'notificacionesDetailView'])->name('notificacionesDetailView');

    Route::post('/notificacion/marcar-como-leido', [ControladorNotificaciones::class, 'notificacionLeida']); //post marcar como leido
});
