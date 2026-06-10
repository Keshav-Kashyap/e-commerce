@extends('layouts.app')

@section('content')

<style>
    body { background-color: #fdfaf5; } /* Pearl background */

    .auth-wrapper {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }

    .auth-card {
        background: #fff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(94, 25, 41, 0.07);
        width: 100%;
        max-width: 500px;
        border: 1px solid #f5ebe9;
    }

    .auth-title { 
        color: #5E1929; 
        font-weight: 700; 
        text-align: center; 
        margin-bottom: 30px; 
        letter-spacing: 1px; 
    }

    .form-label { 
        font-weight: 600; 
        color: #333; 
        font-size: 13px; 
        text-transform: uppercase; 
        margin-bottom: 8px; 
        display: block;
    }

    .custom-input { 
        height: 48px; 
        border-radius: 10px; 
        border: 1px solid #e8d5d1; 
        padding: 10px 15px; 
        transition: 0.3s; 
        background: #fafbfc;
        width: 100%;
    }

    .custom-input:focus { 
        border-color: #c59d5f; 
        box-shadow: 0 0 0 0.2rem rgba(197, 157, 95, 0.1); 
        background: #fff; 
        outline: none; 
    }
    
    .password-wrapper { position: relative; }
    
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 12px;
        cursor: pointer;
        color: #888;
        font-size: 18px;
    }

    .btn-auth {
        background: linear-gradient(135deg, #5E1929, #802336);
        color: #fff; 
        border: none; 
        height: 52px; 
        border-radius: 10px;
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 1px;
        width: 100%; 
        margin-top: 20px; 
        transition: 0.3s;
    }

    .btn-auth:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 8px 15px rgba(94, 25, 41, 0.2); 
        color: #fff; 
    }
    
    .login-link { text-align: center; margin-top: 25px; color: #666; font-size: 14px; }
    .login-link a { color: #c59d5f; font-weight: 700; text-decoration: none; }
    .login-link a:hover { text-decoration: underline; }

    .alert-success-custom {
        background: #e6f4ea;
        color: #2e7d32;
        border-radius: 10px;
        padding: 12px;
        font-size: 14px;
        margin-bottom: 20px;
    }

    @media(max-width: 576px) {
        .auth-card { padding: 30px 20px; border-radius: 15px; margin: 0 15px; }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">✨ Create Account</h2>
        
        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <div id="successBox" class="alert alert-success-custom text-center">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('successBox').style.opacity = "0";
                }, 1500);
                setTimeout(() => {
                    window.location.href = "{{ route('login') }}";
                }, 2000);
            </script>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="alert alert-danger text-center py-2 mb-3" style="font-size: 13px; border-radius: 10px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="custom-input" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="custom-input" value="{{ old('email') }}" placeholder="name@example.com" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="custom-input" value="{{ old('phone') }}" placeholder="10-digit number" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Create Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="custom-input" placeholder="Min. 8 characters" required>
                    <span class="toggle-password" onclick="togglePassword('password')">👁</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="confirmPassword" class="custom-input" placeholder="Repeat your password" required>
                    <span class="toggle-password" onclick="togglePassword('confirmPassword')">👁</span>
                </div>
            </div>

            <button type="submit" class="btn btn-auth">Register Now</button>

            <p class="login-link">
                Already have an account? <a href="{{ route('login') }}">Login Here</a>
            </p>
        </form>
    </div>
</div>

<script>
function togglePassword(id) {
    let input = document.getElementById(id);
    let icon = event.target;
    if (input.type === "password") {
        input.type = "text";
        icon.innerText = "🔒";
    } else {
        input.type = "password";
        icon.innerText = "👁";
    }
}
</script>

@endsection