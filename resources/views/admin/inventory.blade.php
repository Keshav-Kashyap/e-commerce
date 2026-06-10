@extends('layouts.app')

@section('content')

<div class="container-fluid p-0">
    <div class="row g-0">
        
        <div class="col-md-2 d-none d-md-block">
            @include('partials.admin-sidebar')
        </div>

        <div class="col-md-10" style="background-color: #fdfaf5; min-height: 100vh; padding-top: 20px;">
            
            <style>
                .admin-header { color: #5E1929; font-weight: 700; margin-bottom: 0; letter-spacing: 0.5px; }
                .btn-add-new {
                    background: linear-gradient(135deg, #c59d5f, #e5c07b);
                    color: #fff; font-weight: 600; padding: 10px 25px; border-radius: 8px; border: none;
                    transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; font-size: 14px; text-decoration: none; display: inline-block;
                }
                .btn-add-new:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(197, 157, 95, 0.4); color: #fff; }
                .admin-card { background: #fff; border-radius: 15px; border: 1px solid #f5ebe9; box-shadow: 0 10px 30px rgba(94, 25, 41, 0.05); overflow: hidden; }
                .table-premium { margin-bottom: 0; }
                .table-premium thead { background: #5E1929; color: #fdfaf5; }
                .table-premium th { font-weight: 600; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; padding: 15px; border: none; }
                .table-premium td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f5ebe9; color: #444; }
                
                /* Out of Stock Row Highlight */
                .row-out-of-stock { background-color: #fff8f8 !important; }
                .row-out-of-stock td { border-bottom: 1px solid #ffe5e5; }
                .product-img-admin { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; background: #fff; padding: 2px; }
                .cat-badge { background: #fdfaf5; color: #c59d5f; border: 1px solid #e8d5d1; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
                
                /* Quick Stock Update Input */
                .stock-input-group { display: flex; align-items: center; gap: 8px; max-width: 120px; }
                .stock-input { border: 1px solid #ddd; border-radius: 6px; text-align: center; font-weight: bold; transition: 0.3s; }
                .stock-input:focus { border-color: #c59d5f; box-shadow: 0 0 0 0.2rem rgba(197, 157, 95, 0.25); }
                .stock-input.danger-stock { border-color: #dc3545; color: #dc3545; background: #fff1f0; }
                .btn-quick-update { background: #e6f4ea; color: #28a745; border: 1px solid #28a745; border-radius: 6px; padding: 4px 10px; transition: 0.3s; }
                .btn-quick-update:hover { background: #28a745; color: #fff; }
                .action-btn { padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; transition: 0.3s; }
            </style>

            <div class="container mt-4 mb-5" style="min-height: 60vh;">
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <h2 class="admin-header">📊 Inventory Management</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
                        <strong>✅ Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="admin-card">
                    <div class="table-responsive">
                        <table class="table table-hover table-premium">
                            <thead>
                                <tr>
                                    <th width="80">Image</th>
                                    <th>Product Details</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th width="150">Stock</th>
                                    <th class="text-center" width="180">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="{{ $product->stock == 0 ? 'row-out-of-stock' : '' }}">
                                        
                                        <td>
                                            <img src="{{ $product->image ? asset($product->image) : asset('images/default.png') }}" class="product-img-admin" alt="{{ $product->name }}">
                                        </td>
                                        
                                        <td>
                                            <div class="fw-bold" style="font-size: 15px; color: #111;">{{ $product->name }}</div>
                                            <div style="font-size: 12px; color: #888;">ID: #{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </td>
                                        
                                        <td>
                                            <span class="cat-badge">{{ $product->category ?? 'N/A' }}</span>
                                        </td>
                                        
                                        <td>
                                            <div class="fw-bold" style="color: #5E1929; font-size: 16px;">₹{{ number_format($product->price, 2) }}</div>
                                        </td>
                                        
                                        <td>
                                            <form action="{{ route('product.update', $product->id) }}" method="POST" class="stock-input-group">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="stock" value="{{ $product->stock }}" class="form-control form-control-sm stock-input {{ $product->stock == 0 ? 'danger-stock' : '' }}" min="0" title="Change number and click checkmark">
                                                <button type="submit" class="btn-quick-update" title="Save Stock">✓</button>
                                            </form>
                                            @if($product->stock == 0)
                                                <div class="text-danger fw-bold mt-1" style="font-size: 11px;">⚠️ Out of Stock</div>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-outline-primary action-btn">
                                                    ✏️ Edit
                                                </a>
                                                
                                                <form action="{{ route('product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger action-btn">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div style="font-size: 40px; opacity: 0.3; margin-bottom: 10px;">📦</div>
                                            <h5 style="color: #5E1929; font-weight: 600;">No Products Found</h5>
                                            <p class="text-muted">Your inventory is empty. Click 'Add New Product' to get started.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> </div>
</div>

@endsection