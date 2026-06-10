@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <h3 class="mb-4 text-center">Products Under ₹{{ $amount }}</h3>

    <div class="row">

        @forelse($products as $product)

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">

                <img src="{{ $product->image ? asset($product->image) : asset('images/default.png') }}"
                     class="card-img-top" style="height:200px;object-fit:cover;">

                <div class="card-body text-center">

                    <h6>{{ $product->name }}</h6>
                    <p class="text-success">₹{{ $product->price }}</p>

                    <a href="{{ route('product.show', $product->id) }}" class="btn btn-dark btn-sm">
                        View
                    </a>

                </div>

            </div>
        </div>

        @empty
            <h5 class="text-center">No products found 😢</h5>
        @endforelse

    </div>

</div>

@endsection