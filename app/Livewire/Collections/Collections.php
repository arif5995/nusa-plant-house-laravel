<?php

namespace App\Livewire\Collections;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Collections extends Component
{
    public $search = '';
    public $selectedCategory = '';
    public $product;

    // Tangkap parameter 'kategori' dari URL
    public function mount($kategori = null)
    {
        if ($kategori) {
            $this->selectedCategory = $kategori;
        }
    }

    #[On('add-to-cart')]
    public function addToCart($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu.');
        }
        // 1. Ambil produk dari database
        $product = Product::findOrFail($productId);

        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan!');
            return;
        }

        // 2. Ambil isi keranjang saat ini (default array kosong)
        $cart = session()->get('cart', []);

        // 3. Cek apakah produk sudah ada di keranjang
        if (isset($cart[$productId])) {
            // Jika ada, tambah jumlahnya (quantity)
            $cart[$productId]['quantity'] += 1;
        } else {
            // Jika belum ada, buat item baru
            $cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => 1,
                'image'    => $product->image,
            ];
        }

        // 4. Simpan kembali ke session
        session()->put('cart', $cart);

        // 5. Dispatch event agar navbar/badge terupdate secara real-time
        $this->dispatch('cart-updated');

        // 6. Feedback ke user
        session()->flash('success', $product->name . ' berhasil ditambahkan!');
    }

    public function render()
    {
        // Data Dummy Kategori
        $categories = Category::all()->pluck('name')->toArray();

        // Filter menggunakan Collection method (jauh lebih bersih)
        $products = Product::query()
            ->with('category') // Eager loading untuk performa

            // 2. Filter Pencarian (Cari berdasarkan nama produk)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })

            // 3. Filter Kategori (Cari berdasarkan nama kategori yang dipilih)
            ->when($this->selectedCategory, function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('name', $this->selectedCategory);
                });
            })

            ->get();

        return view('livewire.collections.collections', [
            'products'   => $products,
            'categories' => $categories,
        ]);
    }
}
