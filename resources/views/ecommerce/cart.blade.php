@extends('layouts.ecommerce')

@section('title')
<title>Keranjang Belanja - Ecommerce</title>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content text-center">
                <h2>Keranjang Belanja</h2>
                <div class="page_link">
                    <a href="{{ url('/') }}">Beranda</a>
                    <a href="{{ route('front.list_cart') }}">Cart</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<!--================Cart Area =================-->
<section class="cart_area">
    <div class="container">
        <div class="cart_inner">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Produk</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Total</th>
                            <th scope="col" style="text-align:center"></th>
                        </tr>
                    </thead>
                    <form action="{{ route('front.update_cart') }}" method="post">
                        @csrf
                        <tbody>
                            @if(!auth()->guard('customer')->check())
                            @forelse ($carts as $row)
                            <tr id="rowu{{ $row['product_variant_id'] }}">
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="{{ asset('storage/variants/' . $row['product_variant_image']) }}"
                                                width="100px" height="100px" alt="{{ $row['product_variant_name'] }}">
                                        </div>
                                        <div class="media-body">
                                            <p>{{ $row['product_name'] }}</p>
                                            <p><small>{{ $row['product_variant_name'] }}</small></p>
                                            @if ($row['is_avail'] == false)
                                            <p style="color: red">Stok Habis/Permintaan melebihi stok</p>
                                            @endif
                                            <p id="isavailu{{ $row['product_variant_id'] }}"
                                                style="display:none; color:red">Stok Habis/Permintaan melebihi stok</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5>Rp {{ number_format($row['product_variant_price'], 2, ",", ".") }}</h5>
                                </td>
                                <td>
                                    <div class="product_count">
                                        <input type="number" name="qty[]" id="sstu{{ $row['product_variant_id'] }}"
                                            maxlength="12" value="{{ $row['qty'] }}" title="Quantity:"
                                            class="input-text qty">
                                        <input type="hidden" name="qty[]" id="cachedu{{ $row['product_variant_id'] }}"
                                            maxlength="12" value="{{ $row['qty'] }}">
                                        <input type="hidden" name="product_variant_id[]"
                                            id="itemu{{ $row['product_variant_id'] }}"
                                            value="{{ $row['product_variant_id'] }}" class="form-control">
                                        <button
                                            onclick="var result = document.getElementById('sstu{{ $row['product_variant_id'] }}'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                            class="increase items-count" type="button">
                                            <i class="lnr lnr-chevron-up"></i>
                                        </button>
                                        <button
                                            onclick="var result = document.getElementById('sstu{{ $row['product_variant_id'] }}'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;"
                                            class="reduced items-count" type="button">
                                            <i class="lnr lnr-chevron-down"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <h5 id="prcu{{ $row['product_variant_id'] }}">Rp
                                        {{ number_format($row['product_variant_price'] * $row['qty'], 2, ",", ".") }}
                                    </h5>
                                </td>
                                <td>
                                    <a class="btn btn-light"
                                        href="{{ route('front.remove_from_cart', $row['product_variant_id']) }}"><i
                                            class="lnr lnr-trash" style="color:red;weight:bold"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">Tidak ada belanjaan</td>
                            </tr>
                            @endforelse

                            @elseif (auth()->guard('customer')->check())
                            @forelse ($carts_reg->details as $row)
                            <tr id="rowr{{ $row->product_variant_id }}">
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="{{ asset('storage/variants/' . $row->variant->image) }}"
                                                width="100px" height="100px" alt="{{ $row->variant->name }}">
                                        </div>
                                        <div class="media-body">
                                            <p>{{ $row->variant->product->name }}</p>
                                            <p><small>{{ $row->variant->name }}</small></p>
                                            @if ($row->is_avail == false)
                                            <p style="color: red">Stok Habis/Permintaan melebihi stok</p>
                                            @endif
                                            <p id="isavailr{{ $row->product_variant_id }}"
                                                style="display:none; color:red">Stok Habis/Permintaan melebihi stok</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5>Rp {{ number_format($row->variant->price, 2, ",", ".") }}</h5>
                                </td>
                                <td>
                                    <div class="product_count">
                                        <input type="number" name="qty[]" id="sstr{{ $row->product_variant_id }}"
                                            maxlength="12" value="{{ $row->qty }}" title="Quantity:"
                                            class="input-text qty">
                                        <input type="hidden" name="qty[]" id="cachedr{{ $row->product_variant_id }}"
                                            maxlength="12" value="{{ $row->qty }}">
                                        <input type="hidden" name="product_variant_id[]"
                                            id="itemr{{ $row->product_variant_id }}"
                                            value="{{ $row->product_variant_id }}" class="form-control">
                                        <button
                                            onclick="var result = document.getElementById('sstr{{ $row->product_variant_id }}'); var sst = result.value; if( !isNaN( sst )) result.value++; return false;"
                                            class="increase items-count" type="button">
                                            <i class="lnr lnr-chevron-up"></i>
                                        </button>
                                        <button
                                            onclick="var result = document.getElementById('sstr{{ $row->product_variant_id }}'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;"
                                            class="reduced items-count" type="button">
                                            <i class="lnr lnr-chevron-down"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <h5 id="prcr{{ $row->product_variant_id }}">Rp
                                        {{ number_format($row->price, 2, ",", ".") }}</h5>
                                </td>
                                <td>
                                    <a class="btn btn-light"
                                        href="{{ route('front.remove_from_cart', $row->product_variant_id) }}"><i
                                            class="lnr lnr-trash" style="color:red;weight:bold"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">Tidak ada belanjaan</td>
                            </tr>
                            @endforelse
                            @endif
                            <tr class="bottom_button">
                                <td>
                                    <button class="gray_btn" style="cursor: pointer;">Perbarui Keranjang</button>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    @if (auth()->guard('customer')->check())
                                    <h5 id="totalr">Rp {{ number_format($carts_reg->total_cost, 2, ",", ".") }}</h5>
                                    @else
                                    <h5 id="totalu">Rp {{ number_format($subtotal, 2, ",", ".") }}</h5>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </form>
                </table>
            </div>
        </div>
        <div class="row float-right">
            <div class="col-md-12">
                <div class="cart-inner">
                    <div class="out_button_area">
                        <div class="checkout_btn_inner">
                            <a class="gray_btn" href="{{ route('front.product') }}">Lanjut Belanja</a>
                            <a class="blue_btn" href="{{ route('front.checkout') }}">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->
