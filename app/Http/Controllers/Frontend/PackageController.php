<?php

// app/Http/Controllers/Frontend/PackageController.php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function __construct()
    {
        // Only these pages require login:
        $this->middleware('auth')->only(['show', 'quote', 'payment', 'submit']);
    }

    public function index()
    {
        $packages = Package::where('is_active', true)->orderBy('price')->get();
        return view('frontend.packages.index', compact('packages'));
    }

    public function show(Package $package)
    {
        $isNinety   = (int)$package->price === 90000;
        $canUpgrade = (int)$package->price <= 90000;

        return view('frontend.packages.show', compact('package', 'isNinety', 'canUpgrade'));
    }

    // app/Http/Controllers/Frontend/PackageController.php

    public function quote(Request $request, Package $package)
    {
        $isNinety   = (int)$package->price === 90000;
        $canUpgrade = (int)$package->price <= 90000;

        $data = $request->validate([
            'garden_upgrade'          => ['nullable', 'boolean'],
            'addons'                  => ['array'],
            'addons.*'                => ['in:chairs,tables,tent'],

            'items'                   => ['array'], // normalized inclusions
            'items.*.checked'         => ['nullable', 'boolean'],
            'items.*.label'           => ['required', 'string', 'max:200'],
            'items.*.qty'             => ['required', 'integer', 'min:1', 'max:10000'],

            'custom_items'            => ['array'],
            'custom_items.*.label'    => ['required', 'string', 'max:200'],
            'custom_items.*.qty'      => ['required', 'integer', 'min:1', 'max:10000'],

            'summary'                 => ['nullable', 'string', 'max:5000'],
        ]);

        // Filter to only checked inclusions
        $items = collect($data['items'] ?? [])
            ->filter(fn($i) => !empty($i['checked']))
            ->map(fn($i) => ['label' => trim($i['label']), 'qty' => (int)$i['qty']])
            ->values()
            ->all();

        $customItems = collect($data['custom_items'] ?? [])
            ->map(fn($i) => ['label' => trim($i['label']), 'qty' => (int)$i['qty']])
            ->values()
            ->all();

        $gardenUpgrade = $canUpgrade && $request->boolean('garden_upgrade');
        $addons        = $isNinety ? (array)($data['addons'] ?? []) : [];

        // Pricing per rules
        $base     = (int)$package->price + ($gardenUpgrade ? 20000 : 0);
        $subtotal = $base; // items & custom items & 90k addons are not priced here (subject to quote)
        $discount = 0;
        $total    = $subtotal;
        $dueNow   = (int) ceil($total * 0.30);
        $balance  = $total - $dueNow;

        return view('frontend.packages.quote', [
            'package'       => $package,
            'isNinety'      => $isNinety,
            'canUpgrade'    => $canUpgrade,
            'gardenUpgrade' => $gardenUpgrade,
            'addons'        => $addons,

            'items'         => $items,
            'customItems'   => $customItems,
            'summary'       => $data['summary'] ?? '',

            'subtotal'      => $subtotal,
            'discount'      => $discount,
            'total'         => $total,
            'payment'       => 'dp30',
            'dueNow'        => $dueNow,
            'balance'       => $balance,

            'senior'        => false,
        ]);
    }

    public function submit(Request $request, Package $package)
    {
        $isNinety   = (int)$package->price === 90000;
        $canUpgrade = (int)$package->price <= 90000;

        $data = $request->validate([
            'garden_upgrade'          => ['nullable', 'boolean'],
            'addons'                  => ['array'],
            'addons.*'                => ['in:chairs,tables,tent'],

            'items'                   => ['array'],
            'items.*.label'           => ['required', 'string', 'max:200'],
            'items.*.qty'             => ['required', 'integer', 'min:1', 'max:10000'],

            'custom_items'            => ['array'],
            'custom_items.*.label'    => ['required', 'string', 'max:200'],
            'custom_items.*.qty'      => ['required', 'integer', 'min:1', 'max:10000'],

            'summary'                 => ['nullable', 'string', 'max:5000'],

            'senior'                  => ['nullable', 'boolean'],
            // ✅ required only if senior is checked
            'senior_id'               => ['nullable', 'required_if:senior,1', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'payment'                 => ['required', 'in:full,dp30'],

            'name'                    => ['required', 'string', 'max:120'],
            'phone'                   => ['required', 'string', 'max:30'],
            'email'                   => ['nullable', 'email', 'max:120'],
            'notes'                   => ['nullable', 'string', 'max:2000'],
        ]);

        $items = collect($data['items'] ?? [])
            ->map(fn($i) => ['label' => trim($i['label']), 'qty' => (int)$i['qty']])
            ->all();

        $customItems = collect($data['custom_items'] ?? [])
            ->map(fn($i) => ['label' => trim($i['label']), 'qty' => (int)$i['qty']])
            ->all();

        $gardenUpgrade = $canUpgrade && $request->boolean('garden_upgrade');
        $addons        = $isNinety ? (array)($data['addons'] ?? []) : [];

        $base     = (int)$package->price + ($gardenUpgrade ? 20000 : 0);
        $subtotal = $base;
        $discount = $request->boolean('senior') ? (int) round($subtotal * 0.20) : 0;
        $total    = max(0, $subtotal - $discount);

        $payment  = $data['payment'];
        $dueNow   = $payment === 'dp30' ? (int) ceil($total * 0.30) : $total;
        $balance  = $total - $dueNow;

        // ✅ Save the Senior ID image if provided
        $seniorIdPath = null;
        if ($request->boolean('senior') && $request->hasFile('senior_id')) {
            // stores to storage/app/public/senior_ids (run: php artisan storage:link once)
            $seniorIdPath = $request->file('senior_id')->store('senior_ids', 'public');
        }

        // TODO: persist inquiry/order, include $seniorIdPath if needed.

        return back()->with('success', 'Your request has been submitted. We will contact you shortly.');
    }
}
