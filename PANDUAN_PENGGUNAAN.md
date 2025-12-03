# PANDUAN PENGGUNAAN SISTEM E-LOG KARYAWAN

## 1. DEFINISI SISTEM

**Sistem E-Log Karyawan** adalah sistem digital berbasis web yang digunakan untuk mencatat, melacak, dan memvalidasi aktivitas harian karyawan dalam suatu organisasi. Sistem ini memungkinkan karyawan untuk mencatat aktivitas kerja harian mereka, sementara supervisor dan manager dapat memvalidasi aktivitas tersebut secara efisien. Sistem ini dirancang untuk meningkatkan transparansi, akuntabilitas, dan efisiensi dalam pelacakan aktivitas kerja karyawan.

### Tujuan Sistem:

-   Mencatat aktivitas harian karyawan secara sistematis
-   Memvalidasi aktivitas karyawan melalui hierarki organisasi
-   Menyediakan dashboard dan laporan untuk monitoring aktivitas
-   Meningkatkan transparansi dan akuntabilitas kerja
-   Memudahkan evaluasi kinerja karyawan

---

## 2. PERAN (ROLE) DAN HAK AKSES

Sistem ini memiliki 5 peran pengguna dengan hak akses yang berbeda:

### 2.1. Karyawan

**Hak Akses:**

-   Mencatat aktivitas harian sendiri
-   Melihat dan mengelola log aktivitas sendiri
-   Melihat dashboard statistik aktivitas sendiri
-   Mengedit dan menghapus log aktivitas sendiri (sebelum divalidasi)
-   Mengubah profil sendiri

**Tidak Dapat:**

-   Memvalidasi aktivitas
-   Melihat aktivitas karyawan lain
-   Mengakses data master (departemen, unit)
-   Mengelola data karyawan

### 2.2. Supervisor (SPV)

**Hak Akses:**

-   Semua hak akses Karyawan
-   Melihat aktivitas semua karyawan di unitnya
-   Memvalidasi (approve/reject) aktivitas karyawan di unitnya
-   Melakukan bulk approve/reject untuk beberapa aktivitas sekaligus
-   Melihat dashboard statistik unitnya
-   Melihat laporan detail aktivitas per karyawan di unitnya

**Tidak Dapat:**

-   Memvalidasi aktivitas supervisor atau manager lain
-   Mengakses data master (departemen, unit)
-   Mengelola data karyawan

### 2.3. Manager

**Hak Akses:**

-   Semua hak akses Supervisor
-   Melihat aktivitas semua karyawan di departemennya (termasuk semua unit dalam departemen)
-   Memvalidasi aktivitas karyawan dan supervisor di departemennya
-   Melihat dashboard statistik departemennya
-   Melihat laporan detail aktivitas per departemen

**Tidak Dapat:**

-   Memvalidasi aktivitas manager lain
-   Mengakses data master (departemen, unit)
-   Mengelola data karyawan

### 2.4. SDM / Admin

**Hak Akses:**

-   Semua hak akses Manager
-   Mengakses semua aktivitas karyawan di seluruh organisasi
-   Memvalidasi semua aktivitas
-   Mengelola data master (departemen, unit)
-   Mengelola data karyawan (tambah, edit, hapus, aktifkan/nonaktifkan)
-   Mengubah role karyawan
-   Mengubah password karyawan
-   Melihat semua dashboard dan laporan

### 2.5. Superadmin

**Hak Akses:**

-   Semua hak akses SDM/Admin
-   Akses penuh ke semua fitur sistem

---

## 3. CARA MENGGUNAKAN SISTEM

### 3.1. Login ke Sistem

1. Buka browser dan akses URL sistem e-log karyawan
2. Masukkan **Username** (NIK) dan **Password** yang telah diberikan
3. Klik tombol **Login**
4. Setelah login berhasil, Anda akan diarahkan ke halaman Dashboard

**Catatan:**

