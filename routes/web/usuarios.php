<?php

use App\Http\Controllers\ControladorUsuarios;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth']], function () {
    Route::get('/perfil', [ControladorUsuarios::class, 'perfilView'])->name('perfilView');

    Route::get('/usuarios', [ControladorUsuarios::class, 'usuariosView'])->name('usuariosView');

    Route::get('/crear/usuario/vista', [ControladorUsuarios::class, 'nuevoUsuarioView'])->name('nuevoUsuarioView');
    Route::post('/crear/usuario/post', [ControladorUsuarios::class, 'nuevoUsuarioPost'])->name('nuevoUsuarioPost');

    Route::get('/editar/usuario/{token}', [ControladorUsuarios::class, 'editarUsuarioView'])->name('editarUsuarioView');
    Route::post('/editar/usuario/post', [ControladorUsuarios::class, 'editarUsuarioPost'])->name('editarUsuarioPost');

    Route::get('/eliminar/usuario/{id}', [ControladorUsuarios::class, 'eliminarUsuario'])->name('eliminarUsuario');

    //validaciones
    Route::get('/consulta/username/{consulta}' , [ControladorUsuarios::class, 'checkUsername'])->name('checkUsername');
    Route::get('/consulta/email/{consulta}' , [ControladorUsuarios::class, 'checkEmail'])->name('checkEmail');

});
