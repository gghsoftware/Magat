{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Reports Management')
@section('page-title', 'Reports Management')

@push('head')
  {{-- Chart.js (CDN) --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <style>
    /* Print-friendly styles */
    @media print {
      @page { size: A4 portrait; margin: 12mm; }
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      .no-print { display: none !important; }
      .card { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
      .page-title { color: #065f46 !important; }
      /* Avoid page breaks inside tables/charts */
      .avoid-break, table { break-inside: avoid; page-break-inside: avoid; }
      .section-title { background: #059669 !important; color: #fff !important; -webkit-print-color-adjust: exact; }
    }
  </style>
@endpush

@section('content')
  {{-- Header + Filters --}}
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6 no-print">
    <h2 class="text-2xl font-semibold text-green-800 page-title">Reports</h2>

    <div class="flex items-center gap-3">
      <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
        <option value="all">All Reports</option>
        <option value="sales">Sales Reports</option>
        <option value="orders">Order Reports</option>
        <option value="customers">Customer Reports</option>
      </select>

      {{-- Year filter (drives monthly + top packages) --}}
      <form method="get">
        <label class="sr-only" for="year">Year</label>
        <select id="year" name="year"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                onchange="this.form.submit()">
          @foreach($yearsForFilter as $y)
            <option value="{{ $y }}" @selected($selectedYear == $y)>{{ $y }}</option>
          @endforeach
        </select>
      </form>

      <button type="button"
              onclick="window.print()"
              class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm shadow">
        Print
      </button>
    </div>
  </div>

  {{-- KPI Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 avoid-break">
    <div class="bg-white rounded-lg shadow card p-4">
      <div class="text-sm text-gray-500">Total Customers</div>
      <div class="text-2xl font-semibold text-green-700">
        {{ number_format($totalCustomers) }}
      </div>
    </div>

    <div class="bg-white rounded-lg shadow card p-4">
      <div class="text-sm text-gray-500">Payments ({{ $selectedYear }})</div>
      @php
        $sumYear = collect($monthlyPayments)->sum('total_amount');
        $cntYear = collect($monthlyPayments)->sum('payments_count');
      @endphp
      <div class="text-2xl font-semibold text-green-700">₱{{ number_format($sumYear, 2) }}</div>
      <div class="text-xs text-gray-500">{{ number_format($cntYear) }} payments</div>
    </div>

    <div class="bg-white rounded-lg shadow card p-4">
      <div class="text-sm text-gray-500">Top Package ({{ $selectedYear }})</div>
      @php $top = $topPackagesThisYear->first(); @endphp
      @if($top)
        <div class="text-lg font-semibold">{{ $top['package_name'] }}</div>
        <div class="text-xs text-gray-500">{{ $top['orders_count'] }} orders</div>
      @else
        <div class="text-gray-400">No data</div>
      @endif
    </div>
  </div>

  {{-- Charts Section --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 avoid-break">
    {{-- Monthly Payments Chart --}}
    <div class="bg-white rounded-lg shadow card p-4">
      <div class="px-2 py-2 rounded section-title text-white font-medium mb-3">Monthly Payments — {{ $selectedYear }}</div>
      <canvas id="chartMonthly" height="140"></canvas>
    </div>

    {{-- Yearly Payments Chart --}}
    <div class="bg-white rounded-lg shadow card p-4">
      <div class="px-2 py-2 rounded section-title text-white font-medium mb-3">Yearly Payments — Last 5 Years</div>
      <canvas id="chartYearly" height="140"></canvas>
    </div>

    {{-- Top Packages (Selected Year) --}}
    <div class="bg-white rounded-lg shadow card p-4 lg:col-span-2">
      <div class="px-2 py-2 rounded section-title text-white font-medium mb-3">Top Packages — {{ $selectedYear }}</div>
      <canvas id="chartTopPackages" height="160"></canvas>
      @if(($topPackagesThisYear ?? collect())->isEmpty())
        <div class="text-sm text-gray-500 mt-2">No data</div>
      @endif
    </div>
  </div>

  {{-- Monthly Payments Table --}}
  <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 card avoid-break">
    <div class="px-6 py-3 bg-green-600 text-white font-medium section-title">Monthly Payments — {{ $selectedYear }}</div>
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto">
        <thead class="bg-green-50">
          <tr>
            <th class="px-6 py-2 text-left text-xs font-semibold text-green-800">Month</th>
            <th class="px-6 py-2 text-right text-xs font-semibold text-green-800">Total Amount</th>
            <th class="px-6 py-2 text-right text-xs font-semibold text-green-800"># Payments</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @foreach($monthlyPayments as $m)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-2 text-sm">{{ $m['month_label'] }}</td
              ><td class="px-6 py-2 text-sm text-right">₱{{ number_format($m['total_amount'], 2) }}</td
              ><td class="px-6 py-2 text-sm text-right">{{ number_format($m['payments_count']) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-gray-50">
          <tr>
            <th class="px-6 py-2 text-right text-xs font-semibold text-green-800">TOTAL</th>
            <th class="px-6 py-2 text-right text-sm font-semibold">
              ₱{{ number_format(collect($monthlyPayments)->sum('total_amount'), 2) }}
            </th>
            <th class="px-6 py-2 text-right text-sm font-semibold">
              {{ number_format(collect($monthlyPayments)->sum('payments_count')) }}
            </th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- Yearly Payments Table --}}
  <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 card avoid-break">
    <div class="px-6 py-3 bg-green-600 text-white font-medium section-title">Yearly Payments — Last 5 Years</div>
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto">
        <thead class="bg-green-50">
          <tr>
            <th class="px-6 py-2 text-left text-xs font-semibold text-green-800">Year</th>
            <th class="px-6 py-2 text-right text-xs font-semibold text-green-800">Total Amount</th>
            <th class="px-6 py-2 text-right text-xs font-semibold text-green-800"># Payments</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($yearlyPayments as $y)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-2 text-sm">{{ $y->y }}</td>
              <td class="px-6 py-2 text-sm text-right">₱{{ number_format($y->total_amount, 2) }}</td>
              <td class="px-6 py-2 text-sm text-right">{{ number_format($y->payments_count) }}</td>
            </tr>
          @empty
            <tr>
              <td class="px-6 py-3 text-sm text-gray-500" colspan="3">No data</td>
            </tr>
          @endforelse
        </tbody>
        @if($yearlyPayments->count())
          <tfoot class="bg-gray-50">
            @php
              $ySum = $yearlyPayments->sum('total_amount');
              $yCnt = $yearlyPayments->sum('payments_count');
            @endphp
            <tr>
              <th class="px-6 py-2 text-right text-xs font-semibold text-green-800">TOTAL</th>
              <th class="px-6 py-2 text-right text-sm font-semibold">₱{{ number_format($ySum, 2) }}</th>
              <th class="px-6 py-2 text-right text-sm font-semibold">{{ number_format($yCnt) }}</th>
            </tr>
          </tfoot>
        @endif
      </table>
    </div>
  </div>

  {{-- Packages Uptake by Year --}}
  <div class="bg-white shadow-md rounded-lg overflow-hidden card avoid-break">
    <div class="px-6 py-3 bg-green-600 text-white font-medium section-title">Packages Uptake by Year</div>

    @forelse($packageUptakeByYear as $y => $rows)
      <div class="px-6 py-3 font-semibold text-green-800 border-t">{{ $y }}</div>
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto mb-4">
          <thead class="bg-green-50">
            <tr>
              <th class="px-6 py-2 text-left text-xs font-semibold text-green-800">Package</th>
              <th class="px-6 py-2 text-right text-xs font-semibold text-green-800"># Orders</th>
              <th class="px-6 py-2 text-right text-xs font-semibold text-green-800">Revenue</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @php $rowsIter = collect($rows)->values(); @endphp
            @foreach($rowsIter as $r)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-2 text-sm">{{ $r['package_name'] }}</td>
                <td class="px-6 py-2 text-sm text-right">{{ number_format($r['orders_count']) }}</td>
                <td class="px-6 py-2 text-sm text-right">₱{{ number_format($r['revenue_sum'], 2) }}</td>
              </tr>
            @endforeach
          </tbody>
          @php
            $ordersTotal = collect($rows)->sum('orders_count');
            $revenueTotal = collect($rows)->sum('revenue_sum');
          @endphp
          <tfoot class="bg-gray-50">
            <tr>
              <th class="px-6 py-2 text-right text-xs font-semibold text-green-800">TOTAL</th>
              <th class="px-6 py-2 text-right text-sm font-semibold">{{ number_format($ordersTotal) }}</th>
              <th class="px-6 py-2 text-right text-sm font-semibold">₱{{ number_format($revenueTotal, 2) }}</th>
            </tr>
          </tfoot>
        </table>
      </div>
    @empty
      <div class="px-6 py-4 text-sm text-gray-500">No data</div>
    @endforelse
  </div>

@endsection

@push('scripts')
<script>
  // Data from controller
  const monthly = @json($monthlyPayments); // [{month_label, total_amount, payments_count}]
  const yearly = @json($yearlyPayments);   // [{y, total_amount, payments_count}]
  const topPkgs = @json(($topPackagesThisYear ?? collect())->values()); // [{package_name, orders_count, revenue_sum}]

  // Helpers
  const peso = (v)=> new Intl.NumberFormat('en-PH',{style:'currency',currency:'PHP'}).format(v ?? 0);
  const num  = (v)=> new Intl.NumberFormat('en-PH').format(v ?? 0);

  // Monthly chart (dual-axis: amount + count)
  (() => {
    const ctx = document.getElementById('chartMonthly');
    const labels = monthly.map(m => m.month_label);
    const amounts = monthly.map(m => (+m.total_amount));
    const counts  = monthly.map(m => (+m.payments_count));

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          { label: 'Total Amount', data: amounts, yAxisID: 'y', borderWidth: 1 },
          { label: '# Payments',   data: counts,  yAxisID: 'y1', type: 'line', tension: 0.25, borderWidth: 2 }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top' },
          tooltip: {
            callbacks: {
              label: ctx => ctx.dataset.yAxisID === 'y' ? ` ${peso(ctx.parsed.y)}` : ` ${num(ctx.parsed.y)}`
            }
          }
        },
        scales: {
          y:  { beginAtZero: true, ticks: { callback: v => peso(v) }, title:{display:true,text:'Amount (PHP)'} },
          y1: { beginAtZero: true, position:'right', grid:{drawOnChartArea:false}, title:{display:true,text:'# Payments'} }
        }
      }
    });
  })();

  // Yearly chart (bar for amounts, line for counts)
  (() => {
    const ctx = document.getElementById('chartYearly');
    const labels  = yearly.map(y => y.y);
    const amounts = yearly.map(y => (+y.total_amount));
    const counts  = yearly.map(y => (+y.payments_count));

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          { label: 'Total Amount', data: amounts, yAxisID: 'y', borderWidth: 1 },
          { label: '# Payments',   data: counts,  yAxisID: 'y1', type: 'line', tension: 0.25, borderWidth: 2 }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top' },
          tooltip: {
            callbacks: {
              label: ctx => ctx.dataset.yAxisID === 'y' ? ` ${peso(ctx.parsed.y)}` : ` ${num(ctx.parsed.y)}`
            }
          }
        },
        scales: {
          y:  { beginAtZero: true, ticks: { callback: v => peso(v) }, title:{display:true,text:'Amount (PHP)'} },
          y1: { beginAtZero: true, position:'right', grid:{drawOnChartArea:false}, title:{display:true,text:'# Payments'} }
        }
      }
    });
  })();

  // Top packages (selected year) — bar by # orders; tooltip includes revenue
  (() => {
    const ctx = document.getElementById('chartTopPackages');
    const labels = (topPkgs || []).map(p => p.package_name);
    const counts  = (topPkgs || []).map(p => (+p.orders_count));
    const revenue = (topPkgs || []).map(p => (+p.revenue_sum));

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          { label: '# Orders', data: counts, borderWidth: 1 }
        ]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => ` # Orders: ${num(ctx.parsed.x)} | Revenue: ${peso(revenue[ctx.dataIndex])}`
            }
          }
        },
        scales: {
          x: { beginAtZero: true, title: { display: true, text: '# Orders' } },
          y: { ticks: { autoSkip: false } }
        }
      }
    });
  })();
</script>
@endpush