@endsection

@section('js')
<script>
    $(document).ready(function () {
        var myObj = {
            style: "currency",
            currency: "IDR"
        }
        @if(auth()-> guard('customer')-> check())
        @foreach($carts_reg-> details as $row)
        $(".items-count").on('click', function () {
            var newQty = $("#sstr{{ $row->product_variant_id }}").val();
            if (newQty == 0) {
                if (confirm("Hapus item dari keranjang?")) {
                    $("#sstr{{ $row->product_variant_id }}").val(0);
                    $("#sstrm{{ $row->product_variant_id }}").val(0);
                    $("#cachedr{{ $row->product_variant_id }}").val(0);
                    $("#cachedrm{{ $row->product_variant_id }}").val(0);
                } else {
                    $("#sstr{{ $row->product_variant_id }}").val(1);
                    $("#sstrm{{ $row->product_variant_id }}").val(1);
                    $("#cachedr{{ $row->product_variant_id }}").val(1);
                    $("#cachedrm{{ $row->product_variant_id }}").val(1);
                }
            }
            newQty = $("#sstr{{ $row->product_variant_id }}").val();

            var itemID = $("#itemr{{ $row->product_variant_id }}").val();
            console.log(itemID + "" + newQty);

            if (newQty != $("#cachedr{{ $row->product_variant_id }}").val()) {
                $.ajax({
                    type: "POST",
                    url: "/cart/update-reg",
                    data: {
                        id: itemID,
                        qty: newQty
                    },
                    dataType: "JSON",
                    success: function (res) {
                        $("#rowr{{ $row->product_variant_id }}").fadeOut(function () {
                            $("#prcr{{ $row->product_variant_id }}").html(res.variant.price.toLocaleString("id-ID", myObj));
                            $("#prcrm{{ $row->product_variant_id }}").html(res.variant.price.toLocaleString("id-ID", myObj));

                            $("#sstrm{{ $row->product_variant_id }}").val(res.variant.qty);

                            if (res.variant.is_avail === false) {
                                $("#isavailr{{ $row->product_variant_id }}").show();
                                $("#isavailrm{{ $row->product_variant_id }}").show();
                            } else {
                                $("#isavailr{{ $row->product_variant_id }}").hide();
                                $("#isavailm{{ $row->product_variant_id }}").hide();
                            }

                            $("#cachedr{{ $row->product_variant_id }}").val(res.variant.qty);
                            $("#cachedrm{{ $row->product_variant_id }}").val(res.variant.qty);
                            $("#totalr").text(res.subtotal.toLocaleString("id-ID", myObj));
                            $("#totalrm").text(res.subtotal.toLocaleString("id-ID", myObj));
                        }).fadeIn();
                    },
                    error: function (xhr, status, err) {
                        console.log(err);
                    }
                })
            }
        })
        @endforeach
        @endif

        @if(!auth()->guard('customer')->check())
        @foreach($carts as $row)
        $('.qty, .items-count').on('change keyup click', function () {
            var newQty = $("#sstu{{ $row['product_variant_id'] }}").val();
            if (newQty == 0) {
                if (confirm("Hapus item dari keranjang?")) {
                    $("#sstu{{ $row['product_variant_id'] }}").val(0);
                    $("#sstum{{ $row['product_variant_id'] }}").val(0);
                    $("#cachedu{{ $row['product_variant_id'] }}").val(0);
                    $("#cachedum{{ $row['product_variant_id'] }}").val(0);
                } else {
                    $("#sstu{{ $row['product_variant_id'] }}").val(1);
                    $("#sstum{{ $row['product_variant_id'] }}").val(1);
                    $("#cachedu{{ $row['product_variant_id'] }}").val(1);
                    $("#cachedum{{ $row['product_variant_id'] }}").val(1);
                }
            }
            newQty = $("#sstu{{ $row['product_variant_id'] }}").val();
            var itemID = $("#itemu{{ $row['product_variant_id'] }}").val();

            if (newQty != $("#cachedu{{ $row['product_variant_id'] }}").val()) {
                $.ajax({
                    type: "POST",
                    url: "/cart/update-unreg",
                    data: {
                        id: itemID,
                        qty: newQty
                    },
                    dataType: "JSON",
                    success: function (res) {
                        $("#rowu{{ $row['product_variant_id'] }}").fadeOut(function () {
                            var id = itemID;
                            var prc = Number(res.carts[id]['product_variant_price'] * res.carts[id]['qty']);
                            $("#prcu{{ $row['product_variant_id'] }}").html(prc.toLocaleString("id-ID", myObj));
                            $("#prcum{{ $row['product_variant_id'] }}").html(prc.toLocaleString("id-ID", myObj));

                            $("#sstum{{ $row['product_variant_id'] }}").val(res.carts[id]['qty']);

                            if (res.carts[id]['is_avail'] === false) {
                                $("#isavailu{{ $row['product_variant_id'] }}").show();
                                $("#isavailum{{ $row['product_variant_id'] }}").show();
                            } else {
                                $("#isavailu{{ $row['product_variant_id'] }}").hide();
                                $("#isavailum{{ $row['product_variant_id'] }}").hide();
                            }

                            $("#cachedu{{ $row['product_variant_id'] }}").val(res.carts[id]['qty']);
                            $("#cachedum{{ $row['product_variant_id'] }}").val(res.carts[id]['qty']);
                            $("#totalu").text(res.subtotal.toLocaleString("id-ID", myObj));
                            $("#totalum").text(res.subtotal.toLocaleString("id-ID", myObj));
                        }).fadeIn();
                    },
                    error: function (xhr, status, err) {
                        console.log(err);
                    }
                })
            }
        })
        @endforeach
        @endif
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

</script>
@endsection
