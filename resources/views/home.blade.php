@extends('layouts.app')

@section('content')

<style>
/* ================= PREMIUM BANNER ================= */
.carousel-item img { height: 450px; object-fit: cover; }
.carousel-caption { bottom: 20%; left: 8%; right: auto; text-align: left; }
.banner-title { font-size: 50px; font-weight: 600; color: #c59d5f; letter-spacing: 2px; }
.carousel-fade .carousel-item { transition: opacity 1s ease-in-out; }

/* ================= CATEGORY ================= */
.category-section { background: #fdfaf5; padding: 40px 0; border-bottom: 1px solid #f5ebe9; }
.category-item { width: 110px; cursor: pointer; transition: 0.3s; text-decoration: none; display: flex; flex-direction: column; align-items: center; }
.category-item p { margin-top: 12px; font-size: 13px; font-weight: 600; color: #5E1929; text-transform: uppercase; letter-spacing: 0.5px; }

.circle {
    width: 100px; height: 100px; border-radius: 50%; overflow: hidden;
    border: 2px solid #e8d5d1; display: flex; align-items: center; justify-content: center;
    background: #fff; transition: 0.3s; box-shadow: 0 4px 10px rgba(94, 25, 41, 0.05);
}
.circle img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }

.category-item:hover { transform: translateY(-6px); }
.category-item:hover .circle { border-color: #c59d5f; box-shadow: 0 8px 20px rgba(197, 157, 95, 0.2); }
.category-item:hover .circle img { transform: scale(1.1); }

/* ================= PRODUCTS CARD (FIXED LAYOUT) ================= */
.premium-card {
    position: relative; 
    height: 100%; display: flex; flex-direction: column; background: #fff;
    border-radius: 12px; border: 1px solid #f5ebe9; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;
}
.premium-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(94, 25, 41, 0.08); border-color: #e8d5d1; }

/* 🔥 AUTO-SLIDER CSS */
.product-img-wrapper { 
    position: relative; 
    padding-top: 100%; 
    background: #fdfaf5; 
    overflow: hidden;
}

.slide-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 15px;
    opacity: 0;
    transition: opacity 0.4s ease-in-out; /* Thoda fast transition hover ke liye */
}

.slide-img.active {
    opacity: 1;
    z-index: 1;
}

.card-body { padding: 20px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
.product-title { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.product-price { color: #5E1929; font-size: 18px; font-weight: 700; margin-bottom: 15px; }

/* Buttons inside Card */
.btn-view {
    padding: 8px 20px; border: 1px solid #5E1929; color: #5E1929; background: transparent;
    border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; text-decoration: none;
}
.btn-view:hover { background: #5E1929; color: #fff; }

.btn-add {
    padding: 8px 20px; background: linear-gradient(135deg, #c59d5f, #e5c07b); color: #fff;
    border: none; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;
}
.btn-add:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(197, 157, 95, 0.3); }
.btn-add:disabled { background: #ccc; box-shadow: none; cursor: not-allowed; }

/* Wishlist absolute */
.wishlist-btn-card {
    position: absolute; top: 12px; right: 12px; background: #fff; border: 1px solid #eee;
    border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;
    font-size: 16px; cursor: pointer; z-index: 10; transition: 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.wishlist-btn-card:hover { background: #fdfaf5; transform: scale(1.1); }

/* Share Button CSS */
.share-btn-card {
    position: absolute; 
    top: 55px;
    right: 12px;
    background: #fff; border: 1px solid #eee; color: #5E1929;
    border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; z-index: 10; transition: 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.share-btn-card:hover { background: #fdfaf5; transform: scale(1.1); color: #c59d5f; }

/* ================= PREMIUM SCROLL ================= */
.premium-slider-wrapper { overflow: hidden; width: 100%; position: relative; }
.premium-slider { display: flex; width: max-content; animation: scrollSlider 25s linear infinite; }
.premium-item { flex: 0 0 auto; margin-right: 20px; }
.premium-item img { width: 250px; height: 250px; object-fit: cover; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

@keyframes scrollSlider { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

/* REVIEWS */
.review-wrapper { overflow: hidden; width: 100%; position: relative; }
.review-track { display: flex; width: max-content; animation: scrollReview 30s linear infinite; }
.review-card {
    width: 320px; margin-right: 25px; padding: 30px 25px; background: #fff; border-radius: 15px;
    text-align: center; box-shadow: 0 5px 20px rgba(94, 25, 41, 0.05); border: 1px solid #f5ebe9;
}
.review-card h6 { margin-top: 15px; font-weight: 700; color: #5E1929; letter-spacing: 0.5px; }

@keyframes scrollReview { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

/* ================= BUDGET BOXES ================= */
.budget-box {
    width: 140px; height: 140px; border: 2px solid #e8d5d1; display: flex; flex-direction: column;
    justify-content: center; align-items: center; text-decoration: none; transition: 0.3s; border-radius: 50%; background: #fff;
}
.budget-box h5 { margin: 5px 0 0 0; font-weight: bold; color: #5E1929; font-size: 22px; }

.section-heading {
    text-align: center; color: #5E1929; font-weight: 700; margin-bottom: 40px; letter-spacing: 1px; position: relative; padding-bottom: 15px;
}
.section-heading::after {
    content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 60px; height: 3px; background: #c59d5f;
}

/* ================= BUDGET BOXES (FIXED) ================= */
.budget-box {
    width: 140px; 
    height: 140px; 
    border: 2px solid #e8d5d1; 
    display: flex; 
    flex-direction: column;
    justify-content: center; 
    align-items: center; 
    text-decoration: none; 
    transition: all 0.3s ease; 
    border-radius: 50%; 
    background: #fff;
}

.budget-box p { 
    margin: 0; 
    font-size: 12px; 
    color: #888; 
    text-transform: uppercase; 
    letter-spacing: 1px; 
}

.budget-box h5 { 
    margin: 5px 0 0 0; 
    font-weight: bold; 
    color: #5E1929; 
    font-size: 22px; 
}

/* Hover Effect */
.budget-box:hover { 
    transform: translateY(-8px); 
    background: #5E1929; 
    border-color: #5E1929; 
    box-shadow: 0 10px 20px rgba(94, 25, 41, 0.15);
}

.budget-box:hover p { 
    color: #d8c3c8; 
}

.budget-box:hover h5 { 
    color: #c59d5f; 
}

/* Responsive adjustment for Mobile */
@media (max-width: 768px) {
    .budget-box { 
        width: 100px; 
        height: 100px; 
    }
    .budget-box h5 { 
        font-size: 16px; 
    }
    .budget-box p { 
        font-size: 10px; 
    }
}

</style>

<div class="container-fluid p-0">
    <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3500">
        <div class="carousel-inner">
            <div class="carousel-item active"><img src="{{ asset('images/banner1.png') }}" class="d-block w-100"></div>
            <div class="carousel-item"><img src="{{ asset('images/banner2.png') }}" class="d-block w-100"></div>
            <div class="carousel-item"><img src="{{ asset('images/banner3.png') }}" class="d-block w-100"></div>
        </div>
    </div>
</div>

<div class="container-fluid category-section">
    <div class="d-flex justify-content-center flex-wrap gap-3 gap-md-4">
        @foreach(\App\Models\Category::all() as $cat)
            <a href="{{ route('search', ['categories[]' => $cat->name]) }}" class="category-item">
                <div class="circle">
                    <img src="{{ $cat->image ? asset($cat->image) : asset('images/categories/default.jpg') }}" alt="{{ $cat->name }}">
                </div>
                <p>{{ $cat->name }}</p>
            </a>
        @endforeach
    </div>
</div>

<div class="container mt-5 pt-4">
    <h3 class="section-heading">What's New</h3>
    <div class="row">
        @php 
            // 1. Sabse pehle try karte hain database ke JSON structure ke hisaab se
            $filtered = \App\Models\Product::where('category', 'LIKE', '%what\'s-new%') // Normal case
                        ->orWhere('category', 'LIKE', '%what-s-new%') // Dash case
                        ->latest()
                        ->take(8)
                        ->get();
            
            // 2. Agar phir bhi product nahi mil raha (Purane products ke liye jo array nahi string thay)
            if($filtered->isEmpty()){
                $filtered = \App\Models\Product::where('category', 'what\'s-new')
                            ->latest()
                            ->take(8)
                            ->get();
            }
        @endphp

        @forelse($filtered as $row)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="premium-card"> 
                <button type="button" class="wishlist-btn-card wishlist-btn" data-id="{{ $row->id }}">
                    {{ in_array($row->id, $wishlistIds ?? []) ? '💖' : '🤍' }}
                </button>
                
                <button type="button" class="share-btn-card" onclick="shareProduct('{{ addslashes($row->name) }}', 'Check out!', '{{ route('product.show', $row->id) }}')">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                </button>

                <a href="{{ route('product.show', $row->id) }}">
                    <div class="product-img-wrapper auto-slider">
                        <img src="{{ $row->image ? asset($row->image) : asset('images/default.png') }}" class="slide-img active">
                        
                        @if($row->gallery)
                            @php $gallery = json_decode($row->gallery); @endphp
                            @if(is_array($gallery))
                                @foreach($gallery as $g_img)
                                    <img src="{{ asset($g_img) }}" class="slide-img">
                                @endforeach
                            @endif
                        @endif
                    </div>
                </a>

                <div class="card-body">
                    <h6 class="product-title">{{ $row->name }}</h6>
                    <p class="product-price">₹{{ number_format($row->price, 2) }}</p>
                    <div class="d-flex gap-2 w-100 mt-auto">
                        <a href="{{ route('product.show', $row->id) }}" class="btn-view text-center" style="flex:1;">View</a>
                        <button class="btn-add add-to-cart" data-id="{{ $row->id }}" style="flex:1;">Add</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">More exciting products arriving soon! ✨</p>
            <p class="small text-danger">Debug Tip: Category in DB must match "what's-new"</p>
        </div>
        @endforelse
    </div>
</div>

<div class="container mt-5 pt-4">
    <h3 class="section-heading">Premium Collection</h3>
    <div class="premium-slider-wrapper">
        <div class="premium-slider">
            @foreach([1,2,3,4,5,1,2,3,4,5] as $img)
            <div class="premium-item"><img src="{{ asset('images/collection/'.$img.'.jpg') }}"></div>
            @endforeach
        </div>
    </div>
</div>

<div class="container mt-5 pt-4 text-center">
    <h3 class="section-heading">Shop by Budget</h3>
    <div class="d-flex justify-content-center flex-wrap gap-3 gap-md-4 mt-4">
        @foreach(['199', '399', '699', '999', '1099'] as $p)
        <a href="{{ route('search', ['price_max' => $p]) }}" class="budget-box">
            <p>Under</p><h5>₹{{ $p }}</h5>
        </a>
        @endforeach
    </div>
</div>

<div class="container mt-5 pt-4 mb-5">
    <h3 class="section-heading">What Our Customers Say</h3>
    <div class="review-wrapper mt-4">
        <div class="review-track">
            @foreach(['Amila M.', 'Yash K.', 'Deepali B.', 'Rahul S.', 'Sneha G.'] as $name)
            <div class="review-card">
                <div style="color: #c59d5f; font-size:20px; margin-bottom:5px;">★★★★★</div>
                <h6>{{ $name }}</h6>
                <p>"Premium quality jewelry, exactly as shown!"</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const cards = document.querySelectorAll('.premium-card');

    cards.forEach(card => {
        const images = card.querySelectorAll('.slide-img');
        if (images.length <= 1) return;

        let interval;
        let currentIndex = 0;

        // Mouse image par aate hi cycle shuru
        card.addEventListener('mouseenter', () => {
            interval = setInterval(() => {
                images[currentIndex].classList.remove('active');
                currentIndex = (currentIndex + 1) % images.length;
                images[currentIndex].classList.add('active');
            }, 1000); // Har 1 second mein image badlegi jab tak cursor upar hai
        });

        // Mouse hat te hi wapas Main Image (Index 0) par
        card.addEventListener('mouseleave', () => {
            clearInterval(interval);
            // Sabhi images se active hatao
            images.forEach(img => img.classList.remove('active'));
            // Pehli image ko wapas active kar do
            currentIndex = 0;
            images[0].classList.add('active');
        });
    });
});
</script>

@endsection