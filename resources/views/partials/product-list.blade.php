<div class="row px-2 px-md-0">
    @forelse($products as $product)
        <div class="col-md-4 col-sm-6 col-6 mb-4 px-1 px-md-3">
            <div class="premium-card">
                <a href="{{ route('product.show', $product->id) }}">
                    <div class="product-img-wrapper">
                        <img src="{{ $product->image ? asset($product->image) : asset('images/default.png') }}" alt="{{ $product->name }}">
                    </div>
                </a>
                <div class="card-body">
                    <h5 class="product-title">{{ $product->name }}</h5>
                    <div class="product-price">₹{{ number_format($product->price, 2) }}</div>
                    <a href="{{ route('product.show', $product->id) }}" class="btn-view-product w-100">View Details</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h4>No products found</h4>
                <p class="text-muted">We couldn't find any items matching your selected filters.</p>
                <a href="{{ route('search') }}" class="btn btn-premium mt-3" style="max-width:200px; margin:0 auto; background:#c59d5f; color:#fff; border:none;">Clear Filters</a>
            </div>
        </div>
    @endforelse
</div>