@extends('layouts.app')

@section('content')
<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680"><b>Produk Kami</b></h2>

    <div class="row">
        @foreach($products as $row)
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <img src="{{ asset('image/produk/' . $row->image) }}" style="height: 250px; object-fit: cover; width: 100%;">
                    <div class="caption">
                        <h3>{{ $row->nama }}</h3>
                        <h4>Rp.{{ number_format($row->harga) }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('produk.detail', $row->kode_produk) }}" class="btn btn-warning btn-block">Detail</a> 
                            </div>
                            @if(session()->has('kd_cs'))
                                <div class="col-md-6">
                                    <a href="{{ route('cart.add', ['id' => $row->kode_produk, 'kd_cs' => session('kd_cs'), 'hal' => 1]) }}" class="btn btn-success btn-block" role="button">
                                        <i class="glyphicon glyphicon-shopping-cart"></i> Tambah
                                    </a>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <a href="{{ route('cart.index') }}" class="btn btn-success btn-block" role="button">
                                        <i class="glyphicon glyphicon-shopping-cart"></i> Tambah
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
