<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('landing');
    }

    /**
     * Display the About System page.
     *
     * @return \Illuminate\View\View
     */
    public function aboutSystem()
    {
        return view('about-system');
    }
}
