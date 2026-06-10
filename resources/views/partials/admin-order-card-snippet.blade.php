<div class="card mb-4 shadow-sm border-0 rounded-4 order-card">
    <div class="card-header bg-white border-bottom p-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <strong class="fs-5" style="color: #5E1929;">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong><br>
                <span class="text-muted">{{ $order->name }} | {{ $order->phone }} (UID: {{ $order->user_id }})</span>
            </div>
            <div class="col-md-6 text-md-end text-start">
                <div class="d-inline-flex gap-2 align-items-center flex-wrap justify-content-md-end">
                    @if($order->tracking_id)
                        <div class="tracking-badge shadow-sm">🚚 Tracking: {{ $order->tracking_id }}</div>
                    @endif
                    <span class="badge badge-status shadow-sm 
                        @if($order->status == 'cancelled') bg-danger 
                        @elseif($order->status == 'delivered') bg-success
                        @elseif($order->status == 'shipped') bg-info
                        @else bg-warning text-dark @endif"> 
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <p class="mb-4"><strong>📍 Address:</strong> <span class="text-secondary">{{ $order->address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</span></p>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead style="background: #5E1929; color: #fdfaf5;">
                    <tr><th>Image</th><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td><img src="{{ asset($item->product->image ?? 'images/default.png') }}" style="width:50px;height:50px;object-fit:cover;border-radius:8px;"></td>
                        <td class="text-start"><strong>{{ $item->product->name ?? 'Deleted' }}</strong></td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->price, 2) }}</td>
                        <td class="fw-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mt-4 justify-content-end">
            <div class="col-md-5">
                <div class="p-3 summary-box">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal:</span><span class="fw-bold">₹{{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="d-flex justify-content-between align-items-center border-top pt-2"><h5 class="mb-0 fw-bold">Total:</h5><h4 class="mb-0 text-danger fw-bold">₹{{ number_format($order->total_amount, 2) }}</h4></div>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-top">
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-3"><select name="status" class="form-select"><option value="pending" {{$order->status=='pending'?'selected':''}}>Pending</option><option value="confirmed" {{$order->status=='confirmed'?'selected':''}}>Confirmed</option><option value="shipped" {{$order->status=='shipped'?'selected':''}}>Shipped</option><option value="delivered" {{$order->status=='delivered'?'selected':''}}>Delivered</option></select></div>
                <div class="col-md-4"><input type="text" name="tracking_id" class="form-control" placeholder="Tracking ID" value="{{ $order->tracking_id }}"></div>
                <div class="col-md-auto"><button type="submit" class="btn btn-success">Update Info</button></div>
                <div class="col-md-auto ms-auto"><button type="button" class="btn btn-outline-danger" onclick="openCancelModal({{ $order->id }})">Cancel Order</button></div>
            </form>
        </div>
    </div>
</div>