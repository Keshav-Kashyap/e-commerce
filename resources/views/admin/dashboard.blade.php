@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <div class="col-md-2 d-none d-md-block">
            @include('partials.admin-sidebar')
        </div>

        <div class="col-md-10 p-4 p-md-5" style="background-color: #f8f9fa; min-height: 100vh;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 style="color: #5E1929; font-weight: bold; margin-bottom: 0;">👋 Welcome back, {{ auth()->user()->name }}!</h2>
                    <p class="text-muted">Here is what's happening with Shringar today.</p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="border-bottom: 4px solid #c59d5f !important;">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted fw-bold small text-uppercase mb-1">Total Products</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ count($products) }}</h3>
                            </div>
                            <div style="font-size: 30px; opacity: 0.5;">🛍️</div>
                        </div>
                    </div>
                </div>

                @php $lowStockCount = $products->where('stock', '<', 5)->count(); @endphp
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="border-bottom: 4px solid #dc3545 !important;">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted fw-bold small text-uppercase mb-1">Low Stock Alerts</p>
                                <h3 class="fw-bold mb-0 {{ $lowStockCount > 0 ? 'text-danger' : 'text-success' }}">{{ $lowStockCount }}</h3>
                            </div>
                            <div style="font-size: 30px; opacity: 0.5;">⚠️</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="border-bottom: 4px solid #28a745 !important;">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted fw-bold small text-uppercase mb-1">Monthly Revenue</p>
                                <h3 class="fw-bold mb-0 text-dark">₹{{ number_format($totalRevenue, 2) }}</h3>
                            </div>
                            <div style="font-size: 30px; opacity: 0.5;">💰</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="border-bottom: 4px solid #007bff !important;">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted fw-bold small text-uppercase mb-1">New Orders</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $newOrdersCount }}</h3>
                            </div>
                            <div style="font-size: 30px; opacity: 0.5;">📦</div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row g-4">
                
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0" style="color: #5E1929;">Recently Added Products</h5>
                            <a href="{{ route('admin.inventory') }}" class="btn btn-sm btn-outline-dark">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <tbody>
                                    @foreach($products->take(4) as $product)
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td width="60"><img src="{{ $product->image ? asset($product->image) : asset('images/default.png') }}" style="width: 50px; height: 50px; object-fit:cover; border-radius: 8px;"></td>
                                        <td class="fw-bold">{{ $product->name }}<br><small class="text-muted fw-normal">{{ $product->category }}</small></td>
                                        <td class="text-end fw-bold" style="color: #5E1929;">₹{{ number_format($product->price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4" style="background: linear-gradient(145deg, #5E1929, #3a0d17); color: #fff;">
                        <h5 class="fw-bold mb-4" style="color: #c59d5f;">Quick Actions</h5>
                        
                        <a href="{{ route('product.create') }}" class="btn w-100 text-start mb-3" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                            ➕ Add New Product
                        </a>
                        <a href="{{ route('admin.orders') }}" class="btn w-100 text-start mb-3" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                            🛒 Check Orders
                        </a>
                        <a href="{{ route('admin.inventory') }}" class="btn w-100 text-start" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                            📦 Update Stock
                        </a>
                    </div>
                </div>

            </div>

        </div> </div>
</div>
@endsection