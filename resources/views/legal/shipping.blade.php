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
    .policy-content p, .policy-content li { color: #555; line-height: 1.8; font-size: 15px; }
</style>

<div class="container">
    <div class="policy-container">
        <h1 class="policy-title">Shipping Policy</h1>
        
        <div class="policy-content">
            <p>Thank you for shopping with <strong>Shringar Jewellery</strong>. Following are the terms and conditions that constitute our Shipping Policy.</p>

            <h4>1. Domestic Shipping Processing Time</h4>
            <p>All orders are processed within <strong>1-2 business days</strong>. Orders are not shipped or delivered on Sundays or public holidays. If we are experiencing a high volume of orders, shipments may be delayed by a few days. Please allow additional days in transit for delivery.</p>

            <h4>2. Shipping Rates & Delivery Estimates</h4>
            <p>Shipping charges for your order will be calculated and displayed at checkout.</p>
            <ul>
                <li><strong>Standard Shipping:</strong> 3-5 business days.</li>
                <li><strong>Express Shipping:</strong> 1-2 business days (Available in select metro cities).</li>
                <li><strong>Free Shipping:</strong> Available on all orders above ₹999.</li>
            </ul>

            <h4>3. Shipment Confirmation & Order Tracking</h4>
            <p>You will receive a Shipment Confirmation email once your order has shipped containing your tracking number(s). The tracking number will be active within 24 hours.</p>

            <h4>4. Damages</h4>
            <p>Shringar is not liable for any products damaged or lost during shipping. However, customer satisfaction is our priority. If you received your order damaged, please contact us immediately with unboxing videos and photos so we can assist you.</p>

            <h4>5. International Shipping</h4>
            <p>Currently, we only ship within India. We do not offer international shipping at this time.</p>
        </div>
    </div>
</div>
@endsection