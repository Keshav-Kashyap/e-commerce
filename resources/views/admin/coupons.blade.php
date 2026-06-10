@extends('layouts.app')
@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 d-none d-md-block">@include('partials.admin-sidebar')</div>
        <div class="col-md-10 p-4 p-md-5" style="background-color: #fdfaf5; min-height: 100vh;">
            <h3 style="color: #5E1929; font-weight: bold;">🎫 Discount Coupons</h3>
            
            @if(session('success'))
                <div class="alert alert-success mt-3 shadow-sm rounded-3">✅ {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
            @endif

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card shadow-sm p-4 border-0" style="border-radius: 15px;">
                        <h6 class="fw-bold mb-3" style="color: #c59d5f;">Create New Coupon</h6>
                        <form action="{{ route('admin.coupons.store') }}" method="POST">
                            @csrf
                            <input type="text" name="code" class="form-control mb-3" placeholder="Code (e.g. DIWALI20)" required style="text-transform: uppercase;">
                            <input type="number" name="discount_percent" class="form-control mb-3" placeholder="Discount Percentage (%)" required min="1" max="99">
                            
                            <input type="number" name="max_discount" class="form-control mb-3" placeholder="Max Discount (₹) (e.g. 199 or blank)" min="1">
                            
                            <input type="number" name="usage_limit" class="form-control mb-3" placeholder="Usage Limit (e.g. 50 or blank for unlimited)" min="1">
                            <button type="submit" class="btn w-100 text-white fw-bold" style="background: #5E1929;">Add Coupon</button>
                        </form>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card shadow-sm p-4 border-0" style="border-radius: 15px;">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead style="background: #5E1929; color: #fff;">
                                    <tr>
                                        <th>Code</th>
                                        <th>Discount</th>
                                        <th>Max Limit (₹)</th> <th>Used / Limit</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons as $coupon)
                                    <tr>
                                        <td class="fw-bold" style="color: #5E1929; font-size: 18px;">{{ $coupon->code }}</td>
                                        
                                        <td class="fw-bold">{{ $coupon->discount_percent }}% OFF</td>
                                        
                                        <td class="fw-bold" style="color: #c59d5f;">
                                            {{ $coupon->max_discount ? 'Upto ₹'.$coupon->max_discount : 'No Limit' }}
                                        </td>
                                        
                                        <td class="fw-bold " style="font-size: 16px;">
                                            {{ $coupon->times_used ?? '0' }} / {{ $coupon->usage_limit ?? 'Unlimited' }}
                                        </td>

                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Disabled</span>
                                            @endif
                                        </td>
                                        
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end align-items-center gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $coupon->id }}">
                                                    ✏️ Edit
                                                </button>

                                                <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST" class="m-0 p-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $coupon->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                        {{ $coupon->is_active ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Delete this coupon?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editModal{{ $coupon->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold" style="color: #5E1929;">Edit Coupon: {{ $coupon->code }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label class="form-label text-start w-100">Discount Percentage (%)</label>
                                                            <input type="number" name="discount_percent" class="form-control" value="{{ $coupon->discount_percent }}" required min="1" max="99">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label text-start w-100">Max Discount (₹) (Leave blank for No Limit)</label>
                                                            <input type="number" name="max_discount" class="form-control" value="{{ $coupon->max_discount }}" min="1">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label text-start w-100">Usage Limit (Leave blank for Unlimited)</label>
                                                            <input type="number" name="usage_limit" class="form-control" value="{{ $coupon->usage_limit }}" min="1">
                                                        </div>
                                                        <button type="submit" class="btn w-100 text-white fw-bold" style="background: #5E1929;">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No coupons created yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection