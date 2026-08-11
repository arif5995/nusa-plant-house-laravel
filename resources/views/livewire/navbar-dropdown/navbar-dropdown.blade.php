
<div x-data="{ open: false }" class="relative" @click.outside="open = false">
    @auth
        <!-- Avatar Button -->
        <button @click="open = !open" class="flex items-center justify-center w-10 h-10 rounded-full bg-forest-100 text-forest-800 font-bold border-2 border-transparent hover:border-forest-600 focus:outline-none transition-all duration-300">
            @if(auth()->user()->profile_photo_path)
                <img src="{{ auth()->user()->profilePhotoUrl() }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
            @else
                <span class="text-sm">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
            @endif
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-3 w-56 bg-white/80 backdrop-blur-xl shadow-xl rounded-2xl border border-gray-100 z-50 overflow-hidden"
             style="display: none;">
            
            <div class="px-4 py-3 border-b border-gray-100/50">
                <p class="text-xs text-gray-500 mb-1">Masuk sebagai</p>
                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
            </div>

            <div class="py-1">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-forest-50 hover:text-forest-600 transition-colors duration-200">
                    Dashboard
                </a>
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-forest-50 hover:text-forest-600 transition-colors duration-200">
                    Profile Settings
                </a>
            </div>

            <div class="py-1 border-t border-gray-100/50">
                <button wire:click="logout" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                    Logout
                </button>
            </div>
        </div>
    @endauth
</div>