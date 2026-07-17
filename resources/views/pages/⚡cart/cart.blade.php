<div class="max-w-4xl mx-auto py-16 px-4">
    <!-- Stepper Header -->
    <h1 class="text-3xl font-bold text-forest-800 mb-8">
        {{ $step == 1 ? 'Keranjang Belanja' : ($step == 2 ? 'Detail Pengiriman' : 'Konfirmasi Pesanan') }}
    </h1>

    <!-- Include sub-view berdasarkan step -->
    @include('pages.⚡cart.steps.step-' . $step)
</div>
