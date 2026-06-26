@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
    <h1>Reporte de Ventas</h1>
    <p class="text-muted">Período: {{ $fecha_inicio ?? 'Todas las fechas' }} - {{ $fecha_fin ?? 'Hasta hoy' }}</p>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totales['total_ventas'] }}</h3>
                    <p>Total de Ventas</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>${{ number_format($totales['monto_total'], 2) }}</h3>
                    <p>Monto Total</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>${{ number_format($totales['total_descuentos'], 2) }}</h3>
                    <p>Total Descuentos</p>
                </div>
                <div class="icon"><i class="fas fa-tags"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>${{ number_format($totales['total_impuestos'], 2) }}</h3>
                    <p>Total Impuestos</p>
                </div>
                <div class="icon"><i class="fas fa-receipt"></i></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalle de Ventas</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th>Subtotal</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->fecha->format('d/m/Y H:i') }}</td>
                        <td>{{ $venta->numero_cliente }}</td>
                        <td>
                            @foreach($venta->detalles as $detalle)
                                <span class="badge badge-info">{{ $detalle->producto->nombre }} x{{ $detalle->cantidad }}</span><br>
                            @endforeach
                        </td>
                        <td>${{ number_format($venta->subtotal, 2) }}</td>
                        <td>${{ number_format($venta->descuento, 2) }}</td>
                        <td>${{ number_format($venta->total, 2) }}</td>
                        <td>
                            @if($venta->estado === 'ANULADA')
                                <span class="badge badge-danger">ANULADA</span>
                            @else
                                <span class="badge badge-success">COMPLETADA</span>
                            @endif
                        </td>
                        <td>{{ $venta->usuario->name ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No hay ventas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
