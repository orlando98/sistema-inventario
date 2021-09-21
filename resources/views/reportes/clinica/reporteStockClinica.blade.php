@extends('layouts.sistema')
@section('title-content' , 'Reporte stock (Clínica)')
@section('content')
<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">
               Reporte stock (Clínica)
            </h2>
        </div>
    </div>
</div>

<!-- contenido -->
<div class="box">
    <!-- FILTRO -->
    <div class="row mb-4">
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
        <div class="form-group mb-2 col-md-6" style="display: flex; justify-content: flex-end; align-items: flex-end; grid-gap: 10px;">
            <button type="button" class="btn btn-primary" onclick="searchFilters()" id="btn_search"><i class="fas fa-search"></i>&nbsp; Buscar</button>
            <button type="button" class="btn btn-secondary" onclick=" cleanSearch()"><i class="fas fa-eraser"></i>&nbsp; Limpiar</button>
        </div>
        <div class="form-group mb-2 col-md-2" style="display: flex; justify-content: flex-end; align-items: flex-end;">
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
                    <b> <th style="color:black" width="20%" class="text-left">Nombre</th> </b>
                    <b> <th style="color:black" width="5%" class="text-left">Lote</th> </b>
                    <b> <th style="color:black" width="20%" class="text-left">Área</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Stock actual</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">F. Caducidad</th> </b>
                    </tr>
                </thead>
                <tbody id="tbody_moviminetos">
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
        document.getElementById('tbody_moviminetos').innerHTML = ""

        if(area == "success"){
            var tr_spinner = `<tr><td colspan="7">Cargando.. <i class="fas fa-circle-notch fa-spin"></i></td></tr>`
            document.getElementById('tbody_moviminetos').innerHTML = tr_spinner

            $('#btn_search').attr('disabled', true).text("").append(spinner, "\u00a0 Buscando");
            var area = document.getElementById('area_fk').value

            fetch(`/filtro/reporte/stock/clinica/parametros/${area}`)
            .then(response => response.json()).then(data => {
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

                            var td4 = document.createElement('td')
                                td4.setAttribute('class' , 'text-center')
                                td4.innerText = element.cantidad;

                            var date_cadu = (element.fecha_cadu).split(" "); var fecha_cadu = date_cadu[0].split("-");
                            var td5 = document.createElement('td')
                                td5.setAttribute('class' , 'text-center')
                                td5.innerText = (fecha_cadu[2]+"/"+fecha_cadu[1]+"/"+fecha_cadu[0]).toString();

                        tr_general.append(td1)
                        tr_general.append(td2)
                        tr_general.append(td3)
                        tr_general.append(td4)
                        tr_general.append(td5)
                        document.getElementById('tbody_moviminetos').append(tr_general)
                    });

                    document.getElementById('pdf_generate').style.display = "block"

                    var btnPDF = document.getElementById("btn_pdf");
                        btnPDF.setAttribute('href', `/PDF/reporte/stock/clinica/${area}`)

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

        document.getElementById('area_fk').value = ""
        document.getElementById('area_fk').classList.remove('is-valid')
        document.getElementById('area_fk').classList.remove('is-invalid')

        document.getElementById('pdf_generate').style.display = "none"

    }

</script>
@endsection
