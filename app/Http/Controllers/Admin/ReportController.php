<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Optional Eloquent models if you have them; otherwise we use Query Builder joins below.
use App\Models\Payment;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $now  = now();
        $year = (int)($request->input('year') ?: $now->year);
        $minYear = $now->year - 4; // last 5 years (incl current)

        /**
         * ------------------------------------------
         * PAYMENTS SUMMARY (Monthly for selected year)
         * ------------------------------------------
         * Definition of "paid": payment_status='completed' AND paid_at not null
         */
        $monthlyRaw = DB::table('payments')
            ->selectRaw('MONTH(paid_at) as month_num, SUM(amount) as total_amount, COUNT(*) as payments_count')
            ->whereYear('paid_at', $year)
            ->where('payment_status', 'completed')
            ->whereNotNull('paid_at')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        // normalize 12 months
        $monthlyPayments = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyPayments[] = [
                'month_num'      => $m,
                'month_label'    => Carbon::createFromDate($year, $m, 1)->format('M'),
                'total_amount'   => (float)($monthlyRaw[$m]->total_amount ?? 0),
                'payments_count' => (int)($monthlyRaw[$m]->payments_count ?? 0),
            ];
        }

        /**
         * ------------------------------------------
         * PAYMENTS SUMMARY (Yearly last 5 years)
         * ------------------------------------------
         */
        $yearlyPayments = DB::table('payments')
            ->selectRaw('YEAR(paid_at) as y, SUM(amount) as total_amount, COUNT(*) as payments_count')
            ->whereYear('paid_at', '>=', $minYear)
            ->where('payment_status', 'completed')
            ->whereNotNull('paid_at')
            ->groupBy('y')
            ->orderBy('y', 'desc')
            ->get();

        /**
         * ------------------------------------------
         * PACKAGES UPTAKE (orders per "package" product per year)
         * ------------------------------------------
         * Package == products.type = 'package'
         * Count distinct orders that include that package product
         * Revenue = SUM(order_items.price * qty)
         */
        $packageRows = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('products as p', 'p.id', '=', 'oi.product_id')
            ->where('p.type', 'package')
            ->whereYear('o.created_at', '>=', $minYear)
            ->selectRaw('YEAR(o.created_at) as y, p.id as package_product_id, p.name as package_name')
            ->selectRaw('COUNT(DISTINCT o.id) as orders_count')
            ->selectRaw('SUM(oi.price * oi.qty) as revenue_sum')
            ->groupBy('y', 'package_product_id', 'package_name')
            ->orderBy('y', 'desc')
            ->get();

        // Build: [year => [package_product_id => {...}]]
        $packageUptakeByYear = [];
        foreach ($packageRows as $r) {
            $packageUptakeByYear[$r->y][$r->package_product_id] = [
                'package_id'   => (int)$r->package_product_id, // product id of the package
                'package_name' => $r->package_name,
                'price'        => null, // optional: fetch p.effective_price or p.price if desired
                'orders_count' => (int)$r->orders_count,
                'revenue_sum'  => (float)$r->revenue_sum,
            ];
        }

        // Top packages THIS selected year (by orders_count)
        $topPackagesThisYear = collect($packageUptakeByYear[$year] ?? [])
            ->sortByDesc('orders_count')
            ->values()
            ->take(10);

        /**
         * ------------------------------------------
         * CUSTOMERS TOTAL
         * ------------------------------------------
         * Users joined to roles.role_name = 'user' (adjust if your role_name differs e.g., 'customer')
         */
        $totalCustomers = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('roles.role_name', 'user') // change to 'customer' if that's your naming
            ->count();

        // Year filter range (last 10 years)
        $yearsForFilter = range($now->year, $now->year - 9);

        return view('admin.reports.index', [
            'selectedYear'        => $year,
            'yearsForFilter'      => $yearsForFilter,

            'monthlyPayments'     => $monthlyPayments,
            'yearlyPayments'      => $yearlyPayments,

            'packageUptakeByYear' => $packageUptakeByYear,
            'topPackagesThisYear' => $topPackagesThisYear,

            'totalCustomers'      => $totalCustomers,
        ]);
    }
}
