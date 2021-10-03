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
					<h2>Pesanan Diterima</h2>
					<div class="page_link">
                        <a href="{{ url('/') }}">Home</a>
						<a href="">Invoice</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Home Banner Area =================-->

	<!--================Order Details Area =================-->
	<section class="order_details p_120">
		<div class="container">
			<div class="row justify-content-center">
				<h3 class="title_confirmation">Terima kasih, pesanan Anda telah kami terima.</h3>
			</div>
			
			@if ($order->free_access == 1)
				<h5 class="info_confirmation">Silakan periksa e-mail Anda untuk informasi detail pesanan.</h5>
			@elseif ($order->free_access == 0 && !auth()->guard('customer')->check())
				<h5 class="info_confirmation">Silakan <span class="emphasis"><strong>Log In</strong></span> dan akses menu <span class="emphasis"><strong>Akun Saya</strong></span> untuk melihat detail pesanan. <br/> Periksa <span class="emphasis"><strong>e-mail</span></strong> Anda untuk informasi akun.</h5>
			@endif
			<h6 class="text-center">Lakukan pembayaran dalam
			    <span id="countdown" class="badge badge-warning"></span>
			</h6>

			@if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
			
			<form action="{{ route('customer.set_payment_destination') }}" method="POST">
				@csrf
				<div class="row justify-content-center">
					<input type="hidden" name="order_id" value="{{ $order->id }}">
					<div class="form-group">
                        <label for="">Transfer Ke</label>
                        <select name="transfer_to" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="BCA - 1234567">BCA: 1234567 a.n Ersad Ishlahuddin</option>
                            <option value="Mandiri - 2345678">Mandiri: 2345678 a.n Ersad Ishlahuddin</option>
                            <option value="BRI - 9876543">BRI: 9876543 a.n Ersad Ishlahuddin</option>
                            <option value="BNI - 6789456">BNI: 6789456 a.n Ersad Ishlahuddin</option>
                        </select>
                        <p class="text-danger">{{ $errors->first('transfer_to') }}</p>
                    </div>
				</div>
				<div class="row justify-content-center mb-5">"
					<button class="blue_btn">
						Bayar Pesanan
					</button>
				</div>
			</form>
				{{-- <a href="{{ route('customer.view_order_unregistered', $order->invoice) }}">
					<button class="blue_btn">
						Bayar Pesanan
					</button>
				</a> --}}
			<div class="row order_d_inner">
				<div class="col-lg-6">
					<div class="details_item">
						<h4>Informasi Pesanan</h4>
						<ul class="list">
							<li>
								<a href="#">
                                    <span>Invoice</span> :</a> <a href="#" id="copy_inv"><strong><span id="invoice_number">{{ $order->invoice }}</span></strong> <span><i class="fa fa-clone"></i></span></=>
							</li>
							<li>
								<a href="#">
                                    <span>Tanggal</span> : {{ $order->created_at }}</a></a>
							</li>
							<li>
								<a href="#">
									<span>Subtotal</span> : Rp {{ number_format($order->subtotal) }}
								</a>
							</li>
							<li>
								<a href="#">
									<span>Ongkos Kirim</span> : Rp {{ number_format($order->shipping_cost) }}
								</a>
							</li>
							<li>
								<a href="#">
									<span>Total</span> : Rp {{ number_format($order->total_cost) }}
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="details_item">
						<h4>Informasi Pemesan</h4>
						<ul class="list">
							<li>
								<a href="#">
                                    <span>Nama Pemesan</span> : {{ $order->customer_name }}</a>
							</li>
							<li>
								<a href="#">
                                    <span>Alamat</span> : {{ $order->customer_address }}</a>
							</li>
							<li>
								<a href="#">
                                    <span>Kota</span> : {{ $order->district->city->name }}</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
    <!--================End Order Details Area =================-->
    
@endsection

@section('js')
<script>
    var countdownDate = new Date("{{ $order->invalid_at }}").getTime();

    var x = setInterval(function () {
        var now = new Date().getTime();
        var dist = countdownDate - now;

        var hours = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((dist % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML = hours + " jam " + minutes + " menit " + seconds +
            " detik ";

        if (dist < 0) {
            clearInterval(x);
            document.getElementById("countdown").className = "badge badge-danger"
            document.getElementById("countdown").innerHTML = "Batas pembayaran habis";
            document.getElementById("konfirmasi").disabled = true;
        }
    }, 1000)

	$("#copy_inv").click(function() {
		var inv = document.getElementById("invoice_number");
		var txtarea = document.createElement("textarea");
		txtarea.value = inv.textContent;
		document.body.appendChild(txtarea);

		txtarea.select();
		
        try {
            document.execCommand("copy");
            alert("Invoice Number " + txtarea.value + " Copied!");
        } catch(error) {
            console.log("Copying failed" + error);
        }

		txtarea.remove();
    })
</script>
@endsection