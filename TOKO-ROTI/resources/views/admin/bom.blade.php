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
                    <td>
                        <a href="{{ route('admin.produk.edit', $row->kode_produk) }}" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a> 
                        <a href="{{ route('admin.produk.delete', $row->kode_produk) }}" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data ?')"><i class="glyphicon glyphicon-trash"></i></a> 
                        <a href="{{ route('admin.bom.index', ['kode' => $row->kode_produk]) }}" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Lihat BOM</a>
                    </td>
                </tr>
                @php $no++; @endphp
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Produk</a>
</div>

@if(isset($selectedProduct))
    <!-- Trigger Modal -->
    <button type="button" data-toggle="modal" data-target="#bomModal" id="triggerBomBtn" style="display: none;"></button>

    <!-- Modal BOM Detail -->
    <div class="modal fade" id="bomModal" tabindex="-1" role="dialog" aria-labelledby="bomModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="{{ route('admin.produk.index') }}" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                    <h4 class="modal-title" id="bomModalLabel">BOM PRODUK {{ strtoupper($selectedProduct->nama) }}</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Material</th>
                                <th>Kebutuhan Material</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $bomNo = 1; @endphp
                            @foreach($bomItems as $item)
                                <tr>
                                    <td>{{ $bomNo }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->jml }} {{ $item->satu }}</td>
                                </tr>
                                @php $bomNo++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-default">Close</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#triggerBomBtn").click();
        });
    </script>
@endif
@endsection
