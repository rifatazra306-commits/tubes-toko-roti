@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 150px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Edit Produk</b></h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.produk.update', $product->kode_produk) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="image_preview"><img src="{{ asset('image/produk/' . $product->image) }}" width="100"></label>
            <input type="file" id="files" name="files">
            <p class="help-block">Pilih Gambar untuk mengganti Gambar Produk (Format: JPG, JPEG, PNG. Max: 1MB)</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kode">Kode Produk</label>
                    <input type="text" class="form-control" id="kode_disabled" disabled value="{{ $product->kode_produk }}">
                    <input type="hidden" name="kode" value="{{ $product->kode_produk }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">Nama Produk</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama Produk" name="nama" value="{{ $product->nama }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" placeholder="Masukkan Harga" name="harga" value="{{ $product->harga }}" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="desk">Deskripsi</label>
            <textarea name="desk" class="form-control" rows="4" required>{{ trim($product->deskripsi) }}</textarea>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-6">
                <button type="submit" class="btn btn-warning btn-block"><i class="glyphicon glyphicon-edit"></i> Edit</button>
            </div>  
            <div class="col-md-6">
                <a href="{{ route('admin.produk.index') }}" class="btn btn-danger btn-block">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
