<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Keranjang;

class CartController extends Controller
{
    public function index()
    {
        $kode_cs = session('kd_cs');
        
        // Gabungkan keranjang dengan produk untuk mengambil gambar produk
        $cartItems = Keranjang::join('produk', 'keranjang.kode_produk', '=', 'produk.kode_produk')
            ->select(
                'keranjang.id_keranjang',
                'keranjang.kode_produk',
                'keranjang.nama_produk',
                'keranjang.qty',
                'produk.image',
                'produk.harga'
            )
            ->where('keranjang.kode_customer', $kode_cs)
            ->get();

        return view('keranjang', compact('cartItems'));
    }

    public function add(Request $request, $id)
    {
        $kode_cs = session('kd_cs');
        $product = Produk::where('kode_produk', $id)->firstOrFail();
        $hal = $request->query('hal', 1);
        $qty = $request->query('jml', 1);

        $existing = Keranjang::where('kode_produk', $id)
            ->where('kode_customer', $kode_cs)
            ->first();

        if ($existing) {
            $existing->qty += $qty;
            $existing->save();
        } else {
            Keranjang::create([
                'kode_customer' => $kode_cs,
                'kode_produk' => $product->kode_produk,
                'nama_produk' => $product->nama,
                'qty' => $qty,
                'harga' => $product->harga
            ]);
        }

        if ($hal == 1) {
            return redirect()->route('cart.index')->with('success', 'SUCCESSFULLY ADDED TO CART');
        } else {
            return redirect()->route('produk.detail', $id)->with('success', 'SUCCESSFULLY ADDED TO CART');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'qty' => 'required|integer|min:1'
        ]);

        $item = Keranjang::findOrFail($request->id);
        $item->qty = $request->qty;
        $item->save();

        return redirect()->route('cart.index')->with('success', 'CART SUCCESSFULLY UPDATED');
    }

    public function delete($id)
    {
        $item = Keranjang::where('id_keranjang', $id)
            ->where('kode_customer', session('kd_cs'))
            ->firstOrFail();
            
        $item->delete();

        return redirect()->route('cart.index')->with('success', 'ITEM SUCCESSFULLY REMOVED FROM CART');
    }
}
