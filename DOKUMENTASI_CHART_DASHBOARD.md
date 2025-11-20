# Dokumentasi Chart Dashboard E-Log Karyawan

## Penjelasan Masing-Masing Chart dan Cara Membaca Data

---

## 1. **Status Log Aktivitas (Donut Chart)**

### 📊 Apa yang Ditampilkan?
Chart ini menampilkan **distribusi status** dari semua log aktivitas yang ada dalam sistem.

### 🎨 Komponen Chart:
- **Tipe**: Donut Chart (Pie Chart dengan lubang di tengah)
- **Warna**:
  - 🟡 **Kuning** = Menunggu Validasi
  - 🟢 **Hijau** = Tervalidasi
  - 🔴 **Merah** = Ditolak
- **Data**: Menampilkan jumlah dan persentase dari masing-masing status

### 📖 Cara Membaca:
1. **Lihat ukuran setiap potongan**:
   - Potongan yang lebih besar = jumlah lebih banyak
   - Potongan yang lebih kecil = jumlah lebih sedikit

2. **Baca persentase di setiap potongan**:
   - Contoh: "Menunggu: 45.2%" berarti 45.2% dari total log masih menunggu validasi

3. **Lihat legend di bawah chart**:
   - Menampilkan label dan jumlah absolut dari setiap status

### 💡 Insight yang Bisa Didapat:
- ✅ **Jika hijau dominan**: Banyak log yang sudah tervalidasi (baik)
- ⚠️ **Jika kuning dominan**: Banyak log yang masih menunggu validasi (perlu perhatian)
- ❌ **Jika merah dominan**: Banyak log yang ditolak (perlu evaluasi)

### 📝 Contoh Interpretasi:
```
Menunggu: 30% (60 log)
Tervalidasi: 65% (130 log)
Ditolak: 5% (10 log)

Interpretasi: 
- Sebagian besar log sudah tervalidasi (65%)
- Masih ada 30% yang menunggu validasi (perlu ditindaklanjuti)
- Hanya 5% yang ditolak (relatif kecil)
```

---

## 2. **Trend Log Aktivitas (7 Hari Terakhir) - Line Chart**

### 📊 Apa yang Ditampilkan?
Chart ini menampilkan **trend jumlah log aktivitas** selama 7 hari terakhir dalam bentuk garis.

### 🎨 Komponen Chart:
- **Tipe**: Line Chart (Grafik Garis)
- **Sumbu X (Horizontal)**: Tanggal (format: dd/mm)
- **Sumbu Y (Vertikal)**: Jumlah Log Aktivitas
- **Garis**: Menghubungkan titik-titik data setiap hari

### 📖 Cara Membaca:
1. **Baca sumbu X (bawah)**:
   - Menampilkan tanggal dari 7 hari terakhir
   - Contoh: "01/12", "02/12", "03/12", dst.

2. **Baca sumbu Y (kiri)**:
   - Menampilkan jumlah log aktivitas
   - Contoh: 0, 10, 20, 30, dst.

3. **Lihat garis trend**:
   - **Garis naik** (↗️) = Jumlah log meningkat dari hari sebelumnya
   - **Garis turun** (↘️) = Jumlah log menurun dari hari sebelumnya
   - **Garis datar** (→) = Jumlah log stabil

4. **Hover pada titik**:
   - Akan menampilkan detail jumlah log pada hari tersebut

### 💡 Insight yang Bisa Didapat:
- ✅ **Garis naik konsisten**: Aktivitas karyawan meningkat (positif)
- ⚠️ **Garis turun drastis**: Ada penurunan aktivitas (perlu investigasi)
- 📈 **Pola naik-turun**: Normal, menunjukkan variasi aktivitas harian
- 📉 **Garis datar rendah**: Aktivitas rendah, mungkin ada masalah

### 📝 Contoh Interpretasi:
```
Hari 1: 25 log
Hari 2: 30 log (naik +5)
Hari 3: 28 log (turun -2)
Hari 4: 35 log (naik +7)
Hari 5: 32 log (turun -3)
Hari 6: 40 log (naik +8)
Hari 7: 38 log (turun -2)

Interpretasi:
- Trend keseluruhan naik (dari 25 ke 38)
- Ada fluktuasi normal setiap hari
- Hari 6 adalah hari paling produktif (40 log)
```

---

## 3. **Log Aktivitas per Departemen (Bar Chart)**

### 📊 Apa yang Ditampilkan?
Chart ini menampilkan **perbandingan jumlah log aktivitas** dari **Top 5 Departemen** yang paling aktif.

