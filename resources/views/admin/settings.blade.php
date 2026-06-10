@extends('layouts.app')
@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 d-none d-md-block">@include('partials.admin-sidebar')</div>
        <div class="col-md-10 p-4 p-md-5" style="background-color: #fdfaf5; min-height: 100vh;">
            <h3 style="color: #5E1929; font-weight: bold;">⚙️ Store Settings</h3>
            
            @if(session('success'))
                <div class="alert alert-success mt-3 shadow-sm rounded-3">✅ {{ session('success') }}</div>
            @endif

            <div class="card shadow-sm p-4 mt-4 border-0" style="border-radius: 15px; max-width: 600px;">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <h5 class="fw-bold mb-3" style="color: #c59d5f;">Contact Information</h5>
                    <div class="mb-3">
                        <label class="small fw-bold">Support Email</label>
                        <input type="email" name="support_email" class="form-control" value="{{ $settings['support_email'] ?? 'support@shringar.net' }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="small fw-bold">Support Phone</label>
                        <input type="text" name="support_phone" class="form-control" value="{{ $settings['support_phone'] ?? '+91-XXXXXXXXXX' }}" required>
                    </div>

                    <h5 class="fw-bold mb-3 mt-4" style="color: #c59d5f;">Order & Shipping</h5>
                    <div class="mb-3">
                        <label class="small fw-bold">Standard Shipping Charge (₹)</label>
                        <input type="number" name="shipping_charge" class="form-control" value="{{ $settings['shipping_charge'] ?? '0' }}" min="0">
                        <small class="text-muted">Set 0 for Free Shipping</small>
                    </div>
                    
                    <button type="submit" class="btn text-white fw-bold px-5 mt-3" style="background: #5E1929;">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection