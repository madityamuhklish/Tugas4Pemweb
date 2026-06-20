# Portofolio Mahasiswa TI UIR — CRUD Project

Portofolio pribadi dengan fitur **CRUD (Create, Read, Update, Delete)** untuk mengelola Daftar Project/Karya. Dibangun dengan **PHP + MySQL**.

## 🗂 Struktur File

```
portofolio-crud/
├── config/
│   └── koneksi.php      # koneksi ke database
├── uploads/              # tempat gambar project tersimpan
├── database.sql          # script untuk membuat database & tabel
├── index.php             # halaman utama (tampil semua project) - READ
├── tambah.php             # form tambah project - CREATE
├── edit.php               # form edit project - UPDATE
├── hapus.php               # proses hapus project - DELETE
├── style.css
├── todo.js
└── Fotoo.jpeg             # taruh foto profilmu di sini
```

## ⚙️ Cara Menjalankan (Local, pakai XAMPP/Laragon)

### 1. Install XAMPP atau Laragon
Download di [apachefriends.org](https://www.apachefriends.org/) (XAMPP) atau [laragon.org](https://laragon.org/) (Laragon).

### 2. Taruh folder project
Copy folder `portofolio-crud` ke:
- XAMPP: `C:\xampp\htdocs\portofolio-crud`
- Laragon: `C:\laragon\www\portofolio-crud`

### 3. Jalankan Apache & MySQL
Buka XAMPP/Laragon Control Panel → Start **Apache** dan **MySQL**.

### 4. Buat Database
1. Buka browser → `http://localhost/phpmyadmin`
2. Klik tab **SQL**
3. Copy-paste seluruh isi file `database.sql` → klik **Go**

Ini akan otomatis membuat database `portofolio_db` beserta tabel `projects`.

### 5. Cek Konfigurasi Koneksi
Buka `config/koneksi.php`, pastikan sesuai setting MySQL kamu (default XAMPP/Laragon biasanya sudah cocok: user `root`, password kosong).

### 6. Tambahkan Foto Profil
Taruh file foto kamu (apa saja namanya, asal **diawali kata "foto"**, contoh: `foto.jpg`, `foto-profil.png`) langsung di folder root project (sejajar dengan `index.php`). Sistem akan otomatis mendeteksinya. Kalau belum ada foto, akan muncul avatar inisial sebagai gantinya.

### 7. Buka di Browser
```
http://localhost/portofolio-crud/index.php
```

## ✅ Fitur

| Aksi | File | Keterangan |
|------|------|------------|
| **C**reate | `tambah.php` | Tambah project baru — layout full lebar 2 kolom dengan preview gambar real-time |
| **R**ead | `index.php` | Menampilkan semua project dalam bentuk kartu (grid) |
| **U**pdate | `edit.php` | Edit data project — layout full lebar 2 kolom |
| **D**elete | `hapus.php` | Hapus project beserta gambarnya |
| **Pesan Masuk** | `pesan.php` | Lihat pesan dari form Contact, tandai dibaca, atau hapus |
| **Kirim Pesan** | `kirim_pesan.php` | Backend penerima submit form Contact |

## 🔒 Keamanan
- Semua query database menggunakan **Prepared Statement** (`bind_param`) untuk mencegah SQL Injection.
- Output ke HTML menggunakan `htmlspecialchars()` untuk mencegah XSS.

## 📤 Upload ke GitHub

```bash
cd portofolio-crud
git init
git add .
git commit -m "Portofolio CRUD - PHP MySQL"
git branch -M main
git remote add origin https://github.com/USERNAME/NAMA-REPO.git
git push -u origin main
```

> ⚠️ Jangan lupa buat file `.gitignore` agar folder `uploads/` (kecuali `.gitkeep`) dan file sensitif tidak ikut ter-upload jika berisi data pribadi.

## 📸 Untuk Screenshot Tugas
1. Jalankan project di local (`localhost/portofolio-crud`)
2. Screenshot: halaman utama dengan daftar project
3. Screenshot: proses tambah project (sebelum & sesudah submit)
4. Screenshot: proses edit project
5. Screenshot: proses hapus project (sebelum & sesudah)
6. Tempel semua ke Word → export ke PDF
