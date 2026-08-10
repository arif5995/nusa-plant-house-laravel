<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\RajaOngkirService;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    //
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

    public function nextStep()
    {
        if ($this->step == 2 && $this->shippingType === 'export') {
            $this->validate([
                'fullName' => 'required',
                'shippingAddress' => 'required',
                'agreeTerms' => 'accepted',
            ]);
        }

        $this->step++;
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
        return $this->shippingType === 'export' ? 500000 : 15000;
    }


    public function checkout()
    {
        // 1. Validasi bukti transfer
        $this->validate([
            'paymentReceipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2 MB
        ]);

        // 2. Konversi File ke Base64 String
        $fileContents = file_get_contents($this->paymentReceipt->getRealPath());
        $mimeType     = $this->paymentReceipt->getMimeType();
        
        // Format standar Base64 Data URI (opsional, tapi memudahkan saat ditampilkan di tag <img>)
        $base64String = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

        $cart = session()->get('cart');

        // 1. Simpan ke tabel Orders
        $order = Order::create([
            'customer_name' => $this->fullName,
            'phone'         => $this->phoneNumber,
            'shipping_type' => $this->shippingType,
            'address' => $this->shippingAddress,
            'total_price'   => $this->subtotal + $this->shippingCost,
            'payment_receipt' => $base64String, // Simpan string Base64 ke DB
            'status'        => 'pending',
        ]);

        // 2. Simpan ke tabel OrderItems
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $id,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }

        // 3. Bersihkan Keranjang
        session()->forget('cart');

        // 4. Buat string pesan WhatsApp
        $message = "Halo, saya ingin memesan produk. ID Pesanan: #{$order->id}%0A%0A";
        $message .= "Nama: {$this->fullName}%0A";
        $message .= "Total: Rp " . number_format($this->subtotal + $this->shippingCost, 0, ',', '.');

        // 5. Bersihkan keranjang
        session()->forget('cart');

        // 6. Redirect ke WhatsApp
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

    public function updateShippingCost()
    {
        $costData = RajaOngkirService::getShippingCost(
            '151', // ID Kota Asal (Contoh: Kota asal Anda)
            $this->destinationCityId, // ID Kota Tujuan (Dapat dari form)
            $this->totalWeight, // Total berat barang
            'jne' // Kurir
        );

        // Ambil harga dari response JSON dan simpan ke properti $shippingCost
        $this->shippingCost = $costData['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'];
    }

    public function render()
    {
        return view('pages.⚡cart.cart');
    }
};
