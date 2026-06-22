<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Produk::all();
        return view('admin.m_produk', compact('products'));
    }

    public function create()
    {
        // Generate next product code (P0001, P0002, etc.)
        $lastProduct = Produk::orderBy('kode_produk', 'desc')->first();
        if ($lastProduct) {
            $num = (int) substr($lastProduct->kode_produk, 1, 4);
            $add = $num + 1;
        } else {
            $add = 1;
        }
        $nextCode = 'P' . str_pad($add, 4, '0', STR_PAD_LEFT);

        return view('admin.tm_produk', compact('nextCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric|min:0',
            'desk' => 'required',
            'files' => 'required|image|max:1024' // format JPG, JPEG, PNG, max 1MB
        ]);

        // Upload image
        $file = $request->file('files');
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '.' . $extension;
        $file->move(public_path('image/produk'), $filename);

        // Save product
        Produk::create([
            'kode_produk' => $request->kode,
            'nama' => $request->nama,
            'image' => $filename,
            'deskripsi' => $request->desk,
            'harga' => $request->harga,
            'stok' => $request->stok
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'PRODUK BERHASIL DITAMBAHKAN');
    }

    public function edit($id)
    {
        $product = Produk::where('kode_produk', $id)->firstOrFail();
        return view('admin.edit_produk', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric|min:0',
            'desk' => 'required',
            'files' => 'nullable|image|max:1024'
        ]);

        $product = Produk::where('kode_produk', $id)->firstOrFail();

        $updateData = [
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->desk
        ];

        if ($request->hasFile('files')) {
            // Delete old image
            $oldImagePath = public_path('image/produk/' . $product->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Upload new image
            $file = $request->file('files');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
            $file->move(public_path('image/produk'), $filename);

            $updateData['image'] = $filename;
        }

        $product->update($updateData);

        return redirect()->route('admin.produk.index')->with('success', 'PRODUK BERHASIL DIEDIT');
    }

    public function delete($id)
    {
        $product = Produk::where('kode_produk', $id)->firstOrFail();

        // Delete image
        $imagePath = public_path('image/produk/' . $product->image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete product
        $product->delete();

        return redirect()->route('admin.produk.index')->with('success', 'PRODUK BERHASIL DIHAPUS');
    }
}
