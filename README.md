# Repositori untuk Proyek Ecommerce Hexavara

# Setup Project
1. Salin .env.example
2. php artisan key:generate
3. Setting DB
4. php artisan storage:link
5. php artisan serve

# Dev Setup

## .env
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ersadishla.dev@gmail.com
MAIL_PASSWORD=diiywoehmjqmvnal
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ersadishla.dev@gmail.com
MAIL_FROM_NAME=Admin-Ecommerce
```

# To Do
## Bisnis Logic:
- kurangi stok saat checkout
- invalid_at order (1 jam)
- admin pembayaran
- admin produksi
## Bug:
- Varian dan Cart, detail-detailnya
- Dashboard Pesanan (Customer dan Admin), detail-detailnya
