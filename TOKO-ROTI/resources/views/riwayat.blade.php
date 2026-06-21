@extends('layouts.app')

@section('content')
<div class="container" style="padding-bottom: 300px;">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680; margin-bottom: 20px;"><b>Riwayat Belanja Anda</b></h2>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <b>Sukses!</b> {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="well text-center" style="background-color: #fff; border: 1px solid #ff8680; padding: 40px;">
            <h4 style="color: #666; font-weight: bold;">Anda belum memiliki riwayat pesanan.</h4>
            <a href="{{ route('produk') }}" class="btn btn-success" style="margin-top: 15px; background-color: #ff8680; border-color: #ff8680;">Mulai Belanja</a>
        </div>
    @else
        <table class="table table-hover table-striped" style="border: 1px solid #ddd;">
            <thead style="background-color: #fcf8f8;">
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Total Belanja</th>
                    <th class="text-center">Status Pembayaran</th>
                    <th class="text-center">Status Pengiriman</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($orders as $invoice => $items)
                    @php
                        $firstItem = $items->first();
                        $totalPayment = 0;
                        foreach($items as $item) {
                            $totalPayment += $item->harga * $item->qty;
                        }
                    @endphp
                    <tr>
                        <td>{{ $no }}</td>
                        <td><span class="label label-default" style="font-size: 11px;">#{{ $invoice }}</span></td>
                        <td>{{ $firstItem->tanggal }}</td>
                        <td style="font-weight: bold; color: #ff8680;">Rp.{{ number_format($totalPayment) }}</td>
                        <td class="text-center">
                            @if(strtolower($firstItem->status_pembayaran) === 'lunas')
                                <span class="label label-success" style="padding: 6px 12px; font-size: 11px; text-transform: uppercase;">Lunas</span>
                            @else
                                <span class="label label-warning" style="padding: 6px 12px; font-size: 11px; text-transform: uppercase; background-color: #f0ad4e;">Pending</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($firstItem->terima == 1)
                                <span class="label label-success" style="padding: 6px 12px; font-size: 11px;">Diterima & Dikirim</span>
                            @elseif($firstItem->tolak == 1)
                                <span class="label label-danger" style="padding: 6px 12px; font-size: 11px;">Ditolak</span>
                            @else
                                <span class="label label-info" style="padding: 6px 12px; font-size: 11px; background-color: #5bc0de;">{{ $firstItem->status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-xs btn-primary" type="button" data-toggle="collapse" data-target="#details-{{ $invoice }}" aria-expanded="false" aria-controls="details-{{ $invoice }}">
                                <i class="glyphicon glyphicon-eye-open"></i> Detail Item
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Collapsible Details Row -->
                    <tr class="collapse" id="details-{{ $invoice }}" style="background-color: #fafafa;">
                        <td colspan="7" style="padding: 15px 30px;">
                            <div style="background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 4px;">
                                <h5 style="border-bottom: 2px solid #ff8680; padding-bottom: 5px; margin-top: 0; color: #ff8680;"><b>Rincian Produk untuk Invoice #{{ $invoice }}</b></h5>
                                <table class="table table-bordered table-condensed" style="margin-bottom: 0;">
                                    <thead style="background-color: #f9f9f9;">
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Harga Satuan</th>
                                            <th class="text-center">Kuantitas</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            <tr>
                                                <td>{{ $item->nama_produk }}</td>
                                                <td>Rp.{{ number_format($item->harga) }}</td>
                                                <td class="text-center">{{ $item->qty }}</td>
                                                <td class="text-right" style="font-weight: bold;">Rp.{{ number_format($item->harga * $item->qty) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr style="background-color: #f9f9f9;">
                                            <td colspan="3" class="text-right"><b>Grand Total:</b></td>
                                            <td class="text-right" style="font-weight: bold; color: #ff8680;">Rp.{{ number_format($totalPayment) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <div style="margin-top: 10px; font-size: 12px; color: #777;">
                                    <b>Alamat Pengiriman:</b> {{ $firstItem->alamat }}, {{ $firstItem->kota }}, {{ $firstItem->provinsi }} - {{ $firstItem->kode_pos }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php $no++; @endphp
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
