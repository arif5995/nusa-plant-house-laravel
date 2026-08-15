# Planning Implementasi: Perbaikan UI/UX Halaman Riwayat Transaksi

**File yang diaudit:** `resources/views/livewire/dashboard/transaction-history.blade.php`, `app/Livewire/Dashboard/TransactionHistory.php`, `app/Services/OrderService.php`
**Target eksekutor:** Junior Developer / AI model gratis
**Catatan:** Bug syntax (`<?php` menggantung) dan format mata uang (`$` → `Rp`) di file ini **sudah diperbaiki** — dokumen ini fokus 100% ke UI/UX, bukan bug fungsional lagi.

---

## 0. Temuan Audit Desain

| #   | Temuan                                                                                                                                                                                                                                                    | Dampak                                                                                                                                                             |
| --- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 1   | Halaman ini pakai warna **`indigo`** (`text-indigo-600`, `focus:ring-indigo-200`, dst), padahal tema resmi project ini **hijau `forest`** (lihat `tailwind.config.js`: `forest.50/100/600/800`) — dipakai konsisten di halaman Cart & Dashboard.          | Halaman ini terlihat "nyasar", tidak konsisten dengan identitas visual toko.                                                                                       |
| 2   | File ini salah satu dari **cuma 2 file di seluruh project** yang pakai class `dark:` (satunya `welcome.blade.php`). `darkMode` bahkan **tidak dikonfigurasi** di `tailwind.config.js`, jadi default Tailwind ke `prefers-color-scheme` (ikut setting OS). | Kalau OS user di-set dark mode, halaman ini tiba-tiba berubah gelap sementara semua halaman lain (Dashboard, Cart, dll) tetap terang — pengalaman tidak konsisten. |
| 3   | Status pesanan cuma teks polos (`Status: Pending`), tidak ada badge warna.                                                                                                                                                                                | Sulit di-scan cepat, padahal Dashboard sudah punya pola badge warna (`bg-yellow-100 text-yellow-800`, dst) yang bisa dipakai ulang.                                |
| 4   | Status pembayaran (`payment_status`) **tidak ditampilkan sama sekali** di list.                                                                                                                                                                           | User harus klik "Lihat Detail" satu-satu cuma untuk tahu mana yang belum dibayar.                                                                                  |
| 5   | Tanggal pesanan tidak ditampilkan di card.                                                                                                                                                                                                                | Sulit membedakan pesanan lama vs baru sekilas.                                                                                                                     |
| 6   | Cuma teks "Lihat Detail" yang bisa diklik, bukan seluruh card.                                                                                                                                                                                            | Target klik kecil, kurang nyaman terutama di mobile.                                                                                                               |
| 7   | Empty state ("Tidak ada transaksi") sama persis baik untuk "user belum pernah pesan" maupun "hasil filter kosong" — tidak ada petunjuk untuk reset filter.                                                                                                | Membingungkan user yang lupa sudah pilih filter status tertentu.                                                                                                   |
| 8   | Tidak ada pencarian berdasarkan nomor pesanan — cuma filter status.                                                                                                                                                                                       | Untuk user dengan banyak transaksi, cukup merepotkan mencari 1 pesanan spesifik.                                                                                   |

---

## 1. Arah Desain

Ikuti bahasa visual yang **sudah dipakai** di halaman Dashboard & Cart:

- Warna: `forest-50/100/600/800`, bukan `indigo`.
- Card: `rounded-2xl` / `rounded-3xl`, `border border-gray-100`, `shadow-sm`.
- Badge status: pola pill warna sesuai status (`bg-green-100 text-green-800` untuk completed, `bg-yellow-100 text-yellow-800` untuk pending, `bg-red-100 text-red-800` untuk cancelled) — **persis pola yang sudah dipakai di `dashboard.blade.php`**, tinggal disalin.
- **Hapus semua class `dark:`** dari file ini — konsisten dengan halaman lain yang tidak mendukung dark mode sama sekali.
- Ikon: pakai Font Awesome (`fa-solid`), sudah dipakai di halaman Cart (`fa-solid fa-truck-fast`, dst).

---

## 2. Breakdown Task

### Task 1 — Tambah Dukungan Pencarian di Backend

**Estimasi:** 45 menit

Sebelum sentuh tampilan, siapkan dulu backend-nya. Edit `app/Services/OrderService.php`, ubah `getUserOrders()`:

```php
public function getUserOrders(?string $status = null, int $perPage = 10, ?string $search = null)
{
    $query = Order::query()->where('user_id', Auth::id())
        ->orderByDesc('created_at');

    if ($status) {
        $query->where('status', $status);
    }

    if ($search) {
        $query->where('order_number', 'like', '%' . $search . '%');
    }

    return $query->paginate($perPage);
}
```

Edit `app/Livewire/Dashboard/TransactionHistory.php`:

