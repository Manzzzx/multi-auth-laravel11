<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        $total = Product::count();
        return view('admin.product.home', compact(['products', 'total']));
    }
    public function create()
    {
        return view('admin.product.create');
    }
    public function save(Request $request)
    {
        $validation = $request->validate([
            'title' => 'required',
            'category' => 'required',
            'price' => 'required',
        ]);
        $data = Product::create($validation);
        if ($data) {
            session()->flash('success', 'Produk berhasil ditambahkan');
            return redirect(route('admin.products'));
        } else {
            session()->flash('error', 'Produk gagal ditambahkan');
            return redirect(route('admin.products.create'));
        }
    }
    public function edit($id)
    {
        $products = Product::findOrFail($id);
        return view('admin.product.update', compact('products'));
    }
    public function delete($id)
    {
        $products = Product::findOrFail($id)->delete();
        if ($products) {
            session()->flash('success', 'Produk berhasil dihapus');
            return redirect(route('admin.products'));
        } else {
            session()->flash('error', 'Produk gagal dihapus');
            return redirect(route('admin.products'));
        }
    }
    public function update(Request $request, $id)
    {
        $products = Product::findOrFail($id);
        $title = $request->title;
        $category = $request->category;
        $price = $request->price;

        $products->title = $title;
        $products->category = $category;
        $products->price = $price;
        $data = $products->save();
        if ($data) {
            session()->flash('success', 'Produk berhasil di update');
            return redirect(route('admin.products'));
        } else {
            session()->flash('error', 'Produk gagal di update');
            return redirect(route('admin.products.edit', $id));
        }
    }
}