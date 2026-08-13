# Planning Implementasi: Dashboard Pembeli (Riwayat Transaksi, Upload Bukti Transfer, Batalkan & Edit Order)

**Revisi berdasarkan klarifikasi:** Dashboard ini **milik pembeli** (bukan admin/toko) — setiap user login melihat riwayat transaksi miliknya sendiri, status pembayaran, dan bisa upload bukti transfer, batalkan pesanan, atau edit order.

**Target eksekutor:** Junior Developer / AI model gratis

---

## 0. Temuan Audit (kondisi kode saat ini)

| #   | Temuan                                                                                                                                    | Lokasi                                                             | Dampak                                                                                     |
| --- | ----------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ |
| 1   | `Dashboard.php` masih 100% data dummy hardcode, statistiknya store-wide (Total Products, dll) — tidak relevan untuk dashboard pembeli     | `app/Livewire/Dashboard/Dashboard.php`                             | Harus dirombak total, bukan sekadar disambung ke DB                                        |
| 2   | `ShippingDetail.php` memanggil `view('livewire.dashboard.shipping-detail')` — **file blade ini tidak ada sama sekali**                    | `app/Livewire/Dashboard/ShippingDetail.php`                        | Route `/dashboard/transactions/{order}` pasti error "View not found" kalau dibuka sekarang |
| 3   | `transaction-history.blade.php` menampilkan total pakai simbol `$` (`${{ number_format($order->total, 2) }}`), padahal ini toko Indonesia | `resources/views/livewire/dashboard/transaction-history.blade.php` | Salah tampilan mata uang                                                                   |
| 4   | Tidak ada mekanisme upload bukti transfer, batalkan pesanan, atau edit order sama sekali di kode manapun                                  | —                                                                  | Fitur belum ada, perlu dibangun dari nol                                                   |
| 5   | Tidak ada tempat penyimpanan data pengiriman (nama penerima, no. HP, alamat) di skema `orders` saat ini                                   | migration `orders`                                                 | Fitur "edit order" butuh field ini — perlu migration tambahan                              |

> **Dependensi:** Kolom `payment_receipt` di tabel `orders` mungkin **belum ada** kalau planning perbaikan checkout (RajaOngkir) sebelumnya belum dikerjakan. Task 1 di bawah mengecek & menambahkannya kalau perlu — aman dijalankan dua kali (idempotent) selama pakai `Schema::hasColumn()` sebagai guard.

---

## 1. Cakupan Fitur

Dashboard pembeli terdiri dari 3 halaman (route sudah ada, tinggal diisi logic-nya):

| Route                             | Component                                                   | Fungsi                                                                                            |
| --------------------------------- | ----------------------------------------------------------- | ------------------------------------------------------------------------------------------------- |
| `/dashboard`                      | `Dashboard.php`                                             | Ringkasan pribadi: jumlah pesanan, jumlah menunggu pembayaran, total belanja, 5 transaksi terbaru |
| `/dashboard/transactions`         | `TransactionHistory.php` (sudah ada, perlu perbaikan minor) | List semua transaksi milik user, bisa difilter status                                             |
| `/dashboard/transactions/{order}` | `ShippingDetail.php` (perlu view baru + fitur baru)         | Detail 1 transaksi: status pembayaran, upload bukti transfer, batalkan pesanan, edit order        |

**Aturan bisnis untuk aksi di halaman detail (wajib ditegakkan di backend, bukan cuma disembunyikan di UI):**

- **Upload bukti transfer:** hanya bisa dilakukan kalau `payment_status = 'unpaid'`.
- **Batalkan pesanan:** hanya bisa kalau `status = 'pending'` (belum diproses toko). Begitu status berubah jadi `processing`/`shipped`/`completed`, tombol batal **hilang**, dan backend tetap menolak kalau ada yang coba akses langsung lewat request manual.
- **Edit order:** hanya bisa kalau `status = 'pending'`, dengan alasan sama seperti batalkan.
- Semua query **wajib** di-scope `where('user_id', Auth::id())` — user A tidak boleh bisa lihat/edit/batalkan order milik user B walau tahu ID order-nya (ini sudah jadi pola di `OrderService::getOrderDetail()`, ikuti pola yang sama).

---

## 2. Arsitektur

