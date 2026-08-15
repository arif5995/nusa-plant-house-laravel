<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fa-solid fa-receipt text-forest-600"></i>
            Riwayat Transaksi
        </h1>
        <p class="text-sm text-gray-500 mt-1">Semua pesanan yang pernah kamu buat.</p>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 mb-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-grow">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:13px; pointer-events:none;"></i>
            <input wire:model.live.debounce.500ms="search" type="text" placeholder="Cari nomor pesanan..."
                class="w-full py-3 bg-gray-50 border border-transparent rounded-xl text-sm text-gray-800
                    focus:bg-white focus:ring-2 focus:ring-forest-600 focus:border-forest-600 outline-none"
                style="padding-left:42px; padding-right:16px;">
        </div>

        <div class="relative sm:w-56" style="position:relative;">
            <select wire:model.live="statusFilter"
                class="w-full py-3 bg-gray-50 border border-transparent rounded-xl text-sm text-gray-800
                    focus:bg-white focus:ring-2 focus:ring-forest-600 focus:border-forest-600 outline-none cursor-pointer"
                style="padding-left:16px; padding-right:36px; -webkit-appearance:none; -moz-appearance:none; appearance:none; background-image:none;">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <i class="fa-solid fa-chevron-down" style="position:absolute; right:16px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:11px; pointer-events:none;"></i>
        </div>
    </div>

    {{-- Loading --}}
    <div wire:loading class="text-center py-10">
        <svg class="animate-spin h-8 w-8 text-forest-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <p class="mt-3 text-sm text-gray-500">Memuat data...</p>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <i class="fa-regular fa-folder-open text-4xl text-gray-300 mb-3"></i>
            @if ($statusFilter || $search)
                <p class="text-gray-500 text-sm">Tidak ada transaksi yang cocok dengan pencarian/filter ini.</p>
                <button wire:click="$set('statusFilter', ''); $set('search', '')" class="mt-3 text-sm font-semibold text-forest-600 hover:underline">
                    Reset filter
                </button>
            @else
                <p class="text-gray-500 text-sm">Kamu belum punya transaksi apa pun.</p>
                <a href="{{ route('products.index') }}" class="mt-3 inline-block text-sm font-semibold text-forest-600 hover:underline">
                    Mulai belanja &rarr;
                </a>
            @endif
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($orders as $order)
                <a href="{{ route('dashboard.transactions.detail', ['order' => $order->id]) }}"
                    class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-forest-200 transition-all">

                    <div class="flex items-start justify-between mb-3" style="gap:8px;">
                        <h3 class="font-bold text-gray-900 text-sm">#{{ $order->order_number }}</h3>
                        <span class="inline-flex items-center rounded-full text-xs font-bold"
                            style="padding:4px 10px; white-space:nowrap;
                                background-color: {{ $order->status === 'completed' ? '#dcfce7' : ($order->status === 'cancelled' ? '#fee2e2' : (in_array($order->status, ['shipped','processing']) ? '#dbeafe' : '#fef3c7')) }};
                                color: {{ $order->status === 'completed' ? '#166534' : ($order->status === 'cancelled' ? '#991b1b' : (in_array($order->status, ['shipped','processing']) ? '#1e40af' : '#92400e')) }};">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <p class="text-xs text-gray-400 mb-3">
                        <i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </p>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        <span class="text-xs font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                            <i class="fa-solid {{ $order->payment_status === 'paid' ? 'fa-circle-check' : 'fa-clock' }}" style="margin-right:4px;"></i>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                        <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>