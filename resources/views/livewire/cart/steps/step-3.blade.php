<div class="space-y-8 animate-fade-in">
    <!-- Header Banner -->
    <div style="background-color: #1B4332;" class="relative overflow-hidden bg-forest-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-semibold uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Langkah 3 dari 3
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Ringkasan & <span class="text-emerald-300">Konfirmasi Pesanan</span>
                </h2>
                <p class="text-emerald-100/90 text-sm mt-1 max-w-xl">
                    Periksa kembali rincian produk, alamat pengiriman, dan selesaikan pembayaran untuk memproses pesanan tanaman Anda.
                </p>
            </div>
            <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3 px-4 rounded-2xl border border-white/15 self-start md:self-auto">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/30 flex items-center justify-center text-emerald-200">
                    <i class="fa-solid fa-leaf text-forest-600 text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs text-emerald-200 font-medium">Transaksi Aman</span>
                    <span class="block text-xs font-bold text-white">Jaminan Garansi Segar</span>
                </div>
            </div>
        </div>
        <!-- Decorative Ambient Light -->
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Main Content 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Item List & Shipping Summary -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Items Purchased Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                        <i class="fa-solid fa-basket-shopping text-forest-600"></i> Item Pesanan ({{ count($cart) }})
                    </h3>
                    <span class="text-xs font-medium text-forest-500">Rincian produk</span>
                </div>

                <div class="max-h-[380px] overflow-y-auto pr-1 space-y-2.5 custom-scrollbar">
                    @foreach ($cart as $item)
                        <div class="p-3 bg-gray-50/70 hover:bg-white border border-gray-100 rounded-2xl flex items-center gap-4 transition-all duration-200 group shadow-sm hover:shadow">
                            
                            <!-- Thumbnail Gambar (Persegi 1:1 & Presisi) -->
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-white border border-gray-100 flex-shrink-0 relative aspect-square">
                                <img src="{{ $item['image'] ?? asset('images/default-leaf.png') }}"
                                    alt="{{ $item['name'] }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300 ease-out">
                            </div>

                            <!-- Detail Produk (Nama & Quantity) -->
                            <div class="flex-grow min-w-0">
                                <h4 class="font-serif font-semibold text-gray-800 text-sm truncate group-hover:text-forest-800 transition-colors">
                                    {{ $item['name'] }}
                                </h4>
                                <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-forest-50 border border-forest-100 font-semibold text-forest-800">
                                        {{ $item['quantity'] }}x
                                    </span>
                                    <span>@ Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Total Harga per Item -->
                            <div class="text-right flex-shrink-0 pl-2">
                                <span class="font-sans font-bold text-gray-900 text-sm tracking-tight block">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </span>
                            </div>

                        </div>
                    @endforeach
                </div>

            <!-- Shipping Info Card -->
