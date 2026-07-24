# Inventory Warehouse Management System

<p align="center">
  <img src="public/images/logo.png" width="150">
</p>

<p align="center">
Sistem Informasi Inventory Warehouse Berbasis Web menggunakan Laravel dengan metode <b>Simple Moving Average (SMA)</b> untuk melakukan forecasting kebutuhan persediaan.
</p>

---

## 📖 Tentang Aplikasi

Inventory Warehouse Management System merupakan aplikasi berbasis web yang dirancang untuk membantu proses pengelolaan persediaan barang pada gudang secara terintegrasi. Sistem ini mendukung pencatatan transaksi **Delivery In** dan **Delivery Out**, monitoring stok secara real-time, serta melakukan **forecasting** kebutuhan persediaan menggunakan metode **Simple Moving Average (SMA)**.

Aplikasi ini dikembangkan sebagai bagian dari penelitian tugas akhir pada PT Giken Precision Indonesia.

---

# ✨ Fitur Utama

## Authentication

- Login
- Logout
- Role Based Authentication
- Session Management

---

## Dashboard

- Total Barang
- Total Delivery In
- Total Delivery Out
- Total Stok
- Grafik Inventory
- Forecast Summary

---

## Master Data

- Data Barang
- Data Customer
- Data User
- Data Role

---

## Delivery In

- Tambah Barang Masuk
- Edit Barang Masuk
- Hapus Barang Masuk
- Riwayat Barang Masuk
- Update Stock Otomatis

---

## Delivery Out

- Tambah Barang Keluar
- Edit Barang Keluar
- Hapus Barang Keluar
- Riwayat Barang Keluar
- Update Stock Otomatis

---

## Stock Management

- Monitoring Stock
- Stock Real-Time
- Pencarian Barang
- Filter Data

---

## Forecast Inventory

Menggunakan metode

Simple Moving Average (SMA)

Fitur:

- Forecast berdasarkan histori transaksi
- Window SMA = 3 Periode
- Hasil Forecast
- Grafik Forecast
- Evaluasi Forecast

---

## Evaluasi Forecast

Sistem menghitung secara otomatis:

- Mean Absolute Error (MAE)
- Mean Squared Error (MSE)
- Mean Absolute Percentage Error (MAPE)

---

## Security

Laravel Authentication

Middleware Authorization

CSRF Protection

Form Validation

Password Hashing (bcrypt)

Route Protection

Input Validation

---

# 🛠️ Built With

Backend

- Laravel 11
- PHP 8.2+

Frontend

- Bootstrap 5
- HTML5
- CSS3
- JavaScript
- jQuery
- DataTables

Database

- MySQL

Development Tools

- Composer
- XAMPP
- Visual Studio Code

---

# 📂 Folder Structure

```
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
vendor/
```

---

# 💻 System Requirements

PHP >= 8.2

Composer

MySQL

Node.js

NPM

Git

XAMPP / Laragon

---

# 🚀 Installation

## 1 Clone Repository

```bash
git clone https://github.com/username/inventory-warehouse.git
```

atau

```bash
git clone git@github.com:username/inventory-warehouse.git
```

Masuk ke folder project

```bash
cd inventory-warehouse
```

---

## 2 Install Dependency

```bash
composer install
```

---

## 3 Install Frontend

```bash
npm install
```

---

## 4 Copy Environment

Windows

```bash
copy .env.example .env
```

Linux

```bash
cp .env.example .env
```

---

## 5 Generate Key

```bash
php artisan key:generate
```

---

## 6 Konfigurasi Database

Buat database

```
inventory_warehouse
```

Edit file

```
.env
```

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_warehouse
DB_USERNAME=root
DB_PASSWORD=
```

---

## 7 Jalankan Migration

```bash
php artisan migrate
```

Jika menggunakan Seeder

```bash
php artisan db:seed
```

atau

```bash
php artisan migrate --seed
```

---

## 8 Build Asset

```bash
npm run dev
```

atau

```bash
npm run build
```

---

## 9 Jalankan Server

```bash
php artisan serve
```

Buka browser

```
http://127.0.0.1:8000
```

---

# 🔑 Default Login

Administrator

```
Email :
admin@example.com

Password :
password
```

*(Sesuaikan dengan akun yang dibuat melalui seeder atau database Anda.)*

---

# 📊 Forecast Method

Aplikasi menggunakan metode

Simple Moving Average (SMA)

Rumus

```
Ft = (Xt + Xt-1 + Xt-2) / n
```

Window yang digunakan

```
3 Periode
```

Evaluasi Forecast

- MAE
- MSE
- MAPE

---

# 📸 Screenshot

Dashboard

```
docs/dashboard.png
```

Inventory

```
docs/inventory.png
```

Delivery In

```
docs/delivery-in.png
```

Delivery Out

```
docs/delivery-out.png
```

Forecast

```
docs/forecast.png
```

---

# 🔒 Security

Laravel Authentication

CSRF Token

Middleware

Validation Request

Password Hashing

Role Permission

Protected Route

---

# 👨‍💻 Author

**Nama**

Tugas Akhir

Universitas XXXXX

Program Studi XXXXX

GitHub

https://github.com/username

---

# 📄 License

MIT License

```
Copyright (c) 2026

Permission is hereby granted...
```

---

# 🤝 Contributing

1. Fork Repository

2. Create Branch

```bash
git checkout -b feature/new-feature
```

3. Commit

```bash
git commit -m "Add new feature"
```

4. Push

```bash
git push origin feature/new-feature
```

5. Create Pull Request

---

# 📧 Contact

Email

example@email.com

GitHub

https://github.com/username
