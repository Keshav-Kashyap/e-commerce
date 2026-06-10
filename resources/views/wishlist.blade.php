@extends('layouts.app')

@section('content')

<style>
    body { background-color: #fdfaf5; }
    
    /* Page Header */
    .wishlist-header {
        background: linear-gradient(rgba(94, 25, 41, 0.9), rgba(94, 25, 41, 0.9)), url('{{ asset("images/banner1.png") }}');
        background-size: cover;
        background-position: center;
        padding: 60px 0;
        text-align: center;
        color: #fff;
        margin-bottom: 40px;
    }
    .wishlist-header h2 { font-weight: 700; letter-spacing: 2px; margin-bottom: 10px; }

    /* Product Cards */
    .premium-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #f5ebe9;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .premium-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(94, 25, 41, 0.08); border-color: #e8d5d1; }
    
    /* Remove Button (Top Right Corner) */
    .btn-remove-wishlist {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid #f5ebe9;
        color: #dc3545;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        z-index: 10;
        transition: 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-remove-wishlist:hover { background: #dc3545; color: #fff; border-color: #dc3545; transform: scale(1.1); }

    .product-img-wrapper { position: relative; padding-top: 100%; background: #fdfaf5; overflow: hidden; }
    .product-img-wrapper img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .premium-card:hover .product-img-wrapper img { transform: scale(1.08); }
    
    .card-body { padding: 15px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .product-title { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-price { color: #5E1929; font-size: 16px; font-weight: 700; margin-bottom: 12px; }
    
    .btn-add-cart {
        background: linear-gradient(135deg, #c59d5f, #e5c07b);
        color: #fff;
        border: none;
        padding: 10px 15px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-add-cart:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(197, 157, 95, 0.3); color: #fff; }
    .btn-add-cart:disabled { background: #ccc; box-shadow: none; cursor: not-allowed; transform: none; }

    /* Empty State */
    .empty-state { text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; border: 1px dashed #e8d5d1; }
    .empty-icon { font-size: 50px; opacity: 0.5; margin-bottom: 15px; }
    .empty-state h4 { color: #5E1929; font-weight: 600; }
    
    .btn-premium { display: inline-block; padding: 12px 30px; background: #5E1929; color: #fff; border-radius: 30px; text-decoration: none; font-weight: 600; transition: 0.3s; margin-top: 15px; }
    .btn-premium:hover { background: #c59d5f; color: #fff; transform: translateY(-3px); }

    /* ================= MOBILE OPTIMIZATIONS ================= */
    @media(max-width: 768px) {
        .wishlist-header { padding: 40px 0; margin-bottom: 25px; }
        .wishlist-header h2 { font-size: 24px; }
        
        /* Compact Cards for 2-column mobile grid */
        .card-body { padding: 10px; }
        .product-title { font-size: 12px; }
        .product-price { font-size: 14px; margin-bottom: 10px; }
        .btn-add-cart { font-size: 10px; padding: 8px 5px; }
        .btn-remove-wishlist { width: 26px; height: 26px; font-size: 12px; top: 5px; right: 5px; }
    }
</style>

<div class="wishlist-header">
    <div class="container">
        <h2>My Wishlist</h2>
        <p style="color: #c59d5f; font-style: italic;">Your favorite jewelry pieces in one place</p>
    </div>
</div>

<div class="container mb-5" style="min-height: 60vh;">
    
    @php
        // Fetch items safely regardless of what the controller passed
        $items = isset($wishlists) ? $wishlists : (isset($wishlistItems) ? $wishlistItems : \App\Models\Wishlist::where('user_id', auth()->id())->with('product')->get());
    @endphp

    @if($items->count() > 0)
        <div class="row px-2 px-md-0">
            @foreach($items as $item)
                @if($item->product)
                <div class="col-6 col-md-4 col-lg-3 mb-4 px-1 px-md-3" id="wishlist-card-{{ $item->product->id }}">
                    <div class="premium-card">
                        
                        <button class="btn-remove-wishlist" onclick="removeWishlistItem({{ $item->product->id }})" title="Remove from Wishlist">
                            🗑️
                        </button>

                        <a href="{{ route('product.show', $item->product->id) }}">
                            <div class="product-img-wrapper">
                                <img src="{{ $item->product->image ? asset($item->product->image) : asset('images/default.png') }}" alt="{{ $item->product->name }}">
                            </div>
                        </a>
                        
                        <div class="card-body">
                            <h5 class="product-title">{{ $item->product->name }}</h5>
                            <div class="product-price">₹{{ number_format($item->product->price, 2) }}</div>
                            
                            @if($item->product->stock > 0)
                                <button class="btn-add-cart add-to-cart" data-id="{{ $item->product->id }}">Add to Cart</button>
                            @else
                                <button class="btn-add-cart" disabled>Out of Stock</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="empty-state">
                    <div class="empty-icon">💖</div>
                    <h4>Your Wishlist is Empty</h4>
                    <p class="text-muted">Looks like you haven't added any of our beautiful pieces to your wishlist yet.</p>
                    <a href="{{ route('search') }}" class="btn-premium">Explore Collection</a>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
    function removeWishlistItem(productId) {
        fetch(`{{ url('/wishlist-toggle') }}/${productId}`, {
            method: 'POST',
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'removed') {
                // Instantly hide the card for smooth UI
                let card = document.getElementById('wishlist-card-' + productId);
                if(card) {
                    card.style.transition = "opacity 0.3s";
                    card.style.opacity = "0";
                    setTimeout(() => { window.location.reload(); }, 300);
                } else {
                    window.location.reload();
                }
            }
        })
        .catch(err => console.log(err));
    }
</script>

@endsection