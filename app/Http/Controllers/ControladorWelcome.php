<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControladorWelcome extends Controller
{
    public function welcome(){
        return view('welcome');
    }
}
