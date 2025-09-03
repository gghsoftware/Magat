<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // You can pass data (like total users, orders, etc.) here
        return view('admin.dashboard');
    }
}