### 🎨 Komponen Chart:
- **Tipe**: Bar Chart Vertikal
- **Sumbu X (Horizontal)**: Nama Departemen
- **Sumbu Y (Vertikal)**: Jumlah Log Aktivitas
- **Bar (Batang)**: Setiap batang mewakili satu departemen

### 📖 Cara Membaca:
1. **Baca label di bawah (sumbu X)**:
   - Menampilkan nama departemen
   - Contoh: "IT", "HR", "Finance", dst.

2. **Baca tinggi setiap bar**:
   - Bar lebih tinggi = lebih banyak log aktivitas
   - Bar lebih pendek = lebih sedikit log aktivitas

3. **Baca angka di atas bar**:
   - Menampilkan jumlah log yang tepat untuk departemen tersebut

4. **Urutan bar**:
   - Bar paling tinggi = departemen paling aktif
   - Bar paling pendek = departemen kurang aktif

### 💡 Insight yang Bisa Didapat:
- 🏆 **Departemen dengan bar tertinggi**: Departemen paling produktif
- ⚖️ **Perbandingan antar departemen**: Melihat distribusi aktivitas
- 📊 **Kesetaraan aktivitas**: Jika bar hampir sama tinggi = aktivitas merata
- ⚠️ **Ketimpangan besar**: Jika satu bar sangat tinggi dan lainnya rendah = perlu evaluasi

### 📝 Contoh Interpretasi:
```
IT: 150 log (bar tertinggi)
HR: 120 log
Finance: 100 log
Marketing: 80 log
Operations: 60 log (bar terpendek)

Interpretasi:
- Departemen IT adalah yang paling aktif
- Ada perbedaan aktivitas antar departemen
- Operations perlu ditingkatkan aktivitasnya
```

---

## 4. **Jam Kerja Rata-rata (7 Hari Terakhir) - Area Chart**

### 📊 Apa yang Ditampilkan?
Chart ini menampilkan **rata-rata jam kerja per hari** selama 7 hari terakhir, dihitung dari durasi aktivitas yang sudah **tervalidasi**.

### 🎨 Komponen Chart:
- **Tipe**: Area Chart (Area yang diisi dengan gradient)
- **Sumbu X (Horizontal)**: Tanggal (format: dd/mm)
- **Sumbu Y (Vertikal)**: Rata-rata Jam Kerja (dalam jam)
- **Area**: Daerah yang diisi menunjukkan rata-rata jam kerja

### 📖 Cara Membaca:
1. **Baca sumbu X (bawah)**:
   - Menampilkan tanggal 7 hari terakhir
   - Contoh: "01/12", "02/12", dst.

2. **Baca sumbu Y (kiri)**:
   - Menampilkan rata-rata jam kerja
   - Contoh: 0, 2, 4, 6, 8 jam

3. **Lihat tinggi area**:
   - Area lebih tinggi = rata-rata jam kerja lebih lama
   - Area lebih rendah = rata-rata jam kerja lebih pendek

4. **Lihat trend garis**:
   - **Naik**: Rata-rata jam kerja meningkat
   - **Turun**: Rata-rata jam kerja menurun
   - **Datar**: Rata-rata jam kerja stabil

5. **Hover pada titik**:
   - Menampilkan rata-rata jam kerja yang tepat (dalam format jam, contoh: 7.5 jam)

### 💡 Insight yang Bisa Didapat:
- ✅ **Area tinggi konsisten**: Karyawan bekerja dengan durasi yang baik
- ⚠️ **Area rendah**: Durasi kerja pendek, mungkin perlu evaluasi
- 📈 **Trend naik**: Produktivitas meningkat
- 📉 **Trend turun**: Produktivitas menurun
- ⏰ **Standar normal**: Biasanya 6-8 jam per hari

### 📝 Contoh Interpretasi:
```
Hari 1: 7.2 jam
Hari 2: 7.5 jam
Hari 3: 6.8 jam
Hari 4: 7.0 jam
Hari 5: 7.3 jam
Hari 6: 7.1 jam
Hari 7: 7.4 jam

Interpretasi:
- Rata-rata jam kerja stabil di sekitar 7 jam
- Tidak ada fluktuasi drastis (baik)
- Hari 3 sedikit lebih rendah (6.8 jam) tapi masih wajar
- Secara keseluruhan produktivitas baik
```

---

## 5. **Top 5 Karyawan Paling Aktif (Bulan Ini) - Horizontal Bar Chart**

### 📊 Apa yang Ditampilkan?
Chart ini menampilkan **5 karyawan dengan log aktivitas terbanyak** pada bulan berjalan, dalam bentuk bar horizontal.

