<nav class="sidebar-nav">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="nav-icon icon-speedometer"></i> Dashboard
            </a>
        </li>
        @if ( Auth::user()->user_type_id == 1 )
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.index') }}">
                <i class="nav-icon fa fa-user-circle-o"></i> Admin
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.home_management') }}">
                <i class="nav-icon icon-home"></i> Manajemen Beranda
            </a>
        </li>
        @endif

        @if ( Auth::user()->user_type_id == 1 )
        <li class="nav-title">MANAJEMEN PRODUK</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('category.index') }}">
                <i class="nav-icon icon-drop"></i> Kategori
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('product.index') }}">
                <i class="nav-icon icon-drop"></i> Produk
            </a>
        </li>
        @endif

        <li class="nav-title">Pesanan</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('orders.index') }}">
                <i class="nav-icon icon-basket-loaded"></i> Proses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('orders.orders_pending') }}">
                <i class="nav-icon icon-basket-loaded"></i> Pending
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('orders.orders_done') }}">
                <i class="nav-icon icon-basket-loaded"></i> Selesai
            </a>
        </li>

        @if ( Auth::user()->user_type_id == 1 )
        <li class="nav-title">Laporan</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('report.product_statistic') }}">
                <i class="nav-icon icon-puzzle"></i> Statistik Produk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('report.finance') }}">
                <i class="nav-icon icon-puzzle"></i> Keuangan
            </a>
        </li>
        @endif
    </ul>
</nav>
