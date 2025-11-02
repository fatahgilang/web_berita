# Panduan Cepat Developer

## Memulai

Panduan ini akan membantu Anda dengan cepat mengatur dan menjalankan website Berita Maos secara lokal.

## Prasyarat

- PHP 8.1 atau lebih tinggi
- Composer
- MySQL atau MariaDB
- Node.js dan NPM
- Git

## Langkah Instalasi

### 1. Clone Repository
```bash
git clone <url-repository>
cd web_berita
```

### 2. Instal Dependensi PHP
```bash
composer install
```

### 3. Instal Dependensi Frontend
```bash
npm install
```

### 4. Konfigurasi Lingkungan
```bash
cp .env.example .env
```

Edit file `.env` dan atur kredensial database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=username_anda
DB_PASSWORD=password_anda

APP_URL=http://127.0.0.1:8000
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database
```bash
php artisan migrate
```

### 7. Buat Storage Symlink
```bash
php artisan storage:link
```

### 8. Bangun Aset Frontend
```bash
npm run build
```

### 9. Mulai Server Pengembangan
```bash
php artisan serve
```

Aplikasi akan tersedia di `http://127.0.0.1:8000`

## Tugas Pengembangan Umum

### Membuat Migrasi Baru
```bash
php artisan make:migration create_nama_tabel_table
```

### Menjalankan Migrasi
```bash
php artisan migrate
```

### Mengembalikan Migrasi
```bash
php artisan migrate:rollback
```

### Seeding Database
```bash
php artisan db:seed
```

### Membersihkan Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Gambaran Struktur Kode

### Controller
Terletak di `app/Http/Controllers/`
- `LandingController.php` - Logika halaman beranda
- `NewsController.php` - Fungsionalitas terkait berita
- `AuthorController.php` - Fungsionalitas profil penulis

### Model
Terletak di `app/Models/`
- `News.php` - Artikel berita
- `Author.php` - Penulis
- `NewsCategory.php` - Kategori berita
- `Banner.php` - Banner unggulan
- `User.php` - Akun pengguna

### View
Terletak di `resources/views/`
- `pages/` - Template halaman utama
- `layouts/` - File layout dasar
- `includes/` - Komponen yang dapat digunakan kembali

## Menguji Perubahan

### Menguji Rute
Setelah membuat perubahan pada rute, Anda dapat melihat daftar semua rute dengan:
```bash
php artisan route:list
```

### Menguji Database
Anda dapat menggunakan tinker untuk menguji query database:
```bash
php artisan tinker
```

### Menguji View
Bersihkan cache view setelah membuat perubahan pada template Blade:
```bash
php artisan view:clear
```

## Checklist Deployment

Sebelum mendeploy ke produksi:

1. [ ] Set `APP_ENV=production` di `.env`
2. [ ] Set `APP_DEBUG=false` di `.env`
3. [ ] Jalankan `php artisan config:cache`
4. [ ] Jalankan `php artisan route:cache`
5. [ ] Jalankan `php artisan view:cache`
6. [ ] Pastikan symlink penyimpanan dibuat
7. [ ] Verifikasi semua variabel lingkungan diatur dengan benar

## Perintah Berguna

### Perintah Artisan
```bash
# Daftar semua perintah yang tersedia
php artisan list

# Lihat lingkungan saat ini
php artisan env

# Periksa status aplikasi
php artisan up/down

# Generate file helper IDE
php artisan ide-helper:generate
```

### Perintah Database
```bash
# Buat model baru dengan migrasi
php artisan make:model NamaModel -m

# Buat controller baru
php artisan make:controller NamaController

# Buat seeder baru
php artisan make:seeder NamaSeeder
```

## Butuh Bantuan?

- Periksa dokumentasi yang ada di folder `docs/`
- Tinjau struktur codebase
- Lihat implementasi yang ada untuk fungsionalitas serupa
- Konsultasikan dengan team lead atau developer senior

---

*Panduan Cepat Developer terakhir diperbarui: 2 November 2025*