<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Ventas</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        .total { 
            font-weight: bold; 
        }
        .anulada {
            color: #dc3545;
            font-weight: bold;
        }
        .activa {
            color: #28a745;
        }
        .resumen {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Reporte de Ventas</h1>
    <p>Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>N° Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->numero_cliente }}</td>
                <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</td>
                <td>${{ number_format($venta->total, 2) }}</td>
                <td class="{{ $venta->estado === 'ANULADA' ? 'anulada' : 'activa' }}">
                    {{ $venta->estado }}
                    @if($venta->estado === 'ANULADA' && $venta->fecha_anulacion)
                        <br>
                        <small>{{ \Carbon\Carbon::parse($venta->fecha_anulacion)->format('d/m/Y H:i') }}</small>
                    @endif
                </td>
                <td>{{ optional($venta->usuario)->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="resumen">
        <h3>Resumen</h3>
        <p>Ventas Activas: {{ $cantidadActivas }} - Total: ${{ number_format($totalActivas, 2) }}</p>
        <p>Ventas Anuladas: {{ $cantidadAnuladas }} - Total: ${{ number_format($totalAnuladas, 2) }}</p>
        <p class="total">Total General: ${{ number_format($totalActivas + $totalAnuladas, 2) }}</p>
    </div>
</body>
</html> 