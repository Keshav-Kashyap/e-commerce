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
        max-width: 450px;
        border: 1px solid #f5ebe9;
    }

    .auth-title { 
        color: #5E1929; 
        font-weight: 700; 
        text-align: center; 
        margin-bottom: 10px; 
        letter-spacing: 1px; 
    }

    .auth-subtitle {
        text-align: center;
        color: #c59d5f;
        font-size: 14px;
        margin-bottom: 30px;
        font-style: italic;
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

    .forgot-link {
        display: block;
        text-align: right;
        font-size: 12px;
        color: #c59d5f;
        text-decoration: none;
        margin-top: 5px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #666;
        margin-top: 15px;
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
        margin-top: 25px; 
        transition: 0.3s;
    }

    .btn-auth:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 8px 15px rgba(94, 25, 41, 0.2); 
        color: #fff; 
    }
    
    .register-link { text-align: center; margin-top: 25px; color: #666; font-size: 14px; }
    .register-link a { color: #c59d5f; font-weight: 700; text-decoration: none; }

    @media(max-width: 576px) {
        .auth-card { padding: 30px 20px; margin: 0 15px; }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Login to your Shringar account</p>
        
        {{-- ALERTS --}}
        @if(session('error'))
            <div class="alert alert-danger text-center py-2 mb-3" style="font-size: 13px; border-radius: 10px;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="custom-input" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
            </div>

            <div class="mb-2">
                <label class="form-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="custom-input" placeholder="••••••••" required>
                    <span class="toggle-password" onclick="togglePassword('password')">👁</span>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
            </div>

            <label class="remember-me">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} style="accent-color: #5E1929;">
                Remember me on this device
            </label>

            <button type="submit" class="btn btn-auth">Secure Login</button>

            <p class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Register Now</a>
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