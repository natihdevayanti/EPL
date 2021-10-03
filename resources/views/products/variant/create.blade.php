@extends('layouts.admin')

@section('title')
    <title>Tambah Varian</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/administrator/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/administrator/product">Produk</a></li>
        <li class="breadcrumb-item active">Tambah Varian</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-4">
                    <div>
                        <a href="/administrator/product">
                            <button class="btn btn-default mb-3"><i class="fa fa-chevron-circle-left"></i> Kembali</button>
                        </a>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Varian Baru</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('product_variant.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="product_id">Produk</label>
                                    <select name="product_id" class="form-control">
                                        <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                                    </select>
                                    <p class="text-danger">{{ $errors->first('product_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Varian</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="price">Harga</label>
                                    <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                                    <p class="text-danger">{{ $errors->first('price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="weight">Berat</label>
                                    <input type="number" name="weight" class="form-control" value="{{ old('weight') }}" required>
                                    <p class="text-danger">{{ $errors->first('weight') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Stok Minimum</label>
                                    <input type="number" name="minimum_stock" class="form-control" value="{{ old('minimum_stock') }}" required>
                                    <p class="text-danger">{{ $errors->first('minimum_stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Stok</label>
                                    <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
                                    <p class="text-danger">{{ $errors->first('stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="image">Foto Varian</label>
                                    <input type="file" name="image" class="form-control" value="{{ old('image') }}" required>
                                    <p class="text-danger">{{ $errors->first('image') }}</p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm">Tambah</button>
                                </div>
                            </form>
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
</script>
@endsection