### 🎨 Komponen Chart:
- **Tipe**: Horizontal Bar Chart (Bar Chart mendatar)
- **Sumbu X (Horizontal)**: Jumlah Log Aktivitas
- **Sumbu Y (Vertikal)**: Nama Karyawan
- **Bar**: Setiap bar mewakili satu karyawan

### 📖 Cara Membaca:
1. **Baca label di kiri (sumbu Y)**:
   - Menampilkan nama karyawan
   - Urutan dari atas ke bawah: dari yang paling aktif ke yang kurang aktif

2. **Baca panjang bar**:
   - Bar lebih panjang = lebih banyak log aktivitas
   - Bar lebih pendek = lebih sedikit log aktivitas

3. **Baca angka di ujung bar**:
   - Menampilkan jumlah log yang tepat

4. **Urutan**:
   - Bar terpanjang (paling atas) = karyawan paling aktif
   - Bar terpendek (paling bawah) = karyawan kurang aktif (tapi masih top 5)

### 💡 Insight yang Bisa Didapat:
- 🏆 **Karyawan teratas**: Performer terbaik bulan ini
- 📊 **Perbandingan performa**: Melihat gap antara karyawan
- ✅ **Kesetaraan**: Jika bar hampir sama panjang = performa merata
- ⚠️ **Ketimpangan**: Jika satu bar sangat panjang = ada superstar, perlu distribusi tugas

### 📝 Contoh Interpretasi:
```
Budi Santoso: 45 log (bar terpanjang)
Siti Nurhaliza: 42 log
Ahmad Dahlan: 38 log
Dewi Sartika: 35 log
Raden Ajeng: 32 log (bar terpendek)

Interpretasi:
- Budi Santoso adalah karyawan paling aktif bulan ini
- Perbedaan antara top 1 dan top 5 tidak terlalu besar (baik)
- Semua karyawan dalam top 5 memiliki performa yang baik
```

---

## 6. **Log Aktivitas per Status (7 Hari Terakhir) - Stacked Bar Chart**

### 📊 Apa yang Ditampilkan?
Chart ini menampilkan **perbandingan status log aktivitas per hari** selama 7 hari terakhir dalam bentuk stacked bar (bar bertumpuk).

### 🎨 Komponen Chart:
- **Tipe**: Stacked Bar Chart (Bar Chart bertumpuk)
- **Sumbu X (Horizontal)**: Tanggal (format: dd/mm)
- **Sumbu Y (Vertikal)**: Jumlah Log Aktivitas
- **Stack (Tumpukan)**:
  - 🟡 **Kuning (bawah)**: Menunggu
  - 🟢 **Hijau (tengah)**: Tervalidasi
  - 🔴 **Merah (atas)**: Ditolak

### 📖 Cara Membaca:
1. **Baca sumbu X (bawah)**:
   - Menampilkan tanggal 7 hari terakhir

2. **Baca tinggi total bar**:
   - Tinggi total = total log aktivitas pada hari tersebut
   - Bar lebih tinggi = lebih banyak total log

3. **Baca setiap layer dalam stack**:
   - **Layer kuning (bawah)**: Jumlah log yang menunggu validasi
   - **Layer hijau (tengah)**: Jumlah log yang sudah tervalidasi
   - **Layer merah (atas)**: Jumlah log yang ditolak

4. **Bandingkan antar hari**:
   - Lihat perubahan proporsi setiap status dari hari ke hari
   - Lihat apakah ada hari dengan banyak log menunggu atau ditolak

5. **Lihat legend**:
   - Menampilkan warna dan label untuk setiap status

### 💡 Insight yang Bisa Didapat:
- ✅ **Hijau dominan**: Banyak log yang tervalidasi (baik)
- ⚠️ **Kuning dominan**: Banyak log yang masih menunggu (perlu validasi segera)
- ❌ **Merah muncul**: Ada log yang ditolak (perlu evaluasi)
- 📊 **Proporsi stabil**: Jika proporsi setiap hari sama = proses validasi konsisten
- ⚠️ **Kuning menumpuk**: Jika kuning semakin banyak = backlog validasi meningkat

### 📝 Contoh Interpretasi:
```
Hari 1: Total 50 log
  - Menunggu: 10 (kuning)
  - Tervalidasi: 35 (hijau)
  - Ditolak: 5 (merah)

Hari 2: Total 55 log
  - Menunggu: 15 (kuning) ← meningkat
  - Tervalidasi: 38 (hijau)
  - Ditolak: 2 (merah)

Interpretasi:
- Hari 2 lebih produktif (55 vs 50 log)
- Tapi log menunggu meningkat (15 vs 10) ← perlu perhatian
- Log tervalidasi juga meningkat (baik)
- Log ditolak menurun (baik)
```

---

