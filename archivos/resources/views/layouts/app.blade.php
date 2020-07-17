<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{config('app.name')}}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    @include('layouts.css.coreui')
    <link rel="stylesheet" href="{{asset('css/gijgo.min.css')}}">
    @yield('styles')
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{route('home')}}">
        Sistema
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>

    <ul class="nav navbar-nav ml-auto">
{{--        <li class="nav-item d-md-down-none">--}}
{{--            <a class="nav-link" href="#">--}}
{{--                <i class="icon-bell"></i>--}}
{{--                <span class="badge badge-pill badge-danger">5</span>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item dropdown">
            <a class="nav-link" style="margin-right: 10px" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="true" aria-expanded="false">
                @auth
                    {!! Auth::user()->username !!}
                @else
                    Invitado
                @endauth
            </a>
            @auth
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div>
{{--                    <a class="dropdown-item" href="#">--}}
{{--                        <i class="fa fa-envelope-o"></i> Messages--}}
{{--                        <span class="badge badge-success">42</span>--}}
{{--                    </a>--}}
{{--                    <div class="dropdown-header text-center">--}}
{{--                        <strong>Settings</strong>--}}
{{--                    </div>--}}
{{--                    <a class="dropdown-item" href="#">--}}
{{--                        <i class="fa fa-user"></i> Profile</a>--}}
                    {{--                    @if(RolesAuth::puedeAccederA('RegisterController@showRegistrationForm'))--}}
{{--                    @if(auth()->user()->role->name === 'admin')--}}
                        <a class="dropdown-item" href="{{route('editPassword')}}">
                            <i class="fa fa-wrench"></i> Cambiar Contraseña</a>
{{--                    @endif--}}

{{--                    <a class="dropdown-item" href="#">--}}
{{--                        <i class="fa fa-wrench"></i> Settings</a>--}}
                    <div class="dropdown-divider"></div>
{{--                    <a class="dropdown-item" href="#">--}}
{{--                        <i class="fa fa-shield"></i> Lock Account</a>--}}
                    <a class="dropdown-item" href="{!! url('/logout') !!}" class="btn btn-default btn-flat"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-unlock"></i>Cerrar Sesión
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            @endauth

        </li>
    </ul>
</header>

<div class="app-body">
    @include('layouts.sidebar')
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

@include('layouts.js.coreui')

<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script src="{{asset('js/mensaje.js')}}"></script>
<script src="{{asset('js/gijgo.min.js')}}"></script>
<script src="{{asset('js/messages.es-es.js')}}"></script>
@yield('scripts')

<script>

    $(function () {
        $('select').selectize();
    });

</script>

</body>
</html>
