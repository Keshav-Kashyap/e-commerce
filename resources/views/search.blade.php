@extends('layouts.app')

@section('content')

<style>
    /* Premium Search Page Styling */
    body { background-color: #fdfaf5; }
    
    .search-page-header {
        background: linear-gradient(rgba(94, 25, 41, 0.9), rgba(94, 25, 41, 0.9)), url('{{ asset("images/banner1.png") }}');
        background-size: cover;
        background-position: center;
        padding: 60px 0;
        text-align: center;
        color: #fff;
        margin-bottom: 40px;
    }
    
    .search-page-header h2 { font-weight: 700; letter-spacing: 1px; margin-bottom: 10px; }
    .search-page-header p { color: #c59d5f; font-size: 16px; font-style: italic; }

    /* Product Cards */
    .premium-card {
        background: #fff; border-radius: 12px; border: 1px solid #f5ebe9;
        overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;
        height: 100%; display: flex; flex-direction: column;
    }
    .premium-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(94, 25, 41, 0.08); border-color: #e8d5d1; }
    
    .product-img-wrapper { position: relative; padding-top: 100%; background: #fdfaf5; overflow: hidden; }
    .product-img-wrapper img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .premium-card:hover .product-img-wrapper img { transform: scale(1.08); }
    
    .card-body { padding: 20px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .product-title { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-price { color: #5E1929; font-size: 18px; font-weight: 700; margin-bottom: 15px; }
    
    .btn-view-product {
        display: inline-block; padding: 10px 25px; background: transparent; color: #5E1929;
        border: 1px solid #5E1929; border-radius: 25px; font-size: 13px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; text-decoration: none;
    }
    .btn-view-product:hover { background: #5E1929; color: #fff; }

    /* Filters Sidebar */
    .filter-sidebar {
        background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #f5ebe9;
        position: sticky; top: 170px;
    }
    .filter-title { font-size: 16px; font-weight: 700; color: #5E1929; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
    .filter-item { margin-bottom: 12px; display: flex; align-items: center; }
    .filter-item label { color: #555; font-size: 14px; cursor: pointer; margin-left: 10px; margin-bottom: 0; }
    .custom-checkbox { accent-color: #5E1929; width: 18px; height: 18px; cursor: pointer; }

    /* Mobile Filter Styles */
    @media(max-width: 991px) {
        .filter-sidebar {
            position: fixed; top: 0; left: -350px; width: 300px; height: 100vh;
            z-index: 2000; border-radius: 0; margin: 0; overflow-y: auto; transition: left 0.4s ease;
        }
        .filter-sidebar.open { left: 0; }
    }
</style>

<div class="search-page-header">
    <div class="container">
        <h2>
            @if(!empty($query))
                Search Results for "{{ $query }}"
            @else
                Our Collection
            @endif
        </h2>
        <p>Discover the elegance you deserve</p>
    </div>
</div>

<div class="container mb-5">
    <form id="filterForm" action="{{ route('search') }}" method="GET">
        <input type="hidden" name="q" value="{{ request('q') ?? request('query') }}">

        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar shadow-sm" id="filterSidebar">
                    <div class="mobile-filter-header d-lg-none d-flex justify-content-between align-items-center mb-3">
                        <h5 class="m-0 fw-bold" style="color:#5E1929;">Filters</h5>
                        <button type="button" onclick="toggleFilterDrawer()" class="btn-close"></button>
                    </div>

                    <h5 class="filter-title d-none d-lg-block">Filters</h5>
                    
                    <div class="mb-4">
                        <h6 style="font-size:14px; font-weight:700; color:#5E1929; margin-bottom: 15px;">Categories</h6>
                        @php 
                            $selectedCats = request('categories', []); 
                            $dbCategories = \App\Models\Category::all();
                        @endphp
                        @foreach($dbCategories as $cat)
                        <div class="filter-item">
                            <input type="checkbox" name="categories[]" value="{{ $cat->name }}" class="custom-checkbox filter-ajax" id="cat-{{ $cat->id }}" {{ in_array($cat->name, $selectedCats) ? 'checked' : '' }}>
                            <label for="cat-{{ $cat->id }}">{{ $cat->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mb-4">
                        <h6 style="font-size:14px; font-weight:700; color:#5E1929; margin-bottom: 15px;">Budget Range</h6>
                        
                        @php $currentPrice = request('price_max'); @endphp
                        
                        <div class="filter-item">
                            <input type="radio" name="price_max" value="" class="custom-checkbox filter-ajax" id="price-all" {{ !$currentPrice || $currentPrice == 'all' ? 'checked' : '' }}>
                            <label for="price-all">All Prices</label>
                        </div>

                        @foreach(['199', '399', '699', '999', '1099'] as $p)
                        <div class="filter-item">
                            <input type="radio" name="price_max" value="{{ $p }}" class="custom-checkbox filter-ajax" id="price-{{ $p }}" {{ $currentPrice == $p ? 'checked' : '' }}>
                            <label for="price-{{ $p }}">Under ₹{{ $p }}</label>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('search') }}" class="btn btn-sm btn-outline-danger w-100 py-2" style="border-radius: 8px;">Reset All Filters</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <span class="text-muted" id="results-count" style="font-size:14px;">
                        Showing {{ $products->count() }} results
                    </span>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-dark d-lg-none" style="border-radius: 20px; font-size:14px; border-color:#f5ebe9; color:#5E1929;" onclick="toggleFilterDrawer()">
                            ⚙️ Filters
                        </button>

                        <select name="sort" class="form-select filter-ajax" style="border-radius:20px; font-size:14px; min-width: 180px; border-color:#f5ebe9; color:#5E1929; cursor:pointer;">
                            <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Sort: Recommended</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="low-high" {{ request('sort') == 'low-high' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="high-low" {{ request('sort') == 'high-low' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <div id="product-list-container">
                    @include('partials.product-list')
                </div>
            </div>
        </div>
    </form>
</div>

<div id="overlay" onclick="toggleFilterDrawer()" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1500;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleFilterDrawer() {
        let drawer = document.getElementById("filterSidebar");
        let overlay = document.getElementById("overlay");
        if(!drawer || !overlay) return;
        
        drawer.classList.toggle('open');
        if (drawer.classList.contains('open')) {
            overlay.style.display = "block";
            document.body.style.overflow = "hidden";
        } else {
            overlay.style.display = "none";
            document.body.style.overflow = "";
        }
    }

    $(document).ready(function() {
        // AJAX Fetch Function
        function fetchFilteredProducts() {
            let form = $('#filterForm');
            let url = form.attr('action');
            let data = form.serialize();

            // Loading Effect
            $('#product-list-container').css('opacity', '0.5');

            $.ajax({
                url: url,
                data: data,
                type: 'GET',
                success: function(response) {
                    // Update Product List
                    $('#product-list-container').html(response);
                    $('#product-list-container').css('opacity', '1');
                    
                    // Update URL without page refresh
                    window.history.pushState({}, "", url + "?" + data);

                    // Update Result Count
                    let newCount = $('#product-list-container').find('.premium-card').length;
                    $('#results-count').text('Showing ' + newCount + ' results');
                },
                error: function() {
                    $('#product-list-container').css('opacity', '1');
                    console.error('Filtering failed.');
                }
            });
        }

        // Trigger on any filter change
        $(document).on('change', '.filter-ajax', function() {
            fetchFilteredProducts();
            
            // Close mobile drawer if a radio button (price) is selected
            if($(window).width() < 991 && $(this).attr('type') === 'radio') {
                toggleFilterDrawer();
            }
        });
    });
</script>

@endsection