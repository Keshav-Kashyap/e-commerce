@extends('layouts.app')

@section('content')

<style>
.category-title {
    color: #c59d5f;
    font-weight: 600;
}

/* Product Card */
.product-card {
    border-radius: 15px;
    overflow: hidden;
    transition: 0.3s;
    position: relative;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.product-card img {
    height: 220px;
    object-fit: cover;
}

.price {
    color: #28a745;
    font-weight: bold;
}

/* Wishlist btn */
.wishlist-btn {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 10px;
}

/* Empty state */
.empty-box {
    padding: 50px;
    text-align: center;
    color: #777;
}
</style>

<div class="container mt-5">

    <h3 class="mb-4 text-center category-title">
        {{ ucfirst($name) }}
    </h3>

    <div class="row">

        @forelse($products as $row)

        <div class="col-md-3 mb-4">
            <div class="card product-card border-0">

                <!-- ❤️ WISHLIST BUTTON -->
                <button 
                    class="wishlist-btn"
                    data-id="{{ $row->id }}">
                    {{ in_array($row->id, $wishlistIds ?? []) ? '💖' : '🤍' }}
                </button>

                <!-- IMAGE -->
                <img src="{{ $row->image 
                    ? asset($row->image) 
                    : asset('images/default.png') }}" 
                    class="card-img-top">

                <div class="card-body text-center">

                    <h5 class="fw-bold">{{ $row->name }}</h5>

                    <p class="price mb-2">₹{{ $row->price }}</p>

                    <div class="d-flex justify-content-center gap-2">

                        <!-- VIEW -->
                        <a href="{{ route('product.show', $row->id) }}" 
                           class="btn btn-sm btn-outline-dark px-3">
                            View
                        </a>

                        <!-- ADD TO CART -->
                        <button 
                            class="btn btn-sm btn-dark px-3 add-to-cart" 
                            data-id="{{ $row->id }}">
                            Add
                        </button>

                    </div>

                </div>

            </div>
        </div>

        @empty

        <div class="empty-box">
            <h4>No products in this category 😢</h4>
            <a href="{{ route('home') }}" class="btn btn-outline-dark mt-3">
                Go Shopping →
            </a>
        </div>

        @endforelse

    </div>

</div>


{{-- 🔥 FINAL JS --}}
<script>

document.addEventListener("DOMContentLoaded", function () {

    // 🔥 COMMON LOGIN HANDLER
    function handleAuth(res){
        if(res.redirected || res.status === 401){
            window.location.href = "/login";
            return true;
        }
        return false;
    }

    // ================= ADD TO CART =================
    document.querySelectorAll('.add-to-cart').forEach(btn => {

        btn.addEventListener('click', function () {

            let id = this.dataset.id;

            fetch(`/add-to-cart/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            })
            .then(res => {
                if(handleAuth(res)) return;
                return res.json();
            })
            .then(data => {

                if(!data) return;

                // 🔥 cart count update
                let count = document.getElementById('cart-count');
                if(count){
                    count.innerText = parseInt(count.innerText) + 1;
                }

            })
            .catch(err => console.log(err));

        });

    });


    // ================= WISHLIST =================
    document.querySelectorAll('.wishlist-btn').forEach(btn => {

        btn.addEventListener('click', function () {

            let el = this;
            let id = el.dataset.id;

            fetch(`/wishlist-toggle/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            })
            .then(res => {
                if(handleAuth(res)) return;
                return res.json();
            })
            .then(data => {

                if(!data) return;

                // 🔥 toggle icon
                if(data.status === "added"){
                    el.innerText = "💖";
                } else {
                    el.innerText = "🤍";
                }

            })
            .catch(err => console.log(err));

        });

    });

});

</script>

@endsection