<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * 🔹 Tampilkan halaman login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * 🔹 Proses login dan arahkan user berdasarkan role
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'kasir':
                    return redirect()->intended(route('kasir.dashboard'));
                case 'customer':
                    // Untuk customer, cek apakah ada halaman yang coba diakses sebelum login
                    // Jika ada (contoh: product detail), kembali ke sana. Jika tidak, ke shop
                    return redirect()->intended(route('shop.index'));
                default:
                    Auth::logout();
                    return redirect('/login')->withErrors([
                        'email' => 'Role pengguna tidak dikenali.',
                    ]);
            }
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * 🔹 Logout user
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}
