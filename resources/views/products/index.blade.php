@extends('layouts.admin')

@section('title')
<title>List Produk</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/administrator/home">Home</a></li>
        <li class="breadcrumb-item active"><a href="/administrator/product">Produk</a></li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                List Produk
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <span><a href="{{ route('product.create') }}"><button
                                        class="btn btn-primary mb-3 float-left">Tambah Produk</button></a></span>
                            <form action="{{ route('product.index') }}" method="get">
                                <div class="input-group mb-3 col-md-3 float-right">
                                    <input type="text" name="q" class="form-control" placeholder="Cari..."
                                        value="{{ request()->q }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button">Cari</button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Nama Varian</th>
                                            <th>Stok</th>
                                            <th>Harga</th>
                                            <th>Berat</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product as $row)
                                        @foreach ($row->variant as $subrow)
                                        <tr>
                                            <td>
                                                {{ $row->name }} <br>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item"
                                                            href="{{ route('product.show', $row->id) }}">Detil
                                                            Produk</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('product.edit', $row->id) }}">Edit Produk</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('product.destroy', $row->id) }}"
                                                            onclick="return confirm('Hapus Produk?')">Hapus Produk</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('product_variant.create_variant', $row->id) }}">Tambah
                                                            Varian</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $subrow->name }}</td>
                                            <td>{{ $subrow->stock }}</td>
                                            <td>{{ $subrow->price }}</td>
                                            <td>{{ $subrow->weight }}</td>
                                            <td>{!! $row->status_label !!}</td>
                                            <td>
                                                <form action="{{ route('product_variant.destroy', $subrow->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" data-toggle="modal" data-target="#show-variant" 
                                                        onclick="showVariant({{$subrow->id}})" class="btn btn-success btn-sm">Detil</a>
                                                    <a href="{{ route('product_variant.edit', $subrow->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Hapus Varian?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach                                        
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {!! $product->links() !!}
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
                                        <td id="variant_name"></td>
                                    </tr>
                                    <tr>
                                        <th>Harga</th>
                                        <td>Rp <span id="variant_price"></span></td>
                                    </tr>
                                    <tr>
                                        <th>Berat</th>
                                        <td id="variant_weight"></td>
                                    </tr>
                                    <tr>
                                        <th>Stok</th>
                                        <td id="variant_stock"></td>
                                    </tr>
                                    <tr>
                                        <th>Stok Minimum</th>
                                        <td id="variant_minimum"></td>
                                    </tr>
                                    <tr>
                                        <th>Gambar</th>
                                        <td>
                                            <img id="variant_image" src="" height="200px">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Pembaharuan Terakhir</th>
                                        <td id="variant_updated"></td>
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
    $('select').select2({
        theme: 'bootstrap4'
    });

    const table = document.querySelector('table');

    let headerCell = null;

    for (let row of table.rows) {
        const firstCell = row.cells[0];

        if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
            headerCell = firstCell;
        } else {
            headerCell.rowSpan++;
            firstCell.remove();
        }
    }

    for (let row of table.rows) {
        const statusCell = row.cells[row.cells.length-2];

        if (headerCell === null || statusCell.innerText !== headerCell.innerText) {
            headerCell = statusCell;
        } else {
            headerCell.rowSpan++;
            statusCell.remove();
        }
    }

    function showVariant(id) {
        $.ajax({
            url: 'product_variant/get/' + id,
            type: 'GET',
            data: { id: id },
            dataType: 'JSON',
            success: function(res) {
                var date = new Date(res.updated_at);
                var filename = res.image;
                var src = "/storage/variants/" + filename;

                $("#variant_name").text(res.name);
                $("#variant_price").html(res.price);
                $("#variant_weight").text(res.weight);
                $("#variant_stock").text(res.stock);
                $("#variant_minimum").text(res.minimum_stock);
                $("#variant_image").attr('alt', res.name);
                $("#variant_image").attr('src', src);
                $("#variant_updated").text(date.toString());
            }
        });
    }
</script>
@endsection
