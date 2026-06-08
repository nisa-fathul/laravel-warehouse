@extends('html.html')

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@section('content')
@include('components.navbar')
@include('components.sidebar')

<main class="container-fluid py-4">
  
  <div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card h-100 border-start border-primary border-4 shadow-sm">
        <div class="card-body">
          <div class="text-muted small fw-bold text-uppercase">Total Penerimaan</div>
          <div class="fs-3 fw-bold my-1">3</div>
          <div class="text-secondary small">Dokumen masuk</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card h-100 border-start border-success border-4 shadow-sm">
        <div class="card-body">
          <div class="text-muted small fw-bold text-uppercase">Nilai Barang Masuk</div>
          <div class="fs-3 fw-bold my-1">$10,750.00</div>
          <div class="text-secondary small">Total nilai diterima</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card h-100 border-start border-info border-4 shadow-sm">
        <div class="card-body">
          <div class="text-muted small fw-bold text-uppercase">Selesai</div>
          <div class="fs-3 fw-bold my-1">2</div>
          <div class="text-secondary small">Penerimaan lengkap</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card h-100 border-start border-warning border-4 shadow-sm">
        <div class="card-body">
          <div class="text-muted small fw-bold text-uppercase">Sebagian / Rusak</div>
          <div class="fs-3 fw-bold my-1 text-warning">1</div>
          <div class="text-secondary small">Perlu tindak lanjut</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
      <h5 class="mb-0 fw-bold text-secondary">+ Catat Penerimaan Baru</h5>
      <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#din-form-collapse" aria-expanded="false" aria-controls="din-form-collapse" id="toggle-form-btn">
        Buka Form ▾
      </button>
    </div>
    <div class="collapse" id="din-form-collapse">
      <div class="card-body bg-light-subtle">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label small fw-semibold">Tanggal Terima</label>
            <input type="date" class="form-control" value="2026-06-08">
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-semibold">Waktu</label>
            <input type="time" class="form-control" value="15:00">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Pilih SKU / Barang</label>
            <select class="form-select" onchange="fillItem(this)">
              <option value="">— Pilih item —</option>
              <option value="SKU-001" data-name="Laptop Asus ROG" data-unit="pcs" data-cost="1500.00">SKU-001 — Laptop Asus ROG</option>
              <option value="SKU-002" data-name="Logitech G Pro X Wireless" data-unit="pcs" data-cost="125.00">SKU-002 — Logitech G Pro X Wireless</option>
              <option value="SKU-003" data-name="Monitor Dell 24 Inch" data-unit="unit" data-cost="200.00">SKU-003 — Monitor Dell 24 Inch</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Nama Barang</label>
            <input type="text" class="form-control" id="din-item-name" placeholder="Otomatis terisi" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-semibold">Supplier</label>
            <input type="text" class="form-control" placeholder="Nama supplier">
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-semibold">No. PO Referensi</label>
            <input type="text" class="form-control" placeholder="PO-XXXX-XXX (opsional)">
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-semibold">Qty Dipesan</label>
            <input type="number" class="form-control" placeholder="0">
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-semibold">Qty Diterima</label>
            <input type="number" class="form-control" placeholder="0">
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-semibold">Satuan</label>
            <input type="text" class="form-control" id="din-unit" placeholder="pcs / L / kg" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-semibold">Harga Satuan ($)</label>
            <input type="number" class="form-control" id="din-cost" placeholder="0.00" step="0.01">
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Kondisi Barang</label>
            <select class="form-select">
              <option>Good</option>
              <option>Damaged</option>
              <option>Partial Damage</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label small fw-semibold">Diterima Oleh</label>
            <input type="text" class="form-control" placeholder="Nama petugas">
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Catatan</label>
            <textarea class="form-control" rows="2" placeholder="Catatan penerimaan..."></textarea>
          </div>
        </div>
        <div class="mt-4 pt-3 border-top d-flex gap-2">
          <button class="btn btn-primary" onclick="submitForm('din')">✓ Simpan Penerimaan</button>
          <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#din-form-collapse">Batal</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
      <h5 class="mb-0 fw-bold text-secondary">Riwayat Penerimaan Barang</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle w-100" id="din-table">
          <thead class="table-light text-secondary small text-uppercase">
            <tr>
              <th>No. Dokumen</th>
              <th>Tanggal / Waktu</th>
              <th>SKU</th>
              <th>Nama Barang</th>
              <th>Supplier</th>
              <th>Ref PO</th>
              <th>Qty Dipesan</th>
              <th>Qty Diterima</th>
              <th>Selisih</th>
              <th>Nilai</th>
              <th>Kondisi</th>
              <th>Diterima Oleh</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody class="small">
            <tr>
              <td><span class="font-monospace fw-bold text-dark">DIN-001</span></td>
              <td>
                <div class="font-monospace" style="font-size: 12px;">2026-06-05</div>
                <div class="text-muted" style="font-size: 11px;">10:30</div>
              </td>
              <td><span class="font-monospace text-primary fw-bold">SKU-001</span></td>
              <td>
                <div class="fw-bold">Laptop Asus ROG</div>
                <div class="text-muted" style="font-size: 11px;">📝 Dus agak penyok dikit luar...</div>
              </td>
              <td>PT. Asus Indonesia</td>
              <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">PO-2026-001</span></td>
              <td class="font-monospace">5 pcs</td>
              <td class="font-monospace">5 pcs</td>
              <td class="font-monospace text-muted">—</td>
              <td class="font-monospace fw-semibold">$7,500.00</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Good</span></td>
              <td>Budi Santoso</td>
              <td><span class="badge bg-success">Complete</span></td>
            </tr>
            <tr>
              <td><span class="font-monospace fw-bold text-dark">DIN-002</span></td>
              <td>
                <div class="font-monospace" style="font-size: 12px;">2026-06-06</div>
                <div class="text-muted" style="font-size: 11px;">14:15</div>
              </td>
              <td><span class="font-monospace text-primary fw-bold">SKU-002</span></td>
              <td>
                <div class="fw-bold">Logitech G Pro X Wireless</div>
                <div class="text-muted" style="font-size: 11px;">📝 2 Unit menyusul dari vendor</div>
              </td>
              <td>Logitech Official Store</td>
              <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">PO-2026-004</span></td>
              <td class="font-monospace">12 pcs</td>
              <td class="font-monospace">10 pcs</td>
              <td class="font-monospace text-danger fw-bold">-2</td>
              <td class="font-monospace fw-semibold">$1,250.00</td>
              <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Partial Damage</span></td>
              <td>Siti Rahma</td>
              <td><span class="badge bg-warning text-dark">Partial</span></td>
            </tr>
            <tr>
              <td><span class="font-monospace fw-bold text-dark">DIN-003</span></td>
              <td>
                <div class="font-monospace" style="font-size: 12px;">2026-06-07</div>
                <div class="text-muted" style="font-size: 11px;">09:00</div>
              </td>
              <td><span class="font-monospace text-primary fw-bold">SKU-003</span></td>
              <td>
                <div class="fw-bold">Monitor Dell 24 Inch</div>
              </td>
              <td>Dell Corp Indonesia</td>
              <td><span class="text-muted">—</span></td>
              <td class="font-monospace">10 unit</td>
              <td class="font-monospace">10 unit</td>
              <td class="font-monospace text-muted">—</td>
              <td class="font-monospace fw-semibold">$2,000.00</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Good</span></td>
              <td>Budi Santoso</td>
              <td><span class="badge bg-success">Complete</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white py-3">
      <h5 class="mb-0 fw-bold text-secondary">Ringkasan Penerimaan per SKU</h5>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100 bg-light-subtle border-light-subtle">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="font-monospace text-primary fw-bold small">SKU-001</span>
                <span class="badge bg-info-subtle text-info-emphasis rounded-pill">1 txn</span>
              </div>
              <h6 class="card-title fw-bold text-truncate">Laptop Asus ROG</h6>
              <hr class="my-2 opacity-50">
              <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Total Diterima</span>
                <span class="text-primary fw-bold">5 pcs</span>
              </div>
              <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Total Nilai</span>
                <span class="fw-semibold">$7,500.00</span>
              </div>
              <div class="d-flex justify-content-between small">
                <span class="text-muted">Stok Saat Ini</span>
                <span class="fw-bold">15</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100 bg-light-subtle border-light-subtle">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="font-monospace text-primary fw-bold small">SKU-002</span>
                <span class="badge bg-info-subtle text-info-emphasis rounded-pill">1 txn</span>
              </div>
              <h6 class="card-title fw-bold text-truncate">Logitech G Pro X Wireless</h6>
              <hr class="my-2 opacity-50">
              <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Total Diterima</span>
                <span class="text-primary fw-bold">10 pcs</span>
              </div>
              <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Total Nilai</span>
                <span class="fw-semibold">$1,250.00</span>
              </div>
              <div class="d-flex justify-content-between small">
                <span class="text-muted">Stok Saat Ini</span>
                <span class="fw-bold">45</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100 bg-light-subtle border-light-subtle">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="font-monospace text-primary fw-bold small">SKU-003</span>
                <span class="badge bg-info-subtle text-info-emphasis rounded-pill">1 txn</span>
              </div>
              <h6 class="card-title fw-bold text-truncate">Monitor Dell 24 Inch</h6>
              <hr class="my-2 opacity-50">
              <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Total Diterima</span>
                <span class="text-primary fw-bold">10 unit</span>
              </div>
              <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Total Nilai</span>
                <span class="fw-semibold">$2,000.00</span>
              </div>
              <div class="d-flex justify-content-between small">
                <span class="text-muted">Stok Saat Ini</span>
                <span class="fw-bold">8</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>

