@extends('layouts.admin')

@section('title')
<title>Detail pesanan</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">View Order</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Detail Pesanan
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Detail Pemesanan</h4>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="30%">Nama Pemesan</th>
                                            <td>{{ $order->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Telp</th>
                                            <td>{{ $order->customer_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>{{ $order->customer_address }}, {{ $order->district->name }} -
                                                {{  $order->district->city->name}},
                                                {{ $order->district->city->province->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Status</th>
                                            <td>{!! $order->status_label !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Ubah Order Status</th>
                                            <td>
                                            @if ($order->status == 1 && $order->payment->status == 0 && Auth::user()->user_type_id != 3)
                                                <a href="{{ route('orders.approve_payment', $order->invoice) }}" class="btn btn-success btn-sm mr-2 mb-2" onclick="return confirm('Konfirmasi Pembayaran?')">Terima Pembayaran</a>
                                                <a href="{{ route('orders.make_pending', $order->invoice) }}" class="btn btn-warning btn-sm mr-2 mb-2" onclick="return confirm('Pending Pembayaran?')">Pending</a>
                                            @elseif ($order->status == 5)
                                                <a href="{{ route('orders.approve_payment', $order->invoice) }}" class="btn btn-success btn-sm mr-2 mb-2" onclick="return confirm('Proses Kembali Pesanan?')">Proses</button>
                                                <a href="{{ route('orders.cancel_payment', $order->invoice) }}" class="btn btn-danger btn-sm mr-2 mb-2" onclick="return confirm('Batalkan Pesanan?')">Batal</button>
                                            @endif
                                                <a href="{{ route('orders.cancel_payment', $order->invoice) }}" class="btn btn-danger btn-sm mr-2 mb-2" onclick="return confirm('Batalkan Pesanan?')">Batal</button>
                                                <a href="{{ route('orders.make_done', $order->invoice) }}" class="btn btn-success btn-sm mb-2" onclick="return confirm('Selesaikan Pesanan?')">Selesai</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Kurir</th>
                                            <td>{{ $order->shipping }}</td>
                                        </tr>
                                        @if ($order->status > 1 && $order->status < 5)
                                        <tr>
                                            <th>Nomor Resi</th>
                                            <td>
                                                @if ($order->status == 2 && Auth::user()->user_type_id != 2)
                                                <form action="{{ route('orders.shipping') }}" method="post">
                                                    @csrf
                                                    <div class="input-group">
                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                        <input type="text" name="tracking_number"
                                                            placeholder="Masukkan Nomor Resi" class="form-control"
                                                            required>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-secondary"
                                                                type="submit" onclick="return confirm('Kirim Nomor Resi?')">Kirim</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                @else
                                                {{ $order->tracking_number }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                @if(Auth::user()->user_type_id != 3)
                                <div class="col-md-6">
                                    <h4>Detail Pembayaran</h4>
                                    @if ($order->status != 0)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="30%">Nama Pengirim</th>
                                            <td>{{ $order->payment->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Tujuan</th>
                                            <td>{{ $order->payment->transfer_to }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Transfer</th>
                                            <td>{{ $order->payment->transfer_date }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bukti Pembayaran</th>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#lihat-konfirmasi">Lihat</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status Pembayaran</th>
                                            <td>{!! $order->payment->status_label !!}</td>
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
                                    <h5 class="text-center">Belum Konfirmasi Pembayaran</h5>
                                    @endif
                                </div>
                                @endif
                                <div class="col-md-12">
                                    <h4>Detail Produk</h4>
                                    <table class="table table-borderd table-hover">
                                        <tr>
                                            <th>Produk</th>
                                            <th>Quantity</th>
                                            <th>Harga</th>
                                            <th>Berat</th>
                                            <th>Subtotal</th>
                                        </tr>
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
                                            <td>Jasa Pengiriman (<span>{{ $order->shipping }}</span>)</td>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