-   Jika lupa password, klik "Lupa Password?" untuk reset password
-   Password pertama kali harus diubah setelah login pertama (jika diminta)

---

### 3.2. Dashboard

Dashboard menampilkan ringkasan statistik dan grafik aktivitas berdasarkan peran pengguna:

#### 3.2.1. Statistik Status Aktivitas

Menampilkan jumlah aktivitas berdasarkan status:

-   **Menunggu Validasi**: Aktivitas yang belum divalidasi
-   **Tervalidasi**: Aktivitas yang telah disetujui
-   **Ditolak**: Aktivitas yang ditolak

#### 3.2.2. Grafik Trend Aktivitas (7 Hari Terakhir)

Menampilkan grafik line chart yang menunjukkan tren jumlah aktivitas selama 7 hari terakhir.

#### 3.2.3. Top 5 Aktivitas per Departemen

Menampilkan grafik bar chart 5 departemen dengan aktivitas terbanyak.

#### 3.2.4. Top 5 Karyawan Paling Aktif (Bulan Ini)

Menampilkan 5 karyawan dengan aktivitas terbanyak pada bulan berjalan.

#### 3.2.5. Timeline Aktivitas

Menampilkan timeline aktivitas karyawan dalam bentuk grafik Gantt chart yang menunjukkan distribusi waktu aktivitas.

#### 3.2.6. Distribusi Aktivitas per Departemen

Menampilkan grafik pie chart distribusi aktivitas per departemen dengan opsi untuk melihat detail per departemen.

---

### 3.3. Mencatat Aktivitas Harian

#### 3.3.1. Membuat Log Aktivitas Baru

1. Dari menu sidebar, klik **"Log Aktivitas"** → **"Tambah Log"**
   Atau klik tombol **"Tambah Log"** di halaman Log Aktivitas
2. Isi form dengan data berikut:
    - **Tanggal**: Pilih tanggal aktivitas (default: hari ini)
    - **Waktu Awal**: Masukkan waktu mulai aktivitas (format: HH:MM, contoh: 08:00)
    - **Waktu Akhir**: Masukkan waktu selesai aktivitas (format: HH:MM, contoh: 17:00)
    - **Aktivitas**: Tuliskan deskripsi aktivitas yang dilakukan (wajib diisi)
    - **Departemen**: Otomatis terisi sesuai departemen Anda
    - **Unit**: Otomatis terisi sesuai unit Anda
3. Klik tombol **"Simpan"** untuk menyimpan log aktivitas

**Catatan:**

-   Pastikan waktu akhir lebih besar dari waktu awal
-   Deskripsi aktivitas harus jelas dan detail
-   Status default adalah "Menunggu Validasi"

#### 3.3.2. Melihat Log Aktivitas Saya

1. Dari menu sidebar, klik **"Log Aktivitas"** → **"Aktivitas Saya"**
2. Halaman ini menampilkan semua log aktivitas Anda
3. Anda dapat menggunakan filter untuk mencari aktivitas tertentu:
    - Filter berdasarkan tanggal (dari - sampai)
    - Filter berdasarkan status
4. Klik **"Filter"** untuk menerapkan filter
5. Klik **"Reset"** untuk menghapus semua filter

#### 3.3.3. Mengedit Log Aktivitas

1. Dari halaman Log Aktivitas, klik tombol **"Edit"** pada log yang ingin diubah
2. Ubah data yang diperlukan
3. Klik tombol **"Simpan"** untuk menyimpan perubahan

**Catatan:**

-   Log aktivitas hanya dapat diedit jika statusnya masih "Menunggu Validasi"
-   Log yang sudah divalidasi atau ditolak tidak dapat diedit

#### 3.3.4. Menghapus Log Aktivitas

1. Dari halaman Log Aktivitas, klik tombol **"Hapus"** pada log yang ingin dihapus
2. Konfirmasi penghapusan pada dialog yang muncul
3. Log aktivitas akan dihapus dari sistem