<script>
$(document).ready(function() {
  // Inisialisasi DataTables terintegrasi styling Bootstrap 5
  $('#din-table').DataTable({
    "pageLength": 10,
    "responsive": true,
    "language": {
      "search": "Cari data:",
      "lengthMenu": "Tampilkan _MENU_ entri",
      "zeroRecords": "Data tidak ditemukan",
      "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
      "infoFiltered": "(disaring dari _MAX_ total entri)",
      "paginate": {
        "next": "›",
        "previous": "‹"
      }
    }
  });

  // Sinkronisasi teks status tombol saat collapse Bootstrap dipicu
  const myCollapse = document.getElementById('din-form-collapse');
  const toggleBtn = document.getElementById('toggle-form-btn');
  
  myCollapse.addEventListener('shown.bs.collapse', function () {
    toggleBtn.textContent = 'Tutup Form ▴';
  });
  myCollapse.addEventListener('hidden.bs.collapse', function () {
    toggleBtn.textContent = 'Buka Form ▾';
  });
});

function fillItem(sel) {
  const opt = sel.options[sel.selectedIndex];
  document.getElementById('din-item-name').value = opt.dataset.name || '';
  document.getElementById('din-unit').value       = opt.dataset.unit || '';
  document.getElementById('din-cost').value       = opt.dataset.cost || '';
}

function submitForm(type) {
  const msg = type === 'din'
    ? 'Penerimaan berhasil dicatat! (Demo — tidak tersimpan ke database)'
    : 'Pengiriman berhasil dicatat!';
  alert(msg);
}
</script>
