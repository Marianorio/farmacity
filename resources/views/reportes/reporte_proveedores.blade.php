<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Proveedor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Proveedor</h1>
        <p><strong>Proveedor:</strong> {{ $proveedor['nombre'] }}</p>
        <p><strong>Contacto:</strong> {{ $proveedor['contacto'] }}</p>
        <p><strong>Dirección:</strong> {{ $proveedor['direccion'] }}</p>
        <p><strong>Fecha del reporte:</strong> {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio Compra</th>
                <th>Stock Inicial</th>
                <th>Stock Actual</th>
                <th>Fecha Caducidad</th>
                <th>Fecha Creación</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto['nombre'] }}</td>
                <td>${{ number_format($producto['precio_compra'], 2) }}</td>
                <td>{{ $producto['stock_inicial'] }}</td>
                <td>{{ $producto['stock_actual'] }}</td>
                <td>{{ \Carbon\Carbon::parse($producto['caducidad'])->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($producto['fecha_creacion'])->format('d/m/Y') }}</td>
                <td>${{ number_format($producto['precio_compra'] * $producto['stock_inicial'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Inversión: ${{ number_format($total, 2) }}</p>
    </div>
</body>
</html>
