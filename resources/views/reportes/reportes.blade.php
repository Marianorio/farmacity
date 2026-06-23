<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Ventas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 20px; }
        .totales { margin-top: 20px; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Ventas</h1>
        <p>Período: {{ $fecha_inicio ?? 'Todas las fechas' }} - {{ $fecha_fin ?? 'Hasta hoy' }}</p>
    </div>

    <div class="totales">
        <h3>Resumen</h3>
        <p>Total de Ventas: {{ $totales['total_ventas'] }}</p>
        <p>Monto Total: ${{ number_format($totales['monto_total'], 2) }}</p>
        <p>Total Descuentos: ${{ number_format($totales['total_descuentos'], 2) }}</p>
        <p>Total Impuestos: ${{ number_format($totales['total_impuestos'], 2) }}</p>
    </div>

    <h3>Detalle de Ventas</h3>
    <table>
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
                <th>Usuario Anulación</th>
                <th>Fecha Anulación</th>
                <th>Motivo Anulación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->fecha->format('d/m/Y H:i') }}</td>
                <td>{{ $venta->numero_cliente }}</td>
                <td>
                    @foreach($venta->detalles as $detalle)
                        {{ $detalle->producto->nombre }} ({{ $detalle->cantidad }})<br>
                    @endforeach
                </td>
                <td>${{ number_format($venta->subtotal, 2) }}</td>
                <td>${{ number_format($venta->descuento, 2) }}</td>
                <td>${{ number_format($venta->total, 2) }}</td>
                <td>{{ $venta->estado }}</td>
                <td>{{ $venta->usuario->name ?? 'N/A' }}</td>
                @if($venta->estado === 'ANULADA')
                    <td>
                        Anulada por: {{ $venta->usuarioAnulacion->name ?? 'N/A' }}<br>
                        Fecha: {{ $venta->fecha_anulacion }}<br>
                        Motivo: {{ $venta->motivo_anulacion }}
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