**Catatan:**

-   Log aktivitas hanya dapat dihapus jika statusnya masih "Menunggu Validasi"
-   Log yang sudah divalidasi atau ditolak tidak dapat dihapus

---

### 3.4. Memvalidasi Aktivitas (Untuk SPV, Manager, SDM/Admin)

#### 3.4.1. Validasi Satu Aktivitas

1. Dari halaman Log Aktivitas, klik tombol **"Setujui"** atau **"Tolak"** pada log yang ingin divalidasi
2. Jika memilih **"Tolak"**, masukkan catatan validasi (opsional namun disarankan)
3. Klik tombol **"Konfirmasi"** untuk menyelesaikan validasi

**Catatan:**

-   Setelah divalidasi, status log akan berubah menjadi "Tervalidasi" atau "Ditolak"
-   Log yang sudah divalidasi tidak dapat diubah atau dihapus oleh karyawan

#### 3.4.2. Validasi Beberapa Aktivitas Sekaligus (Bulk Action)

1. Dari halaman Log Aktivitas, centang checkbox pada log yang ingin divalidasi
2. Toolbar bulk action akan muncul di bagian atas
3. Klik tombol **"Setujui Terpilih"** atau **"Tolak Terpilih"**
4. Jika memilih **"Tolak Terpilih"**, masukkan catatan validasi (opsional)
5. Klik tombol **"Konfirmasi"** untuk menyelesaikan validasi

**Catatan:**

-   Bulk action hanya tersedia untuk SPV, Manager, SDM, dan Admin
-   Anda dapat memilih beberapa log sekaligus dengan mencentang checkbox

---

### 3.5. Melihat Detail Aktivitas

#### 3.5.1. Detail Aktivitas per Karyawan

1. Dari halaman Log Aktivitas, klik nama karyawan atau tombol **"Detail"**
2. Halaman detail menampilkan:
    - Informasi karyawan (Nama, NIK, Departemen, Unit)
    - Statistik aktivitas (Total, Menunggu, Tervalidasi, Ditolak)
    - Daftar semua aktivitas karyawan dengan pagination
    - Grafik distribusi aktivitas per hari
    - Grafik distribusi aktivitas per status

#### 3.5.2. Detail Aktivitas per Departemen

1. Dari Dashboard, klik pada grafik **"Distribusi Aktivitas per Departemen"** atau dari menu sidebar
2. Halaman detail menampilkan:
    - Informasi departemen
    - Statistik aktivitas departemen
    - Daftar karyawan dalam departemen dengan jumlah aktivitas
    - Grafik distribusi aktivitas per status
    - Grafik distribusi aktivitas per unit
3. Klik pada nama karyawan untuk melihat detail aktivitas karyawan tersebut
4. Aktivitas per karyawan ditampilkan dalam accordion yang dapat dibuka/ditutup

---

### 3.6. Manajemen Data Master (Hanya SDM/Admin)

#### 3.6.1. Mengelola Departemen

1. Dari menu sidebar, klik **"Data Master"** → **"Departemen"**
2. Halaman menampilkan daftar semua departemen
3. **Menambah Departemen:**
    - Klik tombol **"Tambah Departemen"**
    - Isi nama departemen
    - Klik **"Simpan"**
4. **Mengedit Departemen:**
    - Klik tombol **"Edit"** pada departemen yang ingin diubah
    - Ubah nama departemen
    - Klik **"Simpan"**
5. **Menghapus Departemen:**
    - Klik tombol **"Hapus"** pada departemen yang ingin dihapus
    - Konfirmasi penghapusan

**Catatan:**

-   Departemen yang sudah digunakan tidak dapat dihapus (soft delete)
-   Pastikan tidak ada karyawan yang menggunakan departemen sebelum menghapus

#### 3.6.2. Mengelola Unit

