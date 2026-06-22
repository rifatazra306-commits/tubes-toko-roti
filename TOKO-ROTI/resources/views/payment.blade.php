@extends('layouts.app')

@section('content')
<div class="container" style="padding-bottom: 200px">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680"><b>Ringkasan Pembayaran</b></h2>
    
    <div class="row">
        <div class="col-md-6">
            <h4>Order List</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
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
                    <tr style="background-color: #f9f9f9;">
                        <td colspan="5" style="text-align: right; font-weight: bold; font-size: 16px; color: #ff8680;">Grand Total = Rp.{{ number_format($grandTotal) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="panel panel-default" style="margin-top: 20px;">
                <div class="panel-heading" style="background-color: #fdf5f5; color: #ff8680; font-weight: bold;">
                    Alamat Pengiriman
                </div>
                <div class="panel-body">
                    <p><b>Name:</b> {{ $shipping['nama'] }}</p>
                    <p><b>Full Address:</b> {{ $shipping['almt'] }}, {{ $shipping['kota'] }}, {{ $shipping['prov'] }} - {{ $shipping['kopos'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-primary" style="border-color: #ff8680;">
                <div class="panel-heading" style="background-color: #ff8680; border-color: #ff8680; font-weight: bold;">
                    select payment method
                </div>
                <div class="panel-body">
                    <form action="{{ route('checkout.payment.process') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="radio-inline" style="font-weight: bold; font-size: 14px;">
                                <input type="radio" name="payment_method" value="qris" checked onclick="switchPaymentMethod('qris')">
                                QRIS (Scan QR Code)
                            </label>
                            <label class="radio-inline" style="font-weight: bold; font-size: 14px;">
                                <input type="radio" name="payment_method" value="bni" onclick="switchPaymentMethod('bni')">
                                Transfer Bank BNI (Virtual Account)
                            </label>
                            <label class="radio-inline" style="font-weight: bold; font-size: 14px;">
                                <input type="radio" name="payment_method" value="mandiri" onclick="switchPaymentMethod('mandiri')">
                                Transfer Bank Mandiri (Virtual Account)
                            </label>
                        </div>
                        
                        <hr>

                        <!-- Payment Guide Area -->
                        <div id="payment-guide" style="min-height: 250px; background-color: #fafafa; border: 1px dashed #ccc; padding: 20px; border-radius: 4px; text-align: center;">
                            <!-- QRIS Guide (Default) -->
                            <div id="guide-qris">
                                <h4 style="color: #333;"><b>Payment via QRIS</b></h4>
                                <p style="color: #666; margin-bottom: 15px;">Please scan the QR Code below using GoPay, OVO, Dana, LinkAja, or your Mobile Banking app.</p>
                                <center>
                                    <div style="padding: 10px; border: 1px solid #ddd; background: #fff; width: 220px; border-radius: 4px;">
                                        <img src="{{ asset('image/qris_mockup.png') }}" alt="QRIS Mockup" style="width: 200px; height: 200px;">
                                    </div>
                                </center>
                                <p style="margin-top: 15px; font-weight: bold; color: #ff8680;">Amount: Rp.{{ number_format($grandTotal) }}</p>
                            </div>

                            <!-- BNI Guide -->
                            <div id="guide-bni" style="display: none;">
                                <h4><b>Payment via BNI Virtual Account</b></h4>
                                <p>Please perform a transfer to the following Virtual Account number:</p>
                                <div style="background: #eef7ee; border: 1px solid #d0ebd0; padding: 15px; border-radius: 4px; display: inline-block; margin: 10px 0;">
                                    <span style="font-size: 20px; font-weight: bold; letter-spacing: 1px; color: #2e7d32;">8801 0856 7485 64</span>
                                </div>
                                <p><b>Account Name:</b> RAPI-CAKE BAKERY (SIMULASI)</p>
                                <p><b>Amount:</b> Rp.{{ number_format($grandTotal) }}</p>
                                <hr>
                                <div style="text-align: left; font-size: 12px; color: #666;">
                                    <p><b>Payment Method:</b></p>
                                    <ol>
                                        <li>Open BNI Mobile Banking or BNI ATM.</li>
                                        <li>Select the <b>Transfer > Virtual Account Billing</b> menu.</li>
                                        <li>Enter the VA number above.</li>
                                        <li>Confirm the payment amount and complete the transaction.</li>
                                    </ol>
                                </div>
                            </div>

                            <!-- Mandiri Guide -->
                            <div id="guide-mandiri" style="display: none;">
                                <h4><b>Payment via Mandiri Virtual Account</b></h4>
                                <p>Please perform a transfer to the following Virtual Account number:</p>
                                <div style="background: #eef7ee; border: 1px solid #d0ebd0; padding: 15px; border-radius: 4px; display: inline-block; margin: 10px 0;">
                                    <span style="font-size: 20px; font-weight: bold; letter-spacing: 1px; color: #2e7d32;">8891 0856 7485 64</span>
                                </div>
                                <p><b>Account Name:</b> RAPI-CAKE BAKERY (SIMULASI)</p>
                                <p><b>Amount:</b> Rp.{{ number_format($grandTotal) }}</p>
                                <hr>
                                <div style="text-align: left; font-size: 12px; color: #666;">
                                    <p><b>Payment Method:</b></p>
                                    <ol>
                                        <li>Open Livin' by Mandiri or Mandiri ATM.</li>
                                        <li>Select the <b>Bayar > Multi Payment</b> menu.</li>
                                        <li>Enter the company code Rapi-Cake and the VA number above.</li>
                                        <li>Confirm the payment amount and complete the transaction.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 25px;">
                            <button type="submit" class="btn btn-lg btn-block btn-success" style="background-color: #2e7d32; border-color: #2e7d32; font-weight: bold; text-transform: uppercase;">
                                <i class="glyphicon glyphicon-ok-sign"></i> Confirm Payment
                            </button>
                            <a href="{{ route('checkout.index') }}" class="btn btn-default btn-block" style="margin-top: 10px;">Back to Address</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchPaymentMethod(method) {
        document.getElementById('guide-qris').style.display = 'none';
        document.getElementById('guide-bni').style.display = 'none';
        document.getElementById('guide-mandiri').style.display = 'none';
        
        if (method === 'qris') {
            document.getElementById('guide-qris').style.display = 'block';
        } else if (method === 'bni') {
            document.getElementById('guide-bni').style.display = 'block';
        } else if (method === 'mandiri') {
            document.getElementById('guide-mandiri').style.display = 'block';
        }
    }
</script>
@endsection
