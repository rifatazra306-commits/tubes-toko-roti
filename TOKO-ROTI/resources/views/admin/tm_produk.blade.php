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

        <div class="form-group">
            <label for="desk">Deskripsi</label>
            <textarea name="desk" class="form-control" rows="4" required></textarea>
        </div>

        <hr>
        <h3 style="width: 100%; border-bottom: 4px solid gray">BOM Produk</h3>
        <br>
        
        <div class="row">
            <div class="col-md-6">
                <h4>Daftar Material yang ada di Gudang/Inventory</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Kode Material</th>
                            <th scope="col">Nama Material</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no2 = 1; @endphp
                        @foreach($materials as $material)
                            <tr>
                                <th scope="row">{{ $no2 }}</th>
                                <td>{{ $material->kode_bk }}</td>
                                <td>{{ $material->nama }}</td>
                            </tr>
                            @php $no2++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h4>Pilih material yang hanya dibutuhkan untuk produk</h4>
                <div class="bg-danger" style="padding: 10px; border-radius: 4px; margin-bottom: 10px;">
                    <p style="color: red; font-weight: bold; margin: 0;">NB. Form di bawah tidak harus diisi semua</p>
                    <p style="color: red; font-weight: bold; margin: 0;">Kode Material tidak boleh sama</p>
                </div>
                <br>
                
                @for($i = 0; $i < count($materials); $i++)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Material</label>
                                <input type="text" name="material[]" class="form-control" placeholder="Masukkan Kode Material">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kebutuhan Material</label>
                                <input type="text" class="form-control" placeholder="Contoh : 250 atau 0.2" name="keb[]">
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
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