1. Dari menu sidebar, klik **"Data Master"** → **"Unit"**
2. Halaman menampilkan daftar semua unit
3. **Menambah Unit:**
    - Klik tombol **"Tambah Unit"**
    - Isi nama unit dan pilih departemen
    - Klik **"Simpan"**
4. **Mengedit Unit:**
    - Klik tombol **"Edit"** pada unit yang ingin diubah
    - Ubah data unit
    - Klik **"Simpan"**
5. **Menghapus Unit:**
    - Klik tombol **"Hapus"** pada unit yang ingin dihapus
    - Konfirmasi penghapusan

**Catatan:**

-   Unit yang sudah digunakan tidak dapat dihapus (soft delete)
-   Pastikan tidak ada karyawan yang menggunakan unit sebelum menghapus

---

### 3.7. Manajemen Karyawan (Hanya SDM/Admin)

1. Dari menu sidebar, klik **"Karyawan"**
2. Halaman menampilkan daftar semua karyawan dengan informasi:
    - NIK (Username)
    - Nama
    - Departemen
    - Unit
    - Role
    - Status (Aktif/Nonaktif)
3. **Mengaktifkan/Nonaktifkan Karyawan:**
    - Klik tombol **"Aktifkan"** atau **"Nonaktifkan"** pada karyawan yang ingin diubah
4. **Mengubah Data Karyawan:**
    - Klik tombol **"Edit"** pada karyawan yang ingin diubah
    - Ubah data yang diperlukan (nama, departemen, unit, role)
    - Klik **"Simpan"**
5. **Mengubah Password Karyawan:**
    - Klik tombol **"Ubah Password"** pada karyawan yang ingin diubah
    - Masukkan password baru
    - Klik **"Simpan"**
6. **Mengubah Role Karyawan:**
    - Klik tombol **"Ubah Role"** pada karyawan yang ingin diubah
    - Pilih role baru (Karyawan, SPV, Manager, SDM, Admin)
    - Klik **"Simpan"**

---

### 3.8. Mengelola Profil

1. Dari menu sidebar, klik **"Profil"** atau klik nama Anda di topbar
2. Halaman menampilkan informasi profil Anda:
    - NIK (Username)
    - Nama
    - Email
    - Departemen
    - Unit
    - Role
3. **Mengubah Profil:**
    - Klik tombol **"Edit Profil"**
    - Ubah data yang diizinkan (nama, email)
    - Klik **"Simpan"**
4. **Mengubah Password:**
    - Klik tombol **"Ubah Password"**
    - Masukkan password lama
    - Masukkan password baru
    - Konfirmasi password baru
    - Klik **"Simpan"**

---

## 4. FILTER DAN PENCARIAN

### 4.1. Filter Log Aktivitas

Di halaman Log Aktivitas, Anda dapat menggunakan filter berikut:

1. **Filter Tanggal:**

    - **Tanggal Dari**: Filter aktivitas mulai dari tanggal tertentu
    - **Tanggal Sampai**: Filter aktivitas sampai tanggal tertentu

2. **Filter Status:**

    - Semua Status
    - Menunggu Validasi
    - Tervalidasi
    - Ditolak

3. **Filter Nama Karyawan** (untuk SPV, Manager, SDM/Admin):

    - Masukkan nama karyawan untuk mencari aktivitas karyawan tertentu

4. Klik **"Filter"** untuk menerapkan filter
5. Klik **"Reset"** untuk menghapus semua filter

---

## 5. STATUS AKTIVITAS

Sistem menggunakan 3 status untuk aktivitas:

1. **Menunggu Validasi** (Kuning)

    - Status default untuk aktivitas baru
    - Aktivitas masih dapat diedit atau dihapus oleh karyawan
    - Menunggu validasi dari atasan

2. **Tervalidasi** (Hijau)

    - Aktivitas telah disetujui oleh atasan
    - Aktivitas tidak dapat lagi diedit atau dihapus
    - Menunjukkan aktivitas telah divalidasi

