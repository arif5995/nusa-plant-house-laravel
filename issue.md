# Implement GitHub Issue #3: Dashboard Riwayat Transaksi & Detail Pengiriman

## Goal Description

Implement the full feature set described in issue #3 for the **nusa-plant-house-laravel** project. This includes:
- Database migrations for `orders`, `order_items`, and `shipments`.
- Eloquent models with proper relationships.
- Service classes (`OrderService`, `ShipmentService`) handling business logic.
- Livewire components (`TransactionHistory`, `ShippingDetail`) with Blade views.
- Routes, UI design, filtering, pagination, empty state, security checks, and testing.

## User Review Required

> [!IMPORTANT]
> The implementation will modify existing `Order` model fields and add new models/migrations. Ensure these changes align with any existing data or other parts of the application.
>
> > **Breaking Change**: The current `Order` model uses fields like `customer_name`, `phone`, `shipping_type`, etc. The issue requires a different schema (e.g., `user_id`, `order_number`, `status`, `subtotal`, `shipping_cost`, `total`, `payment_status`). Confirm if we should replace the existing schema entirely or migrate/extend it.
>
> > **Design Confirmation**: The UI design follows the project's existing Tailwind design system. If there are custom color or component preferences beyond the specification, let us know.

## Open Questions

> [!QUESTION]
> 1. Should the existing `orders` table be dropped/recreated to match the new field list, or should we add missing columns to it?
>
> 2. Do you already have `OrderItem` and `Shipment` model files (or should we create them from scratch)?
>
> 3. For the `ShipmentService`, do you need a helper to generate a tracking URL based on the courier (e.g., JNE) or a placeholder URL is sufficient?
>
> 4. Any specific naming conventions for route names beyond those already suggested?
>
> 5. Do you prefer to use Laravel factories/seeder for initial data, or will you populate manually?
>
> 6. Should the Livewire components be placed under `app/Livewire/Dashboard/` as per the spec (already intended), and Blade views under `resources/views/livewire/dashboard/`?

## Proposed Changes

---
### Database Migrations

- **[NEW] database/migrations/xxxx_xx_xx_create_orders_table.php** – create `orders` table with fields: `id`, `user_id`, `order_number`, `status`, `subtotal`, `shipping_cost`, `total`, `payment_status`, timestamps.
- **[NEW] database/migrations/xxxx_xx_xx_create_order_items_table.php** – create `order_items` table with fields: `id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `subtotal`, timestamps.
- **[NEW] database/migrations/xxxx_xx_xx_create_shipments_table.php** – create `shipments` table with fields: `id`, `order_id`, `courier`, `service`, `tracking_number`, `status`, `shipped_at`, `delivered_at`, timestamps.
- **[DELETE] database/migrations/2026_08_07_064910_add_profile_fields_to_users_table.php** – (if unrelated to this feature).

---
### Models

- **[MODIFY] app/Models/Order.php** – replace fillable fields with the new schema, add relationships `items()` and `shipment()`.
- **[NEW] app/Models/OrderItem.php** – model with `belongsTo Order` relationship.
- **[NEW] app/Models/Shipment.php** – model with `belongsTo Order` relationship.

---
### Services

- **[NEW] app/Services/OrderService.php** – methods:
  - `getUserOrders(User $user, $filter = null, $perPage = 10)`
  - `getOrderDetail(User $user, $orderId)`
  - `applyFilter($query, $status)`
- **[NEW] app/Services/ShipmentService.php** – methods:
  - `getShipmentByOrder(Order $order)`
  - `generateTrackingUrl(string $courier, string $trackingNumber)` (placeholder logic).

---
### Livewire Components & Views

- **[NEW] app/Livewire/Dashboard/TransactionHistory.php** – loads paginated orders via `OrderService`, supports filter selection, emits loading state.
- **[NEW] resources/views/livewire/dashboard/transaction-history.blade.php** – UI showing cards/grid, filter dropdown, pagination, empty state, loading spinner.
- **[NEW] app/Livewire/Dashboard/ShippingDetail.php** – loads order + shipment details, displays timeline, tracking button.
- **[NEW] resources/views/livewire/dashboard/shipping-detail.blade.php** – UI for detailed view with timeline, address, tracking link.

---
### Routes

- **[MODIFY] routes/web.php** – add routes inside `auth` middleware group:
```php
Route::get('/dashboard/transactions', \App\Livewire\Dashboard\TransactionHistory::class)->name('dashboard.transactions');
Route::get('/dashboard/transactions/{order}', \App\Livewire\Dashboard\ShippingDetail::class)->name('dashboard.transactions.detail');
```

---
### UI Polish & Asset Adjustments

- Update `resources/css/app.css` if any custom Tailwind utilities are needed (e.g., custom colors, card styling).
- Ensure responsive design classes for mobile/desktop as per spec.

---
### Security

- In Livewire components and services, always scope queries with `where('user_id', auth()->id())`.
- Add policy checks (optional) to further enforce ownership.

---
### Testing

- Add feature tests under `tests/Feature/TransactionHistoryTest.php` covering the six test scenarios described.
- Run `php artisan test` after implementation.

---
### Documentation

- Update README or a new `docs/transactions.md` summarizing usage.

## Verification Plan

### Automated Tests
- Run `php artisan migrate:fresh --seed`.
- Execute `php artisan test` and ensure all new feature tests pass.

### Manual Verification
- Log in as a user with orders and confirm dashboard displays correct list, filters, pagination, empty state.
- Open a transaction detail page and verify shipment timeline, tracking button, and security (cannot view another user’s order).
- Test on desktop, tablet, and mobile screen sizes.

---
