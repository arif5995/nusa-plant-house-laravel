<div>
    {{-- HERO SECTION --}}
    <section
        class="relative h-screen overflow-hidden bg-cover bg-center flex items-center justify-center text-white font-sans"
        style="background-image: url('{{ asset('assets/images/pertanian_background.png') }}');">

        {{-- OVERLAY GELAP --}}
        <div class="absolute inset-0 bg-black/40"></div>

        {{-- CONTENT WRAPPER --}}
        <div class="relative z-10 max-w-5xl mx-auto px-6 w-full flex flex-col items-center text-center">

            {{-- BADGE ATAS --}}
            <span class="hero-badge">
                <span>🌿</span>
                <span>Hasil Bumi Nusantara</span>
            </span>

            {{-- JUDUL UTAMA (H1) --}}
            <h1 class="hero-title">
                Kualitas Terbaik untuk Hasil Bumi Nusantara
            </h1>

            {{-- DESKRIPSI (P) --}}
            <p class="hero-description">
                Kami menghubungkan Anda langsung dengan petani lokal terpilih. Dapatkan sayuran, buah, rempah, dan hasil
                perkebunan premium yang dipanen dengan standar kesegaran terbaik.
            </p>

            {{-- TOMBOL / CTA --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 w-full">
                <a href="#produk" class="btn-primary">
                    <span>Mulai Belanja</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>

                <a href="https://wa.me/6281234567890" target="_blank" class="btn-secondary">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>Tanya Admin</span>
                </a>
            </div>

            {{-- PEMBATAS GARIS --}}
            <hr class="w-full max-w-3xl border-t border-white/20 mt-20 mb-8">

            {{-- SECTION STATISTIK --}}
            <div class="grid grid-cols-3 gap-8 w-full max-w-3xl text-center">
                <div>
                    <h3 class="stat-number">100%</h3>
                    <p class="stat-label">Segar Alami</p>
                </div>
                <div>
                    <h3 class="stat-number">50+</h3>
                    <p class="stat-label">Mitra Petani</p>
                </div>
                <div>
                    <h3 class="stat-number">Grade A</h3>
                    <p class="stat-label">Kualitas Premium</p>
                </div>
            </div>

        </div>
    </section>

    {{-- KEUNGGULAN SECTION --}}
    <section class="py-16 bg-white border-y border-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-3 gap-8">
            <div class="flex items-center space-x-4 p-4 rounded-2xl border border-gray-100 bg-gray-50/50">
                <div
                    class="bg-emerald-600 text-white w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-truck-fast text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Pengiriman Cepat & Aman</h4>
                    <p class="text-sm text-gray-500">Tiba dengan kesegaran terjaga</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 p-4 rounded-2xl border border-gray-100 bg-gray-50/50">
                <div
                    class="bg-emerald-600 text-white w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-award text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Kualitas Premium</h4>
                    <p class="text-sm text-gray-500">Dipilih langsung dari petani lokal</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 p-4 rounded-2xl border border-gray-100 bg-gray-50/50">
                <div
                    class="bg-emerald-600 text-white w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-headset text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Layanan Ramah</h4>
                    <p class="text-sm text-gray-500">Bantuan pemesanan via WhatsApp</p>
                </div>
            </div>
        </div>
    </section>

    {{-- KATALOG KATEGORI UTAMA / PRODUK --}}
    <section id="koleksi-kategori" class="py-16 bg-gray-50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- HEADER SECTION --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-gray-200 pb-6">
            <div>
                <h2 class="section-title">Kategori Pilihan</h2>
                <p class="section-subtitle">Temukan berbagai varian tanaman dan perlengkapan kebun terbaik kami.</p>
            </div>
            <a href="collections" wire:navigate
                class="text-forest-600 font-semibold hover:underline flex items-center space-x-2 transition">
                <span>Lihat Semua Produk</span>
                <i class="fa-solid fa-arrow-right text-sm"></i>
            </a>
        </div>

        {{-- GRID KATEGORI UTAMA --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

            {{-- KATEGORI 1 --}}
            <div
                class="product-card group overflow-hidden rounded-3xl bg-white shadow-sm hover:shadow-xl transition duration-300">
                <div class="h-64 overflow-hidden relative">
                    <img src="https://picsum.photos/id/237/200/300" alt="Tanaman Indoor"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <span
                        class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm text-forest-800 text-xs px-4 py-1.5 rounded-full font-bold tracking-wide shadow-sm">🌿
                        Premium Collection</span>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-bold text-gray-800 text-xl group-hover:text-forest-600 transition font-serif">
                        Tanaman Indoor</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Koleksi tanaman hias yang dapat mempercantik sudut
                        ruangan dan menyegarkan udara hunian Anda.</p>
                    <div class="pt-4 flex justify-end">
                        <a href="{{ route('collections') }}#indoor"
                            class="text-sm font-semibold text-forest-600 hover:text-forest-800 flex items-center space-x-1">
                            <span>Jelajahi</span>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- KATEGORI 2 --}}
            <div
                class="product-card group overflow-hidden rounded-3xl bg-white shadow-sm hover:shadow-xl transition duration-300">
                <div class="h-64 overflow-hidden relative">
                    <img src="https://picsum.photos/id/2/200/300" alt="Kaktus & Sukulen"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <span
                        class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm text-forest-800 text-xs px-4 py-1.5 rounded-full font-bold tracking-wide shadow-sm">🌵
                        Easy Care</span>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-bold text-gray-800 text-xl group-hover:text-forest-600 transition font-serif">Kaktus
                        & Sukulen</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Tanaman minimalis yang tangguh dan sangat mudah
                        perawatannya, cocok untuk meja kerja atau teras.</p>
                    <div class="pt-4 flex justify-end">
                        <a href="{{ route('collections', ['kategori' => 'Kaktus & Sukulen']) }}"
                            class="text-sm font-semibold text-forest-600 hover:text-forest-800 flex items-center space-x-1">
                            <span>Jelajahi</span>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- KATEGORI 3 --}}
            <div
                class="product-card group overflow-hidden rounded-3xl bg-white shadow-sm hover:shadow-xl transition duration-300">
                <div class="h-64 overflow-hidden relative">
                    <img src="https://picsum.photos/id/59/200/300" alt="Media Tanam & Alat"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <span
                        class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm text-forest-800 text-xs px-4 py-1.5 rounded-full font-bold tracking-wide shadow-sm">🪴
                        Gardening Essentials</span>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-bold text-gray-800 text-xl group-hover:text-forest-600 transition font-serif">Media
                        Tanam & Perlengkapan</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Berbagai kebutuhan pendukung tanaman mulai dari
                        kompos, tanah humus, pot premium, hingga nutrisi tanaman.</p>
                    <div class="pt-4 flex justify-end">
                        <a href="{{ route('collections', ['kategori' => 'Media Tanam']) }}"
                            class="text-sm font-semibold text-forest-600 hover:text-forest-800 flex items-center space-x-1">
                            <span>Jelajahi</span>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- KATALOG PRODUK --}}
    <section id="produk" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 relative">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h2 class="section-title">Koleksi Hasil Bumi</h2>
                <p class="section-subtitle">Pilihan sayur, buah, dan rempah terlaris untuk kebutuhan harian Anda.</p>
            </div>
        </div>

        <div class="relative group">
            <button id="scroll-left"
                class="hidden md:flex absolute -left-12 top-1/2 -translate-y-1/2 bg-white border border-gray-200 w-10 h-10 rounded-full items-center justify-center shadow-lg hover:bg-emerald-600 hover:text-white transition z-10">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div id="product-container" class="flex gap-6 overflow-x-auto pb-6 scroll-smooth hide-scrollbar snap-x">
                @foreach ($products as $product)
                    <div class="product-card min-w-[280px] sm:min-w-[300px] snap-start">
                        <div class="h-56 overflow-hidden bg-gray-100 relative group">
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <span
                                class="absolute top-3 left-3 bg-emerald-600 text-white text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-wider shadow-lg">
                                Terlaris
                            </span>
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <span
                                class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $product->category->name }}</span>
                            <h3 class="font-bold text-gray-800 text-lg mt-1">{{ $product['name'] }}</h3>
                            <p class="text-emerald-700 font-black text-xl mt-2 mb-4">Rp {{ $product['price'] }} <span
                                    class="text-xs font-medium text-gray-400">/ kg</span></p>

                            <div class="mt-auto space-y-3">
                                <button wire:click="addToCart({{ $product['id'] }})"
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span>Tambah ke Keranjang</span>
                                </button>

                                {{-- <a href="https://wa.me/6281234567890?text=Halo,%20saya%20tertarik%20dengan%20{{ $product['name'] }}"
                                    target="_blank" class="btn-whatsapp">
                                    <i class="fa-brands fa-whatsapp text-lg"></i>
                                    <span>Beli via WhatsApp</span>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button id="scroll-right"
                class="hidden md:flex absolute -right-12 top-1/2 -translate-y-1/2 bg-white border border-gray-200 w-10 h-10 rounded-full items-center justify-center shadow-lg hover:bg-emerald-600 hover:text-white transition z-10">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>

    @push('scripts')
        <script>
            const container = document.getElementById('product-container');
        </script>
    @endpush

    {{-- SECTION TESTIMONI / KEPERCAYAAN PELANGGAN --}}
    <section class="py-20 bg-forest-50">

        {{-- HEADER SECTION --}}
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span
                class="inline-flex items-center space-x-2 bg-forest-100/60 border border-forest-200 text-xs font-bold px-4 py-1.5 rounded-full tracking-wide text-forest-800 uppercase">
                <i class="fa-solid fa-star text-amber-500"></i>
                <span>Ulasan Pelanggan</span>
            </span>
            <h2 class="section-title">Kata Mereka Tentang Kesegaran Tanaman Kami</h2>
            <p class="pb-8 section-subtitle">Buktikan kualitas layanan dan koleksi tanaman hias kami melalui ulasan
                nyata
                dari para pelanggan.</p>
        </div>

        <div class="relative w-full">
            <div class="animate-scroll flex gap-6 w-max">

                @for ($i = 0; $i < 2; $i++)
                    @foreach ($testimonials as $t)
                        <div class="testimoni-card w-[350px] md:w-[400px] flex-shrink-0">
                            <div class="space-y-4">
                                <div class="flex space-x-1 text-amber-500">
                                    @for ($j = 0; $j < 5; $j++)
                                        <i class="fa-solid fa-star text-sm"></i>
                                    @endfor
                                </div>
                                <p class="text-gray-700 italic">"{{ $t['comment'] }}"</p>
                            </div>
                            <div class="border-t mt-6 pt-4 flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-forest-100 flex items-center justify-center font-bold text-forest-600">
                                    {{ $t['initials'] }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">{{ $t['name'] }}</h4>
                                    <p class="text-xs text-gray-400">{{ $t['role'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endfor

            </div>
        </div>
    </section>

    {{-- SECTION KONTAK / TENTANG --}}
    <section id="tentang" class="pt-20 bg-forest-50">
        <div class="bg-emerald-900 py-24 text-white relative overflow-hidden rounded-t-[3rem] mx-auto max-w-[1400px]">
            <div class="absolute -top-20 -left-20 w-60 h-60 bg-emerald-700 rounded-full blur-3xl opacity-30 -z-10">
            </div>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
                <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Butuh Bantuan atau Ingin Pesan Partai Besar?
                </h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Jangan ragu untuk menghubungi tim admin kami secara langsung. Kami siap melayani kebutuhan hasil
                    bumi
                    untuk rumah tangga maupun komersil Anda.
                </p>
                <div class="flex justify-center pt-4">
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn-contact">
                        <i class="fa-brands fa-whatsapp text-2xl"></i>
                        <span>Chat WhatsApp Kami Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
