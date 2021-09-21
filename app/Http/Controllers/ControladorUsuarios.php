<?php

namespace App\Http\Controllers;

use Error;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ControladorUsuarios extends Controller
{
    public function perfilView(){
       return view('home');
    }

    public function usuariosView(){
        if(Auth::user()->rol == "Administrador"){
            $usuarios = DB::table('users')->where('users.estado', '!=' , [66])->where('users.rol' ,'!=' , ['Administrador'])->get();

            return view('usuarios.usuarios', compact('usuarios'));
        }else{
            return view('errors.error');
        }

    }

    //CREATE
    public function nuevoUsuarioView(){
        if(Auth::user()->rol == "Administrador"){
            return view('usuarios.nuevo_usuario');
        }else{
            return view('errors.error');
        }
    }
    public function nuevoUsuarioPost(Request $request){
        $success = DB::table('users')->insert([
            'nombres' => mb_strtoupper($request->nombres),
            'apellidos' => mb_strtoupper($request->apellidos),
            'username' => mb_strtoupper($request->username),
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'genero' => $request->genero,
            'estado' => $request->estado,
            'token' => Str::random((20)),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if($success){
            return response()->json(['success' => 'Usuario creado con éxito.']);
        }else{
            return response()->json(['error' => 'No se pudo registrar el usuario, vuelva a intentar.']);
        }
    }

    //EDIT
    public function editarUsuarioView($token){
        if(Auth::user()->rol == "Administrador"){
            $usuario = DB::table('users')->where('token', $token)->first();
            if($usuario){
                return view('usuarios.editar_usuario' , compact('usuario'));
            }else{
                return redirect()->back();
            }
        }else{
            return view('errors.error');
        }
    }
    public function editarUsuarioPost(Request $request){

        $idUsuario = $request->id;

        $success = DB::table('users')->where('id' , $idUsuario)->update([
            'nombres' => mb_strtoupper($request->nombres),
            'apellidos' => mb_strtoupper($request->apellidos),
            'username' => mb_strtoupper($request->username),
            'email' => strtolower($request->email),
            'rol' => $request->rol,
            'genero' => $request->genero,
            'estado' => $request->estado,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if($success){
            return response()->json(['success' => 'Usuario editado con éxito.']);
        }else{
            return response()->json(['error' => 'No se pudo editar el usuario, vuelva a intentar.']);
        }
    }


    //DELETE
    public function eliminarUsuario($identificador){
        if(Auth::user()->rol == "Administrador"){
            DB::table('users')->where('id' , $identificador)->update([
                'estado' => '66',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back();
        }else{
            return view('errors.error');
        }
    }


    ///################ validaciones
    public function checkUsername($dato){
        $consulta = DB::table('users')->where('username', mb_strtoupper($dato))->first();
        if($consulta){
            return response()->json(['error' => 'Usuario no disponible', $dato]);
        }else{
            return response()->json(['success' => 'Usuario disponible', $dato]);
        }
    }

    public function checkEmail($dato){
        $consulta = DB::table('users')->where('email', $dato)->first();
        if($consulta){
            return response()->json(['error' => 'Correo no disponible', $dato]);
        }else{
            return response()->json(['success' => 'Correo disponible', $dato]);
        }
    }
}
