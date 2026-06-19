@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 250px;">
    <h2>Dashboard Admin</h2>
    <hr>
    
    <div class="row">
        <div class="col-md-4">
            <div style="background-color: #dfdfdf; padding-bottom: 60px; padding-left: 20px; padding-right: 20px; padding-top: 10px; border-radius: 4px;">
                <h4>PESANAN BARU</h4>
                <h4 style="font-size: 56pt;"><b>{{ $newOrders }}</b></h4>
            </div>
        </div>

        <div class="col-md-4">
            <div style="background-color: #dfdfdf; padding-bottom: 60px; padding-left: 20px; padding-right: 20px; padding-top: 10px; border-radius: 4px;">
                <h4>PESANAN DIBATALKAN</h4>
                <h4 style="font-size: 56pt;"><b>{{ $cancelledOrders }}</b></h4>
            </div>
        </div>

        <div class="col-md-4">
            <div style="background-color: #dfdfdf; padding-bottom: 60px; padding-left: 20px; padding-right: 20px; padding-top: 10px; border-radius: 4px;">
                <h4>PESANAN DITERIMA</h4>
                <h4 style="font-size: 56pt;"><b>{{ $acceptedOrders }}</b></h4>
            </div>
        </div>
    </div>
</div>
@endsection