```
app/
├── Services/
│   └── OrderService.php          # tambah method baru: cancelOrder(), updateShippingInfo(), attachPaymentReceipt()
├── Livewire/Dashboard/
│   ├── Dashboard.php             # rombak total — ringkasan personal
│   ├── TransactionHistory.php    # sudah ada, tidak perlu diubah logic-nya
│   └── ShippingDetail.php        # tambah: upload receipt, cancel, edit mode
resources/views/livewire/dashboard/
├── dashboard.blade.php           # rombak total
├── transaction-history.blade.php # perbaikan minor (format Rupiah)
└── shipping-detail.blade.php     # BARU — belum ada sama sekali
database/migrations/
├── xxxx_add_payment_receipt_to_orders_table.php   # kalau belum ada dari planning sebelumnya
└── xxxx_add_shipping_info_to_orders_table.php     # BARU — untuk fitur edit order
```

---

## 3. Breakdown Task

### Task 1 — Migration: Kolom yang Dibutuhkan di Tabel `orders`

**Estimasi:** 30 menit

Cek dulu apakah kolom `payment_receipt` sudah ada (dari planning RajaOngkir sebelumnya):

```bash
php artisan tinker
>>> Schema::hasColumn('orders', 'payment_receipt')
```

- **Kalau `false`**, buat migration:

    ```bash
    php artisan make:migration add_payment_receipt_to_orders_table
    ```

    ```php
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->longText('payment_receipt')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_receipt');
        });
    }
    ```

Lalu, untuk fitur **edit order** (nama penerima, no HP, alamat), buat migration baru:

```bash
php artisan make:migration add_shipping_info_to_orders_table
```

```php
public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('recipient_name')->nullable()->after('order_number');
        $table->string('recipient_phone')->nullable()->after('recipient_name');
        $table->text('shipping_address')->nullable()->after('recipient_phone');
        $table->string('city')->nullable()->after('shipping_address');
        $table->string('postal_code')->nullable()->after('city');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['recipient_name', 'recipient_phone', 'shipping_address', 'city', 'postal_code']);
    });
}
```

Update `$fillable` di `app/Models/Order.php`:

```php
protected $fillable = [
    'user_id',
    'order_number',
    'recipient_name',
    'recipient_phone',
    'shipping_address',
    'city',
    'postal_code',
    'status',
    'subtotal',
    'shipping_cost',
    'total',
    'payment_status',
    'payment_receipt',
];
```

> **Catatan:** kalau kolom ini masih kosong untuk order-order lama (dibuat sebelum migration ini jalan), halaman detail harus tetap tampil wajar (tampilkan "-" atau "Belum diisi"), jangan error.

**Checklist:**

- [ ] `php artisan migrate` sukses.
- [ ] `Schema::hasColumn('orders', 'recipient_name')` dan `payment_receipt` → `true`.

---

### Task 2 — Tambah Method di `OrderService`

**Estimasi:** 1.5 jam

Tambahkan 3 method baru ke `app/Services/OrderService.php` (jangan hapus method yang sudah ada):

```php
use Illuminate\Support\Facades\Log;

/**
 * Batalkan order milik user yang login. Hanya bisa jika status masih 'pending'.
 * Return: true jika berhasil, false jika ditolak (status tidak memenuhi syarat).
 */
public function cancelOrder(int $orderId): bool
{
    $order = Order::query()->where('user_id', Auth::id())->findOrFail($orderId);

    if ($order->status !== 'pending') {
        return false;
    }

    $order->update(['status' => 'cancelled']);

    return true;
}

/**
 * Update data pengiriman (nama, no HP, alamat) milik user yang login.
 * Hanya bisa jika status masih 'pending'.
 */
public function updateShippingInfo(int $orderId, array $data): bool
{
    $order = Order::query()->where('user_id', Auth::id())->findOrFail($orderId);

    if ($order->status !== 'pending') {
        return false;
    }

    $order->update([
        'recipient_name'   => $data['recipient_name'],
        'recipient_phone'  => $data['recipient_phone'],
        'shipping_address' => $data['shipping_address'],
        'city'              => $data['city'] ?? null,
        'postal_code'       => $data['postal_code'] ?? null,
    ]);

    return true;
}

/**
 * Simpan bukti transfer (base64) ke order milik user yang login.
 * Hanya bisa jika payment_status masih 'unpaid'.
 */
public function attachPaymentReceipt(int $orderId, string $base64Receipt): bool
{
    $order = Order::query()->where('user_id', Auth::id())->findOrFail($orderId);

    if ($order->payment_status !== 'unpaid') {
        return false;
    }

    $order->update(['payment_receipt' => $base64Receipt]);

    return true;
}
```

