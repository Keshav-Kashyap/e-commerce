<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Order;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        $wishlistIds = auth()->check() ? Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray() : [];
        return view('home', compact('products', 'wishlistIds'));
    }

    public function create() 
    {
        $categories = Category::all(); 
        return view('admin.create-product', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'categories' => 'required|array', 
            'categories.*' => 'string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_main_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $imagePath = 'uploads/products/' . $filename;
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $key => $file) {
                if($key < 4) {
                    $galFilename = time() . '_gal_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/gallery'), $galFilename);
                    $galleryPaths[] = 'uploads/gallery/' . $galFilename;
                }
            }
        }

        Product::create([
            'name' => trim($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'category' => json_encode($request->categories), // Multiple categories saved as JSON
            'image' => $imagePath,
            'gallery' => !empty($galleryPaths) ? json_encode($galleryPaths) : null 
        ]);

        return redirect()->route('product.create')->with('success', 'Product added successfully!');
    }

    public function show($id)
    {
        $product = Product::with(['reviews.user'])->findOrFail($id);

        $wishlistIds = auth()->check() 
            ? \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray() 
            : [];

        // 🔥 FIX: Categories ko array mein badalna
        $categoriesArray = json_decode($product->category);
        
        // Agar JSON hai toh pehli category lo, warna wahi string use karo
        $searchCategory = is_array($categoriesArray) ? ($categoriesArray[0] ?? '') : $product->category;

        // 🔥 SUGGESTED PRODUCTS QUERY (LIKE use karna zaroori hai JSON ke liye)
        $suggestedProducts = Product::where('id', '!=', $id)
            ->where('category', 'LIKE', '%' . $searchCategory . '%') // JSON string ke andar search
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('product', compact('product', 'wishlistIds', 'suggestedProducts'));
    }

    public function edit($id) 
    { 
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.edit-product', compact('product', 'categories')); 
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        // Stock only update case
        if ($request->has('stock') && !$request->has('name')) {
            $product->stock = (int) $request->stock;
            $product->save();
            return back()->with('success', 'Stock updated!');
        }

        $request->validate([
            'name' => 'required', 
            'price' => 'required|numeric', 
            'categories' => 'required|array'
        ]);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = (int) $request->stock;
        $product->category = json_encode($request->categories); // Fix update logic for multiple cats
        $product->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $product->image = 'uploads/products/' . $imageName;
        }

        $product->save();
        return redirect()->route('admin.dashboard')->with('success', 'Product updated!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Product deleted!');
    }

    public function search(Request $request)
    {
        $query = $request->query('q') ?? $request->query('query');
        $filterCats = $request->query('categories', []);
        $priceMax = $request->query('price_max'); 
        $sort = $request->query('sort');

        $productQuery = Product::query();
        
        // 1. Keyword Search
        if (!empty($query)) {
            $productQuery->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('category', 'LIKE', "%{$query}%");
            });
        }
        
        // 2. 🔥 DYNAMIC CATEGORIES FILTER FIX:
        // Ab JSON string ke andar category dhund rahe hain
        if (!empty($filterCats)) { 
            $productQuery->where(function($q) use ($filterCats) {
                foreach($filterCats as $cat) {
                    $q->orWhere('category', 'LIKE', "%\"{$cat}\"%")
                      ->orWhere('category', 'LIKE', "%{$cat}%");
                }
            });
        }
        
        // 3. Budget Filter
        if (!empty($priceMax) && $priceMax !== 'all') {
            $productQuery->where('price', '<=', (int)$priceMax);
        }

        // 4. Sorting Logic
        if ($sort == 'low-high') { $productQuery->orderBy('price', 'asc'); }
        elseif ($sort == 'high-low') { $productQuery->orderBy('price', 'desc'); }
        else { $productQuery->latest(); }

        $products = $productQuery->get();

        if ($request->ajax()) {
            return view('partials.product-list', compact('products'))->render();
        }

        return view('search', compact('products', 'query'));
    }

    public function liveSearch(Request $request)
    {
        $query = $request->get('q');
        if (strlen($query) >= 2) {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('category', 'LIKE', "%{$query}%")
                ->take(6)->get(['id', 'name', 'price', 'image']);
            return response()->json($products);
        }
        return response()->json([]);
    }

    public function adminDashboard() 
    {
        $products = Product::latest()->get();
        $newOrdersCount = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');

        return view('admin.dashboard', compact('products', 'newOrdersCount', 'totalRevenue'));
    }
}