@extends('layouts.admin')

@section('title')
<title>Edit Tampilan Slider</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Manajemen Beranda</li>
        <li class="breadcrumb-item active">Edit Tampilan Slider</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Fitur Slider</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.home_management.slider.edit') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                    <input type="hidden" name="id" value="{{ $slider_content->id }}">
                                    <div class="form-group">
                                        <label for="title">Judul</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ $slider_content->title }}" required>
                                        <p class="text-danger">{{ $errors->first('title') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Subjudul</label>
                                        <input type="text" name="subtitle" class="form-control"
                                            value="{{ $slider_content->subtitle }}" required>
                                        <p class="text-danger">{{ $errors->first('subtitle') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Teks</label>
                                        <input type="text" name="text" class="form-control"
                                            value="{{ $slider_content->text }}" required>
                                        <p class="text-danger">{{ $errors->first('text') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Foto</label>
                                        <br>
                                        <img src="{{ asset('storage/slider/' . $slider_content->image) }}" height="100px"
                                            alt="{{ $slider_content->title }}">
                                        <hr>
                                        <input type="file" name="image" class="form-control">
                                        <p><strong>Biarkan kosong jika tidak ingin mengganti gambar</strong></p>
                                        <p class="text-danger">{{ $errors->first('image') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-sm" id="submit_update"
                                            onclick="return confirm('Ubah Data Slider?')">Update</button>
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
