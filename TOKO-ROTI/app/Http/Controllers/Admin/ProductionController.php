<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\BomProduk;
use App\Models\Customer;
use App\Models\Produk;

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
                $bomItems = BomProduk::where('kode_produk', $item->kode_produk)->get();
                foreach ($bomItems as $bomItem) {
                    $inv = Inventory::where('kode_bk', $bomItem->kode_bk)->first();
                    if ($inv) {
                        $bomQty = (float) $bomItem->kebutuhan * (int) $item->qty;
                        $hasil = (float) $inv->qty - $bomQty;
                        if ($hasil < 0 && $order->tolak == 0) {
                            $shortageMaterials[] = $inv->nama;
                            $orderHasShortage = true;
                        }
                    }
                }
            }

            if ($orderHasShortage) {
                Produksi::where('invoice', $order->invoice)->update(['cek' => 1]);
                $order->cek = 1;
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
                $bomItems = BomProduk::where('kode_produk', $item->kode_produk)->get();
                foreach ($bomItems as $bomItem) {
                    $inv = Inventory::where('kode_bk', $bomItem->kode_bk)->first();
                    if ($inv) {
                        $bomQty = (float) $bomItem->kebutuhan * (int) $item->qty;
                        $hasil = (float) $inv->qty - $bomQty;
                        if ($hasil < 0 && $order->tolak == 0) {
                            $shortageMaterials[] = $inv->nama;
                            $orderHasShortage = true;
                        }
                    }
                }
            }

            if ($orderHasShortage) {
                Produksi::where('invoice', $order->invoice)->update(['cek' => 1]);
                $order->cek = 1;
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
            $bomItems = BomProduk::where('kode_produk', $item->kode_produk)->get();
            foreach ($bomItems as $bomItem) {
                $inv = Inventory::where('kode_bk', $bomItem->kode_bk)->first();
                if ($inv) {
                    $bomQty = (float) $bomItem->kebutuhan * (int) $item->qty;
                    $newQty = (float) $inv->qty - $bomQty;
                    Inventory::where('kode_bk', $bomItem->kode_bk)->update(['qty' => $newQty]);
                }
            }
        }

        Produksi::where('invoice', $invoice)->update(['terima' => 1, 'status' => '0']);

        return redirect()->route('admin.produksi.index')->with('success', 'PESANAN BERHASIL DITERIMA, BAHAN BAKU TELAH DIKURANGKAN');
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

    public function bom(Request $request)
    {
        $kode = $request->get('kode');
        $selectedProduct = Produk::where('kode_produk', $kode)->firstOrFail();
        
        $bomItems = BomProduk::join('inventory', 'bom_produk.kode_bk', '=', 'inventory.kode_bk')
            ->select('inventory.nama as nama', 'bom_produk.kebutuhan as jml', 'inventory.satuan as satu')
            ->where('bom_produk.kode_produk', $kode)
            ->get();

        $products = Produk::all();

        return view('admin.bom', compact('products', 'selectedProduct', 'bomItems'));
    }
}
