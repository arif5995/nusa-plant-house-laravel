<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        // Menghasilkan ID gambar acak antara 10 sampai 80
        $randomImageId = rand(10, 80);
        $categoryId = Category::query()->inRandomOrder()->value('id');
        if (! $categoryId) {
            $categoryId = Category::factory()->create()->id;
        }

        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug(),
            'price' => $this->faker->numberBetween(10000, 500000),
            'stock' => $this->faker->numberBetween(5, 100),
            'category_id' => $categoryId,

            // Gambar random menggunakan ID acak
            'image' => "https://picsum.photos/id/{$randomImageId}/200/300",
        ];
    }
}
