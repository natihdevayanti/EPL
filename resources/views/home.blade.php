@extends('layouts.admin')

@section('title')
    <title>Dashboard</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Hari Ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="callout callout-info">
                                        <small class="text-muted">Produk Terjual</small>
                                        <br>
                                        <strong class="h4">{{ $daily->sold ?? ''}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="callout callout-success">
                                        <small class="text-muted">Pemasukan</small>
                                        <br>
                                        <strong class="h4">{{ $daily->income ?? ''}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="callout callout-primary">
                                        <small class="text-muted">Transaksi Berhasil</small>
                                        <br>
                                        <strong class="h4">{{ $daily->success ?? ''}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="callout callout-danger">
                                        <small class="text-muted">Transaksi Gagal</small>
                                        <br>
                                        <strong class="h4">{{ $daily->failed ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="callout callout-info">
                                        <small class="text-muted">Produk Aktif</small>
                                        <br>
                                        <strong class="h4">{{ $daily->active_product ?? ''}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="callout callout-warning">
                                        <small class="text-muted">Pesanan Perlu Diproses</small>
                                        <br>
                                        <strong class="h4">{{ $daily->on_process ?? ''}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="callout callout-warning">
                                        <small class="text-muted">Varian Perlu Restock</small>
                                        <br>
                                        <strong class="h4">{{ $restock_qty ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Bulan Ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="callout callout-info">
                                        <small class="text-muted">Produk Terjual</small>
                                        <br>
                                        <strong class="h4">{{ $monthly->sold ?? ''}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="callout callout-danger">
                                        <small class="text-muted">Pemasukan</small>
                                        <br>
                                        <strong class="h4">{{ $monthly->income ?? ''}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="callout callout-primary">
                                        <small class="text-muted">Transaksi Berhasil</small>
                                        <br>
                                        <strong class="h4">{{ $monthly->success ?? ''}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="callout callout-success">
                                        <small class="text-muted">Transaksi Gagal</small>
                                        <br>
                                        <strong class="h4">{{ $monthly->failed ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Varian Perlu Restock</h4>
                        </div>
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Nama Varian</th>
                                            <th>Stok</th>
                                            <th>Stok Minimum</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($restock as $row)
                                        <tr>
                                            <td><a href="{{ route('product.show', $row->product->id) }}">{{ $row->product->name }}</a></td>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->stock }}</td>
                                            <td>{{ $row->minimum_stock }}</td>
                                            <td>
                                                <a href="{{ route('product_variant.edit', $row->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada varian yang perlu restock.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
