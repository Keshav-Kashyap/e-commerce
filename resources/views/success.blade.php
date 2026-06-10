@extends('layouts.app')

@section('content')

<style>
.success-wrapper {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.success-card {
    background: #fff;
    padding: 40px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 500px;
}

.success-title {
    color: #28a745;
    font-weight: 700;
}

.success-sub {
    color: #777;
}

.btn-home {
    background: linear-gradient(135deg, #c59d5f, #a88347);
    border: none;
    color: #fff;
    border-radius: 10px;
    padding: 10px 20px;
}

.btn-home:hover {
    background: linear-gradient(135deg, #a88347, #8d6b36);
}
</style>

<div class="success-wrapper">

    <div class="success-card">

        <h1 class="success-title mb-3">
            🎉 Order Placed Successfully!
        </h1>

        <div class="alert alert-info mt-4">
            🎉 <strong>Yay!</strong> Aapka Shringar order 
            <strong>
                @if(isset($order) && $order->estimated_delivery)
                    {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d M, Y') }}
                @else
                    {{ now()->addDays(7)->format('d M, Y') }}
                @endif
            </strong> 
            tak aapke paas pahunch jayega!
        </div>

        @if(isset($order) && ($order->tracking_id || $order->shiprocket_status))
            <div class="alert alert-light border mt-3 text-start">
                <div><strong>Tracking ID:</strong> {{ $order->tracking_id ?? 'Pending' }}</div>
                <div><strong>Delivery Sync:</strong> {{ ucfirst($order->shiprocket_status ?? 'Pending') }}</div>
                @if($order->shiprocket_sync_error)
                    <div class="text-danger small mt-2">Sync note: {{ $order->shiprocket_sync_error }}</div>
                @endif
            </div>
        @endif

        <p class="success-sub mb-4">
            Thank you for shopping with <strong>Shringar 💖</strong>
        </p>

        <a href="{{ route('home') }}" class="btn btn-home">
            Continue Shopping →
        </a>

    </div>

</div>

{{-- 🔥 AUTO REDIRECT --}}
<script>
setTimeout(() => {
    window.location.href = "{{ route('home') }}";
}, 5000);
</script>

@endsection