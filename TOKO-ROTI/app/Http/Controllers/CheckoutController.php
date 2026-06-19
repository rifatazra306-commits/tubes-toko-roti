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

        $kode_cs = $request->kode_cs;

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

        $cartItems = Keranjang::where('kode_customer', $kode_cs)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
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
                'provinsi' => $request->prov,
                'kota' => $request->kota,
                'alamat' => $request->almt,
                'kode_pos' => $request->kopos,
                'terima' => '0',
                'tolak' => '0',
                'cek' => 0
            ]);
        }

        // Hapus Keranjang
        Keranjang::where('kode_customer', $kode_cs)->delete();

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('selesai');
    }
}
