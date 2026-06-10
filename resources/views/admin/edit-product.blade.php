@extends('layouts.app')

@section('content')

<style>
.form-wrapper {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fdfaf5;
    padding: 40px 0;
}

.form-card {
    background: #fff;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(94, 25, 41, 0.05);
    border: 1px solid #f5ebe9;
    width: 100%;
    max-width: 650px; /* Thoda wide kiya gallery ke liye */
}

.form-title {
    text-align: center;
    color: #5E1929;
    font-weight: 700;
    margin-bottom: 25px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #eee;
}

textarea.form-control { height: 100px; }

.btn-update {
    background: linear-gradient(135deg, #c59d5f, #a88347);
    border: none;
    border-radius: 10px;
    height: 50px;
    color: #fff;
    font-weight: 600;
    letter-spacing: 1px;
}

.current-img-box {
    background: #fdfaf5;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #f5ebe9;
    display: inline-block;
    margin-right: 10px;
    margin-bottom: 10px;
}

/* Multiple Select Height */
select[multiple] {
    height: 120px !important;
}
</style>

<div class="form-wrapper">
    <div class="form-card">
        <h2 class="form-title">✏️ Edit Product</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="small fw-bold mb-1">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="small fw-bold mb-1">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="small fw-bold mb-1">Price (₹)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="small fw-bold mb-1">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="small fw-bold mb-1">Categories (Select Multiple)</label>
                <select name="categories[]" class="form-select" multiple required>
                    @php
                        // Database se saved categories ko array mein badalna
                        $selectedCats = json_decode($product->category) ?? [$product->category];
                        // Sabhi available categories (AdminController se pass honi chahiye, ya direct fetch)
                        $allCategories = \App\Models\Category::all();
                    @endphp
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->name }}" {{ in_array($cat->name, $selectedCats) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Hold CTRL to select more than one.</small>
            </div>

            <div class="mb-3">
                <label class="d-block small fw-bold mb-2">Current Main Image</label>
                <div class="current-img-box">
                    <img src="{{ asset($product->image) }}" width="80" class="rounded">
                </div>
                <input type="file" name="image" class="form-control mt-2">
                <small class="text-muted">Upload to change main photo.</small>
            </div>

            <div class="mb-4">
                <label class="d-block small fw-bold mb-2">Gallery Images (Angles)</label>
                @if($product->gallery)
                    <div class="d-flex flex-wrap">
                        @foreach(json_decode($product->gallery) as $g_img)
                            <div class="current-img-box">
                                <img src="{{ asset($g_img) }}" width="60" class="rounded">
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" name="gallery[]" class="form-control mt-2" multiple>
                <small class="text-muted">Choose up to 4 images to replace current gallery.</small>
            </div>

            <button type="submit" class="btn btn-update w-100" id="updateBtn">
                ✅ Save All Changes
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('editForm').addEventListener('submit', function(){
    let btn = document.getElementById('updateBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating Product...';
    btn.disabled = true;
});
</script>

@endsection