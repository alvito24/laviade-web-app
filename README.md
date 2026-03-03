# 🛍️ LAVIADE - Fashion Streetwear E-Commerce Platform

<p align="center">
  <b>Platform e-commerce fashion streetwear modern dengan desain visual yang bersih dan pengalaman belanja yang efisien.</b>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Livewire-3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/TailwindCSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/MySQL-8-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
</p>

---

## 📖 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Tech Stack](#-tech-stack)
- [Arsitektur Sistem](#-arsitektur-sistem)
- [Struktur Folder Project](#-struktur-folder-project)
- [Penjelasan Backend](#-penjelasan-backend)
- [Penjelasan Frontend](#-penjelasan-frontend)
- [REST API (untuk Mobile)](#-rest-api-untuk-mobile)
- [Database & Migrasi](#-database--migrasi)
- [Autentikasi](#-autentikasi)
- [Panduan Setup (Getting Started)](#-panduan-setup-getting-started)
- [Perintah-Perintah Penting](#-perintah-perintah-penting)
- [Alur Pengembangan (Development Workflow)](#-alur-pengembangan-development-workflow)
- [Testing](#-testing)
- [CI/CD (GitHub Actions)](#-cicd-github-actions)
- [Troubleshooting](#-troubleshooting)
- [Kontributor](#-kontributor)

---

## 🏪 Tentang Project

**LAVIADE** adalah platform e-commerce fashion streetwear yang dibangun menggunakan arsitektur **Laravel Fullstack** (Blade + Livewire) sekaligus menyediakan **REST API** (via Laravel Sanctum) untuk integrasi dengan aplikasi mobile (Flutter).

### Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 🏠 **Homepage** | Landing page dengan hero banner, campaign slider, produk unggulan |
| 🛒 **Shop & Katalog** | Browse produk, filter berdasarkan kategori, pencarian produk |
| 📦 **Detail Produk** | Gambar produk multiple, detail harga, ukuran, stok |
| 🛍️ **Keranjang Belanja** | Tambah/hapus item, toggle pilih item, ubah jumlah & ukuran |
| 💳 **Checkout & Order** | Proses pembayaran, pemilihan alamat, konfirmasi pesanan |
| ❤️ **Wishlist** | Simpan produk favorit |
| 👤 **Profil Pengguna** | Edit profil, kelola alamat, riwayat pesanan |
| ⭐ **Review** | Berikan ulasan & rating pada produk yang dibeli |
| 📊 **Admin Dashboard** | Statistik penjualan, manajemen data |
| 📦 **Admin Produk** | CRUD produk, kelola gambar, kategori |
| 📋 **Admin Pesanan** | Kelola status pesanan, tracking pengiriman |
| 🎯 **Admin Campaign** | Kelola banner promosi dan campaign |
| 👥 **Admin Users** | Kelola data pengguna |

### Aktor Sistem

- **User (Customer)**: Melihat produk, belanja, checkout, tracking pesanan, menulis review
- **Admin (Store Manager)**: Mengelola produk, kategori, campaign, pesanan, dan pengguna

---

## ⚙️ Tech Stack

### Backend
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| **PHP** | 8.2+ | Bahasa pemrograman server-side |
| **Laravel** | 12.x | Framework PHP utama |
| **Laravel Sanctum** | 4.x | Autentikasi API (token-based) |
| **Laravel Fortify** | 1.x | Autentikasi backend (registrasi, login, 2FA) |
| **Livewire** | 3.x | Komponen interaktif server-side (settings, auth) |
| **Flux UI** | 2.x | Komponen UI untuk Livewire |
| **MySQL** | 8.x | Database relasional |

### Frontend
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| **Blade Templates** | - | Template engine Laravel untuk HTML |
| **TailwindCSS** | 4.x | Utility-first CSS framework |
| **Alpine.js** | 3.x | Micro JavaScript framework untuk interaktivitas |
| **Vite** | 7.x | Build tool & dev server untuk frontend assets |
| **Chart.js** | 4.x | Library chart/grafik untuk admin dashboard |
| **Inter Font** | - | Font utama aplikasi |

### Tools & DevOps
| Teknologi | Kegunaan |
|-----------|----------|
| **Composer** | PHP dependency manager |
| **NPM** | JavaScript dependency manager |
| **Laravel Pint** | Code formatter (PSR-12) |
| **Pest PHP** | Testing framework |
| **GitHub Actions** | CI/CD pipeline (lint + test) |
| **Laragon** | Local development environment |

---

## 🏗️ Arsitektur Sistem

### Diagram Arsitektur

```
┌─────────────────────────────────────────────────────┐
│                   Client Layer                       │
│                                                      │
│   ┌─────────────┐          ┌──────────────────┐     │
│   │  Browser     │          │  Mobile App       │    │
│   │  (Blade +    │          │  (Flutter)        │    │
│   │  TailwindCSS │          │                   │    │
│   │  + Alpine.js)│          │                   │    │
│   └──────┬───────┘          └────────┬──────────┘    │
│          │                           │               │
└──────────┼───────────────────────────┼───────────────┘
           │ HTTP (Web)                │ REST API
           ▼                           ▼
┌──────────────────────────────────────────────────────┐
│                  Laravel Backend                      │
│                                                       │
│  ┌─────────────────┐    ┌──────────────────────┐     │
│  │  Web Controllers │    │  API Controllers      │    │
│  │  (Blade views)   │    │  (JSON responses)     │    │
│  └────────┬─────────┘    └──────────┬────────────┘   │
│           │                         │                 │
│           ▼                         ▼                 │
│  ┌──────────────────────────────────────────────┐    │
│  │           Service Layer (Business Logic)      │    │
│  │  ProductService, CartService, OrderService,   │    │
│  │  WishlistService, CampaignService             │    │
│  └──────────────────────┬────────────────────────┘   │
│                         │                             │
│                         ▼                             │
│  ┌──────────────────────────────────────────────┐    │
│  │         Eloquent Models (16 Models)            │    │
│  └──────────────────────┬────────────────────────┘   │
│                         │                             │
└─────────────────────────┼─────────────────────────────┘
                          │
                          ▼
               ┌────────────────────┐
               │     MySQL Database  │
               │     (22 Tabel)      │
               └────────────────────┘
```

### Design Pattern

Proyek ini menerapkan **Service Layer Pattern**:

```
Request → Controller → Service → Model → Database
                ↓
           Response (Blade View / JSON)
```

| Layer | Tanggung Jawab |
|-------|----------------|
| **Controller** | Menerima request, validasi input, memanggil service, return response |
| **Service** | Seluruh business logic (kalkulasi harga, proses order, dll) |
| **Model** | Definisi tabel, relasi antar tabel, accessor/mutator |
| **Middleware** | Autentikasi & otorisasi (cek login, cek role admin) |
| **Form Request** | Validasi input request |

---

## 📁 Struktur Folder Project

```
laviade-websie-app/
│
├── app/                          # Kode PHP utama (Backend)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/V1/User/      # Controller API v1 untuk User (9 file)
│   │   │   │   ├── AuthController.php      # Register, Login, Logout
│   │   │   │   ├── ProductController.php   # List, Detail, Search produk
│   │   │   │   ├── CartController.php      # CRUD Keranjang
│   │   │   │   ├── OrderController.php     # Checkout, Riwayat pesanan
│   │   │   │   ├── ReviewController.php    # Buat review
│   │   │   │   ├── ProfileController.php   # Profil & Alamat
│   │   │   │   ├── CategoryController.php  # List kategori
│   │   │   │   ├── WishlistController.php  # Toggle wishlist
│   │   │   │   └── BannerController.php    # Banner campaign
│   │   │   │
│   │   │   ├── Web/User/         # Controller Web untuk User (6 file)
│   │   │   │   ├── HomeController.php      # Home, About, Contact
│   │   │   │   ├── ShopController.php      # Katalog, Pencarian
│   │   │   │   ├── CartController.php      # Halaman keranjang
│   │   │   │   ├── CheckoutController.php  # Proses checkout
│   │   │   │   ├── ProfileController.php   # Halaman profil
│   │   │   │   └── WishlistController.php  # Toggle wishlist
│   │   │   │
│   │   │   └── Web/Admin/        # Controller Web untuk Admin (7 file)
│   │   │       ├── AuthController.php      # Login/Logout admin
│   │   │       ├── DashboardController.php # Dashboard admin
│   │   │       ├── ProductController.php   # CRUD produk
│   │   │       ├── CategoryController.php  # CRUD kategori
│   │   │       ├── OrderController.php     # Kelola pesanan
│   │   │       ├── CampaignController.php  # CRUD campaign
│   │   │       └── UserController.php      # Kelola user
│   │   │
│   │   ├── Middleware/           # Middleware kustom
│   │   │   ├── AdminAuthenticate.php    # Cek autentikasi admin
│   │   │   └── RedirectIfAdmin.php      # Redirect jika sudah login admin
│   │   │
│   │   └── Requests/            # Form request validation
│   │
│   ├── Models/                   # Eloquent Models (16 file)
│   │   ├── User.php             # Model pengguna
│   │   ├── Admin.php            # Model admin
│   │   ├── Product.php          # Model produk
│   │   ├── ProductImage.php     # Gambar produk
│   │   ├── Category.php         # Kategori produk
│   │   ├── Cart.php             # Keranjang belanja
│   │   ├── CartItem.php         # Item di keranjang
│   │   ├── Order.php            # Pesanan
│   │   ├── OrderItem.php        # Item pesanan
│   │   ├── Payment.php          # Pembayaran
│   │   ├── Shipment.php         # Pengiriman
│   │   ├── Address.php          # Alamat pengiriman
│   │   ├── Wishlist.php         # Wishlist
│   │   ├── Review.php           # Ulasan produk
│   │   ├── Campaign.php         # Kampanye promosi
│   │   └── CampaignBanner.php   # Banner kampanye
│   │
│   ├── Services/                # Business Logic Layer (5 file)
│   │   ├── ProductService.php   # Logic produk (filter, search, detail)
│   │   ├── CartService.php      # Logic keranjang (tambah, update, hapus)
│   │   ├── OrderService.php     # Logic pesanan (checkout, status, cancel)
│   │   ├── WishlistService.php  # Logic wishlist (toggle, cek)
│   │   └── CampaignService.php  # Logic campaign (list aktif)
│   │
│   ├── Livewire/                # Livewire Components (7 file)
│   │   ├── Actions/Logout.php   # Aksi logout
│   │   └── Settings/            # Pengaturan akun
│   │       ├── Profile.php      # Edit profil
│   │       ├── Password.php     # Ubah password
│   │       ├── Appearance.php   # Pengaturan tampilan
│   │       ├── DeleteUserForm.php # Hapus akun
│   │       └── TwoFactor/       # Two-Factor Authentication
│   │
│   └── Providers/               # Service providers
│
├── config/                       # Konfigurasi Laravel
│   ├── auth.php                 # Konfigurasi autentikasi (User + Admin guard)
│   ├── app.php                  # Konfigurasi aplikasi
│   ├── database.php             # Konfigurasi database
│   ├── fortify.php              # Konfigurasi Laravel Fortify
│   ├── sanctum.php              # Konfigurasi Sanctum (otomatis via package)
│   └── ...                      # Konfigurasi lainnya
│
├── database/
│   ├── migrations/              # File migrasi database (22 file)
│   ├── seeders/                 # Database seeder
│   │   └── DatabaseSeeder.php
│   └── factories/               # Model factories
│
├── resources/                    # Asset Frontend
│   ├── css/
│   │   └── app.css              # TailwindCSS entry point + konfigurasi tema
│   ├── js/
│   │   └── app.js               # JavaScript entry point (Alpine.js)
│   └── views/                   # Blade Templates (70+ file)
│       ├── user/                # Halaman User (14 views)
│       │   ├── home.blade.php           # Homepage
│       │   ├── about.blade.php          # Halaman About
│       │   ├── contact.blade.php        # Halaman Contact
│       │   ├── shop/
│       │   │   ├── index.blade.php      # Katalog produk
│       │   │   └── show.blade.php       # Detail produk
│       │   ├── cart/
│       │   │   └── index.blade.php      # Halaman keranjang
│       │   ├── checkout/
│       │   │   ├── index.blade.php      # Halaman checkout
│       │   │   └── success.blade.php    # Halaman sukses checkout
│       │   └── profile/
│       │       ├── index.blade.php      # Profil user
│       │       ├── orders.blade.php     # Riwayat pesanan
│       │       ├── order-detail.blade.php # Detail pesanan
│       │       ├── wishlist.blade.php   # Daftar wishlist
│       │       └── addresses.blade.php  # Kelola alamat
│       │
│       ├── admin/               # Halaman Admin (14 views)
│       │   ├── auth/login.blade.php     # Login admin
│       │   ├── dashboard.blade.php      # Dashboard
│       │   ├── products/                # CRUD Produk
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── categories/
│       │   │   └── index.blade.php
│       │   ├── orders/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── campaigns/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   └── users/
│       │       ├── index.blade.php
│       │       ├── show.blade.php
│       │       └── edit.blade.php
│       │
│       ├── components/          # Komponen Blade reusable
│       │   ├── layouts/
│       │   │   ├── admin.blade.php     # Layout admin panel
│       │   │   ├── app.blade.php       # Layout aplikasi user
│       │   │   └── auth.blade.php      # Layout halaman auth
│       │   ├── product-card.blade.php  # Komponen kartu produk
│       │   └── ...                     # Komponen lainnya
│       │
│       └── layouts/             # Layout utama
│           ├── app.blade.php           # Layout user
│           ├── admin.blade.php         # Layout admin
│           └── partials/
│               ├── navbar.blade.php    # Navigasi user
│               └── footer.blade.php    # Footer
│
├── routes/                       # Definisi Route
│   ├── web.php                  # Route halaman web (User + Admin)
│   ├── api.php                  # Route REST API v1
│   └── console.php              # Route Artisan console
│
├── public/                       # File publik (akses langsung via URL)
├── storage/                      # File storage (upload, log, cache)
├── tests/                        # Testing (Pest PHP)
│   ├── Feature/                 # Feature tests (11 file)
│   └── Unit/                    # Unit tests
│
├── .github/workflows/           # CI/CD GitHub Actions
│   ├── lint.yml                 # Auto lint check
│   └── tests.yml                # Auto test runner
│
├── composer.json                # PHP dependencies
├── package.json                 # NPM dependencies
├── vite.config.js               # Vite configuration
├── .env.example                 # Template environment variables
└── phpunit.xml                  # PHPUnit/Pest configuration
```

---

## 🔧 Penjelasan Backend

### Controllers

Controllers dibagi menjadi 3 grup besar:

#### 1. Web User Controllers (`app/Http/Controllers/Web/User/`)
Menangani request dari browser untuk halaman user (customer):

| Controller | Fungsi |
|-----------|---------|
| `HomeController` | Homepage, About Us, Contact |
| `ShopController` | Katalog produk, pencarian, filter kategori, detail produk |
| `CartController` | CRUD keranjang, toggle pilih item, select all |
| `CheckoutController` | Proses checkout, halaman sukses |
| `ProfileController` | Edit profil, riwayat pesanan, kelola alamat, wishlist |
| `WishlistController` | Toggle wishlist, hapus dari wishlist |

#### 2. Web Admin Controllers (`app/Http/Controllers/Web/Admin/`)
Menangani request dari browser untuk panel admin:

| Controller | Fungsi |
|-----------|---------|
| `AuthController` | Login/Logout admin (terpisah dari user) |
| `DashboardController` | Dashboard statistik |
| `ProductController` | CRUD produk lengkap (+ kelola gambar) |
| `CategoryController` | CRUD kategori produk |
| `OrderController` | Lihat & kelola status pesanan, update tracking |
| `CampaignController` | CRUD kampanye promosi & banner |
| `UserController` | Kelola data pengguna |

#### 3. API Controllers (`app/Http/Controllers/Api/V1/User/`)
Menangani request REST API untuk aplikasi mobile (Flutter):

| Controller | Endpoint Prefix | Fungsi |
|-----------|-----------------|---------|
| `AuthController` | `/api/v1` | Register, Login, Logout, Get User |
| `ProductController` | `/api/v1/products` | List, Search, Detail, New Arrivals, Best Sellers |
| `CartController` | `/api/v1/cart` | CRUD keranjang |
| `OrderController` | `/api/v1/orders` | Checkout, Riwayat, Detail, Cancel |
| `CategoryController` | `/api/v1/categories` | List kategori |
| `WishlistController` | `/api/v1/wishlist` | Toggle, List, Check |
| `ReviewController` | `/api/v1/reviews` | Buat review |
| `ProfileController` | `/api/v1/profile` | Profil & Alamat |
| `BannerController` | `/api/v1/banners` | Banner campaign untuk home slider |

### Services (`app/Services/`)

Business logic dipisahkan ke dalam Service Layer agar controller tetap ringan:

| Service | Tanggung Jawab |
|---------|----------------|
| `ProductService` | Filter produk, pencarian, detail produk, produk terkait |
| `CartService` | Tambah/update/hapus item keranjang, toggle seleksi |
| `OrderService` | Proses checkout, kalkulasi total, update status, cancel |
| `WishlistService` | Toggle wishlist, cek status wishlist |
| `CampaignService` | Ambil campaign aktif beserta banner |

### Models (`app/Models/`)

Semua 16 Eloquent Models beserta relasinya:

```
User ──┬── hasMany → Orders
       ├── hasMany → Addresses
       ├── hasOne  → Cart ──── hasMany → CartItems → belongsTo → Product
       ├── hasMany → Reviews
       └── hasMany → Wishlists → belongsTo → Product

Admin (terpisah dari User, beda tabel & guard)

Product ──┬── belongsTo → Category
          ├── hasMany   → ProductImages
          ├── hasMany   → Reviews
          └── hasMany   → OrderItems

Order ──┬── hasMany   → OrderItems → belongsTo → Product
        ├── hasOne    → Payment
        ├── hasOne    → Shipment
        └── belongsTo → User, Address

Campaign ── hasMany → CampaignBanners
Category ── hasMany → Products
```

### Middleware (`app/Http/Middleware/`)

| Middleware | Kegunaan |
|-----------|----------|
| `AdminAuthenticate` | Memastikan request berasal dari admin yang sudah login |
| `RedirectIfAdmin` | Redirect ke dashboard jika admin sudah login (untuk halaman login admin) |

---

## 🎨 Penjelasan Frontend

### Teknologi Frontend

- **Blade Templates**: Template engine bawaan Laravel untuk rendering HTML di server-side
- **TailwindCSS v4**: Framework CSS utility-first untuk styling
- **Alpine.js**: Framework JavaScript ringan untuk interaktivitas (dropdown, modal, toggle)
- **Vite**: Build tool modern untuk bundling CSS & JS
- **Chart.js**: Library grafik untuk dashboard admin
- **Flux UI**: Komponen UI premium untuk Livewire (form, button, modal)

### Layout System

Aplikasi menggunakan 3 layout utama:

| Layout | File | Kegunaan |
|--------|------|----------|
| **User Layout** | `layouts/app.blade.php` | Navbar + Footer untuk halaman user |
| **Admin Layout** | `components/layouts/admin.blade.php` | Sidebar + Header untuk panel admin |
| **Auth Layout** | `components/layouts/auth.blade.php` | Layout halaman login/register |

### Komponen Reusable

| Komponen | File | Kegunaan |
|----------|------|----------|
| Navbar | `layouts/partials/navbar.blade.php` | Navigasi atas untuk user |
| Footer | `layouts/partials/footer.blade.php` | Footer halaman |
| Product Card | `components/product-card.blade.php` | Kartu produk (digunakan di shop & home) |

### Halaman User (14 views)

| Halaman | URL | Deskripsi |
|---------|-----|-----------|
| Homepage | `/` | Landing page dengan banner, produk unggulan |
| About | `/about` | Informasi tentang LAVIADE |
| Contact | `/contact` | Halaman kontak |
| Shop | `/shop` | Katalog semua produk |
| Shop by Category | `/shop/category/{slug}` | Filter berdasarkan kategori |
| Product Detail | `/shop/{slug}` | Detail produk + gambar + review |
| Cart | `/cart` | Keranjang belanja |
| Checkout | `/checkout` | Form checkout |
| Checkout Success | `/checkout/success/{orderNumber}` | Konfirmasi pesanan berhasil |
| Profile | `/profile` | Edit profil user |
| Orders | `/profile/orders` | Riwayat pesanan |
| Order Detail | `/profile/orders/{orderNumber}` | Detail pesanan |
| Wishlist | `/profile/wishlist` | Daftar wishlist |
| Addresses | `/profile/addresses` | Kelola alamat pengiriman |

### Halaman Admin (14 views)

| Halaman | URL | Deskripsi |
|---------|-----|-----------|
| Login | `/admin/login` | Login admin |
| Dashboard | `/admin/dashboard` | Dashboard statistik |
| Products List | `/admin/products` | Daftar produk |
| Create Product | `/admin/products/create` | Tambah produk baru |
| Edit Product | `/admin/products/{id}/edit` | Edit produk |
| Categories | `/admin/categories` | Kelola kategori |
| Orders List | `/admin/orders` | Daftar pesanan |
| Order Detail | `/admin/orders/{id}` | Detail & kelola pesanan |
| Campaigns List | `/admin/campaigns` | Daftar campaign |
| Create Campaign | `/admin/campaigns/create` | Buat campaign baru |
| Edit Campaign | `/admin/campaigns/{id}/edit` | Edit campaign |
| Users List | `/admin/users` | Daftar pengguna |
| User Detail | `/admin/users/{id}` | Detail pengguna |
| Edit User | `/admin/users/{id}/edit` | Edit data pengguna |

### Kustomisasi Tema

Konfigurasi tema ada di `resources/css/app.css`:
- Font utama: **Inter** (via `@fontsource/inter`)
- Color palette: Zinc-based neutral colors
- Support **dark mode** menggunakan custom variant

---

## 🌐 REST API (untuk Mobile)

API menggunakan prefix `/api/v1` dan autentikasi via **Laravel Sanctum** (Bearer Token).

### Endpoint Publik (Tanpa Login)

```
POST   /api/v1/register              → Registrasi user baru
POST   /api/v1/login                 → Login & mendapat token

GET    /api/v1/products              → Daftar semua produk
GET    /api/v1/products/search       → Cari produk
GET    /api/v1/products/new-arrivals → Produk terbaru
GET    /api/v1/products/best-sellers → Produk terlaris
GET    /api/v1/products/{slug}       → Detail produk
GET    /api/v1/products/{slug}/related → Produk terkait

GET    /api/v1/categories            → Daftar kategori
GET    /api/v1/banners               → Banner campaign
```

### Endpoint Terproteksi (Perlu Login + Bearer Token)

```
POST   /api/v1/logout                → Logout (revoke token)
GET    /api/v1/user                  → Data user yang login

# Keranjang
GET    /api/v1/cart                  → Lihat keranjang
POST   /api/v1/cart                  → Tambah item ke keranjang
PUT    /api/v1/cart/{id}             → Update jumlah item
DELETE /api/v1/cart/{id}             → Hapus item
POST   /api/v1/cart/{id}/toggle      → Toggle pilih item

# Pesanan
GET    /api/v1/orders                → Riwayat pesanan
GET    /api/v1/orders/{orderNumber}  → Detail pesanan
POST   /api/v1/checkout              → Buat pesanan baru
POST   /api/v1/orders/{orderNumber}/cancel → Cancel pesanan

# Wishlist
GET    /api/v1/wishlist              → Daftar wishlist
POST   /api/v1/wishlist/toggle       → Toggle wishlist produk
GET    /api/v1/wishlist/{productId}/check → Cek status wishlist

# Review
POST   /api/v1/reviews               → Buat review produk

# Profil & Alamat
GET    /api/v1/profile               → Data profil
PUT    /api/v1/profile               → Update profil
GET    /api/v1/addresses             → Daftar alamat
POST   /api/v1/addresses             → Tambah alamat
PUT    /api/v1/addresses/{id}        → Edit alamat
DELETE /api/v1/addresses/{id}        → Hapus alamat
```

### Contoh Penggunaan API

```bash
# Register
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password","password_confirmation":"password"}'

# Login (mendapat token)
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password"}'

# Akses endpoint terproteksi
curl -X GET http://localhost:8000/api/v1/cart \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🗄️ Database & Migrasi

### Entity Relationship Diagram (ERD)

```
┌─────────┐     ┌───────────┐     ┌──────────────┐
│  users   │────<│  orders    │────<│ order_items   │
│          │     │            │     │              │
│          │     │            │     └──────┬───────┘
│          │     │            │            │
│          │     │            │────│ payments      │
│          │     │            │────│ shipments     │
│          │     │            │
│          │     └────┬───────┘
│          │          │
│          │────<│  carts     │────<│ cart_items    │
│          │────<│  addresses │
│          │────<│  wishlists │────>│ products      │
│          │────<│  reviews   │────>│              │
└──────────┘                       │              │
                                   │              │────<│ product_images │
┌──────────┐                       │              │
│ categories│──────────────────────<│              │
└──────────┘                       └──────────────┘

┌──────────┐     ┌─────────────────┐
│ campaigns │────<│ campaign_banners │
└──────────┘     └─────────────────┘

┌──────────┐
│  admins   │  (terpisah dari users)
└──────────┘
```

### Daftar Tabel (22 migration files)

| # | Tabel | Deskripsi |
|---|-------|-----------|
| 1 | `users` | Data pengguna (customer) |
| 2 | `cache` | Cache Laravel |
| 3 | `jobs` | Queue jobs |
| 4 | `admins` | Data admin (terpisah dari users) |
| 5 | `categories` | Kategori produk (name, slug, image) |
| 6 | `products` | Data produk (name, price, stock, sizes, dll) |
| 7 | `product_images` | Gambar produk (multiple per produk) |
| 8 | `addresses` | Alamat pengiriman user |
| 9 | `wishlists` | Wishlist user ↔ product |
| 10 | `carts` | Keranjang per user |
| 11 | `cart_items` | Item di dalam keranjang |
| 12 | `orders` | Data pesanan (order_number, status, total) |
| 13 | `order_items` | Item dalam pesanan |
| 14 | `payments` | Data pembayaran |
| 15 | `shipments` | Data pengiriman (tracking, courier) |
| 16 | `reviews` | Ulasan & rating produk |
| 17 | `campaigns` | Kampanye promosi |
| 18 | `campaign_banners` | Banner per campaign |
| 19 | `personal_access_tokens` | Token Sanctum untuk API auth |
| 20 | *(migration)* | Tambah profile fields ke users |
| 21 | *(migration)* | Tambah two-factor columns ke users |
| 22 | *(migration)* | Tambah banner_sale_types ke campaigns |

---

## 🔐 Autentikasi

### Sistem Dual Authentication

Project ini memiliki **2 sistem auth terpisah** untuk User dan Admin:

| Aspek | User (Customer) | Admin |
|-------|-----------------|-------|
| **Model** | `App\Models\User` | `App\Models\Admin` |
| **Guard** | `web` (default) | `admin` (custom) |
| **Tabel** | `users` | `admins` |
| **Login URL** | `/login` (via Fortify) | `/admin/login` |
| **Framework Auth** | Laravel Fortify + Livewire | Custom Controller |
| **Fitur** | Register, Login, 2FA, Email Verification | Login Only |
| **API Auth** | Laravel Sanctum (Bearer Token) | - |

### Middleware yang Digunakan

| Route Group | Middleware | Keterangan |
|-------------|-----------|------------|
| User Pages (login required) | `auth`, `verified` | Cek login user & email terverifikasi |
| Admin Login Page | `guest.admin` | Redirect jika admin sudah login |
| Admin Panel | `admin` | Cek login admin |
| API Protected | `auth:sanctum` | Cek Bearer Token valid |

---

## 🚀 Panduan Setup (Getting Started)

### Prasyarat (Prerequisites)

Pastikan perangkat lunak berikut sudah terinstall:

| Software | Versi Minimum | Link Download |
|----------|---------------|---------------|
| **PHP** | 8.2 | [php.net](https://www.php.net/downloads) |
| **Composer** | 2.x | [getcomposer.org](https://getcomposer.org/download/) |
| **Node.js** | 18.x | [nodejs.org](https://nodejs.org/) |
| **NPM** | 9.x | Terinstall bersama Node.js |
| **MySQL** | 8.x | [mysql.com](https://dev.mysql.com/downloads/) |
| **Git** | 2.x | [git-scm.com](https://git-scm.com/downloads) |

> **💡 Rekomendasi**: Gunakan [Laragon](https://laragon.org/download/) sebagai local development environment di Windows. Laragon sudah menyediakan PHP, MySQL, dan Apache secara otomatis.

### Langkah-Langkah Setup

#### 1️⃣ Clone Repository

```bash
git clone <URL_REPOSITORY> laviade-websie-app
cd laviade-websie-app
```

#### 2️⃣ Install PHP Dependencies

```bash
composer install
```

#### 3️⃣ Konfigurasi Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

Lalu buka file `.env` dan sesuaikan konfigurasi database:

```env
APP_NAME=LAVIADE
APP_URL=http://laviade-websie-app.test    # Sesuaikan dengan URL local kamu

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laviade_db                     # Nama database yang akan digunakan
DB_USERNAME=root                           # Username database
DB_PASSWORD=                               # Password database (kosong jika Laragon)
```

> ⚠️ **Penting**: Buat database `laviade_db` terlebih dahulu di MySQL sebelum menjalankan migrasi.

#### 4️⃣ Jalankan Migrasi Database

```bash
# Buat semua tabel di database
php artisan migrate

# Atau jika ingin reset dan buat ulang
php artisan migrate:fresh
```

#### 5️⃣ Buat Storage Symlink

```bash
php artisan storage:link
```

Perintah ini membuat symbolic link dari `public/storage` ke `storage/app/public`, agar file yang di-upload bisa diakses via URL.

#### 6️⃣ Install Frontend Dependencies

```bash
npm install
```

#### 7️⃣ Jalankan Development Server

**Opsi A - Menggunakan Laragon (Direkomendasikan):**

Jika menggunakan Laragon, cukup start Laragon dan akses via URL:
```
http://laviade-websie-app.test
```

Lalu jalankan Vite dev server untuk hot-reload CSS/JS:
```bash
npm run dev
```

**Opsi B - Menggunakan Built-in PHP Server:**

```bash
# Jalankan semua sekaligus (Laravel + Queue + Vite)
composer run dev
```

Atau jalankan secara terpisah di terminal yang berbeda:
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (wajib untuk CSS/JS)
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

#### 8️⃣ (Opsional) Buat Data Awal

Belum ada seeder bawaan. Kamu bisa membuat data awal via Tinker:

```bash
php artisan tinker
```

```php
// Buat akun admin
App\Models\Admin::create([
    'name' => 'Super Admin',
    'email' => 'admin@laviade.com',
    'password' => bcrypt('password'),
    'role' => 'super_admin',
    'is_active' => true,
]);

// Buat kategori
App\Models\Category::create([
    'name' => 'Hoodies',
    'slug' => 'hoodies',
    'description' => 'Premium streetwear hoodies',
    'is_active' => true,
]);
```

---

## 📋 Perintah-Perintah Penting

### Artisan Commands

```bash
# === Development ===
php artisan serve                    # Jalankan development server
composer run dev                     # Jalankan server + queue + vite sekaligus

# === Database ===
php artisan migrate                  # Jalankan migrasi
php artisan migrate:fresh            # Reset & jalankan ulang semua migrasi
php artisan migrate:rollback         # Rollback migrasi terakhir
php artisan migrate:status           # Cek status migrasi
php artisan db:seed                  # Jalankan seeder
php artisan tinker                   # Laravel REPL (untuk eksperimen)

# === Cache ===
php artisan cache:clear              # Hapus cache aplikasi
php artisan config:clear             # Hapus cache konfigurasi
php artisan route:clear              # Hapus cache route
php artisan view:clear               # Hapus cache view
php artisan optimize:clear           # Hapus semua cache sekaligus

# === Informasi ===
php artisan route:list               # Tampilkan daftar semua route
php artisan route:list --path=api    # Filter route yang diawali /api

# === Generate File ===
php artisan make:model NamaModel -mf        # Model + Migration + Factory
php artisan make:controller NamaController   # Controller baru
php artisan make:middleware NamaMiddleware    # Middleware baru
php artisan make:request NamaRequest         # Form Request baru
php artisan make:test NamaTest               # Feature test baru
php artisan make:test NamaTest --unit        # Unit test baru

# === Testing ===
php artisan test                     # Jalankan semua test
php artisan test --filter=NamaTest   # Jalankan test tertentu
```

### NPM Commands

```bash
npm install              # Install dependencies
npm run dev              # Jalankan Vite dev server (hot-reload)
npm run build            # Build assets untuk production
```

### Code Quality

```bash
# Format kode dengan Laravel Pint (PSR-12)
./vendor/bin/pint

# Jalankan test
./vendor/bin/pest
```

---

## 🔄 Alur Pengembangan (Development Workflow)

### Pola Pengembangan Fitur

Untuk setiap fitur baru, ikuti urutan ini:

```
1. Migration  → Buat/update tabel database (jika perlu)
2. Model      → Buat/update model Eloquent + relasi
3. Service    → Tulis business logic di service layer
4. Request    → Buat form request untuk validasi input
5. Controller → Buat controller (tipis, delegasi ke service)
6. Route      → Daftarkan route di web.php / api.php
7. View       → Buat template Blade (untuk web) atau JSON response (untuk API)
8. Test       → Tulis test untuk fitur tersebut
```

### Konvensi Penamaan

| Jenis | Konvensi | Contoh |
|-------|----------|--------|
| Controller | PascalCase + `Controller` | `ProductController` |
| Model | PascalCase (singular) | `Product`, `OrderItem` |
| Migration | snake_case | `create_products_table` |
| Route Name | dot notation | `shop.index`, `admin.products.create` |
| View | kebab-case | `order-detail.blade.php` |
| Service | PascalCase + `Service` | `ProductService` |

### Git Branching

Gunakan format berikut untuk nama branch:

```
feature/nama-fitur        → Fitur baru
fix/nama-bug              → Perbaikan bug
refactor/nama-refaktor    → Refaktoring kode
```

---

## 🧪 Testing

Project ini menggunakan **Pest PHP** sebagai testing framework.

### Struktur Test

```
tests/
├── Feature/                          # Test end-to-end
│   ├── Auth/
│   │   ├── AuthenticationTest.php       # Test login/logout
│   │   ├── RegistrationTest.php         # Test registrasi
│   │   ├── EmailVerificationTest.php    # Test verifikasi email
│   │   ├── PasswordResetTest.php        # Test reset password
│   │   ├── PasswordConfirmationTest.php # Test konfirmasi password
│   │   └── TwoFactorChallengeTest.php   # Test 2FA
│   ├── DashboardTest.php
│   ├── Settings/
│   │   ├── PasswordUpdateTest.php
│   │   ├── ProfileUpdateTest.php
│   │   └── TwoFactorAuthenticationTest.php
│   └── ExampleTest.php
└── Unit/
    └── ExampleTest.php
```

### Menjalankan Test

```bash
# Jalankan semua test
php artisan test

# Jalankan test tertentu
php artisan test --filter=AuthenticationTest

# Jalankan dengan output verbose
php artisan test -v
```

---

## ⚡ CI/CD (GitHub Actions)

Project ini memiliki 2 GitHub Actions workflow yang berjalan otomatis saat push/pull request:

| Workflow | File | Fungsi |
|----------|------|--------|
| **Lint** | `.github/workflows/lint.yml` | Cek format kode menggunakan Laravel Pint |
| **Tests** | `.github/workflows/tests.yml` | Jalankan seluruh test suite |

---

## 🔧 Troubleshooting

### ❌ Error: "Class not found"

```bash
composer dump-autoload
```

### ❌ Error: Migration gagal

```bash
# Cek status migrasi
php artisan migrate:status

# Reset dan jalankan ulang
php artisan migrate:fresh
```

### ❌ Error: View/Config tidak update

```bash
php artisan optimize:clear
```

### ❌ Error: Storage link tidak berfungsi

```bash
php artisan storage:link
```

### ❌ Error: CSS/JS tidak ter-load

Pastikan Vite dev server berjalan:
```bash
npm run dev
```

### ❌ Error: SQLSTATE - Database not found

Pastikan database `laviade_db` sudah dibuat di MySQL:
```sql
CREATE DATABASE laviade_db;
```

### ❌ Error: Permission denied (storage/logs)

```bash
# Windows (biasanya tidak diperlukan)
# Linux/Mac:
chmod -R 775 storage bootstrap/cache
```

---

## 👥 Kontributor

<!-- Tambahkan nama anggota tim di sini -->
| Nama | Role | GitHub |
|------|------|--------|
| - | - | - |

---

## 📄 Lisensi

Project ini bersifat proprietary untuk **LAVIADE**.

---

<p align="center">
  <b>Dibuat dengan ❤️ oleh Tim LAVIADE</b>
</p>
