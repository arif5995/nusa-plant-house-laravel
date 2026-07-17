<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <div class="text-center space-y-3 max-w-2xl mx-auto">
            <h1 class="text-4xl font-bold text-forest-800 tracking-tight">Koleksi Tanaman & Perlengkapan</h1>
            <p class="text-gray-600 text-sm">Jelajahi berbagai varian tanaman segar dan media tanam premium.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 items-start">

            <aside class="filter-sidebar">
                <div>
                    <h3 class="filter-heading">Pencarian</h3>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tanaman..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:border-forest-600 text-sm">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div>
                    <h3 class="filter-heading">Kategori Tanaman</h3>
                    <div class="space-y-2">
                        <button wire:click="$set('selectedCategory', '')"
                            class="filter-btn {{ $selectedCategory == '' ? 'filter-btn-active' : 'filter-btn-inactive' }}">
                            Semua Produk
                        </button>
                        @foreach ($categories as $category)
                            <button wire:click="$set('selectedCategory', '{{ $category }}')"
                                class="filter-btn {{ $selectedCategory == $category ? 'filter-btn-active' : 'filter-btn-inactive' }}">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </aside>

            <main class="md:col-span-3 space-y-6">
                <div class="product-header-bar">
                    <p class="text-sm text-gray-500">Menampilkan <span
                            class="font-semibold text-gray-800">{{ count($products) }}</span> produk</p>
                </div>

                @if (count($products) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($products as $productItem)
                            <x-product-card :product="$productItem" />
                        @endforeach
                    </div>
                @else
                    <div class="bg-white p-12 rounded-2xl border border-gray-100 shadow-sm text-center space-y-3">
                        <div class="text-4xl text-gray-300"><i class="fa-solid fa-seedling"></i></div>
                        <h4 class="font-bold text-gray-700 text-lg">Produk Tidak Ditemukan</h4>
                        <p class="text-sm text-gray-500">Coba gunakan kata kunci atau kategori yang lain.</p>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
