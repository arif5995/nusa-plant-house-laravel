

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
        <p class="text-gray-500 mt-1">Manage your account settings and preferences.</p>
    </div>

    <!-- Update Photo Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Profile Photo</h2>
        
        @if (session()->has('photo_updated'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('photo_updated') }}
            </div>
        @endif

        <form wire:submit="updatePhoto" class="flex flex-col md:flex-row items-center gap-6">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200 shrink-0">
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ auth()->user()->profilePhotoUrl() }}" class="w-full h-full object-cover">
                @endif
            </div>
            
            <div class="flex-grow space-y-3">
                <input type="file" wire:model="photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-forest-50 file:text-forest-700 hover:file:bg-forest-100 transition-colors" accept="image/*">
                @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="mt-4 md:mt-0 px-6 py-2.5 bg-forest-600 hover:bg-forest-700 text-white font-medium rounded-xl shadow-sm transition-colors duration-200 flex items-center justify-center min-w-[120px]">
                <span wire:loading.remove wire:target="updatePhoto">Upload Photo</span>
                <span wire:loading wire:target="updatePhoto">Uploading...</span>
            </button>
        </form>
    </div>

    <!-- Profile Info Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-1">Personal Information</h2>
        <p class="text-sm text-gray-500 mb-6">Update your account's profile information and email address.</p>

        @if (session()->has('profile_updated'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('profile_updated') }}
            </div>
        @endif

        <form wire:submit="updateProfile" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-forest-500 focus:ring-forest-500 px-4 py-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="Your full name">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" wire:model="email" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-forest-500 focus:ring-forest-500 px-4 py-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="your@email.com">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" wire:model="phone" class="w-full md:w-1/2 rounded-xl border-gray-200 shadow-sm focus:border-forest-500 focus:ring-forest-500 px-4 py-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="e.g. +628123456789">
                    @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl shadow-sm transition-colors duration-200 flex items-center justify-center min-w-[120px]">
                    <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                    <span wire:loading wire:target="updateProfile">Saving...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Password Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-1">Change Password</h2>
        <p class="text-sm text-gray-500 mb-6">Ensure your account is using a long, random password to stay secure.</p>

        @if (session()->has('password_updated'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('password_updated') }}
            </div>
        @endif

        <form wire:submit="updatePassword" class="space-y-6">
            <div class="space-y-4 max-w-md">
                <!-- Current Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" wire:model="current_password" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-forest-500 focus:ring-forest-500 px-4 py-2.5 bg-gray-50 focus:bg-white transition-colors">
                    @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" wire:model="password" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-forest-500 focus:ring-forest-500 px-4 py-2.5 bg-gray-50 focus:bg-white transition-colors">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" wire:model="password_confirmation" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-forest-500 focus:ring-forest-500 px-4 py-2.5 bg-gray-50 focus:bg-white transition-colors">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl shadow-sm transition-colors duration-200 flex items-center justify-center min-w-[120px]">
                    <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                    <span wire:loading wire:target="updatePassword">Updating...</span>
                </button>
            </div>
        </form>
    </div>

</div>
