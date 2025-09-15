{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'WELCOME ADMIN !')

@section('content')
    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 text-green-800 px-4 py-3 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 text-red-800 px-4 py-3 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- Intro --}}
    <p class="text-gray-700 mb-6">
        Welcome back! Here's a quick snapshot of your store activity.
    </p>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <h3 class="font-medium text-gray-600">Total Users</h3>
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700">👤</span>
            </div>
            <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($stats['total_users'] ?? 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">All registered accounts</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <h3 class="font-medium text-gray-600">Orders</h3>
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700">🧾</span>
            </div>
            <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($stats['orders'] ?? 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total orders on record</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <h3 class="font-medium text-gray-600">Revenue</h3>
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700">💸</span>
            </div>
            <p class="text-3xl font-bold mt-2 text-gray-900">₱{{ number_format($stats['revenue'] ?? 0, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Sum of completed payments</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <h3 class="font-medium text-gray-600">Messages</h3>
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700">✉️</span>
            </div>
            <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($stats['messages'] ?? 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">Unread inquiries</p>
        </div>
    </div>

    {{-- Monthly Summary Chart --}}
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold mb-4 text-green-700">Monthly Summary</h3>
            <span class="text-xs text-gray-500">Based on {{ isset($monthly) && $monthly->count() ? 'actual data' : 'available records' }}</span>
        </div>
        <div class="h-64">
            <canvas id="monthlyChart" aria-label="Monthly Summary Chart"></canvas>
        </div>
    </div>

    {{-- Payments (Verification) --}}
    <div class="bg-white p-6 rounded-xl shadow-md mt-6">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h3 class="text-lg font-semibold text-green-700">
                Payments {{ $supportsVerification ? '(Verification)' : '' }}
            </h3>

            @if($supportsVerification)
                <div class="flex items-center gap-2 text-sm">
                    @php $v = $filter ?? 'all'; @endphp
                    <a href="{{ url()->current() }}?v=all"
                       class="px-3 py-1 rounded border {{ $v==='all' ? 'bg-green-600 text-white border-green-600' : 'text-gray-700 hover:bg-gray-50' }}">All</a>
                    <a href="{{ url()->current() }}?v=pending"
                       class="px-3 py-1 rounded border {{ $v==='pending' ? 'bg-green-600 text-white border-green-600' : 'text-gray-700 hover:bg-gray-50' }}">Pending</a>
                    <a href="{{ url()->current() }}?v=approved"
                       class="px-3 py-1 rounded border {{ $v==='approved' ? 'bg-green-600 text-white border-green-600' : 'text-gray-700 hover:bg-gray-50' }}">Approved</a>
                    <a href="{{ url()->current() }}?v=rejected"
                       class="px-3 py-1 rounded border {{ $v==='rejected' ? 'bg-green-600 text-white border-green-600' : 'text-gray-700 hover:bg-gray-50' }}">Rejected</a>
                </div>
            @endif
        </div>

        <div class="mt-4 overflow-x-auto -mx-2">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold text-gray-600 uppercase">
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3">Order / Customer</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Payment</th>
                        @if($supportsVerification)
                            <th class="px-4 py-3">Verification</th>
                        @endif
                        @if($supportsProof)
                            <th class="px-4 py-3">Proof</th>
                        @endif
                        @if($supportsVerification)
                            <th class="px-4 py-3">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @php
                        $pMap = $pMap ?? ['pending'=>'bg-yellow-100 text-yellow-800','completed'=>'bg-green-100 text-green-800','failed'=>'bg-red-100 text-red-800'];
                        $vMap = $vMap ?? ['pending'=>'bg-gray-100 text-gray-800','approved'=>'bg-green-100 text-green-800','rejected'=>'bg-red-100 text-red-800'];
                    @endphp

                    @forelse($payments as $p)
                        <tr>
                            <td class="px-4 py-3 font-medium">#{{ $p->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">Order #{{ $p->order?->id ?? '—' }}</div>
                                <div class="text-gray-500">{{ $p->order->customer_name ?? $p->order->customer_email ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3">₱{{ number_format($p->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ strtoupper($p->payment_method) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $pMap[$p->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($p->payment_status) }}
                                </span>
                                @if(!empty($p->paid_at))
                                    <span class="ml-2 text-xs text-gray-500">{{ \Illuminate\Support\Carbon::parse($p->paid_at)->format('Y-m-d H:i') }}</span>
                                @endif
                            </td>

                            @if($supportsVerification)
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 rounded text-xs {{ $vMap[$p->verification_status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($p->verification_status ?? 'pending') }}
                                        </span>
                                        @if(!empty($p->verified_at))
                                            <span class="text-xs text-gray-500">{{ \Illuminate\Support\Carbon::parse($p->verified_at)->format('Y-m-d H:i') }}</span>
                                        @endif
                                    </div>
                                </td>
                            @endif

                            @if($supportsProof)
                                <td class="px-4 py-3">
                                    @php
                                        $proofUrl = method_exists($p, 'getProofUrlAttribute')
                                            ? $p->proof_url
                                            : ((isset($p->proof_path) && $p->proof_path) ? asset('storage/'.$p->proof_path) : null);
                                    @endphp
                                    @if($proofUrl)
                                        <button x-data @click="$dispatch('open-proof', { url: '{{ $proofUrl }}' })" class="text-green-700 underline">View</button>
                                    @else
                                        <span class="text-gray-400">No proof</span>
                                    @endif
                                </td>
                            @endif

                            @if($supportsVerification)
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @if(($p->verification_status ?? 'pending') === 'pending')
                                            <form action="{{ route('admin.payments.approve', $p) }}" method="POST" onsubmit="return confirm('Approve this payment?');">
                                                @csrf
                                                <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.payments.reject', $p) }}" method="POST" onsubmit="return confirm('Reject this payment?');">
                                                @csrf
                                                <input type="hidden" name="notes" value="Rejected by admin">
                                                <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            @php
                                $colspan = 5 + ($supportsVerification ? 1 : 0) + ($supportsProof ? 1 : 0) + ($supportsVerification ? 1 : 0);
                            @endphp
                            <td class="px-4 py-6 text-gray-500" colspan="{{ $colspan }}">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="text-xs text-gray-500 mt-3">Showing latest {{ $payments->count() }} payments.</p>

        {{-- Proof Modal --}}
        @if($supportsProof)
            <div x-data="{ open:false, url:null }"
                 x-on:open-proof.window="open=true; url=$event.detail.url"
                 x-show="open"
                 class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50"
                 style="display:none">
                <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full">
                    <div class="flex items-center justify-between p-3 border-b">
                        <h4 class="font-semibold">Payment Proof</h4>
                        <button class="text-gray-500" @click="open=false">&times;</button>
                    </div>
                    <div class="p-4">
                        <img :src="url" alt="Payment Proof" class="w-full h-auto rounded">
                    </div>
                    <div class="p-3 border-t text-right">
                        <a :href="url" target="_blank" class="px-3 py-2 border rounded mr-2">Open original</a>
                        <button class="px-3 py-2 bg-green-600 text-white rounded" @click="open=false">Close</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    {{-- Alpine (if not globally included) --}}
    <script>
        if (!window.Alpine) {
            const s = document.createElement('script');
            s.defer = true;
            s.src = 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js';
            document.head.appendChild(s);
        }
    </script>

    {{-- Chart.js loader + init --}}
    <script>
        (function ensureChartJS(cb){
            if (window.Chart) return cb();
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            s.onload = cb;
            document.head.appendChild(s);
        })(function initMonthlyChart() {
            const el = document.getElementById('monthlyChart');
            if (!el) return;

            const monthly = @json(($monthly ?? collect())->map(fn($r) => [
                'ym' => $r->ym ?? '',
                'total' => (float)($r->total ?? 0),
            ]));

            if (!monthly.length) {
                const wrap = el.closest('.bg-white');
                if (wrap) {
                    const p = document.createElement('p');
                    p.className = 'mt-4 text-sm text-gray-500';
                    p.textContent = 'No data available for the selected period.';
                    wrap.appendChild(p);
                }
                return;
            }

            new Chart(el, {
                type: 'line',
                data: {
                    labels: monthly.map(m => m.ym),
                    datasets: [{
                        label: 'Total',
                        data: monthly.map(m => m.total),
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const v = ctx.parsed.y ?? 0;
                                    return ' ₱' + (v).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: v => '₱' + Number(v).toLocaleString() }
                        }
                    }
                }
            });
        });
    </script>
@endpush
