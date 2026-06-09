@extends('layouts.error')

@section('title', '400')

@section('content')
    <div class="error-box">

        <div class="error-image">
            <img src="{{ asset('assets/img/error-404.png') }}" alt="400">
        </div>

        <div class="error-text">
            <h1>Solicitud no valida</h1>

            <p>
                Parece que la solicitud no se pudo procesar correctamente.
                Lamentamos el inconveniente, estamos trabajando para resolverlo.
            </p>

            <a href="{{ url('/') }}">Regresar al inicio</a>
        </div>

    </div>
@endsection
