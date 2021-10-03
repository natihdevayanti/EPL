<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>[Ivone Seafood Store] - Verifikasi Pendaftaran Anda</title>
</head>
<body>
    <h2>Hai, {{ $customer->name }}</h2>
    <p><strong>Lakukan verifikasi pendaftaran dan ubah password Anda <a href="{{ route('customer.verify', $customer->activate_token) }}">DI SINI</a></strong></p><br/>
    <p>Terima kasih telah melakukan transaksi pada aplikasi kami, berikut password sementara Anda: <strong>{{ $password }}</strong></p>
    
</body>
</html>