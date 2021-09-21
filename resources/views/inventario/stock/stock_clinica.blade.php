@extends('layouts.sistema')
@section('title-content' , 'Stock de productos (Clínica)')
@section('content')

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">Stock de productos (Clínica)</h2>
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
                    <b> <th style="color:black" width="15%" class="text-left">Nombre</th> </b>
                    <b> <th style="color:black" width="10%" class="text-left">Área</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Stock</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Fecha exp.</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Lote</th> </b>
                    </tr>
                </thead>
                <tbody>
                    @if (sizeof($productos_stock) == 0)
                        <tr><td colspan="5">Aún no hay productos registrados.</td></tr>
                    @else
                        @foreach ($productos_stock as $item)
                            {{-- @if ($item->stock_producto > 0) --}}
                                <tr>
                                    <td class="text-left">{{$item->prod_nombre}} <br> <small>{{$item->prod_descripcion}}</small></td>
                                    <td class="text-left">{{$item->area_nombre == null ? '--' : ($item->area_nombre." - ".$item->tipo_area_nombre)}}</td>
                                    <td class="text-center" >
                                        @if ($item->stock_producto == 0)
                                        <span class="badge bg-danger">0</span>
                                        @elseif ($item->stock_producto <= 30)
                                        <span class="badge bg-warning">{{$item->stock_producto}}</span>
                                        @elseif ($item->stock_producto > 30)
                                        <span class="badge bg-success">{{$item->stock_producto}}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$item->arprod_fecha_c == null ? '--' :  date('d/m/Y' , strtotime($item->arprod_fecha_c))}}</td>
                                    <td class="text-center">{{$item->arprod_lote == null ? '--' : $item->arprod_lote}}</td>
                                </tr>
                            {{-- @endif --}}
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
