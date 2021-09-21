@extends('layouts.sistema')
@section('title-content' , 'Reporte kardex (Centro)')
@section('content')
<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">
               Reporte kardex (Clínica)
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
        <div class="form-group mb-2 col-md-2">
            <label class="form-label">Fecha fin</label>
            <input type="date" class="form-control" id="fecha_f" value="{{date('Y-m-d')}}" >
        </div>

        <div class="form-group mb-2 col-md-4">
            <label class="form-label">Área</label>
            <select id="area_fk" class="form-select" onchange="validaterSearch(this.id)">
                <option value="" selected disabled>Seleccionar....</option>
                <optgroup label="GENERAL"><option value="todas">TODAS</option></optgroup>
                <optgroup label="{{mb_strtoupper($areas->lugar)}}">
                    @foreach ($tipos as $item)
                        <option value="{{$areas->idArea}}-{{$item->idTipo}}">{{mb_strtoupper($item->nombre)}}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        <div class="form-group mb-2 col-md-4" style="display: flex; justify-content: center; align-items: flex-end; grid-gap: 10px;">
            <button type="button" class="btn btn-primary" onclick="searchFilters()" id="btn_search"><i class="fas fa-search"></i>&nbsp; Buscar</button>
            <button type="button" class="btn btn-secondary" onclick=" cleanSearch()"><i class="fas fa-eraser"></i>&nbsp; Limpiar</button>
        
            <div class="ml-3" id="pdf_generate" style="display: none">
                <a href="javascript:" class="btn btn-danger" target="_blank" id="btn_pdf" data-accion="true">
                    <i class="fas fa-file-pdf"></i>&nbsp; PDF
                </a>
            </div>
        </div>

    </div>

    <!--TABLA -->
    <div class="card">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
            <thead style="background-color:#ABF975;">
                    <tr>
                    <b> <th style="color:black" width="10%" class="text-left">Fecha movimiento</th> </b>
                    <b> <th style="color:black" width="20%" class="text-left">Producto</th> </b>
                    <b> <th style="color:black" width="5%" class="text-left">Lote</th> </b>
                    <b> <th style="color:black" width="20%" class="text-left">Área</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Ingreso</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Egreso</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Saldo</th> </b>
                        {{-- <th width="10%" class="text-center">F. Caducidad</th> --}}
                    </tr>
                </thead>
                <tbody id="tbody_moviminetos">
                    <tr>
                        <td colspan="7" class="text-left">No se ha realizado una búsqueda.</td>
                    </tr>
                </tbody>
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
        var area = validaterSearch('area_fk')

        document.getElementById('pdf_generate').style.display = "none"

        if(area == "success"){
            document.getElementById('tbody_moviminetos').innerHTML = ""
            var tr_spinner = `<tr><td colspan="7">Cargando.. <i class="fas fa-circle-notch fa-spin"></i></td></tr>`
            document.getElementById('tbody_moviminetos').innerHTML = tr_spinner

            $('#btn_search').attr('disabled', true).text("").append(spinner, "\u00a0 Buscando");
            var area = document.getElementById('area_fk').value
            var fecha_inicio = document.getElementById('fecha_i').value
            var fecha_fin = document.getElementById('fecha_f').value

            fetch(`/filtro/reporte/kardex/clinica/parametros/${fecha_inicio}/${fecha_fin}/${area}`)
            .then(response => response.json()).then(data => {
                console.log(data)
                if(data.productos.length > 0){
                    var sumatoria = data.total_anterior;
                    var total_ingreso = 0;
                    var total_egresos = 0;
                    document.getElementById('tbody_moviminetos').innerHTML = ""
                    var tr_foot = document.createElement('tr')
                        tr_foot.style.backgroundColor = "#FAFBFB"
                        var td_foot_texto = document.createElement('td')
                            td_foot_texto.setAttribute("class", "text-right")
                            td_foot_texto.setAttribute("colspan", "6")
                            td_foot_texto.innerText = "SALDO ANTERIOR"
                        tr_foot.append(td_foot_texto)

                        var td_foot_saldo = document.createElement('td')
                            td_foot_saldo.setAttribute("class", "text-center")
                            td_foot_saldo.innerText = data.total_anterior
                        tr_foot.append(td_foot_saldo)

                    document.getElementById('tbody_moviminetos').append(tr_foot)
                    data.productos.forEach(element => {
                        var tr_general = document.createElement('tr')

                            var date_created = (element.created_at).split(" "); var fecha = date_created[0].split("-");

                            var td0 = document.createElement('td')
                                td0.innerText = (fecha[2]+"/"+fecha[1]+"/"+fecha[0]).toString();

                            var td1 = document.createElement('td')
                                td1.innerText = element.producto;

                            var td2 = document.createElement('td')
                                td2.innerText = element.lote;

                            var td3 = document.createElement('td')
                                // td3.innerText = (element.area_nombre +' - '+element.area_tipo).toString();
                                td3.innerText = (element.area +' - '+element.tipo_area).toString();

                            var cantidad_ingreso = "--";
                            if (element.tipo_movimiento == "INGRESO") {
                                cantidad_ingreso = element.cantidad;
                                sumatoria += parseInt(element.cantidad)
                                total_ingreso += parseInt(element.cantidad)
                            }


                            var td4 = document.createElement('td')
                                td4.setAttribute('class' , 'text-center')
                                td4.innerText = cantidad_ingreso;

                            var cantidad_egreso = "--";
                            if (element.tipo_movimiento == "EGRESO") {
                                cantidad_egreso = element.cantidad;
                                sumatoria -= parseInt(element.cantidad)
                                total_egresos += parseInt(element.cantidad)
                            }

                            var td5 = document.createElement('td')
                                td5.setAttribute('class' , 'text-center')
                                td5.innerText = cantidad_egreso;

                            var td6 = document.createElement('td')
                                td6.setAttribute('class' , 'text-center')
                                td6.innerText = sumatoria;
                            // var date_cadu = (element.fecha_cadu).split(" "); var fecha_cadu = date_cadu[0].split("-");
                            // var td5 = document.createElement('td')
                            //     td5.setAttribute('class' , 'text-center')
                            //     td5.innerText = (fecha_cadu[2]+"/"+fecha_cadu[1]+"/"+fecha_cadu[0]).toString();

                        tr_general.append(td0)
                        tr_general.append(td1)
                        tr_general.append(td2)
                        tr_general.append(td3)
                        tr_general.append(td4)
                        tr_general.append(td5)
                        tr_general.append(td6)
                        document.getElementById('tbody_moviminetos').append(tr_general)
                    });
                    var tr_foot = document.createElement('tr')
                        tr_foot.style.backgroundColor = "#FAFBFB"
                        var td_foot_texto = document.createElement('td')
                            td_foot_texto.setAttribute("class", "text-right")
                            td_foot_texto.setAttribute("colspan", "4")
                            td_foot_texto.innerText = "TOTALES"
                        tr_foot.append(td_foot_texto)

                        var td_foot_ingresos = document.createElement('td')
                            td_foot_ingresos.setAttribute("class", "text-center")
                            td_foot_ingresos.innerText = total_ingreso
                        tr_foot.append(td_foot_ingresos)

                        var td_foot_egresos = document.createElement('td')
                            td_foot_egresos.setAttribute("class", "text-center")
                            td_foot_egresos.innerText = total_egresos
                        tr_foot.append(td_foot_egresos)

                        var td_foot_saldo = document.createElement('td')
                            td_foot_saldo.setAttribute("class", "text-center")
                            td_foot_saldo.innerText = sumatoria
                        tr_foot.append(td_foot_saldo)

                    document.getElementById('tbody_moviminetos').append(tr_foot)

                    document.getElementById('pdf_generate').style.display = "block"

                    var btnPDF = document.getElementById("btn_pdf");
                        btnPDF.setAttribute('href', `/PDF/reporte/kardex/clinica/${fecha_inicio}/${fecha_fin}/${area}`)

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

        document.getElementById('area_fk').value = ""
        document.getElementById('area_fk').classList.remove('is-valid')
        document.getElementById('area_fk').classList.remove('is-invalid')

        document.getElementById('pdf_generate').style.display = "none"

    }

</script>
@endsection
