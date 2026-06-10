@extends('layouts.app')

@section('content')

<style>
.form-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
}

.form-card {
    background: #fff;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(94, 25, 41, 0.05);
    border: 1px solid #f5ebe9;
    width: 100%;
    max-width: 550px;
}

.form-title {
    text-align: center;
    color: #5E1929;
    font-weight: 700;
    margin-bottom: 25px;
}

.form-control, .form-select {
    border-radius: 10px;
    height: 45px;
    border: 1px solid #eee;
}

.btn-submit {
    background: linear-gradient(135deg, #5E1929, #802336);
    border: none;
    border-radius: 10px;
    height: 50px;
    color: #fff;
    font-weight: 600;
    letter-spacing: 1px;
    transition: 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(94, 25, 41, 0.2);
    color: #fff;
}

.img-preview {
    height: 120px;
    width: 120px;
    object-fit: cover;
    margin-top: 15px;
    border-radius: 10px;
    border: 1px solid #f5ebe9;
    display: none;
}
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        
        <div class="col-md-2 d-none d-md-block">
            @include('partials.admin-sidebar')
        </div>

        <div class="col-md-10" style="background-color: #fdfaf5;">
            
            <div class="form-wrapper">
                <div class="form-card">
                    <h2 class="form-title">✨ Add New Jewellery</h2>

                    @if(session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf

                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Product Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Product Name" required>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Description</label>
                            <textarea name="description" class="form-control" placeholder="Describe about your product" style="height: 100px;"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold mb-1">Price (₹)</label>
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold mb-1">Stock Quantity</label>
                                <input type="number" name="stock" value="1" class="form-control" placeholder="Available units">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small">Select Categories (Multiple)</label>
                            <select name="categories[]" class="form-select" multiple style="height: 150px;">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Tip: CTRL dabba kar multiple options select karein.</small>
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold mb-1">Product Image</label>
                            <input type="file" name="image" class="form-control" onchange="previewImage(event)" required>
                            <img id="preview" class="img-preview">
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Main Product Image (Thumbnail)</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Gallery Images (Add 4 different angles)</label>
                            <input type="file" name="gallery[]" class="form-control" multiple>
                            <small class="text-muted">You can select up to 4 images at once.</small>
                        </div>

                        <button class="btn btn-submit w-100" id="submitBtn">
                            🚀 Upload Product
                        </button>
                    </form>
                </div>
            </div>

        </div> 
    </div>
</div>

<script>
function previewImage(event) {
    let reader = new FileReader();
    reader.onload = function(){
        let img = document.getElementById('preview');
        img.src = reader.result;
        img.style.display = "block";
    }
    reader.readAsDataURL(event.target.files[0]);
}

document.getElementById('productForm').addEventListener('submit', function () {
    let btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Uploading...';
    btn.disabled = true;
});
</script>

@endsection