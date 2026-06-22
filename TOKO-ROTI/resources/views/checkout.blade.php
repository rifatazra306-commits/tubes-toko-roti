@extends('layouts.app')

@section('content')
<style>
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.1);
    }
    .table-striped > tbody > tr:nth-of-type(even) {
        background-color: rgba(255, 255, 255, 0.05);
    }
    .table > tbody > tr > td,
    .table > thead > tr > th {
        color: #fff; /* biar teks keliatan di bg gelap */
        border-color: rgba(255,255,255,0.2);
    }
</style>
<div class="container" style="padding-bottom: 200px">
    <h2 style="width: 100%; border-bottom: 4px solid  #C8B273; color: #C8B273;"><b>Checkout</b></h2>
    
    <div class="row">
        <div class="col-md-12">
            <h4 style="color: #C8B273;"><b>Order List</b></h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="color: #e1e0dd; font-weight: bold;">No</th>
                        <th style="color: #e1e0dd; font-weight: bold;">Name</th>
                        <th style="color: #e1e0dd; font-weight: bold;">Price</th>
                        <th style="color: #e1e0dd; font-weight: bold;">Quantity</th>
                        <th style="color: #e1e0dd; font-weight: bold;">Sub Total</th>
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

    <div class="col-md-12" style="
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    padding: 14px 20px;
    border-radius: 7px;
    margin-bottom: 10px;
    text-align: center;
">
    <h5 style="margin: 0; color: #155724; font-weight: bold;">Make Sure Your Order Is Correct</h5>
</div>
    <br>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kode_cs" value="{{ session('kd_cs') }}">
        
        <div class="form-group">
            <label for="nama" style="color:#C8B273;font-weight:bold;">Name</label>
            <input type="text" class="form-control" id="nama" placeholder="Name" name="nama" style="width: 557px;" value="{{ $customer->nama }}" readonly>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prov" style="color:#C8B273;font-weight:bold;">Province</label>
                    <input type="text" class="form-control" id="prov" placeholder="Province" name="prov" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="kota" style="color:#C8B273;font-weight:bold;">City</label>
                    <input type="text" class="form-control" id="kota" placeholder="City" name="kota" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="almt" style="color:#C8B273;font-weight:bold;">Address</label>
                    <input type="text" class="form-control" id="almt" placeholder="Address" name="almt" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="kopos" style="color:#C8B273;font-weight:bold;">Postal Code</label>
                    <input type="text" class="form-control" id="kopos" placeholder="Postal Code" name="kopos" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Order Now</button>
        <a href="{{ route('cart.index') }}" class="btn btn-danger; " style="color: #fff; background-color: #4522ad; border-color: #141c73;">Cancel</a>
    </form>
</div>
@endsection
