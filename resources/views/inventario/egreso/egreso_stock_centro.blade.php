@extends('layouts.sistema')
@section('title-content' , 'Salida de productos (Centro)')
@section('content')

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">Salida de productos (Centro)</h2>
        </div>
    </div>
    <div class="text-right">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-report"><i class="fas fa-plus"></i>&nbsp;  Salida de productos </a>
    </div>
</div>

<!-- contenido -->
<div class="box">
    <div class="card">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead style="background-color:#ABF975;">
                    <tr>
                    <b> <th style="color:black" width="10%" class="text-left">Paciente</th> </b>
                    <b> <th style="color:black" width="10%" class="text-left">Área</th> </b>
                    <b> <th style="color:black" width="20%" class="text-left">Producto</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Cantidad</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Fecha exp.</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Lote</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Responsable</th> </b>
                    </tr>
                </thead>
                <tbody>
                    @if (sizeof($movimientos) == 0)
                        <tr><td colspan="5">Aún no hay productos egresados.</td></tr>
                    @else
                        @foreach ($movimientos as $item)
                        <tr>
                            <td class="text-left">{{$item->paciente_nombres}} {{$item->paciente_apellidos}}</td>
                            <td class="text-left">{{$item->area_nombre == null ? '--' : $item->area_nombre}}</td>
                            <td class="text-left">{{$item->producto_nombre}} <br> <small>{{$item->producto_descripcion}}</small></td>
                            <td class="text-center">{{$item->cantidad_egresada}}</td>
                            <td class="text-center">{{$item->fecha_cadu == null ? '--' :  date('d/m/Y' , strtotime($item->fecha_cadu))}}</td>
                            <td class="text-center">{{$item->lote == null ? '--' : $item->lote}}</td>
                            <td class="text-center">{{$item->username}}</td>
                        </tr>
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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Salida de producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-2" id="idPaciente_fk_has-error">
                            <label class="form-label required">Paciente:</label>
                            <select id="idPaciente_fk" class="form-select select-destin" onchange="validaterRegisterPaciente(); pacienteSelect(this.value);" style="width: 100%">
                                <option value="--">Seleccionar paciente</option>

                                <optgroup label="{{mb_strtoupper($areas->lugar)}}">
                                    @foreach ($pacientes as $item2)
                                        @if ($item2->area_fk == $areas->idArea)
                                            <option value="{{$item2->idPaciente}}-{{$areas->idArea}}">{{mb_strtoupper($areas->lugar)}} | {{mb_strtoupper($item2->nombres)}} {{mb_strtoupper($item2->apellidos)}}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <input type="hidden" id="paciente_fk">
                        <input type="hidden" id="area_fk">
                        <div class="col-lg-12 mb-2">
                            <label class="form-label required">Producto:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="">Producto</th>
                                            <th class="text-center" width="10%">Stock</th>
                                            <th class="text-center" width="10%">Cantidad</th>
                                            <th class="text-center" width="5%">
                                                <button type="button" disabled class="btn btn-sm btn-primary" id="btn_add_product" onclick="generateRow();"><i class="fas fa-plus"></i></button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_productos_modal" style="background: #fff;">
                                        {{-- <tr>
                                            <td>
                                                <select id="" class="form-select form-select-custom select-destin" style="width: 100%;">
                                                    <option value="">--</option>
                                                </select>
                                            </td>
                                            <td>0</td>
                                            <td><input type="text" class="input-modal-stock" value="0" onkeypress="return vaidateInputNumbers(event);"></td>
                                            <td><button type="button" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button></td>
                                        </tr> --}}
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>&nbsp; Cancelar</button>
                    <button type="button" id="btnCreateRegister" disabled class="btn btn-success ml-auto"><i class="fas fa-check"></i>&nbsp; Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
