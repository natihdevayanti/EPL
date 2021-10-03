@extends('layouts.admin')

@section('title')
    <title>Edit Varian Produk</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Varian Produk</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <form action="{{ route('product_variant.update', $variant->id) }}" method="post" enctype="multipart/form-data" >
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Varian Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="product_id">Produk</label>
                                    <select name="product_id" class="form-control">
                                        <option value="">Pilih</option>
                                        @foreach ($product as $row)
                                        <option value="{{ $row->id }}" {{ $variant->product_id == $row->id ? 'selected':'' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('product_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Varian</label>
                                    <input type="text" name="name" class="form-control" value="{{ $variant->name }}" required>
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="price">Harga</label>
                                    <input type="number" name="price" class="form-control" value="{{ $variant->price }}" required>
                                    <p class="text-danger">{{ $errors->first('price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="weight">Berat</label>
                                    <input type="number" name="weight" class="form-control" value="{{ $variant->weight }}" required>
                                    <p class="text-danger">{{ $errors->first('weight') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="minimum_stock">Stok Minimum</label>
                                    <input type="number" name="minimum_stock" class="form-control" value="{{ $variant->minimum_stock }}" required>
                                    <p class="text-danger">{{ $errors->first('minimum_stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Stok</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $variant->stock }}" required>
                                    <p class="text-danger">{{ $errors->first('stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="image">Foto Produk</label>
                                    <br>
                                    <img src="{{ asset('storage/variants/' . $variant->image) }}" width="100px" height="100px" alt="{{ $variant->name }}">
                                    <hr>
                                    <input type="file" name="image" class="form-control">
                                    <p><strong>Biarkan kosong jika tidak ingin mengganti gambar</strong></p>
                                    <p class="text-danger">{{ $errors->first('image') }}</p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm" id="submit_update" onclick="return confirm('Ubah Data?')">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
