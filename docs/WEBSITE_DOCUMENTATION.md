# Dokumentasi Website Berita Maos

## Daftar Isi
1. [Gambaran Umum](#gambaran-umum)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Struktur Proyek](#struktur-proyek)
4. [Skema Database](#skema-database)
5. [Fitur](#fitur)
6. [Rute](#rute)
7. [Controller](#controller)
8. [Model](#model)
9. [View](#view)
10. [Komponen Frontend](#komponen-frontend)
11. [Fungsionalitas Pencarian](#fungsionalitas-pencarian)
12. [Deployment](#deployment)
13. [Troubleshooting](#troubleshooting)

## Gambaran Umum

Maos adalah website media berita yang dibangun dengan framework Laravel. Website ini memungkinkan pengguna untuk menjelajahi artikel berita, mencari konten tertentu, melihat artikel berdasarkan kategori, dan menjelajahi konten berdasarkan penulis. Platform ini memiliki desain responsif yang bekerja dengan baik di perangkat desktop maupun mobile.

## Teknologi yang Digunakan

- **Backend**: Laravel 10+
- **Frontend**: Template Blade, Tailwind CSS, JavaScript
- **Database**: MySQL
- **Package Manager**: Composer, NPM
- **Build Tool**: Vite
- **Penyimpanan Gambar**: Laravel Storage (disk public)

## Struktur Proyek

```
web_berita/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Providers/
├── database/
│   └── migrations/
├── public/
│   ├── assets/
│   └── storage/ (symlink)
├── resources/
│   ├── views/
│   │   ├── includes/
│   │   ├── layouts/
│   │   └── pages/
│   └── css/
├── routes/
└── docs/
```

## Skema Database

### Tabel Users
- id (bigint, primary)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- remember_token (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)

### Tabel Authors
- id (bigint, primary)
- user_id (bigint, foreign key ke users)
- username (string, unique)
- avatar (string)
- bio (longText)
- created_at (timestamp)
- updated_at (timestamp)

### Tabel News Categories
- id (bigint, primary)
- title (string)
- slug (string, unique)
- created_at (timestamp)
- updated_at (timestamp)

### Tabel News
- id (bigint, primary)
- author_id (bigint, foreign key ke authors)
- news_category_id (bigint, foreign key ke news_categories)
- title (string)
- slug (string, unique)
- thumbnail (string)
- content (longText)
- is_featured (boolean, default: false)
- created_at (timestamp)
- updated_at (timestamp)

### Tabel Banners
- id (bigint, primary)
- news_id (bigint, foreign key ke news)
- created_at (timestamp)
- updated_at (timestamp)

## Fitur

### 1. Halaman Beranda/Homepage
Halaman beranda menampilkan:
- Carousel banner dengan berita unggulan
- Bagian berita unggulan
- Bagian berita terbaru
- Showcase penulis
- Berita pilihan penulis

### 2. Daftar Berita
- Daftar berita dengan pagination
- Fungsionalitas pencarian
- Filter berdasarkan kategori
- Layout grid responsif

### 3. Detail Berita
- Konten artikel lengkap
- Bagian berita terkait
- Informasi penulis

### 4. Halaman Kategori
- Berita yang difilter berdasarkan kategori tertentu

### 5. Halaman Penulis
- Profil penulis dengan biografi
- Daftar artikel oleh penulis tersebut

### 6. Pencarian
- Pencarian global di semua artikel berita
- Pencarian berdasarkan judul dan konten

## Rute

| Method | URI              | Name           | Controller Method     | Deskripsi              |
|--------|------------------|----------------|-----------------------|--------------------------|
| GET    | /                | landing        | LandingController@index | Halaman beranda         |
| GET    | /news            | news.all       | NewsController@all    | Daftar semua berita      |
| GET    | /{slug}          | news.category  | NewsController@category | Daftar berita kategori  |
| GET    | /news/{slug}     | news.show      | NewsController@show   | Halaman detail berita    |
| GET    | /author/{username}| author.show    | AuthorController@show | Halaman profil penulis   |

## Controller

### LandingController
Menangani tampilan halaman beranda:
- Memuat data banner dengan berita terkait
- Mengambil berita unggulan
- Mengambil berita terbaru
- Menampilkan showcase penulis

### NewsController
Mengelola fungsionalitas terkait berita:
- `show()` - Menampilkan artikel berita individual
- `all()` - Menampilkan semua berita dengan kemampuan pencarian dan filter
- `category()` - Menampilkan berita berdasarkan kategori

### AuthorController
Menangani halaman profil penulis:
- `show()` - Menampilkan informasi penulis dan artikel mereka

## Model

### News
Mewakili artikel berita dengan relasi ke:
- Author (belongsTo)
- NewsCategory (belongsTo)
- Banner (hasOne)

Termasuk accessor untuk generasi URL thumbnail.

### Author
Mewakili penulis berita dengan relasi ke:
- User (belongsTo)
- News (hasMany)

Termasuk accessor untuk generasi URL avatar dengan fallback ke gambar default.

### NewsCategory
Mewakili kategori berita dengan relasi ke:
- News (hasMany)

### Banner
Mewakili banner unggulan dengan relasi ke:
- News (belongsTo)

### User
Model pengguna Laravel standar dengan kolom role tambahan.

## View

### Layout
- `layouts/app.blade.php` - Template layout utama
- `includes/navbar.blade.php` - Bar navigasi dengan pencarian

### Halaman
- `pages/landing.blade.php` - Halaman beranda dengan semua bagian
- `pages/news/all.blade.php` - Daftar berita dengan pencarian/filter
- `pages/news/show.blade.php` - Artikel berita individual
- `pages/news/category.blade.php` - Berita berdasarkan kategori
- `pages/author/show.blade.php` - Profil penulis

## Komponen Frontend

### Navigasi
- Navbar responsif dengan menu mobile
- Navigasi kategori
- Input pencarian global
- Tombol login

### Kartu Berita
- Styling konsisten untuk pratinjau berita
- Badge kategori
- Gambar thumbnail
- Tanggal publikasi

### Carousel Banner
- Implementasi Swiper.js
- Tampilan berita unggulan full-width
- Overlay gradien untuk keterbacaan teks

## Fungsionalitas Pencarian

### Implementasi
Pencarian diimplementasikan di method `all()` NewsController:
- Mencari di field judul dan konten
- Menggunakan query LIKE dengan wildcard
- Bekerja dalam kombinasi dengan filter kategori
- Mempertahankan istilah pencarian di pagination

### Cara Penggunaan
1. Masukkan istilah pencarian di kotak pencarian navbar
2. Tekan Enter atau navigasi ke halaman hasil pencarian
3. Lihat hasil yang difilter dengan pagination
4. Hapus filter untuk melihat semua berita

## Deployment

### Kebutuhan
- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js dan NPM
- Web server (Apache/Nginx)

### Langkah Setup
1. Clone repository
2. Jalankan `composer install`
3. Jalankan `npm install`
4. Salin `.env.example` ke `.env` dan konfigurasi pengaturan database
5. Jalankan `php artisan key:generate`
6. Jalankan `php artisan migrate`
7. Jalankan `php artisan storage:link`
8. Jalankan `npm run build`
9. Mulai server dengan `php artisan serve`

### Konfigurasi
- Set `APP_URL` di `.env` agar sesuai dengan URL server Anda
- Konfigurasi pengaturan koneksi database
- Set up symlink penyimpanan untuk akses gambar

## Troubleshooting

### Gambar Tidak Tampil
1. Pastikan `php artisan storage:link` telah dijalankan
2. Periksa bahwa `APP_URL` di `.env` sesuai dengan URL server Anda
3. Verifikasi file gambar ada di `storage/app/public/`
4. Bersihkan cache dengan `php artisan config:clear`

### Pencarian Tidak Berfungsi
1. Verifikasi action form pencarian mengarah ke rute yang benar
2. Periksa bahwa parameter pencarian dikirim dengan benar
3. Pastikan indeks database ada untuk field yang dicari

### Link Kategori Tidak Berfungsi
1. Verifikasi slug kategori dibuat dengan benar
2. Periksa definisi rute di `routes/web.php`
3. Pastikan data kategori di-seed dengan benar

### Masalah Performa
1. Tambahkan indeks database pada kolom yang sering diquery
2. Implementasikan caching untuk data yang sering diakses
3. Optimalkan ukuran gambar dan gunakan format yang tepat

---

*Dokumentasi terakhir diperbarui: 2 November 2025*