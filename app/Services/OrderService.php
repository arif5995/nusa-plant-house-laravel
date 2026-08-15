<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class OrderService
{
    /**
     * Ambil info pengiriman untuk sebuah order, dengan fallback ke profil user
     * kalau order belum punya data penerima sendiri (mis. order lama / belum diedit).
     */
    public function getShippingInfo(int $orderId): array
    {
        $order = Order::query()->where('user_id', Auth::id())
            ->with('user')
            ->findOrFail($orderId);

        return [
            'recipient_name'   => $order->recipient_name ?: $order->user->name,
            'recipient_phone'  => $order->recipient_phone ?: $order->user->phone,
            'shipping_address' => $order->shipping_address,
            'city'              => $order->city,
            'postal_code'       => $order->postal_code,
        ];
    }

    /**
     * Get paginated orders for the authenticated user with optional status filter.
     */
    public function getUserOrders(?string $status = null, int $perPage = 10, ?string $search = null)
    {
        $query = Order::query()->where('user_id', Auth::id())
            ->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where('order_number', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Batalkan order milik user yang login. Hanya bisa jika status masih 'pending'.
     */
    public function cancelOrder(int $orderId): bool
    {
        $order = Order::query()->where('user_id', Auth::id())->findOrFail($orderId);

        if ($order->status !== 'pending') {
            return false;
        }

        $order->update(['status' => 'cancelled']);

        return true;
    }

    /**
     * Update data pengiriman (nama, no HP, alamat) milik user yang login.
     * Hanya bisa jika status masih 'pending'.
     */
    public function updateShippingInfo(int $orderId, array $data): bool
    {
        $order = Order::query()->where('user_id', Auth::id())->findOrFail($orderId);

        if ($order->status !== 'pending') {
            return false;
        }

        $order->update([
            'recipient_name'   => $data['recipient_name'],
            'recipient_phone'  => $data['recipient_phone'],
            'shipping_address' => $data['shipping_address'],
            'city'              => $data['city'] ?? null,
            'postal_code'       => $data['postal_code'] ?? null,
        ]);

        return true;
    }

    /**
     * Simpan bukti transfer (base64) ke order milik user yang login.
     * Hanya bisa jika payment_status masih 'unpaid'.
     */
    public function attachPaymentReceipt(int $orderId, string $base64Receipt): bool
    {
        $order = Order::query()->where('user_id', Auth::id())->findOrFail($orderId);

        if ($order->payment_status !== 'unpaid') {
            return false;
        }

        $order->update(['payment_receipt' => $base64Receipt]);

        return true;
    }

    /**
     * Retrieve a single order belonging to the authenticated user.
     */
    public function getOrderDetail(int $orderId)
    {
        return Order::query()->where('user_id', Auth::id())
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
