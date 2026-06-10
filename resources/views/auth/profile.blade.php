@extends('layouts.app') 

@section('content')
<style>
    body { background-color: #fdfaf5; }
    
    .btn-feedback, .btn-cancel-customer {
        background: #fff;
        color: #c59d5f;
        border: 1px solid #c59d5f;
        font-size: 11px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-cancel-customer { color: #dc3545; border-color: #dc3545; margin-top: 5px; }
    .btn-cancel-customer:hover { background: #dc3545; color: #fff; }
    .btn-feedback:hover { background: #c59d5f; color: #fff; }

    .modal-content { border-radius: 15px; border: none; }
    .modal-header { background: #5E1929; color: #fff; border-radius: 15px 15px 0 0; }
    
    /* 🔥 Order Filter Tabs Styling (Fixed Color Issue) */
    .order-filters { display: flex; gap: 10px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 10px; }
    .filter-tab { 
        padding: 8px 18px; font-size: 12px; border-radius: 25px; border: 1px solid #e8d5d1; 
        background: #fff; color: #666; cursor: pointer; transition: 0.3s; white-space: nowrap; font-weight: 600;
    }
    .filter-tab.active { background: #5E1929 !important; color: #fff !important; border-color: #5E1929 !important; }

    /* Layout Styles */
    .profile-header {
        background: linear-gradient(rgba(94, 25, 41, 0.9), rgba(94, 25, 41, 0.9)), url('{{ asset("images/banner1.png") }}');
        background-size: cover; background-position: center; padding: 60px 0; text-align: center; color: #fff; margin-bottom: 40px;
    }
    .profile-header h2 { font-weight: 700; letter-spacing: 2px; margin-bottom: 10px; }
    .profile-card { background: #fff; border-radius: 12px; border: 1px solid #f5ebe9; box-shadow: 0 10px 30px rgba(94, 25, 41, 0.05); padding: 30px; margin-bottom: 25px; }
    .user-avatar { width: 100px; height: 100px; background: #5E1929; color: #c59d5f; font-size: 40px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-bottom: 15px; border: 3px solid #fdfaf5; box-shadow: 0 5px 15px rgba(197, 157, 95, 0.3); }
    .profile-nav .nav-link { color: #555; font-weight: 500; padding: 12px 20px; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease; display: flex; align-items: center; gap: 12px; cursor: pointer; }
    .profile-nav .nav-link.active { background: #5E1929; color: #fff; }
    
    .order-box { background: #fff; border: 1px solid #f5ebe9; border-radius: 12px; padding: 0; margin-bottom: 25px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
    .order-header { background: #fafbfc; padding: 15px 20px; display: flex; justify-content: space-between; border-bottom: 1px solid #f0f0f0; align-items: center; }
    
    /* Tracking Step Fix */
    .tracking-wrapper { padding: 15px; background: #fffcfb; border-top: 1px solid #f5ebe9; }
    .step { font-size: 10px; font-weight: bold; text-transform: uppercase; color: #ccc; }
    .step.active { color: #5E1929; }
</style>

<div class="profile-header">
    <div class="container">
        <h2>My Account</h2>
        <p style="color: #c59d5f; font-style: italic;">Manage your profile and orders</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        {{-- SIDEBAR --}}
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="profile-card">
                <div class="text-center mb-4">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <h4 class="fw-bold">{{ auth()->user()->name }}</h4>
                    <p class="text-muted small text-truncate">{{ auth()->user()->email }}</p>
                    <span class="badge" style="background: #fdfaf5; color: #c59d5f; border: 1px solid #c59d5f; padding: 4px 12px; font-size: 11px; border-radius: 20px; text-transform: uppercase;">
                        {{ auth()->user()->role == 'admin' ? 'Admin Account' : 'Customer' }}
                    </span>
                </div>
                <div class="nav flex-column profile-nav">
                    <a class="nav-link active" onclick="showSection('profile', this)"><span>📊</span> Overview</a>
                    <a class="nav-link" onclick="showSection('orders', this)"><span>🛍️</span> My Orders</a>
                    <a class="nav-link" onclick="showSection('edit', this)"><span>⚙️</span> Settings</a>
                    <a class="nav-link" onclick="showSection('help', this)"><span>🙋‍♂️</span> Help & Support</a>
                    <hr style="border-color: #f5ebe9; margin: 15px 0;">
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-bold">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-8 col-md-7">
            <div class="profile-card" style="min-height: 400px;">
                
                {{-- OVERVIEW SECTION --}}
                <div id="profileSection">
                    <h4 class="tab-content-title">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}!</h4>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="stat-card" style="cursor: pointer; padding: 25px; border: 1px solid #f5ebe9; border-radius:12px; text-align:center; background:#fdfaf5;" onclick="showSection('orders', document.querySelectorAll('.nav-link')[1])">
                                <div style="font-size:30px">📦</div>
                                <div style="font-size:24px; font-weight:bold; color:#5E1929">{{ $allOrders->count() }}</div>
                                <div class="small text-muted text-uppercase fw-bold">Total Orders</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('wishlist') }}" style="text-decoration: none;">
                                <div class="stat-card" style="padding: 25px; border: 1px solid #f5ebe9; border-radius:12px; text-align:center; background:#fdfaf5;">
                                    <div style="font-size:30px">💖</div>
                                    <div style="font-size:24px; font-weight:bold; color:#5E1929">{{ auth()->user()->wishlistItems ? auth()->user()->wishlistItems->count() : 0 }}</div>
                                    <div class="small text-muted text-uppercase fw-bold">Wishlist</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- MY ORDERS SECTION (DYNAMIC DATA) --}}
                <div id="ordersSection" style="display:none;">
                    <h4 class="tab-content-title">Order History</h4>
                    
                    <div class="order-filters">
                        <div class="filter-tab active" onclick="filterOrderType('active', this)">Active</div>
                        <div class="filter-tab" onclick="filterOrderType('past', this)">Past</div>
                        <div class="filter-tab" onclick="filterOrderType('cancelled', this)">Cancelled</div>
                        <div class="filter-tab" onclick="filterOrderType('returned', this)">Returned</div>
                    </div>

                    @php 
                        // Controller change kiye bina hum yahan $allOrders collection se dynamically tab data filter kar rahe hain
                        $activeList = $allOrders->whereNotIn('status', ['delivered', 'cancelled', 'return_requested', 'returned']);
                        $pastList = $allOrders->where('status', 'delivered');
                        $cancelledList = $allOrders->where('status', 'cancelled');
                        $returnedList = $allOrders->whereIn('status', ['return_requested', 'returned']);

                        $groups = [
                            'active' => $activeList, 
                            'past' => $pastList, 
                            'cancelled' => $cancelledList,
                            'returned' => $returnedList
                        ]; 
                    @endphp

                    @foreach($groups as $key => $ordersList)
                        <div class="order-list-container" id="list-{{ $key }}" style="{{ $key == 'active' ? '' : 'display:none' }}">
                            @forelse($ordersList as $order)
                                <div class="order-box">
                                    <div class="order-header">
                                        <div><small class="text-muted">#{{ $order->id }}</small><br><strong>{{ $order->created_at->format('d M, Y') }}</strong></div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $order->status == 'cancelled' ? 'danger' : ($order->status == 'delivered' ? 'success' : 'primary') }} mb-1" style="font-size:9px">{{ strtoupper($order->status) }}</span><br>
                                            @if(in_array($order->status, ['pending', 'confirmed']))
                                                <button class="btn-cancel-customer" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $order->id }}">Cancel</button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        @foreach($order->items as $item)
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="{{ asset($item->product->image ?? 'images/default.png') }}" style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                                                <div class="ms-3 flex-grow-1">
                                                    <div class="fw-bold small">{{ $item->product->name }}</div>
                                                    <small class="text-muted">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price) }}</small>
                                                </div>
                                                <div class="fw-bold">₹{{ number_format($item->price * $item->quantity) }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    {{-- 🔥 NAYA: TRACKING WRAPPER WITH INVOICE & RETURN BUTTON --}}
                                    <div class="tracking-wrapper d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Total: <strong class="text-danger">₹{{ number_format($order->total_amount) }}</strong></small>
                                            <br>
                                            <div class="mt-1 d-flex align-items-center">
                                                <a href="{{ route('order.invoice', $order->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:10px;" target="_blank">⬇ Download Bill</a>
                                                
                                                {{-- Return Order Logic start --}}
                                                @php
                                                    $isWithin7Days = \Carbon\Carbon::parse($order->created_at)->addDays(7)->isFuture();
                                                @endphp

                                                @if($order->status === 'delivered' && $isWithin7Days)
                                                    <form action="{{ route('order.return', $order->id) }}" method="POST" class="m-0 p-0 ms-2" onsubmit="return confirm('Are you sure you want to return this order?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:10px;">🔄 Return Order</button>
                                                    </form>
                                                @elseif($order->status === 'return_requested')
                                                    <span class="badge bg-warning text-dark ms-2" style="font-size:10px;">Return Requested</span>
                                                @elseif($order->status === 'returned')
                                                    <span class="badge bg-danger ms-2" style="font-size:10px;">Returned</span>
                                                @endif
                                                {{-- Return Order Logic end --}}
                                            </div>
                                        </div>
                                        @if($order->tracking_id)
                                            <small class="bg-light p-1 px-2 border rounded" style="font-size:10px">ID: {{ $order->tracking_id }}</small>
                                        @endif
                                        @if($order->shiprocket_status)
                                            <small class="bg-light p-1 px-2 border rounded ms-2" style="font-size:10px">Shiprocket: {{ ucfirst($order->shiprocket_status) }}</small>
                                        @endif
                                    </div>
                                </div>

                                {{-- CANCEL MODAL --}}
                                <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('order.cancel.customer', $order->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header"><h5>Cancel Order</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                                                <div class="modal-body">
                                                    <select name="reason" class="form-select mb-3" required onchange="checkOther(this, {{ $order->id }})">
                                                        <option value="Mind Changed">Mind Changed</option>
                                                        <option value="Ordered by mistake">Ordered by mistake</option>
                                                        <option value="Found better price">Found better price</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    <div id="other_box_{{ $order->id }}" style="display:none">
                                                        <textarea name="other_reason" class="form-control" placeholder="Specify reason..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0"><button type="submit" class="btn btn-danger w-100">Confirm Cancellation</button></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">No orders found here.</div>
                            @endforelse
                        </div>
                    @endforeach
                </div>

                {{-- SETTINGS SECTION --}}
                <div id="editSection" style="display:none;">
                    <h4 class="fw-bold mb-4" style="color:#5E1929">Profile Settings</h4>
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        <div class="mb-3"><label class="small fw-bold text-uppercase">Full Name</label><input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required></div>
                        <div class="mb-3"><label class="small fw-bold text-uppercase">Email Address</label><input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required></div>
                        <div class="mb-4"><label class="small fw-bold text-uppercase">Mobile Number</label><input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}"></div>
                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">Update Profile</button>
                    </form>
                </div>

                {{-- HELP SECTION --}}
                <div id="helpSection" style="display:none;">
                    <h4 class="fw-bold mb-4" style="color:#5E1929">Help & Support</h4>
                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="mb-3"><label class="small fw-bold">Subject</label><select name="subject" class="form-select"><option>Order Issue</option><option>Payment Issue</option><option>Return Inquiry</option></select></div>
                        <div class="mb-3"><label class="small fw-bold">Message</label><textarea name="message" class="form-control" rows="4" required></textarea></div>
                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">Send Message</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function showSection(id, btn) {
        document.getElementById('profileSection').style.display = 'none';
        document.getElementById('ordersSection').style.display = 'none';
        document.getElementById('editSection').style.display = 'none';
        document.getElementById('helpSection').style.display = 'none';
        document.querySelectorAll('.profile-nav .nav-link').forEach(l => l.classList.remove('active'));
        document.getElementById(id + 'Section').style.display = 'block';
        if(btn) btn.classList.add('active');
    }

    function filterOrderType(type, btn) {
        document.querySelectorAll('.order-list-container').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.filter-tab').forEach(el => {
            el.classList.remove('active');
            el.style.background = '#fff';
            el.style.color = '#666';
        });
        document.getElementById('list-' + type).style.display = 'block';
        btn.classList.add('active');
        btn.style.background = '#5E1929';
        btn.style.color = '#fff';
    }

    function checkOther(select, id) {
        document.getElementById('other_box_' + id).style.display = (select.value == 'Other') ? 'block' : 'none';
    }
</script>

@endsection