<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Product;

class Home extends Component
{
    //
    public $dummyProducts = [];
    public $products = [];
    public $testimonials = [];

    public function mount()
    {

        $names = ['Siti', 'Chef Aris', 'Budi', 'Dewi', 'Eko', 'Fani', 'Gatot', 'Hesti', 'Indra', 'Jaka'];
        $roles = ['Ibu Rumah Tangga', 'Pemilik Kafe', 'Arsitek', 'Mahasiswa', 'Guru', 'Dokter', 'Fotografer', 'Designer', 'Chef', 'Developer'];
        // Mengambil semua data
        $this->products = Product::all();

        // Atau ambil dengan relasi kategori (penting untuk performa)
        $this->products = Product::with('category')->get();

        for ($i = 0; $i < 10; $i++) {
            $this->testimonials[] = [
                'name' => $names[$i],
                'role' => $roles[$i],
                'comment' => "Tanaman hias di sini benar-benar kualitas premium! Packing aman dan sampai rumah masih segar. Sangat puas belanja di sini.",
                'initials' => substr($names[$i], 0, 2)
            ];
        }
        // Simulasi 10 data produk
        // for ($i = 1; $i <= 10; $i++) {
        //     $this->products[] = [
        //         'id' => $i,
        //         'name' => "Produk Hasil Tani #$i",
        //         'category' => 'Bumbu Dapur',
        //         'price' => '35.000',
        //         'image' => 'https://picsum.photos/id/' . ($i) . '/200/300'
        //     ];
        // }
        // // Mendefinisikan data dummy tanaman
        // $this->dummyProducts = [
        //     [
        //         'id'           => 1,
        //         'name'         => 'Monstera Deliciosa Premium',
        //         'slug'         => 'monstera-deliciosa-premium',
        //         'category'     => 'Tanaman Indoor',
        //         'price'        => 125000,
        //         'image'        => 'monstera.jpg', // Pastikan nama file gambar ini ada di public/images/
        //         'is_bestseller' => true,
        //     ],
        //     [
        //         'id'           => 2,
        //         'name'         => 'Paket Kaktus Mini Hias',
        //         'slug'         => 'paket-kaktus-mini-hias',
        //         'category'     => 'Kaktus & Sukulen',
        //         'price'        => 45000,
        //         'image'        => 'kaktus.jpg',
        //         'is_bestseller' => false,
        //     ],
        //     [
        //         'id'           => 3,
        //         'name'         => 'Sirih Gading (Epipremnum)',
        //         'slug'         => 'sirih-gading-epipremnum',
        //         'category'     => 'Tanaman Gantung',
        //         'price'        => 35000,
        //         'image'        => 'sirih-gading.jpg',
        //         'is_bestseller' => false,
        //     ],
        //     [
        //         'id'           => 4,
        //         'name'         => 'Paket Kompos & Tanah Humus',
        //         'slug'         => 'paket-kompos-tanah-humus',
        //         'category'     => 'Media Tanam',
        //         'price'        => 25000,
        //         'image'        => 'media-tanam.jpg',
        //         'is_bestseller' => false,
        //     ],
        // ];
    }

    public function render()
    {
        return view('livewire.home.home');
    }
}
