TASK — Dashboard Riwayat Transaksi & Detail Pengiriman

1. Project

Project ini menggunakan:

Laravel 13
Livewire 3
Blade
Tailwind CSS
MySQL
Authentication Laravel yang sudah tersedia

Gunakan Livewire Component per fitur.

Struktur yang digunakan:

app/
├── Livewire/
│ └── Dashboard/
│ ├── TransactionHistory.php
│ └── ShippingDetail.php
│
├── Models/
│ ├── Order.php
│ ├── OrderItem.php
│ └── Shipment.php
│
└── Services/
├── OrderService.php
└── ShipmentService.php

View:

resources/
└── views/
└── livewire/
└── dashboard/
├── transaction-history.blade.php
└── shipping-detail.blade.php 2. Tujuan

Tambahkan dua fitur pada Dashboard User:

A. Riwayat Transaksi

User dapat melihat semua transaksi yang pernah dilakukan.

B. Detail Pengiriman

User dapat melihat detail transaksi dan informasi pengiriman dari transaksi tersebut.

3. Database

Buat 3 tabel:

orders
order_items
shipments
Orders

Field:

id
user_id
order_number
status
subtotal
shipping_cost
total
payment_status
created_at
updated_at

Status order:

pending
paid
processing
shipped
delivered
cancelled
Order Items

Field:

id
order_id
product_id
product_name
quantity
price
subtotal
created_at
updated_at

Satu order dapat memiliki banyak order item.

Shipments

Field:

id
order_id
courier
service
tracking_number
status
shipped_at
delivered_at
created_at
updated_at

Status shipment:

pending
processing
shipped
in_transit
delivered 4. Relasi Model

Buat relasi berikut:

User
└── hasMany Orders

Order
├── belongsTo User
├── hasMany OrderItems
└── hasOne Shipment

OrderItem
└── belongsTo Order

Shipment
└── belongsTo Order

Gunakan Eloquent relationship.

5. Fitur Riwayat Transaksi

Buat Livewire:

php artisan make:livewire Dashboard/TransactionHistory

Component:

app/Livewire/Dashboard/TransactionHistory.php

View:

resources/views/livewire/dashboard/transaction-history.blade.php
Tampilkan

Setiap transaksi harus menampilkan:

Nomor Order
Tanggal Order
Produk
Jumlah Produk
Total Harga
Status Pembayaran
Status Order

Contoh:

ORD-20260811-0001

11 Agustus 2026

Bonsai
1 x Rp250.000

Pupuk Organik
2 x Rp50.000

Total: Rp350.000

Status: Dalam Pengiriman

[Lihat Detail] 6. Filter Transaksi

Tambahkan filter:

Semua
Pending
Diproses
Dikirim
Selesai
Dibatalkan

Ketika user memilih filter, daftar transaksi berubah tanpa reload halaman.

Gunakan Livewire.

7. Pagination

Tampilkan maksimal:

10 transaksi per halaman

Gunakan pagination Laravel/Livewire.

8. Empty State

Jika user belum mempunyai transaksi, tampilkan:

Belum Ada Transaksi

Anda belum melakukan pembelian.
Yuk mulai belanja produk tanaman kami.

[Mulai Belanja] 9. Detail Pengiriman

Buat Livewire:

php artisan make:livewire Dashboard/ShippingDetail

Component:

app/Livewire/Dashboard/ShippingDetail.php

View:

resources/views/livewire/dashboard/shipping-detail.blade.php 10. Informasi Detail

Halaman detail harus menampilkan:

Informasi Order
Nomor Order
Tanggal Order
Status Order
Status Pembayaran
Produk
Nama Produk
Jumlah
Harga
Subtotal
Pengiriman
Kurir
Layanan
Nomor Resi
Status Pengiriman
Alamat
Nama Penerima
Nomor Telepon
Alamat
Kota
Provinsi
Kode Pos 11. Timeline Pengiriman

Buat timeline sederhana:

✓ Pesanan Dibuat

✓ Pembayaran Dikonfirmasi

✓ Pesanan Diproses

● Pesanan Dikirim

○ Pesanan Diterima

Status yang belum selesai harus terlihat berbeda dari status yang sudah selesai.

Gunakan Tailwind CSS.

12. Tombol Tracking

Jika terdapat nomor resi, tampilkan:

[Lacak Pengiriman]

Untuk tahap pertama tidak perlu integrasi API kurir.

Cukup siapkan tombol/link tracking.

Contoh:

https://www.jne.co.id/

Jangan membuat integrasi API JNE/J&T/SiCepat pada tahap ini.

13. Security

Ini WAJIB.

User hanya boleh melihat transaksi miliknya sendiri.

Contoh:

Order::where('user_id', auth()->id())

Jangan menggunakan:

Order::find($id)

tanpa mengecek pemilik order.

User tidak boleh bisa melihat order milik user lain walaupun mengetahui ID atau nomor order.

14. Service

Gunakan dua service sederhana.

OrderService

File:

app/Services/OrderService.php

