<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Total transaksi hari ini
        $totalHariIni = Transaction::whereDate('created_at', $today)->count();

        // Transaksi sukses hari ini
        $suksesHariIni = Transaction::whereDate('created_at', $today)
                        ->where('status', 'success')
                        ->count();

        // Transaksi pending hari ini
        $pendingHariIni = Transaction::whereDate('created_at', $today)
                        ->where('status', 'pending')
                        ->count();

        // Data grafik 7 hari
        $revenueData = $this->getRevenueData();

        return view('kasir.dashboard', compact(
            'totalHariIni', 
            'suksesHariIni',
            'pendingHariIni',
            'revenueData'
        ));
    }

    /**
     * Ambil data revenue 7 hari terakhir
     */
    private function getRevenueData()
    {
        $sevenDaysAgo = Carbon::now()->subDays(6);
        $dates = [];
        $onlineRevenue = [];
        $offlineRevenue = [];
        $lateFeesRevenue = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $sevenDaysAgo->copy()->addDays($i);
            $dateFormatted = $date->format('Y-m-d');
            $dateLabel = $date->format('d/m');
            
            $dates[] = $dateLabel;

            // ONLINE REVENUE (dari Order yang berhasil dibayar)
            $online = Order::whereDate('created_at', $dateFormatted)
                ->where('payment_status', 'approved')
                ->sum('total_price');

            // OFFLINE REVENUE (dari Transaction offline yang sukses)
            $offline = Transaction::whereDate('created_at', $dateFormatted)
                ->where('status', 'success')
                ->sum('total');

            // LATE FEES REVENUE (denda yang sudah dibayar)
            $lateFees = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.late_fee_paid', true)
                ->whereDate('order_items.late_fee_paid_date', $dateFormatted)
                ->sum('order_items.late_fee');

            $lateFees += DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->where('transaction_items.late_fee_paid', true)
                ->whereDate('transaction_items.late_fee_paid_date', $dateFormatted)
                ->sum('transaction_items.late_fee');

            $onlineRevenue[] = (int)$online;
            $offlineRevenue[] = (int)$offline;
            $lateFeesRevenue[] = (int)$lateFees;
        }

        return [
            'dates' => $dates,
            'online' => $onlineRevenue,
            'offline' => $offlineRevenue,
            'lateFees' => $lateFeesRevenue,
        ];
    }
}
