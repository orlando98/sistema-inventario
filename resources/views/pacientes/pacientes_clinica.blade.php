@extends('layouts.sistema')
@section('title-content' , 'Pacientes clínica')
@section('content')

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title"> Pacientes clínica</h2>
        </div>
    </div>
    <div class="text-right">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-report"><i class="fas fa-plus"></i>&nbsp;  Agregar paciente</a>
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
                    <b> <th style="color:black" width="10%" class="text-center">Cédula</th> </b>
                    <b> <th style="color:black" width="20%" class="text-center">Apellidos</th> </b>
                    <b> <th style="color:black" width="20%" class="text-center">Nombres</th> </b>
                    <b> <th style="color:black" width="5%" class="text-center">Edad</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Género</th> </b>
                    <b> <th style="color:black" width="10%" class="text-center">Área</th> </b>
                    </tr>
                </thead>
                <tbody>
                    @if (sizeof($pacientes) == 0)
                        <tr><td colspan="5">Aún no hay pacientes registrados.</td></tr>
                    @else
                        @php $contador = 1; @endphp
                        @foreach ($pacientes as $item)
                        <tr>
                            <td class="text-center">{{$contador++}}</td>
                            <td class="text-center">{{$item->cedula}}</td>
                            <td class="text-center">{{$item->apellidos}}</td>
                            <td class="text-center">{{$item->nombres}}</td>
                            <td class="text-center">{{$item->edad}}</td>
                            <td class="text-center">
                                @if ($item->genero == 1) Masculino
                                @elseif ($item->genero == 2) Femenino
                                @elseif ($item->genero == 3) Otro
                                @endif
                            </td>
                            <td class="text-center">{{$item->nombre_area}}</td>
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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo paciente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Nombres</label>
                                <input type="text" class="form-control text-uppercase" id="nombres" onkeypress="return valideKeyLetras(event);" onchange="validaterRegister(this.id)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control text-uppercase" id="apellidos" onkeypress="return valideKeyLetras(event);" onchange="validaterRegister(this.id)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="cedula" onkeypress="return valideKey(event);" onchange="validaterRegister(this.id)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label class="form-label">Edad</label>
                                <input type="text" class="form-control" id="edad" onkeypress="return valideKey(event);" onchange="validaterRegister(this.id)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Género</label>
                                <select class="form-select" id="genero" onchange="validaterRegister(this.id)">
                                    <option value="" selected disabled>--</option>
                                    <option value="1">Hombre</option>
                                    <option value="2">Mujer</option>
                                    <option value="3">Otros</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Área</label>
                                <select class="form-control" id="area_fk" onchange="validaterRegister(this.id)" disabled>
                                    @foreach ($areas as $item)
                                        <option value="{{$item->idArea}}" {{$item->lugar == "Clínica" ? "selected": "" }}>{{$item->lugar}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label">Observación</label>
                            <textarea class="form-control" rows="2" id="observacion"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>&nbsp; Cancelar</button>
                    <button type="button" id="btnCreateRegister" class="btn btn-success ml-auto"><i class="fas fa-check"></i>&nbsp; Crear paciente</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
@section('script-content')
<script>

    function valideKey(evt){
		var code = (evt.which) ? evt.which : evt.keyCode;
        // 8 backspace.
		if(code==8){ return true;}else if( code>=48 && code<=57) {return true;
		}else{return false;}
	}

    function valideKeyLetras(evt){
		var code = (evt.which) ? evt.which : evt.keyCode;
		if(code >= 48 && code<=57) {return false;}else{return true;}
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

    document.querySelector('#btnCreateRegister').addEventListener('click', ()=>{
        var nombre = validaterRegister('nombres')
        var apellidos = validaterRegister('apellidos')
        var cedula = validaterRegister('cedula')
        var edad = validaterRegister('edad')
        var genero = validaterRegister('genero')
        var area_fk = validaterRegister('area_fk')

        if(nombre == "success" && apellidos == "success" && cedula == "success" && edad == "success" && genero == "success" && area_fk == "success"){
            $("#btnCreateRegister").attr('disabled', true).text("Enviando..").append(spinner)

            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData()
                formData.append('nombres', document.getElementById('nombres').value)
                formData.append('apellidos', document.getElementById('apellidos').value)
                formData.append('cedula', document.getElementById('cedula').value)
                formData.append('edad', document.getElementById('edad').value)
                formData.append('genero', document.getElementById('genero').value)
                formData.append('observacion', document.getElementById('observacion').value)
            formData.append('area_fk', document.getElementById('area_fk').value)

            fetch('/crear/paciente/post',{
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf.value},
                body: formData
            })
            .then(response => response.json()).then(data => {
                console.log(data)
                if(data){
                    if(data.success){
                        console.log(data.success)
                        window.location.href = "/pacientes";
                    }

                    if(data.error){
                        console.log(data.error)
                        window.location.href = "/pacientes";
                    }
                }
            })
            .catch(error => {
                console.log("mostrando error: " + error.message);
            })
        }
    });
</script>
@endsection
