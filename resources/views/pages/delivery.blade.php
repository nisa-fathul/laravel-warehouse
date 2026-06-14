@extends('html.html')

@push('css')
    <style>
        .form-control[readonly],
        .form-select[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }
    </style>
@endpush

@push('js')
<script>
    $(document).ready(function () {
        $('#in-table').DataTable({
            order: [],
            info: true,
        });
    });
</script>

<script>
    function initSelect2() {
        $('.barang-select').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }

    $(document).ready(function () {
        initSelect2();

        $('#add-row').on('click', function () {

        let firstRow = $('#detail-table tbody tr:first');
        firstRow.find('.barang-select').select2('destroy');

        let row = firstRow.clone();

        row.find('input').val('');
        row.find('select').val('');

        $('#detail-table tbody').append(row);

        initSelect2();

    });

        $(document).on('click', '.remove-row', function () {
            if ($('#detail-table tbody tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });

        $(document).on('change', '.barang-select', function () {
            let selected = $(this).find(':selected');
            let row = $(this).closest('tr');
            row.find('.unit')
                .val(selected.data('unit'));
            row.find('.stock')
                .val(selected.data('stock'));
        });
    });
</script>
@endpush

@section('content')
@include('components.navbar')
@include('components.sidebar')

<main id="main" class="main">

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6">
            <div class="card h-100 border-start border-primary border-4 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase">Total Received</div>
                    <div class="fs-3 fw-bold my-1">{{ $dataCard['total_received'] ?? 0 }}</div>
                    <div class="text-secondary small">Document {{ $type }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="card h-100 border-start border-success border-4 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase">Value {{ $type }}</div>
                    <div class="fs-3 fw-bold my-1">{{ 'SGD ' . number_format($dataCard['total_value'], 2, '.', ',') }}</div>
                    <div class="text-secondary small">Total Value {{ $type }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold text-secondary">
                Create New Transaction {{ $type }}
            </h5>

            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#din-form-collapse">
                Open Form <i class="bi bi-caret-down-fill"></i>
            </button>
        </div>
        <div class="collapse" id="din-form-collapse">
            <form action="{{ route('transaction.'.$type.'.store') }}" method="POST">
                @csrf
                @method('POST')
                <div class="card-body bg-light-subtle">
                    {{-- HEADER TRANSAKSI --}}
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">
                                Transaction Date
                            </label>
                            <input type="datetime-local" name="transaction_date" value="{{ old('transaction_date') }}" class="form-control @error('transaction_date') is-invalid @enderror" required>
                            @error('transaction_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">
                                Supplier / Vendor
                            </label>
                            <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" class="form-control @error('supplier_name') is-invalid @enderror" placeholder="Supplier Name" required>
                            @error('supplier_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">
                                Notes
                            </label>
                            <input type="text" name="notes" value="{{ old('notes') }}" class="form-control @error('notes') is-invalid @enderror" placeholder="Optional Notes">
                            @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    {{-- DETAIL BARANG --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            Item Details
                        </h6>
                        <button type="button" class="btn btn-sm btn-primary" id="add-row">
                            <i class="bi bi-plus-circle"></i>
                            Add Item
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="detail-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="40%">
                                        Item
                                    </th>
                                    <th width="15%">
                                        Unit
                                    </th>
                                    <th width="15%">
                                        Current Stock
                                    </th>
                                    <th width="15%">
                                        Qty Received
                                    </th>
                                    <th width="15%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="item_id[]" class="form-select barang-select @error('item_id.*') is-invalid @enderror" required>
                                            <option value="">
                                                Choose Item
                                            </option>
                                            @foreach($dataBarang as $barang)
                                                <option value="{{ $barang->id }}" data-unit="{{ $barang->satuan }}" data-stock="{{ $barang->stok->qty ?? 0 }}">
                                                    {{ $barang->kode_barang }}
                                                    -
                                                    {{ $barang->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('item_id.*')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control unit" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control stock" readonly>
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="qty[]"
                                            min="1"
                                            value="{{ old('qty.*') }}"
                                            class="form-control @error('qty.*') is-invalid @enderror" required>

                                        @error('qty.*')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger remove-row">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @error('barang_id')
                    <div class="text-danger small">
                        {{ $message }}
                    </div>
                    @enderror

                    @error('qty')
                    <div class="text-danger small">
                        {{ $message }}
                    </div>
                    @enderror

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            ✓ Save Receipt
                        </button>

                        <button class="btn btn-light" type="button" data-bs-toggle="collapse"
                            data-bs-target="#din-form-collapse">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-secondary">Riwayat Penerimaan Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle w-100" id="in-table">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th>Transaction Code</th>
                            <th>Tanggal / Waktu</th>
                            <th>Customer</th>
                            <th>Created By</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @foreach ($dataTransaksi as $key => $value)
                            <tr>
                                <td>{{ $value->kode_transaksi }}</td>
                                <td>
                                    {{ $value->tanggal_transaksi }}
                                </td>
                                <td>{{ $value->nama_customer }}</td>
                                <td class="text-center fw-semibold">
                                    {{ $value->user->name }}
                                    <br>
                                    {{ $value->created_at }}
                                </td>
                                <td>{{ $value->keterangan }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-primary" href="{{ route('transaction.detail', ['type' => $type,'action' => 'detail', 'id' => $value->id]) }}">
                                            <i class="bi bi-list"></i>
                                        </a>
                                        @if ($value->user_id == Auth::id())
                                            <a class="btn btn-warning" href="{{ route('transaction.detail', ['type' => $type,'action' => 'update', 'id' => $value->id]) }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
