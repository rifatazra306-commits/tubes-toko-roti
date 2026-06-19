@extends('layouts.app')

@section('content')
<div class="container" style="padding-bottom: 300px;">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680"><b>Keranjang</b></h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        @if(count($cartItems) > 0)
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Image</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Qty</th>
                    <th scope="col">SubTotal</th>
                    <th scope="col">Action</th>
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
                        <td><img src="{{ asset('image/produk/' . $row->image) }}" width="100"></td>
                        <td>{{ $row->nama_produk }}</td>
                        <td>Rp.{{ number_format($row->harga) }}</td>
                        <td>
                            <form action="{{ route('cart.update') }}" method="POST" style="display:inline-block;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $row->id_keranjang }}">
                                <input type="number" name="qty" class="form-control" style="text-align: center; width: 80px; display: inline-block;" value="{{ $row->qty }}" min="1">
                                <button type="submit" class="btn btn-warning btn-sm">Update</button>
                            </form>
                        </td>
                        <td>Rp.{{ number_format($sub) }}</td>
                        <td>
                            <a href="{{ route('cart.delete', $row->id_keranjang) }}" class="btn btn-danger" onclick="return confirm('Yakin ingin dihapus ?')">Delete</a>
                        </td>
                    </tr>
                    @php $no++; @endphp
                @endforeach
                <tr>
                    <td colspan="7" style="text-align: right; font-weight: bold;">Grand Total = Rp.{{ number_format($grandTotal) }}</td>
                </tr>
                <tr>
                    <td colspan="7" style="text-align: right; font-weight: bold;">
                        <a href="{{ route('produk') }}" class="btn btn-success">Lanjutkan Belanja</a> 
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary">Checkout</a>
                    </td>
                </tr>
            @else
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Image</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Qty</th>
                        <th scope="col">SubTotal</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center bg-warning"><h5><b>KERANJANG BELANJA ANDA KOSONG</b></h5></td>
                    </tr>
                </tbody>
            @endif
    </table>
</div>
@endsection
