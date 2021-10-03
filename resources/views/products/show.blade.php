@extends('layouts.admin')
@section('title')
<title>Detil Produk</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/administrator/home">Home</a></li>
        <li class="breadcrumb-item active"><a href="/administrator/product">Produk</a></li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
                <div>
                    <a href="/administrator/product">
                        <button class="btn btn-default mb-3"><i class="fa fa-chevron-circle-left"></i> Kembali</button>
                    </a>
                </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Detil Produk
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>Nama Produk</th>
                                                    <td>{{ $product->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kategori</th>
                                                    <td>{{ $product->category->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Deskripsi</th>
                                                    <td>{{ $product->description }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        {!! $product->status_label !!}
                                                        @if( $product->is_featured == 1 )
                                                        <span class="badge badge-success">Produk Fitur</span>
                                                        @else
                                                        <span class="badge badge-secondary">Bukan Produk Fitur</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Pembaharuan Terakhir</th>
                                                    <td>{{ $product->updated_at }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Varian</th>
                                                    <th>Aksi</th>
                                                </tr>
                                                @foreach ($product->variant as $row)
                                                <tr>
                                                    <td>
                                                        <img id="var_image" src="{{ asset('storage/variants/' . $row->image) }}"
                                                            alt="{{ $row->name }}" height="100px">
                                                    </td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>
                                                        <form action="{{ route('product_variant.destroy', $row->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" data-toggle="modal" data-target="#show-variant"
                                                                class="btn btn-success btn-sm" onclick="showVariant({{$row->id}})">Detil</a>
                                                            <a href="{{ route('product_variant.edit', $row->id) }}"
                                                                class="btn btn-warning btn-sm">Edit</a>
                                                            <button class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Hapus Produk?')">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="modal fade" tabindex="-1" id="show-variant" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detil Varian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Varian</th>
                                        <td id="variant-name"></td>
                                    </tr>
                                    <tr>
                                        <th>Harga</th>
                                        <td>Rp <span id="variant-price"></span></td>
                                    </tr>
                                    <tr>
                                        <th>Berat</th>
                                        <td id="variant-weight"></td>
                                    </tr>
                                    <tr>
                                        <th>Stok</th>
                                        <td id="variant-stock"></td>
                                    </tr>
                                    <tr>
                                        <th>Stok Minimum</th>
                                        <td id="variant-minimum"></td>
                                    </tr>
                                    <tr>
                                        <th>Gambar</th>
                                        <td>
                                            <img id="variant-image" src="" height="200px">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Pembaharuan Terakhir</th>
                                        <td id="variant-updated"></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function showVariant(id) {
        $.ajax({
            url: '/administrator/product_variant/get/' + id,
            type: 'GET',
            data: { id: id },
            dataType: 'JSON',
            success: function(res) {
                var date = new Date(res.updated_at);
                var filename = res.image;
                var src = "/storage/variants/" + filename;

                $("#variant-name").text(res.name);
                $("#variant-price").html(res.price);
                $("#variant-weight").text(res.weight);
                $("#variant-stock").text(res.stock);
                $("#variant-minimum").text(res.minimum_stock);
                $("#variant-image").attr('alt', res.name);
                $("#variant-image").attr('src', src);
                $("#variant-updated").text(date.toString());
            }
        });
    }
</script>
@endsection