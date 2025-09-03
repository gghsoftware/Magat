<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FrontendController extends Controller
{
    public function home()
    {
        return view('frontend.home');
    }

    public function products()
    {
        $products = Product::all(); // example: fetch from DB
        return view('frontend.products', compact('products'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }
}
