<?php

<div class="p-6 bg-gray-100 dark:bg-gray-800 rounded-lg shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Riwayat Transaksi</h2>
        <select wire:model="statusFilter" class="block w-48 mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div wire:loading class="text-center py-6">
        <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <p class="mt-2 text-gray-600 dark:text-gray-300">Memuat data...</p>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Tidak ada transaksi.</p>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($orders as $order)
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 hover:shadow-lg transition-shadow">
                    <h3 class="font-medium text-gray-900 dark:text-gray-100">#{{ $order->order_number }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Status: {{ ucfirst($order->status) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Total: ${{ number_format($order->total, 2) }}</p>
                    <a href="{{ route('dashboard.transactions.detail', ['order' => $order->id]) }}" class="mt-2 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
