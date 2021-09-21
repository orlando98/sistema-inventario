<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Notificaciones
{
    public static function notificaciones(){
        $notificaciones = DB::table('notificaciones')->where('estado', [1])->orderByDesc('idNotificacion')->get();
        $contador = DB::table('notificaciones')->where('estado', [1])->orderByDesc('idNotificacion')->count();

        return [$notificaciones , $contador];
    }
}
