<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #5E1929; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #5E1929; margin: 0; font-size: 28px; letter-spacing: 2px; }
        .details-container { width: 100%; margin-bottom: 20px; }
        .details-container td { vertical-align: top; width: 50%; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #5E1929; color: #fff; }
        .totals { width: 100%; text-align: right; }
        .totals td { padding: 5px 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SHRINGAR</h1>
        <p style="margin:5px 0;">  GANPATI SMART CITY<br>
        , Sikandra<br>
        Agra, Uttar Pradesh - 282007</p>
        <p style="margin:5px 0;"><strong>GSTIN:</strong> 09PQVPS8926K1Z4</p>
    </div>

    <table class="details-container">
        <tr>
            <td>
                <strong>Billed To:</strong><br>
                {{ $order->name }}<br>
                {{ $order->address }}<br>
                {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}<br>
                Phone: {{ $order->phone }}
            </td>
            <td style="text-align: right;">
                <strong>Invoice No:</strong> #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}<br>
                <strong>Date:</strong> {{ $order->created_at->format('d M, Y') }}<br>
                <strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Item Description</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Jewellery Item' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rs. {{ number_format($item->price, 2) }}</td>
                <td>Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal:</td><td>Rs. {{ number_format($order->subtotal, 2) }}</td></tr>
        @if($order->discount_amount > 0)
        <tr><td>Discount:</td><td>- Rs. {{ number_format($order->discount_amount, 2) }}</td></tr>
        @endif
        
        @php
            $isLocal = strtolower(trim($order->state)) == 'uttar pradesh' || strtolower(trim($order->state)) == 'up';
            $halfGst = $order->gst_amount / 2;
            
            // Dynamic Shipping Calculation: Grand Total - (Subtotal - Discount + GST)
            $calculated_shipping = $order->total_amount - ($order->subtotal - $order->discount_amount + $order->gst_amount);
            $calculated_shipping = $calculated_shipping > 0 ? $calculated_shipping : 0;
        @endphp
        
        @if($isLocal)
            <tr><td>CGST (1.5%):</td><td>Rs. {{ number_format($halfGst, 2) }}</td></tr>
            <tr><td>SGST (1.5%):</td><td>Rs. {{ number_format($halfGst, 2) }}</td></tr>
        @else
            <tr><td>IGST (3%):</td><td>Rs. {{ number_format($order->gst_amount, 2) }}</td></tr>
        @endif
        
        <tr><td>Shipping:</td><td>Rs. {{ number_format($calculated_shipping, 2) }}</td></tr>
        <tr><td><strong>Grand Total:</strong></td><td><strong>Rs. {{ number_format($order->total_amount, 2) }}</strong></td></tr>
    </table>
    <br><br>
    <div style="text-align: center; color: #777; font-size: 12px;">
        <p>This is a computer-generated invoice and does not require a physical signature.</p>
        <p>Thank you for choosing Shringar!</p>
    </div>
</body>
</html>