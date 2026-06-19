@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 150px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Inventory Material</b></h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Material</th>
                <th scope="col">Nama</th>
                <th scope="col">Stok</th>
                <th scope="col">Satuan</th>
                <th scope="col">Harga</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($inventory as $row)
                <tr>
                    <th scope="row">{{ $no }}</th>
                    <td>{{ $row->kode_bk }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->qty }}</td>
                    <td>{{ $row->satuan }}</td>
                    <td>Rp.{{ number_format($row->harga) }}/{{ $row->satuan }}</td>
                    <td>
                        <a href="{{ route('admin.inventory.edit', $row->kode_bk) }}" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a> 
                        <a href="{{ route('admin.inventory.delete', $row->kode_bk) }}" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data ?')"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
                @php $no++; @endphp
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.inventory.create') }}" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Material</a>
</div>
@endsection
