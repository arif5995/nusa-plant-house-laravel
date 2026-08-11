<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\Order;

class ShipmentService
{
    /**
     * Get the shipment associated with an order.
     */
    public function getShipmentByOrder(Order $order)
    {
        return $order->shipment;
    }

    /**
     * Generate a tracking URL for a given courier and tracking number.
     * Supports common couriers with realistic URL patterns.
     */
    public function generateTrackingUrl(string $courier, string $trackingNumber): string
    {
        switch (strtolower($courier)) {
            case 'jne':
                return "https://www.jne.co.id/id/tracking/trace?awb={$trackingNumber}";
            case 'tiki':
                return "https://tiki.id/track/{$trackingNumber}";
            case 'pos indonesia':
            case 'pos':
                return "https://www.posindonesia.co.id/track/{$trackingNumber}";
            case 'dhl':
                return "https://www.dhl.com/en/express/tracking.html?AWB={$trackingNumber}";
            default:
                // Fallback to a generic placeholder URL
                return "https://www.example.com/track/{$courier}/{$trackingNumber}";
        }
    }
}
