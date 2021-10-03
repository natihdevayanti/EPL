@extends('layouts.ecommerce')

@section('title')
<title>Checkout - Ecommerce</title>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="banner_content text-center">
                <h2>Informasi Pengiriman</h2>
                <div class="page_link">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="#">Checkout</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<!--================Checkout Area =================-->
<section class="checkout_area section_gap">
    <div class="container">
        <div class="billing_details">
            <div class="row">
                <div class="col-lg-8">
                    <h3>Informasi Pengiriman</h3>

                    @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (auth()->guard('customer')->check())
                    <div id="same-address-section">
                        <div class="row pb-4">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <strong>{{ auth()->guard('customer')->user()->name }}</strong>
                                        <p class="card-text">{{ auth()->guard('customer')->user()->phone_number }}</p>
                                        <p class="card-text">{{ auth()->guard('customer')->user()->address }},
                                            {{ auth()->guard('customer')->user()->district->name }}<br>
                                            {{ auth()->guard('customer')->user()->district->city->name }},
                                            {{ auth()->guard('customer')->user()->district->city->province->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class="custom-control custom-checkbox">
                                <input id="same-address-check" class="custom-control-input" name="same-address-check"
                                    type="checkbox" unchecked>
                                <label class="custom-control-label" for="same-address-check">Alamat pengiriman
                                    berbeda</label>
                            </div>
                        </div>
                    </div>
                    @endif
                    <form id="checkout-form" class="row contact_form" action="{{ route('front.store_checkout') }}"
                        method="post" novalidate="novalidate">
                        @csrf
                        <div id="diff-address-section">
                            <div class="col-md-12 form-group p_star">
                                <label for="">Nama Lengkap</label>
                                <input type="text" class="form-control" id="first" name="customer_name" required>
                                <p class="text-danger">{{ $errors->first('customer_name') }}</p>
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <label for="">No Telp</label>
                                <input type="text" class="form-control" id="number" name="customer_phone" required>
                                <p class="text-danger">{{ $errors->first('customer_phone') }}</p>
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <label for="">Email</label>
                                @if (auth()->guard('customer')->check())
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ auth()->guard('customer')->user()->email }}" required
                                    {{ auth()->guard('customer')->check() ? 'readonly':'' }}>
                                @else
                                <input type="email" class="form-control" id="email" name="email" required>
                                @endif
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                                @if (!auth()->guard('customer')->check())
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="make-account" name="make_account" checked>
                                    <input type="hidden" id="make-account-value" name="make_account_value" value="true">
                                    <label class="custom-control-label" for="make-account">Buat akun baru dengan e-mail ini</label>
                                    <p><small id="make-account-info">Cek kotak masuk e-mail Anda untuk informasi akun</small></p>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <label for="">Alamat Lengkap</label>
                                <input type="text" class="form-control" id="add1" name="customer_address" required>
                                <p class="text-danger">{{ $errors->first('customer_address') }}</p>
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <label for="">Provinsi</label>
                                <select class="form-control" name="province_id" id="province_id" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-danger">{{ $errors->first('province_id') }}</p>
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <label for="">Kabupaten / Kota</label>
                                <select class="form-control" name="city_id" id="city_id" required>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                                <p class="text-danger">{{ $errors->first('city_id') }}</p>
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <label for="">Kecamatan</label>
                                <select class="form-control" name="district_id" id="district_id" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <p class="text-danger">{{ $errors->first('district_id') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group p_star">
                            <label for="">Kurir</label>
                            <input type="hidden" name="weight" id="weight" value="{{ $weight }}">
                            <select class="form-control" name="courier" id="courier" required>
                                <option value="">Pilih Kurir</option>
                            </select>
                            <p class="text-danger">{{ $errors->first('courier') }}</p>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="order_box">
                        <h2>Ringkasan Pesanan</h2>
                        <ul class="list">
                            <li>
                                <a href="#">Produk
                                    <span>Total</span>
                                </a>
                            </li>
                            @if(!auth()->guard('customer')->check())
                            @foreach ($carts as $cart)
                            <li>
                                <a href="#">
                                    <div>
                                        {{ \Str::limit($cart['product_name'], 10) }}
                                    </div>
                                    <div>
                                        {{ $cart['product_variant_name'] }}
                                        <span class="middle">x {{ $cart['qty'] }}</span>
                                        <span class="last">Rp
                                            {{number_format($cart['product_variant_price'] * $cart['qty'])}}</span>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                            @else
                            @foreach ($carts_reg->details as $cart)
                            <li>
                                <a href="#">
                                    <div>
                                        {{ \Str::limit($cart->variant->product->name, 10) }}
                                    </div>
                                    <div>
                                        {{ $cart->variant->name }}
                                        <span class="middle">x {{ $cart->qty }}</span>
                                        <span class="last">Rp
                                            {{number_format($cart->price )}}</span>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                        <ul class="list list_2">
                            <li>
                                <a href="#">Subtotal
                                @if(!auth()->guard('customer')->check())
                                    <span>Rp {{ number_format($subtotal) }}</span>
                                @else
                                    <span>Rp {{ number_format($carts_reg->total_cost) }}</span>
                                @endif
                                </a>
                            </li>
                            <li>
                                <a href="#">Pengiriman
                                    <span id="ongkir">Rp 0</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">Total
                                    <span id="total">
                                        @if(!auth()->guard('customer')->check())
                                            Rp {{ number_format($subtotal) }}
                                        @else
                                            Rp {{ number_format($carts_reg->total_cost) }}
                                        @endif
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="button" class="main_btn" id="checkout-go">Bayar Pesanan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Checkout Area =================-->
@endsection

@section('js')
<script>
    $('select').select2({
        theme: "bootstrap4"
    });

    $('#make-account').on('change', function () {
        var a = document.getElementById("make-account").checked;
        if(a){
            $('#make-account-info').html("Cek kotak masuk e-mail Anda untuk informasi akun");
            $('#make-account-value').val("true");
        } else {
            $('#make-account-info').html("Cek kotak masuk e-mail Anda untuk informasi pesanan");
            $('#make-account-value').val("false");
        }
    });
    
    $('#checkout-go').click(function (e) {
        e.preventDefault();
        $('#checkout-form').submit();
    });

    @if(auth()->guard('customer')->check())
        var a = document.getElementById("same-address-check").checked;
        checkChecked(a);
    @endif

    $('#same-address-check').on('change', function () {
        var check = document.getElementById("same-address-check").checked;
        console.log(check);
        checkChecked(check);
    });

    function checkChecked(c) {
        if (c) diffAddress();
        else sameAddress();
    }

    function sameAddress() {
        $('#same-address-section').fadeIn();
        $('#diff-address-section').fadeOut();

        $('#first').val("{{ auth()->guard('customer')->user()->name ?? ''}}").change();
        $('#number').val("{{ auth()->guard('customer')->user()->phone_number ?? ''}}").change();
        $('#add1').val("{{ auth()->guard('customer')->user()->address ?? ''}}").change();

        var p = changeProvince();
        changeCity(p);
        changeDistrict();
    }

    function diffAddress() {
        $('#same-address-section').fadeOut();
        $('#diff-address-section').fadeIn();
    }

    function changeProvince() {
        $('#province_id').val("{{ auth()->guard('customer')->user()->district->city->province->id ?? ''}}");
        $('#province_id').change();
        console.log($('#province_id').val());
        return $('#province_id').val();
    }

    function changeCity(province_id) {
        $.ajax({
            url: "{{ url('/api/city') }}",
            type: "GET",
            data: {
                province_id: province_id
            },
            success: function (html) {

                $('#city_id').empty()
                $('#city_id').append('<option value="">Pilih Kabupaten/Kota</option>')
                $.each(html.data, function (key, item) {
                    if (item.id == "{{ auth()->guard('customer')->user()->district->city->id ?? ''}}")
                        $('#city_id').append('<option selected="selected" value="' + item.id + '">' + item.type + item.name +
                            '</option>')
                    else
                        $('#city_id').append('<option value="' + item.id + '">' + item.type + ' ' + item.name +
                        '</option>')
                })
                
            }
        });
        console.log($('#city_id').val());
    }

    function changeDistrict() {
        $.ajax({
            url: "{{ url('/api/district') }}",
            type: "GET",
            data: {
                city_id: "{{ auth()->guard('customer')->user()->district->city->id ?? '' }}"
            },
            success: function (html) {
                $('#district_id').empty();
                $('#district_id').append('<option value="">Pilih Kecamatan</option>');
                $.each(html.data, function (key, item) {
                    if (item.id == "{{ auth()->guard('customer')->user()->district->id ?? '' }}") {
                        $('#district_id').append('<option selected="selected" value="' + item.id + '">' + item
                            .name + '</option>')
                        
                    } else {
                        $('#district_id').append('<option value="' + item.id + '">' + item
                            .name + '</option>')
                    }
                });
                $('#district_id').change();
            }
        });
        
    }

    $('#province_id').on('change', function () {
        $.ajax({
            url: "{{ url('/api/city') }}",
            type: "GET",
            data: {
                province_id: $(this).val()
            },
            success: function (html) {

                $('#city_id').empty()
                $('#city_id').append('<option value="">Pilih Kabupaten/Kota</option>')
                $.each(html.data, function (key, item) {
                    if(item.name == 'Surabaya' || item.name == 'Sidoarjo')
                    $('#city_id').append('<option value="' + item.id + '">' + item.type + ' ' + item.name +
                        '</option>')
                })
            }
        });
    })

    $('#city_id').on('change', function () {
        $.ajax({
            url: "{{ url('/api/district') }}",
            type: "GET",
            data: {
                city_id: $(this).val()
            },
            success: function (html) {
                $('#district_id').empty()
                $('#district_id').append('<option value="">Pilih Kecamatan</option>')
                $.each(html.data, function (key, item) {
                    $('#district_id').append('<option value="' + item.id + '">' + item
                        .name + '</option>')
                })
            }
        });
    })

    $('#district_id').on('change', function () {
        $('#courier').empty()
        $('#courier').append('<option value="">Loading...</option>')
        $.ajax({
            url: "{{ url('/api/cost') }}",
            type: "POST",
            data: {
                destination: $(this).val(),
                weight: $('#weight').val()
            },
            success: function (html) {
                console.log(html);
                $('#courier').empty()
                $('#courier').append('<option value="">Pilih Kurir</option>')
                $.each(html.data.results, function (key, item) {
                    let courier = item.courier + ' - ' + item.service + ' (Rp ' + item
                        .cost + ')'
                    let value = item.courier + '-' + item.service + '-' + item.cost
                    $('#courier').append('<option value="' + value + '">' + courier +
                        '</option>')
                })
                $('#courier').append('<option value="' + 'Gojek-GOSEND-' + 0 + '">' + 'GO-SEND - (Rp ' + 0 + ') </option>')
                $('#courier').append('<option value="' + 'Grab-GrabExpress-' + 0 + '">' + 'Grab Express - (Rp ' + 0 + ') </option>')
            }
        });
    })

    $('#courier').on('change', function () {
        let split = $(this).val().split('-');
        $('#ongkir').text('Rp ' + split[2]);

        @if( auth()->guard('customer')->check() )
            var subtotal = "{{ $carts_reg->total_cost }}";
        @else
            var subtotal = "{{ $subtotal }}";
        @endif
        let total = parseInt(subtotal) + parseInt(split['2'])
        $('#total').text('Rp' + total)
    })

</script>
@endsection
