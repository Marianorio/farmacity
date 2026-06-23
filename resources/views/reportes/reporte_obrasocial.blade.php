<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Obra Social</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .fecha {
            margin-bottom: 20px;
            text-align: right;
        }
        .info-obra-social {
            margin-bottom: 20px;
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
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        .totales {
            margin-top: 30px;
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
        }
        .page-break {
            page-break-after: always;
        }
        .productos-cubiertos {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .productos-cubiertos th,
        .productos-cubiertos td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 11px;
        }
        .productos-cubiertos th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte de Obra Social</h2>
    </div>

    <div class="fecha">
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Período: {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</p>
    </div>

    <div class="info-obra-social">
        <h3>{{ $obra_social['nombre'] ?? 'Todas las Obras Sociales' }}</h3>
        @if(isset($obra_social['cuit']))
            <p>CUIT: {{ $obra_social['cuit'] }}</p>
            
            @if(isset($obra_social['productos']) && count($obra_social['productos']) > 0)
                <h4>Productos Cubiertos:</h4>
                <table class="productos-cubiertos">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio Original</th>
                            <th>Descuento</th>
                            <th>Precio con Descuento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($obra_social['productos'] as $producto)
                            <tr>
                                <td>{{ $producto['nombre'] }}</td>
                                <td>${{ $producto['precio_original'] }}</td>
                                <td>{{ $producto['descuento'] }}%</td>
                                <td>${{ $producto['precio_con_descuento'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>N° Receta</th>
                <th>Paciente</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Total</th>
                <th>% Cob.</th>
                <th>Monto Cob.</th>
                <th>A pagar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recetas ?? [] as $receta)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($receta['fecha'])->format('d/m/Y') }}</td>
                    <td>{{ $receta['numero_receta'] }}</td>
                    <td>{{ $receta['paciente'] }}</td>
                    <td>{{ $receta['producto'] }}</td>
                    <td>{{ $receta['cantidad'] }}</td>
                    <td>${{ number_format($receta['precio_unitario'], 2) }}</td>
                    <td>${{ number_format($receta['monto_total'], 2) }}</td>
                    <td>{{ $receta['porcentaje_cobertura'] }}%</td>
                    <td>${{ number_format($receta['monto_cobertura'], 2) }}</td>
                    <td>${{ number_format($receta['monto_paciente'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No hay datos para mostrar en el período seleccionado</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totales">
        <p><strong>Total Recetas:</strong> {{ count($recetas ?? []) }}</p>
        <p><strong>Monto Total:</strong> ${{ number_format($totales['monto_total'] ?? 0, 2) }}</p>
        <p><strong>Total Cobertura:</strong> ${{ number_format($totales['monto_cobertura'] ?? 0, 2) }}</p>
        <p><strong>Total a Pagar por Pacientes:</strong> ${{ number_format($totales['monto_paciente'] ?? 0, 2) }}</p>
    </div>

    <div class="footer">
        <p>Reporte generado por el sistema de Farmacia - Página 1</p>
    </div>
</body>
</html>
