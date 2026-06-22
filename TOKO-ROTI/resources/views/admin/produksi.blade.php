@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 150px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Daftar Pesanan</b></h2>
    <br>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 15px;">
        <a href="{{ route('admin.produksi.index') }}" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i> Reload</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Invoice</th>
                <th scope="col">Kode Customer</th>
                <th scope="col">Status Pembayaran</th>
                <th scope="col">Status Pengiriman</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($orders as $row)
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $row->invoice }}</td>
                    <td>{{ $row->kode_customer }}</td>
                    <td>
                        @if(strtolower($row->status_pembayaran) == 'lunas')
                            <span class="label label-success" style="font-size: 11px;">Lunas</span>
                        @else
                            <span class="label label-warning" style="font-size: 11px;">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($row->terima == 1)
                            <span style="color: green; font-weight: bold;">Pesanan Diterima (Siap Kirim)</span>
                        @elseif($row->tolak == 1)
                            <span style="color: red; font-weight: bold;">Pesanan Ditolak</span>
                        @else
                            <span style="color: orange; font-weight: bold;">{{ $row->status }}</span>
                        @endif
                    </td>
                    <td>{{ $row->tanggal }}</td>
                    <td>
                        @if(strtolower($row->status_pembayaran) != 'lunas')
                            <a href="{{ route('admin.produksi.confirm-payment', $row->invoice) }}" class="btn btn-info" onclick="return confirm('Konfirmasi pembayaran untuk invoice ini?')">
                                <i class="glyphicon glyphicon-usd"></i> Konfirmasi Pembayaran
                            </a>
                        @endif

                        @if($row->tolak == 0 && $row->cek == 1 && $row->terima == 0)
                            <a href="{{ route('admin.inventory.index') }}" class="btn btn-warning">
                                <i class="glyphicon glyphicon-warning-sign"></i> Request Material Shortage
                            </a>
                            <form action="{{ route('admin.produksi.reject', $row->invoice) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin Ingin Menolak?')">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</button>
                            </form>
                        @elseif($row->terima == 0 && $row->cek == 0 && $row->tolak == 0)
                            <form action="{{ route('admin.produksi.accept', $row->invoice) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Terima</button>
                            </form>
                            <form action="{{ route('admin.produksi.reject', $row->invoice) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin Ingin Menolak?')">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</button>
                            </form>
                        @endif

                        <a href="{{ route('admin.produksi.detail', $row->invoice) }}" class="btn btn-primary">
                            <i class="glyphicon glyphicon-eye-open"></i> Detail Pesanan
                        </a>
                    </td>
                </tr>
                @php $no++; @endphp
            @endforeach
        </tbody>
    </table>

    @if($cek_sor > 0 && count($shortageMaterials) > 0)
        <br><br>
        <div class="row">
            <div class="col-md-4 bg-danger" style="padding: 10px; border-radius: 4px;">
                <h4>Kekurangan Material</h4>
                <h5 style="color: red; font-weight: bold;">Silahkan Tambah Stok Material dibawah ini :</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Material</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shortageMaterials as $index => $materialName)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $materialName }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@if(isset($selectedOrder))
    <!-- Trigger Modal -->
    <button type="button" data-toggle="modal" data-target="#detailModal" id="triggerBtn" style="display: none;"></button>

    <!-- Modal Detail Order -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="{{ route('admin.produksi.index') }}" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                    <h4 class="modal-title" id="detailModalLabel">#{{ $selectedOrder->invoice }}</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <td>Invoice</td>
                            <td>{{ $selectedOrder->invoice }}</td>
                        </tr>
                        <tr>
                            <td>Kode Customer</td>
                            <td>{{ $selectedOrder->kode_customer }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>{{ $customer ? $customer->nama : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>{{ $selectedOrder->alamat }}, {{ $selectedOrder->kota }} {{ $selectedOrder->provinsi }}, {{ $selectedOrder->kode_pos }}</td>
                        </tr>
                        <tr>
                            <td>No Telp</td>
                            <td>{{ $customer ? $customer->telp : 'N/A' }}</td>
                        </tr>
                    </table>

                    <hr>
                    <h4>List Order</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $detailNo = 1; 
                                $grandTotal = 0;
                            @endphp
                            @foreach($orderDetails as $item)
                                @php 
                                    $subtotal = $item->harga * $item->qty; 
                                    $grandTotal += $subtotal;
                                @endphp
                                <tr>
                                    <td>{{ $detailNo }}</td>
                                    <td>{{ $item->kode_produk }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>Rp.{{ number_format($item->harga) }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp.{{ number_format($subtotal) }}</td>
                                </tr>
                                @php $detailNo++; @endphp
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-right"><b>Grand Total = Rp.{{ number_format($grandTotal) }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.produksi.index') }}" class="btn btn-default">Close</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#triggerBtn").click();
        });
    </script>
@endif
@endsection
