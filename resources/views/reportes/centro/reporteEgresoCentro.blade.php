@extends('layouts.sistema')
@section('title-content' , 'Reporte egresos (Centro)')
@section('content')
<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">
               Reporte egresos (Centro)
            </h2>
        </div>
    </div>
</div>

<!-- contenido -->
<div class="box">
    <!-- FILTRO -->
    <div class="row mb-4">
        <div class="form-group mb-2 col-md-2">
            <label class="form-label">Fecha inicio</label>
            <input type="date" class="form-control" id="fecha_i" value="{{date('Y-m-d')}}" >
        </div>
        <div class="form-group mb-2 col-md-3">
            <label class="form-label">Fecha fin</label>
            <input type="date" class="form-control" id="fecha_f" value="{{date('Y-m-d')}}" >
        </div>
        <div class="form-group mb-2 col-md-2">
            <label class="form-label">Área</label>
            <select id="area_fk" class="form-select">
                <option value="" selected disabled>Seleccionar....</option>
                <optgroup label="GENERAL">
                    <option value="todas">TODAS</option>
                </optgroup>
                <optgroup label="{{mb_strtoupper($areas->lugar)}}">
                    @foreach ($tipos as $item2)
                        @if ($item2->area_fk == $areas->idArea)
                            <option value="{{$areas->idArea}}-{{$item2->idTipo}}">{{mb_strtoupper($item2->nombre)}}</option>
                        @endif
                    @endforeach
                </optgroup>
            </select>
        </div>
        <div class="form-group mb-2 col-md-2" style="display: flex; align-items: flex-end;">
            <label class="form-check">
                <span class="form-check-label">Mostrar pacientes</span>
                <input class="form-check-input" type="checkbox" id="pacientes_check">
            </label>
        </div>
        <div class="form-group mb-2 col-md-3" style="display: flex; grid-gap: 5px; align-items: flex-end;">
            <div><button type="button" class="btn btn-primary" onclick="searchFilters()" id="btn_search"><i class="fas fa-search"></i>&nbsp; Buscar</button></div>
            <div><button type="button" class="btn btn-secondary" style="width: 76px;" onclick=" cleanSearch()"><i class="fas fa-eraser"></i> Limpiar</button></div>
            <div class="" id="pdf_generate" style="display: none;">
                <a href="javascript:" class="btn btn-danger" target="_blank" id="btn_pdf" data-accion="true"><i class="fas fa-file-pdf"></i>PDF</a>
            </div>
        </div>
    </div>

    <!--TABLA -->
    <div class="card">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead style="background-color:#ABF975;">
                    <tr>
                    <b> <th style="color:black" width="20%" class="text-left">Nombre</th> </b>
                    <b> <th style="color:black" width="5%" class="text-left">Lote</th> </b>
                    <b> <th style="color:black" width="20%" class="text-left">Área</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Paciente</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Cantidad</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">F. Movimiento</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Responsable</th> </b>
                    </tr>
                </thead>
                <tbody id="tbody_moviminetos"></tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('script-content')
<script>
    function validaterSearch(parametro) {
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

    function searchFilters(){
        var area = validaterSearch('area_fk');

        document.getElementById('pdf_generate').style.display = "none"
        document.getElementById('tbody_moviminetos').innerHTML = ""

        if(area == "success"){
            var checkeado = document.getElementById('pacientes_check').checked
            var pacientes = (checkeado == true ? (pacientes = 1) : (pacientes = 0) );

            var tr_spinner = `<tr><td colspan="7">Cargando.. <i class="fas fa-circle-notch fa-spin"></i></td></tr>`
            document.getElementById('tbody_moviminetos').innerHTML = tr_spinner

            $('#btn_search').attr('disabled', true).text("").append(spinner, "\u00a0 Buscando");
            var fecha_inicio = document.getElementById('fecha_i').value
            var fecha_fin = document.getElementById('fecha_f').value
            var area = document.getElementById('area_fk').value

            fetch(`/filtro/reporte/egreso/centro/parametros/${fecha_inicio}/${fecha_fin}/${area}`)
            .then(response => response.json()).then(data => {
                    console.log(data)
                if(data.length > 0){
                    document.getElementById('tbody_moviminetos').innerHTML = ""
                    data.forEach(element => {
                        var tr_general = document.createElement('tr')
                            var td1 = document.createElement('td')
                                td1.innerText = element.producto_nombre;

                            var td2 = document.createElement('td')
                                td2.innerText = element.lote;

                            var td3 = document.createElement('td')
                                td3.innerText = (element.area_nombre +' - '+element.area_tipo).toString();

                                // pacientes
                            var td4 = document.createElement('td')
                                td4.setAttribute('class' , 'text-center')
                                pacientes == 1 ? (
                                    td4.innerText = element.paciente_nombre + ' ' + element.paciente_apellido
                                ) : (
                                    td4.innerText = '--'
                                )


                            var td5 = document.createElement('td')
                                td5.setAttribute('class' , 'text-center')
                                td5.innerText = element.cantidad;

                            var date_time = (element.fecha_egreso).split(" "); var fecha = date_time[0].split("-");
                            var td6 = document.createElement('td')
                                td6.setAttribute('class' , 'text-center')
                                td6.innerText = (fecha[2]+"/"+fecha[1]+"/"+fecha[0]).toString();

                            var td7 = document.createElement('td')
                                td7.setAttribute('class' , 'text-center')
                                td7.innerText = element.usuario_username;

                        tr_general.append(td1)
                        tr_general.append(td2)
                        tr_general.append(td3)
                        tr_general.append(td4)
                        tr_general.append(td5)
                        tr_general.append(td6)
                        tr_general.append(td7)
                        document.getElementById('tbody_moviminetos').append(tr_general)
                    });

                    document.getElementById('pdf_generate').style.display = "block"
                    var btnPDF = document.getElementById("btn_pdf");
                        btnPDF.setAttribute('href', `/PDF/movimiento/egreso/centro/${fecha_inicio}/${fecha_fin}/${area}/${pacientes}`)

                }else{
                    document.getElementById('pdf_generate').style.display = "none"
                    document.getElementById('tbody_moviminetos').innerHTML = ""
                    var tr_noexis = `<tr><td colspan="7">No se encontraron resultados.</td></tr>`
                    document.getElementById('tbody_moviminetos').innerHTML = tr_noexis
                }
            })
            .catch(error => {console.error('MOSTRANDO ERROR: '+error)})
            .finally(()=>{
                $('#btn_search').attr('disabled', false).text("").append(`<i class="fas fa-search"></i>\u00a0 Buscar`);
            });
        }
    }

    function cleanSearch() {
        $('#btn_search').attr('disabled', false).text("").append(`<i class="fas fa-search"></i>\u00a0 Buscar`);

        var fecha = new Date();  var mes = fecha.getMonth()+1; var dia = fecha.getDate(); var ano = fecha.getFullYear();
        if(dia<10) dia='0'+dia;
        if(mes<10) mes='0'+mes;

        document.getElementById('fecha_i').value = ano+"-"+mes+"-"+dia;
        document.getElementById('fecha_f').value = ano+"-"+mes+"-"+dia;

        document.getElementById('tbody_moviminetos').innerHTML = ""

        document.getElementById('area_fk').value = ""
        document.getElementById('paciente_fk').value = ""

        document.getElementById('pdf_generate').style.display = "none"
    }
</script>
@endsection
