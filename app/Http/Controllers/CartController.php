<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display cart items
     */
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect([]);

        return view('cart.index', [
            'cart' => $cart,
            'cartItems' => $cartItems,
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $product = Product::findOrFail($validated['product_id']);
        
        // Check if product already in cart
        $cartItem = $cart->items()->where('product_id', $validated['product_id'])->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            $cartItem->update([
                'quantity' => $newQuantity,
                'subtotal' => $product->price * $newQuantity
            ]);
        } else {
            $cartItem = $cart->items()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'subtotal' => $product->price * $validated['quantity']
            ]);
        }

        // Cek apakah ini dari "Beli Sekarang" button
        if ($request->has('redirect_to_checkout') && $request->redirect_to_checkout == 1) {
            // Simpan di session bahwa ini quick checkout dengan product_id spesifik
            session(['quick_checkout_product_id' => $validated['product_id'], 'quick_checkout_quantity' => $validated['quantity']]);
            return redirect()->route('checkout.index')->with('success', 'Silakan lanjutkan checkout');
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $product = $cartItem->product;
        
        $cartItem->update([
            'quantity' => $validated['quantity'],
            'subtotal' => $product->price * $validated['quantity']
        ]);

        return redirect()->back()->with('success', 'Keranjang diperbarui');
    }

    /**
     * Remove item from cart
     */
    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->items()->delete();
        }

        return redirect()->back()->with('success', 'Keranjang dikosongkan');
    }
}
