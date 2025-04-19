<li><a href="{{ route('user.dashboard') }}" class="menu-link menu-link_us-s">Dashboard</a></li>
<li><a href="{{ route('orders.index') }}" class="menu-link menu-link_us-s">Orders</a></li>
<li><a href="{{ route('account.address') }}" class="menu-link menu-link_us-s">Addresses</a></li>
<li><a href="{{ route('account.details') }}" class="menu-link menu-link_us-s">Account Details</a></li>
<li><a href="{{ route('wishlist.index') }}" class="menu-link menu-link_us-s">Wishlist</a></li>

<li>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="menu-link menu-link_us-s" style="background: none; border: none; padding: 0; cursor: pointer;">
            Logout
        </button>
    </form>
</li>

