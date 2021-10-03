@extends('layouts.admin')

@section('title')
    <title>Edit Admin</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Admin</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <form action="{{ route('admin.update', $user->id) }}" method="post" enctype="multipart/form-data" >
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Data Admin</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="user_type_id">Admin</label>
                                    <select name="user_type_id" class="form-control">
                                        <option value="">Pilih</option>
                                        @foreach ($user_type as $row)
                                        <option value="{{ $row->id }}" {{ $user->user_type_id == $row->id ? 'selected':'' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('user_type_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" class="form-control" value="{{ $user->email }}" required>
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm" onclick="return confirm('Ubah Data?')">Update</button>
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