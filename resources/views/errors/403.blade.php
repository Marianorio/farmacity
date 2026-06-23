@extends('adminlte::page')

@section('title', 'Acceso denegado')

@section('content_header')
    <h1>403 — Acceso denegado</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body text-center">
            <h2 class="display-4">No tienes permiso para acceder a esta página</h2>
            <p class="lead">Si crees que deberías tener acceso, contacta al administrador.</p>
            <a href="{{ url()->previous() ?: route('home') }}" class="btn btn-primary mt-3">Volver</a>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
