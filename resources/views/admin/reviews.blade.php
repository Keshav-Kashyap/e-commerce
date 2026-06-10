@extends('layouts.app')
@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 d-none d-md-block">@include('partials.admin-sidebar')</div>
        <div class="col-md-10 p-4 p-md-5" style="background-color: #fdfaf5; min-height: 100vh;">
            <h3 style="color: #5E1929; font-weight: bold;">⭐ Customer Reviews</h3>
            
            @if(session('success'))
                <div class="alert alert-success mt-3 shadow-sm rounded-3">✅ {{ session('success') }}</div>
            @endif

            <div class="card shadow-sm p-4 mt-4 border-0" style="border-radius: 15px;">
                <table class="table align-middle">
                    <thead style="background: #5E1929; color: #fff;">
                        <tr><th>Product</th><th>Customer</th><th>Rating</th><th>Comment</th><th class="text-end">Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="max-width: 150px;">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset($review->product->image ?? 'images/default.png') }}" width="40" height="40" style="border-radius: 5px; object-fit: cover;">
                                    <span class="small fw-bold">{{ Str::limit($review->product->name ?? 'Deleted Product', 20) }}</span>
                                </div>
                            </td>
                            <td><span class="fw-bold">{{ $review->user->name }}</span><br><small class="text-muted">{{ $review->created_at->format('d M Y') }}</small></td>
                            <td style="color: #c59d5f; font-size: 14px;">
                                @for($i=0; $i<$review->rating; $i++) ★ @endfor
                            </td>
                            <td><span style="font-style: italic; font-size: 13px;">"{{ $review->comment }}"</span></td>
                            <td class="text-end">
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No reviews yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection