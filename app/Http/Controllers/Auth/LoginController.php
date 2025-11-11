<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Menangani permintaan login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentukan field untuk login (bisa email atau name)
        $loginField = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $authData = [
            $loginField => $credentials['identifier'],
            'password' => $credentials['password'],
        ];

        // Coba untuk autentikasi
        if (Auth::attempt($authData, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Jika gagal, kembali dengan pesan error
        return back()->withErrors([
            'identifier' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('identifier');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}
