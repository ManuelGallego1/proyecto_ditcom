<!DOCTYPE html>
<html>
<head>
    <title>Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header, .footer {
            text-align: center;
            padding: 10px;
        }
        .content {
            margin: 20px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Factura</h1>
        </div>
        <div class="content">
            <p><strong>Referencia:</strong> {{ $factura->referencia }}</p>
            <p><strong>Tipo de Venta:</strong> {{ $factura->tipo_venta }}</p>
            <p><strong>Monto:</strong> {{ $factura->monto }}</p>
            <p><strong>Estado:</strong> {{ $factura->estado }}</p>
        </div>
        <div class="footer">
            <p>Gracias por su compra</p>
        </div>
    </div>
</body>
</html>