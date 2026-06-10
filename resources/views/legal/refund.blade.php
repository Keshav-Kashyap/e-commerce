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
    .policy-content ul { margin-bottom: 20px; }
</style>

<div class="container">
    <div class="policy-container">
        <h1 class="policy-title">Return & Exchange Policy</h1>
        
        <div class="policy-content">
            <p>At <strong>Shringar Jewellery</strong>, we take pride in the quality and craftsmanship of our products. If you are not entirely satisfied with your purchase, we are here to help.</p>

            <h4>1. Returns Eligibility</h4>
            <ul>
                <li>You have <strong>7 days</strong> to return an item from the date you received it.</li>
                <li>To be eligible for a return, your item must be unused, in the same condition that you received it, and in its original packaging.</li>
                <li><strong>Note:</strong> For hygiene reasons, we do not accept returns or exchanges on <strong>Earrings</strong> unless they arrive damaged or defective.</li>
            </ul>

            <h4>2. Exchanges</h4>
            <p>We only replace items if they are defective, damaged during transit, or if you received the wrong item. If you need to exchange it for the same item, send us an email at support@shringar.net.in with photos of the damaged product within 48 hours of delivery.</p>

            <h4>3. Refund Process</h4>
            <p>Once we receive your item, we will inspect it and notify you that we have received your returned item. If your return is approved, we will initiate a refund to your original method of payment. You will receive the credit within 5-7 working days, depending on your card issuer's policies.</p>

            <h4>4. Shipping Costs for Returns</h4>
            <p>You will be responsible for paying for your own shipping costs for returning your item. Original shipping costs are non-refundable. If you receive a refund, the cost of return shipping will be deducted from your refund.</p>

            <h4>5. Need Help?</h4>
            <p>Contact us at <strong>support@shringar.net.in</strong> for questions related to refunds and returns.</p>
        </div>
    </div>
</div>
@endsection