**Kenapa logic ini ditaruh di Service, bukan langsung di Livewire component:** supaya aturan bisnis (guard status) tidak bisa dilewati walau ada bug di UI — konsisten dengan pola `getUserOrders()`/`getOrderDetail()` yang sudah ada di file yang sama.

**Checklist:**

- [ ] `php artisan tinker` → buat order dummy `status = 'pending'`, panggil `cancelOrder()` → order berubah jadi `cancelled`, method return `true`.
- [ ] Coba `cancelOrder()` lagi pada order yang sama (sekarang statusnya `cancelled`, bukan `pending`) → return `false`, status **tidak berubah dua kali**.
- [ ] Coba `cancelOrder()` dengan `orderId` milik user lain (bukan yang sedang "login" di tinker) → harus melempar `ModelNotFoundException` (karena `findOrFail` dengan scope `user_id`), bukan malah berhasil membatalkan order orang lain.

---

### Task 3 — Rombak `app/Livewire/Dashboard/Dashboard.php`

**Estimasi:** 1 jam

```php
<?php

namespace App\Livewire\Dashboard;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalOrders = 0;
    public $totalUnpaid = 0;
    public $totalSpent = 'Rp 0';
    public $recentOrders = [];

    public function mount()
    {
        $userId = Auth::id();

        $this->totalOrders = Order::query()->where('user_id', $userId)->count();

        $this->totalUnpaid = Order::query()
            ->where('user_id', $userId)
            ->where('payment_status', 'unpaid')
            ->count();

        $totalSpentRaw = Order::query()
            ->where('user_id', $userId)
            ->where('payment_status', 'paid')
            ->sum('total');

        $this->totalSpent = 'Rp ' . number_format($totalSpentRaw, 0, ',', '.');

        $this->recentOrders = Order::query()
            ->where('user_id', $userId)
            ->latest('created_at')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
}
```

Timpa `resources/views/livewire/dashboard/dashboard.blade.php` (ganti total, hapus kartu "Total Products" yang tidak relevan untuk pembeli, hapus badge persentase palsu yang sebelumnya hardcode):

```blade
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-500 mt-1">Berikut ringkasan pesanan kamu.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Pesanan</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Menunggu Pembayaran</p>
            <h3 class="text-2xl font-bold {{ $totalUnpaid > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $totalUnpaid }}</h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Belanja</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $totalSpent }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
            <a href="{{ route('dashboard.transactions') }}" class="text-sm font-semibold text-forest-600 hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('dashboard.transactions.detail', $order->id) }}" class="text-sm font-semibold text-forest-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada pesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
```

**Checklist:**

- [ ] Login sebagai user yang punya beberapa order → angka "Total Pesanan"/"Menunggu Pembayaran"/"Total Belanja" sesuai data asli user tersebut (bukan data user lain).
- [ ] Login sebagai user baru tanpa order sama sekali → semua angka `0`, tabel menunjukkan "Belum ada pesanan.", **tidak error**.
- [ ] Klik "Lihat semua" / "Detail" → mengarah ke route yang benar.

---

### Task 4 — Perbaikan Minor `TransactionHistory` (format mata uang)

**Estimasi:** 10 menit

Di `resources/views/livewire/dashboard/transaction-history.blade.php`, ganti baris:

```blade
<p class="text-sm text-gray-600 dark:text-gray-300">Total: ${{ number_format($order->total, 2) }}</p>
```

menjadi:

```blade
<p class="text-sm text-gray-600 dark:text-gray-300">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
```

**Checklist:**

- [ ] Buka `/dashboard/transactions` → total tampil format Rupiah, bukan Dollar.

---

### Task 5 — Buat `resources/views/livewire/dashboard/shipping-detail.blade.php` (BARU)

**Estimasi:** 2–2.5 jam

File ini **belum ada sama sekali** — buat dari nol:

