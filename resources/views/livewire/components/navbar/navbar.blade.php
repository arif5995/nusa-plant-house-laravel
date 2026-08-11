<div x-data="{ open: false }" class="w-full">
    {{-- No surplus words or unnecessary actions. - Marcus Aurelius --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <a href="/" class="flex items-center space-x-2 text-forest-800 font-bold text-2xl tracking-tight">
                <i class="fa-solid fa-leaf text-forest-600"></i>
                <span>Nusa<span class="text-forest-600">Plants</span></span>
            </a>

            <div class="hidden md:flex items-center space-x-8 font-medium text-gray-600">
                <a href="{{ url('/') }}"
                    class="transition {{ request()->is('/') ? 'text-forest-800 border-b-2 border-forest-600 pb-1' : 'hover:text-forest-600' }}">
                    Beranda
                </a>

                <a href="{{ route('collections') }}" wire:navigate
                    class="transition {{ request()->is('collections*') ? 'text-forest-800 border-b-2 border-forest-600 pb-1' : 'hover:text-forest-600' }}">
                    Koleksi Produk
                </a>

                <a href="{{ route('about') }}" wire:navigate
                    class="transition {{ request()->is('about') ? 'text-forest-800 border-b-2 border-forest-600 pb-1' : 'hover:text-forest-600' }}">
                    Tentang Kami
                </a>
            </div>

            <div class="flex items-center space-x-4">
               @auth
               <livewire:navbar-dropdown />
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-forest-600">Masuk</a>
            @endauth
                <a href="{{ route('cart') }}" wire:navigate
                    class="relative text-gray-600 hover:text-forest-600 transition">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    @if ($count > 0)
                        <span
                            class="absolute -top-2 -right-2 bg-forest-600 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold">
                            {{ $count }}
                        </span>
                    @endif
                </a>
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="hidden sm:flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-full font-medium text-sm transition shadow-sm space-x-2">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>Hubungi Kami</span>
                </a>
                <button @click="open = !open" class="md:hidden text-gray-600 text-2xl">
                    <i class="fa-solid" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
        <div x-show="open" x-transition
            class="md:hidden bg-white border-t border-gray-100 p-4 space-y-4 font-medium text-gray-600">
            @auth
                <a href="{{ url('/') }}" class="block p-2 hover:bg-forest-50 font-bold text-forest-800">
                    Akun Saya
                </a>
            @else
                <a href="{{ url('login') }}" class="block p-2 hover:bg-forest-50">Masuk</a>
            @endauth
            <a href="{{ url('/') }}" class="block p-2 hover:bg-forest-50">Beranda</a>
            <a href="{{ route('collections') }}" wire:navigate class="block p-2 hover:bg-forest-50">Koleksi Produk</a>
            <a href="{{ route('about') }}" wire:navigate class="block p-2 hover:bg-forest-50">Tentang Kami</a>
            <a href="https://wa.me/6281234567890"
                class="block bg-green-600 text-white text-center py-3 rounded-full">Hubungi Kami</a>
        </div>
    </nav>
</div>
