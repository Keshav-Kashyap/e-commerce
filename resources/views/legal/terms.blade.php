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
        <h1 class="policy-title">Terms & Conditions</h1>
        
        <div class="policy-content">
            <p>Welcome to <strong>Shringar Jewellery</strong>. By accessing or using our website, you agree to be bound by these Terms and Conditions.</p>

            <h4>1. General Conditions</h4>
            <p>We reserve the right to refuse service to anyone for any reason at any time. You agree not to reproduce, duplicate, copy, sell, resell or exploit any portion of the Service without express written permission by us.</p>

            <h4>2. Products and Pricing</h4>
            <p>Prices for our products are subject to change without notice. We have made every effort to display as accurately as possible the colors and images of our products that appear at the store. We cannot guarantee that your computer monitor's display of any color will be accurate.</p>

            <h4>3. Accuracy of Billing and Account Information</h4>
            <p>We reserve the right to refuse any order you place with us. We may, in our sole discretion, limit or cancel quantities purchased per person, per household or per order. You agree to provide current, complete and accurate purchase and account information for all purchases made at our store.</p>

            <h4>4. Changes to Terms of Service</h4>
            <p>You can review the most current version of the Terms of Service at any time at this page. We reserve the right, at our sole discretion, to update, change or replace any part of these Terms of Service by posting updates and changes to our website.</p>

            <h4>5. Contact Information</h4>
            <p>Questions about the Terms of Service should be sent to us at: <br>
            <strong>Email:</strong> support@shringar.net.in</p>
        </div>
    </div>
</div>
@endsection