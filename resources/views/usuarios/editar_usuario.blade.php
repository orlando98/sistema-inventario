@extends('layouts.sistema')
@section('title-content' , "Editar usuario: $usuario->nombres $usuario->apellidos")
@section('content')
@csrf

<!-- Page title -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title"> Editar usuario: {{$usuario->nombres}} {{$usuario->apellidos}}</h2>
        </div>
    </div>
    <div class="text-right">
        <a href="{{route('usuariosView')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>&nbsp;  Regresar</a>
    </div>

</div>

<!-- contenido -->
<div class="box">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <input type="hidden" id="id_usuario" value="{{$usuario->id}}">
                <!-- Nombres -->
                <div class="col-md-4 mb-2">
                    <label class="form-label required">Nombres</label>
                    <input type="text" id="nombres" class="form-control text-uppercase mb-2" value="{{$usuario->nombres}}" onkeypress="return valideKeyLetras(event);" onchange="validaterRegister(this.id)" autocomplete="off">
                </div>

                <!-- Apellidos -->
                <div class="col-md-4 mb-2">
                    <label class="form-label required">Apellidos</label>
                    <input type="text" id="apellidos" class="form-control text-uppercase mb-2" value="{{$usuario->apellidos}}" onkeypress="return valideKeyLetras(event);" onchange="validaterRegister(this.id)" autocomplete="off">
                </div>

                <!-- Correo -->
                <div class="col-md-4 mb-2">
                    <label class="form-label required">Correo</label>
                    <div class="input-icon mb-2">
                        <input type="email" id="email" class="form-control mb-2" value="{{$usuario->email}}" onchange="validateEmail(this.id)" autocomplete="off">
                        <input type="hidden" id="email_old" value="{{$usuario->email}}">
                        <span class="input-icon-addon" style="display: none" id="email_spinner">
                            <div class="spinner-border spinner-border-sm text-muted" role="status"></div>
                        </span>
                    </div>
                    <div class="invalid-feedback" id="email-text-has-error"><span id="email-mensaje"></span></div>
                    <input type="hidden" id="email_final" value="success">
                </div>

                <!-- Usuario -->
                <div class="col-md-4 mb-2">
                    <label class="form-label required">Usuario</label>
                    <div class="input-icon mb-2">
                        <input type="text" id="nombre_usuario" class="form-control text-uppercase mb-2" value="{{$usuario->username}}" onchange="validateNombreUsuario(this.id)" autocomplete="off">
                        <input type="hidden" id="nombre_usuario_old" value="{{$usuario->username}}">
                        <span class="input-icon-addon" style="display: none" id="nombre_usuario_spinner">
                            <div class="spinner-border spinner-border-sm text-muted" role="status"></div>
                        </span>
                    </div>
                    <div class="invalid-feedback" id="nombre_usuario-text-has-error"><span id="nombre_usuario-mensaje"></span></div>
                    <input type="hidden" id="usuario_final" value="success">
                </div>

                <!-- Rol -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Tipo usuario</label>
                    <select id="rol" class="form-select mb-2" onchange="validaterRegister(this.id)">
                        <option value="Administrador" {{$usuario->rol == "Administrador" ? "selected": ""}}>Administrador</option>
                        <option value="Centro" {{$usuario->rol == "Centro" ? "selected": ""}}>Centro</option>
                        <option value="Clinica" {{$usuario->rol == "Clinica" ? "selected": ""}}>Clínica</option>
                    </select>
                </div>

                <!-- Genero -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Género</label>
                    <select id="genero" class="form-select imb-2">
                        <option value="1" {{$usuario->genero == "1" ? "selected": ""}}>Masculino</option>
                        <option value="2" {{$usuario->genero == "2" ? "selected": ""}}>Femenino</option>
                        <option value="3" {{$usuario->genero == "3" ? "selected": ""}}>Otro</option>
                    </select>
                </div>

                <!-- Estado -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Estado</label>
                    <select id="estado" class="form-select mb-2">
                        <option value="1" {{$usuario->estado == "1" ? "selected": ""}}>Activo</option>
                        <option value="0" {{$usuario->estado == "0" ? "selected": ""}}>Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer	text-right">
            <div class="d-flex">
                <button type="button" class="btn btn-success ml-auto" id="btnCreateRegister"><i class="fas fa-check"></i>&nbsp; Actualizar usuario</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script-content')
