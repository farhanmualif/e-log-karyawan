# E-Log Karyawan

Sistem pencatatan aktivitas karyawan berbasis web untuk **Ludira Husada Tama**. Platform ini memungkinkan karyawan untuk mencatat aktivitas harian mereka dengan efisien dan mudah, serta memungkinkan supervisor dan manajer untuk memantau dan mengelola log aktivitas karyawan.

## 📋 Tentang Project

E-Log Karyawan adalah aplikasi web yang dibangun menggunakan Laravel Framework untuk membantu perusahaan dalam mencatat, memantau, dan mengelola aktivitas harian karyawan. Sistem ini terintegrasi dengan database Khanza untuk sinkronisasi data karyawan.

### Fitur Utama

-   ✅ **Pencatatan Log Aktivitas** - Karyawan dapat mencatat aktivitas harian dengan detail waktu, deskripsi, dan status
-   ✅ **Manajemen Karyawan** - Admin dapat mengaktifkan dan mengelola data karyawan
-   ✅ **Hierarki Akses Berdasarkan Role**:
    -   **Karyawan**: Hanya melihat dan mengelola log aktivitas sendiri
    -   **SPV (Supervisor)**: Melihat log aktivitas seluruh karyawan di unitnya
    -   **Manager**: Melihat log aktivitas seluruh unit dalam departemennya
    -   **SDM/Admin**: Mengelola seluruh data dan log aktivitas
    -   **Superadmin**: Akses penuh ke seluruh sistem
-   ✅ **Data Master** - Pengelolaan departemen dan unit kerja
-   ✅ **Dashboard Analytics** - Visualisasi data log aktivitas dengan grafik
-   ✅ **Approval System** - Fitur persetujuan/pembatalan log aktivitas
-   ✅ **Profile Management** - Pengelolaan profil dan perubahan password

## 🔧 Requirements

-   XAMPP (PHP >= 7.1.3, Apache, MySQL)
-   Database Khanza (untuk integrasi data karyawan)

**Catatan**: Project ini sudah termasuk file `composer.phar` di dalam folder, jadi tidak perlu install Composer secara global. XAMPP sudah mencakup PHP yang diperlukan untuk menjalankan `composer.phar`.

## 📦 Instalasi di XAMPP

### 1. Persiapan XAMPP

1. Pastikan XAMPP sudah terinstall di komputer Anda
2. Jalankan XAMPP Control Panel
3. Start **Apache** dan **MySQL**

### 2. Clone atau Copy Project

#### Via Git (jika menggunakan Git):

```bash
cd C:\xampp\htdocs
git clone [link-repository-github] e-log-karyawan
cd e-log-karyawan
```

#### Via Manual:

1. Download atau extract project ke folder `C:\xampp\htdocs\e-log-karyawan`

### 3. Install Dependencies

Buka terminal/command prompt di folder project dan jalankan:

```bash
cd C:\xampp\htdocs\e-log-karyawan
composer.phar install
```

**Catatan**: File `composer.phar` sudah tersedia di folder project, jadi tidak perlu install Composer secara global.

### 4. Setup Environment

```bash
# Copy file environment
copy .env.example .env
# atau di PowerShell/Windows: copy .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Konfigurasi Database

1. Buka phpMyAdmin melalui browser: `http://localhost/phpmyadmin`
2. Buat database baru (contoh: `elog_karyawan`)
3. Edit file `.env` dan sesuaikan konfigurasi database:

```env
APP_URL=http://localhost/e-log-karyawan/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_elog_karyawan
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi Database Khanza
KHANZA_DB_HOST=127.0.0.1
KHANZA_DB_PORT=3306
KHANZA_DB_DATABASE=nama_database_khanza
KHANZA_DB_USERNAME=root
KHANZA_DB_PASSWORD=
```

### 6. Import Database

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Pilih database yang sudah dibuat (contoh: `elog_karyawan`)
3. Klik tab **Import**
4. Klik **Choose File** dan pilih file `db_elog_karyawan.sql`
5. Klik **Go** untuk melakukan import

**Alternatif via Command Line:**

```bash
mysql -u root -p elog_karyawan < db_elog_karyawan.sql
```

### 7. Set Permissions (Windows)

Pastikan folder berikut memiliki izin **Write**:

-   `storage/`
-   `bootstrap/cache/`

Jika diperlukan, klik kanan folder → Properties → Security → Edit permissions untuk user.

### 8. Akses Aplikasi

Akses aplikasi melalui browser:

```
http://localhost/e-log-karyawan/public
```

**Catatan Penting**:

-   Pastikan mengakses melalui URL yang diakhiri dengan `/public`
-   Jika ingin mengakses tanpa `/public`, pastikan file `.htaccess` di root project sudah dikonfigurasi dengan benar untuk redirect ke folder `public`

### 9. Konfigurasi Virtual Host (Opsional)

Konfigurasi Virtual Host memungkinkan Anda mengakses aplikasi dengan URL `http://localhost/e-log-karyawan/` tanpa perlu menambahkan `/public` di akhir URL.

#### Langkah 1: Aktifkan Modul Alias dan Virtual Host di Apache

1. Buka file `httpd.conf` di XAMPP

    - Lokasi: `C:\xampp\apache\conf\httpd.conf`
    - Buka dengan text editor (Notepad++, Visual Studio Code, dll)

2. Pastikan modul berikut sudah aktif (tidak ada tanda `#` di depannya):

```apache
LoadModule alias_module modules/mod_alias.so
LoadModule vhost_alias_module modules/mod_vhost_alias.so
```

3. Pastikan baris berikut juga aktif:

```apache
Include conf/extra/httpd-vhosts.conf
```

