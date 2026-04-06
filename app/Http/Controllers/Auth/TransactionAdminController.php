<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionAdminController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi kasir dengan items dan product
        $transactions = Transaction::with('user', 'items.product')->latest()->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // Load relasi yang diperlukan
        $transaction->load('user', 'items.product');

        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:paid,pending,cancelled',
        ]);

        $transaction->update($validated);

        return redirect()->route('admin.transactions.show', $transaction->id)
                        ->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
