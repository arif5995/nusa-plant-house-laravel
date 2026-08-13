<?php

namespace App\Livewire\Cart;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\RajaOngkirService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Cart extends Component
{
    use WithFileUploads;
    public $cart = [];
    public $step = 1; // 1 = Keranjang, 2 = Pengiriman, 3 = Konfirmasi
    public $shippingType = 'local';
    public $shippingCost = 0;
    // Data untuk Ekspor
    public $country = '';
    public $address = '';

    // Tambahkan properti ini di class CartPage
    public $fullName = '';
    public $email = '';
    public $phoneNumber = '';
    public $city = '';
    public $postalCode = '';
    public $shippingAddress = '';
    public $agreeTerms = false; // Checklist untuk sertifikat phytosanitary

    // 3. Properti untuk menampung file upload bukti transfer
    public $paymentReceipt;

    protected RajaOngkirService $rajaOngkir;

    // ===== Properti pencarian tujuan =====
    public $destinationSearch = '';
    public $destinationResults = [];
    public $destinationId = null;
    public $destinationLabel = '';

    // ===== Properti kurir =====
    public $couriers = [];
    public $selectedCourier = null;

    public function nextStep()
    {
        if ($this->step == 2) {
            if ($this->shippingType === 'export') {
                $this->validate([
                    'fullName' => 'required',
                    'shippingAddress' => 'required',
                    'agreeTerms' => 'accepted',
                ]);
            } else {
                $this->validate([
                    'fullName' => 'required',
                    'phoneNumber' => 'required',
                    'shippingAddress' => 'required',
                    'destinationId' => 'required',
                ], [
                    'destinationId.required' => 'Silakan pilih kota/kecamatan tujuan pengiriman.',
                ]);
            }
        }

        $this->step++;

        if ($this->step == 3 && $this->shippingType === 'local' && $this->destinationId) {
            $this->loadCouriers();
        }
    }
    public function prevStep()
    {
        $this->step--;
    }

    public function redirectToLogin()
    {
        return redirect()->route('login');
    }

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    public function getTotalPriceProperty()
    {
        return $this->subtotal + $this->shippingCost;
    }


    public function getSubtotalProperty()
    {
        return array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $this->cart));
    }

    public function getShippingCostProperty()
    {
        if ($this->shippingType === 'export') {
            return 500000;
        }

        return $this->selectedCourier['cost'] ?? 0;
    }


    public function checkout()
    {
        if ($this->shippingType === 'local' && empty($this->selectedCourier)) {
            $this->addError('selectedCourier', 'Silakan pilih kurir pengiriman terlebih dahulu.');
            return;
        }

        // Validasi bukti transfer
        $this->validate([
            'paymentReceipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $fileContents = file_get_contents($this->paymentReceipt->getRealPath());
        $mimeType     = $this->paymentReceipt->getMimeType();
        $base64String = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

        $cart = session()->get('cart', []);

        $order = Order::create([
            'user_id'         => auth()->id(),
            'order_number'    => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(uniqid()),
            'status'          => 'pending',
            'subtotal'        => $this->subtotal,
            'shipping_cost'   => $this->shippingCost,
            'total'           => $this->subtotal + $this->shippingCost,
            'payment_status'  => 'unpaid',
            'payment_receipt' => $base64String,
        ]);

        foreach ($cart as $id => $item) {
            $subtotalItem = $item['price'] * $item['quantity'];

            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $id,
                'product_name' => $item['name'] ?? ($item['title'] ?? null),
                'quantity'     => $item['quantity'],
                'price'        => $item['price'],
                'subtotal'     => $subtotalItem,
            ]);
        }

        session()->forget('cart');

        $message  = "Halo, saya ingin memesan produk. No. Pesanan: {$order->order_number}%0A%0A";
        $message .= "Nama: {$this->fullName}%0A";
        $message .= "Total: Rp " . number_format($this->subtotal + $this->shippingCost, 0, ',', '.');

        return redirect()->to("https://wa.me/6281234567890?text=" . $message);
    }

    public function increaseQuantity($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
            session()->put('cart', $cart);
            $this->cart = $cart;
        }
        $this->dispatch('cart-updated');
    }

    public function decreaseQuantity($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id]) && $cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity'] -= 1;
            session()->put('cart', $cart);
        } elseif (isset($cart[$id]) && $cart[$id]['quantity'] <= 1) {
            // Opsional: Jika quantity 1 dikurangi, hapus item dari cart
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        $this->cart = $cart;
        $this->dispatch('cart-updated');
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        $this->cart = $cart;
        $this->dispatch('cart-updated'); // Update badge di navbar
    }

    // Removed old updateShippingCost() - replaced by RajaOngkirService-backed methods below

    public function boot(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function updatedShippingType()
    {
        $this->destinationId = null;
        $this->destinationLabel = '';
        $this->destinationSearch = '';
        $this->destinationResults = [];
        $this->couriers = [];
        $this->selectedCourier = null;
    }

    public function updatedDestinationSearch($value)
    {
        if (strlen($value) < 3) {
            $this->destinationResults = [];
            return;
        }

        $this->destinationResults = $this->rajaOngkir->searchDestination($value);
    }

    public function selectDestination($id, $label)
    {
        $this->destinationId = $id;
        $this->destinationLabel = $label;
        $this->destinationSearch = $label;
        $this->destinationResults = [];
        $this->couriers = [];
        $this->selectedCourier = null;
    }

    public function getTotalWeightProperty()
    {
        $weight = array_sum(array_map(
            fn($item) => ($item['weight'] ?? 500) * $item['quantity'],
            $this->cart
        ));

        return max($weight, 100);
    }

    public function loadCouriers()
    {
        if (empty($this->cart) || ! $this->destinationId) {
            return;
        }

        $this->couriers = $this->rajaOngkir->calculateCost(
            $this->destinationId,
            $this->totalWeight
        );
    }

    public function chooseCourier($index)
    {
        $this->selectedCourier = $this->couriers[$index] ?? null;
    }

    public function render()
    {
        return view('livewire.cart.cart');
    }
}
