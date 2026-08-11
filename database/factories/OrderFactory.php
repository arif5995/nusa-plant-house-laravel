<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<Order> */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'order_number' => 'ORD-' . Str::upper(Str::random(8)),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'completed', 'cancelled']),
            'subtotal' => $this->faker->randomFloat(2, 50, 500),
            'shipping_cost' => $this->faker->randomFloat(2, 0, 20),
            'total' => function (array $attributes) {
                return $attributes['subtotal'] + $attributes['shipping_cost'];
            },
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid', 'refunded']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Create related items (1-5 items)
            OrderItem::factory()->count(rand(1, 5))->create(['order_id' => $order->id]);
            // Create shipment for the order
            Shipment::factory()->create(['order_id' => $order->id]);
        });
    }
}
