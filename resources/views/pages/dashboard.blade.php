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
    <div class="row mb-3">
        <div class="col-md-6 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total SKUs</small>
                    <h2 class="mb-0">{{ $summary->total_active }}</h2>
                    <small class="text-secondary">Active products</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card border-primary shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Inventory Value</small>
                    <h2 class="mb-0">{{ 'SGD '.number_format($summary->total_inventory_value,2,'.',',') }}</h2>
                    <small class="text-secondary">Current stock worth</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card border-danger shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Critical Alerts</small>
                    <h2 class="text-danger mb-0">{{ $summary->total_critical_stock ?? 0 }}</h2>
                    <small class="text-secondary">Below minimum stock</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Low Stock Items</small>
                    <h2 class="text-warning mb-0">{{ $summary->total_low_stock ?? 0 }}</h2>
                    <small class="text-secondary">Need reordering</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Avg Monthly Demand</small>
                    <h2 class="mb-0">$125,000</h2>
                    <small class="text-secondary">Next 6-month avg</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Stock Level -->
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Stock Level Overview</span>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <strong>Steel Plate A36</strong>
                            <span>2,450 KG</span>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" style="width:85%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <strong>Aluminium Sheet</strong>
                            <span>520 PCS</span>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-warning" style="width:45%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <strong>Hex Bolt M12</strong>
                            <span>80 PCS</span>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-danger" style="width:15%"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Forecast -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>6-Month Demand Forecast Snapshot</span>
            <a href="#" class="btn btn-sm btn-outline-primary">
                Full Analysis
            </a>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h6>Steel Plate A36</h6>
                        <small class="text-muted">SKU-001</small>

                        <div class="mt-3">
                            <span class="badge bg-success">
                                Risk: Low
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h6>Aluminium Sheet</h6>
                        <small class="text-muted">SKU-002</small>
                        <div class="mt-3">
                            <span class="badge bg-warning">
                                Risk: Medium
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h6>Hex Bolt M12</h6>
                        <small class="text-muted">SKU-003</small>
                        <div class="mt-3">
                            <span class="badge bg-danger">
                                Risk: High
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h6>Bearing 6204</h6>
                        <small class="text-muted">SKU-004</small>
                        <div class="mt-3">
                            <span class="badge bg-success">
                                Risk: Low
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