## 7. **Distribusi Aktivitas per Jam (8:00 - 17:00) - Bar Chart**

### 📊 Apa yang Ditampilkan?
Chart ini menampilkan **distribusi waktu mulai aktivitas** dalam rentang jam kerja (8:00 - 17:00) selama 7 hari terakhir.

### 🎨 Komponen Chart:
- **Tipe**: Bar Chart Vertikal
- **Sumbu X (Horizontal)**: Jam (format: 8:00, 9:00, 10:00, dst.)
- **Sumbu Y (Vertikal)**: Jumlah Aktivitas
- **Bar**: Setiap bar mewakili satu jam

### 📖 Cara Membaca:
1. **Baca label di bawah (sumbu X)**:
   - Menampilkan jam dari 8:00 sampai 17:00
   - Setiap jam mewakili waktu mulai aktivitas

2. **Baca tinggi setiap bar**:
   - Bar lebih tinggi = lebih banyak aktivitas dimulai pada jam tersebut
   - Bar lebih pendek = lebih sedikit aktivitas dimulai pada jam tersebut

3. **Baca angka di atas bar**:
   - Menampilkan jumlah aktivitas yang tepat pada jam tersebut

4. **Identifikasi pola**:
   - **Pagi (8-10)**: Aktivitas awal hari
   - **Siang (11-13)**: Aktivitas tengah hari
   - **Sore (14-17)**: Aktivitas akhir hari

### 💡 Insight yang Bisa Didapat:
- 🌅 **Puncak pagi**: Jika bar tinggi di 8-9, karyawan mulai kerja tepat waktu
- 📊 **Distribusi merata**: Jika bar hampir sama tinggi = aktivitas tersebar sepanjang hari
- ⚠️ **Kosong di pagi**: Jika bar rendah di 8-9 = mungkin terlambat mulai kerja
- 📈 **Puncak siang**: Jika bar tinggi di 11-13 = jam sibuk
- 📉 **Turun sore**: Jika bar turun drastis di 15-17 = aktivitas menurun menjelang akhir hari

### 📝 Contoh Interpretasi:
```
8:00: 25 aktivitas
9:00: 30 aktivitas (puncak pagi)
10:00: 28 aktivitas
11:00: 35 aktivitas
12:00: 40 aktivitas (puncak siang)
13:00: 32 aktivitas
14:00: 30 aktivitas
15:00: 25 aktivitas
16:00: 20 aktivitas
17:00: 15 aktivitas (terendah)

Interpretasi:
- Aktivitas dimulai dengan baik di pagi hari (8-9)
- Puncak aktivitas di siang hari (12:00)
- Aktivitas menurun menjelang akhir hari (normal)
- Pola kerja sehat dan terdistribusi dengan baik
```

---

## 🎯 Tips Membaca Chart Secara Keseluruhan

### 1. **Baca dari Kiri ke Kanan, Atas ke Bawah**
   - Mulai dari chart yang memberikan overview (Status Log)
   - Lanjut ke trend (Trend Log)
   - Detail per kategori (Departemen, Karyawan)
   - Detail waktu (Jam Kerja, Jam Aktivitas)

### 2. **Bandingkan dengan Periode Sebelumnya**
   - Jika memungkinkan, bandingkan dengan data bulan lalu
   - Lihat apakah ada peningkatan atau penurunan

### 3. **Cari Pola dan Anomali**
   - **Pola normal**: Fluktuasi kecil, trend stabil
   - **Anomali**: Lonjakan atau penurunan drastis (perlu investigasi)

### 4. **Gunakan Multiple Chart untuk Validasi**
   - Jika satu chart menunjukkan masalah, cek chart lain untuk konfirmasi
   - Contoh: Jika trend turun, cek apakah ada masalah di departemen tertentu

### 5. **Fokus pada Actionable Insights**
   - Identifikasi masalah yang bisa ditindaklanjuti
   - Contoh: Banyak log menunggu → perlu validasi segera

---

## 📌 Kesimpulan

Setiap chart memberikan perspektif berbeda tentang aktivitas karyawan:
- **Status Chart**: Overview status validasi
- **Trend Chart**: Pola aktivitas dari waktu ke waktu
- **Departemen Chart**: Perbandingan antar departemen
- **Jam Kerja Chart**: Durasi produktivitas
- **Top Karyawan Chart**: Performer terbaik
- **Status per Hari Chart**: Detail validasi harian
- **Jam Aktivitas Chart**: Pola waktu kerja

Dengan membaca semua chart secara bersamaan, Anda akan mendapatkan gambaran lengkap tentang aktivitas dan produktivitas karyawan dalam sistem E-Log Karyawan.

