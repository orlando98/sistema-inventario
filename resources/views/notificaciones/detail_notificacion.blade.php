@extends('layouts.sistema')
@php $fecha_notifi = date('d/m/Y', strtotime($detalle->created_at));@endphp
@section('title-content' , "Notificacion $fecha_notifi")
@section('content')

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">Notificacion fecha: {{date('d/m/Y', strtotime($detalle->created_at))}}</h2>
        </div>
    </div>
    <div class="text-right">
        <a href="{{route('notificacionesView')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>&nbsp;  Regresar</a>
    </div>
</div>

<!-- contenido -->
<div class="box">
    <div class="card">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead style="background-color:#ABF975;">
                    <tr>
                    <b> <th style="color:black" width="15%" class="text-left">Producto</th> </b>
                    <b> <th style="color:black" width="10%" class="text-left">Área</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Lote</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Fecha exp.</th> </b>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $item)
                        <tr>
                            <td class="text-left">{{$item->prod_nombre}} <br> <small>{{$item->prod_descripcion}}</small></td>
                            <td class="text-left">{{$item->area_nombre == null ? '--' : ($item->area_nombre." - ".$item->tipo_area_nombre)}}</td>
                            <td class="text-center">{{$item->arprod_lote == null ? '--' : $item->arprod_lote}}</td>
                            <td class="text-center">{{$item->arprod_fecha_c == null ? '--' :  date('d/m/Y' , strtotime($item->arprod_fecha_c))}}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script-content')

@endsection
