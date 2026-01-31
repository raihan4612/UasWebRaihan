# Sistem Informasi Perpustakaan Daerah Banjarbaru

Website manajemen perpustakaan berbasis PHP dan MySQL dengan fitur CRUD lengkap.

## Fitur Utama

### Master Data (8 Tabel)
1. **Anggota** - Manajemen data anggota perpustakaan
2. **Buku** - Katalog buku dengan pengarang dan penerbit
3. **Kategori Buku** - Klasifikasi kategori buku
4. **Pengarang** - Data pengarang buku
5. **Penerbit** - Data penerbit buku
6. **Petugas** - Data petugas perpustakaan
7. **Rak** - Penempatan rak buku
8. **Tarif Denda** - Daftar tarif denda berdasarkan jenis kerusakan

### Transaksi (5 Modul)
1. **Peminjaman** - Transaksi peminjaman buku
2. **Pengembalian** - Transaksi pengembalian buku
3. **Buku-Kategori** - Relasi buku dengan kategori
4. **Buku-Rak** - Penempatan buku di rak
5. **Denda** - Pencatatan denda kerusakan buku

### Laporan (2 Laporan dengan JOIN)
1. **Laporan Peminjaman** - Join multiple tables: peminjaman, anggota, petugas, buku, pengembalian
2. **Laporan Denda** - Join multiple tables: denda, pengembalian, peminjaman, anggota, buku, tarif_denda, pengarang, penerbit

## Instalasi

1. **Import Database**
   - Buat database baru: perpustakaan_normalized
   - Import file: perpustakaan_normalized.sql

2. **Konfigurasi Database**
   Edit file `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'perpustakaan_normalized');
   ```

3. **Copy Files**
   - Extract file zip ke folder htdocs/www
   - Pastikan folder perpustakaan_website dapat diakses

4. **Akses Website**
   ```
   http://localhost/perpustakaan_website/
   ```

## Struktur Folder

```
perpustakaan_website/
├── assets/
│   └── images/          # Folder untuk gambar
├── config/
│   └── database.php     # Konfigurasi database
├── css/
│   └── style.css        # File CSS utama
├── includes/
│   ├── header.php       # Header template
│   ├── footer.php       # Footer template
│   └── get_detail_peminjaman.php  # AJAX helper
├── js/
│   └── script.js        # File JavaScript
├── modules/
│   ├── master/          # CRUD Master Data
│   │   ├── anggota.php
│   │   ├── buku.php
│   │   ├── kategori.php
│   │   ├── pengarang.php
│   │   ├── penerbit.php
│   │   ├── petugas.php
│   │   ├── rak.php
│   │   └── tarif_denda.php
│   ├── transaksi/       # CRUD Transaksi
│   │   ├── peminjaman.php
│   │   ├── pengembalian.php
│   │   ├── buku_kategori.php
│   │   ├── buku_rak.php
│   │   └── denda.php
│   └── laporan/         # Laporan dengan JOIN
│       ├── laporan_peminjaman.php
│       └── laporan_denda.php
├── index.php            # Halaman utama
└── README.md            # Dokumentasi
```

## Tema & Desain

- **Warna Utama**: Putih, Abu Kebiruan, Kuning
- **Font**: Segoe UI
- **Responsive**: Mobile-friendly design
- **Framework CSS**: Custom CSS dengan Flexbox & Grid

## Fitur Teknis

- CRUD Lengkap untuk semua tabel master dan transaksi
- JOIN Query pada laporan (minimal 2 atau lebih tabel)
- Filter & Search pada laporan
- Dashboard dengan statistik real-time
- Responsive Design untuk mobile dan desktop
- Print Function untuk laporan
- Form Validation client-side dan server-side
- Clean URL dan struktur folder terorganisir
- SQL Injection Prevention

## Teknologi

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Icons**: Font Awesome 6.4.0

## Screenshot Fitur

### Dashboard Home
- Ucapan selamat datang Perpustakaan Banjarbaru
- Background gambar perpustakaan
- Statistik perpustakaan (total anggota, buku, peminjaman, dll)
- Card untuk setiap modul dengan deskripsi

### Master Data
- Form tambah/edit/hapus data
- Tabel data dengan sorting
- Validasi form

### Transaksi
- Form multi-select untuk buku
- Relasi antar tabel
- Status tracking

### Laporan
- Filter berdasarkan anggota dan periode
- Export/Print laporan
- Statistik aggregat

## Database Schema

Database menggunakan normalisasi dengan foreign key constraints untuk menjaga integritas data.

## Lisensi

Project ini dibuat untuk keperluan UAS (Ujian Akhir Semester).

## Catatan

- Pastikan PHP dan MySQL sudah terinstall
- Aktifkan extension mysqli di php.ini
- Backup database secara berkala

---

**© 2026 Perpustakaan Banjarbaru - All Rights Reserved**
