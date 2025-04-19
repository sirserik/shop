<div class="section-menu-left">
    <div class="box-logo">
        <a href="{{ route('admin.dashboard') }}" id="site-logo-inner">
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
                    <a href="{{ route('admin.dashboard') }}" class="">
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
                            <a href="{{ route('admin.products.create') }}">
                                <div class="text">Add Product</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.products.index') }}">
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
                            <a href="{{ route('admin.brands.create') }}">
                                <div class="text">New Brand</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.brands.index') }}">
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
                            <a href="{{ route('admin.categories.create') }}">
                                <div class="text">New Category</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.categories.index') }}">
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
                            <a href="{{ route('admin.orders.index') }}">
                                <div class="text">Orders</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.orders.tracking') }}">
                                <div class="text">Order tracking</div>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Slider --}}
                <li class="menu-item">
                    <a href="{{ route('admin.slider.index') }}">
                        <div class="icon"><i class="icon-image"></i></div>
                        <div class="text">Slider</div>
                    </a>
                </li>

                {{-- Coupons --}}
                <li class="menu-item">
                    <a href="{{ route('admin.coupons.index') }}">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Coupons</div>
                    </a>
                </li>

                {{-- Users --}}
                <li class="menu-item">
                    <a href="{{ route('admin.users.index') }}">
                        <div class="icon"><i class="icon-user"></i></div>
                        <div class="text">User</div>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="menu-item">
                    <a href="{{ route('admin.settings.index') }}">
                        <div class="icon"><i class="icon-settings"></i></div>
                        <div class="text">Settings</div>
                    </a>
                </li>
                {{-- Logout --}}
                <li class="menu-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 0.5rem;">
                            <div class="icon">
                                <i class="icon-log-out" style="font-weight: bold; font-size: 20px"></i>
                            </div>
                            <div class="text" style="font-weight: bold; font-size: 16px">Logout</div>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</div>
