<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        // ambil kategori dari admin/kasir
        $categories = Category::all();

        // produk dengan filter kategori dan search nama
        $products = Product::when($request->category, function ($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->get();

        return view('welcome', compact('categories', 'products'));
    }
}
