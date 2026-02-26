<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('home');
    }
}
