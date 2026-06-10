<style>
/* ================= PREMIUM CART STYLES ================= */
.cart-items-container {
    max-height: calc(100vh - 250px);
    overflow-y: auto;
    padding-right: 5px;
}

/* Custom Scrollbar for Cart */
.cart-items-container::-webkit-scrollbar { width: 5px; }
.cart-items-container::-webkit-scrollbar-track { background: #fdfaf5; border-radius: 10px; }
.cart-items-container::-webkit-scrollbar-thumb { background: #e8d5d1; border-radius: 10px; }

.cart-drawer-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px dashed #e8d5d1;
    position: relative;
}
.cart-drawer-item:last-child { border-bottom: none; }

.cart-item-img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #f5ebe9;
    background: #fdfaf5;
    padding: 2px;
}

.cart-item-details { flex: 1; }

.cart-item-title {
    font-size: 14px;
    font-weight: 600;
    color: #5E1929;
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.3;
}

.cart-item-price {
    font-size: 15px;
    font-weight: 700;
    color: #c59d5f;
}

/* Quantity Controls */
.qty-controls {
    display: flex;
    align-items: center;
    background: #fdfaf5;
    border: 1px solid #e8d5d1;
    border-radius: 20px;
    width: max-content;
    margin-top: 8px;
    overflow: hidden;
}

.qty-btn {
    background: transparent;
    border: none;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #5E1929;
    cursor: pointer;
    transition: 0.3s;
}
.qty-btn:hover:not(:disabled) { background: #f5ebe9; }
.qty-btn:disabled { color: #ccc; cursor: not-allowed; }

.qty-val {
    font-size: 13px;
    font-weight: 600;
    color: #333;
    width: 24px;
    text-align: center;
}

/* Delete Button */
.btn-remove-item {
    background: #fff0f0;
    border: 1px solid #ffccc7;
    color: #dc3545;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
}
.btn-remove-item:hover { background: #dc3545; color: #fff; transform: scale(1.1); border-color: #dc3545; }

/* Status Messages */
.stock-warning {
    color: #dc3545;
    font-size: 11px;
    font-weight: 600;
    margin-top: 4px;
    display: block;
    background: #fff0f0;
    padding: 2px 8px;
    border-radius: 4px;
    width: max-content;
}

/* Empty State */
.cart-empty { text-align: center; padding: 40px 20px; }
.cart-empty-icon { font-size: 50px; margin-bottom: 15px; opacity: 0.5; }
.cart-empty h5 { color: #5E1929; font-weight: 600; font-size: 18px; }
.cart-empty p { color: #888; font-size: 13px; margin-bottom: 20px; }
.btn-shop-now {
    display: inline-block; background: #5E1929; color: #fff; padding: 10px 25px;
    border-radius: 25px; text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.3s;
}
.btn-shop-now:hover { background: #c59d5f; color: #fff; transform: translateY(-2px); }

/* Footer / Checkout Section */
.cart-footer {
    background: #fdfaf5;
    border-radius: 12px;
    padding: 20px;
    margin-top: 15px;
    border: 1px solid #f5ebe9;
}
.cart-total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.cart-total-label { font-size: 14px; color: #555; font-weight: 600; }
.cart-total-val { font-size: 22px; color: #5E1929; font-weight: 700; }

.btn-checkout-premium {
    display: block; width: 100%; background: linear-gradient(135deg, #c59d5f, #e5c07b);
    color: #fff; text-align: center; padding: 14px; border-radius: 8px;
    font-weight: 600; text-transform: uppercase; letter-spacing: 1px; text-decoration: none;
    transition: 0.3s; border: none; margin-top: 15px;
}
.btn-checkout-premium:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(197, 157, 95, 0.4); color: #fff; }
</style>

@php $grandTotal = 0; @endphp

<div class="cart-items-container">
    @forelse($cartItems as $item)
        @php 
            $product = $item->product;
            $price = $product ? $product->price : 0;
            $stock = $product ? $product->stock : 0;
            $total = $price * $item->quantity;
            $grandTotal += $total;
        @endphp

        <div class="cart-drawer-item">

            <img src="{{ $product && $product->image ? asset($product->image) : asset('images/default.png') }}" class="cart-item-img" alt="Product">

            <div class="cart-item-details">

                <div class="cart-item-title">
                    {{ $product->name ?? 'Product Unavailable' }}
                </div>

                <div class="cart-item-price">₹{{ number_format($price, 2) }}</div>

                @if(!$product)
                    <span class="stock-warning">⚠ Product removed</span>
                @elseif($stock == 0)
                    <span class="stock-warning">Out of Stock</span>
                @else
                    <div class="qty-controls">
                        <button type="button" class="qty-btn" onclick="updateCart({{ $item->id }}, 'decrease')">-</button>
                        <div class="qty-val">{{ $item->quantity }}</div>
                        <button type="button" class="qty-btn" onclick="updateCart({{ $item->id }}, 'increase')" {{ $item->quantity >= $stock ? 'disabled' : '' }}>+</button>
                    </div>
                @endif

            </div>

            <button type="button" onclick="removeItem({{ $item->id }})" class="btn-remove-item" title="Remove Item">
                🗑️
            </button>

        </div>

    @empty
        <div class="cart-empty">
            <div class="cart-empty-icon">🛒</div>
            <h5>Your Cart is Empty</h5>
            <p>Discover our beautiful jewellery collection and add items to your cart.</p>
            <a href="{{ route('search') }}" class="btn-shop-now" onclick="closeCart()">Start Shopping</a>
        </div>
    @endforelse
</div>

@if($grandTotal > 0)
    <div class="cart-footer">
        <div class="cart-total-row">
            <span class="cart-total-label">Subtotal</span>
            <span class="cart-total-val">₹{{ number_format($grandTotal, 2) }}</span>
        </div>
        
        <p style="font-size: 11px; color: #888; text-align: center; margin-bottom: 0;">
            Shipping & taxes calculated at checkout.
        </p>

        {{-- 🔥 LOGIN SAFE CHECKOUT --}}
        @auth
            <a href="{{ route('checkout') }}" class="btn-checkout-premium">
                Proceed to Checkout
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-checkout-premium">
                Login to Checkout
            </a>
        @endauth
    </div>
@endif