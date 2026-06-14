@extends('html.html')

@push('js')
<script>
    $('.table').DataTable({
            order:[],
        })
</script>
@endpush

@section('content')
@include('components.navbar')
@include('components.sidebar')

@php
$is_disabled = NULL;

if($action == 'detail' || ($dataTransaksi->user_id != Auth::id())){
    $is_disabled = 'disabled';
}
@endphp

<main class="main" id="main">
    <form action="{{ route('transaction.'.$type.'.update',['id_transaksi' => $dataTransaksi->id]) }}" method="POST">

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    Transaction Detail
                </h5>

                <div>
                    <a href="{{ route('transaction.index', ['type' => $type]) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            Transaction Number
                        </label>
                        <input type="text" class="form-control" value="{{ $dataTransaksi->kode_transaksi }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Transaction Date
                        </label>
                        <input type="datetime-local" name="transaction_date"
                            value="{{ old('transaction_date', $dataTransaksi->tanggal_transaksi->format('Y-m-d\TH:i')) }}"
                            class="form-control @error('transaction_date') is-invalid @enderror" {{ $is_disabled }}>
                        @error('transaction_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Supplier / Customer
                        </label>
                        <input type="text" name="supplier_name"
                            value="{{ old('supplier_name', $dataTransaksi->nama_customer) }}"
                            class="form-control @error('supplier_name') is-invalid @enderror" {{ $is_disabled }}>
                        @error('supplier_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Created By
                        </label>
                        <input type="text" class="form-control" value="{{ $dataTransaksi->user->name }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Notes
                        </label>
                        <input type="text" name="notes" value="{{ old('notes', $dataTransaksi->keterangan) }}"
                            class="form-control" {{ $is_disabled }}>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    Item Details
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Unit</th>
                                <th width="150">Qty</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($dataTransaksi->details as $index => $detail)
                            <tr>
                                <td>
                                    {{ $detail->barang->kode_barang }}
                                </td>
                                <td>
                                    {{ $detail->barang->nama_barang }}
                                </td>
                                <td>
                                    {{ $detail->barang->satuan }}
                                </td>
                                <td>
                                    <input type="number" name="qty[]" value="{{ old('qty.'.$index,$detail->qty) }}"
                                        class="form-control" {{ $is_disabled }} required min="1">
                                    <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                </td>
                                <td>
                                    {{ number_format($detail->barang->stok->qty) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($action == 'update' && ($dataTransaksi->user_id == Auth::id()))
                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
                @endif
            </div>
        </div>
    </form>

</main>
@include('components.footer')
@endsection