**Catatan**: Jika ada tanda `#` di depan baris tersebut, hapus tanda `#` untuk mengaktifkannya.

#### Langkah 2: Edit File httpd-vhosts.conf

1. Buka file `httpd-vhosts.conf` di XAMPP

    - Lokasi: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
    - Buka dengan text editor (Notepad++, Visual Studio Code, dll)

2. Periksa apakah konfigurasi untuk e-log-karyawan sudah ada. Jika sudah ada di dalam `<VirtualHost *:80>` dengan `ServerName localhost`, Anda hanya perlu memastikan konfigurasinya benar.

3. Jika belum ada, tambahkan konfigurasi berikut di dalam blok `<VirtualHost *:80>` yang sudah ada (atau buat blok baru jika belum ada):

```apache
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "C:/xampp/htdocs"

    <Directory "C:/xampp/htdocs">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Alias untuk e-log-karyawan
    Alias /e-log-karyawan "C:/xampp/htdocs/e-log-karyawan/public"
    <Directory "C:/xampp/htdocs/e-log-karyawan/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Catatan**: Jika sudah ada blok `<VirtualHost *:80>` dengan `ServerName localhost`, tambahkan hanya bagian `Alias` dan `<Directory>` untuk e-log-karyawan di dalam blok tersebut.

**Penjelasan Konfigurasi**:

-   `ServerName localhost`: Menggunakan localhost sebagai server name
-   `DocumentRoot`: Mengarah ke folder htdocs utama
-   `Alias /e-log-karyawan`: Membuat alias URL `/e-log-karyawan` yang mengarah ke folder `public` Laravel
-   `AllowOverride All`: Diperlukan agar file `.htaccess` Laravel dapat berfungsi
-   `Require all granted`: Memberikan akses ke semua user

#### Langkah 3: Restart Apache

1. Buka **XAMPP Control Panel**
2. Stop Apache (klik **Stop**)
3. Start Apache lagi (klik **Start**)

#### Langkah 4: Akses Aplikasi

Buka browser dan akses aplikasi melalui:

```
http://localhost/e-log-karyawan/
```

**Catatan**: Setelah konfigurasi virtual host, Anda tidak perlu lagi menambahkan `/public` di akhir URL.

**Jika tidak menggunakan virtual host**, Anda tetap bisa mengakses aplikasi dengan:

```
http://localhost/e-log-karyawan/public
```

### 10. Troubleshooting

**Masalah Umum:**

1. **Error 500 / Internal Server Error**

    - Pastikan folder `storage` dan `bootstrap/cache` memiliki izin Write
    - Cek file `.env` sudah benar dan `APP_KEY` sudah di-generate

2. **Error Database Connection**

    - Pastikan MySQL di XAMPP sudah running
    - Cek konfigurasi database di file `.env`
    - Pastikan database sudah dibuat dan di-import

3. **Tidak bisa akses tanpa /public**

    - Gunakan URL lengkap: `http://localhost/e-log-karyawan/public`
    - Atau konfigurasi virtual host (langkah 9)

4. **Error menjalankan composer.phar install**
    - Pastikan PHP sudah terinstall dan bisa diakses via command line (sudah ada di XAMPP)
    - Pastikan file `composer.phar` ada di folder root project
    - Jika masih error, coba jalankan: `php composer.phar install`
    - Pastikan folder project memiliki izin untuk menulis (untuk membuat folder vendor)

## 👤 Informasi Login Default

### Login Superadmin

-   **User ID**: `superadmin`
-   **Password**: `superduperadmin789`

### Login Admin/SDM

-   **User ID**: `sdm`
-   **Password**: `sdm54321`

### Login User Biasa

-   **User ID**: Sama dengan login di Khanza
-   **Password Default**: `12345`
-   **Catatan**: User akan otomatis diminta untuk mengubah password saat login pertama kali

## 📁 Struktur Project

```
e-log-karyawan/
├── app/
│   ├── Http/
│   │   └── Controllers/     # Controller aplikasi
│   └── User.php             # Model User
├── database/
│   ├── migrations/          # Database migrations
│   └── seeds/               # Database seeders
├── resources/
│   └── views/               # Blade templates
├── routes/
│   └── web.php              # Web routes
├── public/                  # Public assets
├── .env.example             # Template environment
├── db_elog_karyawan.sql     # Database SQL file
└── composer.json            # PHP dependencies
```

## 🔐 Role dan Permission

| Role       | Akses Log Aktivitas      | Manajemen Karyawan | Data Master |
| ---------- | ------------------------ | ------------------ | ----------- |
| Karyawan   | Hanya data sendiri       | -                  | -           |
| SPV        | Semua karyawan di unit   | -                  | -           |
| Manager    | Semua unit di departemen | -                  | -           |
| Admin/SDM  | Semua data               | ✅                 | ✅          |
| Superadmin | Semua data               | ✅                 | ✅          |

## 🚀 Usage

1. Login dengan kredensial yang sesuai
2. **Karyawan**: Gunakan menu "Log Aktivitas" untuk mencatat aktivitas harian
3. **SPV/Manager**: Gunakan dashboard untuk memantau log aktivitas karyawan
4. **Admin**: Akses menu "Karyawan" dan "Data Master" untuk mengelola data

## 🔄 Integrasi dengan Khanza

Sistem ini terintegrasi dengan database Khanza untuk:

-   Sinkronisasi data karyawan
-   Single Sign-On (SSO) menggunakan user ID yang sama dengan Khanza
-   Pengambilan data master karyawan

## 👥 Kontributor

-   Development Team Ludira Husada Tama

## 📞 Support

Untuk pertanyaan atau masalah, silakan hubungi tim development.
