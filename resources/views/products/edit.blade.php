@extends('layouts.admin')

@section('title')
    <title>Edit Produk</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/administrator/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/administrator/product">Produk</a></li>
        <li class="breadcrumb-item">Edit Produk</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data" >
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="category_id">Kategori</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">Pilih</option>
                                        @foreach ($category as $row)
                                        <option value="{{ $row->id }}" {{ $product->category_id == $row->id ? 'selected':'' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Produk</label>
                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="image">Foto Produk</label>
                                    <br>
                                    <img src="{{ asset('storage/products/' . $product->image) }}" width="100px" height="100px" alt="{{ $product->name }}">
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
                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Ubah Status</h4>
                            </div>
                            <div class="card-body">
                                <h6 class="card-text">
                                    Status Varian
                                    {!! $product->status_label !!}
                                </h6>
                                <div class="form-group mt-4">
                                    <button class="btn btn-primary mr-2" id="make_draft" onclick="changeVariantStatus(0)">Draft</button=>
                                    <button class="btn btn-success" id="make_active" onclick="changeVariantStatus(1)">Aktif</button>
                                    <input type="hidden" id="status" name="status" value="{{ $product->status }}">
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

    $(document).ready(function() {
        if( {{ $product->status }} == 1)
            $("#make_active").attr("disabled", true);
        else
            $("#make_draft").attr("disabled", true);
    });

    function changeVariantStatus(status) {
        document.getElementById("status").value = status;
        $("#submit_update").click();
    };
</script>
@endsection
