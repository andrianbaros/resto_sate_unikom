# resto_sate_unikom
Repository ini berisi proyek Tugas Besar Rekayasa Perangkat Lunak 1, yang dikerjakan pada semester 5 oleh mahasiswa Teknik Informatika Universitas Komputer Indonesia.

Proyek ini adalah aplikasi web untuk restoran sate yang dirancang untuk memenuhi kebutuhan manajer, chef, pelayan (waiter), dan kasir. Aplikasi ini dibangun menggunakan PHP dan mencakup berbagai fitur yang diperlukan untuk mengelola operasional restoran secara efisien.

## Fitur

1. **Manajer**
   - Melihat laporan penjualan
   - Mengelola menu
   - Mengelola staf

2. **Chef**
   - Melihat pesanan yang masuk
   - Mengupdate status pesanan

3. **Waiter**
   - Mencatat pesanan pelanggan
   - Mengelola status meja

4. **Kasir**
   - Memproses pembayaran
   - Melihat riwayat transaksi

## Instalasi

1. Clone repositori ini:
   ```bash
   git clone https://github.com/andrianbaros/resto_sate_unikom.git

2. Masuk ke direktori proyek:
bash
Copy code
cd resto_sate_unikom

3. Buat database baru dan impor file resto_sate.sql yang terletak di direktori sql:
sql
Copy code
CREATE DATABASE resto_sate;
USE resto_sate;
SOURCE path/to/resto_sate.sql;

4. Konfigurasi file config.php dengan detail database Anda:
php
Copy code
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resto_sate";
?>

5. Jalankan server lokal menggunakan XAMPP atau alat serupa.