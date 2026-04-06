<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Shipping;
use App\Services\DistanceService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        $users    = User::all();

        return view('admin.orders.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required',
            'product_id'  => 'required',
            'qty'         => 'required|numeric|min:1',
            'status'      => 'required',
        ]);

        $product = Product::find($request->product_id);

        Order::create([
            'user_id'     => $request->user_id,
            'product_id'  => $request->product_id,
            'qty'         => $request->qty,
            'total_price' => $product->price * $request->qty,
            'status'      => $request->status,
        ]);

        return redirect()->route('orders.index')
                         ->with('success', 'Order berhasil ditambahkan.');
    }

    public function show(Order $order)
    {
        // Jika customer, pastikan order miliknya
        if (auth()->check() && auth()->user()->role === 'customer') {
            if ($order->user_id !== auth()->id()) {
                abort(403, 'Anda tidak memiliki akses ke order ini.');
            }
            $order->load('items.product');
            return view('customer.orders.show', compact('order'));
        }
        
        // Untuk admin
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $products = Product::all();
        $users = User::all();

        return view('admin.orders.edit', compact('order', 'products', 'users'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user_id'     => 'required',
            'product_id'  => 'required',
            'qty'         => 'required|numeric|min:1',
            'status'      => 'required',
        ]);

        $product = Product::find($request->product_id);

        $order->update([
            'user_id'     => $request->user_id,
            'product_id'  => $request->product_id,
            'qty'         => $request->qty,
            'total_price' => $product->price * $request->qty,
            'status'      => $request->status,
        ]);

        return redirect()->route('orders.index')
                         ->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
                         ->with('success', 'Order berhasil dihapus.');
    }

    // Method untuk update status pesanan dari halaman show order
    public function updateStatus(Request $request, Order $order)
    {
        // Authorization check
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        try {
            $order->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();
        
        // Cek apakah ini quick checkout dari "Beli Sekarang"
        $quickCheckoutProductId = session('quick_checkout_product_id');
        $quickCheckoutQuantity = session('quick_checkout_quantity', 1);
        
        if ($quickCheckoutProductId) {
            // Quick checkout - hanya product yang dipilih
            $product = Product::findOrFail($quickCheckoutProductId);
            
            // Validasi stok
            if ($quickCheckoutQuantity > $product->stock) {
                return redirect()->back()->withErrors("Stok {$product->name} tidak mencukupi.");
            }
            
            // Hitung total
            $totalPrice = $product->price * $quickCheckoutQuantity;
            
            // Buat mock object untuk compatibility dengan view
            $cartItems = collect([
                (object)[
                    'id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quickCheckoutQuantity,
                    'price' => $product->price,
                    'subtotal' => $totalPrice,
                    'product' => $product,
                ]
            ]);
            
        } else {
            // Regular checkout dari cart
            $cart = $user->cart()->first();

            if (!$cart || $cart->items()->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
            }

            // Validasi stok
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    return redirect()->back()->withErrors("Stok {$item->product->name} tidak mencukupi.");
                }
            }

            // Hitung total
            $totalPrice = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            
            $cartItems = $cart->items;
            $cart = $cart;
        }

        // Hitung jarak customer
        $userAddress = $user->address ?? '';
        $distance = null;
        if ($userAddress) {
            $distance = DistanceService::getDistanceFromAddress($userAddress);
        }

        // Ambil semua opsi pengiriman
        $shippings = Shipping::all();

        return view('checkout.index', [
            'cart' => $cart ?? null,
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'user' => $user,
            'distance' => $distance,
            'shippings' => $shippings,
            'isQuickCheckout' => (bool)$quickCheckoutProductId,
        ]);
    }

    public function processCheckout(Request $request)
    {
        $user = auth()->user();
        
        // Cek apakah ini quick checkout
        $quickCheckoutProductId = session('quick_checkout_product_id');
        $quickCheckoutQuantity = session('quick_checkout_quantity', 1);

        // Validasi dengan shipping_id
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'guarantee_type' => 'required|in:ktp,sim,passport,kartu_pelajar,lainnya',
            'guarantee_number' => 'required|string',
            'payment_method' => 'required|in:transfer,cash',
            'shipping_id' => 'required|exists:shippings,id',
        ]);

        // Ambil shipping yang dipilih
        $shipping = Shipping::findOrFail($request->shipping_id);

        // Update profil user jika ada perubahan
        $user->update([
            'name' => $request->name ?? $user->name,
            'address' => $request->address ?? $user->address,
            'phone' => $request->phone ?? $user->phone,
        ]);

        if ($quickCheckoutProductId) {
            // Quick checkout - dari "Beli Sekarang"
            $product = Product::findOrFail($quickCheckoutProductId);
            
            // Validasi stok
            if ($quickCheckoutQuantity > $product->stock) {
                return redirect()->back()->withErrors("Stok {$product->name} tidak mencukupi.");
            }

            $subtotal = $product->price * $quickCheckoutQuantity;
            
        } else {
            // Regular checkout - dari cart
            $cart = $user->cart()->first();

            if (!$cart || $cart->items()->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
            }

            // Validasi stok akhir
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    return redirect()->back()->withErrors("Stok {$item->product->name} tidak mencukupi.");
                }
            }

            $subtotal = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
        }

        // Hitung total dengan ongkir
        $totalPrice = $subtotal + $shipping->cost;

        // Buat order dengan status pending (menunggu pembayaran dari customer)
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $totalPrice,  // Total sudah termasuk ongkir
            'status' => 'pending',
            'payment_method' => $request->payment_method ?? 'transfer',
            'address' => $user->address,
            'shipping_cost' => $shipping->cost,
            'shipping_cost_confirmed' => true,  // Sudah dikonfirmasi customer
            'guarantee_type' => $request->guarantee_type,
            'guarantee_number' => $request->guarantee_number,
        ]);

        // Buat order items dan kurangi stok
        if ($quickCheckoutProductId) {
            // Quick checkout - satu product
            $product = Product::findOrFail($quickCheckoutProductId);
            $itemSubtotal = $product->price * $quickCheckoutQuantity;
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quickCheckoutQuantity,
                'price' => $product->price,
                'subtotal' => $itemSubtotal,
            ]);

            $product->decrement('stock', $quickCheckoutQuantity);
            
            // Clear session quick checkout
            session()->forget(['quick_checkout_product_id', 'quick_checkout_quantity']);
            
        } else {
            // Regular checkout - dari cart
            foreach ($cart->items as $item) {
                $itemSubtotal = $item->product->price * $item->quantity;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $itemSubtotal,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // Kosongkan keranjang
            $cart->items()->delete();
        }

        return redirect()->route('customer.orders.index')
            ->with('success', 'Pesanan berhasil dibuat! Mohon segera lakukan pembayaran.');
    }

    /**
     * Tampilkan pesanan customer
     */
    public function myOrders()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Customer confirm pesanan sudah diterima
     */
    public function confirmDelivery(Order $order)
    {
        // Pastikan order milik customer yang login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        // Cek status order harus "shipped" sebelum bisa dikonfirmasi diterima
        if ($order->status !== 'shipped') {
            return redirect()->back()->with('error', 'Pesanan belum dikirim atau sudah dikonfirmasi sebelumnya.');
        }

        // Update status menjadi "delivered"
        $order->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        // Notifikasi ke admin/kasir bahwa pesanan sudah diterima
        if ($order->user) {
            // Bisa tambah notifikasi ke admin di sini jika diperlukan
        }

        return redirect()->back()->with('success', 'Terima kasih! Pesanan Anda telah dikonfirmasi diterima.');
    }

    /**
     * Konfirmasi ongkos kirim oleh kasir
     */
    public function confirmShippingCost(Request $request, Order $order)
    {
        // Cek hanya kasir yang bisa akses
        if (auth()->user()->role !== 'kasir') {
            abort(403, 'Hanya kasir yang dapat mengkonfirmasi ongkos kirim.');
        }

        // Validasi input
        $request->validate([
            'admin_shipping_cost' => 'required|numeric|min:0',
            'estimated_delivery_days' => 'required|numeric|min:1',
        ]);

        // Hitung total akhir dengan shipping cost
        $totalWithShipping = $order->total_price + $request->admin_shipping_cost;

        // Update order dengan shipping cost dan set status confirmed
        $order->update([
            'admin_shipping_cost' => $request->admin_shipping_cost,
            'shipping_cost' => $request->admin_shipping_cost,  // Sinkronisasi ke shipping_cost
            'shipping_cost_confirmed' => true,
            'shipping_cost_confirmed_at' => now(),
            'total_price' => $totalWithShipping,  // Update total dengan ongkir
            'estimated_delivery_date' => now()->addDays((int)$request->estimated_delivery_days),
        ]);

        return redirect()->back()->with('success', 'Ongkos kirim sebesar Rp ' . number_format($request->admin_shipping_cost, 0, ',', '.') . ' telah dikonfirmasi. Total pembayaran final: Rp ' . number_format($totalWithShipping, 0, ',', '.'));
    }

    /**
     * Approve payment dari customer
     */
    public function approvePayment(Request $request, Order $order)
    {
        try {
            // Cek hanya admin yang bisa akses
            if (!auth()->check()) {
                return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
            }
            
            if (auth()->user()->role !== 'admin') {
                return redirect()->back()->with('error', 'Hanya admin yang dapat mengkonfirmasi pembayaran.');
            }

            // Update payment status menjadi confirmed
            $order->update([
                'payment_status' => 'confirmed',
                'payment_confirmation_date' => now(),
            ]);

            return redirect()->back()->with('success', 'Pembayaran dari ' . $order->user->name . ' telah dikonfirmasi. Total: Rp ' . number_format($order->total_price, 0, ',', '.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject payment dari customer
     */
    public function rejectPayment(Request $request, Order $order)
    {
        try {
            // Cek hanya admin yang bisa akses
            if (!auth()->check()) {
                return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
            }
            
            if (auth()->user()->role !== 'admin') {
                return redirect()->back()->with('error', 'Hanya admin yang dapat menolak pembayaran.');
            }

            // Validasi input
            $request->validate([
                'payment_notes' => 'required|string|min:5|max:500',
            ]);

            // Update payment status menjadi rejected dengan notes
            $order->update([
                'payment_status' => 'rejected',
                'payment_notes' => $request->payment_notes,
            ]);

            return redirect()->back()->with('warning', 'Pembayaran dari ' . $order->user->name . ' telah ditolak. Alasan: ' . $request->payment_notes);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}