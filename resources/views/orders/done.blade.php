@extends('layouts.admin')

@section('title')
    <title>Daftar Pesanan Selesai</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Orders</li>
        <li class="breadcrumb-item active">Done</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Daftar Pesanan Selesai
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('orders.orders_done') }}" method="get">
                                <div class="input-group mb-3 col-md-6 float-right">
                                    <select name="status" class="form-control mr-3">
                                        <option value="">Pilih Status</option>
                                        <option value="4">Selesai</option>
                                        <option value="6">Dibatalkan</option>
                                    </select>
                                    <input type="text" name="q" class="form-control" placeholder="Cari..." value="{{ request()->q }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit">Cari</button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>InvoiceID</th>
                                            <th>Pelanggan</th>
                                            <th>Total Harga Pesanan</th>
                                            <th>Harga Pengiriman</td>
                                            <th>Subtotal</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $row)
                                        <tr>
                                            <td><strong>{{ $row->invoice }}</strong></td>
                                            <td>
                                                <strong>{{ $row->customer_name }}</strong><br>
                                                {{-- CEK RELASI --}}
                                                {{-- <label><strong>Telp:</strong> {{ $row->customer_phone }}</label><br>
                                                <label><strong>Alamat:</strong> {{ $row->customer_address }} {{ $row->district->name }} - {{  $row->district->city->name}}, {{ $row->district->city->province->name }}</label> --}}
                                            </td>
                                            <td>Rp {{ number_format($row->subtotal) }}</td>
                                            <td>Rp {{ number_format($row->shipping_cost) }}</td>
                                            <td>Rp {{ number_format($row->total_cost) }}</td>
                                            <td>{{ $row->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                {!! $row->status_label !!} <br>
                                                @if ($row->return_count > 0)
                                                    <a href="{{ route('orders.return', $row->invoice) }}">Permintaan Return</a>
                                                @endif
                                            </td>
                                            <td>
                                                    <a href="{{ route('orders.view', $row->invoice) }}" class="btn btn-warning btn-sm mb-2 mr-1">Lihat</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {!! $orders->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
