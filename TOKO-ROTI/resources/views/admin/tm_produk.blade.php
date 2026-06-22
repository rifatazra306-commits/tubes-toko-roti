@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 150px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Tambah Produk</b></h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="files">Pilih Gambar</label>
            <input type="file" id="files" name="files" required>
            <p class="help-block">Pilih Gambar untuk Produk (Format: JPG, JPEG, PNG. Max: 1MB)</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kode">Kode Produk</label>
                    <input type="text" class="form-control" id="kode_disabled" disabled value="{{ $nextCode }}">
                    <input type="hidden" name="kode" value="{{ $nextCode }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">Nama Produk</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama Produk" name="nama" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" placeholder="Contoh : 12000" name="harga" required>
                    <p class="help-block">Isi Harga tanpa menggunakan Titik(.) atau Koma (,)</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="stok">Stok Awal</label>
                    <input type="number" class="form-control" id="stok" placeholder="Contoh : 50" name="stok" min="0" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="desk">Deskripsi</label>
            <textarea name="desk" class="form-control" rows="4" required></textarea>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-6">
                <button type="submit" class="btn btn-success btn-block"><i class="glyphicon glyphicon-plus-sign"></i> Tambah</button>
            </div>  
            <div class="col-md-6">
                <a href="{{ route('admin.produk.index') }}" class="btn btn-danger btn-block">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