<script>
    function valideKey(evt) {
        var code = (evt.which) ? evt.which : evt.keyCode;
        // 8 backspace.
        if (code == 8) {
            return true;
        } else if (code >= 48 && code <= 57) {
            return true;
        } else {
            return false;
        }
    }

    function valideKeyLetras(evt) {
        var code = (evt.which) ? evt.which : evt.keyCode;
        if (code >= 48 && code <= 57) {
            return false;
        } else {
            return true;
        }
    }

    function validaterRegister(parametro) {
        var n = document.getElementById(parametro).value
        if (n == "") {
            document.getElementById(parametro).classList.remove('is-valid')
            document.getElementById(parametro).classList.add('is-invalid')
            return "error"
        } else {
            document.getElementById(parametro).classList.remove('is-invalid')
            document.getElementById(parametro).classList.add('is-valid')
            return "success"
        }
    }

    function validateNombreUsuario(parametro) {
        var old = document.getElementById(parametro+'_old').value
        var n = document.getElementById(parametro).value

        document.getElementById('usuario_final').value = "error"
        document.getElementById(parametro+'-text-has-error').style.display = "none"
        document.getElementById(parametro+'-mensaje').innerHTML = ""

        if(n == ""){
            console.log("vacio")
            document.getElementById(parametro).classList.add('is-invalid')
            document.getElementById('usuario_final').value = "error"
        }else{
            if( (n.toUpperCase()) == (old.toUpperCase())){
                console.log("son iguales")
                document.getElementById('usuario_final').value = "success"
                document.getElementById(parametro).classList.remove('is-invalid')
                document.getElementById(parametro).classList.add('is-valid')
            }else{
                console.log("consultando")
                document.getElementById(parametro).classList.remove('is-invalid')
                document.getElementById(parametro).classList.remove('is-valid')
                document.getElementById(parametro+'-text-has-error').style.display = "none"
                document.getElementById(parametro+'_spinner').style.display = "block"

                fetch('/consulta/username/'+n)
                .then(response => response.json()).then(data => {
                    if(data){
                        if(data.success){
                            document.getElementById(parametro).classList.remove('is-invalid')
                            document.getElementById(parametro).classList.add('is-valid')
                            document.getElementById(parametro+'-text-has-error').style.display = "block"
                            document.getElementById('usuario_final').value = "success"

                            document.getElementById(parametro+'-mensaje').innerHTML = ""
                        }

                        if(data.error){
                            document.getElementById(parametro).classList.remove('is-valid')
                            document.getElementById(parametro).classList.add('is-invalid')

                            document.getElementById(parametro+'-text-has-error').style.display = "block"
                            document.getElementById(parametro+'-mensaje').innerText = data.error
                            document.getElementById('usuario_final').value = "error"
                        }
                    }
                })
                .catch(error => {console.error("mostrando error "+error)})
                .finally(()=>{
                    document.getElementById(parametro+'_spinner').style.display = "none"
                })
            }
        }
    }

    function validateEmail(parametro) {
        var old = document.getElementById(parametro+'_old').value
        var n = document.getElementById(parametro).value
        document.getElementById('email_final').value = "error"
        document.getElementById(parametro+'-text-has-error').style.display = "none"
        document.getElementById(parametro+'-mensaje').innerHTML = ""


        if(n == ""){
            console.log("email vacio")
            document.getElementById(parametro).classList.add('is-invalid')
            document.getElementById('email_final').value = "error"

        }else{
            if( (n.toLowerCase()) == (old.toLowerCase())){
                console.log("email son iguales")
                document.getElementById('email_final').value = "success"
                document.getElementById(parametro).classList.remove('is-invalid')
                document.getElementById(parametro).classList.add('is-valid')

            }else{
                console.log("email consultando")
                document.getElementById(parametro).classList.remove('is-invalid')
                document.getElementById(parametro).classList.remove('is-valid')
                document.getElementById(parametro+'-text-has-error').style.display = "none"
                document.getElementById(parametro+'_spinner').style.display = "block"

                fetch('/consulta/email/'+n)
                .then(response => response.json())
                .then(data => {
                    if(data){
                        if(data.success){
                            document.getElementById(parametro).classList.remove('is-invalid')
                            document.getElementById(parametro).classList.add('is-valid')
                            document.getElementById(parametro+'-text-has-error').style.display = "block"
                            document.getElementById('email_final').value = "success"

                            document.getElementById(parametro+'-mensaje').innerHTML = ""
                        }

                        if(data.error){
                            document.getElementById(parametro).classList.remove('is-valid')
                            document.getElementById(parametro).classList.add('is-invalid')

                            document.getElementById(parametro+'-text-has-error').style.display = "block"
                            document.getElementById(parametro+'-mensaje').innerText = data.error
                            document.getElementById('email_final').value = "error"
                        }
                    }
                })
                .catch(error => {console.error("mostrando error "+error)})
                .finally(()=>{
                    document.getElementById(parametro+'_spinner').style.display = "none"
                })
            }
        }
    }

    document.querySelector('#btnCreateRegister').addEventListener('click', () => {
        var nombre = validaterRegister('nombres')
        var apellidos = validaterRegister('apellidos')
        var username = document.getElementById('usuario_final').value
        var correo = document.getElementById('email_final').value
        var tipo = validaterRegister('rol')

        if (nombre == "success" && apellidos == "success" && username == "success" && tipo == "success" && correo == "success") {
            $("#btnCreateRegister").attr('disabled', true).text("Enviando..").append(spinner)

            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData()
            formData.append('id', document.getElementById('id_usuario').value)
            formData.append('nombres', document.getElementById('nombres').value)
            formData.append('apellidos', document.getElementById('apellidos').value)
            formData.append('username', document.getElementById('nombre_usuario').value)
            formData.append('email', document.getElementById('email').value)
            formData.append('rol', document.getElementById('rol').value)
            formData.append('genero', document.getElementById('genero').value)
            formData.append('estado', document.getElementById('estado').value)

            fetch('/editar/usuario/post', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': csrf.value}, body: formData
                })
                .then(response => response.json()).then(data => {
                    console.log(data)
                    if (data) {
                        if (data.success) {
                            console.log(data.success)
                            window.location.href = "/usuarios";
                        }

                        if (data.error) {
                            console.log(data.error)
                            // window.location.href = "/pacientes";
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