@section('script-content')
<script>
    // ELIMINAR (DUVAL)
    function eliminarFila(obj) {
        var oTr = obj;
        while (oTr.nodeName.toLowerCase() != 'tr') {
            oTr = oTr.parentNode;
        }
        var root = oTr.parentNode;
        root.removeChild(oTr);
    }

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

    function validaterRegisterPaciente(){
        var n = document.getElementById('idPaciente_fk').value
        if(n == "--" || n == ""){
            document.getElementById('idPaciente_fk_has-error').classList.remove('has-success')
            document.getElementById('idPaciente_fk_has-error').classList.add('has-error')

            document.getElementById('btn_add_product').setAttribute('disabled', true)
            return "error"
        }else{
            document.getElementById('idPaciente_fk_has-error').classList.remove('has-error')
            document.getElementById('idPaciente_fk_has-error').classList.add('has-success')
            document.getElementById('btn_add_product').removeAttribute('disabled')
            return "success"
        }
    }

    function pacienteSelect(ids){
        var identificadores = ids.split('-')
        var idPaciente = identificadores[0]
        var idArea = identificadores[1]

        var area = document.getElementById('area_fk').value
        if(area != idArea){
            document.getElementById('tbody_productos_modal').innerHTML = ""
            document.getElementById('btnCreateRegister').setAttribute('disabled' , true)
        }

        document.getElementById('paciente_fk').value = ""
        document.getElementById('paciente_fk').value = idPaciente

        document.getElementById('area_fk').value = idArea
    }

    function OpcionChange(identidad , receptor){
        let idproducto = document.getElementById(identidad).value
        fetch('/detalles/producto/'+idproducto+'/opcion').then((response) => response.json()).then((response)=>{
            document.getElementById(receptor).innerHTML= response.producto_stock
        })
    }

    function generateRow() {
        var idPaciente = document.getElementById('paciente_fk').value

        var tr_general = document.createElement('tr')
            tr_general.setAttribute('class' , 'tr_cont')
            tr_general.style.verticalAlign = "middle";

            //producto
            var td_1 = document.createElement('td');
                var limitante = document.getElementsByClassName('tr_cont');
                limitante = limitante.length + 1;

                var spin_td = document.createElement('i')
                    spin_td.append(spinner)
                    td_1.append(spin_td)
                var select = document.createElement('select');
                    select.setAttribute('class' , 'form-select select-destin contSelect');
                    select.style.width = "100%";
                fetch('/consulta/productos/'+idPaciente).then(response => response.json()).then(data =>{

                    td_1.innerHTML = ""
                    select.setAttribute('id' , 'select_id_'+limitante)
                    select.setAttribute('onchange' , `OpcionChange('select_id_${limitante}', 'select_escoger_${limitante}');`)
                    var option2 = document.createElement('option')
                    option2.value = ""
                    option2.innerText = "Seleccionar.."
                    select.append(option2)
                    data.forEach(element => {

                        var date_time = (element.fecha_expira).split(" "); var fecha = date_time[0].split("-");
                        var option = document.createElement('option')
                            option.value = element.producto_id
                            option.innerText = (element.producto_nombre +' (C: '+ element.producto_stock+') (EXP: '+fecha[2]+"/"+fecha[1]+"/"+fecha[0]+')').toString();
                        select.append(option)
                    });
                })
                .catch(error => console.error('MOSTRANDO ERROR :'+error))
                .finally(()=>{
                    td_1.append(select)
                    $('.select-destin').select2();
                })
            //============

            //stock
            var td_2 = document.createElement('td')
                td_2.setAttribute('class' , 'text-center contStock')
                td_2.setAttribute('id' , 'select_escoger_'+limitante)
            td_2.innerText = "0"

            //cantidad
            var td_3 = document.createElement('td')
                td_3.setAttribute('class','contCant')
                td_3.setAttribute('id','cantidad-has-error_'+limitante)
                var cantidad = document.createElement('input')
                    cantidad.setAttribute('type' , 'text')
                    cantidad.setAttribute('class' , 'input-modal-stock cantInput')
                    cantidad.setAttribute('value' , '0')
                    cantidad.setAttribute('id' , 'cantidad_producto_'+limitante)
                    cantidad.setAttribute('onkeypress' , 'return vaidateInputNumbers(event);')
                    cantidad.setAttribute('onkeyup', `validateStock(${limitante}, this.value)`)
                    cantidad.setAttribute('autocomplete', 'off')
            td_3.append(cantidad)

            //eliminar
            var td_4 = document.createElement('td')
                var btn_delete = document.createElement('button')
                btn_delete.setAttribute('type' , 'button')
                btn_delete.setAttribute('class' , 'btn btn-sm btn-danger')
                btn_delete.setAttribute('onclick' , 'eliminarFila(this); reconteoFilas();')
                var span = document.createElement('i')
                    span.setAttribute('class','fas fa-times')
                btn_delete.append(span)
            td_4.append(btn_delete)

        tr_general.append(td_1)
        tr_general.append(td_2)
        tr_general.append(td_3)
        tr_general.append(td_4)
        document.getElementById('tbody_productos_modal').append(tr_general)

        // actual++
    }

    function validateStock(limitante, cantidad) {
        var maximo = parseInt(document.getElementById('select_escoger_'+limitante).innerHTML)

        if(cantidad > maximo){
            document.getElementById('cantidad-has-error_'+limitante).classList.remove('has-success')
            document.getElementById('cantidad-has-error_'+limitante).classList.add('has-error')

            document.getElementById('btnCreateRegister').setAttribute('disabled' , true)
            document.getElementById('btn_add_product').setAttribute('disabled' , true)


            toastr.warning('La cantidad ingresada en mayor al stock disponible.')
        }

        if(cantidad <= maximo){
            document.getElementById('cantidad-has-error_'+limitante).classList.remove('has-error')
            document.getElementById('cantidad-has-error_'+limitante).classList.add('has-success')

            document.getElementById('btnCreateRegister').removeAttribute('disabled')
            document.getElementById('btn_add_product').removeAttribute('disabled')
        }

    }

    function reconteoFilas() {
        const rows = document.getElementsByClassName('tr_cont')
        for (let i = 0; i < rows.length; i++) {
            var acum = Number(i + 1)
            //select
            var select = document.getElementsByClassName('contSelect')[i];
            select.setAttribute('id', 'select_id_'+acum)
            select.setAttribute('onchange' , `OpcionChange('select_id_${acum}', 'select_escoger_${acum}');`)

            //stcok
            var stock = document.getElementsByClassName('contStock')[i];
            stock.setAttribute('id', 'select_escoger_'+acum)

            //cantidad
            var tdCant = document.getElementsByClassName('contCant')[i];
            tdCant.setAttribute('id', 'cantidad-has-error_'+acum)
            var tdCantInput = document.getElementsByClassName('cantInput')[i];
            tdCantInput.setAttribute('id' , 'cantidad_producto_'+acum)
        }
    }

    document.querySelector('#btnCreateRegister').addEventListener('click', ()=>{
        // if(producto == "success"){
            $("#btnCreateRegister").attr('disabled', true).text("Enviando..").append(spinner)
            var icon_btn = document.createElement('i')
                icon_btn.setAttribute('class', 'fas fa-check')
            var spin_btn = document.createElement('span')
                spin_btn.innerText = "\u00a0 Crear producto"

            let csrf = document.querySelector("input[name='_token']");
            var contenidoregistro = new Array();
            const rows = document.getElementsByClassName('tr_cont')
            for (let i = 0; i < rows.length; i++) {
                const elementos = new Object();
                elementos.id = document.getElementsByClassName('contSelect')[i].value;
                elementos.stock = parseInt(document.getElementsByClassName('contStock')[i].innerHTML);
                elementos.cantidad = document.getElementsByClassName('cantInput')[i].value;
                contenidoregistro.push(elementos);
            }

            const formData = new FormData()
                formData.append('paciente_fk', document.getElementById('paciente_fk').value)
            formData.append('contenido', JSON.stringify(contenidoregistro))

            fetch('/egreso/productos/post',{ method: 'POST', headers: { 'X-CSRF-TOKEN': csrf.value}, body: formData})
            .then(response => response.json()).then(data => {
                console.log(data)
                if(data){
                    if(data.success){
                        toastr.success(data.success)
                        window.location.href = "/egreso/productos";
                    }

                    if(data.error){
                        toastr.error(data.error)
                        $("#btnCreateRegister").attr('disabled', false).text("").append(icon_btn , spin_btn)
                    }
                }
            })
            .catch(error => {
                console.error("mostrando error: " + error.message);
                toastr.error('No se pudo agregar stock al producto, vuelva a intentarlo')
                $("#btnCreateRegister").attr('disabled', false).text("").append(icon_btn , spin_btn)
            })
        // }
    });

    $(document).ready(function(){
        $('.select-destin').select2();
        document.getElementById('paciente_fk').value = ""
    });
</script>
@endsection
