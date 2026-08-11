<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Services\OrderService;
use App\Services\ShipmentService;
use Illuminate\Support\Facades\Auth;

class ShippingDetail extends Component
{
    public $orderId;
    public $order;
    public $shipment;

    public function mount($order)
    {
        $this->orderId = $order;
        $orderService = new OrderService();
        $this->order = $orderService->getOrderDetail($this->orderId);
        $shipmentService = new ShipmentService();
        $this->shipment = $shipmentService->getShipmentByOrder($this->order);
    }

    public function render()
    {
        return view('livewire.dashboard.shipping-detail', [
            'order' => $this->order,
            'shipment' => $this->shipment,
        ]);
    }
}
