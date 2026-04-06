<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\LateFeeService;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;

class OrderKasirController extends Controller
{
    // Tampilkan semua order online
    public function index()
    {
        $orders = Order::with('user', 'items.product')->latest()->get();
        return view('kasir.orders.index', compact('orders'));
    }

    // Detail order
    public function show(Order $order)
    {
        return view('kasir.orders.show', compact('order'));
    }

    // Cetak struk order
    public function print(Order $order)
    {
        return view('kasir.orders.print', compact('order'));
    }

    // Kasir mulai memproses order
    public function process(Order $order)
    {
        $order->cashier_id = auth()->user()->id;
        $order->status = 'diproses';
        $order->processed_at = now();
        $order->save();

        // Kirim notifikasi ke customer
        if ($order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }

        return redirect()->back()->with('success', 'Order sedang diproses dan customer sudah diberi notifikasi.');
    }

    // Kasir menandai order selesai & siap dikirim
    public function complete(Order $order)
    {
        $order->status = 'siap_dikirim';
        $order->save();

        // Kirim notifikasi ke customer
        if ($order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }

        return redirect()->route('kasir.orders.index')->with('success', 'Order siap dikirim dan customer sudah diberi notifikasi.');
    }

    // Form input tracking number
    public function editTracking(Order $order)
    {
        return view('kasir.orders.tracking', compact('order'));
    }

    // Simpan tracking number dan ubah status menjadi shipped
    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
            'status' => 'shipped',
        ]);

        // Kirim notifikasi ke customer dengan nomor resi
        if ($order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }

        return redirect()->route('kasir.orders.index')->with('success', 'Pesanan berhasil dikirim! Nomor resi: ' . $request->tracking_number);
    }

    // Klaim item yang sudah dikembalikan dengan perhitungan denda
    public function claimReturn(Request $request, Order $order)
    {
        $validated = $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'integer|exists:order_items,id',
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
            $orderItem = OrderItem::where('id', $itemId)
                ->where('order_id', $order->id)
                ->first();
            
            if ($orderItem) {
                // Increment stok produk kembali
                $orderItem->product->increment('stock', $orderItem->quantity);
                
                // Update order item
                $orderItem->update([
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

    // Hitung denda otomatis berdasarkan rental period dan tanggal kembali
    public function calculateLateFee(Request $request, OrderItem $orderItem)
    {
        try {
            $validated = $request->validate([
                'returned_date' => 'required|date',
            ]);

            // Verify item has rental_period
            if (!$orderItem->rental_period) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data rental_period tidak ditemukan untuk item ini'
                ], 400);
            }

            $lateFee = LateFeeService::calculate(
                $orderItem->rental_period,
                $orderItem->price,
                $orderItem->created_at,
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

    // Update status order
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // Kirim notifikasi ke customer
        if ($order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // Konfirmasi pembayaran order
    public function approvePayment(Order $order)
    {
        $order->update([
            'payment_status' => 'confirmed',
            'payment_confirmation_date' => now(),
        ]);

        // Kirim notifikasi ke customer
        if ($order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    // Tolak pembayaran order
    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_notes' => 'required|string|max:500',
        ]);

        $order->update([
            'payment_status' => 'rejected',
            'payment_notes' => $request->payment_notes,
        ]);

        // Kirim notifikasi ke customer
        if ($order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil ditolak dan customer sudah diberi notifikasi.');
    }
}