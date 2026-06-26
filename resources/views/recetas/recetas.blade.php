@extends('adminlte::page')

@section('title', 'Recetas')

@section('content_header')
    <h1>Gestión de Recetas</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-prescription"></i> Recetas Médicas</h3>
                </div>
                <div class="card-body">
                    <p>Desde este módulo podrás gestionar las recetas médicas de los pacientes.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Recetas activas
                            <span class="badge badge-primary badge-lg">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Recetas vencidas
                            <span class="badge badge-warning">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pacientes registrados
                            <span class="badge badge-info">0</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-tools"></i> Este módulo se encuentra en desarrollo.
                    </div>
                    <h5>Funcionalidades próximas:</h5>
                    <ul>
                        <li>Registro de recetas médicas</li>
                        <li>Asociación con pacientes</li>
                        <li>Control de vencimientos</li>
                        <li>Impresión de recetas</li>
                        <li>Historial por paciente</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop