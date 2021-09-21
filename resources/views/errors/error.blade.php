@extends('layouts.sistema')
@section('title-content' , 'Error 404')
@section('content')
<div class="empty">
    <div class="empty-icon">
        <img src="{{asset('static/illustrations/undraw_quitting_time_dm8t.svg')}}" height="128" class="mb-4" alt="">
    </div>
    <p class="empty-title h3">
        No se han encontrado resultados</p>
    <p class="empty-subtitle text-muted">

    </p>
    <div class="empty-action"><a href="{{route('home')}}" class="btn btn-primary"><i class="fas fa-home"></i>&nbsp; Ir a panel</a></div>
</div>

@endsection
