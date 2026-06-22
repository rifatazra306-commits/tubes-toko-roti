@extends('layouts.app')

@section('content')
<div class="container" style="padding-bottom: 300px;">
    <h2 style="width: 100%; border-bottom: 4px solid #C8B273; color: #C8B273;"><b>Cart</b></h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        @if(count($cartItems) > 0)
            <thead>
                <tr>
                    <th scope="col" style="color: #C8B273;">No</th>
                    <th scope="col" style="color: #C8B273;">Image</th>
                    <th scope="col" style="color: #C8B273;">Name</th>
                    <th scope="col" style="color: #C8B273;">Price</th>
                    <th scope="col" style="color: #C8B273;">Quantity</th>
                    <th scope="col" style="color: #C8B273;">SubTotal</th>
                    <th scope="col" style="color: #C8B273;">Action</th>
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
                        <th scope="row">{{ $no }}</th>
                        <td>
                            <img src="{{ asset('image/produk/' . $row->image) }}" width="80"
                                style="border-radius: 8px; object-fit: cover; height: 80px;">
                        </td>
                        <td style="vertical-align: middle;">{{ $row->nama_produk }}</td>
                        <td style="vertical-align: middle;">Rp.{{ number_format($row->harga) }}</td>
                        <td style="vertical-align: middle;">
                            <form action="{{ route('cart.update') }}" method="POST" style="display:inline-block;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $row->id_keranjang }}">
                                <input type="number" name="qty" class="form-control" style="text-align: center; width: 80px; display: inline-block;" value="{{ $row->qty }}" min="1">
                                <button type="submit" class="btn btn-warning btn-sm">Update</button>
                            </form>
                        </td>
                        <td style="vertical-align: middle;">Rp.{{ number_format($sub) }}</td>
                        <td style="vertical-align: middle;">
                            <a href="{{ route('cart.delete', $row->id_keranjang) }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin dihapus ?')">Delete</a>
                        </td>
                    </tr>
                    @php $no++; @endphp
                @endforeach

                {{-- Grand Total --}}
                <tr>
                    <td colspan="7" style="text-align: right; font-weight: bold; font-size: 16px; padding: 14px;">
                        Grand Total = Rp.{{ number_format($grandTotal) }}
                    </td>
                </tr>

                {{-- Action Buttons --}}
                <tr>
                    <td colspan="7" style="text-align: right; padding: 10px 14px;">
                        <a href="{{ route('produk') }}" class="btn btn-success">Continue Shopping</a>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="margin-left: 8px;">Checkout</a>
                    </td>
                </tr>

        @else
            <thead>
                <tr>
                    <th scope="col" style="color: #C8B273;">No</th>
                    <th scope="col" style="color: #C8B273;">Image</th>
                    <th scope="col" style="color: #C8B273;">Name</th>
                    <th scope="col" style="color: #C8B273;">Price</th>
                    <th scope="col" style="color: #C8B273;">Quantity</th>
                    <th scope="col" style="color: #C8B273;">SubTotal</th>
                    <th scope="col" style="color: #C8B273;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="text-center bg-warning">
                        <h5><b>YOUR SHOPPING CART IS EMPTY</b></h5>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>

<style>
    .table > tbody > tr > td,
    .table > tbody > tr > th {
        color: #fff;
        border-color: rgba(255,255,255,0.1);
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: rgba(255,255,255,0.05);
    }
    .table-striped > tbody > tr:nth-of-type(even) {
        background-color: rgba(255,255,255,0.02);
    }
    .table > thead > tr > th {
        border-bottom: 2px solid rgba(200,178,115,0.4);
    }
</style>

@endsection