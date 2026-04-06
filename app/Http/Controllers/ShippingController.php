<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $shippings = Shipping::all();
        return view('shippings.index', compact('shippings'));
    }

    public function create()
    {
        return view('shippings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'cost' => 'required|numeric|min:0',
            'estimated_days' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        Shipping::create($request->only('name', 'cost', 'estimated_days', 'description'));

        return redirect()->route('shippings.index')->with('success', 'Data pengiriman berhasil ditambahkan!');
    }

    public function edit(Shipping $shipping)
    {
        return view('shippings.edit', compact('shipping'));
    }

    public function update(Request $request, Shipping $shipping)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'cost' => 'required|numeric|min:0',
            'estimated_days' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $shipping->update($request->only('name', 'cost', 'estimated_days', 'description'));

        return redirect()->route('shippings.index')->with('success', 'Data pengiriman berhasil diperbarui!');
    }

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return redirect()->route('shippings.index')->with('success', 'Data pengiriman berhasil dihapus!');
    }
}
