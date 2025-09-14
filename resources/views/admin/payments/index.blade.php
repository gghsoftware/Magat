@extends('layouts.admin')

@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')
    {{-- Flash --}}
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

    {{-- Filters --}}
    <form method="GET" class="bg-white p-4 rounded-xl shadow mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search ID / Order / Name / Email"
                   class="w-full border rounded px-3 py-2">

            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="">Payment Status (all)</option>
                @foreach(['pending','completed','failed'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            @if($supportsVerification)
                <select name="v" class="w-full border rounded px-3 py-2">
                    <option value="">Verification (all)</option>
                    @foreach(['pending','approved','rejected'] as $v)
                        <option value="{{ $v }}" @selected(request('v')===$v)>{{ ucfirst($v) }}</option>
                    @endforeach
                </select>
            @endif

            <select name="per_page" class="w-full border rounded px-3 py-2">
                @foreach([10,25,50,100] as $pp)
                    <option value="{{ $pp }}" @selected((int)request('per_page',10)===$pp)>{{ $pp }} / page</option>
                @endforeach
            </select>
        </div>

        <div class="mt-3 flex gap-2">
            <button class="px-4 py-2 bg-green-600 text-white rounded">Apply</button>
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 border rounded">Reset</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white p-6 rounded-xl shadow">
        <div class="overflow-x-auto -mx-2">
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

        <div class="mt-4">
            {{ $payments->links() }}
        </div>

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
@endpush
