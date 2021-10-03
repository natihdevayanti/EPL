@extends('layouts.admin')

@section('title')
    <title>List Produk</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Produk</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">List Produk</h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <span><a href="{{ route('product.create') }}"><button class="btn btn-primary mb-3 float-left">Tambah Produk</button></a></span>
                            <form action="{{ route('product_variant.index') }}" method="get">
                                <div class="input-group mb-3 col-md-3 float-right">
                                    <input type="text" name="q" class="form-control" placeholder="Cari..." value="{{ request()->q }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button">Cari</button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Nama Varian</th>
                                            <th>Stok</th>
                                            <th>Harga</th>
                                            <th>Berat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($variant as $row)
                                        <tr>
                                            <td>{{ $row->product['name'] }}</td>
                                            <td>
                                                {{ $row->name }}
                                            </td>
                                            <td>{{ $row->stock }}</td>
                                            <td>Rp {{ number_format($row->price) }}</td>
                                            <td>{{ $row->weight }}</td>
                                            <td>
                                                <form action="{{ route('product_variant.destroy', $row->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="btn btn-success btn-sm">Detil</a>
                                                    <a href="{{ route('product_variant.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus Varian?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {!! $variant->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
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
</script>
@endsection