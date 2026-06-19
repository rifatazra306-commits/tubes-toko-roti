<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProductController extends Controller
{
    public function index()
    {
        $products = Produk::all();
        return view('produk', compact('products'));
    }

    public function detail($id)
    {
        $product = Produk::where('kode_produk', $id)->firstOrFail();
        return view('detail', compact('product'));
    }
}
