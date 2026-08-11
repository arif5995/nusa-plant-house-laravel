<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class OrderService
{
    /**
     * Get paginated orders for the authenticated user with optional status filter.
     */
    public function getUserOrders(string $status = null, int $perPage = 10)
    {
        $query = Order::where('user_id', Auth::id())
                      ->orderByDesc('created_at');
        if ($status) {
            $query->where('status', $status);
        }
        return $query->paginate($perPage);
    }

    /**
     * Retrieve a single order belonging to the authenticated user.
     */
    public function getOrderDetail(int $orderId)
    {
        return Order::where('user_id', Auth::id())
                    ->with(['items', 'shipment'])
                    ->findOrFail($orderId);
    }

    /**
     * Apply filter to a query builder based on status.
     */
    public function applyFilter(Builder $query, string $status)
    {
        return $query->where('status', $status);
    }
}
