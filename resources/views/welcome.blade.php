
@extends('layouts.minimal')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mt-5 pt-4">
                <img src="{{ asset('img/FarmacityLogo.png') }}" alt="Logo" class="welcome-logo">
                <h1 class="mb-3">Bienvenido!</h1>
                <p class="lead mb-4">Accede al sistema para gestionar la farmacia.</p>

                <a href="{{ route('login') }}" class="btn btn-lg btn-primary">Iniciar Sesión</a>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .welcome-logo{ height:110px; margin-bottom:18px; }
    </style>
@endsection