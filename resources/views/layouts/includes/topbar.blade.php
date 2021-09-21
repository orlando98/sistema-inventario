<!-- topbar -->
<header class="navbar navbar-expand-md navbar-light">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu"><span class="navbar-toggler-icon"></span></button>
        <a href="{{route('home')}}" class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pr-0 pr-md-3"> <img src="{{asset('img/logo.png')}}" style="display: block; height: 48px; width: 86px;"></a>
        <div class="navbar-nav flex-row order-md-last">

            <!-- notificaciones -- -->
            <div class="nav-item dropdown d-none d-md-flex mr-3">
                <a href="#" class="nav-link px-0" data-toggle="dropdown" tabindex="-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path></svg>
                    <span class="badge bg-red" id="content_notificaciones2"><div>{{Notificaciones::notificaciones()[1]}}</div></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-card">
                    <div class="card">
                        <div style="padding: 6px 0px 6px 11px;  border-bottom: solid 1px #ededed; font-weight: 700;" ><a href="{{ route('notificacionesView') }}">Notificaciones</a></div>
                        <div class="card-body-custom" id="content_notificaciones">
                            <input type="hidden" id="cant-notf" value="{{Notificaciones::notificaciones()[1]}}">
                            @foreach (Notificaciones::notificaciones()[0] as $item)
                            <div class="card-notify mb-3" id="notifi_{{$item->idNotificacion}}">
                                <div class="titulo check-not" title="Ver notificación" style="cursor: pointer" onclick="viewNotify({{$item->idNotificacion}})">{{$item->titulo}}</div>
                                <div class="descripcion">{{$item->detalle}}</div>
                                <div class="footer-notify">
                                    <div>{{date('d/m/Y' , strtotime(date('Y-m-d')))}}</div>
                                    <div class="ml-auto check-not" style="cursor: pointer" onclick="checkNotify({{$item->idNotificacion}});">Marcar como leído</div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <!-- DATOS USUARIO LOGEADO -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-toggle="dropdown">
                    <div class="d-none d-xl-block pl-2">
                        <div>{{Auth::user()->username}}</div>
                        <div class="mt-1 small text-muted">{{Auth::user()->rol}}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <path d="M7 6a7.75 7.75 0 1 0 10 0"></path>
                            <line x1="12" y1="4" x2="12" y2="12"></line>
                        </svg>&nbsp;  Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- navbar -->
<div class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar navbar-light">
            <div class="container-xl">
                <ul class="navbar-nav">

                    <!-- PANEL  -->
                    <li class="nav-item {{Route::is('home') ? "active" : ""}}">
                        <a class="nav-link" href="{{route('home')}}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" />
                                    <polyline points="5 12 3 12 12 3 21 12 19 12" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg></span>
                            <span class="nav-link-title"> Panel</span>
                        </a>
                    </li>

                    <!-- PRODUCTOS -->
                    <li class="nav-item dropdown {{Route::is('productosView') || Route::is('ingresoProductoView') || Route::is('egresoProductoView') || Route::is('stockView') || Route::is('productosPorVencerView')? "active" : ""}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" /><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                            </span>
                            <span class="nav-link-title">Productos</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{Route::is('productosView') ? "active" : ""}}" href="{{route('productosView')}}"> Productos</a></li>
                            <li><a class="dropdown-item {{Route::is('ingresoProductoView') ? "active" : ""}}" href="{{route('ingresoProductoView')}}">Entrada de productos</a></li>
                            <li><a class="dropdown-item {{Route::is('egresoProductoView') ? "active" : ""}}" href="{{route('egresoProductoView')}}"> Salida de productos</a></li>
                            <li><a class="dropdown-item {{Route::is('stockView') ? "active" : ""}}" href="{{route('stockView')}}"> Stock</a></li>
                            <li><a class="dropdown-item {{Route::is('productosPorVencerView') ? "active" : ""}}" href="{{route('productosPorVencerView')}}"> Productos por vencer</a></li>
                        </ul>
                    </li>

                    <!-- PACIENTES -->
                    <li class="nav-item {{Route::is('pacientesView') ? "active" : ""}}">
                        <a class="nav-link" href="{{route('pacientesView')}}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z"></path>
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M10 16.5l2 -3l2 3m-2 -3v-2l3 -1m-6 0l3 1"></path>
                                    <circle cx="12" cy="7.5" r=".5"></circle>
                                </svg></span>
                            <span class="nav-link-title">Pacientes</span>
                        </a>
                    </li>

                    <!-- REPORTES -->
                    <li class="nav-item dropdown {{Route::is('reporteIngresoView') || Route::is('reporteEgresoView') || Route::is('reporteStockView') ? "active" : ""}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-docs" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" /> <polyline points="14 3 14 8 19 8" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg></span>
                            <span class="nav-link-title">Reportes</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{Route::is('reporteIngresoView') ? "active" : ""}}" href="{{route('reporteIngresoView')}}">Reporte ingresos</a></li>
                            <li><a class="dropdown-item {{Route::is('reporteEgresoView') ? "active" : ""}}" href="{{route('reporteEgresoView')}}">Reporte egresos</a></li>
                            <li><a class="dropdown-item {{Route::is('reporteStockView') ? "active" : ""}}" href="{{route('reporteStockView')}}">Reporte stock</a></li>
                            <li><a class="dropdown-item {{Route::is('reporteKardexView') ? "active" : ""}}" href="{{route('reporteKardexView')}}">Reporte kardex</a></li>
                        </ul>
                    </li>

                    @if (Auth::user()->rol == "Administrador")
                    <!-- USUARIOS -->
                    <li class="nav-item dropdown {{Route::is('usuariosView') || Route::is('nuevoUsuarioView') || Route::is('editarUsuarioView') ? "active" : ""}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-layout" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                    <path d="M5.5 21v-2a4 4 0 0 1 4 -4h5a4 4 0 0 1 4 4v2"></path>
                                </svg></span>
                            <span class="nav-link-title">Usuarios</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{Route::is('usuariosView') ? "active" : ""}}" href="{{route('usuariosView')}}">Usuarios</a></li>
                            
                        </ul>
                    </li>
                    @endif

                    <!-- CHAT -->
                    <li class="nav-item {{Route::is('chatView') ? "active" : ""}}">
                        <a class="nav-link" href="{{route('chatView')}}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z"></path>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                    <path d="M21 3L14.5 21a.55 .55 0 0 1 -1 0L10 14L3 10.5a.55 .55 0 0 1 0 -1L21 3"></path>
                                </svg>
                            </span>
                            <span class="nav-link-title">Chat</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
