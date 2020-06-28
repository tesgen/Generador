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
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{url('/generador/crud')}}">--}}
{{--                        <i class="nav-icon icon-cursor"></i><span>CRUD</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/generador/crud2')}}">
                        <i class="nav-icon icon-cursor"></i><span>CRUD</span>
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{url('/generador/md')}}">--}}
{{--                        <i class="nav-icon icon-cursor"></i><span>Maestro-Detalle</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/generador/md2')}}">
                        <i class="nav-icon icon-cursor"></i><span>Maestro-Detalle</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{url('/generador/usuarios')}}">
                        <i class="nav-icon icon-cursor"></i><span>Autenticación-Usuario</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{url('/generador/permisos')}}">
                        <i class="nav-icon icon-cursor"></i><span>Permisos</span>
                    </a>
                </li>
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
    <main class="main">
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
