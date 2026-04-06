<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\TransactionItem;
use App\Models\Transaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts     = Product::count();
        $totalUsers        = User::count();
        $totalTransactions = Transaction::count();
        $totalOrders       = Order::count();

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd   = Carbon::today()->endOfDay();
        $sevenDaysAgoStart = Carbon::today()->subDays(6)->startOfDay();

        // Income hari ini
        $offlineIncomeToday = Transaction::whereBetween('created_at', [$todayStart, $todayEnd])->sum('total');
        $onlineIncomeToday  = Order::whereBetween('created_at', [$todayStart, $todayEnd])->sum('total_price');

        $lateFeeIncomeToday =
            OrderItem::whereBetween('returned_at', [$todayStart, $todayEnd])->sum('late_fee')
            + TransactionItem::whereBetween('returned_at', [$todayStart, $todayEnd])->sum('late_fee');

        // =============================
        // DATA GRAFIK 7 HARI
        // =============================

        $offlineIncomeByDay = Transaction::select(
                DB::raw('DATE(created_at) as creation_date'),
                DB::raw('SUM(total) as total_income')
            )
            ->where('created_at', '>=', $sevenDaysAgoStart)
            ->groupBy('creation_date')
            ->pluck('total_income', 'creation_date');

        $onlineIncomeByDay = Order::select(
                DB::raw('DATE(created_at) as creation_date'),
                DB::raw('SUM(total_price) as total_income')
            )
            ->where('created_at', '>=', $sevenDaysAgoStart)
            ->groupBy('creation_date')
            ->pluck('total_income', 'creation_date');

        $orderFeeData = OrderItem::select(
                DB::raw('DATE(returned_at) as return_date'),
                DB::raw('SUM(late_fee) as total_fees')
            )
            ->where('returned_at', '>=', $sevenDaysAgoStart)
            ->groupBy('return_date')
            ->pluck('total_fees', 'return_date');

        $transactionFeeData = TransactionItem::select(
                DB::raw('DATE(returned_at) as return_date'),
                DB::raw('SUM(late_fee) as total_fees')
            )
            ->where('returned_at', '>=', $sevenDaysAgoStart)
            ->groupBy('return_date')
            ->pluck('total_fees', 'return_date');

        // Gabungkan fee
        $combinedFeeData = $orderFeeData;

        foreach ($transactionFeeData as $date => $fee) {
            $combinedFeeData[$date] = ($combinedFeeData[$date] ?? 0) + $fee;
        }

        // =============================
        // BUILD CHART
        // =============================

        $chartLabels = [];
        $offlineData = [];
        $onlineData  = [];
        $lateFeeData = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);
            $dateString = $date->toDateString();

            $chartLabels[] = $date->format('D');

            $offlineData[] = $offlineIncomeByDay[$dateString] ?? 0;
            $onlineData[]  = $onlineIncomeByDay[$dateString] ?? 0;
            $lateFeeData[] = $combinedFeeData[$dateString] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers',
            'totalTransactions',
            'totalOrders',
            'offlineIncomeToday',
            'onlineIncomeToday',
            'lateFeeIncomeToday',
            'chartLabels',
            'offlineData',
            'onlineData',
            'lateFeeData'
        ));
    }
}