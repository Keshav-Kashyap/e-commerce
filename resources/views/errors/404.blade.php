@extends('layouts.app')

@section('content')
<style>
    body { background-color: #fdfaf5; }
    .error-page {
        height: 60vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .error-code {
        font-size: 100px;
        font-weight: 800;
        color: #c59d5f;
        line-height: 1;
        margin-bottom: 10px;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.05);
    }
    .error-text {
        font-size: 24px;
        color: #5E1929;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .btn-premium {
        background: #5E1929;
        color: #fff;
        padding: 12px 30px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-premium:hover {
        background: #c59d5f;
        color: #fff;
        transform: translateY(-3px);
    }
</style>

<div class="container error-page">
    <div class="error-code">404</div>
    <div class="error-text">Oops! The jewellery you are looking for is missing.</div>
    <p class="text-muted mb-4">The page you are trying to reach does not exist or has been moved.</p>
    <a href="{{ route('home') }}" class="btn-premium">Return to Shop</a>
</div>
@endsection