3. **Ditolak** (Merah)
    - Aktivitas ditolak oleh atasan
    - Aktivitas tidak dapat lagi diedit atau dihapus
    - Biasanya disertai dengan catatan validasi yang menjelaskan alasan penolakan

---

## 6. TIPS DAN BEST PRACTICES

### 6.1. Untuk Karyawan:

-   **Catat aktivitas harian secara rutin** setiap hari untuk memastikan tidak ada yang terlewat
-   **Gunakan deskripsi yang jelas dan detail** agar atasan dapat memahami aktivitas yang dilakukan
-   **Pastikan waktu aktivitas logis** (waktu akhir > waktu awal)
-   **Periksa status validasi** secara berkala untuk mengetahui apakah aktivitas sudah divalidasi
-   **Jika aktivitas ditolak**, baca catatan validasi dan perbaiki jika perlu

### 6.2. Untuk Supervisor/Manager:

-   **Validasi aktivitas secara berkala** untuk memastikan karyawan mendapatkan feedback tepat waktu
-   **Berikan catatan validasi** jika menolak aktivitas, agar karyawan tahu alasan penolakan
-   **Gunakan bulk action** untuk memvalidasi beberapa aktivitas sekaligus dan menghemat waktu
-   **Periksa dashboard** untuk melihat tren dan statistik aktivitas tim

### 6.3. Untuk SDM/Admin:

-   **Kelola data master** (departemen, unit) dengan baik untuk memastikan struktur organisasi akurat
-   **Perbarui data karyawan** secara berkala (departemen, unit, role)
-   **Pantau dashboard** untuk melihat aktivitas keseluruhan organisasi
-   **Gunakan laporan detail** untuk analisis lebih mendalam

---

## 7. PERTANYAAN UMUM (FAQ)

### Q1: Bagaimana cara mengubah password saya?

**A:** Klik menu "Profil" → "Ubah Password", masukkan password lama dan password baru, lalu klik "Simpan".

### Q2: Apakah saya bisa mengedit log aktivitas yang sudah divalidasi?

**A:** Tidak, log aktivitas yang sudah divalidasi (Tervalidasi atau Ditolak) tidak dapat lagi diedit atau dihapus.

### Q3: Bagaimana cara melihat aktivitas karyawan lain?

**A:** Hanya SPV, Manager, SDM, dan Admin yang dapat melihat aktivitas karyawan lain sesuai dengan hierarki organisasi mereka.

### Q4: Apa yang harus saya lakukan jika aktivitas saya ditolak?

**A:** Baca catatan validasi yang diberikan oleh atasan. Jika diperlukan, Anda dapat membuat log aktivitas baru dengan informasi yang lebih lengkap.

### Q5: Bagaimana cara menggunakan bulk action untuk validasi?

**A:** Centang checkbox pada beberapa log aktivitas, lalu klik tombol "Setujui Terpilih" atau "Tolak Terpilih" yang muncul di toolbar.

### Q6: Apakah sistem mendukung export data?

**A:** Fitur export data dapat ditambahkan sesuai kebutuhan. Hubungi administrator untuk informasi lebih lanjut.

### Q7: Bagaimana cara reset password jika saya lupa?

**A:** Klik "Lupa Password?" di halaman login, masukkan email atau username, lalu ikuti instruksi yang dikirim ke email Anda.

---

## 8. DUKUNGAN TEKNIS

Jika Anda mengalami masalah atau memiliki pertanyaan tentang penggunaan sistem, silakan hubungi:

-   **Email Support**: [masukkan email support]
-   **Telepon**: [masukkan nomor telepon]
-   **Jam Operasional**: [masukkan jam operasional]

---

**Dokumen ini akan diperbarui secara berkala. Pastikan Anda menggunakan versi terbaru dari panduan ini.**

---

_Terakhir diperbarui: [Tanggal]_
