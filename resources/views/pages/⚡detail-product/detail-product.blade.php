<div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ url('/') }}"
            class="flex items-center text-gray-600 hover:text-forest-600 transition space-x-2 text-sm font-medium">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        <div class="space-y-4">
            @if ($product)
                <div class="h-[500px] rounded-3xl overflow-hidden bg-gray-100 border border-gray-200">
                    <img src="{{ isset($product['image']) ? $product['image'] : 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=800' }}"
                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="p-10 text-center">Produk tidak ditemukan.</div>
            @endif
            <div class="grid grid-cols-4 gap-4">
                <div
                    class="h-20 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 cursor-pointer opacity-80 hover:opacity-100">
                    <img src="{{ isset($product['image']) ? $product['image'] : 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=800' }}"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <span
                    class="bg-forest-50 text-forest-800 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">
                    {{ $product->category->name ?? 'Tanaman Hias' }}
                </span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mt-4 leading-tight">
                    {{ $product['name'] ?? 'Produk' }}</h1>
                <p class="text-2xl font-bold text-forest-600 mt-4">Rp
                    {{ number_format($product->price ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div class="border-t border-b border-gray-100 py-4 space-y-2">
                <h4 class="font-semibold text-gray-700">Deskripsi Produk</h4>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ $product->description ?? 'Deskripsi singkat mengenai tanaman ini. Tanaman hias premium yang dirawat dengan media tanam terbaik dan siap mempercantik sudut ruangan Anda.' }}
                </p>
            </div>

            <div class="space-y-4 pt-4">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Jumlah:</span>
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden w-32">
                        <button wire:click="decrementQuantity"
                            class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 transition">-</button>
                        <input type="text" readonly wire:model="quantity"
                            class="w-full text-center font-semibold text-sm focus:outline-none" value="1">
                        <button wire:click="incrementQuantity"
                            class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 transition">+</button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button wire:click="addToCart"
                        class="flex-grow bg-forest-600 hover:bg-forest-800 text-white py-4 rounded-xl font-semibold transition shadow-lg shadow-forest-600/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-cart-plus"></i>
                        <span>+ Keranjang</span>
                    </button>
                    {{-- <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya mau pesan ' . $product->name ?? 'Unknonwn' . ' seharga Rp ' . number_format($product->price, 0, ',', '.')) }}"
                        target="_blank">
                        Pesan via WhatsApp
                    </a> --}}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-100">
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <i class="fa-solid fa-box-open text-forest-600 text-lg"></i>
                    <span>Stok Tersedia ({{ $product->stock ?? 99 }})</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <i class="fa-solid fa-truck text-forest-600 text-lg"></i>
                    <span>Pengiriman dari Pasuruan</span>
                </div>
            </div>
        </div>
    </div>
</div>
