<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>[Ivone Seafood Store] - Pesanan {{ $order->invoice }}</title>
</head>
<body>
    <h2>Hai, {{ $order->customer_name }}</h2>
    <p>Terima kasih telah melakukan transaksi pada aplikasi kami, berikut invoice pesanan Anda: <strong>{{ $order->invoice }}</strong></p>
    <p>Klik <a href="{{ route('customer.view_order_unregistered', $order->invoice) }}">LINK INI</a> untuk melihat informasi pesanan.</p>
</body>
</html>