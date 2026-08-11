<div class="min-h-screen flex flex-col md:flex-row">
    <div class="hidden md:flex flex-col w-full md:w-1/2 bg-forest-600 items-center justify-center text-white p-10">
        <div class="text-center">
            <div class="mb-6">
                <i class="fa-solid fa-leaf text-9xl"></i>
            </div>
            <h1 class="text-5xl font-bold mb-2">NusaPlants</h1>
            <p class="text-xl text-forest-100">Lebih Segar, Lebih Mudah</p>
        </div>
    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Log In</h2>

            <!-- Ubah bagian ini -->
            <form wire:submit="login">
                @csrf <!-- Masih diperlukan untuk keamanan Laravel -->
                
                <input type="text" wire:model="email" placeholder="Email"
                    class="w-full p-3 border rounded-xl mb-4 ...">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                <input type="password" wire:model="password" placeholder="Password"
                    class="w-full p-3 border rounded-xl mb-2 ...">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                <button type="submit"
                        class="w-full bg-forest-600 text-white py-3 ...">
                    Log In
                </button>
            </form>

            <div class="flex items-center gap-2 mb-6">
                <div class="h-px bg-gray-200 flex-1"></div>
                <span class="text-gray-400 text-xs">ATAU</span>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button
                    class="border border-gray-200 py-2.5 rounded-xl text-sm flex items-center justify-center gap-2 hover:bg-gray-50">
                    Facebook
                </button>
                <a href="{{ route('auth.google') }}"
                    class="border border-gray-200 py-2.5 rounded-xl text-sm flex items-center justify-center gap-2 hover:bg-gray-50">
                    Google
                </a>
            </div>

            <p class="mt-6 text-center text-sm text-gray-600">
                Baru di NusaPlants? <a href="{{ route('register') }}"
                    class="text-forest-600 font-bold hover:underline">Daftar</a>
            </p>
        </div>
    </div>
</div>
