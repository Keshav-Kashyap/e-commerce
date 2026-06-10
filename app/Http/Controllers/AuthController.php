<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ================= REGISTER PAGE =================
    public function showRegister()
    {
        return view('auth.register');
    }

    // ================= REGISTER USER =================
    public function register(Request $request)
    {
        // ✅ Validation Updated 
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|digits:10', // 🔥 Phone validation added
            'password' => [
                'required',
                'string',
                'min:8', // standard for security
                'confirmed'
            ]
        ]);

        // ✅ Create user with phone
        $user = User::create([
            'name' => trim($request->name),
            'email' => strtolower($request->email),
            'phone' => $request->phone, // 🔥 Save phone to DB
            'password' => Hash::make($request->password)
        ]);

        // Note: Aapne Blade mein setTimeout lagaya hai login pe bhejne ke liye, 
        // isliye hum yahan auto-login nahi kar rahe taaki user success message dekh sake.
        
        return back()->with('success', 'Account created successfully! Redirecting to login...');
    }

    // ================= LOGIN PAGE =================
    public function showLogin()
    {
        return view('auth.login');
    }

    // ================= LOGIN USER =================
    public function login(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // ✅ Attempt login
        if (Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            // 🔐 Session regenerate (security)
            $request->session()->regenerate();

            return redirect()->intended('/')
                ->with('success', 'Welcome back! Login successful.');
        }

        // ❌ Failed login
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();

        // 🔐 Secure logout
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Logged out successfully!');
    }

    // ================= PROFILE UPDATE =================
    public function update(Request $request)
    {
        $user = auth()->user();

        // ✅ Validation updated to include phone number
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|numeric|digits:10', // 10 digit validation
        ]);

        // ✅ Updating database with new values
        $user->update([
            'name' => trim($request->name),
            'email' => strtolower($request->email),
            'phone' => $request->phone, 
        ]);

        return back()->with('success', 'Profile updated successfully! ✅');
    }

    public function profile()
    {
        // ✅ Auth user ke orders fetch karenge relationships ke saath
        // latest() se naya order upar dikhega
        $orders = \App\Models\Order::where('user_id', auth()->id())
                    ->with(['items.product']) 
                    ->latest()
                    ->get();

        return view('auth.profile', compact('orders'));
    }
}