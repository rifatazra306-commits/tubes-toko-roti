<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Keranjang;
use App\Models\Produksi;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index()
    {
        $kode_cs = session('kd_cs');
        $customer = Customer::where('kode_customer', $kode_cs)->firstOrFail();
        
        $cartItems = Keranjang::join('produk', 'keranjang.kode_produk', '=', 'produk.kode_produk')
            ->select(
                'keranjang.id_keranjang',
                'keranjang.kode_produk',
                'keranjang.nama_produk',
                'keranjang.qty',
                'produk.harga'
            )
            ->where('keranjang.kode_customer', $kode_cs)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        return view('checkout', compact('customer', 'cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cs' => 'required',
            'prov' => 'required',
            'kota' => 'required',
            'almt' => 'required',
            'kopos' => 'required',
        ]);

        // Simpan data shipping ke session sementara untuk halaman pembayaran
        session([
            'checkout_shipping' => [
                'kode_cs' => $request->kode_cs,
                'nama' => $request->nama,
                'prov' => $request->prov,
                'kota' => $request->kota,
                'almt' => $request->almt,
                'kopos' => $request->kopos,
            ]
        ]);

        return redirect()->route('checkout.payment');
    }

    public function payment()
    {
        if (!session()->has('checkout_shipping')) {
            return redirect()->route('checkout.index')->with('error', 'Silakan isi form alamat terlebih dahulu.');
        }

        $kode_cs = session('kd_cs');
        $shipping = session('checkout_shipping');
        
        $cartItems = Keranjang::join('produk', 'keranjang.kode_produk', '=', 'produk.kode_produk')
            ->select(
                'keranjang.id_keranjang',
                'keranjang.kode_produk',
                'keranjang.nama_produk',
                'keranjang.qty',
                'produk.harga'
            )
            ->where('keranjang.kode_customer', $kode_cs)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        return view('payment', compact('shipping', 'cartItems'));
    }

    public function paymentProcess(Request $request)
    {
        if (!session()->has('checkout_shipping')) {
            return redirect()->route('checkout.index')->with('error', 'Sesi checkout telah kedaluwarsa.');
        }

        $shipping = session('checkout_shipping');
        $kode_cs = $shipping['kode_cs'];

        $cartItems = Keranjang::where('kode_customer', $kode_cs)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Generate Invoice
        $lastOrder = Produksi::orderBy('invoice', 'desc')->first();
        if ($lastOrder) {
            $num = (int) substr($lastOrder->invoice, 3, 4);
            $add = $num + 1;
        } else {
            $add = 1;
        }

        if (strlen($add) == 1) {
            $format = "INV000" . $add;
        } elseif (strlen($add) == 2) {
            $format = "INV00" . $add;
        } elseif (strlen($add) == 3) {
            $format = "INV0" . $add;
        } else {
            $format = "INV" . $add;
        }

        $tanggal = Carbon::now()->format('Y-m-d');

        foreach ($cartItems as $row) {
            Produksi::create([
                'invoice' => $format,
                'kode_customer' => $kode_cs,
                'kode_produk' => $row->kode_produk,
                'nama_produk' => $row->nama_produk,
                'qty' => $row->qty,
                'harga' => $row->harga,
                'status' => 'Pesanan Baru',
                'tanggal' => $tanggal,
                'provinsi' => $shipping['prov'],
                'kota' => $shipping['kota'],
                'alamat' => $shipping['almt'],
                'kode_pos' => $shipping['kopos'],
                'terima' => '0',
                'tolak' => '0',
                'cek' => 0,
                'status_pembayaran' => 'pending'
            ]);
        }

        // Hapus Keranjang
        Keranjang::where('kode_customer', $kode_cs)->delete();

        // Bersihkan session shipping
        session()->forget('checkout_shipping');

        return redirect()->route('checkout.history')->with('success', 'Transaksi berhasil dicatat, menunggu konfirmasi pembayaran!');
    }

    public function history()
    {
        $kode_cs = session('kd_cs');
        
        // Ambil riwayat order dikelompokkan berdasarkan Invoice
        $orders = Produksi::where('kode_customer', $kode_cs)
            ->orderBy('invoice', 'desc')
            ->get()
            ->groupBy('invoice');

        return view('riwayat', compact('orders'));
    }

    public function success()
    {
        return view('selesai');
    }
}
