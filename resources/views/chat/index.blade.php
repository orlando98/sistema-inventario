@extends('layouts.sistema')
@section('title-content' , 'Chat')
{{-- @section('ckeditor') <script src="{{asset('ckeditor/ckeditor.js')}}"></script> @endsection --}}
@section('content')
@csrf

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">Chat</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de usuarios</h3>
            </div>
            <div class="list list-row list-hoverable">
                @foreach ($usuarios as $item)
                <div class="list-item usuarios-option usuarios-option-a" style="cursor: pointer" onclick="mensajeUsers({{$item->id}});">
                    <div class="text-truncate">
                        <span class="d-block text-truncate">{{$item->nombres}} {{$item->apellidos}}</span>
                        <small class="d-block text-truncate mt-n1">{{$item->rol}}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mensajes</h3>
                <span></span>
            </div>
            <!-- Mensajes chat -->
            <div class="cuerpo-chat">
                <input type="hidden" id="usuario_select">
                <div class="contenido-chat" id="body-mensajes"></div>
            </div>
            <!-- Enviar mensaje -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2 mb-0">
                            <label class="form-label">Mensaje</label>
                            <textarea rows="3" class="form-control" onchange="verificateNull()" id="mensaje_chat"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" id="btnSendMensa" class="col-md-12 btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z"></path>
                                <path d="M21 3L14.5 21a.55 .55 0 0 1 -1 0L10 14L3 10.5a.55 .55 0 0 1 0 -1L21 3"></path>
                            </svg>
                            Enviar mensaje
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script-content')
<script> var logeado = {{Auth::user()->id}}</script>
<script src="{{asset('js/configCC.js')}}"></script>
<script src="{{asset('js/chat.js')}}"></script>
@endsection
