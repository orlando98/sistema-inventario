@extends('layouts.sistema')
@section('title-content' , 'Productos')
@section('content')

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title"> Productos</h2>
        </div>
    </div>
    <div class="text-right">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-report"><i class="fas fa-plus"></i>&nbsp;  Agregar producto</a>
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
                    <b> <th style="color:black" width="20%" class="text-left">Nombre</th> </b>
                    <b> <th style="color:black" width="40%" class="text-left">Descripción</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Stock (general)</th> </b>
                        @if (Auth::user()->rol == "Administrador")
                        <th width="5%" class="text-center"></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (sizeof($productos) == 0)
                        <tr><td colspan="5">Aún no hay productos registrados.</td></tr>
                    @else
                        @php $contador = 1; @endphp
                        @foreach ($productos as $item)
                        <tr>
                            <td class="text-center">{{$contador++}}</td>
                            <td class="text-left">{{$item->nombre}}</td>
                            <td class="text-left">{{$item->descripcion}}</td>
                            <td class="text-center">{{$item->stock_producto == null ? 0 : $item->stock_producto}}</td>
                            @if (Auth::user()->rol == "Administrador")
                            <td class="text-right">
                                <span class="dropdown ml-1 position-static">
                                    <button class="btn btn-white btn-sm dropdown-toggle align-text-top" data-boundary="viewport" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i>&nbsp; Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-editar-registro" data-id="{{$item->idProducto}}" data-nombre="{{$item->nombre}}" data-descripcion="{{$item->descripcion}}"><i class="fas fa-pencil-alt"></i>&nbsp; Editar</a>
                                        <a class="dropdown-item" href="#" onclick="deleteThis({{$item->idProducto}})"><i class="fas fa-trash"></i>&nbsp; Eliminar</a>
                                    </div>
                                </span>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL CREAR NUEVO PACIENTE-->
<form method="POST" id="formCreateRegister">@csrf
    <div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Nombre producto</label>
                                <input type="text" class="form-control" id="nombre" onchange="validaterRegister(this.id)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" rows="2" id="descripcion"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>&nbsp; Cancelar</button>
                    <button type="button" id="btnCreateRegister" class="btn btn-success ml-auto"><i class="fas fa-check"></i>&nbsp; Crear producto</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- MODAL EDITAR PACIENTE-->
<form method="POST" id="formEditarRegister">@csrf
    <div class="modal modal-blur fade" id="modal-editar-registro" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="edit-nombre" onchange="validaterRegister(this.id)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" rows="2" id="edit-descripcion"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="idProducto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>&nbsp; Cancelar</button>
                    <button type="button" id="btnEditRegister" class="btn btn-success ml-auto"><i class="fas fa-check"></i>&nbsp; Actualizar cambios</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('script-content')
<script>
    function validaterRegister(parametro){
        var n = document.getElementById(parametro).value
        if(n == ""){
            document.getElementById(parametro).classList.remove('is-valid')
            document.getElementById(parametro).classList.add('is-invalid')
            return "error"
        }else{
            document.getElementById(parametro).classList.remove('is-invalid')
            document.getElementById(parametro).classList.add('is-valid')
            return "success"
        }
    }

    document.querySelector('#nombre').addEventListener('keypress', function (e) { if (e.key === 'Enter') { event.preventDefault(); return false; } });
    document.querySelector('#edit-nombre').addEventListener('keypress', function (e) { if (e.key === 'Enter') { event.preventDefault(); return false; }});

    document.querySelector('#btnCreateRegister').addEventListener('click', ()=>{
        var nombre = validaterRegister('nombre')
        // var descripcion = validaterRegister('descripcion')

        if(nombre == "success"){
            $("#btnCreateRegister").attr('disabled', true).text("Enviando..").append(spinner)

            var icon_btn = document.createElement('i')
                icon_btn.setAttribute('class', 'fas fa-check')
            var spin_btn = document.createElement('span')
                spin_btn.innerText = "\u00a0 Crear producto"

            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData()
                formData.append('nombre', document.getElementById('nombre').value)
                formData.append('descripcion', document.getElementById('descripcion').value)

            fetch('/crear/producto/post',{ method: 'POST', headers: { 'X-CSRF-TOKEN': csrf.value}, body: formData})
            .then(response => response.json()).then(data => {
                console.log(data)
                if(data){
                    if(data.success){
                        toastr.success(data.success)
                        window.location.href = "/productos";
                    }

                    if(data.warning){
                        toastr.warning(data.warning)
                        $("#btnCreateRegister").attr('disabled', false).text("").append(icon_btn , spin_btn)
                    }

                    if(data.error){
                        toastr.error(data.error)
                        $("#btnCreateRegister").attr('disabled', false).text("").append(icon_btn , spin_btn)
                    }
                }
            })
            .catch(error => {
                console.log("mostrando error: " + error.message);
            })
        }
    });

    //################# EDITAR ######################
    document.getElementById('modal-editar-registro').addEventListener('show.bs.modal', function (event) {
        var datos = $(event.relatedTarget);
        document.getElementById('idProducto').value = datos.data('id');

        document.getElementById('edit-nombre').value = datos.data('nombre');
        document.getElementById('edit-descripcion').value = datos.data('descripcion');
    });

    document.querySelector('#btnEditRegister').addEventListener('click', ()=>{
        var nombre = validaterRegister('edit-nombre')
        // var descripcion = validaterRegister('edit-descripcion')

        if(nombre == "success"){
            $("#btnEditRegister").attr('disabled', true).text("Enviando..").append(spinner)

            var icon_btn = document.createElement('i')
                icon_btn.setAttribute('class', 'fas fa-check')
            var spin_btn = document.createElement('span')
                spin_btn.innerText = "\u00a0 Actualizar cambios"

            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData()
                formData.append('idProducto', document.getElementById('idProducto').value)
                formData.append('nombre', document.getElementById('edit-nombre').value)
                formData.append('descripcion', document.getElementById('edit-descripcion').value)

            fetch('/editar/producto/post',{
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf.value},
                body: formData
            })
            .then(response => response.json()).then(data => {
                console.log(data)
                if(data){
                    if(data.success){
                        toastr.success(data.success)
                        window.location.href = "/productos";
                    }

                    if(data.warning){
                        toastr.warning(data.warning)
                        $("#btnEditRegister").attr('disabled', false).text("").append(icon_btn , spin_btn)
                    }

                    if(data.error){
                        toastr.error(data.error)
                        $("#btnEditRegister").attr('disabled', false).text("").append(icon_btn , spin_btn)
                    }
                }
            })
            .catch(error => {
                console.log("mostrando error: " + error.message);
            })
        }
    });

    //################# ELIMINAR ######################
    function deleteThis(params) {
        if(confirm('Está seguro de eliminar este registro?')){
            window.location.href = "/eliminar/producto/"+params;

            toastr.success('Producto eliminado con éxito.');
        }
    }
</script>
@endsection
