<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Kategori
        $categories = ['Tanaman Indoor', 'Kaktus & Sukulen', 'Tanaman Gantung', 'Media Tanam'];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
            ]);
        }

        // 2. Data Produk Dummy
        Product::factory()->count(20)->create();
        // $products = [
        //     ['name' => 'Monstera Deliciosa', 'price' => 150000, 'stock' => 10, 'category_id' => 1, 'image' => 'https://picsum.photos/id/59/200/300'],
        //     ['name' => 'Kaktus Mini', 'price' => 25000, 'stock' => 50, 'category_id' => 2, 'image' => 'https://picsum.photos/id/60/200/300'],
        //     ['name' => 'Sirih Gading', 'price' => 35000, 'stock' => 20, 'category_id' => 3, 'image' => 'https://picsum.photos/id/61/200/300'],
        //     ['name' => 'Tanah Humus', 'price' => 15000, 'stock' => 100, 'category_id' => 4, 'image' => 'https://picsum.photos/id/62/200/300'],
        // ];

        // foreach ($products as $prod) {
        //     Product::create([
        //         'name'        => $prod['name'],
        //         'slug'        => Str::slug($prod['name']),
        //         'price'       => $prod['price'],
        //         'stock'       => $prod['stock'],
        //         'category_id' => $prod['category_id'],
        //         'image'       => $prod['image'],
        //     ]);
        // }
    }
}
