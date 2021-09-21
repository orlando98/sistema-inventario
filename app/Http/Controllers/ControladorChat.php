<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class ControladorChat extends Controller
{
    protected $auth, $database;

    public function __construct(){
        $factory = (new Factory())
            ->withServiceAccount(__DIR__.'/patronato-firebase.json')
            ->withDatabaseUri('https://patronato-b4232-default-rtdb.firebaseio.com/');

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    public function chatView(){
        $usuarios = DB::table('users')->where('estado', [1])->whereNotIn('id', [Auth::user()->id])->get();
        return view('chat.index', compact('usuarios'));
    }

    public function envioMensaje(Request $request){
        $date_actual = date('Y-m-d H:i:s');

        $existencia1 = DB::table('sala')->where('usuario_1', $request->usuario_select)->where('usuario_2' , Auth::user()->id)->first();
        $existencia2 = DB::table('sala')->where('usuario_1', Auth::user()->id)->where('usuario_2' , $request->usuario_select)->first();

        if($existencia1 || $existencia2){
            if($existencia1){$idSala = $existencia1->idSala; $keyFirebase = $existencia1->key_firebase;}
            if($existencia2){$idSala = $existencia2->idSala; $keyFirebase = $existencia2->key_firebase;}

            $nuevoMensaje = $this->database->getReference("chat/sala/".$keyFirebase."/mensajes")->push([
                'mensaje' => $request->mensaje,
                'usuario_fk' => Auth::user()->id,
                'sala_fk' => $idSala,
                'created_at' => $date_actual
            ]);
            // $key = $nuevoMensaje->getKey();

            $mensaje = DB::table('mensajes')->insert([
                'mensaje' => $request->mensaje,
                'usuario_fk' => Auth::user()->id,
                'sala_fk' => $idSala,
                'key_sala_fk' => $keyFirebase, //firebase
                'created_at' => $date_actual
            ]);

            $mensajes = DB::table('mensajes')->where('sala_fk', $idSala)->get();
            if($mensaje){
                return response()->json(['success']);
            }else{
                return response()->json(['error']);
            }
        }
        else{
            $nuevaSala = $this->database->getReference("chat/sala")->push([
                'usuario_1' => intval($request->usuario_select),
                'usuario_2' => Auth::user()->id,
                'created_at' => $date_actual
            ]);

            $idSala = DB::table('sala')->insertGetId([
                'usuario_1' => $request->usuario_select,
                'usuario_2' => Auth::user()->id,
                'key_firebase' => $nuevaSala->getKey(), //firebase
                'created_at' => $date_actual
            ]);

            $mensaje = DB::table('mensajes')->insert([
                'mensaje' => $request->mensaje,
                'usuario_fk' => Auth::user()->id,
                'sala_fk' => $idSala,
                'key_sala_fk' => $nuevaSala->getKey(), //firebase
                'created_at' => $date_actual
            ]);

            $nuevoMensaje = $this->database->getReference("chat/sala/".$nuevaSala->getKey()."/mensajes")->push([
                'mensaje' => $request->mensaje,
                'usuario_fk' => Auth::user()->id,
                'sala_fk' => $idSala,
                'created_at' => $date_actual
            ]);

            if($idSala && $mensaje){
                return response()->json(['success' => $nuevaSala->getKey()]);
            }else{
                return response()->json(['error']);
            }
        }
    }

    public function consultarMensaje($usuario){
        $existencia1 = DB::table('sala')->where('usuario_1', $usuario)->where('usuario_2' , Auth::user()->id)->first();
        $existencia2 = DB::table('sala')->where('usuario_2', $usuario)->where('usuario_1' , Auth::user()->id)->first();

        if($existencia1 || $existencia2){
            if($existencia1){$idSala = $existencia1->idSala; $key_sala_fk = $existencia1->key_firebase;}
            if($existencia2){$idSala = $existencia2->idSala; $key_sala_fk = $existencia2->key_firebase;}

            $mensajes = DB::table('mensajes')->where('sala_fk', $idSala)->get();
            $key = $key_sala_fk;

            return response()->json(['success' => $key]);
        }else{
            return response()->json(['error']);
        }
    }
}
