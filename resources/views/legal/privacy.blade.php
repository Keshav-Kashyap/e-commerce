@extends('layouts.app')

@section('content')
<style>
    body { background-color: #fdfaf5; }
    .policy-container {
        max-width: 900px;
        margin: 60px auto;
        background: #fff;
        padding: 50px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(94, 25, 41, 0.05);
        border: 1px solid #f5ebe9;
    }
    .policy-title {
        color: #5E1929;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #c59d5f;
    }
    .policy-content h4 { color: #5E1929; margin-top: 25px; font-weight: 600; }
    .policy-content p { color: #555; line-height: 1.8; font-size: 15px; }
</style>

<div class="container">
    <div class="policy-container">
        <h1 class="policy-title">Privacy Policy</h1>
        
        <div class="policy-content">
            <p>Welcome to <strong>Shringar Jewellery</strong>. We value your privacy and are committed to protecting your personal data.</p>

            <h4>1. Information We Collect</h4>
            <p>We collect information you provide directly to us when you make a purchase, create an account, or contact us for support. This includes your name, email address, phone number, and shipping address.</p>

            <h4>2. How We Use Your Information</h4>
            <p>We use your data to process transactions, deliver your orders, send order confirmations, and respond to your customer service requests.</p>

            <h4>3. Payment Security</h4>
            <p>All payments are processed through secure payment gateways (like Razorpay). We do not store your credit/debit card information on our servers.</p>

            <h4>4. Contact Us</h4>
            <p>If you have any questions about this Privacy Policy, please contact us at: <br>
            <strong>Email:</strong> support@shringar.net.in <br>
            <strong>Phone:</strong> +91-XXXXXXXXXX</p>
        </div>
    </div>
</div>
@endsection