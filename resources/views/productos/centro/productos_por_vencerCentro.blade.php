@extends('layouts.sistema')
@section('title-content' , 'Productos por vencer (Centro)')
@section('content')

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title"> Productos por vencer (Centro)</h2>
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
                    <b> <th style="color:black" width="5%" class="text-center" class="w-1">No.</th> </b>
                    <b> <th style="color:black" width="25%" class="text-left">Nombre</th> </b>
                    <b> <th style="color:black" width="30%" class="text-left">Área</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Lote</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Cantidad</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Fecha exp.</th> </b>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 1; @endphp
                    @foreach ($productos_stock as $item)
                    <tr>
                        <td class="text-center">{{$contador++}}</td>
                        <td class="text-left">
                            {{$item->producto_nombre}}
                            <br>
                            <small>{{$item->producto_descripcion}}</small>
                        </td>
                        <td class="text-left">{{$item->area_nombre}} - {{$item->area_tipo}}</td>
                        <td class="text-center">{{$item->lote}}</td>
                        <td class="text-center">{{$item->cantidad}}</td>
                        <td class="text-center">{{date('d/m/Y' , strtotime($item->fecha_caduc))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
