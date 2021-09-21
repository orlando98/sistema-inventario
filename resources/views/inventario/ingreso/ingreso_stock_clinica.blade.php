@extends('layouts.sistema')
@section('title-content' , 'Entrada de productos (Clínica)')
@section('content')

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">Entrada de productos (Clínica)</h2>
        </div>
    </div>
    <div class="text-right">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-report"><i class="fas fa-plus"></i>&nbsp;  Agregar stock</a>
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
                            @if ($item->stock_producto > 0)
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
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL AGREGAR STOCK PACIENTE-->
<form method="POST" id="formCreateRegister">@csrf
    <div class="modal modal-blur fade" id="modal-report"  role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar stock al producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-2" id="idProducto_fk_has-error">
                            <label class="form-label required">Producto:</label>
                            <select id="idProducto_fk" class="form-control select-destin" onchange="validaterRegisterProduct()" style="width: 100%">
                                <option value="--">Seleccionar producto</option>
                                @foreach ($productos as $item)
                                <option value="{{$item->idProducto}}">{{$item->nombre}} ({{$item->descripcion}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label class="form-label">Lote:</label>
                            <input type="text" class="form-control" id="lote" onchange="validaterRegister(this.id)" autocomplete="off">
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label class="form-label">Fecha caducidad:</label>
                            <input type="date" class="form-control" id="fecha_cadu" onchange="validaterRegister(this.id)">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required">Área:</label>
                            <select id="area_fk" class="form-select" onchange="validaterRegister(this.id)">
                                <option value="" selected disabled>Seleccionar....</option>
                                @foreach ($tipos as $item)
                                    <option value="{{$item->idTipo}}">{{mb_strtoupper($areas->lugar)}} - {{mb_strtoupper($item->nombre)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <label class="form-label required">Cantidad:</label>
                            <div class="d-flex justify-content-center" style="grid-gap: 5px;">
                                <div><button type="button" id="resta"  onclick="sumStock(this.id)" class="btn-stock">-</button></div>
                                <div><input type="text" class="input-stock" onkeyup="sumStock(this.id)" id="input-stock" value="1"  onkeypress="return vaidateInputNumbers(event);" autocomplete="off"></div>
                                <div><button type="button" id="suma"  onclick="sumStock(this.id)" class="btn-stock">+</button></div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>&nbsp; Cancelar</button>
                    <button type="button" id="btnCreateRegister" class="btn btn-success ml-auto"><i class="fas fa-check"></i>&nbsp; Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
@section('script-content')
<script>
    function vaidateInputNumbers(evt){
		var code = (evt.which) ? evt.which : evt.keyCode;
		if(code==8){
            return true;
        }else if(code>=48 && code<=57) {
            return true;
		}else{
            return false;
        }
	}

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

    function validaterRegisterProduct(){
        var n = document.getElementById('idProducto_fk').value
        if(n == "--" || n == ""){
            document.getElementById('idProducto_fk_has-error').classList.remove('has-success')
            document.getElementById('idProducto_fk_has-error').classList.add('has-error')
            return "error"
        }else{
            document.getElementById('idProducto_fk_has-error').classList.remove('has-error')
            document.getElementById('idProducto_fk_has-error').classList.add('has-success')
            return "success"
        }
    }

    function sumStock(params){
        var conteo = 0;
        if(params == "resta"){
            if(document.getElementById('input-stock').value <= 1){
                document.getElementById('input-stock').value = Number(1)
            }else{
                document.getElementById('input-stock').value = Number(document.getElementById('input-stock').value) - Number(1)
            }
        }

        if(params == "suma"){
            document.getElementById('input-stock').value =  Number(document.getElementById('input-stock').value) + Number(1)
        }

        if(params == "input-stock"){
            if(document.getElementById('input-stock').value < 1){
                document.getElementById('input-stock').value = 1;
            }
        }

    }

    document.querySelector('#btnCreateRegister').addEventListener('click', ()=>{
        var producto = validaterRegisterProduct()
        var area_fk = validaterRegister('area_fk')

        if(area_fk == "success" && producto == "success"){
            $("#btnCreateRegister").attr('disabled', true).text("Enviando..").append(spinner)

            var icon_btn = document.createElement('i')
                icon_btn.setAttribute('class', 'fas fa-check')
            var spin_btn = document.createElement('span')
                spin_btn.innerText = "\u00a0 Crear producto"

            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData()
                formData.append('lote', document.getElementById('lote').value)
                formData.append('fecha_c', document.getElementById('fecha_cadu').value)
                formData.append('area_fk', document.getElementById('area_fk').value)
                formData.append('producto_fk', document.getElementById('idProducto_fk').value)
                formData.append('cantidad', document.getElementById('input-stock').value)

            fetch('/ingreso/productos/post',{ method: 'POST', headers: { 'X-CSRF-TOKEN': csrf.value}, body: formData})
            .then(response => response.json()).then(data => {
                console.log(data)
                if(data){
                    if(data.success){
                        toastr.success(data.success)
                        window.location.href = "/ingreso/productos";
                    }

                    if(data.error){
                        toastr.error(data.error)
                        $("#btnCreateRegister").attr('disabled', false).text("").append(icon_btn , spin_btn)
                    }
                }
            })
            .catch(error => {
                console.log("mostrando error: " + error.message);
                toastr.error('No se pudo agregar stock al producto, vuelva a intentarlo')
                $("#btnCreateRegister").attr('disabled', false).text("").append(icon_btn , spin_btn)
            })
        }
    });

    $(document).ready(function() {
    $('.select-destin').select2();
});
</script>
@endsection
