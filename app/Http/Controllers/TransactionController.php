<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Order;
use App\Models\TransactionItem;
use App\Services\LateFeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // ==============================
    // List transaksi kasir
    // ==============================
    public function index()
    {
        $transactions = Transaction::with(['items.product', 'user'])->latest()->get();
        return view('kasir.transactions.index', compact('transactions'));
    }

    // ==============================
    // Form tambah transaksi
    // ==============================
    public function create()
    {
        $products = Product::all();
        return view('kasir.transactions.create', compact('products'));
    }

    // ==============================
    // Simpan transaksi baru
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
            'payment' => 'nullable|integer|min:0',
        ]);

        $transactionCode = 'TRX-' . strtoupper(uniqid());
        $total = 0;

        // Simpan transaksi utama
        $transaction = Transaction::create([
            'transaction_code' => $transactionCode,
            'user_id' => Auth::id(),
            'total' => 0,
            'payment' => $request->payment ?? 0,
            'change' => 0,
            'status' => 'paid',
        ]);

        foreach ($request->products as $itemData) {
            $product = Product::findOrFail($itemData['product_id']);
            $subtotal = $product->price * $itemData['quantity'];
            $total += $subtotal;

            // Kurangi stok
            $product->decrement('stock', $itemData['quantity']);

            // Simpan item
            $transaction->items()->create([
                'product_id' => $product->id,
                'quantity'   => $itemData['quantity'],
                'price'      => $product->price,
                'subtotal'   => $subtotal,
            ]);
        }

        // Hitung kembalian (status selalu paid)
        $payment = $request->payment ?? 0;
        $change = ($payment >= $total) ? $payment - $total : 0;

        $transaction->update([
            'total' => $total,
            'payment' => $payment,
            'change' => $change,
            'status' => 'paid',
        ]);

        return redirect()->route('kasir.transactions.index')
                         ->with('success', 'Transaksi berhasil disimpan dan muncul di halaman admin.');
    }

    // ==============================
    // Edit transaksi
    // ==============================
    public function edit(Transaction $transaction)
    {
        $products = Product::all();
        $transaction->load('items.product'); // ambil items
        return view('kasir.transactions.edit', compact('transaction', 'products'));
    }

    // ==============================
    // Update transaksi multi-produk
    // ==============================
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
            'payment' => 'nullable|integer|min:0',
        ]);

        $total = 0;

        // Kembalikan stok lama
        foreach ($transaction->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        // Hapus items lama
        $transaction->items()->delete();

        // Tambahkan item baru
        foreach ($request->products as $itemData) {
            $product = Product::findOrFail($itemData['product_id']);
            $subtotal = $product->price * $itemData['quantity'];
            $total += $subtotal;

            $product->decrement('stock', $itemData['quantity']);

            $transaction->items()->create([
                'product_id' => $product->id,
                'quantity'   => $itemData['quantity'],
                'price'      => $product->price,
                'subtotal'   => $subtotal,
            ]);
        }

        // Hitung kembalian (status selalu paid)
        $payment = $request->payment ?? 0;
        $change = ($payment >= $total) ? $payment - $total : 0;

        $transaction->update([
            'total'  => $total,
            'payment'=> $payment,
            'change' => $change,
            'status' => 'paid',
        ]);

        return redirect()->route('kasir.transactions.index')
                         ->with('success', 'Transaksi berhasil diperbarui.');
    }

    // ==============================
    // Hapus transaksi
    // ==============================
    public function destroy(Transaction $transaction)
    {
        // Stok dikembalikan otomatis karena di model booted()
        $transaction->delete();
        return redirect()->route('kasir.transactions.index')
                         ->with('success', 'Transaksi berhasil dihapus.');
    }

    // ==============================
    // Detail transaksi
    // ==============================
    public function show(Transaction $transaction)
    {
        $transaction->load('items.product');
        return view('kasir.transactions.show', compact('transaction'));
    }

    // ==============================
    // Cetak struk transaksi
    // ==============================
    public function print(Transaction $transaction)
    {
        $transaction->load('items.product');
        return view('kasir.transactions.print', compact('transaction'));
    }

    // ==============================
    // Klaim item yang sudah dikembalikan dengan perhitungan denda
    // ==============================
    public function claimReturn(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'integer|exists:transaction_items,id',
            'late_fees' => 'array',
            'late_fees.*' => 'numeric|min:0',
            'returned_dates' => 'array',
            'returned_dates.*' => 'date',
        ]);

        $itemIds = $validated['item_ids'];
        $lateFees = $request->late_fees ?? [];
        $returnedDates = $request->returned_dates ?? [];
        
        // Update returned_at, late_fee, dan actual_returned_date untuk item-item yang diklaim
        foreach ($itemIds as $itemId) {
            $transactionItem = TransactionItem::where('id', $itemId)
                ->where('transaction_id', $transaction->id)
                ->first();
            
            if ($transactionItem) {
                // Increment stok produk kembali
                $transactionItem->product->increment('stock', $transactionItem->quantity);
                
                // Update transaction item
                $transactionItem->update([
                    'returned_at' => now(),
                    'actual_returned_date' => $returnedDates[$itemId] ?? now(),
                    'late_fee' => isset($lateFees[$itemId]) ? (int)$lateFees[$itemId] : 0,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengembalian barang dan denda berhasil dicatat. Stok produk sudah bertambah.'
        ]);
    }

    // ==============================
    // Hitung denda otomatis berdasarkan rental period dan tanggal kembali
    // ==============================
    public function calculateLateFee(Request $request, TransactionItem $transactionItem)
    {
        try {
            $validated = $request->validate([
                'returned_date' => 'required|date',
            ]);

            // Verify item has rental_period
            if (!$transactionItem->rental_period) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data rental_period tidak ditemukan untuk item ini'
                ], 400);
            }

            $lateFee = LateFeeService::calculate(
                $transactionItem->rental_period,
                $transactionItem->price,
                $transactionItem->created_at,
                $validated['returned_date']
            );

            return response()->json([
                'success' => true,
                'late_fee' => (int)$lateFee,
                'formatted' => 'Rp ' . number_format((int)$lateFee, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