```php
<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\OrderService;

class TransactionHistory extends Component
{
    use WithPagination;

    public $statusFilter = null;
    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'tailwind';

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orderService = new OrderService();
        $orders = $orderService->getUserOrders($this->statusFilter, $this->perPage, $this->search);
        return view('livewire.dashboard.transaction-history', [
            'orders' => $orders,
        ]);
    }
}
```

**Checklist:**

- [ ] Ketik sebagian nomor order di kolom pencarian (nanti disambung ke UI di Task 2) → lewat tinker, `getUserOrders(null, 10, 'ORD-2026081')` cuma mengembalikan order yang nomornya cocok.
- [ ] Ganti filter status → halaman tidak "nyangkut" di halaman 2 dari hasil filter sebelumnya (`resetPage()` jalan, sudah ada sebelumnya untuk status, sekarang ditambah untuk search juga).

---

### Task 2 — Rombak Header & Filter (Warna, Badge, Pencarian)

**Estimasi:** 1 jam

Ganti bagian atas file (header + filter), dari:

```blade
<div class="p-6 bg-gray-100 dark:bg-gray-800 rounded-lg shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Riwayat Transaksi</h2>
        <select wire:model="statusFilter" class="block w-48 mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            ...
        </select>
    </div>
```

Menjadi:

```blade
<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fa-solid fa-receipt text-forest-600"></i>
            Riwayat Transaksi
        </h1>
        <p class="text-sm text-gray-500 mt-1">Semua pesanan yang pernah kamu buat.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-grow">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input wire:model.live.debounce.500ms="search" type="text" placeholder="Cari nomor pesanan..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest-600 focus:border-forest-600 outline-none">
        </div>
        <select wire:model.live="statusFilter"
            class="w-full sm:w-52 py-2.5 px-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest-600 focus:border-forest-600 outline-none">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
```

> Perhatikan `wire:model` (tanpa `.live`) di versi lama diganti jadi `wire:model.live` — supaya filter status langsung apply begitu dipilih, tidak perlu submit tombol terpisah (sudah otomatis reaktif via Livewire).

**Checklist:**

- [ ] Ketik di kolom pencarian → hasil list ter-filter otomatis setelah berhenti mengetik (debounce 500ms).
- [ ] Ganti dropdown status → list otomatis ter-filter tanpa reload halaman.
- [ ] Tidak ada lagi warna `indigo` atau class `dark:` tersisa di file.

---

### Task 3 — Rombak Loading State

**Estimasi:** 15 menit

Ganti:

```blade
<div wire:loading class="text-center py-6">
    <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" ...>
```

Menjadi (ganti warna spinner ke `forest-600`, hapus class `dark:`):

```blade
<div wire:loading class="text-center py-10">
    <svg class="animate-spin h-8 w-8 text-forest-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
    </svg>
    <p class="mt-3 text-sm text-gray-500">Memuat data...</p>
</div>
```

**Checklist:**

- [ ] Ganti filter/pencarian → spinner hijau (bukan indigo) sempat muncul sesaat sebelum hasil baru tampil.

---

### Task 4 — Rombak Empty State (Bedakan "Belum Ada Order" vs "Hasil Filter Kosong")

**Estimasi:** 30 menit

Ganti:

```blade
@if($orders->isEmpty())
    <div class="text-center py-12">
        <p class="text-gray-500 dark:text-gray-400">Tidak ada transaksi.</p>
    </div>
@else
```

Menjadi:

```blade
@if($orders->isEmpty())
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
        <i class="fa-regular fa-folder-open text-4xl text-gray-300 mb-3"></i>
        @if ($statusFilter || $search)
            <p class="text-gray-500 text-sm">Tidak ada transaksi yang cocok dengan pencarian/filter ini.</p>
            <button wire:click="$set('statusFilter', ''); $set('search', '')" class="mt-3 text-sm font-semibold text-forest-600 hover:underline">
                Reset filter
            </button>
        @else
            <p class="text-gray-500 text-sm">Kamu belum punya transaksi apa pun.</p>
            <a href="{{ route('products.index') }}" class="mt-3 inline-block text-sm font-semibold text-forest-600 hover:underline">
                Mulai belanja &rarr;
            </a>
        @endif
    </div>
@else
```

> Sesuaikan `route('products.index')` dengan nama route katalog produk yang benar-benar ada di project (cek `routes/web.php`, mungkin namanya beda, misal `products` atau `home`).

**Checklist:**

- [ ] User baru tanpa order sama sekali → muncul pesan "belum punya transaksi" + tombol ke halaman belanja.
- [ ] User dengan order tapi filter status yang dipilih tidak match apa pun → muncul pesan berbeda + tombol "Reset filter" yang berfungsi.

---

### Task 5 — Rombak Card Transaksi (Badge Status, Tanggal, Full-Card Clickable)

