<div class="section-menu-left">
    <div class="box-logo">
        <a href="index.html" id="site-logo-inner">
            <img class="" id="logo_header" alt="" src="images/logo/logo.png"
                 data-light="images/logo/logo.png" data-dark="images/logo/logo.png">
        </a>
        <div class="button-show-hide">
            <i class="icon-menu-left"></i>
        </div>
    </div>
    <div class="center">
        <div class="center-item">
            <div class="center-heading">Main Home</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="index.html" class="">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Dashboard</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="center-item">
            <ul class="menu-list">
                {{-- Products --}}
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-shopping-cart"></i></div>
                        <div class="text">Products</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('products.create') }}">
                                <div class="text">Add Product</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('products.index') }}">
                                <div class="text">Products</div>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Brand --}}
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-layers"></i></div>
                        <div class="text">Brand</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('brands.create') }}">
                                <div class="text">New Brand</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('brands.index') }}">
                                <div class="text">Brands</div>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Category --}}
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-layers"></i></div>
                        <div class="text">Category</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('categories.create') }}">
                                <div class="text">New Category</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('categories.index') }}">
                                <div class="text">Categories</div>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Order --}}
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-file-plus"></i></div>
                        <div class="text">Order</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('orders.index') }}">
                                <div class="text">Orders</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('orders.tracking') }}">
                                <div class="text">Order tracking</div>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Slider --}}
                <li class="menu-item">
                    <a href="{{ route('slider.index') }}">
                        <div class="icon"><i class="icon-image"></i></div>
                        <div class="text">Slider</div>
                    </a>
                </li>

                {{-- Coupons --}}
                <li class="menu-item">
                    <a href="{{ route('coupons.index') }}">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Coupons</div>
                    </a>
                </li>

                {{-- Users --}}
                <li class="menu-item">
                    <a href="{{ route('users.index') }}">
                        <div class="icon"><i class="icon-user"></i></div>
                        <div class="text">User</div>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="menu-item">
                    <a href="{{ route('settings.index') }}">
                        <div class="icon"><i class="icon-settings"></i></div>
                        <div class="text">Settings</div>
                    </a>
                </li>
                {{-- Logout --}}
                <li class="menu-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="icon-logout"></i>
                            <span class="text">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</div>
