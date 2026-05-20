# Proyek Klinplus - AI Context & Guidelines

Dokumen ini berfungsi sebagai panduan konteks bagi Gemini AI/LLM untuk memahami arsitektur, teknologi, dan aturan penulisan kode dalam pengembangan sistem informasi Klinplus

---

## 1. System Overview

- **Nama Proyek:** Klinplus
- **Deskripsi:** Sistem Informasi Penjadwalan Layanan dan Form Pemesanan (Booking Form) berbasis web untuk penyedia jasa kebersihan/layanan terkait.
- **Tujuan Utama:** Mengotomatisasi proses bisnis tradisional (pemesanan manual) menjadi platform digital modern yang menangani penjadwalan, pengelolaan pelanggan, kalkulasi harga (termasuk diskon), dan pencatatan pembayaran hingga penerbitan Invoice PDF melalui Email.

---

## 2. Tech Stack & Environment

Sistem ini dibangun menggunakan ekosistem teknologi berikut:

- **Backend Framework:** Laravel (PHP).
- **Frontend Interactivity:** Blade Templating Engine, JavaScript/jQuery, Tailwind CSS, Lucide Icons.
- **Database:** MySQL.
- **Fitur Tambahan:** AJAX untuk pemuatan data dinamis, integrasi PDF generator untuk Invoice, Resend API untuk kirim Invoice otomatis.

---

## 3. Struktur Database & Hubungan Model Utama

Pahami relasi antar entitas berikut saat memodifikasi kode atau query:

- **Pelanggan (Customers):** Menyimpan data data diri pelanggan (`id_pelanggan`, `nama_pelanggan`, dll).
- **Orders:** Entitas utama pemesanan.
  - Relasi: `belongsTo` ke Pelanggan.
  - Relasi: `hasMany` ke OrderDetail.
  - Field Kunci: `id_order`, `tanggal_pengerjaan`, `alamat_lokasi`, `diskon`, `metode_pembayaran`, `tipe_pembayaran`.
- **OrderDetail:** Menghubungkan order dengan layanan spesifik yang dipilih.
  - Relasi: `belongsTo` ke LayananSubkategori.
  - Field Kunci: `harga`.
- **LayananSubkategori & RootKategori:** Tingkatan kategori layanan yang ditawarkan (misal: _Cuci Kasur - Basic (4 Sisi) - Bed 90x200_).
  - Relasi: `OrderDetail` -> `LayananSubkategori` -> `RootKategori`.

---

## 4. Coding Guidelines

### Backend (Laravel)

- Gunakan **Eloquent ORM** untuk query database. Selalu manfaatkan _Eager Loading_ (`with()`) untuk menghindari masalah query $N+1$, terutama saat memuat relasi di halaman indeks/tabel (contoh: `Order::with(['pelanggan', 'orderDetails.layananSubkategori'])`).
- Logika kalkulasi finansial (seperti total harga setelah diskon) diletakkan di Controller atau sebagai _Accessor_ di dalam Model, bukan murni di JavaScript client-side demi keamanan data.
- Gunakan _Route Name_ yang konsisten (contoh: `pembayaran.index`, `orders.show`).

### Frontend (Blade & JavaScript)

- Gunakan tata letak berbasis komponen `@extends('layouts.app')`, `@section('content')`, dan `@push('scripts')`.
- Terapkan format mata uang Rupiah secara konsisten pada komponen view (`Rp ` + format ribuan dengan titik).

---

## 5. Instruksi Respons untuk Gemini CLI

- **Bahasa:** Berikan penjelasan teknis dan baris kode dalam bahasa yang bersih, tapi gunakan gaya bahasa santai/casual jika memberikan tips tambahan.
- **Format Kode:** Selalu berikan blok kode Laravel/JS yang siap pakai (_production-ready_) lengkap dengan komentar penjelas pada bagian logika yang krusial.
- **Efisiensi:** Prioritaskan scannability, gunakan tabel atau bullet points untuk membandingkan opsi solusi teknis.
