@extends('layouts.app') 

@section('content')
<style>
    body { background-color: #fdfaf5; }
    .checkout-container { margin-top: 50px; margin-bottom: 80px; }

    /* Form Styles */
    .checkout-card {
        background: #fff;
        padding: 30px;
        border-radius: 18px;
        box-shadow: 0 15px 40px rgba(94, 25, 41, 0.05);
        border: 1px solid #f5ebe9;
    }

    .checkout-title {
        color: #5E1929;
        font-weight: 700;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-control, .form-select { 
        border-radius: 10px; 
        height: 45px; 
        border: 1px solid #eee; 
        background: #fafafa; 
        font-size: 14px;
    }
    textarea.form-control { height: 90px; }
    .form-control:focus, .form-select:focus { 
        border-color: #c59d5f; 
        box-shadow: 0 0 5px rgba(197,157,95,0.2); 
        background: #fff; 
    }

    /* Summary Card */
    .summary-card {
        background: #fff;
        border-radius: 18px;
        padding: 25px;
        border: 1.5px solid #c59d5f;
        position: sticky;
        top: 100px;
    }

    .product-row {
        display: flex; gap: 15px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #eee;
    }

    .product-img {
        width: 65px; height: 65px; object-fit: cover; border-radius: 10px; border: 1px solid #f5ebe9;
    }

    .summary-table { width: 100%; margin-top: 20px; }
    .summary-table td { padding: 8px 0; font-size: 15px; color: #555; }
    .total-row { border-top: 2px solid #fdfaf5; font-weight: 700; color: #5E1929; font-size: 18px; }

    .payment-box {
        border: 1px solid #eee; border-radius: 10px; padding: 15px; margin-bottom: 12px; cursor: pointer; transition: 0.3s;
    }
    .payment-box:hover { border-color: #c59d5f; background: #faf7f0; }

    .btn-order {
        background: linear-gradient(135deg, #5E1929, #802336);
        border: none; border-radius: 10px; height: 52px; color: #fff; font-weight: 600; letter-spacing: 1px; transition: 0.3s;
    }
    .btn-order:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(94, 25, 41, 0.2); color: #fff; }
</style>

<div class="container checkout-container">
    <div class="row g-4">

        {{-- LEFT COLUMN: FORM --}}
        <div class="col-lg-7">
            <div class="checkout-card">
                <h3 class="checkout-title"><span>📍</span> Shipping & Payment</h3>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('place.order') }}" method="POST" id="orderForm">
                    @csrf

                    @if(isset($product))
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                    @endif

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="small fw-bold mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" placeholder="name@example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="10-digit mobile number" maxlength="10" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="small fw-bold mb-1">Flat / House No. / Building / Street</label>
                            <textarea name="address" class="form-control" placeholder="E.g. House No. , street" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="small fw-bold mb-1">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" class="form-control" placeholder="6-digit Pincode" maxlength="6" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="small fw-bold mb-1">State</label>
                            <select name="state" id="state" class="form-select" required>
                                <option value="" disabled selected>Select State</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small fw-bold mb-1">City / Town</label>
                            <select name="city" id="city" class="form-select" required>
                                <option value="" disabled selected>Select State First</option>
                            </select>
                        </div>
                        
                    </div>

                    <h6 class="mt-4 mb-3 fw-bold" style="color: #5E1929;">Choose Payment Method</h6>

                    <label class="payment-box d-block">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <span class="ms-2">Cash on Delivery (COD)</span>
                    </label>

                    <label class="payment-box d-block">
                        <input type="radio" name="payment_method" value="razorpay">
                        <span class="ms-2">Pay Online (Secure Razorpay)</span>
                    </label>

                    <button type="submit" id="orderBtn" class="btn btn-order w-100 mt-3">
                        🛒 Confirm Order (₹{{ number_format($grandTotal ?? $totalAmount, 2) }})
                    </button>
                </form>
            </div>
        </div>

        {{-- RIGHT COLUMN: SUMMARY --}}
        <div class="col-lg-5">
            <div class="summary-card">
                <h5 class="fw-bold mb-4" style="color: #5E1929;">Order Summary</h5>

                <div class="items-wrapper" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                    @if(isset($product))
                        <div class="product-row">
                            <img src="{{ asset($product->image) }}" class="product-img">
                            <div class="flex-grow-1">
                                <h6 class="m-0 text-dark small fw-bold">{{ $product->name }}</h6>
                                <small class="text-muted">Quantity: 1</small><br>
                                <span class="fw-bold">₹{{ number_format($product->price, 2) }}</span>
                            </div>
                        </div>
                    @else
                        @foreach($cartItems as $item)
                            <div class="product-row">
                                <img src="{{ asset($item->product->image) }}" class="product-img">
                                <div class="flex-grow-1">
                                    <h6 class="m-0 text-dark small fw-bold">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small><br>
                                    <span class="fw-bold">₹{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="mt-4 mb-3">
                    @if(session()->has('coupon'))
                        <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: #e6f4ea; border: 1px solid #c3e6cb;">
                            <span class="text-success small fw-bold">🎫 {{ session('coupon')['code'] }} ({{ session('coupon')['discount_percent'] }}% OFF)</span>
                            <form action="{{ route('checkout.remove.coupon') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0" style="font-size: 12px;">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('checkout.apply.coupon') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="coupon_code" class="form-control" placeholder="COUPON CODE" required style="text-transform: uppercase;">
                            <button type="submit" class="btn btn-dark fw-bold px-3">Apply</button>
                        </form>
                    @endif
                </div>

                <table class="summary-table">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-end fw-bold">₹{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                    @if(isset($discount) && $discount > 0)
                    <tr>
                        <td class="text-success fw-bold">Discount</td>
                        <td class="text-end text-success fw-bold">- ₹{{ number_format($discount, 2) }}</td>
                    </tr>
                    @endif
                    
                    {{-- 🔥 NAYA: GST ROW ADDED HERE --}}
                    <tr>
                        <td>GST (3%)</td>
                        <td class="text-end fw-bold">₹{{ number_format($gstAmount ?? 0, 2) }}</td>
                    </tr>

                    {{-- 🔥 UPDATED: SHIPPING ROW (Dynamic) --}}
                    <tr>
                        <td>Shipping</td>
                        <td class="text-end {{ ($shippingCharge ?? 0) == 0 ? 'text-success' : '' }} fw-bold">
                            {{ ($shippingCharge ?? 0) == 0 ? 'FREE' : '₹' . number_format($shippingCharge, 2) }}
                        </td>
                    </tr>

                    <tr class="total-row">
                        <td class="pt-3">Grand Total</td>
                        <td class="pt-3 text-end" style="font-size: 22px;">₹{{ number_format($grandTotal ?? $totalAmount, 2) }}</td>
                    </tr>
                </table>

                <div class="mt-3 p-2 rounded text-center" style="background: #fff9f0; border: 1px dashed #c59d5f;">
                    <p class="small mb-0" style="color: #5E1929;">
                        🚚 <strong>Estimated Delivery:</strong> 
                        {{ now()->addDays(5)->format('d M') }} - {{ now()->addDays(7)->format('d M, Y') }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Mega List
    const stateCityData = {
        "Andaman and Nicobar Islands": ["Port Blair", "Diglipur", "Mayabunder", "Rangat", "Havelock Island"],
        "Andhra Pradesh": ["Visakhapatnam", "Vijayawada", "Guntur", "Nellore", "Tirupati", "Kurnool", "Rajahmundry", "Kadapa", "Anantapur", "Kakinada", "Eluru", "Ongole", "Nandyal", "Machilipatnam", "Tenali", "Proddatur", "Chittoor", "Hindupur", "Bhimavaram", "Madanapalle"],
        "Arunachal Pradesh": ["Itanagar", "Naharlagun", "Pasighat", "Namsai", "Roing", "Tawang", "Ziro", "Tezu", "Bomdila"],
        "Assam": ["Guwahati", "Silchar", "Dibrugarh", "Jorhat", "Nagaon", "Tinsukia", "Tezpur", "Bongaigaon", "Karimganj", "Dhubri", "Diphu", "North Lakhimpur", "Lumbding", "Goalpara", "Sivasagar", "Barpeta"],
        "Bihar": ["Patna", "Gaya", "Bhagalpur", "Muzaffarpur", "Purnia", "Darbhanga", "Ara", "Begusarai", "Katihar", "Munger", "Chhapra", "Danapur", "Saharsa", "Hajipur", "Sasaram", "Dehri", "Siwan", "Motihari", "Nawada", "Bagaha", "Buxar", "Kishanganj", "Sitamarhi", "Jamui", "Jehanabad"],
        "Chandigarh": ["Chandigarh"],
        "Chhattisgarh": ["Raipur", "Bhilai", "Bilaspur", "Korba", "Rajnandgaon", "Raigarh", "Jagdalpur", "Ambikapur", "Dhamtari", "Mahasamund", "Bhatapara", "Chirmiri", "Dalli-Rajhara"],
        "Dadra and Nagar Haveli and Daman and Diu": ["Silvassa", "Daman", "Diu"],
        "Delhi": ["New Delhi", "North Delhi", "South Delhi", "East Delhi", "West Delhi", "Central Delhi", "Shahdara", "Rohini", "Dwarka", "Vasant Kunj", "Lajpat Nagar"],
        "Goa": ["Panaji", "Margao", "Vasco da Gama", "Mapusa", "Ponda", "Bicholim", "Curchorem", "Cuncolim", "Sanquelim"],
        "Gujarat": ["Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar", "Jamnagar", "Gandhinagar", "Junagadh", "Anand", "Navsari", "Morbi", "Nadiad", "Surendranagar", "Bharuch", "Vapi", "Bhuj", "Porbandar", "Palanpur", "Valsad", "Godhra", "Patan", "Kalol", "Botad", "Amreli"],
        "Haryana": ["Gurugram", "Faridabad", "Panipat", "Ambala", "Rohtak", "Hisar", "Karnal", "Sonipat", "Panchkula", "Yamunanagar", "Bhiwani", "Sirsa", "Bahadurgarh", "Jind", "Thanesar", "Kaithal", "Rewari", "Palwal", "Hansı", "Narnaul"],
        "Himachal Pradesh": ["Shimla", "Dharamshala", "Mandi", "Baddi", "Nahan", "Paonta Sahib", "Solan", "Kullu", "Chamba", "Palampur", "Sundarnagar"],
        "Jammu and Kashmir": ["Srinagar", "Jammu", "Anantnag", "Baramulla", "Kathua", "Sopore", "Pulwama", "Kupwara", "Udhampur", "Poonch", "Bandipora"],
        "Jharkhand": ["Ranchi", "Jamshedpur", "Dhanbad", "Bokaro", "Deoghar", "Phusro", "Hazaribagh", "Giridih", "Ramgarh", "Medininagar", "Chirkunda", "Jhumri Telaiya", "Sahibganj"],
        "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru", "Hubballi", "Belagavi", "Kalaburagi", "Davanagere", "Ballari", "Vijayapura", "Shivamogga", "Tumakuru", "Raichur", "Bidar", "Hosapete", "Gadag", "Robertson Pet", "Hassan", "Bhadravati", "Chitradurga", "Udupi", "Kolar", "Mandya", "Chikkamagaluru"],
        "Kerala": ["Thiruvananthapuram", "Kochi", "Kozhikode", "Kollam", "Thrissur", "Kannur", "Alappuzha", "Kottayam", "Palakkad", "Manjeri", "Thalassery", "Ponnani", "Vatakara", "Kanhangad", "Payyanur", "Koyilandy", "Parappanangadi"],
        "Ladakh": ["Leh", "Kargil"],
        "Lakshadweep": ["Kavaratti", "Agatti", "Amini", "Minicoy"],
        "Madhya Pradesh": ["Indore", "Bhopal", "Gwalior", "Jabalpur", "Ujjain", "Sagar", "Dewas", "Satna", "Ratlam", "Rewa", "Murwara", "Singrauli", "Burhanpur", "Khandwa", "Morena", "Bhind", "Chhindwara", "Guna", "Shivpuri", "Vidisha", "Damoh", "Chhatarpur", "Mandsaur", "Khargone", "Neemuch", "Pithampur", "Hoshangabad", "Itarsi"],
        "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Thane", "Nashik", "Kalyan-Dombivli", "Vasai-Virar", "Aurangabad", "Navi Mumbai", "Solapur", "Mira-Bhayandar", "Bhiwandi", "Amravati", "Nanded", "Kolhapur", "Akola", "Ulhasnagar", "Sangli", "Jalgaon", "Latur", "Dhule", "Ahmednagar", "Chandrapur", "Parbhani", "Jalna", "Beed", "Gondia", "Satara", "Barshi", "Yavatmal", "Wardha", "Osmanabad"],
        "Manipur": ["Imphal", "Churachandpur", "Thoubal", "Kakching", "Lilong", "Mayang Imphal"],
        "Meghalaya": ["Shillong", "Tura", "Nongstoin", "Jowai", "Baghmara"],
        "Mizoram": ["Aizawl", "Lunglei", "Saiha", "Champhai", "Kolasib", "Serchhip"],
        "Nagaland": ["Dimapur", "Kohima", "Mokokchung", "Tuensang", "Wokha", "Zunheboto"],
        "Odisha": ["Bhubaneswar", "Cuttack", "Rourkela", "Brahmapur", "Sambalpur", "Puri", "Balasore", "Bhadrak", "Baripada", "Jharsuguda", "Bargarh", "Rayagada", "Bhawanipatna", "Dhenkanal", "Balangir", "Kendujhar"],
        "Puducherry": ["Puducherry", "Ozhukarai", "Karaikal", "Yanam", "Mahe"],
        "Punjab": ["Ludhiana", "Amritsar", "Jalandhar", "Patiala", "Bathinda", "Hoshiarpur", "Mohali", "Batala", "Pathankot", "Moga", "Abohar", "Malerkotla", "Khanna", "Phagwara", "Muktsar", "Barnala", "Rajpura", "Firozpur", "Kapurthala", "Faridkot"],
        "Rajasthan": ["Jaipur", "Jodhpur", "Kota", "Bikaner", "Ajmer", "Udaipur", "Bhilwara", "Alwar", "Bharatpur", "Sri Ganganagar", "Sikar", "Pali", "Tonk", "Kishangarh", "Beawar", "Hanumangarh", "Dhaulpur", "Sawai Madhopur", "Churu", "Gangapur City", "Jhunjhunu", "Baran", "Chittaurgarh", "Bundi", "Nagaur", "Banswara", "Dungarpur"],
        "Sikkim": ["Gangtok", "Namchi", "Mangan", "Gyalshing", "Singtam", "Rangpo"],
        "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Tiruppur", "Salem", "Erode", "Tirunelveli", "Vellore", "Thoothukudi", "Dindigul", "Thanjavur", "Ranipet", "Sivakasi", "Karur", "Ooty", "Hosur", "Nagercoil", "Kanchipuram", "Kumarapalayam", "Karaikkudi", "Neyveli", "Cuddalore", "Kumbakonam", "Tiruvannamalai", "Pudukkottai"],
        "Telangana": ["Hyderabad", "Warangal", "Nizamabad", "Karimnagar", "Ramagundam", "Khammam", "Mahbubnagar", "Nalgonda", "Adilabad", "Suryapet", "Miryalaguda", "Jagtial", "Mancherial", "Kothagudem", "Bodhan", "Palwancha"],
        "Tripura": ["Agartala", "Udaipur", "Dharmanagar", "Kailashahar", "Belonia", "Khowai", "Ambassa"],
        "Uttar Pradesh": ["Agra", "Lucknow", "Kanpur", "Ghaziabad", "Prayagraj", "Varanasi", "Meerut", "Bareilly", "Aligarh", "Moradabad", "Saharanpur", "Gorakhpur", "Noida", "Firozabad", "Jhansi", "Muzaffarnagar", "Mathura", "Ayodhya", "Rampur", "Shahjahanpur", "Farrukhabad", "Maunath Bhanjan", "Hapur", "Orai", "Etawah", "Mirzapur", "Bulandshahr", "Sambhal", "Amroha", "Hardoi", "Fatehpur", "Raebareli", "Orai", "Sitapur", "Bahraich", "Modinagar", "Unnao", "Jaunpur", "Lakhimpur", "Hathras", "Banda", "Pilibhit", "Mughalsarai", "Barabanki", "Mainpuri", "Gonda", "Budaun", "Etah", "Kasganj", "Deoband", "Ghazipur"],
        "Uttarakhand": ["Dehradun", "Haridwar", "Roorkee", "Haldwani", "Rudrapur", "Kashipur", "Rishikesh", "Ramnagar", "Pithoragarh", "Manglaur", "Jaspur", "Tehri", "Almora", "Mussoorie"],
        "West Bengal": ["Kolkata", "Asansol", "Siliguri", "Durgapur", "Bardhaman", "English Bazar", "Baharampur", "Habra", "Kharagpur", "Shantipur", "Dankuni", "Dhulian", "Ranaghat", "Haldia", "Raiganj", "Krishnanagar", "Nabadwip", "Medinipur", "Jalpaiguri", "Balurghat", "Basirhat", "Bankura", "Chakdaha", "Darjeeling", "Alipurduar", "Purulia", "Jangipur", "Bangaon", "Cooch Behar"]
    };

    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');

    // PHP ke purane values ko JS mein lena (Agar validation fail hua tha)
    let oldState = "{{ old('state') }}";
    let oldCity = "{{ old('city') }}";

    // 1. Saare states load karna
    for (let state in stateCityData) {
        let option = new Option(state, state);
        if (state === oldState) option.selected = true; // Purana state select rakhna
        stateSelect.add(option);
    }

    // 2. City load karne ka function
    function loadCities(selectedState, selectedCity = null) {
        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
        if (selectedState && stateCityData[selectedState]) {
            let cities = stateCityData[selectedState].sort(); // Alphabetical order
            cities.forEach(function(city) {
                let option = new Option(city, city);
                if (city === selectedCity) option.selected = true; // Purani city select rakhna
                citySelect.add(option);
            });
            // Agar city list mein option "Other" add karna chahe toh:
            citySelect.add(new Option("Other (Mention in Address)", "Other"));
        } else {
            citySelect.innerHTML = '<option value="" disabled selected>Select State First</option>';
        }
    }

    // 3. Jab User dropdown se state change kare
    stateSelect.addEventListener('change', function() {
        loadCities(this.value);
    });

    // 4. Page load hone par purana data bharna (agar error aayi thi pichli baar)
    if (oldState) {
        loadCities(oldState, oldCity);
    }

    // Order Submit Button Loading Effect
    let form = document.getElementById('orderForm');
    let btn = document.getElementById('orderBtn');
    form.addEventListener('submit', function () {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
        btn.disabled = true;
    });
});
</script>

@endsection