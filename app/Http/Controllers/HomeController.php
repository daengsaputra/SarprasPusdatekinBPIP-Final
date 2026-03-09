<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\SiteSetting;

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
        $videoMeta = SiteSetting::landingVideoMeta();

        $availableAssetsQuery = Asset::query()
            ->where('kind', Asset::KIND_LOANABLE)
            ->where('status', 'active')
            ->where('quantity_available', '>', 0);

        $availableAssets = (clone $availableAssetsQuery)
            ->orderByDesc('quantity_available')
            ->orderBy('name')
            ->limit(12)
            ->get();

        $availableUnits = (int) (clone $availableAssetsQuery)->sum('quantity_available');

        $activeLoansQuery = Loan::query()
            ->with('asset:id,name,code,photo')
            ->whereIn('status', ['borrowed', 'partial']);

        $activeLoans = (clone $activeLoansQuery)
            ->orderByDesc('loan_date')
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        $inUseUnits = (int) (clone $activeLoansQuery)
            ->selectRaw('COALESCE(SUM(CASE WHEN quantity > COALESCE(quantity_returned, 0) THEN quantity - COALESCE(quantity_returned, 0) ELSE 0 END), 0) as total')
            ->value('total');

        return view('landing', [
            'landingVideoUrl' => $videoMeta['url'],
            'landingVideoMime' => $videoMeta['mime'],
            'summaryData' => [
                'available' => $availableUnits,
                'in_use' => $inUseUnits,
            ],
            'availableAssets' => $availableAssets,
            'activeLoans' => $activeLoans,
        ]);
    }

    /**
     * Menampilkan halaman dashboard
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }
}
