@extends('layouts.ecommerce')

@section('title')
<title>Cari Pesanan - Ecommerce</title>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content text-center">
                <h2>Cari Pesanan</h2>
                <div class="page_link">
                    <a href="{{ route('front.index') }}">Home</a>
                    <a href="{{ route('front.find_order') }}">Cari Pesanan</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<section class="section_gap main-box">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="main-title" style="text-align:center">
                <p>Masukkan Nomor Invoice pesananmu untuk melihat detil pesanan.</p>
                <p class="notice" id="notice">Hanya untuk <strong>Pesanan Tanpa Akun</strong></p>
            </div>
        </div>
        @if (session('error'))
        <div class="row justify-content-center">
            <div class="badge badge-danger">{{ session('error') }}</div>
        </div>
        @endif
        <div class="row justify-content-center my-4" style="text-align:center">
            <form class="form-horizontal" action="{{ route('front.find_order.show') }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="invoice" placeholder="Nomor Invoice">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">
                            Cari
                        </button>
                    </div>
                </div>
                <small>Nomor Invoice bisa kamu temukan di e-mail konfirmasi pemesanan.</small><br>
                <small id="notice-hover">Jika pesananmu dibuat dengan akun, silakan <strong>LOG IN</strong> terlebih dahulu lalu akses <strong>AKUN SAYA</strong></small>
            </form>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        document.getElementById("notice").onmouseover = function () {
            document.getElementById("notice-hover").style.borderBottom = "thick solid #f6bd60";
        };
        document.getElementById("notice").onmouseout = function () {
            document.getElementById("notice-hover").style.borderBottom = "none";
        };
    })
</script>
@endsection