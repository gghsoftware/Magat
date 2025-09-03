<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        return view('frontend.account.dashboard', compact('user'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders ?? []; // if you have relationship
        return view('frontend.account.orders', compact('orders'));
    }
}
