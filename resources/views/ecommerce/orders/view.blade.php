@extends('layouts.ecommerce')

@section('title')
<title>Order {{ $order->invoice }} - Ecommerce</title>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content text-center">
                <h2>Order {{ $order->invoice }}</h2>
                <div class="page_link">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ route('customer.orders') }}">Order {{ $order->invoice }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<!--================Login Box Area =================-->
<section class="login_box_area p_120">
    <div class="container">
        <div class="row">
            @if (auth()->guard('customer')->check())
            <div class="col-md-3">
                @include('layouts.ecommerce.module.sidebar')
            </div>
            <div class="col-md-9">
                @else
                <div class="col-md-12">
                    @endif
                    <div class="row">
                        @if (session('success')) 
                        <div class="alert alert-success">{{ session('success') }}</div>
                         @endif
                        @if (session('error')) 
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Data Pelanggan</h4>
                                </div>
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <th width="30%">InvoiceID</th>
                                            <td width="5%"></td>
                                            <td>
                                                <a href="#" id="copy_inv">
                                                    <strong><span id="invoice_number">{{ $order->invoice }}</span></strong> <span><i class="fa fa-clone"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Invoice (PDF)</th>
                                            <td width="5%"></td>
                                            @if ($order->free_access == 0)
                                            <td><a href="{{ route('customer.order_pdf', $order->invoice) }}"
                                                    target="_blank"><strong>{{ $order->invoice }}</strong></a></td>
                                            @elseif ($order->free_access == 1)
                                            <td><a href="{{ route('customer.order_pdf_unregistered', $order->invoice) }}"
                                                    target="_blank"><strong>{{ $order->invoice }}</strong></a></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <td width="5%"></td>
                                            <td>{{ $order->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>No Telp</th>
                                            <td></td>
                                            <td>{{ $order->customer_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td></td>
                                            <td>{{ $order->customer_address }}, {{ $order->district->name }}
                                                {{ $order->district->city->name }},
                                                {{ $order->district->city->province->name }}</td>
                                        </tr>
                                        @if ($order->tracking_number != NULL)
                                            <tr>
                                                <th>Nomor Resi</th>
                                                <td></td>
                                                <td>
                                                    <a href="#" id="copy_tracking">
                                                        <strong><span>{{ $order->tracking_number }}</span></strong> <span> <i class="fa fa-clone"></i></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        Pembayaran
                                        @if ($order->status == 0 && $order->free_access == 0)
                                        <a href="{{ url('member/payment/a?invoice=' . $order->invoice) }}"
                                            class="btn btn-primary btn-sm float-right ml-2" id="konfirmasi">Konfirmasi</a>
                                        <a href="{{ route('customer.order_cancel', $order->invoice) }}"
                                            class="btn btn-danger btn-sm float-right" id="konfirmasi">Batalkan</a>
                                        @elseif ($order->status == 0 && $order->free_access == 1)
                                        <a href="{{ url('member/payment/x?invoice=' . $order->invoice) }}"
                                            class="btn btn-primary btn-sm float-right ml-2" id="konfirmasi">Konfirmasi</a>
                                        <a href="{{ route('customer.order_cancel_unregistered', $order->invoice) }}"
                                            class="btn btn-danger btn-sm float-right" id="konfirmasi">Batalkan</a>
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    @if ($order->status != 0)
                                    <table>
                                        <tr>
                                            <th width="40%">Nama Pengirim</th>
                                            <td width="5%"></td>
                                            <td>{{ $order->payment->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Transfer</th>
                                            <td></td>
                                            <td>{{ $order->payment->transfer_date }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Transfer</th>
                                            <td></td>
                                            <td>Rp {{ number_format($order->payment->amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tujuan Transfer</th>
                                            <td></td>
                                            <td>{{ $order->payment->transfer_to }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bukti Transfer</th>
                                            <td></td>
                                            <td>
                                                <img src="{{ asset('storage/payment/' . $order->payment->proof) }}"
                                                    width="50px" height="50px" alt="">
                                                <a href="#" class="btn btn-primary btn-sm ml-2" data-toggle="modal"
                                                    data-target="#lihat-konfirmasi">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="modal fade bd-example-modal-lg" id="lihat-konfirmasi" role="dialog">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="d-flex justify-content-center">
                                                                <img src="{{ asset('storage/payment/' . $order->payment->proof) }}"
                                                                    style="max-width:100%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <h4 class="text-center">Belum ada data pembayaran</h4>
                                    @if($order->status != 6)
                                    <h6 class="text-center">Lakukan pembayaran dalam
                                        <span id="countdown" class="badge badge-warning"></span>
                                    </h6>
                                    <table>
                                        <tr>
                                            <th>Tujuan Transfer</th>
                                            <td></td>
                                            <td>{{ $order->payment->transfer_to }}</td>
                                        </tr>
                                    </table>
                                    @endif
                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Detail Pesanan</h4>
                                    @if ($order->status == 0)
                                    <span class="badge badge-warning">Menunggu Konfirmasi Pembayaran</span>
                                    @elseif ($order->status == 1)
                                    <span class="badge badge-warning">Menunggu Konfirmasi Admin</span>
                                    @elseif ($order->status == 2)
                                    <span class="badge badge-info">Pesanan Diproses</span>
                                    @elseif ($order->status == 3)
                                    <span class="badge badge-info">Pesanan Dikirim</span>
                                    @elseif ($order->status == 4)
                                    <span class="badge badge-success">Pesanan Selesai</span>
                                    @elseif ($order->status == 5)
                                    <span class="badge badge-danger">Pesanan Pending</span>
                                    @elseif ($order->status == 6)
                                    <span class="badge badge-secondary">Pesanan Dibatalkan</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        @if ($order->status == 3)
                                        <p>Klik tombol di bawah ini jika pesanan {{ $order->invoice }} telah kamu terima.</p>
                                            <form action="{{ route('customer.order_accept_unregistered') }}" class="form-inline"
                                            onsubmit="return confirm('Kamu Yakin?');" method="post">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <button class="btn btn-success btn-sm"
                                                    onclick="return confirm('Terima Pesanan?')">Terima Pesanan</button>
                                            </form>
                                        @elseif ($order->status == 4)
                                        <p>Pesanan {{ $order->invoice }} telah diterima pada tanggal {{ $order->updated_at }}.</p>
                                        @endif
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Quantity</th>
                                                    <th>Harga</th>
                                                    <th>Berat</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->details as $row)
                                                <tr>
                                                    <td>{{ $row->variant->name }}</td>
                                                    <td>{{ $row->qty }}</td>
                                                    <td>Rp {{ number_format($row->price) }}</td>
                                                    <td>{{ $row->weight }} gr</td>
                                                    <td>Rp {{ number_format($row->qty * $row->price) }}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td>{{ $order->shipping }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>Rp {{ number_format($order->shipping_cost) }}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <th>Rp {{ number_format($order->total_cost) }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>
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

    $("#copy_tracking").click(function() {
		var trc = document.getElementById("tracking_number");
		var txtarea = document.createElement("textarea");
		txtarea.value = trc.textContent;
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
