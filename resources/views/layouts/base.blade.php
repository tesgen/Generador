<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('titulo')</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker-4.17.47.css')}}">
    <link rel="stylesheet" href="{{asset('css/coreui.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome-4.4.7.css')}}">
    <link rel="stylesheet" href="{{asset('css/flag-icon.min-3.3.0.css')}}">
    <link rel="stylesheet" href="{{asset('css/simple-line-icons-2.4.1.css')}}">
    <link rel="stylesheet" href="{{asset('css/gijgo.min.css')}}">
    @yield('styles')
    <style>

        .app-header {
            background-color: #022b5e;
            border-bottom: 0px;
        }

        .sidebar .nav-link.active {
            background: #0a5ab1;
        }

        .sidebar .nav-link.active:hover {
            background: #5ca7f8;
        }

        #titulo-pagina {
            /*background: #3576e5;*/
            color: white;
            height: 70px;
            padding: 10px;
            margin-bottom: 10px;
            /*background-image: linear-gradient(to right, #3576e5, #0e1c4b);*/
            background-image: linear-gradient(to right, #06478e, #3e8adc, #06478e);
        }

        .card-header {
            background-color: #5ca7f8;
            color: white;
        }

        h5 {
            background-color: #5ca7f8;
            color: white;
            padding: 10px;
            margin-top: 10px;
        }

    </style>
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{url('/generador')}}">
        Generador
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>

    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item d-md-down-none">
        </li>
        <li class="nav-item dropdown">

        </li>
    </ul>
</header>

<div class="app-body">
    <div class="sidebar">

        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/generador/crud2')}}">
                        <i class="nav-icon fa fa-list-alt" aria-hidden="true"></i><span>CRUD</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/generador/md2')}}">
                        <i class="nav-icon fa fa-window-restore" aria-hidden="true"></i><span>Maestro-Detalle</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/generador/usuarios')}}">
                        <i class="nav-icon fa fa-user" aria-hidden="true"></i><span>Autenticación-Usuario</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/generador/permisos')}}">
                        <i class="nav-icon fa fa-address-card" aria-hidden="true"></i><span>Permisos</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <main class="main bg-light">
        @yield('titulo-pagina')
        @yield('content')
        <div class="modal" tabindex="-1" role="dialog" id="spinnerModal"
             style="background-color: rgba(0, 0, 0, .5) !important;">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <span class="fa fa-circle-o-notch fa-spin fa-3x w-100"></span>
            </div>
        </div>
    </main>
</div>
<footer class="app-footer">
    <div class="ml-auto">
        <span>Powered by</span>
        <a href="https://coreui.io">CoreUI</a>
    </div>
</footer>


<script src="{{asset('js/coreui/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('js/coreui/popper-1.14.7.js')}}"></script>
<script src="{{asset('js/coreui/bootstrap-4.3.1.js')}}"></script>
<script src="{{asset('js/coreui/moment-2.20.1.js')}}"></script>
<script src="{{asset('js/coreui/bootstrap-datetimepicker-4.17.47.min.js')}}"></script>
<script src="{{asset('js/coreui/coreui.js')}}"></script>

<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script src="{{asset('js/mensaje.js')}}"></script>
<script src="{{asset('js/gijgo.min.js')}}"></script>
<script src="{{asset('js/messages.es-es.js')}}"></script>

<script src="{{asset('js/gridstack.all.js')}}"></script>
<script src="{{asset('js/jquery-ui.js')}}"></script>

@yield('scripts')

</body>

</html>
