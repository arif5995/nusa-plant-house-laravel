# Refactor Project Structure (Modern Laravel Architecture)

Tugas ini bertujuan untuk merestrukturisasi arsitektur direktori aplikasi Laravel kita menjadi lebih rapi, modular, dan terukur. Struktur ini dirancang untuk memisahkan UI/interaction (Livewire), single-responsibility actions, dan business logic utama (Services) sehingga lebih mudah dikelola (maintainable).

Silakan ikuti dan implementasikan struktur folder di bawah ini:

## 1. Direktori `app/`

Pindahkan atau buat kelas-kelas PHP sesuai dengan struktur berikut:

```text
app/
│
├── Actions/                # Logic kecil (single responsibility)
│   ├── Auth/
│   └── User/
│
├── Livewire/              # UI + interaction (core utama)
│   ├── Dashboard/
│   │   ├── Index.php
│   │   └── StatsCard.php
│   │
│   ├── Profile/
│   │   ├── EditProfile.php
│   │   └── ChangePassword.php
│   │
│   ├── Components/        # reusable UI
│   │   ├── Navbar.php
│   │   ├── DropdownUser.php
│   │   └── Modal.php
│   │
│   └── Shop/
│       ├── ProductList.php
│       ├── ProductDetail.php
│       └── Cart.php
│
├── Models/
│   ├── User.php
│   ├── Product.php
│   ├── Order.php
│   └── Category.php
│
├── Services/              # Business logic utama
│   ├── AuthService.php
│   ├── ProductService.php
│   ├── OrderService.php
│   └── UserService.php
│
├── Repositories/          # (optional, kalau project mulai besar)
│   ├── ProductRepository.php
│   └── OrderRepository.php
│
├── Helpers/               # helper global (optional)
│
└── Providers/
```

## 2. Direktori `resources/views/`

Pindahkan dan sesuaikan file blade UI untuk Livewire sesuai struktur di bawah:

```text
resources/views/
│
├── livewire/
│   ├── dashboard/
│   │   ├── index.blade.php
│   │   └── stats-card.blade.php
│   │
│   ├── profile/
│   │   ├── edit-profile.blade.php
│   │   └── change-password.blade.php
│   │
│   ├── components/
│   │   ├── navbar.blade.php
│   │   ├── dropdown-user.blade.php
│   │   └── modal.blade.php
│   │
│   └── shop/
│       ├── product-list.blade.php
│       ├── product-detail.blade.php
│       └── cart.blade.php
│
├── layouts/
│   └── app.blade.php
│
└── pages/
    ├── home.blade.php
    └── dashboard.blade.php
```

## Langkah-langkah Implementasi:
1. **Buat Direktori**: Buat folder-folder baru seperti `Actions`, `Services`, dan `Repositories` (jika diperlukan) di dalam folder `app/`.
2. **Pindahkan Logic**: Ekstrak business logic yang sebelumnya ada di dalam Controller atau komponen Livewire, lalu masukkan ke dalam `Services` atau `Actions`.
3. **Pindahkan File Livewire**:
   - Pindahkan class komponen Livewire (baik Volt maupun standard) ke namespace `App\Livewire\...`.
   - Pindahkan file `.blade.php` komponen Livewire ke dalam `resources/views/livewire/...`.
4. **Update Routes & Namespaces**: Jangan lupa untuk memperbarui namespace (terutama di file `routes/web.php` jika menggunakan class components) serta reference path ke setiap View/Livewire component.

*Issue ini dapat dikerjakan secara bertahap oleh junior programmer atau digunakan sebagai prompt referensi untuk AI code generator.*
