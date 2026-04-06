<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * 🔹 Halaman utama produk (khusus admin)
     */
    public function index()
    {
        // Hanya admin yang boleh akses halaman ini
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('kasir.products.index');
        }

        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * 🔹 Halaman daftar produk (khusus kasir, read-only)
     */
    public function kasirIndex()
    {
        $products = Product::all();
        return view('kasir.products.index', compact('products'));
    }

    /**
     * 🔹 Form tambah produk (admin only)
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * 🔹 Simpan produk baru (admin only)
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * 🔹 Edit produk (admin only)
     */
    public function edit(Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * 🔹 Update produk (admin only)
     */
    public function update(Request $request, Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * 🔹 Hapus produk (admin only)
     */
    public function destroy(Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }

    public function frontendIndex(Request $request)
    {
        $categoryFilter = $request->query('category');
        $search = $request->query('search');
        
        $products = Product::query()
            ->when($categoryFilter, function ($query, $categoryFilter) {
                return $query->where('category_id', $categoryFilter);
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();
        
        $categories = Category::all();
        
        return view('customer.shop', compact('products', 'categories'));
    }


public function frontendShow($id)
{
    $product = Product::findOrFail($id);
    return view('customer.shop.show', compact('product'));
}

public function welcome(Request $request)
{
    // Ambil keyword pencarian dari input form
    $search = $request->query('search');

    // Query produk + fitur search
    $products = Product::query()
        ->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->get();

    return view('welcome', compact('products'));
}


}