```blade
<div class="max-w-4xl mx-auto py-10 px-4 space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan #{{ $order->order_number }}</h1>
        <a href="{{ route('dashboard.transactions') }}" class="text-sm text-forest-600 hover:underline">&larr; Kembali</a>
    </div>

    {{-- Status Pesanan & Pembayaran --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <span class="text-xs uppercase text-gray-400 font-bold">Status Pesanan</span>
            <p class="font-bold text-gray-800 mt-1">{{ ucfirst($order->status) }}</p>
        </div>
        <div>
            <span class="text-xs uppercase text-gray-400 font-bold">Status Pembayaran</span>
            <p class="font-bold mt-1 {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                {{ ucfirst($order->payment_status) }}
            </p>
        </div>
        <div>
            <span class="text-xs uppercase text-gray-400 font-bold">Total</span>
            <p class="font-bold text-gray-800 mt-1">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>
        <div>
            <span class="text-xs uppercase text-gray-400 font-bold">Tanggal Pesan</span>
            <p class="font-bold text-gray-800 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>

    {{-- Item Pesanan --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Item Pesanan</h3>
        <div class="space-y-2">
            @foreach ($order->items as $item)
                <div class="flex justify-between text-sm border-b border-gray-50 pb-2">
                    <span>{{ $item->product_name }} x{{ $item->quantity }}</span>
                    <span class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Info Pengiriman --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800">Info Pengiriman</h3>
            @if ($order->status === 'pending' && ! $editMode)
                <button wire:click="startEdit" class="text-xs font-semibold text-forest-600 hover:underline">Edit</button>
            @endif
        </div>

        @if (! $editMode)
            <div class="text-sm text-gray-700 space-y-1">
                <p><b>Penerima:</b> {{ $order->recipient_name ?: '-' }}</p>
                <p><b>No. HP:</b> {{ $order->recipient_phone ?: '-' }}</p>
                <p><b>Alamat:</b> {{ $order->shipping_address ?: '-' }}</p>
                <p><b>Kota:</b> {{ $order->city ?: '-' }} {{ $order->postal_code ? '('.$order->postal_code.')' : '' }}</p>
            </div>

            @if ($shipment)
                <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-gray-700 space-y-1">
                    <p><b>Kurir:</b> {{ $shipment->courier }} ({{ $shipment->service }})</p>
                    <p><b>No. Resi:</b> {{ $shipment->tracking_number ?: 'Belum tersedia' }}</p>
                    @if ($shipment->tracking_number)
                        <a href="{{ $trackingUrl }}" target="_blank" class="inline-block mt-2 text-xs font-semibold text-forest-600 hover:underline">Lacak Paket &rarr;</a>
                    @endif
                </div>
            @endif
        @else
            {{-- Form Edit --}}
            <div class="space-y-3">
                <input wire:model="recipientName" type="text" placeholder="Nama Penerima"
                    class="w-full p-3 border rounded-xl outline-none @error('recipientName') border-red-500 @enderror">
                @error('recipientName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                <input wire:model="recipientPhone" type="tel" placeholder="No. HP"
                    class="w-full p-3 border rounded-xl outline-none @error('recipientPhone') border-red-500 @enderror">
                @error('recipientPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                <textarea wire:model="shippingAddress" placeholder="Alamat Lengkap"
                    class="w-full p-3 border rounded-xl outline-none h-24 @error('shippingAddress') border-red-500 @enderror"></textarea>
                @error('shippingAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                <div class="grid grid-cols-2 gap-3">
                    <input wire:model="city" type="text" placeholder="Kota" class="w-full p-3 border rounded-xl outline-none">
                    <input wire:model="postalCode" type="text" placeholder="Kode Pos" class="w-full p-3 border rounded-xl outline-none">
                </div>

                <div class="flex gap-3">
                    <button wire:click="cancelEdit" class="px-4 py-2 rounded-xl bg-gray-100 text-sm font-semibold">Batal</button>
                    <button wire:click="saveShippingInfo" class="px-4 py-2 rounded-xl bg-forest-600 text-white text-sm font-semibold">Simpan Perubahan</button>
                </div>
            </div>
        @endif
    </div>

    {{-- Upload Bukti Transfer --}}
    @if ($order->payment_status === 'unpaid')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Upload Bukti Transfer</h3>

        @if ($order->payment_receipt)
            <p class="text-xs text-green-700 bg-green-50 border border-green-100 rounded-lg px-3 py-2 mb-3">
                Bukti transfer sudah diupload, menunggu verifikasi toko.
            </p>
        @endif

        <input type="file" wire:model="paymentReceipt" accept=".jpg,.jpeg,.png,.pdf" class="text-sm">
        @error('paymentReceipt') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror

        <div wire:loading wire:target="paymentReceipt" class="text-xs text-blue-600 mt-2">Mengunggah...</div>

        @if ($paymentReceipt)
            <button wire:click="uploadReceipt" class="mt-3 px-4 py-2 rounded-xl bg-forest-600 text-white text-sm font-semibold">
                Kirim Bukti Transfer
            </button>
        @endif
    </div>
    @endif

    {{-- Batalkan Pesanan --}}
    @if ($order->status === 'pending')
    <div class="bg-red-50 border border-red-100 rounded-2xl p-6 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-red-800">Batalkan Pesanan</h3>
            <p class="text-xs text-red-600 mt-1">Tindakan ini tidak bisa dibatalkan.</p>
        </div>
        <button wire:click="confirmCancel" wire:confirm="Yakin ingin membatalkan pesanan ini?"
            class="px-4 py-2 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
            Batalkan Pesanan
        </button>
    </div>
    @endif

</div>
```

