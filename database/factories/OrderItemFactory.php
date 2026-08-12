<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<OrderItem> */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        $productId = Product::query()->inRandomOrder()->value('id');
        if (! $productId) {
            $productId = Product::factory()->create()->id;
        }

        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->randomFloat(2, 5, 200);

        return [
            // order_id will be assigned in OrderFactory afterCreating callback
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price,
            'product_name' => $this->faker->word,
            'subtotal' => $price * $quantity,
        ];
    }
}