Digunakan untuk:

Mengambil transaksi user
Mengambil detail transaksi
Filter transaksi
ShipmentService

File:

app/Services/ShipmentService.php

Digunakan untuk:

Mengambil informasi pengiriman
Mengambil status pengiriman
Membuat tracking URL

Jangan membuat Repository atau Architecture yang kompleks.

15. Livewire Rules

Livewire hanya menangani:

UI
Filter
Pagination
User interaction
Loading state
Redirect

Service menangani:

Business logic

Model menangani:

Database relationship

Gunakan pola sederhana:

Blade
↓
Livewire
↓
Service
↓
Model
↓
Database 16. UI Design

Gunakan desain yang sudah ada di project.

Gunakan:

Tailwind CSS
Forest green
White
Gray
Rounded card
Soft shadow
Responsive layout

Gunakan card:

rounded-2xl
shadow-sm
border

Tambahkan:

hover effect
loading state
empty state

Jangan mengubah layout website lain yang tidak berhubungan dengan fitur ini.

17. Responsive

Harus berjalan dengan baik pada:

Desktop
Tablet
Mobile

Untuk mobile, transaksi harus berubah menjadi card vertical.

18. CLI Commands

Gunakan command berikut:

php artisan make:model Order -m
php artisan make:model OrderItem -m
php artisan make:model Shipment -m

php artisan make:livewire Dashboard/TransactionHistory
php artisan make:livewire Dashboard/ShippingDetail

php artisan make:class Services/OrderService
php artisan make:class Services/ShipmentService

Kemudian:

php artisan migrate 19. Route

Tambahkan route:

Route::middleware('auth')->group(function () {
Route::get('/dashboard/transactions', \App\Livewire\Dashboard\TransactionHistory::class)
->name('dashboard.transactions');

    Route::get('/dashboard/transactions/{order}', \App\Livewire\Dashboard\ShippingDetail::class)
        ->name('dashboard.transactions.detail');

}); 20. Testing

Setelah selesai, lakukan testing berikut.

Test 1

User login.

Hasil:

User dapat melihat transaksi miliknya.
Test 2

User belum memiliki transaksi.

Hasil:

Menampilkan empty state.
Test 3

User memilih filter Dikirim.

Hasil:

Hanya transaksi dengan status shipped yang muncul.
Test 4

User membuka detail transaksi.

Hasil:

Informasi transaksi dan pengiriman muncul.
Test 5

User mencoba membuka transaksi user lain.

Hasil:

Access ditolak / transaksi tidak ditemukan.
Test 6

User membuka halaman melalui mobile.

Hasil:

Layout tetap rapi dan tidak overflow. 21. Definition of Done

Fitur dianggap selesai jika semua checklist berikut terpenuhi:

[ ] Migration orders selesai
[ ] Migration order_items selesai
[ ] Migration shipments selesai

[ ] Model selesai
[ ] Relationship selesai

[ ] OrderService selesai
[ ] ShipmentService selesai

[ ] TransactionHistory Livewire selesai
[ ] ShippingDetail Livewire selesai

[ ] Riwayat transaksi tampil
[ ] Filter transaksi berjalan
[ ] Pagination berjalan
[ ] Empty state tersedia

[ ] Detail transaksi tersedia
[ ] Detail produk tersedia
[ ] Detail pengiriman tersedia
[ ] Nomor resi tersedia
[ ] Timeline pengiriman tersedia

[ ] Security ownership sudah diterapkan
[ ] User tidak bisa melihat order user lain

[ ] Responsive mobile
[ ] Responsive desktop
[ ] Loading state
[ ] Tidak ada error Laravel
[ ] Tidak ada error Livewire 22. Aturan untuk Developer / AI

PENTING:

1. Jangan mengubah fitur yang tidak berhubungan.
2. Jangan menambahkan package baru tanpa alasan.
3. Jangan membuat Repository.
4. Jangan membuat DDD.
5. Jangan membuat architecture yang kompleks.
6. Gunakan struktur Livewire per fitur.
7. Gunakan Service untuk business logic.
8. Gunakan Eloquent relationship.
9. Jangan query database di Blade.
10. Jangan menaruh business logic di Blade.
11. Pastikan user hanya dapat melihat order miliknya.
12. Gunakan Tailwind dari project yang sudah ada.
13. Ikuti design system yang sudah ada.
14. Kerjakan satu bagian terlebih dahulu sebelum lanjut.
15. Setelah setiap bagian selesai, jelaskan file yang dibuat/diubah.
    Urutan pengerjaan
16. Migration
    ↓
17. Model & Relationship
    ↓
18. Seeder
    ↓
19. OrderService
    ↓
20. TransactionHistory
    ↓
21. ShippingDetail
    ↓
22. Route
    ↓
23. UI Polish
    ↓
24. Security Test
    ↓
25. Final Test

Jangan mengerjakan semua bagian sekaligus. Mulai dari database, pastikan berhasil, kemudian lanjut ke bagian berikutnya.
