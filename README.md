# POS APP

Aplikasi kasir sederhana berbasis web menggunakan Laravel dan PostgreSQL.

## Yang Dibutuhkan

- PHP 8.3+
- PostgreSQL
- Composer

## Install

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Setting database di file `.env`:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pos_app
DB_USERNAME=postgres
DB_PASSWORD=password_db_kamu
```

Lalu jalankan:

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

Buka browser ke `http://127.0.0.1:8000`.

## Akun Login

```
Email: admin@pos.app
Password: password
```

## Data Contoh

Seeder sudah menyediakan data awal untuk testing:

- **Supplier**: 2 data (SUP-001 PT Maju Jaya, SUP-002 CV Berkah Sentosa)
- **Barang**: 5 data (Mie Instan, Sabun Mandi, Shampo Botol, Minuman Kemasan, Biskuit)
- **Pembelian**: 2 transaksi contoh
- **Penjualan**: 3 transaksi contoh (Shampo, Minuman, Biskuit)

## Fitur yang Ada

1. Login & logout
2. Dashboard menampilkan ringkasan data dan grafik penjualan 7 hari terakhir
3. Kelola data supplier (tambah, lihat, ubah, hapus)
4. Kelola data barang (tambah, lihat, ubah, hapus) dengan fitur expired date dan stok
5. Transaksi pembelian barang dari supplier, stok otomatis bertambah
6. Transaksi penjualan, stok dicek sebelum disimpan dan otomatis dikurangi

## Struktur Tabel

- `users` — data login
- `suppliers` — data supplier
- `products` — data barang
- `purchases` — transaksi pembelian
- `sales` — transaksi penjualan

## Catatan Penting

- Stok barang bertambah otomatis saat ada transaksi pembelian
- Stok barang berkurang otomatis saat ada transaksi penjualan
- Stok dicek sebelum transaksi penjualan disimpan, kalau stok kurang maka tidak bisa disimpan
- Nomor supplier dan kode barang tidak boleh sama
