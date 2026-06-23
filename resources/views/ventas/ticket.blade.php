<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket de Venta</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .details {
            margin-bottom: 15px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items th, .items td {
            text-align: left;
            padding: 3px;
        }
        .items th {
            border-bottom: 1px solid #000;
        }
        .total {
            text-align: right;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0;">Farma City</h2>
        <p style="margin: 5px 0;">Ticket de Venta #{{ str_pad($venta->id, 8, '0', STR_PAD_LEFT) }}</p>
        <p style="margin: 5px 0;">Fecha: {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="details">
        <p style="margin: 3px 0;">Cliente: {{ $venta->numero_cliente }}</p>
        <p style="margin: 3px 0;">Cajero: {{ $venta->usuario ? $venta->usuario->name : 'Usuario no disponible' }}</p>
        @if($venta->obraSocial)
            <p style="margin: 3px 0;">Obra Social: {{ $venta->obraSocial->nombre }}</p>
        @endif
    </div>

    <table class="items">
        <tr>
            <th>Producto</th>
            <th>Cant</th>
            <th>P.Unit</th>
            <th>Total</th>
        </tr>
        @foreach($detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>${{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @if($detalle->descuento > 0)
                <tr>
                    <td colspan="3" style="text-align: right;">Descuento {{ $venta->obraSocial->nombre ?? '' }} ({{ $detalle->descuento }}%):</td>
                    <td>-${{ number_format(($detalle->precio_unitario * $detalle->cantidad * $detalle->descuento / 100), 2) }}</td>
                </tr>
            @endif
        @endforeach
    </table>

    <div class="total">
        <p style="margin: 3px 0;">Subtotal: ${{ number_format($venta->subtotal, 2) }}</p>
        @if($venta->impuestos > 0)
            <p style="margin: 3px 0;">IVA: ${{ number_format($venta->impuestos, 2) }}</p>
        @endif
        @if($venta->descuento > 0)
            <p style="margin: 3px 0;">Descuento Total: ${{ number_format($venta->descuento, 2) }}</p>
            @if($venta->obraSocial)
                <p style="margin: 3px 0; font-size: 10px;">(Obra Social: {{ $venta->obraSocial->nombre }})</p>
            @endif
        @endif
        <p style="margin: 3px 0;"><strong>TOTAL: ${{ number_format($venta->total, 2) }}</strong></p>
    </div>

    <div class="footer">
        <p style="margin: 5px 0;">¡Gracias por su compra!</p>
        <p style="margin: 5px 0;">Vuelva pronto</p>
    </div>
</body>
</html>