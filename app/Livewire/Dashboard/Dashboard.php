<?php

namespace App\Livewire\Dashboard;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalOrders = 0;
    public $totalUnpaid = 0;
    public $totalSpent = 'Rp 0';
    public $recentOrders = [];

    public function mount()
    {
        $userId = Auth::id();

        $this->totalOrders = Order::query()->where('user_id', $userId)->count();

        $this->totalUnpaid = Order::query()
            ->where('user_id', $userId)
            ->where('payment_status', 'unpaid')
            ->count();

        $totalSpentRaw = Order::query()
            ->where('user_id', $userId)
            ->where('payment_status', 'paid')
            ->sum('total');

        $this->totalSpent = 'Rp ' . number_format($totalSpentRaw, 0, ',', '.');

        $this->recentOrders = Order::query()
            ->where('user_id', $userId)
            ->latest('created_at')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
}