**Checklist:**

- [ ] Buka `/dashboard/transactions/{id}` untuk order milik sendiri → halaman tampil lengkap, tidak lagi error "View not found".
- [ ] Order dengan `status=processing` (bukan `pending`) → tombol "Edit" dan blok "Batalkan Pesanan" **tidak muncul**.
- [ ] Order dengan `payment_status=paid` → blok "Upload Bukti Transfer" **tidak muncul**.
- [ ] Coba akses order milik user lain via URL manual (ganti angka ID di address bar) → harus gagal (404 atau redirect), bukan malah tampil.

---

### Task 6 — Lengkapi `ShippingDetail.php` dengan Method Aksi

**Estimasi:** 2 jam

Timpa isi `app/Livewire/Dashboard/ShippingDetail.php`:

```php
<?php

namespace App\Livewire\Dashboard;

use App\Services\OrderService;
use App\Services\ShipmentService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShippingDetail extends Component
{
    use WithFileUploads;

    public $orderId;
    public $order;
    public $shipment;
    public $trackingUrl = null;

    // Edit mode
    public $editMode = false;
    public $recipientName;
    public $recipientPhone;
    public $shippingAddress;
    public $city;
    public $postalCode;

    // Upload bukti transfer
    public $paymentReceipt;

    protected OrderService $orderService;
    protected ShipmentService $shipmentService;

    public function boot(OrderService $orderService, ShipmentService $shipmentService)
    {
        $this->orderService = $orderService;
        $this->shipmentService = $shipmentService;
    }

    public function mount($order)
    {
        $this->orderId = $order;
        $this->loadOrder();
    }

    protected function loadOrder()
    {
        $this->order = $this->orderService->getOrderDetail($this->orderId);
        $this->shipment = $this->shipmentService->getShipmentByOrder($this->order);

        if ($this->shipment && $this->shipment->tracking_number) {
            $this->trackingUrl = $this->shipmentService->generateTrackingUrl(
                $this->shipment->courier,
                $this->shipment->tracking_number
            );
        }
    }

    // ===== Edit Info Pengiriman =====

    public function startEdit()
    {
        if ($this->order->status !== 'pending') {
            return;
        }

        $this->recipientName    = $this->order->recipient_name;
        $this->recipientPhone   = $this->order->recipient_phone;
        $this->shippingAddress  = $this->order->shipping_address;
        $this->city              = $this->order->city;
        $this->postalCode        = $this->order->postal_code;
        $this->editMode = true;
    }

    public function cancelEdit()
    {
        $this->editMode = false;
        $this->resetErrorBag();
    }

    public function saveShippingInfo()
    {
        $this->validate([
            'recipientName'   => 'required|string|max:255',
            'recipientPhone'  => 'required|string|max:20',
            'shippingAddress' => 'required|string',
        ]);

        $success = $this->orderService->updateShippingInfo($this->orderId, [
            'recipient_name'   => $this->recipientName,
            'recipient_phone'  => $this->recipientPhone,
            'shipping_address' => $this->shippingAddress,
            'city'              => $this->city,
            'postal_code'       => $this->postalCode,
        ]);

        if (! $success) {
            session()->flash('error', 'Pesanan sudah diproses, tidak bisa diubah lagi.');
            $this->editMode = false;
            $this->loadOrder();
            return;
        }

        $this->editMode = false;
        $this->loadOrder();
        session()->flash('success', 'Info pengiriman berhasil diperbarui.');
    }

    // ===== Upload Bukti Transfer =====

    public function uploadReceipt()
    {
        $this->validate([
            'paymentReceipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $fileContents = file_get_contents($this->paymentReceipt->getRealPath());
        $mimeType     = $this->paymentReceipt->getMimeType();
        $base64String = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

        $success = $this->orderService->attachPaymentReceipt($this->orderId, $base64String);

        if (! $success) {
            session()->flash('error', 'Pesanan ini sudah dibayar, tidak perlu upload bukti transfer lagi.');
        } else {
            session()->flash('success', 'Bukti transfer berhasil diupload, menunggu verifikasi toko.');
        }

        $this->paymentReceipt = null;
        $this->loadOrder();
    }

    // ===== Batalkan Pesanan =====

    public function confirmCancel()
    {
        $success = $this->orderService->cancelOrder($this->orderId);

        if (! $success) {
            session()->flash('error', 'Pesanan ini sudah diproses, tidak bisa dibatalkan lagi.');
        } else {
            session()->flash('success', 'Pesanan berhasil dibatalkan.');
        }

        $this->loadOrder();
    }

    public function render()
    {
        return view('livewire.dashboard.shipping-detail');
    }
}
```

