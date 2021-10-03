@extends('layouts.ecommerce')

@section('title')
<title>{{ $product->name }} - Ecommerce</title>
@endsection

@section('css')
<style>
    input[type='radio'] { display:none; }
    input[type='radio'] + label {
        display:inline-block;
        background-color:#92acff;
        padding:5px 10px;
        margin-right: 10px;
        margin-bottom: 10px;
        border-radius: 3px;
        color: white;
        cursor:pointer;
        transition: all 300ms linear 0s; }
        input[type='radio']:hover + label {
            background-color:#3d64e6;
        }
        input[type='radio']:checked + label {
            background-color:#3d64e6;
        }
</style>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content text-center">
                <h2>{{ $product->name }}</h2>
                <div class="page_link">
                    <a href="{{ url('/') }}">Beranda</a>
                    <a href="#">{{ $product->name }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<div class="product_image_area">
    <div class="container">
        <div class="row s_product_inner">
            <div class="col-lg-6">
                <div class="s_product_img">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" id="product_image" src="{{ asset('storage/products/' . $product->image) }}"
                                    alt="{{ $product->name }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="s_product_text">
                    <h3>{{ $product->name }}</h3>
                    <h2 id="prc">Rp {{ number_format($product->variant->first()->price, 2, ",", ".") }}</h2>
                    <ul class="list">
                        <li>
                            <a class="active" href="#">
                                <span>Kategori</span> : {{ $product->category->name }}</a>
                        </li>
                    </ul>
                    <hr>

                    <form action="{{ route('front.cart') }}" method="POST">
                        @csrf
                        <div class="product_variant">
							<h4>Varian</h4>
                            <div class="row">
                                <div class="col-12">
									@forelse($product->variant as $row)
                                    <input type="radio" name="btn_variant" id="var{{ $row->id }}" value="{{ $row->id }}">
                                    <label for="var{{ $row->id }}">{{ $row->name }}</label>
									@empty

									@endforelse
                                    <input type="hidden" name="product_variant_id" value="" required>
								</div>
							</div>
							<div class="row selected_variant">
								<div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" id="tableVarian" style="display:none">
                                            <thead>
												<tr>
													<th>Stok</th>
													<th>Berat</th>
													<th>Harga</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td id="var_stock"></td>
													<td id="var_weight"></td>
													<td id="var_price"></td>
												</tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
							</div>
                        </div>
                        <hr>
                        <div class="product_count">
                            <label for="qty">Quantity:</label>
                            <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:"
                                class="input-text qty">
                            <input type="hidden" name="product_id" value="{{ $product->id }}" class="form-control">
                            <button
                                onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                class="increase items-count" type="button">
                                <i class="lnr lnr-chevron-up"></i>
                            </button>
                            <button
                                onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                                class="reduced items-count" type="button">
                                <i class="lnr lnr-chevron-down"></i>
                            </button>
                        </div>
                        <div class="card_area">
                            <button class="main_btn" id="add_to_cart">Masukkan Keranjang</button>
                        </div>

                        @if (session('success'))
                        <div class="alert alert-success mt-2">{{ session('success') }}</div>
                        @elseif (session('error'))
                        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--================End Single Product Area =================-->

<!--================Product Description Area =================-->
<section class="product_description_area">
    <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" id="desc-tab" data-toggle="tab" href="#desc" role="tab"
                    aria-controls="home" aria-selected="true">Deskripsi</a>
			</li>
			<li class="nav-item">
                <a class="nav-link show" id="review-tab" data-toggle="tab" href="#review" role="tab"
                    aria-controls="home" aria-selected="true">Ulasan</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="desc" role="tabpanel" aria-labelledby="desc-tab"
                style="color: black">
                {!! $product->description !!}
			</div>
        </div>
    </div>
</section>
<!--================End Product Description Area =================-->
@endsection

@section('js')
<script>

$(document).ready(function() {
    const firstVariant = {{ $product->variant->first()->id }};
    $("#var"+firstVariant).prop("checked", true).trigger("change");
})

$("input[name=btn_variant]").on('change', function(e) {
    e.preventDefault();
  
    $("input[name=product_variant_id]").val(this.value);
    var var_id = this.value;
    $.ajax({
        type: 'GET',
        url: "/product/variant/" + var_id,
        data: { var: var_id },
        dataType: 'JSON',
        success: function(res){
            showVariantInfo(res);
        }
    });
});


function showVariantInfo(info) {
    var myObj = {
        style: "currency",
        currency: "IDR"
    }
    var filename = info[0].image;
    var src = "/storage/variants/" + filename;
    $('#product_image').attr('alt', info[0].name);
    $('#product_image').attr('src', src);
    
    $('.selected_variant').fadeOut( '200', function() {
        $('#tableVarian').show();
        $('#var_stock').text(info[0].stock);
        $('#var_weight').text(info[0].weight + ' gr');
        $('#var_price').text(info[0].price);
        $('#prc').html(info[0].price.toLocaleString("id-ID", myObj));
    }).fadeIn('200');
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
@endsection
