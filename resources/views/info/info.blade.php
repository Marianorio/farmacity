@extends('adminlte::page')

@section('title', 'Información del Sistema')

@section('content_header')
    <h1>Información del Sistema</h1>
@stop

@section('content')
    @php
        $userCount = \App\Models\User::count();
        $productCount = \App\Models\Producto::count();
        $saleCount = \App\Models\Venta::count();
        $providerCount = \App\Models\Proveedor::count();
    @endphp

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-rocket"></i> Primeros Pasos</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Bienvenido a <strong>FarmaCity</strong> - Sistema de Gestión Farmacéutica.
                    </div>
                    <h5>Guía rápida:</h5>
                    <ol>
                        <li><strong>Productos</strong> - Administra el catálogo de productos, precios y stock.</li>
                        <li><strong>Ventas</strong> - Realiza ventas con soporte para obras sociales y descuentos.</li>
                        <li><strong>Obras Sociales</strong> - Configura coberturas y descuentos por obra social.</li>
                        <li><strong>Proveedores</strong> - Gestiona tus proveedores y pedidos.</li>
                        <li><strong>Reportes</strong> - Genera reportes de ventas y obras sociales.</li>
                    </ol>
                    <h5>Atajos de teclado:</h5>
                    <ul>
                        <li><kbd>F2</kbd> - Nuevo producto</li>
                        <li><kbd>F3</kbd> - Nueva venta</li>
                        <li><kbd>F4</kbd> - Buscar producto</li>
                        <li><kbd>Ctrl + F</kbd> - Buscar en listados</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Estadísticas</h3>
                </div>
                <div class="card-body">
                    <canvas id="statsChart" height="200"></canvas>
                    <hr>
                    <dl class="row mb-0">
                        <dt class="col-sm-8">Usuarios registrados</dt>
                        <dd class="col-sm-4 text-right"><strong>{{ $userCount }}</strong></dd>
                        <dt class="col-sm-8">Productos en catálogo</dt>
                        <dd class="col-sm-4 text-right"><strong>{{ $productCount }}</strong></dd>
                        <dt class="col-sm-8">Ventas realizadas</dt>
                        <dd class="col-sm-4 text-right"><strong>{{ $saleCount }}</strong></dd>
                        <dt class="col-sm-8">Proveedores</dt>
                        <dd class="col-sm-4 text-right"><strong>{{ $providerCount }}</strong></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
$(function () {
    const ctx = document.getElementById('statsChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Usuarios', 'Productos', 'Ventas', 'Proveedores'],
            datasets: [{
                data: [{{ $userCount }}, {{ $productCount }}, {{ $saleCount }}, {{ $providerCount }}],
                backgroundColor: ['#17a2b8', '#28a745', '#ffc107', '#dc3545'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>
@stop