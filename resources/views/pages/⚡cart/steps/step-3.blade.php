<div class="bg-white p-6 rounded-xl border-2 border-gray-200 shadow-lg max-w-sm mx-auto">
    <!-- Header Nota -->
    <div class="text-center border-b-2 border-dashed border-gray-300 pb-4 mb-4">
        <h2 class="text-xl font-bold text-gray-800">NOTA PEMESANAN</h2>
        <p class="text-xs text-gray-500">Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <!-- List Item -->
    <div class="space-y-3 mb-4">
        @foreach ($cart as $item)
            <div class="flex justify-between text-sm">
                <div class="flex flex-col">
                    <span class="font-bold">{{ $item['name'] }}</span>
                    <span class="text-gray-500 text-xs">{{ $item['quantity'] }} x Rp
                        {{ number_format($item['price'], 0, ',', '.') }}</span>
                </div>
                <span class="font-mono">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
            </div>
        @endforeach
    </div>

    <!-- Perhitungan -->
    <div class="border-t-2 border-dashed border-gray-300 pt-4 space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-600">Subtotal:</span>
            <span class="font-mono">Rp {{ number_format($this->getSubtotalProperty(), 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">Ongkir:</span>
            <span class="font-mono">Rp {{ number_format($this->getShippingCostProperty(), 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
            <span>TOTAL:</span>
            <span class="font-mono text-forest-700">Rp
                {{ number_format($this->getSubtotalProperty() + $this->getShippingCostProperty(), 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Informasi Pengiriman -->
    <div class="mt-6 pt-4 border-t border-gray-200 text-xs text-gray-600 space-y-1">
        <p><strong>Penerima:</strong> {{ $fullName }}</p>
        <p><strong>Alamat:</strong> {{ $shippingAddress }}</p>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-6">
        @auth
            <button wire:click="checkout"
                class="w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition flex items-center justify-center gap-2">
                <i class="fa-brands fa-whatsapp"></i> Konfirmasi ke WhatsApp
            </button>
        @else
            <button wire:click="redirectToLogin" class="w-full bg-forest-600 text-white py-3 rounded-lg font-bold">
                Login untuk Memesan
            </button>
        @endauth

        <button wire:click="prevStep" class="w-full mt-3 text-gray-400 hover:text-gray-600 text-xs underline">
            Ubah Detail Pengiriman
        </button>
    </div>
</div>
