@extends('layouts.admin')

@section('title')
<title>Tambah Ke Slider</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Manajemen Beranda</li>
        <li class="breadcrumb-item active">Tambah Ke Slider</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Tambah Ke Slider</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.home_management.slider.add') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                        required>
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="title">Subjudul</label>
                                    <input type="text" name="subtitle" class="form-control"
                                        value="{{ old('subtitle') }}">
                                    <p class="text-danger">{{ $errors->first('subtitle') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="title">Teks</label>
                                    <textarea name="text" class="form-control">{{ old('text') }}</textarea>
                                    <p class="text-danger">{{ $errors->first('text') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="image">Foto</label>
                                    <input type="file" name="image" class="form-control" value="{{ old('image') }}"
                                        required>
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
