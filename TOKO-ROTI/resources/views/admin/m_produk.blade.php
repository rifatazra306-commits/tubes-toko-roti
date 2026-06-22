@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 150px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Master Produk</b></h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Produk</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Image</th>
                <th scope="col">Harga</th>
                <th scope="col">Stok</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($products as $row)
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $row->kode_produk }}</td>
                    <td>{{ $row->nama }}</td>
                    <td><img src="{{ asset('image/produk/' . $row->image) }}" width="100"></td>
                    <td>Rp.{{ number_format($row->harga) }}</td>
                    <td>{{ $row->stok }}</td>
                    <td>
                        <a href="{{ route('admin.produk.edit', $row->kode_produk) }}" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a> 
                        <a href="{{ route('admin.produk.delete', $row->kode_produk) }}" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data ?')"><i class="glyphicon glyphicon-trash"></i></a> 
                    </td>
                </tr>
                @php $no++; @endphp
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Produk</a>
</div>
@endsection
