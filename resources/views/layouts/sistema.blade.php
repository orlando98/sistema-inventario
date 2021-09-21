<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>PASM | @yield('title-content')</title>

        <link rel="icon" href="{{asset('img/logo.png')}}" type="image/x-icon"/>
        <link rel="shortcut icon" href="{{asset('img/logo.png')}}" type="image/x-icon"/>
        <!-- CSS files -->

        <link href="{{asset('fontawesome/css/all.css')}}" rel="stylesheet"/>
        <link href="{{asset('css/tabler.css')}}" rel="stylesheet"/>
        <link href="{{asset('css/demo.css')}}" rel="stylesheet"/>
        <link href="{{asset('toastr/toast.css')}}" rel="stylesheet"/>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="{{asset('css/custom.css')}}" rel="stylesheet"/>

    </head>
    <body class="antialiased">
        <div class="page">
            @include('layouts.includes.topbar')

            <div class="content">
                <div class="container-xl">
                    @yield('content')
                </div>

                <footer class="footer footer-transparent">
                    <div class="container">
                        <div class="row text-center align-items-center">
                            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            Copyright © <span><script>var anio = new Date(); document.write(anio.getFullYear());</script> </span>  <a href="{{route('home')}}" class="link-secondary">Sistema</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- Libs JS -->
        <script src="{{asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('fontawesome/js/all.js')}}"></script>
        <script src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{asset('libs/peity/jquery.peity.min.js')}}"></script>

        <script src="https://www.gstatic.com/firebasejs/8.2.4/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.2.4/firebase-database.js"></script>

        <script src="{{asset('toastr/toast.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        </script>
        <script>
            var spinner = document.createElement('i')
                spinner.setAttribute('class', 'fas fa-circle-notch fa-spin');

            var spinner2 = `<span class="spinner-border spinner-border-sm mr-2" role="status"></span>`
        </script>

        @yield('script-content')

        <script>
            function viewNotify(id) {
                window.location.href = "/notificacion/"+id;
            }

            function checkNotify(id) {
                document.getElementById('notifi_'+id).style.display = "none";

                let csrf = document.querySelector("input[name='_token']");
                const formData = new FormData();  formData.append('id', id)

                fetch('/notificacion/marcar-como-leido',{ method: 'POST', headers: { 'X-CSRF-TOKEN': csrf.value}, body: formData})
                .then(response => response.json()).then(data => { })
                .catch(error => {console.error("mostrando error: " + error.message); })
                .finally(()=>{  $("#content_notificaciones2").load(location.href+" #content_notificaciones2>*","");});
            }

            var conteo = 1;
            setInterval(function(){
                $("#content_notificaciones").load(location.href+" #content_notificaciones>*","");


                if(Number(document.getElementById('cant-notf').value) > 0){
                    $("#content_notificaciones2").load(location.href+" #content_notificaciones2>*","");
                    console.log("contando "+ conteo++)
                }
            }, 6000);

        </script>
    </body>
</html>
