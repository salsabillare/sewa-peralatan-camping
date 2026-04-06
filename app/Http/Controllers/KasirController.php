<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    /**
     * Tampilkan dashboard kasir
     */
    public function index()
    {
        // Nanti bisa kirim data transaksi / statistik
        return view('kasir.dashboard');
    }
}
