@extends('html.html')

@push('js')
<script>
    $(document).ready(function () {
        $('#inventoryTable').DataTable({
            order: [],
            info: true,
        });
    });
</script>
@endpush

@section('content')
@include('components.navbar')
@include('components.sidebar')
<main id="main" class="main">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Inventory Management
            </h5>
        </div>
        <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold text-secondary">+ Add Item</h5>
            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                data-bs-target="#din-form-collapse" aria-expanded="false" aria-controls="din-form-collapse"
                id="toggle-form-btn">
                Open Form ▾
            </button>
        </div>
        <div class="collapse" id="din-form-collapse">
            <div class="card-body bg-light-subtle">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Part No</label>
                        <input type="text" class="form-control" placeholder="Part No">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Part Name</label>
                        <input type="text" class="form-control" placeholder="Part Name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Supplier</label>
                        <input type="text" class="form-control" placeholder="Supplier Name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Stock</label>
                        <input type="number" class="form-control" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Unit</label>
                        <input type="text" class="form-control" id="din-unit" placeholder="pcs / L / kg" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Unit Cost ($)</label>
                        <input type="number" class="form-control" id="din-cost" placeholder="0.00" step="0.01">
                    </div>                   
                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <button class="btn btn-primary" onclick="submitForm('din')">✓ Save</button>
                    <button class="btn btn-light" type="button" data-bs-toggle="collapse"
                        data-bs-target="#din-form-collapse">Cancel</button>
                </div>
            </div>
        </div>
    </div>        

        <div class="card-body">
            <div class="table-responsive mt-3">
                <table id="inventoryTable" class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>Stock</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                            <th>Lead Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>SKU001</td>
                            <td>
                                <strong>Steel Plate A36</strong>
                                <br>
                                <small class="text-muted">
                                    PT Baja Indonesia
                                </small>
                            </td>
                            <td>2,450 KG</td>
                            <td>$25.00</td>
                            <td>$61,250</td>
                            <td>14</td>
                            <td>
                                <span class="badge bg-success">
                                    OK
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>SKU002</td>
                            <td>
                                <strong>Aluminium Sheet</strong>
                                <br>
                                <small class="text-muted">
                                    PT Aluminium Jaya
                                </small>
                            </td>
                            <td>520 PCS</td>
                            <td>$18.00</td>
                            <td>$9,360</td>
                            <td>10</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    Low
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>SKU003</td>
                            <td>
                                <strong>Hex Bolt M12</strong>
                                <br>
                                <small class="text-muted">
                                    Fastener Supplier
                                </small>
                            </td>
                            <td>80 PCS</td>
                            <td>$0.50</td>
                            <td>$40</td>
                            <td>5</td>
                            <td>
                                <span class="badge bg-danger">
                                    Critical
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>SKU004</td>
                            <td>
                                <strong>Bearing 6204</strong>
                                <br>
                                <small class="text-muted">
                                    SKF Indonesia
                                </small>
                            </td>
                            <td>1,250 PCS</td>
                            <td>$3.00</td>
                            <td>$3,750</td>
                            <td>7</td>
                            <td>
                                <span class="badge bg-success">
                                    OK
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-lg-3">
            <div class="card h-100 border-success shadow-sm">
                <div class="card-body">
                    <h6>Steel Plate A36</h6>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Current Stock : <strong>2450 KG</strong>
                        </li>
                        <li class="list-group-item">
                            Reorder Point : <strong>500</strong>
                        </li>
                        <li class="list-group-item">
                            Min / Max : <strong>300 / 3000</strong>
                        </li>
                        <li class="list-group-item">
                            Coverage : <strong>6.5 Months</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card h-100 border-warning shadow-sm">
                <div class="card-body">
                    <h6>Aluminium Sheet</h6>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Current Stock : <strong>520 PCS</strong>
                        </li>
                        <li class="list-group-item">
                            Reorder Point : <strong>400</strong>
                        </li>
                        <li class="list-group-item">
                            Min / Max : <strong>200 / 1200</strong>
                        </li>
                        <li class="list-group-item">
                            Coverage : <strong>2.8 Months</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card h-100 border-danger shadow-sm">
                <div class="card-body">
                    <h6>Hex Bolt M12</h6>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Current Stock : <strong>80 PCS</strong>
                        </li>
                        <li class="list-group-item">
                            Reorder Point : <strong>250</strong>
                        </li>
                        <li class="list-group-item">
                            Min / Max : <strong>100 / 1000</strong>
                        </li>
                        <li class="list-group-item">
                            Coverage : <strong>0.5 Months</strong>
                        </li>
                    </ul>

                    <button class="btn btn-danger w-100 mt-3">
                        Create Purchase Order
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card h-100 border-primary shadow-sm">
                <div class="card-body">
                    <h6>Bearing 6204</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Current Stock : <strong>1250 PCS</strong>
                        </li>
                        <li class="list-group-item">
                            Reorder Point : <strong>300</strong>
                        </li>
                        <li class="list-group-item">
                            Min / Max : <strong>200 / 2000</strong>
                        </li>
                        <li class="list-group-item">
                            Coverage : <strong>4.5 Months</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

@include('components.footer')
@endsection
