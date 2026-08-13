<?php

namespace App\Livewire\DetailProduct;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Http\Request;

class DetailProduct extends Component
{
    public $product;
    public $quantity = 1;

    // Menerima data product saat komponen dipanggil
    public function mount($id, Request $request)
    {
        $this->product = Product::findOrFail($id);
        $this->quantity = $request->query('qty', 1);
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        // 1. Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // 2. Cek apakah produk sudah ada di keranjang
        if (isset($cart[$this->product->id])) {
            // LOGIKA EDIT: Timpa dengan qty yang baru dipilih di halaman detail
            $cart[$this->product->id]['quantity'] = $this->quantity;
            session()->flash('success', 'Jumlah ' . $this->product->name . ' diperbarui di keranjang!');
        } else {
            // LOGIKA INSERT: Tambah baru
            $cart[$this->product->id] = [
                'id'       => $this->product->id,
                'name'     => $this->product->name,
                'price'    => $this->product->price,
                'quantity' => $this->quantity, // Ambil dari property $this->quantity
                'image'    => $this->product->image,
                'slug'     => $this->product->slug,
                'weight'   => $this->product->weight ?? 500,
            ];
            session()->flash('success', $this->product->name . ' berhasil ditambahkan ke keranjang!');
        }

        // 3. Simpan kembali ke session
        session()->put('cart', $cart);

        // 4. Update badge navbar
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.detail-product.detail-product');
    }
}
