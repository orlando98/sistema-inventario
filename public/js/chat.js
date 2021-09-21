firebase.initializeApp(firebaseConfig);

var database = firebase.database();
var consulta = database.ref('chat/sala');

$(document).ready(function() {
    document.getElementById('usuario_select').value = ""
    document.getElementById('body-mensajes').innerHTML = ""
    $(document).on('click', '.usuarios-option-a', function () {
        $('.usuarios-option-a').removeClass("usuarios-select");
        $(this).addClass("usuarios-select");
    });

    var objDiv = document.getElementById("body-mensajes");
    objDiv.scrollTop = objDiv.scrollHeight;

});

function verificateNull() {
    var n = document.getElementById('mensaje_chat').value
    if(n == ""){
        document.getElementById('mensaje_chat').classList.remove('is-valid')
        document.getElementById('mensaje_chat').classList.add('is-invalid')
        return "error";
    }else{
        document.getElementById('mensaje_chat').classList.remove('is-invalid')
        document.getElementById('mensaje_chat').classList.add('is-valid')
        return "success";
    }
}

function mensajeUsers(id){
    document.getElementById('usuario_select').value = id
    document.getElementById('body-mensajes').innerHTML = ""
    fetch('/consultar/mensajes/chat/'+id)
    .then(response => response.json())
    .then(data => {
        document.getElementById('body-mensajes').innerHTML = ""
        if(data){
            if(data.success){
                var key_sala_fk = data.success
                consulta.on('value', function (snapshot) {
                    const data = snapshot.val();
                    for (let key in data) {
                        // console.log(key, data)
                        if(key == key_sala_fk){
                            generarMensajes(data[key])
                        }
                    }
                });
            }
        }
    })
    .catch(err => console.error(err))
    .finally(()=>{
        var objDiv = document.getElementById("body-mensajes");
            objDiv.scrollTop = objDiv.scrollHeight
    });
}

document.querySelector('#btnSendMensa').addEventListener('click', ()=>{
    var texto = verificateNull();
    var destino = document.getElementById('usuario_select').value

    if(texto == "success" && destino != ""){
        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData()
        formData.append('mensaje', document.getElementById('mensaje_chat').value)
        formData.append('usuario_select', document.getElementById('usuario_select').value)

        fetch('/enviar/mensajes/chat/post', {method: 'POST' , headers: {'X-CSRF-TOKEN': csrf.value}, body: formData})
        .then(response => response.json()).then(data => {
            if(data){
                if(data.success){
                    var key_sala_fk = data.success
                    consulta.on('value', function (snapshot) {
                        const data = snapshot.val();
                        for (let key in data) {
                            if(key == key_sala_fk){
                                generarMensajes(data[key])
                            }
                        }
                    });

                }
                if(data.error){
                    console.log('error en el servidor');
                }
            }
        })
        .catch(error => {
            console.log("mostrando error: " + error.message);
            toastr.error('No se pudo enviar el mensaje, vuelva a intentarlo')
        })
        .finally(()=>{
            document.getElementById('mensaje_chat').value = ""
            document.getElementById('mensaje_chat').classList.remove('is-valid')
            document.getElementById('mensaje_chat').classList.remove('is-invalid')

            var objDiv = document.getElementById("body-mensajes");
                objDiv.scrollTop = objDiv.scrollHeight
        })
    }else{
        if(destino == ""){
            toastr.error('No hay usuario seleccionado para enviar mensaje.')
        }
    }
});

function generarMensajes(data) {
    var mensajes = data.mensajes;
    // console.log(logeado)
    document.getElementById('body-mensajes').innerHTML = ""
    for (const key in mensajes) {
        var mensaje_general = document.createElement('div')
        if(mensajes[key].usuario_fk == logeado){
            mensaje_general.setAttribute('class' , 'mensaje-me')
        }else{
            mensaje_general.setAttribute('class' , 'mensaje-remitente')
        }

        var span_mensaje = document.createElement('span')
            span_mensaje.setAttribute('class', 'c-mensaje')
            span_mensaje.innerHTML = mensajes[key].mensaje

        var date_mensaje = (mensajes[key].created_at).split(" ");
        var fecha_mensaje = date_mensaje[0].split("-"); var fecha = (fecha_mensaje[2]+"/"+fecha_mensaje[1]+"/"+fecha_mensaje[0]).toString();
        var hora_mensaje = date_mensaje[1].split(":"); var hora = (hora_mensaje[0]+":"+hora_mensaje[1]).toString();
        var small_hora = document.createElement('small')
            small_hora.setAttribute('class', 'c-fecha')
            small_hora.innerHTML = fecha +' '+' '+hora

        mensaje_general.append(span_mensaje)
        mensaje_general.append(small_hora)
        document.getElementById('body-mensajes').append(mensaje_general)
    }

    var objDiv = document.getElementById("body-mensajes");
            objDiv.scrollTop = objDiv.scrollHeight
}
