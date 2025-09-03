<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home.index');
    }

    public function contact()
    {
        return view('frontend.contact'); // resources/views/frontend/contact.blade.php
    }
}
