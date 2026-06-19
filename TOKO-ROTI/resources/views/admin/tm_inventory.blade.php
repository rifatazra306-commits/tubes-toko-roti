@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 150px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Tambah Material</b></h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.inventory.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kode Material</label>
                    <input type="text" class="form-control" disabled value="{{ $nextCode }}">
                    <input type="hidden" name="kd_material" value="{{ $nextCode }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">Nama Material</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Material" name="nama" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" placeholder="contoh 2 atau 4" min="0" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" class="form-control" id="satuan" placeholder="Contoh : Kg atau gram" name="satuan" required>
                    <p class="help-block">Hanya Masukkan Satuan saja : Kg atau gram</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh : 1000" min="1" required>
                    <p class="help-block">Harga termasuk harga per kg atau per gram</p>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah</button>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-danger">Cancel</a>
    </form>
</div>
@endsection