**Estimasi:** 1.5 jam

Ganti seluruh blok card:

```blade
<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    @foreach($orders as $order)
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 hover:shadow-lg transition-shadow">
            <h3 class="font-medium text-gray-900 dark:text-gray-100">#{{ $order->order_number }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">Status: {{ ucfirst($order->status) }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            <a href="{{ route('dashboard.transactions.detail', ['order' => $order->id]) }}" class="mt-2 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
                Lihat Detail
            </a>
        </div>
    @endforeach
</div>
```

Menjadi:

```blade
<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    @foreach($orders as $order)
        <a href="{{ route('dashboard.transactions.detail', ['order' => $order->id]) }}"
            class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-forest-200 transition-all">

            <div class="flex items-start justify-between mb-3">
                <h3 class="font-bold text-gray-900 text-sm">#{{ $order->order_number }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                    {{ match($order->status) {
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'shipped', 'processing' => 'bg-blue-100 text-blue-800',
                        default => 'bg-yellow-100 text-yellow-800',
                    } }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <p class="text-xs text-gray-400 mb-3">{{ $order->created_at->format('d M Y, H:i') }}</p>

            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                <span class="text-xs font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                    <i class="fa-solid {{ $order->payment_status === 'paid' ? 'fa-circle-check' : 'fa-clock' }} mr-1"></i>
                    {{ ucfirst($order->payment_status) }}
                </span>
                <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </a>
    @endforeach
</div>
```

> `match()` butuh PHP 8+ — cek versi PHP project dulu (`php -v`). Kalau ternyata masih PHP 7.x (kemungkinan kecil untuk project Laravel modern, tapi tetap dicek), ganti jadi `@if/@elseif` biasa.

**Checklist:**

- [ ] Klik di mana saja pada card (bukan cuma teks "Lihat Detail") → langsung masuk ke halaman detail.
- [ ] Badge status berubah warna sesuai status (`pending` kuning, `completed` hijau, `cancelled` merah, `shipped`/`processing` biru).
- [ ] Badge status pembayaran tampil, warna hijau untuk `paid`, kuning/amber untuk `unpaid`.
- [ ] Tanggal pesanan tampil dengan format yang jelas dibaca.

---

### Task 6 — Rapikan Pagination

**Estimasi:** 15 menit

Bungkus `{{ $orders->links() }}` dengan sedikit spacing tambahan:

```blade
<div class="mt-8 flex justify-center">
    {{ $orders->links() }}
</div>
```

**Checklist:**

- [ ] Kalau data lebih dari `perPage` (10), navigasi halaman tampil rapi, tidak menempel ke card di atasnya.

---

## 3. Estimasi Total Waktu

| Task                       | Estimasi              |
| -------------------------- | --------------------- |
| Task 1 — Backend pencarian | 45 menit              |
| Task 2 — Header & filter   | 1 jam                 |
| Task 3 — Loading state     | 15 menit              |
| Task 4 — Empty state       | 30 menit              |
| Task 5 — Card transaksi    | 1.5 jam               |
| Task 6 — Pagination        | 15 menit              |
| **Total**                  | **± 4–4.5 jam kerja** |

---

## 4. Definition of Done

1. Tidak ada lagi warna `indigo` atau class `dark:` di file ini — konsisten dengan tema `forest` di seluruh halaman lain.
2. Status pesanan & status pembayaran tampil sebagai badge warna, bukan teks polos.
3. Pencarian nomor pesanan berfungsi, reaktif tanpa reload halaman.
4. Empty state membedakan "belum ada order" vs "hasil filter kosong", dengan aksi yang jelas (reset filter / mulai belanja).
5. Seluruh card bisa diklik untuk masuk ke detail, bukan cuma teks link kecil.
6. Semua checklist Task 1–6 lolos, dan halaman tetap berfungsi normal di mobile (cek breakpoint `sm`/`md`/`lg` yang sudah ada di grid).

---

## 5. Catatan untuk Junior Dev / AI Gratis

- Kerjakan Task 1 (backend) duluan sebelum Task 2 (UI pencarian) — kalau dibalik, UI pencarian akan terlihat jalan padahal sebenarnya tidak melakukan apa-apa ke hasil.
- Cek dulu route katalog produk yang benar untuk link "Mulai belanja" di Task 4 — jangan asal tebak nama route, lihat `routes/web.php`.
- Kalau ragu dengan warna badge status, contek langsung pola yang sudah dipakai di `resources/views/livewire/dashboard/dashboard.blade.php` bagian tabel "Pesanan Terbaru" — supaya konsisten, jangan bikin skema warna baru sendiri.
- Jangan ubah logic apa pun di luar yang disebutkan (misal jangan sentuh `getOrderDetail()`, `cancelOrder()`, dst) — scope dokumen ini murni tampilan + 1 penambahan kecil (pencarian) di backend.
