@extends('layouts.ecommerce')

@section('title')
<title>Ecommerce</title>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div id="slider" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach($slider_contents as $key => $row)
                    <div class="carousel-item {{$key == 0 ? 'active' : '' }}" id="{{ $row->id }}">
                        <img class="slider-center-cropped" src="{{ asset('storage/slider/' . $row->image) }}"
                            alt="{{ $row->title }}">
                        <div class="carousel-caption d-none d-md-block">
                            <h1>{{ $row->title }}</h1>
                            <h5>{{ $row->subtitle }}</h5>
                            <p>{{ $row->text }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Sebelumnya</span>
                </a>
                <a class="carousel-control-next" href="#slider" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Selanjutnya</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!--================End Home Banner Area =================-->

<!--================Feature Product Area =================-->
<section class="feature_product_area section_gap">
    <div class="main_box">
        <div class="container-fluid">
            <div class="row">
                <div class="main_title">
                    <h2>Produk Fitur</h2>
                    <p>Produk fitur terupdate</p>
                </div>
            </div>
            <div class="row">
                @forelse($features as $row)
                <div class="col">
                    <div class="f_p_item">
                        <div class="f_p_img">
                            <a href="{{ url('/product/' . $row->id) }}">
                                <img id="pic{{ $row }}" class="home-product-center-cropped"
                                    src="{{ asset('storage/products/' . $row->image) }}" alt="{{ $row->name }}">
                            </a>
                        </div>
                        <a href="{{ url('/product/' . $row->id) }}">
                            <h4>{{ $row->name }}</h4>
                        </a>
                        @if( number_format($row->variant->first()->price) != number_format($row->variant->last()->price)
                        )
                        <h5>Rp {{ number_format($row->variant->first()->price) }} - Rp
                            {{ number_format($row->variant->last()->price) }}</h5>
                        @else
                        <h5>Rp {{ number_format($row->variant->first()->price) }}</h5>
                        @endif
                    </div>
                </div>
                @empty

                @endforelse
            </div>
        </div>
    </div>
</section>
<section class="feature_product_area section_gap">
    <div class="main_box">
        <div class="container-fluid">
            <div class="row">
                <div class="main_title">
                    <h2>Produk Terbaru</h2>
                    <p>Produk baru ditambahkan</p>
                </div>
            </div>
            <div class="row">
                @forelse($products as $row)
                <div class="col">
                    <div class="f_p_item">
                        <div class="f_p_img">
                            <a href="{{ url('/product/' . $row->id) }}">
                                <img class="home-product-center-cropped"
                                    src="{{ asset('storage/products/' . $row->image) }}" alt="{{ $row->name }}">
                            </a>
                        </div>
                        <a href="{{ url('/product/' . $row->id) }}">
                            <h4>{{ $row->name }}</h4>
                        </a>
                        @if( number_format($row->variant->first()->price) != number_format($row->variant->last()->price)
                        )
                        <h5>Rp {{ number_format($row->variant->first()->price) }} - Rp
                            {{ number_format($row->variant->last()->price) }}</h5>
                        @else
                        <h5>Rp {{ number_format($row->variant->first()->price) }}</h5>
                        @endif
                    </div>
                </div>
                @empty

                @endforelse
            </div>

            {{-- <div class="row">
					{{ $products->links() }}
            </div> --}}
        </div>
    </div>
</section>
<!--================End Feature Product Area =================-->

@endsection

@section('js')
<script>

</script>
@endsection
