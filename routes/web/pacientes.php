<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorPacientes;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/pacientes', [ControladorPacientes::class, 'pacientesView'])->name('pacientesView');
    Route::post('/crear/paciente/post', [ControladorPacientes::class, 'nuevoPacientePost'])->name('nuevoPacientePost');
    Route::post('/editar/paciente/post', [ControladorPacientes::class, 'editPacientePost'])->name('editPacientePost');
    Route::get('/eliminar/paciente/{id}', [ControladorPacientes::class, 'eliminarPaciente'])->name('eliminarPaciente');
});
