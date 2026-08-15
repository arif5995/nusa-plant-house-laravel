<div class="max-w-4xl mx-auto py-10 px-4 space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan #{{ $order->order_number }}</h1>
        <a href="{{ route('dashboard.transactions') }}" class="text-sm text-forest-600 hover:underline">&larr; Kembali</a>
    </div>

    @if (session('success'))
        <div class="p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">{{ session('error') }}</div>
    @endif

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