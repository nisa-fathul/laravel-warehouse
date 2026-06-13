@extends('html.html')

@push('js')
<script>
    $(document).ready(function () {
        $('#inventoryTable').DataTable({
            order: [],
            info: true,
        });
    });

    function deleteItem(url) {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            text: 'You are about to delete this record.',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'delete',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },

                    beforeSend: function () {
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },

                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message ?? 'Item deleted successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },

                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: xhr.responseJSON?.message ?? 'Failed to delete item.'
                        });
                    }
                });

            }

        });
    }
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
                        <div class="card-body bg-light-subtle">
                            <form action="{{ route('inventory.create') }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">
                                            Part Number
                                        </label>

                                        <input type="text" name="kode_barang" value="{{ old('kode_barang') }}"
                                            class="form-control @error('kode_barang') is-invalid @enderror"
                                            placeholder="Enter Part Number" required>

                                        @error('kode_barang')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">
                                            Part Name
                                        </label>

                                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                                            class="form-control @error('nama_barang') is-invalid @enderror"
                                            placeholder="Enter Part Name" required>

                                        @error('nama_barang')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">
                                            Unit
                                        </label>
                                        <input type="text" name="satuan" value="{{ old('satuan') }}"
                                            class="form-control @error('satuan') is-invalid @enderror"
                                            placeholder="PCS / KG / LTR" required>
                                        @error('satuan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">
                                            Minimum Stock
                                        </label>
                                        <input type="number" name="min_stok" value="{{ old('min_stok') }}"
                                            class="form-control @error('min_stok') is-invalid @enderror"
                                            placeholder="Enter Minimum Stock" required>
                                        @error('min_stok')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">
                                            Stock
                                        </label>
                                        <input type="number" name="stok" value="{{ old('stok') }}"
                                            class="form-control @error('stok') is-invalid @enderror"
                                            placeholder="Enter Actual Stock" required>
                                        @error('stok')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">
                                            Selling Price
                                        </label>
                                        <input type="number" name="harga" value="{{ old('harga') }}"
                                            class="form-control @error('harga') is-invalid @enderror"
                                            placeholder="Enter Selling Price" step="0.01" required>
                                        @error('harga')
                                        <div class="invalid-feedback" required>
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i>
                                            Save
                                        </button>
                                        <button type="button" class="btn btn-light" data-bs-toggle="collapse"
                                            data-bs-target="#din-form-collapse">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                            <th>Minimum Stock</th>
                            <th>Stock</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($dataBarang as $key => $value )
                            <tr>
                                <td class="fw-bold">{{ $value->kode_barang }}</td>
                                <td>
                                    {{ $value->nama_barang }}
                                </td>
                                <td>{{ $value->min_stok }} {{ $value->satuan }}</td>
                                <td>{{ $value->stok->qty }} {{ $value->satuan }}</td>
                                <td>{{ $value->hargaFormat() }}</td>
                                <td>{{ 'SGD '.number_format($value->harga * $value->stok->qty, 2, '.', ',') }}</td>
                                <td>
                                    @php
                                        $actual_stok = $value->stok->qty;
                                        $min_stok = $value->min_stok;

                                        if ($actual_stok < $min_stok) {
                                            $status_stok = 'Critical';
                                            $badge_class = 'bg-danger';
                                        } elseif ($actual_stok <= ($min_stok + 10)) {
                                            $status_stok = 'Low';
                                            $badge_class = 'bg-warning text-dark';
                                        } else {
                                            $status_stok = 'Ok';
                                            $badge_class = 'bg-success';
                                        }
                                    @endphp

                                    <span class="badge {{ $badge_class }}">
                                        {{ $status_stok }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $value->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="deleteItem('{{ route('inventory.destroy',['id' => $value->id]) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
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

@foreach ($dataBarang as $barang )
<div class="modal fade" id="editModal{{ $barang->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('inventory.update', ['id' => $barang->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">
                                Part Number
                            </label>

                            <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}"
                                class="form-control @error('kode_barang') is-invalid @enderror"
                                placeholder="Enter Part Number">

                            @error('kode_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">
                                Part Name
                            </label>

                            <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                                class="form-control @error('nama_barang') is-invalid @enderror"
                                placeholder="Enter Part Name">

                            @error('nama_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">
                                Unit
                            </label>
                            <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}"
                                class="form-control @error('satuan') is-invalid @enderror"
                                placeholder="PCS / KG / LTR">
                            @error('satuan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">
                                Minimum Stock
                            </label>
                            <input type="number" name="min_stok" value="{{ old('min_stok', $barang->min_stok) }}"
                                class="form-control @error('min_stok') is-invalid @enderror"
                                placeholder="Enter Minimum Stock">
                            @error('min_stok')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- <div class="col-md-12">
                            <label class="form-label small fw-semibold">
                                Stock
                            </label>
                            <input type="number" name="stok" value="{{ old('stok', $barang->stok->qty) }}"
                                class="form-control @error('stok') is-invalid @enderror"
                                placeholder="Enter Actual Stock">
                            @error('stok')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div> --}}

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">
                                Selling Price
                            </label>
                            <input type="number" name="harga" value="{{ old('harga', $barang->harga) }}"
                                class="form-control @error('harga') is-invalid @enderror"
                                placeholder="Enter Selling Price" step="0.01">
                            @error('harga')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@include('components.footer')
@endsection