> Tambahkan juga blok tampilan flash message (`session('success')`/`session('error')`) di bagian atas `shipping-detail.blade.php` dari Task 5 — sisipkan tepat di bawah judul halaman:
>
> ```blade
> @if (session('success'))
>     <div class="p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl">{{ session('success') }}</div>
> @endif
> @if (session('error'))
>     <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">{{ session('error') }}</div>
> @endif
> ```

**Checklist:**

- [ ] Klik "Edit" pada order `pending` → form muncul terisi data lama, ubah alamat, klik "Simpan Perubahan" → data ter-update, tampilan kembali ke mode baca.
- [ ] Upload bukti transfer pada order `unpaid` → file terupload, flash message sukses muncul, blok upload tetap tampil tapi dengan pesan "menunggu verifikasi".
- [ ] Klik "Batalkan Pesanan" → muncul konfirmasi browser (`wire:confirm`), setelah konfirmasi status order jadi `cancelled`, blok "Batalkan"/"Edit" langsung hilang dari tampilan.
- [ ] Coba trigger `saveShippingInfo()`/`confirmCancel()`/`uploadReceipt()` pada order yang statusnya sudah bukan `pending`/`unpaid` (uji lewat tinker atau ubah status manual dulu di DB) → backend menolak (flash message error), **bukan cuma UI yang menyembunyikan tombol**.

---

## 4. Estimasi Total Waktu

| Task                                             | Estimasi                         |
| ------------------------------------------------ | -------------------------------- |
| Task 1 — Migration kolom `orders`                | 30 menit                         |
| Task 2 — Method baru di `OrderService`           | 1.5 jam                          |
| Task 3 — Rombak `Dashboard.php`                  | 1 jam                            |
| Task 4 — Perbaikan minor `TransactionHistory`    | 10 menit                         |
| Task 5 — Buat `shipping-detail.blade.php` (baru) | 2–2.5 jam                        |
| Task 6 — Lengkapi `ShippingDetail.php`           | 2 jam                            |
| **Total**                                        | **± 7–7.5 jam kerja (± 1 hari)** |

---

## 5. Definition of Done

1. `/dashboard` menampilkan ringkasan **milik user yang login**, bukan data dummy/store-wide.
2. `/dashboard/transactions/{id}` tidak lagi error "View not found" — tampil lengkap dengan status, item, info pengiriman.
3. Upload bukti transfer, batalkan pesanan, dan edit order berfungsi, **dengan guard status di level backend** (bukan cuma disembunyikan di UI).
4. User tidak bisa mengakses/mengubah/membatalkan order milik user lain.
5. Semua checklist Task 1–6 lolos.

---

## 6. Catatan untuk Junior Dev / AI Gratis

- **Guard status (`pending`/`unpaid`) harus ditegakkan di `OrderService`, bukan cuma di Blade** — kalau cuma disembunyikan di UI, user yang paham bisa memicu action Livewire lewat browser console dan tetap berhasil membatalkan/edit order yang seharusnya sudah terkunci. Ini poin paling penting di seluruh planning ini.
- Task 5 & 6 saling bergantung — kerjakan sekuensial, jangan paralel, karena blade di Task 5 memanggil properti/method yang baru ditambahkan di Task 6.
- Kalau ada order lama di database yang dibuat sebelum Task 1 (belum punya `recipient_name` dkk), pastikan tampilan tetap wajar (`{{ $order->recipient_name ?: '-' }}`, sudah ada di kode Task 5) — jangan sampai blank/error karena null.
- Fitur "kurangi/kembalikan stok produk saat order dibatalkan" **belum termasuk** di planning ini karena pengurangan stok saat checkout sendiri belum diimplementasikan di project ini — kalau nanti fitur itu dibuat, `cancelOrder()` di Task 2 perlu direvisi supaya ikut mengembalikan stok.
