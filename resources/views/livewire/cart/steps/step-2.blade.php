<div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
    <!-- Pilihan Metode -->
    <div>
        <label class="block font-bold mb-2">Pilih Metode Pengiriman:</label>
        <select wire:model.live="shippingType"
            class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-forest-600 outline-none">
            <option value="local">Lokal (Pengiriman Dalam Negeri)</option>
            <option value="export">Ekspor (Pengiriman Internasional)</option>
        </select>
    </div>

    <!-- Form Data Penerima -->
    <div class="space-y-4 pt-4 border-t">
        <h3 class="font-bold text-gray-800">Data Penerima</h3>

        <input wire:model="fullName" type="text" placeholder="Nama Lengkap Penerima"
            class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-forest-600 outline-none @error('fullName') border-red-500 @enderror">
        @error('fullName')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror

        <input wire:model="phoneNumber" type="tel" placeholder="Nomor WhatsApp (+62...)"
            class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-forest-600 outline-none @error('phoneNumber') border-red-500 @enderror">
        @error('phoneNumber')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror

        <input wire:model="email" type="email" placeholder="Alamat Email (Untuk Resi & Dokumen)"
            class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-forest-600 outline-none">
    </div>

    <!-- Alamat & Detail Tambahan (Dinamis) -->
    <div class="space-y-4 pt-4 border-t">
        <h3 class="font-bold text-gray-800">
            {{ $shippingType === 'export' ? 'Alamat Internasional' : 'Alamat Pengiriman' }}
        </h3>

        @if ($shippingType === 'export')
            <div class="grid grid-cols-2 gap-4">
                <input wire:model="country" type="text" placeholder="Negara Tujuan"
                    class="w-full p-3 border rounded-xl outline-none">
                <input wire:model="postalCode" type="text" placeholder="Kode Pos"
                    class="w-full p-3 border rounded-xl outline-none">
            </div>
            <label class="flex items-start space-x-3 bg-red-50 p-4 rounded-xl border border-red-100 cursor-pointer">
                <input wire:model="agreeTerms" type="checkbox" class="mt-1">
                <span class="text-xs text-red-700">
                    <b>Pernyataan Penting:</b> Saya memahami bahwa pengiriman tanaman ke luar negeri memerlukan
                    <b>Sertifikat Phytosanitary</b>.
                </span>
            </label>
        @else
            <input wire:model="city" type="text" placeholder="Kota / Kabupaten"
                class="w-full p-3 border rounded-xl outline-none">
        @endif

        <textarea wire:model="shippingAddress" placeholder="Alamat Lengkap (Street Address, City, State/Province)"
            class="w-full p-3 border rounded-xl h-24 outline-none @error('shippingAddress') border-red-500 @enderror"></textarea>
        @error('shippingAddress')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    @if ($shippingType === 'local')
        <div class="space-y-2 pt-4 border-t relative">
            <label class="block font-bold text-gray-800">Cari Kota / Kecamatan Tujuan</label>

            <div class="relative">
                <input wire:model.live.debounce.500ms="destinationSearch" type="text"
                    placeholder="Ketik nama kota atau kecamatan..."
                    class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-forest-600 outline-none @error('destinationId') border-red-500 @enderror">

                @if (count($destinationResults) > 0)
                    <div class="absolute z-20 w-full bg-white border border-gray-200 rounded-xl mt-1 shadow-lg max-h-60 overflow-y-auto">
                        @foreach ($destinationResults as $item)
                            <button type="button"
                                wire:click="selectDestination('{{ $item['id'] }}', '{{ $item['label'] }}')"
                                class="w-full text-left px-4 py-2.5 hover:bg-forest-50 text-sm text-gray-700 border-b border-gray-50 last:border-0">
                                {{ $item['label'] }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($destinationId)
                <div class="flex items-center gap-2 text-xs text-forest-700 bg-forest-50 border border-forest-100 rounded-lg px-3 py-2 mt-1">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Tujuan terpilih: <b>{{ $destinationLabel }}</b></span>
                </div>
            @endif

            @error('destinationId')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    @endif

    <!-- Tombol Navigasi -->
    <div class="flex gap-4 mt-6">
        <button wire:click="prevStep"
            class="px-6 py-3 bg-gray-100 rounded-xl font-bold hover:bg-gray-200">Kembali</button>
        <button wire:click="nextStep"
            class="flex-grow bg-forest-600 text-white py-3 rounded-xl font-bold hover:bg-forest-700 transition">
            Lanjut ke Konfirmasi
        </button>
    </div>
</div>
