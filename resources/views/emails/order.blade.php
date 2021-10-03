<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>[Ivone Seafood Store] - Pesanan Anda Dikirim {{ $order->invoice }}</title>
</head>
<body>
    <h2>Hai, {{ $order->customer_name }}</h2>
    <p>Terima kasih telah melakukan transaksi pada aplikasi kami, berikut nomor resi dari pesanan anda: <strong>{{ $order->tracking_number }}</strong></p>
    @if ($order->free_access == 0)
        <p>Klik <a href="{{ route('customer.view_order', $order->invoice) }}">link ini</a> untuk melihat invoice.</p>
    @elseif ($order->free_access == 1)
        <p>Klik <a href="{{ route('customer.view_order_unregistered', $order->invoice) }}">link ini</a> untuk melihat invoice.</p>
    @endif
</body>
</html>