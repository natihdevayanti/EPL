<ul class="nav navbar-nav float-right">
    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('front.index') }}">Beranda</a>
    </li>
    <li class="nav-item {{ Request::is('product*') ? 'active' : '' }} {{ Request::is('category*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('front.product') }}">Produk</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('front.about') }}">Tentang Kami</a>
    </li>
    <li class="nav-item {{ Request::is('find-order*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('front.find_order') }}">Cari Pesanan</a>
    </li>
</ul>