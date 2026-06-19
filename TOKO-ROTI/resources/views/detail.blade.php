@extends('layouts.app')

@section('content')
<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680"><b>Detail produk</b></h2>

    <div class="row">
        <div class="col-md-4">
            <div class="thumbnail">
                <img src="{{ asset('image/produk/' . $product->image) }}" width="400">
            </div>
        </div>

        <div class="col-md-8">
            <form action="{{ route('cart.add', $product->kode_produk) }}" method="GET">
                <input type="hidden" name="kd_cs" value="{{ session('kd_cs') }}">
                <input type="hidden" name="hal" value="2">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><b>Nama</b></td>
                            <td>{{ $product->nama }}</td>
                        </tr>
                        <tr>
                            <td><b>Harga</b></td>
                            <td>Rp.{{ number_format($product->harga) }}</td>
                        </tr>
                        <tr>
                            <td><b>Deskripsi</b></td>
                            <td>{{ $product->deskripsi }}</td>
                        </tr>
                        <tr>
                            <td><b>Jumlah</b></td>
                            <td><input class="form-control" type="number" min="1" name="jml" value="1" style="width: 155px;"></td>
                        </tr>
                    </tbody>
                </table>
                @if(session()->has('user'))
                    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Tambahkan ke Keranjang</button>
                @else
                    <a href="{{ route('cart.index') }}" class="btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Tambahkan ke Keranjang</a>
                @endif
                <a href="{{ route('produk') }}" class="btn btn-warning"> Kembali Belanja</a>
            </form>
        </div>
    </div>
</div>
<br>
<br>
@endsection
