@extends('layouts.app')

@section('content')

<style>
.payment-wrapper {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.payment-card {
    background: #fff;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 450px;
    width: 100%;
}

.payment-title {
    font-weight: 600;
    color: #c59d5f;
}

.payment-amount {
    font-size: 28px;
    font-weight: bold;
    margin: 15px 0;
}

.btn-pay {
    background: black;
    border: none;
    border-radius: 12px;
    height: 50px;
    width: 100%;
    color: #fff;
    cursor: pointer;
    transition: 0.3s;
}
.btn-pay:hover {
    background: #333;
}
</style>

<div class="payment-wrapper">
    <div class="payment-card">
        <img src="{{ asset('images/icons/payment.png') }}" width="80" alt="Payment">
        <h3 class="payment-title">Secure Payment</h3>
        <div class="payment-amount">
            ₹{{ $total }}
        </div>

        <form action="{{ route('payment.success') }}" method="POST" id="razorpay-secure-form">
            @csrf
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
            
            <input type="hidden" name="name" value="{{ $name }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="phone" value="{{ $phone }}">
            <input type="hidden" name="address" value="{{ $address }}">
            <input type="hidden" name="pincode" value="{{ $pincode }}">
            <input type="hidden" name="city" value="{{ $city }}">
            <input type="hidden" name="state" value="{{ $state }}">
            @if(isset($product_id))
                <input type="hidden" name="product_id" value="{{ $product_id }}">
                <input type="hidden" name="quantity" value="{{ $quantity }}">
            @endif
        </form>

        <button id="rzp-button" class="btn-pay">Pay Now</button>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "{{ env('RAZORPAY_KEY') }}",
    "amount": "{{ $razorOrder['amount'] }}",
    "currency": "INR",
    "name": "Shringar Jewellery",
    "description": "Order Payment",
    "order_id": "{{ $razorOrder['id'] }}",

    "handler": function (response){
        // 🔥 IMPROVEMENT: URL redirect ki jagah form values set karke POST request karna
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_order_id').value = response.razorpay_order_id || "";
        document.getElementById('razorpay_signature').value = response.razorpay_signature || "";
        
        // Button ko disable karke text change karein taaki user do baar click na kare
        let btn = document.getElementById('rzp-button');
        btn.innerText = "Processing...";
        btn.disabled = true;

        document.getElementById('razorpay-secure-form').submit();
    },

    "theme": {
        "color": "#c59d5f"
    }
};

var rzp = new Razorpay(options);

document.getElementById('rzp-button').onclick = function(e){
    rzp.open();
    e.preventDefault();
}
</script>

@endsection