<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<Shipment> */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition()
    {
        $couriers = ['JNE', 'TIKI', 'POS Indonesia', 'DHL'];
        $courier = $this->faker->randomElement($couriers);
        $trackingNumber = strtoupper(Str::random(10));
        $status = $this->faker->randomElement(['pending', 'shipped', 'delivered', 'cancelled']);
        return [
            // 'order_id' will be set by OrderFactory afterCreating callback
            'courier' => $courier,
            'service' => $courier . ' Express',
            'tracking_number' => $trackingNumber,
            'status' => $status,
            'shipped_at' => $this->faker->dateTimeBetween('-10 days', 'now'),
            'delivered_at' => $status === 'delivered' ? $this->faker->dateTimeBetween('now', '+5 days') : null,
        ];
    }
}
