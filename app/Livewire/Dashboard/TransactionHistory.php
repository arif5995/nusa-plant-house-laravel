<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\OrderService;

class TransactionHistory extends Component
{
    use WithPagination;

    public $statusFilter = null;
    public $perPage = 10;
    protected $paginationTheme = 'tailwind';

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orderService = new OrderService();
        $orders = $orderService->getUserOrders($this->statusFilter, $this->perPage);
        return view('livewire.dashboard.transaction-history', [
            'orders' => $orders,
        ]);
    }
}
