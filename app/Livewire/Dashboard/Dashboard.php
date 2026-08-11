<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public $totalOrders = 156;
    public $totalProducts = 42;
    public $totalTransactions = 'Rp 12.500.000';
    public $recentActivities = [];

    public function mount()
    {
        // Dummy data for recent activities
        $this->recentActivities = [
            ['id' => 1, 'action' => 'Order #1023 Placed', 'date' => '2 hours ago', 'status' => 'Pending'],
            ['id' => 2, 'action' => 'Payment Received for #1022', 'date' => '5 hours ago', 'status' => 'Completed'],
            ['id' => 3, 'action' => 'New Product Added: Monstera Deliciosa', 'date' => '1 day ago', 'status' => 'Completed'],
            ['id' => 4, 'action' => 'Order #1021 Shipped', 'date' => '2 days ago', 'status' => 'Completed'],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
}
