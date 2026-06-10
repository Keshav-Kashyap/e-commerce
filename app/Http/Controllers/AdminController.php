<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\Review;
use App\Models\Order;

class AdminController extends Controller
{
    // ================= PHASE 1 =================

    // 1. Categories Management
    public function categories()
    {
        $categories = Category::latest()->get();
        return view('admin.categories', compact('categories'));
    }

        public function storeCategory(Request $request) 
        {
            // Validation ko thoda easy kar dete hain check karne ke liye
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required' // Sirf check ki file aayi hai ya nahi
            ]);

            $imagePath = null;
            
            // Yahan hum manually check kar rahe hain
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // Check karein file valid hai ya nahi
                if($file->isValid()) {
                    $imageName = time().'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('images/categories'), $imageName);
                    $imagePath = 'images/categories/' . $imageName;
                } else {
                    return back()->withErrors(['image' => 'File upload mein koi dikkat hai. Dobara koshish karein.']);
                }
            }

            \App\Models\Category::create([
                'name' => $request->name,
                'image' => $imagePath
            ]);

            return back()->with('success', 'Category added successfully!');
        }

    public function destroyCategory($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Category deleted!');
    }

    // 2. Inventory (Complete Inventory - Shifted from Dashboard)
    public function inventory()
    {
        $products = Product::orderBy('stock', 'asc')->get();
        return view('admin.inventory', compact('products'));
    }

    // 3. Registered Customers
    public function customers()
    {
        // Admin account ko chhod kar baaki sab users
        $customers = User::where('role', '!=', 'admin')->latest()->get();
        return view('admin.customers', compact('customers'));
    }


    // ================= PHASE 2 =================

    // 1. Manage Reviews
    public function reviews()
    {
        $reviews = Review::with(['product', 'user'])->latest()->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function destroyReview($id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Review deleted successfully!');
    }

    // 2. Coupons & Offers 
    public function coupons()
    {
        
        $coupons = Coupon::latest()->get();
        return view('admin.coupons', compact('coupons'));
    }

    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'discount_percent' => 'required|integer|min:1|max:99',
            'max_discount' => 'nullable|numeric|min:1', // Upto limit ke liye naya validation
            'usage_limit' => 'nullable|integer|min:1' 
        ]);
        
        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'discount_percent' => $request->discount_percent,
            'max_discount' => $request->max_discount, // Naya limit data save ho raha hai
            'usage_limit' => $request->usage_limit, 
            'is_active' => true
        ]);
        
        return back()->with('success', 'Coupon created successfully!');
    }


    public function updateCoupon(Request $request, $id)
    {
        $request->validate([
            'discount_percent' => 'required|integer|min:1|max:99',
            'max_discount' => 'nullable|numeric|min:1', // Upto limit ke liye naya validation
            'usage_limit' => 'nullable|integer|min:1' 
        ]);

        $coupon = Coupon::findOrFail($id);
        
        $coupon->update([
            'discount_percent' => $request->discount_percent,
            'max_discount' => $request->max_discount, // Naya limit data update ho raha hai
            'usage_limit' => $request->usage_limit
        ]);

        return back()->with('success', 'Coupon limit updated successfully!');
    }
    
    public function toggleCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();
        return back()->with('success', 'Coupon status updated!');
    }

    public function destroyCoupon($id)
    {
        Coupon::findOrFail($id)->delete();
        return back()->with('success', 'Coupon deleted permanently!');
    }

    // 3. Store Settings (Fully Functional)
    public function settings()
    {
        // Database se saari settings fetch karke array mein badalna
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {

        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return back()->with('success', 'Store settings updated successfully!');
    }

    public function adminDashboard() 
    {
        $products = Product::latest()->get();

        // 2. New Orders count 
        $newOrdersCount = Order::where('status', 'pending')->count();

        // 3. Total Revenue 
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');


        return view('admin.dashboard', compact('products', 'newOrdersCount', 'totalRevenue'));
    }



    public function salesInvoices(Request $request) {
        $query = Order::with('user')->whereIn('status', ['confirmed', 'shipped', 'delivered']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $invoices = $query->latest()->get();
        return view('admin.invoices', compact('invoices'));
    }
}