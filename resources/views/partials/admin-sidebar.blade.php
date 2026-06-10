<style>
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
    /* Jo page open hoga, uska menu dark ho jayega */
    .nav-link-custom.active {
        background-color: #5E1929;
        color: #fff;
    }
    .sidebar-divider {
        border-top: 1px solid #f5ebe9;
        margin: 15px;
    }
    .btn-logout-sidebar {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff1f0; /* Soft light red background */
    color: #cf1322; /* Premium dark red text */
    padding: 12px 20px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s ease;
    border: 1px solid #ffa39e;
    }
    .btn-logout-sidebar:hover {
        background-color: #cf1322; /* Solid red on hover */
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(207, 19, 34, 0.2);
        transform: translateY(-2px);
    }

    .btn-logout-sidebar i {
        font-size: 18px;
    }

    /* Sidebar list item fix */
    .nav-item {
        list-style: none;
    }
</style>

<div class="admin-sidebar">
    <h4 class="sidebar-heading">Shringar Admin</h4>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>📊</span> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.orders') }}" class="nav-link-custom {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <span>🛒</span> Customer Orders
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.invoices') }}" class="nav-link-custom {{ request()->routeIs('admin.invoices') ? 'active' : '' }}">
                <span>🧾</span> Sales Invoices
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('product.create') }}" class="nav-link-custom {{ request()->routeIs('product.create') ? 'active' : '' }}">
                <span>🛍️</span> Add Products
            </a>
        </li>
        
        <div class="sidebar-divider"></div>

        <li class="nav-item">
            <a href="{{ route('admin.categories') }}" class="nav-link-custom {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                <span>📂</span> Categories
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.inventory') }}" class="nav-link-custom {{ request()->routeIs('admin.inventory') ? 'active' : '' }}">
                <span>📦</span> Inventory Alerts
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.customers') }}" class="nav-link-custom {{ request()->routeIs('admin.customers') ? 'active' : '' }}">
                <span>👥</span> Customers
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.coupons') }}" class="nav-link-custom {{ request()->routeIs('admin.coupons') ? 'active' : '' }}">
                <span>🎫</span> Coupons & Offers
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.reviews') }}" class="nav-link-custom {{ request()->routeIs('admin.reviews') ? 'active' : '' }}">
                <span>⭐</span> Product Reviews
            </a>
        </li>

        <div class="sidebar-divider"></div>

        <li class="nav-item">
            <a href="{{ route('admin.settings') }}" class="nav-link-custom {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <span>⚙️</span> Store Settings
            </a>
        </li>
        
        <div class="sidebar-divider"></div>

        <li class="nav-item mt-4 px-3">
            <a href="#" class="btn-logout-sidebar" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
        
    </ul>
</div>