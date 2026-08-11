<div class="space-y-4">
    @if (count($cart) > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @foreach ($cart as $id => $item)
                <div class="flex items-center p-6 border-b border-gray-100">
                    <div class="w-20 h-20 rounded-xl mr-4 overflow-hidden bg-gray-100 flex items-center justify-center">
                        <img src="{{ $item['image'] ?? asset('images/default-leaf.png') }}"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-gray-800">{{ $item['name'] }}</h3>
                        <p class="text-sm text-forest-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                    <!-- Tombol Qty & Hapus -->
                    <div class="flex items-center space-x-3 mr-8">
                        <button wire:click="decreaseQuantity('{{ $id }}')"
                            class="w-6 h-6 rounded-full bg-gray-100">-</button>
                        <span class="font-bold w-6 text-center">{{ $item['quantity'] }}</span>
                        <button wire:click="increaseQuantity('{{ $id }}')"
                            class="w-6 h-6 rounded-full bg-forest-100 text-forest-700">+</button>
                    </div>
                    <button wire:click="removeItem('{{ $id }}')" class="text-red-500"><i
                            class="fa-solid fa-trash"></i></button>
                </div>
            @endforeach
            <div class="p-6 bg-gray-50 flex justify-between items-center">
                <div class="text-lg font-bold">Total: Rp
                    {{ number_format($this->getTotalPriceProperty(), 0, ',', '.') }}</div>
                <button wire:click="nextStep" class="bg-forest-600 text-white px-6 py-2 rounded-xl font-bold">Lanjut
                    Pengiriman</button>
            </div>
        </div>
    @else
        <p class="text-center py-12 text-gray-500">Keranjang masih kosong.</p>
    @endif
</div>
