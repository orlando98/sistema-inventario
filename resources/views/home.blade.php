@extends('layouts.sistema')
@section('title-content' , 'Panel')
@section('content')
<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">
                Bienvenido(a), <span class="ml-2">{{Auth::user()->nombres}} {{Auth::user()->apellidos}}</span>
            </h2>
        </div>
    </div>
</div>

<div class="row row-deck row-cards text-center">
    <div class="d-flex justify-content-center">
        <img src="{{asset('img/logo.png')}}" style="height: 404px; width: 55%;" alt="Logo patronato">
    </div>
</div>
@endsection

@section('script-content')
@endsection
