@props(['product'])
<div class="product-card min-w-[280px] sm:min-w-[300px] snap-start">
    <div class="h-56 overflow-hidden bg-gray-100 relative group">
        <img src="{{ isset($product['image']) ? $product['image'] : 'https://images.unsplash.com/photo-1509423356641-486930004e0a?q=80&w=600' }}"
            alt="{{ $product['name'] ?? 'Produk' }}"
            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

        @if ($product['is_bestseller'] ?? false)
            <span
                class="absolute top-3 left-3 bg-forest-600 text-white text-xs px-2.5 py-1 rounded-md font-medium">Terlaris</span>
        @endif
    </div>
    <div class="p-5 space-y-3 flex-grow flex flex-col justify-between">
        <div>
            <span
                class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ $product->category->name ?? 'Tanaman' }}</span>
            <h3 class="font-bold text-gray-800 text-lg leading-tight mt-0.5">{{ $product['name'] ?? 'Nama Produk' }}</h3>
            <p class="text-forest-600 font-bold text-xl mt-2">Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <div class="mt-auto space-y-3">
            <button wire:click="$dispatch('add-to-cart', { productId: {{ $product['id'] }} })"
                class="w-full bg-forest-600 hover:bg-forest-800 text-white py-3 rounded-xl font-semibold transition shadow-lg shadow-forest-600/20 flex items-center justify-center space-x-2">
                <i class="fa-solid fa-cart-plus"></i>
                <span>+ Keranjang</span>
            </button>
            <a href="{{ route('product.show', $product['id'] ?? '#') }}"
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center space-x-2">
                Lihat Detail
            </a>
        </div>
    </div>
</div>
