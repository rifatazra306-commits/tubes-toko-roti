<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class HomeController extends Controller
{
    public function index()
    {
        $products = Produk::all();
        return view('home', compact('products'));
    }

    public function about()
    {
        return view('about');
    }

    public function manual()
    {
        return view('manual');
    }
}
