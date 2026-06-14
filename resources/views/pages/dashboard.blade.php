@extends('html.html')

@push('css')

@endpush

@push('js')

@endpush

@section('content')

@include('components.navbar')

@include('components.sidebar')

<!-- KPI -->
<main id="main" class="main">
    <div class="row row-gap-3 mb-3">
        <div class="col-md-6 col-lg-4 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total SKUs</small>
                    <h2 class="mb-0">{{ $summary->total_active }}</h2>
                    <small class="text-secondary">Active products</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="card border-primary shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Inventory Value</small>
                    <h2 class="mb-0">{{ 'SGD '.number_format($summary->total_inventory_value,2,'.',',') }}</h2>
                    <small class="text-secondary">Current stock worth</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="card border-danger shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Critical Alerts</small>
                    <h2 class="text-danger mb-0">{{ $summary->total_critical_stock ?? 0 }}</h2>
                    <small class="text-secondary">Below minimum stock</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Low Stock Items</small>
                    <h2 class="text-warning mb-0">{{ $summary->total_low_stock ?? 0 }}</h2>
                    <small class="text-secondary">Need reordering</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Avg Monthly Demand</small>
                    <h2 class="mb-0">{{ 'SGD '.number_format($average['average_monthly'], 2, '.', ',') }}</h2>
                    <small class="text-secondary">Monthly Avg</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Stock Level -->
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Top 5 Items</span>
                    <a href="{{ Route('inventory.index') }}" class="btn btn-sm btn-outline-primary">
                        Stock Overview
                    </a>
                </div>

                <div class="card-body">
                    @forelse ($topBarang as $key => $value )
                        @php
                            $actual_stok = $value->stok->qty;
                            $min_stok = $value->min_stok;

                            $max_stok = $min_stok + 10;
                            $progress = min(
                                ($actual_stok / $max_stok) * 100,
                                100
                            );
                            if ($actual_stok < $min_stok) {
                                $badge_class = 'bg-danger';
                            } elseif ($actual_stok <= ($min_stok + 10)) {
                                $badge_class = 'bg-warning';
                            } else {
                                $badge_class = 'bg-success';
                            }
                        @endphp

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $value->nama_barang }}</strong>
                                <span>{{ $value->stok->qty }} {{ $value->satuan }}</span>
                            </div>
                            <div class="progress mt-2">
                                <div class="progress-bar {{ $badge_class }}" style="width: {{ $progress }}%">
                                    {{ round($progress) }}%
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <x-empty-state/>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
