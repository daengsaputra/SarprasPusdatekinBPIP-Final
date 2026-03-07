<?php

namespace App\Http\Controllers;

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

        return view('landing', [
            'landingVideoUrl' => $videoMeta['url'],
            'landingVideoMime' => $videoMeta['mime'],
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
