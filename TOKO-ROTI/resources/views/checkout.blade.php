@extends('layouts.app')

@section('content')
<div class="container" style="padding-bottom: 200px">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680"><b>Checkout</b></h2>
    
    <div class="row">
        <div class="col-md-6">
            <h4>Daftar Pesanan</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 1; 
                        $grandTotal = 0; 
                    @endphp
                    @foreach($cartItems as $row)
                        @php 
                            $sub = $row->harga * $row->qty; 
                            $grandTotal += $sub; 
                        @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $row->nama_produk }}</td>
                            <td>Rp.{{ number_format($row->harga) }}</td>
                            <td>{{ $row->qty }}</td>
                            <td>Rp.{{ number_format($sub) }}</td>
                        </tr>
                        @php $no++; @endphp
                    @endforeach
                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Grand Total = Rp.{{ number_format($grandTotal) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 bg-success" style="padding: 10px; border-radius: 4px; margin-bottom: 10px;">
            <h5 style="margin: 0; color: #3c763d;"><b>Pastikan Pesanan Anda Sudah Benar</b></h5>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 bg-warning" style="padding: 10px; border-radius: 4px; margin-bottom: 10px;">
            <h5 style="margin: 0; color: #8a6d3b;"><b>Isi Form di bawah ini</b></h5>
        </div>
    </div>
    <br>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kode_cs" value="{{ session('kd_cs') }}">
        
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" placeholder="Nama" name="nama" style="width: 557px;" value="{{ $customer->nama }}" readonly>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prov">Provinsi</label>
                    <input type="text" class="form-control" id="prov" placeholder="Provinsi" name="prov" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="kota">Kota</label>
                    <input type="text" class="form-control" id="kota" placeholder="Kota" name="kota" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="almt">Alamat</label>
                    <input type="text" class="form-control" id="almt" placeholder="Alamat" name="almt" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="kopos">Kode Pos</label>
                    <input type="text" class="form-control" id="kopos" placeholder="Kode Pos" name="kopos" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Order Sekarang</button>
        <a href="{{ route('cart.index') }}" class="btn btn-danger">Cancel</a>
    </form>
</div>
@endsection
