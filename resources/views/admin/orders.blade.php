@extends('layouts.app')

@section('content')

<style>
    /* 🔥 Professional Spacing & Design */
    .admin-container { background-color: #fdfaf5; min-height: 100vh; padding: 30px; }
    .section-header { color: #5E1929; font-weight: 700; margin-bottom: 25px; border-bottom: 2px solid #5E1929; padding-bottom: 10px; display: inline-block; }
    
    /* Tabs Styling */
    .nav-tabs { border-bottom: 2px solid #f5ebe9; margin-bottom: 30px; gap: 10px; }
    .nav-tabs .nav-link { border: none; color: #666; font-weight: 600; padding: 12px 25px; border-radius: 10px 10px 0 0; transition: 0.3s; }
    .nav-tabs .nav-link:hover { background: #f5ebe9; }
    .nav-tabs .nav-link.active { background: #5E1929 !important; color: #fff !important; }

    /* Order Card Styling */
    .order-card { background: #fff; border-radius: 15px; border: 1px solid #f5ebe9; box-shadow: 0 5px 15px rgba(0,0,0,0.02); margin-bottom: 30px; overflow: hidden; }
    .order-card-header { background: #fafbfc; padding: 20px; border-bottom: 1px solid #f0f0f0; }
    .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    
    .status-badge { font-size: 11px; padding: 6px 15px; border-radius: 20px; font-weight: 700; text-transform: uppercase; }
    .summary-box { background: #fdfaf5; border-radius: 12px; padding: 15px; border: 1px solid #f5ebe9; }
    
    /* Rejected Table Styling */
    .rejected-table thead { background: #5E1929; color: #fff; }
    .rejected-table td { vertical-align: middle; padding: 15px; }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        {{-- Sidebar --}}
        <div class="col-md-2 d-none d-md-block">
            @include('partials.admin-sidebar')
        </div>

        {{-- Main Dashboard --}}
        <div class="col-md-10 admin-container">
            <h2 class="section-header">🛒 Admin Orders Management</h2>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">✅ {{ session('success') }}</div>
            @endif

            {{-- --- TABS NAVIGATION --- --}}
            <ul class="nav nav-tabs" id="orderTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="active-tab" data-bs-toggle="tab" href="#activeOrders" role="tab">📦 Active ({{ $activeOrders->count() }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="complete-tab" data-bs-toggle="tab" href="#completeOrders" role="tab">✅ Delivered ({{ $completeOrders->count() }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="return-tab" data-bs-toggle="tab" href="#returnRequests" role="tab">🔄 Returns ({{ $returnRequests->count() }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" id="rejected-tab" data-bs-toggle="tab" href="#rejectedOrders" role="tab">❌ Cancelled/Rejected</a>
                </li>
            </ul>

            {{-- --- TABS CONTENT --- --}}
            <div class="tab-content" id="orderTabsContent">
                
                {{-- 1. ACTIVE ORDERS --}}
                <div class="tab-pane fade show active" id="activeOrders" role="tabpanel">
                    @forelse($activeOrders as $order)
                        @include('partials.admin-order-card-snippet', ['order' => $order])
                    @empty
                        <div class="text-center py-5 bg-white rounded-4 border">No active orders found.</div>
                    @endforelse
                </div>

                {{-- 2. COMPLETE ORDERS --}}
                <div class="tab-pane fade" id="completeOrders" role="tabpanel">
                    @forelse($completeOrders as $order)
                        @include('partials.admin-order-card-snippet', ['order' => $order])
                    @empty
                        <div class="text-center py-5 bg-white rounded-4 border">No completed orders yet.</div>
                    @endforelse
                </div>

                {{-- 3. RETURN REQUESTS --}}
                <div class="tab-pane fade" id="returnRequests" role="tabpanel">
                    @forelse($returnRequests as $order)
                        @include('partials.admin-order-card-snippet', ['order' => $order])
                    @empty
                        <div class="text-center py-5 bg-white rounded-4 border">No return requests found.</div>
                    @endforelse
                </div>

                {{-- 4. REJECTED TABLE --}}
                <div class="tab-pane fade" id="rejectedOrders" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <table class="table rejected-table mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Reason</th>
                                    <th>Time</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rejectedOrders as $order)
                                <tr>
                                    <td class="fw-bold">#{{ $order->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $order->user->name ?? $order->name }}</div>
                                        <small class="text-muted">ID: {{ $order->user_id ?? 'Guest' }}</small>
                                    </td>
                                    <td class="text-danger"><strong>{{ $order->cancel_reason ?? 'Reason not specified' }}</strong></td>
                                    <td><small>{{ $order->updated_at->format('d M Y, h:i A') }}</small></td>
                                    <td class="text-end fw-bold">₹{{ number_format($order->total_amount) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No rejected orders record.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div> 
    </div>
</div>

{{-- --- CANCELLATION MODAL --- --}}
<div class="modal fade" id="cancelModalAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h4 class="fw-bold mb-0">Cancel Order</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="cancelFormAdmin">
                @csrf
                <div class="modal-body p-4 pt-0">
                    <p class="text-muted small mb-3">Please provide a reason for cancelling this order. This will be visible to the customer.</p>
                    <textarea name="reason" class="form-control" rows="4" placeholder="Reason..." required style="background: #f8f9fa; border-radius:12px;"></textarea>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-danger w-100 py-3 fw-bold" style="border-radius:12px;">Reject Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCancelModal(orderId) {
    var myModal = new bootstrap.Modal(document.getElementById('cancelModalAdmin'));
    document.getElementById('cancelFormAdmin').action = "{{ url('/admin/order/cancel') }}/" + orderId;
    myModal.show();
}
</script>

@endsection