<div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 space-y-4">
    <!-- Header: Detail Pengiriman & Tombol Ubah Alamat -->
    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
        <!-- Diperbaiki: Mengubah gap-2 menjadi gap-3 agar jarak ikon & judul lebih proporsional -->
        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-3">
            <i class="fa-solid fa-truck-fast text-forest-600"></i>
            <span>Detail Pengiriman</span>
        </h3>
        <!-- Diperbaiki: Mengubah gap-1 menjadi gap-2 agar ikon pen & teks "Ubah Alamat" tidak menempel -->
        <button wire:click="prevStep" class="text-xs text-emerald-700 hover:text-emerald-900 font-semibold transition flex items-center gap-2">
            <i class="fa-solid fa-pen text-[11px]"></i>
            <span>Ubah Alamat</span>
        </button>
    </div>

    <!-- Grid Konten -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
        
        <!-- KARTU 1: PENERIMA -->
        <!-- Diperbaiki: Menggunakan gap-4 agar lingkaran ikon tidak menabrak teks "PENERIMA" -->
        <div class="p-4 bg-gray-50/80 hover:bg-white border border-gray-100 hover:border-gray-200/80 rounded-2xl transition-all duration-200 flex items-start gap-4 shadow-2xs">
            <!-- Icon Box -->
            <div class="w-10 h-10 rounded-full bg-white border border-emerald-100 inline-flex items-center justify-center text-forest-600 shadow-2xs flex-shrink-0">
                <i class="fa-solid fa-user-check text-sm"></i>
            </div>
            <!-- Konten -->
            <div class="min-w-0 flex-grow">
                <span class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-0.5">Penerima</span>
                <h4 class="font-bold text-gray-800 text-sm truncate">{{ $fullName ?: '-' }}</h4>
                @if($phoneNumber)
                    <!-- Diperbaiki: Mengubah gap-1.5 menjadi gap-2 agar ikon telepon tidak terlalu rapat -->
                    <p class="text-xs text-gray-500 font-medium flex items-center gap-2 mt-1">
                        <i class="fa-solid fa-phone text-[10px] text-forest-600"></i>
                        <span>{{ $phoneNumber }}</span>
                    </p>
                @endif
            </div>
        </div>

        <!-- KARTU 2: METODE PENGIRIMAN -->
        <!-- Diperbaiki: Menggunakan gap-4 -->
        <div class="p-4 bg-gray-50/80 hover:bg-white border border-gray-100 hover:border-gray-200/80 rounded-2xl transition-all duration-200 flex items-start gap-4 shadow-2xs">
            <!-- Icon Box -->
            <div class="w-10 h-10 rounded-full {{ $shippingType === 'export' ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-forest-50 border-forest-100 text-forest-800' }} border inline-flex items-center justify-center shadow-2xs flex-shrink-0">
                <i class="fa-solid {{ $shippingType === 'export' ? 'fa-plane-departure' : 'fa-truck-fast' }} text-sm"></i>
            </div>
            <!-- Konten -->
            <div class="min-w-0 flex-grow">
                <span class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Metode Pengiriman</span>
                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-xs font-bold {{ $shippingType === 'export' ? 'bg-amber-100/80 text-amber-900 border border-amber-200/60' : 'bg-emerald-100/80 text-emerald-900 border border-emerald-200/60' }}">
                    <span>{{ $shippingType === 'export' ? 'Ekspor Internasional' : 'Lokal (Dalam Negeri)' }}</span>
                </span>
            </div>
        </div>

        <!-- KARTU 3: ALAMAT TUJUAN -->
        <!-- Diperbaiki: Menggunakan gap-4 -->
        <div class="sm:col-span-2 p-4 bg-gradient-to-br from-gray-50/90 to-emerald-50/25 hover:from-white hover:to-white border border-gray-100 hover:border-emerald-200/60 rounded-2xl transition-all duration-200 flex items-start gap-4 shadow-2xs">
            <!-- Icon Box -->
            <div class="w-10 h-10 rounded-full bg-white border border-emerald-100 inline-flex items-center justify-center text-forest-600 shadow-2xs flex-shrink-0">
                <i class="fa-solid fa-map-location-dot text-sm"></i>
            </div>
            <!-- Konten -->
            <div class="min-w-0 flex-grow">
                <span class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Alamat Tujuan Pengiriman</span>
                
                <p class="text-xs text-gray-800 leading-relaxed font-medium">
                    {{ $shippingAddress ?: '-' }}
                </p>

                <!-- Rincian Kota, Kode Pos, Negara -->
                @if($city || $postalCode || $country)
                    <div class="mt-2.5 pt-2 border-t border-gray-200/60 flex flex-wrap items-center gap-2 text-[11px]">
                        @if($city)
                            <span class="bg-white px-2 py-0.5 rounded-md border border-gray-200/80 text-gray-700 font-semibold shadow-2xs">
                                {{ $city }}
                            </span>
                        @endif
                        @if($postalCode)
                            <span class="bg-white px-2 py-0.5 rounded-md border border-gray-200/80 text-gray-600 font-medium shadow-2xs">
                                Kode Pos: <span class="font-bold text-gray-800">{{ $postalCode }}</span>
                            </span>
                        @endif
                        @if($country)
                            <span class="bg-forest-50 text-forest-800 px-2 py-0.5 rounded-md border border-forest-100 font-bold shadow-2xs inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-flag text-[9px]"></i>
                                <span>{{ $country }}</span>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
            </div>
            </div>
                
                <!-- KARTU 1: PENERIMA -->
                <div class="p-4 bg-gray-50/80 hover:bg-white border border-gray-100 hover:border-gray-200/80 rounded-2xl transition-all duration-200 flex items-start gap-4 shadow-2xs">
                    <!-- Icon Box -->
                    <div class="w-10 h-10 rounded-full bg-white border border-emerald-100 inline-flex items-center justify-center text-forest-600 shadow-2xs flex-shrink-0">
                        <i class="fa-solid fa-user-check text-sm"></i>
                    </div>
                    <!-- Konten -->
                    <div class="min-w-0 flex-grow">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-0.5">Penerima</span>
                        <h4 class="font-bold text-gray-800 text-sm truncate">{{ $fullName ?: '-' }}</h4>
                        @if($phoneNumber)
                            <p class="text-xs text-gray-500 font-medium flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-phone text-[10px] text-forest-600"></i>
                                <span>{{ $phoneNumber }}</span>
                            </p>
                        @endif
                    </div>
                </div>  

                <!-- KARTU 2: METODE PENGIRIMAN -->
                <div class="p-4 bg-gray-50/80 hover:bg-white border border-gray-100 hover:border-gray-200/80 rounded-2xl transition-all duration-200 flex items-start gap-4 shadow-2xs">
                    <!-- Icon Box Dinamis -->
                    <div class="w-10 h-10 rounded-full {{ $shippingType === 'export' ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-forest-50 border-forest-100 text-forest-800' }} border inline-flex items-center justify-center shadow-2xs flex-shrink-0">
                        <i class="fa-solid {{ $shippingType === 'export' ? 'fa-plane-departure' : 'fa-truck-fast' }} text-sm"></i>
                    </div>
                    <!-- Konten -->
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Metode Pengiriman</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $shippingType === 'export' ? 'bg-amber-100/80 text-amber-900 border border-amber-200/60' : 'bg-emerald-100/80 text-emerald-900 border border-emerald-200/60' }}">
                            {{ $shippingType === 'export' ? 'Ekspor Internasional' : 'Lokal (Dalam Negeri)' }}
                        </span>
                    </div>
                </div>

                <!-- KARTU 3: ALAMAT TUJUAN (FULL WIDTH) -->
                <div class="sm:col-span-2 p-4 bg-gradient-to-br from-gray-50/90 to-emerald-50/25 hover:from-white hover:to-white border border-gray-100 hover:border-emerald-200/60 rounded-2xl transition-all duration-200 flex items-start gap-4 shadow-2xs">
                    <!-- Icon Box -->
                        <div class="w-10 h-10 rounded-full bg-white border border-emerald-100 inline-flex items-center justify-center text-forest-600 shadow-2xs flex-shrink-0">
                            <i class="fa-solid fa-map-location-dot text-sm"></i>
                        </div>
                    <!-- Konten Alamat -->
                    <div class="min-w-0 flex-grow">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Alamat Tujuan Pengiriman</span>
                        
                        <p class="text-xs text-gray-800 leading-relaxed font-medium">
                            {{ $shippingAddress ?: '-' }}
                        </p>

                        <!-- Rincian Kota, Kode Pos, Negara (Mini Badges) -->
                        @if($city || $postalCode || $country)
                            <div class="mt-2.5 pt-2 border-t border-gray-200/60 flex flex-wrap items-center gap-1.5 text-[11px]">
                                @if($city)
                                    <span class="bg-white px-2 py-0.5 rounded-md border border-gray-200/80 text-gray-700 font-semibold shadow-2xs">
                                        {{ $city }}
                                    </span>
                                @endif
                                @if($postalCode)
                                    <span class="bg-white px-2 py-0.5 rounded-md border border-gray-200/80 text-gray-600 font-medium shadow-2xs">
                                        Kode Pos: <span class="font-bold text-gray-800">{{ $postalCode }}</span>
                                    </span>
                                @endif
                                @if($country)
                                    <span class="bg-forest-50 text-forest-800 px-2 py-0.5 rounded-md border border-forest-100 font-bold shadow-2xs flex items-center gap-1">
                                        <i class="fa-solid fa-flag text-[9px]"></i> {{ $country }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            </div>

<!-- Financial Calculation Summary Card -->
<div class="bg-gradient-to-br from-emerald-50/70 via-gray-50/80 to-emerald-50/40 rounded-3xl p-6 shadow-sm border border-emerald-200/80 space-y-4">
    
    <!-- Rincian Biaya (Layer Box Putih) -->
    <div class="bg-white/90 rounded-2xl p-4 border border-emerald-100/80 shadow-2xs space-y-3">
        <!-- Subtotal -->
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600 font-medium">Subtotal Produk</span>
            <span class="font-bold font-mono text-gray-800 tracking-tight">
                Rp {{ number_format($this->getSubtotalProperty(), 0, ',', '.') }}
            </span>
        </div>

        <!-- Biaya Pengiriman -->
        <div class="flex justify-between items-center text-sm pt-2 border-t border-dashed border-gray-150">
            <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                <span class="text-gray-600 font-medium">Biaya Pengiriman</span>
                @if($shippingType === 'export')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200/60 text-amber-800 text-[10px] font-semibold w-fit">
                        <i class="fa-solid fa-certificate text-[9px]"></i>
                        <span>Phytosanitary Cert.</span>
                    </span>
                @endif
            </div>
            <span class="font-bold font-mono text-gray-800 tracking-tight">
                Rp {{ number_format($this->getShippingCostProperty(), 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Bagian Total Tagihan (Highlight Utama) -->
    <div class="pt-1 px-1 flex items-end justify-between gap-4">
        <div class="space-y-1">
            <span class="text-[11px] text-emerald-800 uppercase tracking-widest font-bold block">
                Total Tagihan
            </span>
            <span class="text-2xl sm:text-3xl font-black font-mono text-forest-800 tracking-tight block">
                Rp {{ number_format($this->getSubtotalProperty() + $this->getShippingCostProperty(), 0, ',', '.') }}
            </span>
        </div>

        <!-- Badge IDR Premium -->
        <div class="flex-shrink-0 mb-0.5">
            <span class="inline-flex items-center gap-1.5 bg-forest-800 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md shadow-forest-800/20 tracking-wider">
                <i class="fa-solid fa-shield-check text-emerald-300 text-[11px]"></i>
                <span>IDR</span>
            </span>
        </div>
    </div>

</div>

        </div>

            <!-- Right Column: Payment Instructions & Receipt Upload -->
<div class="lg:col-span-5 space-y-5">
    
    <!-- KARTU 1: REKENING PEMBAYARAN -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 space-y-4">
        <!-- Header: Perbaikan Jarak (gap-3) & Center Ikon -->
        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-3">
            <i class="fa-solid fa-building-columns text-forest-600"></i>
            <span>Rekening Pembayaran</span>
        </h3>
        
        <p class="text-xs text-gray-500 leading-relaxed font-medium">
            Silakan lakukan transfer ke salah satu rekening resmi <strong class="text-gray-800 font-semibold">Nusa Plant House</strong> berikut:
        </p>

        <!-- Daftar Rekening Bank -->
        <div class="space-y-3 pt-1">
            <!-- Bank BCA (Perbaikan background solid bg-blue-600 agar tidak putih/blank) -->
            <div class="p-4 rounded-2xl bg-gray-50/80 hover:bg-white border border-gray-200 transition-all duration-200 flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-12 h-10 rounded-xl bg-blue-600 text-white font-black text-xs inline-flex items-center justify-center tracking-wider shadow-sm flex-shrink-0">
                        BCA
                    </div>
                    <div class="min-w-0">
                        <span class="block text-sm font-bold text-gray-900 tracking-wide font-mono">123-456-7890</span>
                        <span class="block text-[11px] text-gray-500 font-medium truncate">a.n Nusa Plant House</span>
                    </div>
                </div>
                <span class="text-[11px] font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg border border-blue-100 flex-shrink-0">
                    <i class="fa-regular fa-copy mr-1"></i>Salin
                </span>
            </div>

            <!-- Bank Mandiri (Perbaikan background solid bg-blue-800) -->
            <div class="p-4 rounded-2xl bg-gray-50/80 hover:bg-white border border-gray-200 transition-all duration-200 flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-12 h-10 rounded-xl bg-blue-800 text-white font-black text-xs inline-flex items-center justify-center tracking-wider shadow-sm flex-shrink-0">
                        MDR
                    </div>
                    <div class="min-w-0">
                        <span class="block text-sm font-bold text-gray-900 tracking-wide font-mono">987-000-1234</span>
                        <span class="block text-[11px] text-gray-500 font-medium truncate">a.n Nusa Plant House</span>
                    </div>
                </div>
                <span class="text-[11px] font-semibold text-blue-800 bg-blue-50 px-3 py-1 rounded-lg border border-blue-100 flex-shrink-0">
                    <i class="fa-regular fa-copy mr-1"></i>Salin
                </span>
            </div>
        </div>
    </div>

    <!-- KARTU 2: UPLOAD BUKTI TRANSFER -->
    @auth
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 space-y-4">
        <!-- Header: Presisi dengan gap-3 & Center Ikon -->
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-3">
                <i class="fa-solid fa-file-arrow-up text-forest-600"></i>
                <span>Bukti Transfer</span>
            </h3>
            <span class="text-[10px] font-bold uppercase tracking-widest text-red-600 bg-red-50 border border-red-200/60 px-2.5 py-1 rounded-full">
                * Wajib
            </span>
        </div>

        <!-- Area Konten Upload -->
        <div class="relative">
            
            <!-- STATE 1: JIKA BELUM ADA FILE (Tampilkan Dropzone) -->
            @if (!$paymentReceipt)
                <label class="group relative flex flex-col items-center justify-center p-6 border-2 border-dashed border-emerald-300 rounded-2xl cursor-pointer bg-emerald-50/50 hover:bg-emerald-50 hover:border-emerald-500 transition-all duration-200">
                    <input type="file" 
                        wire:model="paymentReceipt" 
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden">
                    
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white inline-flex items-center justify-center mb-3 group-hover:scale-105 group-hover:bg-emerald-700 transition duration-200 shadow-md">
                        <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                    </div>

                    <span class="text-xs font-bold text-gray-800 group-hover:text-emerald-800 transition text-center block">
                        Klik untuk Unggah Bukti Pembayaran
                    </span>
                    <span class="text-[11px] text-gray-400 mt-1 text-center font-medium block">
                        Format JPG, PNG, atau PDF (Maksimal 2MB)
                    </span>
                </label>

            <!-- STATE 2: JIKA FILE SUDAH DIUNGGAH (Tampilkan Preview & Tombol Hapus) -->
            @else
                <div class="p-4 bg-emerald-50/70 border border-emerald-200 rounded-2xl space-y-3.5 shadow-2xs">
                    
                    <!-- Status Bar & Tombol Hapus -->
                    <div class="flex items-center justify-between pb-2 border-b border-emerald-200/60 mb-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-emerald-900">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm flex-shrink-0"></i>
                            <span>Berkas Siap Dilampirkan</span>
                        </div>

                        <!-- Tombol Hapus (Reset File ke Null di Livewire) -->
                        <button type="button"
                                wire:click="$set('paymentReceipt', null)"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 border border-red-200/80 text-red-600 hover:text-red-700 font-bold text-[11px] transition cursor-pointer flex-shrink-0 mb-4">
                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                            <span>Hapus</span>
                        </button>
                    </div>

                    <!-- Tampilan Preview Gambar (JPG, PNG) -->
                    @if (in_array($paymentReceipt->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                        <div class="relative overflow-hidden rounded-xl border border-emerald-200 h-44 bg-white flex items-center justify-center">
                            <img src="{{ $paymentReceipt->temporaryUrl() }}" 
                                alt="Preview Bukti Transfer" 
                                class="w-full h-full object-contain">
                        </div>
                    <!-- Tampilan Preview PDF -->
                    @else
                        <div class="p-3.5 bg-white border border-emerald-100 rounded-xl flex items-center gap-3 text-xs text-gray-800 font-semibold shadow-2xs">
                            <div class="w-10 h-10 rounded-lg bg-red-50 border border-red-100 inline-flex items-center justify-center flex-shrink-0 text-red-500">
                                <i class="fa-solid fa-file-pdf text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="block truncate text-gray-900">{{ $paymentReceipt->getClientOriginalName() }}</span>
                                <span class="block text-[10px] text-gray-400 mt-0.5 uppercase">Dokumen PDF</span>
                            </div>
                        </div>
                    @endif

                    <!-- Tombol Ganti File (Opsional agar user tidak perlu klik hapus dulu) -->
                    <div class="pt-3">
                        <label class="w-full py-2 px-3 bg-white hover:bg-gray-50 border border-emerald-200 rounded-xl text-xs font-semibold text-emerald-800 flex items-center justify-center gap-2 cursor-pointer transition shadow-2xs block text-center">
                            <input type="file" 
                                wire:model="paymentReceipt" 
                                accept=".jpg,.jpeg,.png,.pdf" 
                                class="hidden">
                            <i class="fa-solid fa-rotate text-emerald-600 text-xs"></i>
                            <span>Ganti dengan Berkas Lain</span>
                        </label>
                    </div>

                </div>
            @endif

            <!-- Loading State Saat Upload / Ganti File -->
            <div wire:loading wire:target="paymentReceipt" class="mt-3 w-full p-3.5 bg-blue-50 border border-blue-200 rounded-2xl flex items-center gap-3 text-xs text-blue-800 shadow-2xs">
                <i class="fa-solid fa-spinner fa-spin text-base text-blue-600 flex-shrink-0"></i>
                <span class="font-semibold">Mengunggah dan memproses berkas...</span>
            </div>

            <!-- Error Validasi -->
            @error('paymentReceipt')
                <div class="mt-3 p-3.5 bg-red-50 border border-red-200 rounded-2xl text-xs text-red-700 font-semibold flex items-center gap-2.5 shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-red-600 text-sm flex-shrink-0"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

        </div>
    </div>
    @endauth

    <!-- KARTU 3: TOMBOL AKSI & CHECKOUT -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 space-y-4">
        @auth
            <!-- Perbaikan Tombol: Mengubah ke bg-emerald-600 solid agar teks selalu terlihat jelas -->
            <button wire:click="checkout"
                    wire:loading.attr="disabled"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-emerald-600/20 transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-3 disabled:opacity-60 disabled:cursor-not-allowed text-base cursor-pointer">
                <i class="fa-brands fa-whatsapp text-2xl text-white flex-shrink-0"></i> 
                <span class="text-white font-bold">Konfirmasi & Kirim Pesanan</span>
            </button>
            
            <p class="text-[11px] text-gray-500 text-center leading-normal">
                Pesanan Anda akan diteruskan ke tim <span class="font-semibold text-gray-700">Customer Service</span> kami via <span class="font-bold text-emerald-600">WhatsApp</span> secara otomatis.
            </p>
        @else
            <div class="p-5 bg-amber-50 border border-amber-200 rounded-2xl text-center space-y-3.5 shadow-2xs">
                <p class="text-xs text-amber-900 font-semibold leading-relaxed">
                    Anda harus masuk ke akun Anda terlebih dahulu untuk menyelesaikan pemesanan.
                </p>
                <a href="{{ route('login') }}" 
                   wire:click.prevent="redirectToLogin"
                   class="inline-block w-full bg-forest-800 hover:bg-forest-900 text-white py-3.5 px-4 rounded-xl font-bold text-sm text-center transition shadow-md shadow-forest-800/20 hover:opacity-95 cursor-pointer">
                    <i class="fa-solid fa-right-to-bracket mr-2 text-emerald-300"></i>
                    <span>Login untuk Memesan</span>
                </a>
            </div>
        @endauth

        <!-- Tombol Kembali -->
        <button wire:click="prevStep" 
                class="w-full py-3.5 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-emerald-50 hover:border-emerald-200 text-gray-600 hover:text-emerald-800 text-xs font-semibold transition flex items-center justify-center gap-2 cursor-pointer group">
            <i class="fa-solid fa-arrow-left text-xs text-gray-400 group-hover:text-emerald-600 transition flex-shrink-0"></i> 
            <span>Ubah Detail Pengiriman</span>
        </button>
    </div>

</div>

    </div>
</div>