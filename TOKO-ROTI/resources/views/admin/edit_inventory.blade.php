@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 150px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Edit Inventory</b></h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.inventory.update', $material->kode_bk) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kode Material</label>
                    <input type="text" class="form-control" disabled value="{{ $material->kode_bk }}">
                    <input type="hidden" name="kd_material" value="{{ $material->kode_bk }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">Nama Material</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Material" name="nama" value="{{ $material->nama }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="{{ $material->qty }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" class="form-control" id="satuan" placeholder="Contoh : Kg" name="satuan" value="{{ $material->satuan }}" required>
                    <p class="help-block">Hanya Masukkan Satuan saja : Kg atau gram</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh : 1000" value="{{ $material->harga }}" required>
                    <p class="help-block">Harga termasuk harga per kg atau per gram</p>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i> Edit</button>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-danger">Cancel</a>
    </form>
</div>
@endsection
