@extends('layouts.app')

@section('content')
    <div class="page-wrap d-flex flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <span class="display-1 d-block">Error 403</span>
                    <div class="mb-4 lead">No tienes permiso para realizar esta acción</div>
                    <a href="{{route('home')}}" class="btn btn-link">Ir a la página de inicio</a>
                </div>
            </div>
        </div>
    </div>
@endsection
