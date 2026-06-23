@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Inicio</h1>
@stop

@section('content')
    {{-- Botones de menú --}}
    <div class="row mb-4">
        @php
            $user = auth()->user();
            $isFarmaceutico = $user && method_exists($user, 'hasRole') && $user->hasRole('Farmaceutico');
            $isCajero = $user && method_exists($user, 'hasRole') && $user->hasRole('Cajero');
        @endphp

        @if($isFarmaceutico)
            <div class="col-md-4">
                <a href="{{ route('productos.index') }}" class="btn btn-lg btn-primary btn-block">
                    <i class="fas fa-box"></i> Productos
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('proveedores.index') }}" class="btn btn-lg btn-warning btn-block">
                    <i class="fas fa-truck"></i> Proveedores
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('obras-sociales.index') }}" class="btn btn-lg btn-info btn-block">
                    <i class="fas fa-hospital"></i> Obras Sociales
                </a>
            </div>

        @elseif($isCajero)
            <div class="col-md-12">
                <a href="{{ route('ventas.index') }}" class="btn btn-lg btn-success btn-block">
                    <i class="fas fa-cash-register"></i> Caja
                </a>
            </div>

        @else
            <div class="col-md-3">
                <a href="{{ route('productos.index') }}" class="btn btn-lg btn-primary btn-block">
                    <i class="fas fa-box"></i> Productos
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('ventas.index') }}" class="btn btn-lg btn-success btn-block">
                    <i class="fas fa-shopping-cart"></i> Ventas
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('proveedores.index') }}" class="btn btn-lg btn-warning btn-block">
                    <i class="fas fa-truck"></i> Proveedores
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('obras-sociales.index') }}" class="btn btn-lg btn-info btn-block">
                    <i class="fas fa-hospital"></i> Obras Sociales
                </a>
            </div>
        @endif
    </div>

    {{-- Widgets informativos --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">
                        Productos con Stock Mínimo
                        <i class="fas fa-question-circle ml-2" title="Muestra productos cuyo stock actual es menor al stock mínimo establecido. Estos productos necesitan ser reordenados" data-toggle="tooltip" data-placement="right" style="cursor: help; color: #007bff;"></i>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-bajo-stock" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Stock Actual</th>
                                    <th>Stock Mínimo</th>
                                    <th>Proveedor</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        Productos por Vencer
                        <i class="fas fa-question-circle ml-2" title="Muestra productos que vencen en los próximos 30 días. Es importante revisar y descartar estos productos a tiempo" data-toggle="tooltip" data-placement="right" style="cursor: help; color: #007bff;"></i>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-por-vencer" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Stock Actual</th>
                                    <th>Proveedor</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico de ventas --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gráfico de Ventas</h3>
                </div>
                <div class="card-body">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/dataTables.spanish.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inicializar tooltips de Bootstrap
            $('[data-toggle="tooltip"]').tooltip();

            // Tabla de productos con bajo stock
            if (!$.fn.DataTable.isDataTable('#tabla-bajo-stock')) {
                $('#tabla-bajo-stock').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '/productos/bajo-stock',
                    columns: [
                        { data: 'id' },
                        { data: 'nombre' },
                        { data: 'categoria.nombre', defaultContent: 'Sin categoría' },
                        { data: 'stock_actual' },
                        { data: 'stock_minimo' },
                        { data: 'proveedor.nombre', defaultContent: 'Sin proveedor' }
                    ],
                    language: spanishTranslation,
                    pageLength: 5,
                    responsive: true,
                    autoWidth: false
                });
            }

            // Tabla de productos por vencer
            if (!$.fn.DataTable.isDataTable('#tabla-por-vencer')) {
                $('#tabla-por-vencer').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '/productos/por-vencer',
                    columns: [
                        { data: 'id' },
                        { data: 'nombre' },
                        { data: 'categoria.nombre', defaultContent: 'Sin categoría' },
                        { data: 'caducidad' },
                        { data: 'stock_actual' },
                        { data: 'proveedor.nombre', defaultContent: 'Sin proveedor' }
                    ],
                    language: spanishTranslation,
                    pageLength: 5,
                    responsive: true,
                    autoWidth: false
                });
            }

            // Gráfico de ventas
            const canvas = document.getElementById('ventasChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                let chart = null;

                // Obtener datos desde la API
                fetch('/ventas/chart/data')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        label: 'Ventas por Mes',
                                        data: data.data,
                                        borderColor: 'rgb(75, 192, 192)',
                                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4,
                                        pointRadius: 5,
                                        pointHoverRadius: 7,
                                        pointBackgroundColor: 'rgb(75, 192, 192)',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 2
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Total de Ventas ($)'
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top'
                                        },
                                        title: {
                                            display: false
                                        }
                                    }
                                }
                            });
                        }
                    })
                    .catch(error => console.error('Error al cargar datos del gráfico:', error));
            }
        });
    </script>
@stop