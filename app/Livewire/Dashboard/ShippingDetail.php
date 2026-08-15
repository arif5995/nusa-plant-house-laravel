<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Services\OrderService;
use App\Services\ShipmentService;
use Livewire\WithFileUploads;

class ShippingDetail extends Component
{
    use WithFileUploads;

    public $orderId;
    public $order;
    public $shipment;
    public $trackingUrl = null;

    public $editMode = false;
    public $recipientName;
    public $recipientPhone;
    public $shippingAddress;
    public $city;
    public $postalCode;

    public $paymentReceipt;

    protected OrderService $orderService;
    protected ShipmentService $shipmentService;

    public function boot(OrderService $orderService, ShipmentService $shipmentService)
    {
        $this->orderService = $orderService;
        $this->shipmentService = $shipmentService;
    }

    public function mount($order)
    {
        $this->orderId = $order;
        $this->loadOrder();
    }

    protected function loadOrder()
    {
        $this->order = $this->orderService->getOrderDetail($this->orderId);
        $this->shipment = $this->shipmentService->getShipmentByOrder($this->order);

        if ($this->shipment && $this->shipment->tracking_number) {
            $this->trackingUrl = $this->shipmentService->generateTrackingUrl(
                $this->shipment->courier,
                $this->shipment->tracking_number
            );
        }
    }

    public function startEdit()
    {
        if ($this->order->status !== 'pending') {
            return;
        }

        $shippingInfo = $this->orderService->getShippingInfo($this->orderId);

        $this->recipientName   = $shippingInfo['recipient_name'];
        $this->recipientPhone  = $shippingInfo['recipient_phone'];
        $this->shippingAddress = $shippingInfo['shipping_address'];
        $this->city             = $shippingInfo['city'];
        $this->postalCode       = $shippingInfo['postal_code'];
        $this->editMode = true;
    }

    public function cancelEdit()
    {
        $this->editMode = false;
        $this->resetErrorBag();
    }

    public function saveShippingInfo()
    {
        $this->validate([
            'recipientName'   => 'required|string|max:255',
            'recipientPhone'  => 'required|string|max:20',
            'shippingAddress' => 'required|string',
        ]);

        $success = $this->orderService->updateShippingInfo($this->orderId, [
            'recipient_name'   => $this->recipientName,
            'recipient_phone'  => $this->recipientPhone,
            'shipping_address' => $this->shippingAddress,
            'city'              => $this->city,
            'postal_code'       => $this->postalCode,
        ]);

        if (! $success) {
            session()->flash('error', 'Pesanan sudah diproses, tidak bisa diubah lagi.');
            $this->editMode = false;
            $this->loadOrder();
            return;
        }

        $this->editMode = false;
        $this->loadOrder();
        session()->flash('success', 'Info pengiriman berhasil diperbarui.');
    }

    public function uploadReceipt()
    {
        $this->validate([
            'paymentReceipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $fileContents = file_get_contents($this->paymentReceipt->getRealPath());
        $mimeType     = $this->paymentReceipt->getMimeType();
        $base64String = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

        $success = $this->orderService->attachPaymentReceipt($this->orderId, $base64String);

        if (! $success) {
            session()->flash('error', 'Pesanan ini sudah dibayar, tidak perlu upload bukti transfer lagi.');
        } else {
            session()->flash('success', 'Bukti transfer berhasil diupload, menunggu verifikasi toko.');
        }

        $this->paymentReceipt = null;
        $this->loadOrder();
    }

    public function confirmCancel()
    {
        $success = $this->orderService->cancelOrder($this->orderId);

        if (! $success) {
            session()->flash('error', 'Pesanan ini sudah diproses, tidak bisa dibatalkan lagi.');
        } else {
            session()->flash('success', 'Pesanan berhasil dibatalkan.');
        }

        $this->loadOrder();
    }

    public function render()
    {
        return view('livewire.dashboard.shipping-detail');
    }
}
