<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="img/favicon.png" type="image/png">

    @yield('title')

    <link rel="stylesheet" href="{{ asset('ecommerce/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/vendors/linericon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/vendors/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/vendors/lightbox/simpleLightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/vendors/nice-select/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/vendors/animate-css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/vendors/jquery-ui/jquery-ui.css') }}">

    <link rel="stylesheet" href="{{ asset('ecommerce/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/css/select2-bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('ecommerce/css/responsive.css') }}">

    @yield('css')
</head>

<body>
    <!--================Header Menu Area =================-->
    <header class="header_area">
        <div class="top_menu row m0">
            <div class="container-fluid">
                <div class="float-right">
                    <ul class="right_side">
                        @if (auth()->guard('customer')->check())
                        <li><a href="{{ route('customer.dashboard') }}">Akun Saya</a></li>
                        <li><a href="{{ route('customer.logout') }}">Logout</a></li>
                        @else
                        <li><a href="{{ route('customer.signup') }}">Sign Up</a></li>
                        <li><a href="{{ route('customer.login') }}">Login</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="main_menu">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h" href="{{ url('/') }}">
                        <h3>Ivone Seafood Store</h3>
                        {{-- <img src="imgsrc.jpg" alt=""> --}}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                        <div class="row w-100">
                            <div class="col-lg-7 pr-0">
                                @include('layouts.ecommerce.module.menu')
                            </div>

                            <div class="col-lg-5">
                                <ul class="nav navbar-nav navbar-right right_nav pull-right">
                                    <hr>
                                    <li class="nav-item" onclick="toggleSearch()">
                                        <a class="icons"><i class="lnr lnr-magnifier"></i></a>
                                    </li>
                                    <hr>
                                    <li class="nav-item">
                                        <a href="#" class="icons" data-toggle="modal" data-target="#cartModal">
                                            <i class="lnr lnr-cart"></i>
                                        </a>
                                    </li>
                                    <hr>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <div id="myOverlay" class="overlay">
                <div class="overlay-content float-right">
                    <form class="form-inline" action="{{ route('front.product') }}" method="get">
                        <div class="col-auto">
                            <input class="form-control float-right" type="text" name="q" placeholder="Cari..."
                                value="{{ request()->q }}">
                        </div>
                        <div class="col-auto">
                            <button class="form-control btn btn-secondary"><i class="lnr lnr-magnifier"></i>
                                Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="modal fade w-100" id="cartModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header pl-0">
                    <h5 class="modal-title w-100 text-center position-absolute">Keranjang Saya</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                                            <tr id="rowum{{ $row['product_variant_id'] }}">
                                                <td>
                                                    <div class="media">
                                                        <div class="d-flex">
                                                            <img src="{{ asset('storage/variants/' . $row['product_variant_image']) }}"
                                                                width="100px" height="100px"
                                                                alt="{{ $row['product_variant_name'] }}">
                                                        </div>
                                                        <div class="media-body">
                                                            <p>{{ $row['product_name'] }}</p>
                                                            <p><small>{{ $row['product_variant_name'] }}</small></p>
                                                            @if ($row['is_avail'] == false)
                                                            <p style="color: red">Stok Habis/Permintaan melebihi stok
                                                            </p>
                                                            @endif
                                                            <p id="isavailum{{ $row['product_variant_id'] }}"
                                                                style="display:none; color:red">Stok Habis/Permintaan
                                                                melebihi stok</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5>Rp
                                                        {{ number_format($row['product_variant_price'], 2, ",", ".") }}
                                                    </h5>
                                                </td>
                                                <td>
                                                    <div class="product_count">
                                                        <input type="number" name="qty[]"
                                                            id="sstum{{ $row['product_variant_id'] }}" maxlength="12"
                                                            value="{{ $row['qty'] }}" title="Quantity:"
                                                            class="input-text qty">
                                                        <input type="hidden" name="qty[]"
                                                            id="cachedum{{ $row['product_variant_id'] }}" maxlength="12"
                                                            value="{{ $row['qty'] }}">
                                                        <input type="hidden" name="product_variant_id[]"
                                                            id="itemum{{ $row['product_variant_id'] }}"
                                                            value="{{ $row['product_variant_id'] }}"
                                                            class="form-control">
                                                        <button
                                                            onclick="var result = document.getElementById('sstum{{ $row['product_variant_id'] }}'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                                            class="increase items-count" type="button">
                                                            <i class="lnr lnr-chevron-up"></i>
                                                        </button>
                                                        <button
                                                            onclick="var result = document.getElementById('sstum{{ $row['product_variant_id'] }}'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;"
                                                            class="reduced items-count" type="button">
                                                            <i class="lnr lnr-chevron-down"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 id="prcum{{ $row['product_variant_id'] }}">Rp
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
                                            <tr id="rowrm{{ $row->product_variant_id }}">
                                                <td>
                                                    <div class="media">
                                                        <div class="d-flex">
                                                            <img src="{{ asset('storage/variants/' . $row->variant->image) }}"
                                                                width="100px" height="100px"
                                                                alt="{{ $row->variant->name }}">
                                                        </div>
                                                        <div class="media-body">
                                                            <p>{{ $row->variant->product->name }}</p>
                                                            <p><small>{{ $row->variant->name }}</small></p>
                                                            @if ($row->is_avail == false)
                                                            <p style="color: red">Stok Habis/Permintaan melebihi stok
                                                            </p>
                                                            @endif
                                                            <p id="isavailrm{{ $row->product_variant_id }}"
                                                                style="display:none; color:red">
                                                                Stok Habis/Permintaan melebihi stok</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5>Rp {{ number_format($row->variant->price, 2, ",", ".") }}</h5>
                                                </td>
                                                <td>
                                                    <div class="product_count">
                                                        <input type="number" name="qty[]"
                                                            id="sstrm{{ $row->product_variant_id }}" maxlength="12"
                                                            value="{{ $row->qty }}" title="Quantity:"
                                                            class="input-text qty">
                                                        <input type="hidden" name="qty[]"
                                                            id="cachedrm{{ $row->product_variant_id }}" maxlength="12"
                                                            value="{{ $row->qty }}">
                                                        <input type="hidden" name="product_variant_id[]"
                                                            id="itemrm{{ $row->product_variant_id }}"
                                                            value="{{ $row->product_variant_id }}" class="form-control">
                                                        <button
                                                            onclick="var result = document.getElementById('sstrm{{ $row->product_variant_id }}'); var sst = result.value; if( !isNaN( sst )) result.value++; return false;"
                                                            class="increase items-count" type="button">
                                                            <i class="lnr lnr-chevron-up"></i>
                                                        </button>
                                                        <button
                                                            onclick="var result = document.getElementById('sstrm{{ $row->product_variant_id }}'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;"
                                                            class="reduced items-count" type="button">
                                                            <i class="lnr lnr-chevron-down"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 id="prcrm{{ $row->product_variant_id }}">Rp
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
                                                <td colspan="3">
                                                    <button class="gray_btn" style="cursor: pointer;">Perbarui
                                                        Keranjang</button>
                                                    <a class="gray_btn" href="{{ route('front.list_cart') }}">Lihat
                                                        Keranjang</a>
                                                </td>
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
                                                    <h5 id="totalrm">Rp
                                                        {{ number_format($carts_reg->total_cost, 2, ",", ".") }}</h5>
                                                    @else
                                                    <h5 id="totalum">Rp {{ number_format($subtotal, 2, ",", ".") }}</h5>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!--================Header Menu Area =================-->

    @yield('content')

    <!--================ start footer Area  =================-->
    <footer class="footer-area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6 class="footer_title">Tentang Kami</h6>
                        <p>
                            IvoneSeafoodStore merupakan store online yang menjual berbagai pilihan bahan makanan healthy.
                            Untuk sementara, IvoneSeafoodStore melayani area Surabaya dan Sidoarjo dimana pemesanan bisa 
                            dilakukan minimal H-1.
                            <br>
                            Ke depan, selain seafood, kami juga akan menyediakan berbagai bahan makanan sehat/healthy lainnya.
                            Menyediakan makanan untuk kecerdasan dan kesehatan demi mengakselerasi terciptanya sumber daya 
                            manusia yang unggul #IndonesiaMaju adalah salah satu komitmen kami.
                        </p>
                    </div>
                </div>
                {{-- <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="single-footer-widget">
                        <h6 class="footer_title">Newsletter</h6>
                        <p>Stay updated with our latest trends</p>
                        <div id="mc_embed_signup">
                            <form target="_blank"
                                action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                                method="get" class="subscribe_form relative">
                                <div class="input-group d-flex flex-row">
                                    <input name="EMAIL" placeholder="Email Address" onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Email Address '" required="" type="email">
                                    <button class="btn sub-btn">
                                        <span class="lnr lnr-arrow-right"></span>
                                    </button>
                                </div>
                                <div class="mt-10 info"></div>
                            </form>
                            <br>
                            <h6 class="footer_title">Email Kami</h6>
                            <p>ivoneseafoodstore@gmail.com</p>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="single-footer-widget f_social_wd">
                        <h6 class="footer_title">Kontak Kami</h6>
                        <div class="row mb-4">
                            <div class="col-12">
                                <p>Media Sosial</p>
                                <div class="f_social">
                                    <a href="https://instagram.com/xxxxxxx">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                    <a href="https://wa.me/1XXXXXXXXXX">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p>Email</p>
                                <div class="f_social">
                                    <a href="mailto:ivoneseafoodstore@gmail.com">ivoneseafoodstore@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row footer-bottom d-flex justify-content-between align-items-center">
                <p class="col-lg-12 footer-text text-center">
                    Copyright &copy;<script>
                        document.write(new Date().getFullYear());

                    </script>
                </p>
            </div>
        </div>
    </footer>
    <!--================ End footer Area  =================-->

    <script src="{{ asset('ecommerce/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('ecommerce/js/popper.js') }}"></script>
    <script src="{{ asset('ecommerce/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('ecommerce/js/stellar.js') }}"></script>
    <script src="{{ asset('ecommerce/vendors/lightbox/simpleLightbox.min.js') }}"></script>
    <script src="{{ asset('ecommerce/vendors/nice-select/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('ecommerce/vendors/isotope/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('ecommerce/vendors/isotope/isotope-min.js') }}"></script>
    <script src="{{ asset('ecommerce/vendors/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('ecommerce/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('ecommerce/vendors/counter-up/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('ecommerce/vendors/flipclock/timer.js') }}"></script>
    <script src="{{ asset('ecommerce/vendors/counter-up/jquery.counterup.js') }}"></script>
    <script src="{{ asset('ecommerce/js/mail-script.js') }}"></script>
    <script src="{{ asset('ecommerce/js/select2.js') }}"></script>
    <script src="{{ asset('ecommerce/js/theme.js') }}"></script>
    <script src="{{ asset('ecommerce/js/extras.js') }}"></script>
    <script>
        $(document).ready(function() {
            var myObj = {
                style: "currency",
                currency: "IDR"
            }
            @if(auth()->guard('customer')->check())
                @foreach($carts_reg->details as $row)
                    $(".items-count").on('click', function() {
                        var newQty = $("#sstrm{{ $row->product_variant_id }}").val();
                        if(newQty == 0) {
                            if(confirm("Hapus item dari keranjang?")){
                                $("#sstrm{{ $row->product_variant_id }}").val(0)
                                $("#sstr{{ $row->product_variant_id }}").val(0)
                                $("#cachedrm{{ $row->product_variant_id }}").val(0);
                                $("#cachedr{{ $row->product_variant_id }}").val(0);
                            } else {
                                $("#sstrm{{ $row->product_variant_id }}").val(1)
                                $("#sstr{{ $row->product_variant_id }}").val(1)
                                $("#cachedrm{{ $row->product_variant_id }}").val(1);
                                $("#cachedr{{ $row->product_variant_id }}").val(1);
                            }
                        }
                        newQty = $("#sstrm{{ $row->product_variant_id }}").val();
                        
                        var itemID = $("#itemrm{{ $row->product_variant_id }}").val();

                        if (newQty != $("#cachedrm{{ $row->product_variant_id }}").val()) {
                            $.ajax({
                                type: "POST",
                                url: "/cart/update-reg",
                                data: { id: itemID, qty: newQty },
                                dataType: "JSON",
                                success: function(res) {
                                    $("#rowrm{{ $row->product_variant_id }}").fadeOut( function() {
                                        $("#prcrm{{ $row->product_variant_id }}").html(res.variant.price.toLocaleString("id-ID", myObj));
                                        $("#prcr{{ $row->product_variant_id }}").html(res.variant.price.toLocaleString("id-ID", myObj));

                                        $("#sstr{{ $row->product_variant_id }}").val(res.variant.qty);

                                        if (res.variant.is_avail === false) {
                                            $("#isavailrm{{ $row->product_variant_id }}").show();
                                            $("#isavailr{{ $row->product_variant_id }}").show();
                                        } else {
                                            $("#isavailrm{{ $row->product_variant_id }}").hide();
                                            $("#isavailr{{ $row->product_variant_id }}").hide();
                                        }
                                        
                                        $("#cachedrm{{ $row->product_variant_id }}").val(res.variant.qty);
                                        $("#cachedr{{ $row->product_variant_id }}").val(res.variant.qty);
                                        $("#totalrm").text(res.subtotal.toLocaleString("id-ID", myObj));
                                        $("#totalr").text(res.subtotal.toLocaleString("id-ID", myObj));
                                    }).fadeIn();
                                },
                                error: function(xhr, status, err) {
                                    console.log(err);
                                }
                            })
                        }
                    })
                @endforeach
            @endif

            @if(!auth()->guard('customer')->check())
                @foreach($carts as $row)
                    $('.qty, .items-count').on('change keyup click', function() {
                        var newQty = $("#sstum{{ $row['product_variant_id'] }}").val();
                        if(newQty == 0) {
                            if(confirm("Hapus item dari keranjang?")){
                                $("#sstum{{ $row['product_variant_id'] }}").val(0);
                                $("#sstu{{ $row['product_variant_id'] }}").val(0);
                                $("#cachedum{{ $row['product_variant_id'] }}").val(0);
                                $("#cachedu{{ $row['product_variant_id'] }}").val(0);
                            } else {
                                $("#sstum{{ $row['product_variant_id'] }}").val(1);
                                $("#sstu{{ $row['product_variant_id'] }}").val(1);
                                $("#cachedum{{ $row['product_variant_id'] }}").val(1);
                                $("#cachedu{{ $row['product_variant_id'] }}").val(1);
                            }
                        }
                        newQty = $("#sstum{{ $row['product_variant_id'] }}").val();
                        var itemID = $("#itemum{{ $row['product_variant_id'] }}").val();

                        if (newQty != $("#cachedum{{ $row['product_variant_id'] }}").val()) {
                            $.ajax({
                                type: "POST",
                                url: "/cart/update-unreg",
                                data: { id: itemID, qty: newQty },
                                dataType: "JSON",
                                success: function(res) {
                                    $("#rowum{{ $row['product_variant_id'] }}").fadeOut( function() {
                                        var id = itemID;
                                        var prc = Number(res.carts[id]['product_variant_price'] * res.carts[id]['qty']);
                                        $("#prcum{{ $row['product_variant_id'] }}").html(prc.toLocaleString("id-ID", myObj));
                                        $("#prcu{{ $row['product_variant_id'] }}").html(prc.toLocaleString("id-ID", myObj));

                                        $("#sstu{{ $row['product_variant_id'] }}").val(res.carts[id]['qty']);

                                        if (res.carts[id]['is_avail'] === false) {
                                            $("#isavailum{{ $row['product_variant_id'] }}").show();
                                            $("#isavailu{{ $row['product_variant_id'] }}").show();
                                        } else {
                                            $("#isavailum{{ $row['product_variant_id'] }}").hide();
                                            $("#isavailu{{ $row['product_variant_id'] }}").hide();
                                        }

                                        $("#cachedum{{ $row['product_variant_id'] }}").val(res.carts[id]['qty']);
                                        $("#cachedu{{ $row['product_variant_id'] }}").val(res.carts[id]['qty']);
                                        $("#totalum").text(res.subtotal.toLocaleString("id-ID", myObj));
                                        $("#totalu").text(res.subtotal.toLocaleString("id-ID", myObj));
                                    }).fadeIn();
                                },
                                error: function(xhr, status, err) {
                                    console.log(err);
                                }
                            })
                        }
                    })
                @endforeach
            @endif
        })
    </script>

    @yield('js')
</body>

</html>
