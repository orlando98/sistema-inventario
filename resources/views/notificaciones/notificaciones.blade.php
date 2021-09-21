@extends('layouts.sistema')
@section('title-content' , 'Notificaciones')
@section('content')
@csrf

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">Notificaciones (Productos por vencer)</h2>
        </div>
    </div>
</div>

<!-- contenido -->
<div class="box">
    <div class="card">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead style="background-color:#ABF975;">
                    <tr>
                    <b> <th style="color:black" width="15%" class="text-left">Titulo</th> </b> 
                    <b> <th style="color:black" width="10%" class="text-left">Descripción</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Fecha notificación</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Estado</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Ver detalles</th> </b>
                    </tr>
                </thead>
                <tbody id="tbody_notificacion">
                    @if (sizeof($notificaciones) == 0)
                        <tr><td colspan="5">Aún no hay notificaciones.</td></tr>
                    @else
                        @foreach ($notificaciones as $item)
                            <tr>
                                <td class="text-left">{{$item->titulo}}</td>
                                <td class="text-left">{{$item->detalle}}</td>
                                <td class="text-center">{{date('d/m/Y' , strtotime($item->created_at))}}</td>
                                <td class="text-center">
                                    @if ($item->estado == 1)<span class="badge bg-success">Activa</span> @endif
                                    @if ($item->estado == 0)<span class="badge bg-warning">Leído</span> @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('notificacionesDetailView', ['id'=>1]) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                    @if ($item->estado == 1)
                                    <button type="button" class="btn btn-sm btn-success" id="btnCheck_{{$item->idNotificacion}}" title="Marcar como leído" onclick="checkNotify2({{$item->idNotificacion}})"><i class="fas fa-check"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script-content')
<script>
    function checkNotify2(id) {
        $("#btnCheck_"+id).attr('disabled', true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();  formData.append('id', id);
        fetch('/notificacion/marcar-como-leido',{ method: 'POST', headers: { 'X-CSRF-TOKEN': csrf.value}, body: formData})
        .then(response => response.json()).then(data => {
            console.log(data)
            window.location.href = "/notificaciones";
            toastr.success('Notificación marcada con éxito.');
        })
        .catch(error =>{ console.error("mostrando error: " + error.message);})
        .finally(()=>{
            $("#content_notificaciones2").load(location.href+" #content_notificaciones2>*","");
        });
    }
</script>
@endsection



