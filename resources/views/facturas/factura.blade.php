<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura #{{ $venta->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
        }
        .info-empresa {
            margin-bottom: 20px;
        }
        .info-factura {
            margin-bottom: 20px;
        }
        .info-cliente {
            margin-bottom: 30px;
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
            background-color: #f8f9fa;
        }
        .totales {
            float: right;
            width: 300px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA</h1>
        <!-- Aquí puedes agregar tu logo -->
        <!-- <img src="{{ public_path('img/logo.png') }}" class="logo"> -->
    </div>

    <div class="info-empresa">
        <h3>Farma City</h3>
        <p>Av. 25 de Mayo 1150, Formosa Capital</p>
        <p>Entre calles Moreno y Saavedra</p>
        <p>CP: 3600 - Formosa, Argentina</p>
        <p>Teléfono: (370) 442-5890</p>
        <p>Email: farmacity.formosa@gmail.com</p>
    </div>

    <div class="info-factura">
        <strong>Factura #:</strong> {{ $venta->id }}<br>
        <strong>Fecha:</strong> {{ $venta->fecha }}<br>
        <strong>Estado:</strong> {{ $venta->estado }}
    </div>

    <div class="info-cliente">
        <h3>Cliente</h3>
        <strong>Nro. Cliente:</strong> {{ $venta->numero_cliente ?? 'N/A' }}<br>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>${{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>${{ number_format($venta->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>IVA (21%):</strong></td>
                <td>${{ number_format($venta->impuestos, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Descuento:</strong></td>
                <td>${{ number_format($venta->descuento, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td>${{ number_format($venta->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Gracias por su compra</p>
        <p>Esta factura es un comprobante válido de pago</p>
    </div>
</body>
</html>