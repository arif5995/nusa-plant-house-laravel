<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    public static function getShippingCost($origin, $destination, $weight, $courier)
    {
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.key')
        ])->post(config('services.rajaongkir.url') . 'cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier
        ]);

        return $response->json();
    }
}
