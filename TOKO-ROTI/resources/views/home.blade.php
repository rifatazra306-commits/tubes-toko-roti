@extends('layouts.app')

@section('content')
<!-- IMAGE -->
<div class="container-fluid" style="margin: 0; padding: 0;">
    <div class="image" style="margin-top: -21px">
        <img src="{{ asset('image/home/1.jpg') }}" style="width: 100%; height: 650px;">
    </div>
</div>
<br>
<br>

<!-- PRODUK TERBARU -->
<div class="container">
    <h4 class="text-center" style="font-family: arial; padding-top: 10px; padding-bottom: 10px; font-style: italic; line-height: 29px; border-top: 2px solid #ff8d87; border-bottom: 2px solid #ff8d87;">
        Rapi Cake & Bakery adalah salah satu pelopor pertama dalam bisnis roti modern di Indonesia. Didirikan pada tahun 1978, saat ini dikelola di bawah PT. Mustika Citra Rasa. Produk kami sehat, bergizi, dan terjangkau oleh semua orang.
    </h4>

    <h2 style="width: 100%; border-bottom: 4px solid #ff8680; margin-top: 80px;"><b>Produk Kami</b></h2>

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
<br>
<br>
@endsection
