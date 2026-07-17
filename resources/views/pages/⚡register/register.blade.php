<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Akun Baru</h2>

        <form wire:submit="register">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" wire:model="name"
                    class="w-full p-3 border rounded-xl mt-1 focus:ring-2 focus:ring-forest-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email"
                    class="w-full p-3 border rounded-xl mt-1 focus:ring-2 focus:ring-forest-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" wire:model="password"
                    class="w-full p-3 border rounded-xl mt-1 focus:ring-2 focus:ring-forest-500">
                @error('password')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" wire:model="password_confirmation"
                    class="w-full p-3 border rounded-xl mt-1 focus:ring-2 focus:ring-forest-500">
            </div>

            <button type="submit"
                class="w-full bg-forest-600 text-white py-3 rounded-xl font-bold hover:bg-forest-700 transition">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-forest-600 font-bold hover:underline">Log
                In</a>
        </p>
    </div>
</div>
