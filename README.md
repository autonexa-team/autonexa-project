<p align="center">
  <img src="public/assets/logo.png" width="120">
</p>

<h1 align="center">Autonexa</h1>
# 🚀 Autonexa — Sistem Manajemen Bengkel Motor

Autonexa adalah platform berbasis web untuk mempermudah manajemen bengkel motor dan reservasi service secara online.
Aplikasi ini dirancang untuk mendukung multi-cabang dengan kontrol terpusat serta pengalaman pengguna yang modern.

---

## ✨ Fitur Utama

### 👨‍💼 Admin Pusat

* Manajemen bengkel (multi cabang)
* Manajemen user (admin cabang)
* Manajemen layanan & sparepart
* Monitoring reservasi & laporan

### 🏪 Admin Cabang

* Mengelola reservasi pelanggan
* Update status service (pending → selesai)
* Input hasil service (layanan & sparepart)
* Mengelola stok sparepart

### 👤 Pelanggan

* Mencari bengkel terdekat (berbasis lokasi)
* Melakukan reservasi service
* Melihat estimasi harga & durasi
* Melihat riwayat service

---

## 🗺️ Fitur Unggulan

* 📍 **Pencarian Bengkel Terdekat (Maps)**
* ⏱️ **Estimasi Durasi Service**
* 💰 **Estimasi Biaya Service**
* 📅 **Reservasi dengan Slot Terbatas**
* 🔄 **Update Status Service Real-time**

---

## 🧱 Tech Stack

* **Backend**: Laravel
* **Frontend**: Blade, CSS, JavaScript
* **Database**: MySQL / SQLite
* **Maps**: Leaflet.js / OpenStreetMap
* **Version Control**: Git & GitHub

---

## 🧩 Struktur Role

| Role         | Akses                    |
| ------------ | ------------------------ |
| Admin Pusat  | Mengelola seluruh sistem |
| Admin Cabang | Operasional bengkel      |
| Pelanggan    | Reservasi & informasi    |

---

## ⚙️ Instalasi

1. Clone repository

```bash
git clone https://github.com/autonexa-team/autonexa-project.git
cd autonexa-project
```

2. Install dependency

```bash
composer install
npm install
```

3. Copy file environment

```bash
cp .env.example .env
```

4. Generate key

```bash
php artisan key:generate
```

5. Setup database

```bash
php artisan migrate
```

6. Jalankan server

```bash
php artisan serve
```

---

## 📂 Struktur Project (Singkat)

```
app/
├── Models
├── Http/Controllers
resources/
├── views
public/
routes/
```

---

## 👥 Tim Pengembang

Autonexa Team

---

## 📄 Lisensi

Project ini dibuat untuk keperluan pengembangan dan pembelajaran.
