<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControladorPacientes extends Controller
{

    public function pacientesView(){
        $areas = DB::table('areas')->where('estado', [1])->get();

        if(Auth::user()->rol == "Administrador"){
            $pacientes = DB::table('pacientes')
                ->leftJoin('areas','pacientes.area_fk' , '=' , 'areas.idArea')
                ->select('pacientes.*','areas.lugar as nombre_area')
                ->where('pacientes.estado', [1])
                ->orderBy('apellidos', 'asc')
                ->orderBy('nombres', 'asc')
            ->get();

            return view('pacientes.pacientes', compact('pacientes', 'areas'));
        }

        if(Auth::user()->rol == "Clinica"){
            $pacientes = DB::table('pacientes')
                ->leftJoin('areas','pacientes.area_fk' , '=' , 'areas.idArea')
                ->select('pacientes.*','areas.lugar as nombre_area')
                ->where('pacientes.estado', [1])
                ->where('areas.lugar','Clínica')
                ->orderBy('apellidos', 'asc')
                ->orderBy('nombres', 'asc')
            ->get();
            return view('pacientes.pacientes_clinica', compact('pacientes', 'areas'));
        }

        if(Auth::user()->rol == "Centro"){
            $pacientes = DB::table('pacientes')
                ->leftJoin('areas','pacientes.area_fk' , '=' , 'areas.idArea')
                ->select('pacientes.*','areas.lugar as nombre_area')
                ->where('pacientes.estado', [1])
                ->where('areas.lugar','Centro')
                ->orderBy('apellidos', 'asc')
                ->orderBy('nombres', 'asc')
            ->get();
            return view('pacientes.pacientes_centro', compact('pacientes', 'areas'));
        }

        if(Auth::user()->rol == "Usuario" || Auth::user()->rol == null){
            return view('errors.error');
        }

    }

    public function nuevoPacientePost(Request $request){
        $date_actual = date('Y-m-d H:i:s');
        $success = DB::table('pacientes')->insert([
            'area_fk' => $request->area_fk,

            'apellidos' => mb_strtoupper($request->apellidos),
            'cedula' => $request->cedula,
            'edad' => $request->edad,
            'genero' => $request->genero,
            'nombres' => mb_strtoupper($request->nombres),
            'observacion' => $request->observacion,
            'created_at' => $date_actual
        ]);

        if($success){
            return response()->json(['success' => 'Paciente creado con éxito.']);
        }else{
            return response()->json(['error' => 'No se pudo registrar, vuelva a intentar.']);
        }
    }

    public function editPacientePost(Request $request){
        $date_actual = date('Y-m-d H:i:s');
        $success = DB::table('pacientes')->where('idPaciente', $request->idPaciente)->update([
            'area_fk' => $request->area_fk,

            'apellidos' => mb_strtoupper($request->apellidos),
            'cedula' => $request->cedula,
            'edad' => $request->edad,
            'genero' => $request->genero,
            'nombres' => mb_strtoupper($request->nombres),
            'observacion' => $request->observacion,
            'updated_at' => $date_actual
        ]);

        if($success){
            return response()->json(['success' => 'Paciente editado con éxito.']);
        }else{
            return response()->json(['error' => 'No se pudo registrar, vuelva a intentar.']);
        }
    }

    public function eliminarPaciente($identificador){
        DB::table('pacientes')->where('idPaciente', $identificador)->update(['estado' => 66]);
        return redirect()->back();
    }
}
