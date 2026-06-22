<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::all();
        return view('admin.inventory', compact('inventory'));
    }

    public function create()
    {
        // Generate next material code (M0001, M0002, etc.)
        $lastInv = Inventory::orderBy('kode_bk', 'desc')->first();
        if ($lastInv) {
            $num = (int) substr($lastInv->kode_bk, 1, 4);
            $add = $num + 1;
        } else {
            $add = 1;
        }
        $nextCode = 'M' . str_pad($add, 4, '0', STR_PAD_LEFT);

        return view('admin.tm_inventory', compact('nextCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_material' => 'required',
            'nama' => 'required',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required',
            'harga' => 'required|numeric|min:1'
        ]);

        Inventory::create([
            'kode_bk' => $request->kd_material,
            'nama' => $request->nama,
            'qty' => $request->stok,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'tanggal' => date('Y-m-d')
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'MATERIAL BERHASIL DITAMBAHKAN');
    }

    public function edit($id)
    {
        $material = Inventory::where('kode_bk', $id)->firstOrFail();
        return view('admin.edit_inventory', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required',
            'harga' => 'required|numeric|min:1'
        ]);

        Inventory::where('kode_bk', $id)->update([
            'nama' => $request->nama,
            'qty' => $request->stok,
            'satuan' => $request->satuan,
            'harga' => $request->harga
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'MATERIAL BERHASIL DIEDIT');
    }

    public function delete($id)
    {
        Inventory::where('kode_bk', $id)->delete();
        return redirect()->route('admin.inventory.index')->with('success', 'MATERIAL BERHASIL DIHAPUS');
    }
}
