<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Unique invoices count
        $newOrders = Produksi::where('terima', '0')
            ->where('tolak', '0')
            ->distinct('invoice')
            ->count('invoice');

        $cancelledOrders = Produksi::where('tolak', '1')
            ->distinct('invoice')
            ->count('invoice');

        $acceptedOrders = Produksi::where('terima', '1')
            ->distinct('invoice')
            ->count('invoice');

        return view('admin.dashboard', compact('newOrders', 'cancelledOrders', 'acceptedOrders'));
    }
}
