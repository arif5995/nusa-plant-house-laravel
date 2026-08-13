<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.rajaongkir.key');
        $this->baseUrl = config('services.rajaongkir.base_url');
    }

    /**
     * Cari kota/kecamatan tujuan berdasarkan keyword.
     * Return: [['id' => ..., 'label' => ...], ...]
     */
    public function searchDestination(string $keyword): array
    {
        return Cache::remember("rajaongkir:search:{$keyword}", now()->addDay(), function () use ($keyword) {
            try {
                $response = Http::withHeaders(['key' => $this->apiKey])
                    ->get("{$this->baseUrl}/destination/domestic-destination", [
                        'search' => $keyword,
                        'limit'  => 10,
                        'offset' => 0,
                    ]);

                if (! $response->successful()) {
                    Log::error('RajaOngkir searchDestination failed', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                    return [];
                }

                return collect($response->json('data', []))->map(fn($item) => [
                    'id'    => $item['id'],
                    'label' => $item['label'],
                ])->toArray();
            } catch (\Throwable $e) {
                Log::error('RajaOngkir searchDestination exception', ['message' => $e->getMessage()]);
                return [];
            }
        });
    }

    /**
     * Hitung ongkir ke tujuan tertentu.
     * Return: [['courier_code' => ..., 'courier_name' => ..., 'service' => ..., 'cost' => ..., 'etd' => ...], ...]
     */
    public function calculateCost(int|string $destinationId, int $weightGram, array $couriers = ['jne', 'jnt', 'sicepat']): array
    {
        $weightGram = max($weightGram, 100); // minimal 100 gram sesuai ketentuan API
        $courierStr = implode(':', $couriers);
        $cacheKey   = "rajaongkir:cost:{$destinationId}:{$weightGram}:{$courierStr}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($destinationId, $weightGram, $courierStr) {
            try {
                $response = Http::withHeaders(['key' => $this->apiKey])
                    ->asForm()
                    ->post("{$this->baseUrl}/calculate/domestic-cost", [
                        'origin'      => config('services.rajaongkir.origin_id'),
                        'destination' => $destinationId,
                        'weight'      => $weightGram,
                        'courier'     => $courierStr,
                        'price'       => 'lowest',
                    ]);

                if (! $response->successful()) {
                    Log::error('RajaOngkir calculateCost failed', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                    return [];
                }

                return collect($response->json('data', []))->map(fn($item) => [
                    'courier_code' => $item['code'],
                    'courier_name' => $item['name'],
                    'service'      => $item['service'],
                    'cost'         => $item['cost'],
                    'etd'          => $item['etd'],
                ])->toArray();
            } catch (\Throwable $e) {
                Log::error('RajaOngkir calculateCost exception', ['message' => $e->getMessage()]);
                return [];
            }
        });
    }
}
