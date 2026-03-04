<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('root');
    }

    /**
     * Menampilkan halaman utama
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function root()
    {
        return view('landing');  // Ganti dengan nama view yang sesuai, misalnya 'home'
    }

    /**
     * Menampilkan halaman dashboard
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalBarang = (int) Asset::sum('quantity_total');
        $totalBarangAset = (int) Asset::where('kind', Asset::KIND_INVENTORY)->sum('quantity_total');
        $totalPeminjaman = (int) Loan::count();
        $totalPengembalian = (int) Loan::where('quantity_returned', '>', 0)->count();
        $totalDipinjamHistoris = (int) Loan::sum('quantity');
        $totalBarangKembali = (int) Loan::sum('quantity_returned');
        $totalBarangDipinjam = max($totalDipinjamHistoris - $totalBarangKembali, 0);

        $asetPercent = $totalBarang > 0 ? (int) round(($totalBarangAset / $totalBarang) * 100) : 0;
        $dipinjamPercent = $totalBarang > 0 ? (int) round(($totalBarangDipinjam / $totalBarang) * 100) : 0;
        $kembaliPercent = $totalDipinjamHistoris > 0
            ? (int) round(($totalBarangKembali / $totalDipinjamHistoris) * 100)
            : 0;

        return view('home', compact(
            'totalBarang',
            'totalBarangAset',
            'totalPeminjaman',
            'totalPengembalian',
            'totalBarangDipinjam',
            'totalBarangKembali',
            'asetPercent',
            'dipinjamPercent',
            'kembaliPercent'
        ));
    }
}
