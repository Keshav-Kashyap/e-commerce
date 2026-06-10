@extends('layouts.app')

@section('content')

<style>
    body { background-color: #fffcfb; }
    .product-wrapper { margin-top: 30px; margin-bottom: 60px; }

    /* Breadcrumb */
    .custom-breadcrumb { font-size: 13px; color: #888; margin-bottom: 20px; }
    .custom-breadcrumb a { color: #c59d5f; text-decoration: none; transition: 0.3s; }
    .custom-breadcrumb a:hover { color: #5E1929; }

    /* 🔥 MANUAL SLIDER AREA */
    .product-img-container {
        background: #fdfaf5;
        border-radius: 15px;
        padding: 40px;
        text-align: center;
        border: 1px solid #f5ebe9;
        box-shadow: 0 10px 40px rgba(94, 25, 41, 0.05);
        position: relative;
        overflow: hidden;
        height: 500px; 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slide-img-main {
        max-width: 100%;
        max-height: 450px;
        object-fit: contain;
        display: none; 
        transition: transform 0.5s ease;
    }

    .slide-img-main.active { display: block; }
    
    .product-img-container:hover .slide-img-main.active { transform: scale(1.05); }

    /* Arrows Style */
    .nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        color: #5E1929;
        border: 1px solid #e8d5d1;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: 0.3s;
        font-size: 22px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .nav-arrow:hover { background: #5E1929; color: #fff; }
    .prev-arrow { left: 15px; }
    .next-arrow { right: 15px; }

    /* Thumbnails */
    .thumbnail-container { display: flex; gap: 12px; margin-top: 20px; justify-content: center; flex-wrap: wrap; }
    .thumb-img {
        width: 70px; height: 70px; object-fit: cover; border-radius: 10px; border: 2px solid #eee;
        cursor: pointer; transition: 0.3s; padding: 5px; background: #fff;
    }
    .thumb-img.active { border-color: #c59d5f; box-shadow: 0 0 10px rgba(197,157,95,0.3); }

    /* Product Details Area */
    .product-details-container { padding: 20px 20px 20px 40px; }
    .product-category-badge { display: inline-block; background: #f5ebe9; color: #5E1929; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }
    .product-title { font-size: 32px; font-weight: 700; color: #111; margin-bottom: 10px; line-height: 1.3; }
    .product-price-container { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
    .product-price { font-size: 28px; color: #5E1929; font-weight: bold; margin: 0; }
    .product-stock { font-size: 13px; font-weight: 600; padding: 4px 10px; border-radius: 5px; }
    .stock-in { background: #e6f4ea; color: #389e0d; }
    .stock-out { background: #fff1f0; color: #cf1322; }
    .product-description { color: #555; font-size: 15px; line-height: 1.7; margin-bottom: 30px; }

    /* Buttons */
    .quantity-selector { border: 1px solid #e8d5d1; border-radius: 8px; background: #fff; padding: 2px; width: fit-content; }
    .qty-btn { background: transparent; border: none; width: 40px; height: 45px; font-size: 20px; color: #5E1929; cursor: pointer; transition: 0.2s; }
    .qty-btn:hover { background: #fdfaf5; color: #c59d5f; }
    .qty-input { width: 50px; border: none; text-align: center; font-weight: 700; color: #5E1929; background: transparent; outline: none; }
    .action-buttons { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
    .btn-add-cart { background: linear-gradient(135deg, #c59d5f, #e5c07b); color: #fff; border: none; padding: 15px 30px; font-size: 15px; font-weight: 600; border-radius: 8px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease; flex: 1; min-width: 200px; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .btn-add-cart:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(197, 157, 95, 0.4); color: #fff; }
    .btn-buy-now { background: #5E1929; color: #fff; border: none; padding: 15px 30px; font-size: 15px; font-weight: 600; border-radius: 8px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease; flex: 1; text-align: center; text-decoration: none; }
    .btn-buy-now:hover { background: #4a1320; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(94, 25, 41, 0.3); color: #fff; }
    .btn-wishlist-large { background: #fff; border: 2px solid #e8d5d1; color: #5E1929; padding: 0 20px; height: 54px; font-size: 20px; border-radius: 8px; transition: all 0.3s; display: flex; align-items: center; justify-content: center; cursor: pointer; }

    /* Trust & Reviews */
    .trust-badges { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; gap: 10px; }
    .trust-item { text-align: center; flex: 1; }
    .trust-icon { font-size: 24px; margin-bottom: 8px; display: block; }
    .trust-text { font-size: 12px; color: #666; font-weight: 500; }
    .review-card { transition: all 0.3s ease; border-color: #f5ebe9 !important; }
    .review-card:hover { transform: translateX(10px); border-color: #c59d5f !important; box-shadow: 0 10px 25px rgba(94, 25, 41, 0.08) !important; background-color: #fffcfb; }

    /* Suggested Products Cards */
    .premium-card { position: relative; height: 100%; display: flex; flex-direction: column; background: #fff; border-radius: 12px; border: 1px solid #f5ebe9; overflow: hidden; transition: 0.3s, box-shadow 0.3s; }
    .premium-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(94, 25, 41, 0.08); border-color: #e8d5d1; }
    .product-img-wrapper-card { position: relative; padding-top: 100%; background: #fdfaf5; overflow: hidden; }
    .product-img-wrapper-card img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; padding: 15px; transition: transform 0.5s ease; }
    .card-body-custom { padding: 20px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .card-title-custom { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .card-price-custom { color: #5E1929; font-size: 18px; font-weight: 700; margin-bottom: 15px; }
    .btn-view-card { padding: 8px 20px; border: 1px solid #5E1929; color: #5E1929; background: transparent; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase; text-decoration: none; transition: 0.3s; }
    .wishlist-btn-card { position: absolute; top: 12px; right: 12px; background: #fff; border: 1px solid #eee; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-size: 16px; cursor: pointer; z-index: 10; transition: 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }

    @media(max-width: 768px) {
        .product-details-container { padding: 20px 0; }
        .action-buttons { flex-direction: column; }
        .product-img-container { height: 350px; padding: 20px; }
    }
</style>

<div class="container product-wrapper">

    {{-- Breadcrumb Fix --}}
    <div class="custom-breadcrumb">
        <a href="{{ route('home') }}">Home</a> 
        <span class="mx-2">›</span> 
        @php $breadcrumbCats = json_decode($product->category); @endphp
        @if(is_array($breadcrumbCats))
            <a href="{{ route('search', ['categories[]' => $breadcrumbCats[0]]) }}">{{ strtoupper($breadcrumbCats[0]) }}</a> 
        @else
            <a href="{{ route('search', ['categories[]' => $product->category]) }}">{{ strtoupper($product->category) }}</a>
        @endif
        <span class="mx-2">›</span> 
        <span class="text-dark fw-medium">{{ $product->name }}</span>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="product-img-container" id="manualGallery">
                @php
                    $galleryImages = $product->gallery ? json_decode($product->gallery) : [];
                    $allImgs = array_merge([$product->image], $galleryImages);
                @endphp

                @foreach($allImgs as $index => $img)
                    <img src="{{ asset($img) }}" class="slide-img-main {{ $index == 0 ? 'active' : '' }}" data-index="{{ $index }}" alt="{{ $product->name }}">
                @endforeach

                @if(count($allImgs) > 1)
                    <div class="nav-arrow prev-arrow" onclick="moveSlide(-1)">&#10094;</div>
                    <div class="nav-arrow next-arrow" onclick="moveSlide(1)">&#10095;</div>
                @endif
            </div>

            @if(count($allImgs) > 1)
                <div class="thumbnail-container">
                    @foreach($allImgs as $index => $img)
                        <img src="{{ asset($img) }}" class="thumb-img {{ $index == 0 ? 'active' : '' }}" onclick="setSlide({{ $index }})" alt="angle">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            <div class="product-details-container">
                {{-- 🔥 Category Badge Fix --}}
                @if($product->category)
                    <div class="product-category-badge">
                        @php $badgeCats = json_decode($product->category); @endphp
                        @if(is_array($badgeCats))
                            {{ implode(', ', array_map('strtoupper', $badgeCats)) }}
                        @else
                            {{ strtoupper($product->category) }}
                        @endif
                    </div>
                @endif

                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-price-container">
                    <h2 class="product-price">₹{{ number_format($product->price, 2) }}</h2>
                    @if(isset($product) && $product->stock > 0)
                        <span class="product-stock stock-in">In Stock</span>
                    @else
                        <span class="product-stock stock-out">Out of Stock</span>
                    @endif
                </div>

                <div class="product-description">
                    {{ $product->description ?? 'Experience the epitome of elegance with this premium piece from Shringar.' }}
                </div>

                <div class="action-buttons">
                    @if($product->stock > 0)
                        <div class="quantity-selector d-flex align-items-center me-3 mb-3">
                            <button type="button" class="qty-btn" onclick="changeQty('decrease')">−</button>
                            <input type="number" id="productQty" value="1" min="1" max="{{ $product->stock }}" readonly class="qty-input">
                            <button type="button" class="qty-btn" onclick="changeQty('increase', {{ $product->stock }})">+</button>
                        </div>

                        <button class="btn-add-cart add-to-cart mb-3" id="addToCartBtn" data-id="{{ $product->id }}">
                            <span style="font-size: 18px;">🛒</span> Add to Cart
                        </button>
                    @else
                        <button disabled class="btn-add-cart w-100 mb-3" style="background: #e0e0e0; color: #888;">Out of Stock</button>
                    @endif

                    @auth
                        <a href="javascript:void(0)" onclick="buyNowWithQty({{ $product->id }})" class="btn-buy-now w-100 mb-3">BUY NOW</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-buy-now w-100 mb-3">Login to Buy</a>
                    @endauth

                    <div class="d-flex gap-2 w-100">
                        <button class="btn-wishlist-large wishlist-btn flex-fill" data-id="{{ $product->id }}">
                            {{ in_array($product->id, $wishlistIds ?? []) ? '💖' : '🤍' }} 
                            <span style="font-size: 16px; margin-left: 8px; color: #333;">Wishlist</span>
                        </button>

                        <button type="button" onclick="shareProduct('{{ addslashes($product->name) }}', 'Check this out!', '{{ url()->current() }}')" class="btn-wishlist-large flex-fill" style="color: #c59d5f;">
                            <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                            <span style="font-size: 16px; margin-left: 8px; color: #333;">Share</span>
                        </button>
                    </div>
                </div>

                <div class="trust-badges">
                    <div class="trust-item"><span class="trust-icon">🚚</span><span class="trust-text">Fast & Safe<br>Delivery</span></div>
                    <div class="trust-item"><span class="trust-icon">💎</span><span class="trust-text">Premium<br>Quality</span></div>
                    <div class="trust-item"><span class="trust-icon">🔒</span><span class="trust-text">Secure<br>Checkout</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 pt-4" style="border-top: 1px solid #f5ebe9;">
        <div class="mb-4 text-center">
            <h3 class="fw-bold mb-1" style="color: #5E1929;">Customer Stories</h3>
            <p class="text-muted small">Real experiences from our Shringar family</p>
        </div>
        <div class="row g-4">
            @forelse($product->reviews as $review)
                <div class="col-12 col-lg-6">
                    <div class="review-card landscape-card h-100 p-4 rounded-4 shadow-sm bg-white border d-flex align-items-center">
                        <div class="avatar-wrapper me-4 text-center flex-shrink-0">
                            <div class="avatar-lg d-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 65px; height: 65px; background: #fdfaf5; color: #c59d5f; font-weight: 700; border: 1px solid #e5c07b; font-size: 24px;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $review->user->name }}</h6>
                                    <div class="text-success small fw-600">✔ Verified Buyer</div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="mb-2" style="color: #c59d5f;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="text-muted mb-0" style="font-size: 14px; font-style: italic;">"{{ $review->comment }}"</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5"><p class="text-muted">No reviews yet. Be the first to share your experience!</p></div>
            @endforelse
        </div>
    </div>

    @if(isset($suggestedProducts) && $suggestedProducts->count() > 0)
    <div class="container mt-5 pt-5" style="border-top: 1px solid #f5ebe9;">
        <div class="mb-4 text-center">
            <h3 class="fw-bold mb-1" style="color: #5E1929;">You May Also Like</h3>
            <p class="text-muted small">Explore more from our collection</p>
        </div>
        <div class="row">
            @foreach($suggestedProducts as $row)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="premium-card"> 
                    <button class="wishlist-btn-card wishlist-btn" data-id="{{ $row->id }}">{{ in_array($row->id, $wishlistIds ?? []) ? '💖' : '🤍' }}</button>
                    <a href="{{ route('product.show', $row->id) }}">
                        <div class="product-img-wrapper-card"><img src="{{ $row->image ? asset($row->image) : asset('images/default.png') }}"></div>
                    </a>
                    <div class="card-body-custom p-3 text-center">
                        <h6 class="card-title-custom fw-bold mb-1">{{ $row->name }}</h6>
                        <p class="card-price-custom text-danger mb-2">₹{{ number_format($row->price, 2) }}</p>
                        <a href="{{ route('product.show', $row->id) }}" class="btn-view-card w-100 d-block">View Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    // 1. MANUAL SLIDER LOGIC
    let currentSlideIdx = 0;
    const mainSlides = document.querySelectorAll('.slide-img-main');
    const thumbImgs = document.querySelectorAll('.thumb-img');

    function updateSliderDisplay(n) {
        if (n >= mainSlides.length) currentSlideIdx = 0;
        else if (n < 0) currentSlideIdx = mainSlides.length - 1;
        else currentSlideIdx = n;

        mainSlides.forEach(s => s.classList.remove('active'));
        thumbImgs.forEach(t => t.classList.remove('active'));

        mainSlides[currentSlideIdx].classList.add('active');
        if(thumbImgs[currentSlideIdx]) thumbImgs[currentSlideIdx].classList.add('active');
    }

    function moveSlide(step) { updateSliderDisplay(currentSlideIdx + step); }
    function setSlide(index) { updateSliderDisplay(index); }

    // 2. QUANTITY SELECTOR
    function changeQty(type, maxStock) {
        let qtyInput = document.getElementById('productQty');
        if(!qtyInput) return;
        let currentQty = parseInt(qtyInput.value);
        if (type === 'increase' && currentQty < maxStock) qtyInput.value = currentQty + 1;
        else if (type === 'decrease' && currentQty > 1) qtyInput.value = currentQty - 1;
    }

    // 3. BUY NOW
    function buyNowWithQty(productId) {
        let qty = document.getElementById('productQty').value;
        const baseUrl = window.BASE_URL || "{{ url('/') }}";
        window.location.href = `${baseUrl}/checkout?product_id=${productId}&quantity=${qty}`;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const baseUrl = window.BASE_URL || "{{ url('/') }}";

        function handleAuth(res) {
            if (res.status === 401 || res.status === 403) {
                window.location.href = `${baseUrl}/login`;
                return true;
            }
            return false;
        }

        // Add To Cart logic
        const addToCartBtn = document.getElementById('addToCartBtn');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function () {
                let id = this.dataset.id;
                let qty = document.getElementById('productQty').value;
                let originalText = this.innerHTML;
                this.innerHTML = "⏳ Adding...";
                this.disabled = true;

                fetch(`${baseUrl}/cart/add/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ quantity: qty })
                })
                .then(res => handleAuth(res) ? null : res.json())
                .then(data => {
                    if (data && (data.status === 'success' || data.cart_count !== undefined)) {
                        if (document.getElementById('cart-count')) document.getElementById('cart-count').innerText = data.cart_count;
                        this.innerHTML = "✅ Added!";
                        if (typeof showToast === 'function') showToast(`Added to cart!`);
                    }
                    setTimeout(() => { this.innerHTML = originalText; this.disabled = false; }, 2000);
                })
                .catch(err => { this.innerHTML = originalText; this.disabled = false; });
            });
        }

        // Wishlist Toggle
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                let el = this;
                fetch(`${baseUrl}/wishlist-toggle/${this.dataset.id}`, {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content, "Accept": "application/json" }
                })
                .then(res => handleAuth(res) ? null : res.json())
                .then(data => {
                    if(data) {
                        el.innerHTML = el.innerHTML.includes('💖') || el.innerHTML.includes('🤍') 
                            ? el.innerHTML.replace(/💖|🤍/, data.status === "added" ? "💖" : "🤍")
                            : (data.status === "added" ? "💖" : "🤍");
                        if (typeof showToast === 'function') showToast(data.status === "added" ? "Added to wishlist!" : "Removed!");
                    }
                });
            });
        });
    });
</script>
@endsection