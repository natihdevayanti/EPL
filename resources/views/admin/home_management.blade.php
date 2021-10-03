@extends('layouts.admin')

@section('title')
<title>Manajemen Beranda</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Manajemen Beranda</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Fitur Slider
                                <a href="{{ route('admin.home_management.slider.view_add') }}">
                                    <button class="btn btn-primary btn-sm float-right">Tambah Konten Slider</button>
                                </a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Judul</th>
                                            <th>Subjudul</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($slider_contents as $row)
                                        <tr>
                                            <td><img src="{{ asset('storage/slider/' . $row->image) }}" height="100px"
                                                    alt="{{ $row->title }}"></td>
                                            <td>{{ $row->title }} </td>
                                            <td>{{ $row->subtitle }} </td>
                                            <td>
                                                <form
                                                    action="{{ route('admin.home_management.slider.delete', $row->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="{{ route('admin.home_management.slider.view_edit', $row->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal"
                                                        data-target="#lihat-slider"
                                                        onclick="showSlider({{ $row->id }})">Detail</a>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Hapus Konten Slider?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade bd-example-modal-lg" id="lihat-slider" role="dialog">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                                <img id="slider-image" class="w-100" src="">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h1 id="slider-title"></h1>
                                                    <h5 id="slider-subtitle"><h5>
                                                    <p id="slider-text"><p>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Produk Unggulan
                                <a href="#" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                                    data-target="#add-featured">Tambah Produk Fitur</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($features as $row)
                                        <tr>
                                            <td><img src="{{ asset('storage/products/' . $row->image) }}" height="100px"
                                                    alt="{{ $row->name }}"></td>
                                            <td>{{ $row->id }}</td>
                                            <td>{{ $row->name }}</td>
                                            <td>
                                                <form
                                                    action="{{ route('admin.home_management.featured.delete', $row->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Hapus Produk Fitur?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" tabindex="-1" id="add-featured" role="dialog">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('admin.home_management.featured.add') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Produk Fitur</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="product_id">Produk</label>
                                                    <select name="product_id" class="form-control">
                                                        <option value="">Pilih</option>
                                                        @foreach ($products as $row)
                                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-sm">Tambah</button>
                                        </div>
                                    </div>
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
    function showSlider(id) {
        console.log(id);
        $.ajax({
            url: '/administrator/manage/slider/show/' + id,
            type: 'GET',
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (res) {
                $("#slider-title").html(res.title);
                $("#slider-subtitle").text(res.subtitle);
                $("#slider-text").text(res.text);
                $("#slider-image").attr('src', "{{asset('storage/slider')}}/" + res.image);
            }
        });
    }
</script>
@endsection
