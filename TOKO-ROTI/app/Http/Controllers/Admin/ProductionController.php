<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produksi;
use App\Models\Produk;
use App\Models\Customer;

class ProductionController extends Controller
{
    public function index()
    {
        $orders = Produksi::selectRaw("
            invoice, 
            MAX(kode_customer) as kode_customer, 
            MAX(status) as status, 
            MAX(kode_produk) as kode_produk, 
            MAX(qty) as qty, 
            MAX(terima) as terima, 
            MAX(tolak) as tolak, 
            MAX(cek) as cek, 
            MAX(tanggal) as tanggal, 
            MAX(status_pembayaran) as status_pembayaran
        ")
        ->groupBy('invoice')
        ->get();

        $shortageMaterials = [];

        foreach ($orders as $order) {
            $items = Produksi::where('invoice', $order->invoice)->get();
            $orderHasShortage = false;

            foreach ($items as $item) {
                $product = Produk::where('kode_produk', $item->kode_produk)->first();
                if ($product) {
                    $hasil = (int) $product->stok - (int) $item->qty;
                    if ($hasil < 0 && $order->tolak == 0) {
                        $shortageMaterials[] = $product->nama;
                        $orderHasShortage = true;
                    }
                }
            }

            if ($orderHasShortage) {
                Produksi::where('invoice', $order->invoice)->update(['cek' => 1]);
                $order->cek = 1;
            } else {
                // If there's no shortage, reset cek to 0
                Produksi::where('invoice', $order->invoice)->update(['cek' => 0]);
                $order->cek = 0;
            }
        }

        $shortageMaterials = array_values(array_unique($shortageMaterials));
        $cek_sor = Produksi::where('cek', 1)->distinct('invoice')->count('invoice');

        return view('admin.produksi', compact('orders', 'shortageMaterials', 'cek_sor'));
    }

    public function detail($invoice)
    {
        $selectedOrder = Produksi::where('invoice', $invoice)->firstOrFail();
        $orderDetails = Produksi::where('invoice', $invoice)->get();
        $customer = Customer::where('kode_customer', $selectedOrder->kode_customer)->first();

        // Load the full index variables as well since detail page is an overlay modal
        $orders = Produksi::selectRaw("
            invoice, 
            MAX(kode_customer) as kode_customer, 
            MAX(status) as status, 
            MAX(kode_produk) as kode_produk, 
            MAX(qty) as qty, 
            MAX(terima) as terima, 
            MAX(tolak) as tolak, 
            MAX(cek) as cek, 
            MAX(tanggal) as tanggal, 
            MAX(status_pembayaran) as status_pembayaran
        ")
        ->groupBy('invoice')
        ->get();

        $shortageMaterials = [];

        foreach ($orders as $order) {
            $items = Produksi::where('invoice', $order->invoice)->get();
            $orderHasShortage = false;

            foreach ($items as $item) {
                $product = Produk::where('kode_produk', $item->kode_produk)->first();
                if ($product) {
                    $hasil = (int) $product->stok - (int) $item->qty;
                    if ($hasil < 0 && $order->tolak == 0) {
                        $shortageMaterials[] = $product->nama;
                        $orderHasShortage = true;
                    }
                }
            }

            if ($orderHasShortage) {
                Produksi::where('invoice', $order->invoice)->update(['cek' => 1]);
                $order->cek = 1;
            } else {
                Produksi::where('invoice', $order->invoice)->update(['cek' => 0]);
                $order->cek = 0;
            }
        }

        $shortageMaterials = array_values(array_unique($shortageMaterials));
        $cek_sor = Produksi::where('cek', 1)->distinct('invoice')->count('invoice');

        return view('admin.produksi', compact('orders', 'shortageMaterials', 'cek_sor', 'selectedOrder', 'orderDetails', 'customer'));
    }

    public function accept($invoice)
    {
        $items = Produksi::where('invoice', $invoice)->get();

        foreach ($items as $item) {
            $product = Produk::where('kode_produk', $item->kode_produk)->first();
            if ($product) {
                $newStok = (int) $product->stok - (int) $item->qty;
                // Avoid dropping below 0, or just let it update
                Produk::where('kode_produk', $item->kode_produk)->update(['stok' => $newStok]);
            }
        }

        Produksi::where('invoice', $invoice)->update(['terima' => 1, 'status' => '0']);

        return redirect()->route('admin.produksi.index')->with('success', 'PESANAN BERHASIL DITERIMA, STOK PRODUK TELAH DIKURANGKAN');
    }

    public function reject($invoice)
    {
        Produksi::where('invoice', $invoice)->update(['tolak' => 1, 'terima' => 2]);

        return redirect()->route('admin.produksi.index')->with('success', 'PESANAN DITOLAK');
    }

    public function confirmPayment($invoice)
    {
        Produksi::where('invoice', $invoice)->update(['status_pembayaran' => 'Lunas']);

        return redirect()->route('admin.produksi.index')->with('success', 'Pembayaran Invoice ' . $invoice . ' Berhasil Dikonfirmasi!');
    }
}
