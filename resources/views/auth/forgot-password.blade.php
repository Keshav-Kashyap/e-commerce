@extends('layouts.app')

@section('content')

<div class="container mt-5 d-flex justify-content-center">

    <div class="auth-card" style="max-width:400px; width:100%;">

        <h4 class="text-center mb-3">Forgot Password</h4>

        <form method="POST" action="{{ route('password.send') }}">
            @csrf

            <input type="email" name="email" class="form-control mb-3" placeholder="Enter your email" required>

            <button class="btn btn-theme w-100">Continue</button>

        </form>

    </div>

</div>

@endsection