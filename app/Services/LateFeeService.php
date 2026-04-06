<?php

namespace App\Services;

use Carbon\Carbon;

class LateFeeService
{
    /**
     * Hitung denda keterlambatan berdasarkan rental period dan tanggal kembali actual
     * 
     * @param int $rentalPeriodHours - Periode sewa dalam jam (24, 48, 72)
     * @param float $pricePerDay - Harga per 24 jam
     * @param Carbon|string $createdAt - Tanggal sewa dimulai
     * @param Carbon|string $actualReturnDate - Tanggal kembali actual
     * @return float - Nominal denda
     */
    public static function calculate($rentalPeriodHours, $pricePerDay, $createdAt, $actualReturnDate)
    {
        $createdAt = Carbon::parse($createdAt);
        $actualReturnDate = Carbon::parse($actualReturnDate);
        
        // Duedate = createdAt + rental period
        $dueDate = $createdAt->copy()->addHours($rentalPeriodHours);
        
        // Jika terlambat hitung denda
        if ($actualReturnDate > $dueDate) {
            // Hitung berapa jam terlambat
            $lateHours = $dueDate->diffInHours($actualReturnDate, false);
            
            // Bulatkan ke atas per 24 jam
            $lateDays = ceil($lateHours / 24);
            
            // Hitung denda
            $lateFee = $lateDays * $pricePerDay;
            
            return $lateFee;
        }
        
        return 0;
    }
    
    /**
     * Format denda dengan nilai minimal (jika ada)
     */
    public static function formatFee($lateFee)
    {
        return (int)$lateFee;
    }
}
