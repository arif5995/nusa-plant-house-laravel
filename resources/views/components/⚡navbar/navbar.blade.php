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
                <!-- Dropdown Container -->
                <div x-data="{ openDropdown: false }" class="relative">
                    <!-- Trigger: Lingkaran Profil -->
                    <button @click="openDropdown = !openDropdown" class="flex items-center space-x-2 focus:outline-none">
                        <div class="w-10 h-10 rounded-full bg-forest-100 border border-forest-600 flex items-center justify-center text-forest-800 font-bold hover:bg-forest-200 transition">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                                <!-- Dropdown Menu yang Ditingkatkan -->
                            <!-- Gunakan w-72 dan min-w-[280px] agar lebar lebih terjamin -->
                    <div x-show="openDropdown" 
                        @click.away="openDropdown = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="absolute right-0 mt-3 w-72 min-w-[280px] bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-[999] overflow-hidden">
                        
                        <!-- Bagian Header User -->
                        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Masuk sebagai</p>
                            <p class="text-sm font-bold text-gray-800 truncate mt-1">{{ auth()->user()->name }}</p>
                        </div>

                        <!-- Gunakan blok di bawah ini untuk link agar tidak terpotong -->
                        <div class="py-2">
                            <a href="#" class="flex items-center px-6 py-3.5 text-sm text-gray-700 hover:bg-forest-50 hover:text-forest-700 transition duration-150">
                                <i class="fa-solid fa-gauge-high mr-4 text-forest-600"></i> <span class="font-medium">Dashboard</span>
                            </a>
                            
                            <a href="#" class="flex items-center px-6 py-3 text-sm text-gray-700 hover:bg-forest-50 hover:text-forest-700 transition duration-150">
                                <i class="fa-solid fa-user-gear mr-4 text-forest-600"></i> <span class="font-medium">Pengaturan Profil</span>
                            </a>
                        </div>

                        <div class="border-t border-gray-100">
                            <form wire:submit="logout">
                                @csrf
                                <button type="submit" class="flex w-full items-center px-6 py-4 text-sm text-red-600 hover:bg-red-50 transition duration-150">
                                    <i class="fa-solid fa-right-from-bracket mr-4"></i> <span class="font-medium">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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
