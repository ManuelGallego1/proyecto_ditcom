<!DOCTYPE html>
<html>
<head>
    <title>Factura Generada</title>
</head>
<body>
    <p>Hola,</p>
    <p>Adjunto encontrarás la factura generada.</p>
    <p>Referencia: {{ $factura->referencia }}</p>
    <p>Tipo de Venta: {{ $factura->tipo_venta }}</p>
    <p>Monto: {{ $factura->monto }}</p>
    <p>Estado: {{ $factura->estado }}</p>
    <p>Gracias por usar nuestra aplicación!</p>
</body>
</html>