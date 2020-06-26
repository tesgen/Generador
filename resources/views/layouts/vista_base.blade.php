@extends('tesgen::layouts.base')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/demo.css')}}">
    <link rel="stylesheet" href="{{asset('css/gridstack.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/gridstack-extra.css')}}">
@endsection

@section('content')

    <div class="container-fluid">
        <h1 class="text-center">@yield('titulo')</h1>

        <div class="card" id="card-principal">
            <div class="card-header">
                <strong>Tabla Principal</strong>
            </div>
            <div class="card-body">

                @include('tesgen::includes.conf_tabla', [
                'tipoTabla' => 'principal',
                'nombreIdClase' => 'nombreClase',
                'nombreIdNatural' => 'nombreNatural',
                'nombreIdPlural' => 'nombrePlural'
                ])

                @include('tesgen::includes.conf_columnas', [
                'tipoTabla' => 'principal'
                ])

            </div>
        </div>
        @yield('contenido_propio')
        <hr>
        <button id="generar" onclick="generar()" class="btn btn-success">Generar</button>
        <button onclick="guardar()" class="btn btn-primary">Guardar</button>
        <hr>
        <div class="modal" tabindex="-1" role="dialog" id="spinnerModal"
             style="background-color: rgba(0, 0, 0, .5) !important;">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <span class="fa fa-circle-o-notch fa-spin fa-3x w-100"></span>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    @include('tesgen::includes.scripts_tabla')
    @yield('scripts_propios')

@endsection





