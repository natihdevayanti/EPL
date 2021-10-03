@extends('layouts.admin')

@section('title')
<title>Tambah Produk</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/administrator/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/administrator/product">Produk</a></li>
        <li class="breadcrumb-item active">Tambah Produk</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="col-md-4">
                <div>
                    <a href="/administrator/product">
                        <button class="btn btn-default mb-3"><i class="fa fa-chevron-circle-left"></i> Kembali</button>
                    </a>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Produk Baru</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="category_id">Kategori</label>
                                <select name="category_id" class="form-control">
                                    <option value="">Pilih</option>
                                    @foreach ($category as $row)
                                    <option value="{{ $row->id }}" {{ old('category_id') == $row->id ? 'selected':'' }}>
                                        {{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-danger">{{ $errors->first('category_id') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Produk</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" id="description"
                                    class="form-control">{{ old('description') }}</textarea>
                                <p class="text-danger">{{ $errors->first('description') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Varian</label>
                                <input type="text" name="name_var" class="form-control" value="{{ old('name_var') }}"
                                    required>
                                <p class="text-danger">{{ $errors->first('name_var') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}"
                                    required>
                                <p class="text-danger">{{ $errors->first('price') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="weight">Berat (gr)</label>
                                <input type="number" name="weight" class="form-control" value="{{ old('weight') }}"
                                    required>
                                <p class="text-danger">{{ $errors->first('weight') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stok Minimum</label>
                                <input type="number" name="minimum_stock" class="form-control" value="{{ old('minimum_stock') }}" required>
                                <p class="text-danger">{{ $errors->first('minimum_stock') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stok</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}"
                                    required>
                                <p class="text-danger">{{ $errors->first('stock') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="image">Foto Produk</label>
                                <input type="file" id="image" name="image" class="form-control"
                                    value="{{ old('image') }}" required>
                                <p class="text-danger">{{ $errors->first('image') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="0">Draft</option>
                                    <option value="1">Aktif</option>
                                </select>
                                <p class="text-danger">{{ $errors->first('status') }}</p>
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
</main>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
<script>
    $('select').select2({
        theme: 'bootstrap4'
    });

    const compress = new Compress()
    const upload = document.getElementById('image')

    upload.addEventListener('change', (e) => {
        const files = [...e.target.files]
        compress.compress(files, {
            quality: 0.5
        }).then((images) => {
            console.log(images)
        })
    })

</script>
@endsection
