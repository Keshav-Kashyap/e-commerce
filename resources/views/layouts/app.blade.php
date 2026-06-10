<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Shringar - Premium Artificial Jewellery')</title>
        <meta name="description" content="@yield('meta_description', 'Discover elegance with Shringar. Shop premium and timeless artificial jewellery for every occasion.')">
        
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', 'Shringar - Premium Artificial Jewellery')">
        <meta property="og:description" content="@yield('meta_description', 'Discover elegance with Shringar.')">
        <meta property="og:image" content="@yield('meta_image', asset('images/logo.png'))">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            /* STICKY HEADER FIX */
            body {
                overflow-x: hidden;
                padding-top: 125px; 
                margin: 0;
            }
            html {
                overflow-x: hidden;
            }

            body.cart-open {
                overflow: hidden; 
            }

            /* Card ka pura container transparent karein */
            .premium-card {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                transition: transform 0.3s ease;
            }

            /* Image wrapper ko clean aur circular/soft rakhein */
            .product-img-wrapper {
                position: relative;
                padding-top: 100%;
                background-color: #fff; /* Pure white background */
                border-radius: 20px;
                overflow: hidden;
                border: 1px solid #f5ebe9; /* Bahut halki border */
                transition: 0.4s all ease;
            }

            /* 🔥 MAGIC PROPERTY: Image background ko page ke sath merge karne ke liye */
            .product-img-wrapper img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: contain; /* Image katni nahi chahiye */
                padding: 15px;
                
                /* Yeh property image ke white/grey background ko transparent jaisa treat karti hai */
                mix-blend-mode: multiply; 
                filter: contrast(105%) brightness(1.02);
            }

            /* Hover effect ko premium banayein */
            .premium-card:hover .product-img-wrapper {
                transform: translateY(-8px);
                box-shadow: 0 15px 35px rgba(94, 25, 41, 0.1);
                border-color: #c59d5f;
            }

            /* Text aur details ka alignment */
            .card-body {
                padding: 15px 5px !important;
                background: transparent !important;
            }

            .product-title {
                font-size: 14px;
                color: #333;
                font-weight: 500;
                margin-bottom: 5px;
            }

            .product-price {
                color: #5E1929;
                font-weight: 700;
                font-size: 16px;
            }
                /* ================= PREMIUM HEADER WRAPPER & THEME ================= */
                .header-wrapper {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 1040;
                    background: #fffcfb;
                    box-shadow: 0 4px 20px rgba(94, 25, 41, 0.08);
                }

                /* 1. Top Bar */
                .top-bar {
                    background-color: #5E1929;
                    color: #fdfaf5;
                    font-size: 12px;
                    padding: 8px 20px; 
                    font-weight: 500;
                    letter-spacing: 0.8px;
                }
                .top-bar a { color: #fdfaf5; text-decoration: none; transition: color 0.3s; }
                .top-bar a:hover { color: #c59d5f; }
                .top-bar-center a { color: #c59d5f; text-decoration: underline; font-weight: 600; }
                
                /* 2. Main Header */
                .main-header {
                    padding: 10px 40px; 
                    border-bottom: 1px solid #f5ebe9;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    position: relative;
                    z-index: 1050; 
                    background: #fffcfb;
                    flex-wrap: wrap; 
                }

                /* Left Group */
                .header-left { 
                    flex: 1; 
                    display: flex; 
                    align-items: center; 
                    justify-content: flex-start;
                    gap: 20px; 
                }

                /* ================= 🔥 SEAMLESS LOGO INTEGRATION 🔥 ================= */
                .header-logo-left {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: transparent;
                    padding: 0;
                    transition: all 0.3s ease;
                    text-decoration: none; 
                }
                
                .header-logo-left img {
                    height: 40px; 
                    width: auto;
                    object-fit: contain;
                    transition: all 0.3s ease;
                    mix-blend-mode: multiply; 
                }

                .header-logo-left:hover img {
                    transform: scale(1.05); 
                }
                
                /* Absolute Center for Company Name */
                .header-center {
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    text-align: center;
                    width: max-content;
                    z-index: 1055;
                }
                
                /* Right Side (Search + Icons only) */
                .header-right { 
                    flex: 1; 
                    display: flex; 
                    justify-content: flex-end; 
                    align-items: center; 
                    gap: 20px; 
                }

                /* Desktop Search Box */
                .search-wrapper { position: relative; }
                .search-box-sm {
                    display: flex;
                    align-items: center;
                    border: 1px solid #e8d5d1;
                    border-radius: 20px;
                    overflow: hidden;
                    width: 100%;
                    max-width: 180px;
                    background: #ffffff;
                    transition: 0.3s;
                    height: 36px; 
                    margin-bottom: 0;
                }
                .search-box-sm:focus-within { border-color: #c59d5f; box-shadow: 0 0 8px rgba(197, 157, 95, 0.2); }
                .search-box-input-sm { border: none; padding: 5px 15px; outline: none; width: 100%; font-size: 13px; background: transparent; color: #5E1929; }
                .search-box-button-sm { background: transparent; border: none; padding: 0 12px; cursor: pointer; color: #5E1929; }

                /* Live Search Dropdown UI */
                .search-dropdown {
                    position: absolute;
                    top: 110%;
                    right: 0;
                    width: 320px; 
                    background: #fff;
                    border-radius: 12px;
                    box-shadow: 0 15px 35px rgba(94, 25, 41, 0.15);
                    border: 1px solid #f5ebe9;
                    overflow: hidden;
                    display: none;
                    z-index: 1060;
                }

                .live-search-item {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 10px 15px;
                    text-decoration: none !important;
                    border-bottom: 1px solid #fdfaf5;
                    transition: 0.3s;
                }

                .live-search-item:hover { background: #fdfaf5; }

                .live-search-img {
                    width: 45px; height: 45px; object-fit: cover;
                    border-radius: 6px; border: 1px solid #eee;
                }

                .live-search-info { display: flex; flex-direction: column; }
                .live-search-name { font-size: 13px; font-weight: 600; color: #5E1929; margin-bottom: 2px; line-height: 1.2; }
                .live-search-price { font-size: 12px; font-weight: 700; color: #c59d5f; }

                .view-all-results {
                    display: block; text-align: center; padding: 10px; background: #5E1929;
                    color: #fff !important; font-size: 12px; font-weight: 600; text-transform: uppercase;
                }

                /* Premium SVG Icons CSS */
                .header-icons { display: flex; align-items: center; gap: 20px; }
                .icon-link { position: relative; text-decoration: none; color: #5E1929; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; }
                .icon-link svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; transition: 0.3s ease; }
                .icon-link:hover { color: #c59d5f; transform: translateY(-3px); }
                .icon-link:hover svg { filter: drop-shadow(0 4px 6px rgba(197,157,95,0.4)); }

                /* Premium Dropdown Style */
                .premium-dropdown-menu {
                    border: 1px solid #f5ebe9; border-radius: 12px; box-shadow: 0 10px 30px rgba(94, 25, 41, 0.15);
                    padding: 10px 0; min-width: 180px; z-index: 1060 !important;
                }
                .premium-dropdown-menu .dropdown-item { padding: 10px 20px; font-size: 14px; font-weight: 500; color: #5E1929; transition: 0.3s; }
                .premium-dropdown-menu .dropdown-item:hover { background: #fdfaf5; color: #c59d5f; transform: translateX(5px); }
                .icon-link.dropdown-toggle::after { display: none; }

                /* Premium Gold Badge */
                .badge {
                    position: absolute; top: -8px; right: -10px; background: linear-gradient(135deg, #c59d5f, #e5c07b) !important;
                    color: #fff; font-size: 10px; font-weight: bold; width: 18px; height: 18px; display: flex;
                    align-items: center; justify-content: center; border-radius: 50%; border: 2px solid #fffcfb;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
                }

                /* 3. Category Navigation */
                .category-nav {
                    background-color: #5E1929; padding: 12px 0; text-align: center;
                    box-shadow: inset 0 2px 5px rgba(0,0,0,0.1); position: relative; z-index: 1030;
                }
                .nav-links {
                    list-style: none; margin: 0; padding: 0; display: flex;
                    justify-content: center; gap: 30px; flex-wrap: wrap;
                }
                .nav-links li a {
                    color: #fdfaf5; text-decoration: none; font-size: 13px; font-weight: 500;
                    letter-spacing: 1px; text-transform: uppercase; transition: color 0.3s, transform 0.3s;
                    display: inline-block;
                }
                .nav-links li a:hover { color: #c59d5f; transform: translateY(-2px); }

                .mobile-menu-btn { display: none; font-size: 24px; cursor: pointer; color: #5E1929; }
                .mobile-search-wrapper { display: none; } 

                /* ================= 🔥 MOBILE RESPONSIVE FIXES 🔥 ================= */
                @media(max-width: 991px) {
                    body { padding-top: 110px; } 
                    .top-bar { display: none; }
                    .category-nav { display: none; }
                    
                    .main-header { padding: 10px 15px; }

                    .header-left { flex: none; gap: 10px; order: 1; }
                    .mobile-menu-btn { display: block; line-height: 1; }

                    .header-logo-left { display: none; } 
                    
                    .header-center { 
                        position: static; transform: none; flex: 1; text-align: center; order: 2; 
                    }
                    .header-center span { font-size: 22px !important; letter-spacing: 2px !important; }
                    
                    .header-right { flex: none; gap: 15px; order: 3; justify-content: flex-end; }
                    
                    .header-right .search-wrapper { display: none; }

                    .header-icons { gap: 18px; }
                    .icon-link svg { width: 22px; height: 22px; }
                    .badge { width: 16px; height: 16px; font-size: 9px; top: -5px; right: -8px; }

                    .mobile-search-wrapper {
                        display: block; width: 100%; margin-top: 12px; order: 4; position: relative;
                    }
                    .search-box-mobile {
                        display: flex; align-items: center; border: 1px solid #e8d5d1;
                        border-radius: 20px; background: #fdfaf5; height: 40px;
                    }
                    .search-box-mobile input { border: none; padding: 5px 15px; outline: none; width: 100%; font-size: 14px; background: transparent; color: #5E1929; }
                    .search-box-mobile button { background: transparent; border: none; padding: 0 15px; cursor: pointer; color: #5E1929; }

                    .search-dropdown-mobile {
                        position: absolute; top: 100%; left: 0; width: 100%; background: #fff;
                        border-radius: 0 0 12px 12px; box-shadow: 0 10px 30px rgba(94, 25, 41, 0.15);
                        border: 1px solid #f5ebe9; overflow: hidden; display: none; z-index: 1060;
                    }
                }

                /* ================= CART & SIDEBAR ================= */
                #cartDrawer {
                    position: fixed; top: 0; right: -400px; width: 360px; height: 100%;
                    background: rgba(255,255,255,0.95); backdrop-filter: blur(15px);
                    box-shadow: -5px 0 25px rgba(0,0,0,0.3); transition: right 0.4s ease-in-out;
                    z-index: 2000; padding: 20px; overflow-y: auto;
                }

                .sidebar {
                    position: fixed !important; top: 0 !important; left: -350px !important;
                    width: 320px !important; height: 100vh !important; background: #ffffff !important;
                    transition: left 0.4s ease-in-out !important; z-index: 1050 !important;
                    box-shadow: 5px 0 30px rgba(0,0,0,0.08); display: flex; flex-direction: column;
                }

                .sidebar.open { left: 0 !important; }

                .sidebar-header { padding: 25px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; }
                .sidebar-header h2 { margin: 0; color: #111; font-size: 24px; font-weight: 700; letter-spacing: 1px; }
                .sidebar-header .close-btn { color: #999; font-size: 24px; cursor: pointer; transition: 0.3s; line-height: 1; }
                .sidebar-header .close-btn:hover { color: #c59d5f; transform: rotate(90deg); }
                .sidebar-content { padding: 20px; overflow-y: auto; flex-grow: 1; }
                .sidebar-title { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #a0a0a0; margin: 15px 0 10px 10px; font-weight: 600; }
                
                .sidebar-link { display: flex; align-items: center; padding: 12px 15px; color: #333; text-decoration: none; border-radius: 8px; margin-bottom: 4px; font-size: 15px; font-weight: 500; transition: all 0.3s ease; }
                .sidebar-link .icon { width: 24px; margin-right: 12px; font-size: 18px; display: inline-block; text-align: center; }
                .sidebar-link:hover { background: #fdfaf5; color: #c59d5f; transform: translateX(6px); }
                
                /* Sidebar Submenu for Categories */
                .sidebar-submenu {
                    display: none;
                    flex-direction: column;
                    background: #fdfaf5;
                    border-radius: 8px;
                    margin: 0 10px 10px 10px;
                    padding: 5px 0;
                    overflow: hidden;
                    border: 1px solid #f5ebe9;
                }
                .sidebar-submenu.show { display: flex; }
                .sidebar-sublink {
                    padding: 10px 20px 10px 50px;
                    color: #5E1929;
                    text-decoration: none;
                    font-size: 14px;
                    font-weight: 500;
                    transition: 0.3s;
                    position: relative;
                }
                .sidebar-sublink::before {
                    content: "•";
                    position: absolute;
                    left: 35px;
                    color: #c59d5f;
                }
                .sidebar-sublink:hover { color: #c59d5f; background: #fff; }
                .cat-arrow { margin-left: auto; font-size: 10px; transition: transform 0.3s ease; color: #999; }
                .cat-arrow.rotate { transform: rotate(180deg); color: #c59d5f; }

                .sidebar-footer { padding: 20px; border-top: 1px solid #f0f0f0; background: #fafbfc; }
                
                .btn-premium { width: 100%; padding: 12px; border: 1px solid #c59d5f; background: transparent; color: #c59d5f; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; font-size: 13px; text-align: center; text-decoration: none; display: block; }
                .btn-premium:hover { background: #c59d5f; color: #fff; }

                /* ================= PREMIUM FOOTER ================= */
                .footer { background: #2c0b14; color: #fdfaf5; padding: 60px 0 20px 0; margin-top: 80px; border-top: 4px solid #c59d5f; }
                .footer-container { display: flex; flex-wrap: wrap; justify-content: space-between; max-width: 1200px; margin: auto; padding: 0 20px; }
                .footer-col { width: 22%; margin-bottom: 30px; }
                .footer-logo { color: #c59d5f; font-weight: 700; font-size: 28px; margin-bottom: 15px; letter-spacing: 1px; }
                .footer-col p { color: #d8c3c8; font-size: 14px; line-height: 1.6; }
                .footer-col h3 { color: #fff; font-size: 16px; margin-bottom: 20px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
                .footer-col ul { list-style: none; padding: 0; margin: 0; }
                .footer-col ul li { margin-bottom: 12px; }
                .footer-col a { color: #d8c3c8; text-decoration: none; font-size: 14px; transition: 0.3s; display: inline-block; }
                .footer-col a:hover { color: #c59d5f; transform: translateX(5px); }
                
                .social-icons { display: flex; gap: 15px; }
                .social-icons a { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 50%; transition: 0.3s; border: 1px solid rgba(197, 157, 95, 0.3); }
                .social-icons a:hover { background: #c59d5f; transform: translateY(-3px); }
                .social-icons img { width: 20px; height: 20px; filter: brightness(0) invert(1); }
                .footer-bottom { text-align: center; padding-top: 20px; margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); color: #d8c3c8; font-size: 13px; }

                @media(max-width: 768px) {
                    .footer-col { width: 100%; text-align: center; }
                    .social-icons { justify-content: center; }
                    .footer-col a:hover { transform: translateX(0); color: #c59d5f; }
                }

                #overlay {
                    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                    background: rgba(0,0,0,0.4); backdrop-filter: blur(3px); display: none;
                    z-index: 1000; transition: opacity 0.3s; opacity: 0;
                }
                #overlay.show { display: block; opacity: 1; }


                .admin-sidebar {
                    background-color: #fff;
                    border-right: 1px solid #f5ebe9;
                    min-height: 100vh;
                    padding-top: 20px;
                }
                .sidebar-heading {
                    color: #5E1929;
                    font-weight: 700;
                    text-align: center;
                    margin-bottom: 30px;
                    font-size: 22px;
                    letter-spacing: 1px;
                }
                .nav-link-custom {
                    color: #555;
                    font-size: 15px;
                    padding: 12px 20px;
                    margin: 5px 15px;
                    border-radius: 8px;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                }
                .nav-link-custom span {
                    margin-right: 12px;
                    font-size: 18px;
                }
                .nav-link-custom:hover {
                    background-color: #fdfaf5;
                    color: #c59d5f;
                }
                .nav-link-custom.active {
                    background-color: #5E1929;
                    color: #fff;
                }
                .sidebar-divider {
                    border-top: 1px solid #f5ebe9;
                    margin: 15px;
                }
                
                /* ================== PREMIUM HORIZONTAL NAVBAR CSS ================== */
            .premium-navbar {
                list-style: none;
                background: transparent;
            }

            .premium-nav-link {
                display: inline-block;
                color: #fdfaf5; /* Cream/Off-White color for text */
                text-decoration: none;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: 1.5px;
                padding: 15px 0;
                transition: 0.3s;
                position: relative;
                white-space: nowrap; /* Text ko ek hi line mein rakhne ke liye */
            }

            /* Hover par text color change aur niche premium line */
            .premium-nav-link:hover {
                color: #c59d5f; /* Gold/Beige color on hover */
            }

            .premium-nav-link::after {
                content: '';
                position: absolute;
                bottom: 8px; /* Text se thoda upar lane ke liye */
                left: 0;
                width: 0%;
                height: 1.5px;
                background: #c59d5f; /* Gold line on hover */
                transition: 0.3s;
            }

            .premium-nav-link:hover::after {
                width: 100%;
            }

            /* Responsive: Chhoti screens par gap kam ho */
            @media (max-width: 768px) {
                .premium-navbar {
                    gap: 20px !important;
                    overflow-x: auto; /* Agar jyada hon categories toh scroll karein */
                }
            }


        </style>
    </head>

    <body>

        <div class="header-wrapper">
            
            <div class="top-bar d-flex justify-content-between align-items-center">
                <div>✨ Limited Offers Available</div>
                <div class="top-bar-center">
                    Sale Upto 50% Off | <a href="#">Click Here To Shop</a>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <a href="{{ route('profile') }}">My Account</a>
                    @else
                        <a href="{{ route('login') }}">Login / Register</a>
                    @endauth
                </div>
            </div>

            <header class="main-header">
                
                <div class="header-left">
                    <div class="mobile-menu-btn" onclick="toggleSidebar()">☰</div>
                    <a href="{{ route('home') }}" class="header-logo-left">
                        <img src="{{ asset('images/logo.png') }}" alt="Shringar Logo">
                    </a>
                </div>

                <div class="header-center">
                    <a href="{{ route('home') }}" style="text-decoration: none;">
                        <span style="font-size: 28px; font-weight: 700; color: #c59d5f; letter-spacing: 3px;">SHRINGAR</span>
                    </a>
                </div>

                <div class="header-right">
                    <div class="search-wrapper">
                        <form action="{{ route('search') }}" method="GET" class="search-box-sm">
                            <input type="text" placeholder="Search..." name="q" id="searchInput" autocomplete="off" required class="search-box-input-sm">
                            <button type="submit" class="search-box-button-sm"></button>
                        </form>
                        <div id="searchResultsDropdown" class="search-dropdown"></div>
                    </div>

                    <div class="header-icons">
                        @auth
                            @if(auth()->user()->role == 'admin')
                                <div class="dropdown d-inline-block">
                                    <a href="#" class="icon-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Admin Panel">
                                        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end premium-dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">📊 Dashboard</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.orders') }}">🛒 Manage Orders</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.invoices') }}">🧾Sales Invoices</a></li>
                                        <li><a class="dropdown-item" href="{{ route('product.create') }}">🛍️ Add Product</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.categories') }}">📂 Manage Categories</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.inventory') }}">📦 Inventory Alerts</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.customers') }}">👥 Customer List</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.orders') }}">🛒 Manage Orders</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.reviews') }}">⭐ Product Reviews</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.coupons') }}">🎫 Coupons & Offers</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.settings') }}">⚙️ Store Settings</a></li>                                        
                                        </ul>
                                </div>
                            @endif
                        @endauth

                        <a href="{{ route('profile') }}" class="icon-link" title="Profile">
                            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </a>

                        <a href="{{ route('wishlist') }}" class="icon-link" title="Wishlist">
                            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                            @if(isset($wishlistCount) && $wishlistCount > 0)
                                <span class="badge">{{ $wishlistCount }}</span>
                            @endif
                        </a>

                        <a href="javascript:void(0)" onclick="openCart()" class="icon-link" title="Cart">
                            <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                            <span id="cart-count" class="badge">{{ $cartCount ?? 0 }}</span>
                        </a>
                    </div>
                </div>

                <div class="mobile-search-wrapper">
                    <form action="{{ route('search') }}" method="GET" class="search-box-mobile">
                        <input type="text" placeholder="Search for jewellery..." name="q" id="searchInputMobile" autocomplete="off" required>
                        <button type="submit">🔍</button>
                    </form>
                    <div id="searchResultsDropdownMobile" class="search-dropdown-mobile search-dropdown-item"></div>
                </div>

            </header>

            <div class="header-nav" style="background: #5E1929; border-bottom: 1px solid #f5ebe9;">
                <div class="container d-flex justify-content-center">
                    <nav class="premium-navbar d-flex gap-4 p-0 m-0">
                        @foreach(\App\Models\Category::all() as $cat)
                            <a href="{{ route('search', ['categories[]' => $cat->name]) }}" class="premium-nav-link text-uppercase">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>
        <br>
        <div id="sidebar" class="sidebar">
            <div class="sidebar-content">
                <div class="sidebar-header">
                    <h2>SHRINGAR</h2>
                    <span class="close-btn" onclick="toggleSidebar()">×</span>
                </div>
                
                <div class="sidebar-title">Main Menu</div>
                <a href="{{ route('home') }}" class="sidebar-link"><span class="icon">🏠</span> Home</a>
                <a href="javascript:void(0)" onclick="openCart()" class="sidebar-link"><span class="icon">🛒</span> My Cart</a>
                <a href="{{ route('wishlist') }}" class="sidebar-link"><span class="icon">❤️</span> Wishlist</a>
                
                <div class="sidebar-title" style="margin-top: 25px;">Collections & Categories</div>
                
                <a href="javascript:void(0)" class="sidebar-link" onclick="toggleCategoryMenu()">
                    <span class="icon">🏷️</span> Categories
                    <span id="cat-arrow" class="cat-arrow">▼</span>
                </a>
                <div id="category-submenu" class="sidebar-submenu">
                    <a href="{{ route('search', ['categories[]' => 'Western']) }}" class="sidebar-sublink">Western</a>
                    <a href="{{ route('search', ['categories[]' => 'Sets']) }}" class="sidebar-sublink">Sets</a>
                    <a href="{{ route('search', ['categories[]' => 'Earrings']) }}" class="sidebar-sublink">Earrings</a>
                    <a href="{{ route('search', ['categories[]' => 'Pendants']) }}" class="sidebar-sublink">Pendants</a>
                    <a href="{{ route('search', ['categories[]' => 'Rings']) }}" class="sidebar-sublink">Rings</a>
                    <a href="{{ route('search', ['categories[]' => 'Bangles']) }}" class="sidebar-sublink">Bangles</a>
                </div>

                <a href="{{ route('search', ['categories[]' => 'New Arrivals']) }}" class="sidebar-link"><span class="icon">✨</span> New Arrivals</a>
                <a href="{{ route('search', ['categories[]' => 'Best Sellers']) }}" class="sidebar-link"><span class="icon">⭐</span> Best Sellers</a>
                <a href="{{ route('search') }}" class="sidebar-link"><span class="icon">💎</span> Premium Collection</a>

                @if(auth()->check())
                    <div class="sidebar-title" style="margin-top: 25px;">My Account</div>
                    <a href="{{ route('profile') }}" class="sidebar-link"><span class="icon">👤</span> Profile Settings</a>

                    @if(auth()->user()->role == 'admin')
                        <div class="sidebar-title" style="margin-top: 25px; color: #c59d5f;">Admin Controls</div>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon">📊</span> Dashboard</a>
                        <a href="{{ route('admin.orders') }}" class="sidebar-link"><span class="icon">📦</span> Manage Orders</a>
                        <a href="{{ route('product.create') }}" class="sidebar-link"><span class="icon">➕</span> Add Product</a>
                    @endif
                @endif
            </div>

            <div class="sidebar-footer">
                @if(auth()->check())
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-premium">🚪 Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-premium">🔐 Login / Register</a>
                @endif
            </div>
        </div>

        <div id="cartDrawer">
            <div class="d-flex justify-content-between mb-3">
                <h5>🛒 Your Cart</h5>
                <button onclick="closeCart()" style="border:none;background:none;font-size:18px;">✖</button>
            </div>
            <div id="cartContent">
                @foreach($cartItems ?? [] as $item)
                    <div class="d-flex mb-3 border-bottom pb-2">
                        <img src="{{ asset($item->product->image ?? 'images/default.png') }}" style="width:60px;height:60px;border-radius:10px;object-fit:cover;">
                        <div class="ms-2 flex-grow-1">
                            <div class="fw-bold">{{ $item->product->name ?? 'Product' }}</div>
                            <div class="text-success">₹{{ $item->product->price ?? 0 }}</div>
                            @if($item->product->stock == 0)
                                <span class="text-danger fw-bold">Out of Stock</span>
                            @endif
                            <div class="d-flex align-items-center mt-1">
                                <button onclick="updateCart({{ $item->id }}, 'decrease')">-</button>
                                <span class="mx-2">{{ $item->quantity }}</span>
                                <button onclick="updateCart({{ $item->id }}, 'increase')" {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>+</button>
                            </div>
                        </div>
                        <button onclick="removeItem({{ $item->id }})" style="border:none;background:none;">🗑</button>
                    </div>
                @endforeach
                @if(empty($cartItems) || count($cartItems) == 0)
                    <p class="text-center mt-3">Cart is empty 😢</p>
                @endif
            </div>
        </div>

        <div id="overlay"></div>

        @yield('content')


        <section class="py-5" style="background-color: #fff;">
            <div class="container text-center">
                <h2 class="fw-bold" style="color: #5E1929;">The Essence of Shringar</h2>
                <p class="text-muted mx-auto" style="max-width: 700px; font-style: italic;">
                    "Har gehna ek kahani kehta hai. Shringar mein hum artificial jewellery ko us bariki se banate hain ki wo aapki khoobsurti mein chaar chand laga de, bina aapki jeb par bhari pade."
                </p>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <h5 class="fw-bold">✨ Handcrafted</h5>
                        <p class="small text-muted">Exquisite designs made with love.</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="fw-bold">💎 Premium Quality</h5>
                        <p class="small text-muted">Skin-friendly and long-lasting shine.</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="fw-bold">🚚 Fast Delivery</h5>
                        <p class="small text-muted">Delivering elegance at your doorstep.</p>
                    </div>
                </div>
            </div>
        </section>
        @if(request()->routeIs('home') || request()->is('/'))
            <div class="container my-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="position-relative overflow-hidden rounded-4 shadow-sm" style="height: 300px;">
                            <img src="{{ asset('images/wedding_look.jpg') }}" class="w-100 h-100 object-fit-cover" style="transition: 0.5s;">
                            <div class="position-absolute bottom-0 start-0 p-4 text-white w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                <h3 class="fw-bold">Wedding Collection</h3>
                                <a href="#" class="text-white text-decoration-underline">Shop the Look</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative overflow-hidden rounded-4 shadow-sm" style="height: 300px;">
                            <img src="{{ asset('images/minimal_look.png') }}" class="w-100 h-100 object-fit-cover">
                            <div class="position-absolute bottom-0 start-0 p-4 text-white w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                <h3 class="fw-bold">Minimalist Daily Wear</h3>
                                <a href="#" class="text-white text-decoration-underline">Explore More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif
            <section class="py-5 text-white" style="background-color: #5E1929;">
                <div class="container text-center">
                    <h3 class="fw-bold">Join the Shringar Club</h3>
                    <p>Subscribe to get updates on new arrivals and 10% off on your first order.</p>
                    <form class="d-flex justify-content-center mt-4 mx-auto" style="max-width: 500px;">
                        <input type="email" class="form-control rounded-start-pill border-0 px-4" placeholder="Your Email Address">
                        <button class="btn btn-warning rounded-end-pill px-4 fw-bold" style="background-color: #c59d5f; color: white; border: none;">JOIN</button>
                    </form>
                </div>
            </section>
            <div id="cartToast" style="position: fixed; top: 120px; right: 20px; background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; display: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1); font-weight: 500; z-index: 9999;"></div>

            <footer class="footer">
                <div class="footer-container">
                    <div class="footer-col">
                        <h2 class="footer-logo">SHRINGAR</h2>
                        <p>Discover elegance with Shringar. We bring you timeless artificial jewellery crafted with love for every special occasion. Luxury you can afford.</p>
                    </div>
                    <div class="footer-col">
                        <h3>Quick Links</h3>
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('search') }}">Shop Collection</a></li>
                            <li><a href="{{ route('terms') ?? '#' }}">Terms & Conditions</a></li>
                            <li><a href="{{ route('privacy') ?? '#' }}">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h3>Customer Support</h3>
                        <ul>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                            <li><a href="{{ route('refund') ?? '#' }}">Return & Exchange Policy</a></li>
                            <li><a href="{{ route('shipping') ?? '#' }}">Shipping Information</a></li>
                            <li><a href="{{ route('profile') }}">Track Order</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h3>Connect With Us</h3>
                        <div class="social-icons">
                            <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"></a>
                            <a href="https://www.instagram.com/styledbyshringar" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram"></a>
                            <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter"></a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>© 2026 Shringar Jewellery. All Rights Reserved. Designed for Elegance.</p>
                </div>
            </footer>

        <script>

            const BASE_URL = "{{ url('/') }}";

            // 🔥 AUTH HANDLER
            function handleAuth(res){
                if(res.status === 401 || res.status === 403 || res.redirected || res.url.includes('/login')){ 
                    let drawer = document.getElementById("cartDrawer");
                    if(drawer) drawer.style.right = "-400px";
                    
                    let sidebar = document.getElementById("sidebar");
                    if(sidebar) sidebar.classList.remove('open');
                    
                    let overlay = document.getElementById("overlay");
                    if(overlay) {
                        overlay.classList.remove('show');
                        setTimeout(() => overlay.style.display = "none", 300);
                    }
                    
                    document.body.classList.remove("cart-open");
                    window.location.href = BASE_URL + "/login";
                    return true;
                }
                return false;
            }

            // ================= SIDEBAR =================
            function toggleSidebar() {
                let sidebar = document.getElementById("sidebar");
                let overlay = document.getElementById("overlay");
                if (!sidebar || !overlay) return;

                sidebar.classList.toggle('open');
                let isOpen = sidebar.classList.contains('open');
                
                if (isOpen) {
                    overlay.style.display = "block";
                    setTimeout(() => overlay.classList.add('show'), 10);
                } else {
                    overlay.classList.remove('show');
                    setTimeout(() => overlay.style.display = "none", 300);
                }
            }

            // 🔥 Toggle Category Submenu function
            function toggleCategoryMenu() {
                let submenu = document.getElementById("category-submenu");
                let arrow = document.getElementById("cat-arrow");
                if(submenu.classList.contains("show")) {
                    submenu.classList.remove("show");
                    arrow.classList.remove("rotate");
                } else {
                    submenu.classList.add("show");
                    arrow.classList.add("rotate");
                }
            }

            // ================= CART =================
            function openCart() {
                fetch(BASE_URL + "/cart", {
                    headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json, text/html" }
                })
                .then(res => {
                    if(handleAuth(res)) throw new Error("Unauthorized"); 
                    if (res.ok) return res.text();
                    throw new Error("Failed to load cart");
                })
                .then(html => {
                    if(!html) return;
                    let drawer = document.getElementById("cartDrawer");
                    let overlay = document.getElementById("overlay");
                    document.getElementById("cartContent").innerHTML = html;
                    drawer.style.right = "0";
                    overlay.style.display = "block";
                    setTimeout(() => overlay.classList.add('show'), 10);
                    document.body.classList.add("cart-open");
                }).catch(err => console.log("Cart fetch stopped:", err.message));
            }

            function closeCart() {
                let drawer = document.getElementById("cartDrawer");
                let overlay = document.getElementById("overlay");
                if (!drawer || !overlay) return;
                drawer.style.right = "-400px";
                overlay.classList.remove('show');
                setTimeout(() => overlay.style.display = "none", 300);
                document.body.classList.remove("cart-open");
            }

            document.getElementById("overlay")?.addEventListener("click", function(){
                closeCart();
                let sidebar = document.getElementById("sidebar");
                if(sidebar && sidebar.classList.contains('open')) toggleSidebar();
            });

            // ================= COMMON POST =================
            function postData(url){
                return fetch(url,{
                    method:"POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                })
                .then(res=>{
                    if(handleAuth(res)) throw new Error("Unauthorized");
                    return res.json();
                });
            }

            // ================= CART ACTION =================
            function updateCart(id,type){
                let url = type === "increase" ? `${BASE_URL}/cart/increase/${id}` : `${BASE_URL}/cart/decrease/${id}`;
                postData(url).then(()=> reloadCart()).catch(()=>{});
            }

            function removeItem(id){
                postData(`${BASE_URL}/cart/remove/${id}`).then(()=> reloadCart()).catch(()=>{});
            }

            function reloadCart(){
                fetch(BASE_URL + "/cart", { headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json, text/html" } })
                .then(res=>{
                    if(handleAuth(res)) throw new Error("Unauthorized");
                    return res.text();
                })
                .then(html=>{
                    if(html) document.getElementById("cartContent").innerHTML = html;
                }).catch(()=>{});
            }

            // ================= ADD TO CART =================
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll('.add-to-cart').forEach(button => {
                    button.addEventListener('click', function(e){
                        e.stopImmediatePropagation();
                        let btn = this; 
                        let url = btn.dataset.url || `${BASE_URL}/cart/add/${btn.dataset.id}`;
                        if(btn.disabled) return;

                        let originalText = btn.innerHTML;
                        btn.innerText = "Adding...";
                        btn.disabled = true;

                        fetch(url, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        })
                        .then(res => {
                            if(handleAuth(res)) {
                                btn.innerHTML = originalText; btn.disabled = false;
                                throw new Error("Unauthorized");
                            }
                            return res.json();
                        })
                        .then(data => {
                            if(!data) return;
                            if(data.status === 'success'){
                                showToast("Added to cart ✅");
                                let cartBadge = document.getElementById("cart-count");
                                if(cartBadge && data.cart_count !== undefined) cartBadge.innerText = data.cart_count;
                            } else {
                                showToast(data.message || "Something went wrong");
                            }
                            btn.innerHTML = originalText; btn.disabled = false;
                        })
                        .catch(err => {
                            if(err.message !== "Unauthorized") {
                                showToast("Something went wrong");
                                btn.innerHTML = originalText; btn.disabled = false;
                            }
                        });
                    });
                });
            });

            // ================= WISHLIST =================
            document.addEventListener("click", function(e){
                if(e.target.classList.contains("wishlist-btn")){
                    let el = e.target;
                    let id = el.dataset.id;
                    postData(`${BASE_URL}/wishlist-toggle/${id}`)
                    .then(data=>{
                        if(!data) return;
                        el.innerText = data.status === "added" ? "💖" : "🤍";
                    }).catch(()=>{});
                }
            });

            function showToast(message) {
                let toast = document.getElementById('cartToast');
                if(!toast) return;
                toast.innerText = message;
                toast.style.display = "block";
                setTimeout(() => toast.style.display = "none", 2000);
            }


            // ================= SHARE PRODUCT API =================
            function shareProduct(title, text, url) {
                if (navigator.share) {
                    // Modern browsers aur Mobiles ke liye native share drawer
                    navigator.share({
                        title: title,
                        text: text,
                        url: url
                    }).then(() => {
                        console.log('Thanks for sharing!');
                    }).catch((error) => {
                        console.log('Sharing cancelled or failed', error);
                    });
                } else {
                    // Purane browsers ke liye Fallback (Link copy ho jayega)
                    navigator.clipboard.writeText(url).then(() => {
                        showToast("Product link copied to clipboard! 📋");
                    });
                }
            }

            // ================= LIVE SEARCH UI (Desktop & Mobile) =================
            function setupLiveSearch(inputId, dropdownId) {
                const input = document.getElementById(inputId);
                const dropdown = document.getElementById(dropdownId);

                if (!input || !dropdown) return;

                input.addEventListener('input', function() {
                    let query = this.value;

                    if (query.length >= 2) {
                        fetch(`${BASE_URL}/live-search?q=${query}`)
                            .then(res => res.json())
                            .then(products => {
                                if (products.length > 0) {
                                    let html = '';
                                    products.forEach(p => {
                                        let imgPath = p.image ? `${BASE_URL}/${p.image}` : `${BASE_URL}/images/default.png`;
                                        html += `
                                            <a href="${BASE_URL}/product/${p.id}" class="live-search-item">
                                                <img src="${imgPath}" class="live-search-img">
                                                <div class="live-search-info">
                                                    <span class="live-search-name">${p.name}</span>
                                                    <span class="live-search-price">₹${p.price}</span>
                                                </div>
                                            </a>
                                        `;
                                    });
                                    html += `<a href="${BASE_URL}/search?q=${query}" class="view-all-results">View All Results</a>`;
                                    dropdown.innerHTML = html;
                                    dropdown.style.display = 'block';
                                } else {
                                    dropdown.style.display = 'none';
                                }
                            });
                    } else {
                        dropdown.style.display = 'none';
                    }
                });

                // Bahar click karne par dropdown band ho jaye
                document.addEventListener('click', (e) => {
                    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.style.display = 'none';
                    }
                });
            }

            // Dono ko activate karein
            setupLiveSearch('searchInput', 'searchResultsDropdown'); 
            setupLiveSearch('searchInputMobile', 'searchResultsDropdownMobile');
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>