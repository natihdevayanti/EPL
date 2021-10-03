@extends('layouts.ecommerce')

@section('title')
<title>Jual Produk - Ecommerce</title>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content text-center">
                <h2>Jual Produk</h2>
                <div class="page_link">
                    <a href="{{ route('front.index') }}">Home</a>
                    <a href="{{ route('front.product') }}">Produk</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<section class="cat_product_area section_gap">
    <div class="container-fluid">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="product_top_bar">
                    <div class="left_dorp">
                    <br>
                    </div>
                    <div class="right_page ml-auto">
                        {{ $products->links() }}
                    </div>
                </div>
                <div class="latest_product_inner row">
                    @forelse ($products as $row)
                    <div class="col">
                        <div class="f_p_item">
                            <div class="f_p_img">
                                <a href="{{ url('/product/' . $row->id) }}">
                                    <img class="product-center-cropped" src="{{ asset('storage/products/' . $row->image) }}"
                                        alt="{{ $row->name }}">
                                </a>
                            </div>
                            <a href="{{ url('/product/' . $row->id) }}">
                                <h4>{{ $row->name }}</h4>
                            </a>
                            @if( number_format($row->variant->first()->price) != number_format($row->variant->last()->price) )
                            <h4>Rp {{ number_format($row->variant->first()->price) }} - Rp {{ number_format($row->variant->last()->price) }}</h4>
                            @else
                            <h4>Rp {{ number_format($row->variant->first()->price) }}</h4>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-md-12">
                        <h3 class="text-center">Tidak ada produk</h3>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-3">
                <div class="left_sidebar_area">
                    <aside class="left_widgets cat_widgets">
                        <div class="l_w_title">
                            <h3>Kategori Produk</h3>
                        </div>
                        <div class="widgets_inner">
                            <ul class="list">
                                <li class="list-group-item border-0 {{ Request::is('product') ? 'active' : '' }}">
                                    <strong><a href="{{ route('front.product') }}">All Product</a></strong>
                                </li>
                                @foreach ($categories as $category)
                                    <li class="list-group-item border-0 {{ request()->segment(2) == $category->slug ? 'active' : '' }}">
                                        <strong><a href="{{ url('/category/' . $category->slug) }}">{{ $category->name }}</a></strong>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>

        <div class="row">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
