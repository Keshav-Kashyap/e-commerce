@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <div class="col-md-2 d-none d-md-block">
            @include('partials.admin-sidebar')
        </div>

        <div class="col-md-10 p-4 p-md-5" style="background-color: #fdfaf5; min-height: 100vh;">
            
            <h3 style="color: #5E1929; font-weight: bold; margin-bottom: 25px;">📂 Manage Categories</h3>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm p-4 border-0" style="border-radius: 15px;">
                
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="mb-5">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="small fw-bold mb-2 text-muted">Category Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="e.g. Bangles, Necklaces" required style="border-radius: 8px; height: 48px;">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold mb-2 text-muted">Category Image (Circle View)</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                                   required style="border-radius: 8px; height: 48px; padding: 10px;">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn text-white w-100 fw-bold shadow-sm" style="background: linear-gradient(135deg, #5E1929, #802336); border-radius: 8px; border: none; height: 48px;">
                                ➕ Add Category
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead style="background: #5E1929; color: #fdfaf5;">
                            <tr>
                                <th class="p-3 border-0" style="font-weight: 600; letter-spacing: 1px; width: 100px;">IMAGE</th>
                                <th class="p-3 border-0" style="font-weight: 600; letter-spacing: 1px;">CATEGORY NAME</th>
                                <th class="text-end p-3 border-0" style="font-weight: 600; letter-spacing: 1px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr style="border-bottom: 1px solid #f5ebe9; transition: 0.3s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='transparent'">
                                <td class="p-3">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 1px solid #eee;">
                                        <img src="{{ $category->image ? asset($category->image) : asset('images/categories/default.jpg') }}" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </td>
                                <td class="fw-bold p-3" style="color: #333; font-size: 15px;">
                                    {{ $category->name }}
                                </td>
                                <td class="text-end p-3">
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-2 fw-bold" style="border-radius: 6px;">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <div style="font-size: 30px; opacity: 0.3; margin-bottom: 10px;">📂</div>
                                    No categories found. Add your first category above!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div> 
    </div>
</div>
@endsection