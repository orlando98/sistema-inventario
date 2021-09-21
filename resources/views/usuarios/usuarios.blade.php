@extends('layouts.sistema')
@section('title-content' , 'Pacientes')
@section('content')

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">Usuarios</h2>
        </div>
    </div>
    <div class="text-right">
        <a href="{{route('nuevoUsuarioView')}}" class="btn btn-primary" ><i class="fas fa-user-plus"></i>&nbsp;  Agregar usuario</a>
    </div>

</div>

<!-- contenido -->
<div class="box">
    <div class="card">
        <div class="table-responsive">
            <table  class="table card-table table-vcenter text-nowrap datatable">
                <thead style="background-color:#ABF975;">
                    <tr>
                    <b> <th style="color:black" width="5%" class="text-center" class="w-1">No.</th></b>
                    <b> <th style="color:black" width="10%" class="text-center">Nombre de usuario</th></b>
                    <b> <th style="color:black" width="20%" class="text-center">Nombre</th></b>
                    <b> <th style="color:black" width="20%" class="text-center">Apellidos</th></b>
                        {{-- <th width="5%" class="text-center">Correo</th> --}}
                        {{-- <th width="10%" class="text-center">Área</th> --}}
                    <b> <th style="color:black" width="10%" class="text-center">Rol</th> </b>
                        <th width="5%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (sizeof($usuarios) == 0)
                        <tr><td colspan="5">Aún no hay usuarios registrados.</td></tr>
                    @else
                        @php $contador = 1; @endphp
                        @foreach ($usuarios as $item)
                        <tr>
                            <td class="text-center">{{$contador++}}</td>
                            <td class="text-center">{{$item->username}}</td>
                            <td class="text-center">{{$item->nombres}}</td>
                            <td class="text-center">{{$item->apellidos}}</td>
                            {{-- <td class="text-center">{{$item->email}}</td> --}}
                            <td class="text-center">{{$item->rol}}</td>
                            {{-- <td class="text-center">{{$item->nombre_area}}</td> --}}
                            <td class="text-right">
                                <span class="dropdown ml-1 position-static">
                                    <button class="btn btn-white btn-sm dropdown-toggle align-text-top" data-boundary="viewport" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i>&nbsp; Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                        <a class="dropdown-item" href="{{ route('editarUsuarioView', ['token'=>$item->token]) }}"><i class="fas fa-pencil-alt"></i>&nbsp; Editar usuario</a>
                                        <a class="dropdown-item" href="#" onclick="deleteThis({{$item->id}})"><i class="fas fa-trash"></i>&nbsp; Eliminar usuario</a>
                                    </div>
                                </span>
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
    //################# ELIMINAR ######################
    function deleteThis(params) {
        if(confirm('Está seguro de eliminar este usuario?')){
            window.location.href = "/eliminar/usuario/"+params;
        }
    }
</script>
@endsection
