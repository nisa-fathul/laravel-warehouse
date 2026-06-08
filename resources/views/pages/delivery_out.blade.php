@extends('html.html')

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@section('content')
@include('components.navbar')
@include('components.sidebar')

<div class="flex-grow-1 overflow-hidden" id="page-content-wrapper">

  <main class="container-fluid py-4">
    
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-start border-warning border-4 shadow-sm">
          <div class="card-body">
            <div class="text-muted small fw-bold text-uppercase">Total Pengiriman</div>
            <div class="fs-3 fw-bold my-1">3</div>
            <div class="text-secondary small">Dokumen keluar</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-start border-success border-4 shadow-sm">
          <div class="card-body">
            <div class="text-muted small fw-bold text-uppercase">Nilai Barang Keluar</div>
            <div class="fs-3 fw-bold my-1">$4,125.00</div>
            <div class="text-secondary small">Total nilai dikirim</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-start border-info border-4 shadow-sm">
          <div class="card-body">
            <div class="text-muted small fw-bold text-uppercase">Internal Transfer</div>
            <div class="fs-3 fw-bold my-1">2</div>
            <div class="text-secondary small">Pindah antar divisi</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-start border-dark border-4 shadow-sm">
          <div class="card-body">
            <div class="text-muted small fw-bold text-uppercase">Outbound Shipment</div>
            <div class="fs-3 fw-bold my-1">1</div>
            <div class="text-secondary small">Ke pelanggan / eksternal</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-secondary">+ Catat Pengeluaran Baru</h5>
        <button class="btn btn-sm btn-outline-warning text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#dout-form-collapse" aria-expanded="false" aria-controls="dout-form-collapse" id="toggle-form-btn-out">
          Buka Form ▾
        </button>
      </div>
      <div class="collapse" id="dout-form-collapse">
        <div class="card-body bg-light-subtle">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Tanggal Keluar</label>
              <input type="date" class="form-control" value="2026-06-08">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Waktu</label>
              <input type="time" class="form-control" value="16:25">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Pilih SKU / Barang</label>
              <select class="form-select" onchange="fillItemOut(this)">
                <option value="">— Pilih item —</option>
                <option value="SKU-001" data-name="Laptop Asus ROG" data-unit="pcs" data-cost="1500.00" data-stock="15">SKU-001 — Laptop Asus ROG (Stok: 15 pcs)</option>
                <option value="SKU-002" data-name="Logitech G Pro X Wireless" data-unit="pcs" data-cost="125.00" data-stock="45">SKU-002 — Logitech G Pro X Wireless (Stok: 45 pcs)</option>
                <option value="SKU-003" data-name="Monitor Dell 24 Inch" data-unit="unit" data-cost="200.00" data-stock="8">SKU-003 — Monitor Dell 24 Inch (Stok: 8 unit)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Nama Barang</label>
              <input type="text" class="form-control" id="dout-item-name" placeholder="Otomatis terisi" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Tujuan / Departemen</label>
              <input type="text" class="form-control" placeholder="Cth: Production Line A">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Jenis Pengeluaran</label>
              <select class="form-select">
                <option>Internal Transfer</option>
                <option>Internal Use</option>
                <option>Outbound Shipment</option>
                <option>Return to Supplier</option>
                <option>Write-off / Disposal</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Qty Keluar</label>
              <input type="number" class="form-control" id="dout-qty" placeholder="0" oninput="checkStock()">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Satuan</label>
              <input type="text" class="form-control" id="dout-unit" placeholder="pcs / L / kg" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Stok Tersedia</label>
              <input type="text" class="form-control text-primary fw-bold" id="dout-stock" placeholder="—" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Diminta Oleh</label>
              <input type="text" class="form-control" placeholder="Nama pemohon">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Disetujui Oleh</label>
              <input type="text" class="form-control" placeholder="Nama approver">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Estimasi Nilai ($)</label>
              <input type="text" class="form-control fw-bold" id="dout-value" placeholder="—" readonly>
            </div>
            <div class="col-12">
              <label class="form-label small fw-semibold">Catatan</label>
              <textarea class="form-control" rows="2" placeholder="Catatan pengiriman..."></textarea>
            </div>
          </div>
          
          <div id="dout-stock-warn" class="alert alert-danger d-none mt-3 mb-0" role="alert">
            ⚠️ Qty melebihi stok tersedia!
          </div>

          <div class="mt-4 pt-3 border-top d-flex gap-2">
            <button class="btn btn-warning text-dark fw-bold" onclick="submitForm('dout')">✓ Simpan Pengeluaran</button>
            <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#dout-form-collapse">Batal</button>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-secondary">Riwayat Pengeluaran Barang</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle w-100" id="dout-table">
            <thead class="table-light text-secondary small text-uppercase">
              <tr>
                <th>No. Dokumen</th>
                <th>Tanggal / Waktu</th>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Tujuan</th>
                <th>Jenis</th>
                <th>Qty Keluar</th>
                <th>Nilai</th>
                <th>Diminta Oleh</th>
                <th>Disetujui</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody class="small">
              <tr>
                <td><span class="font-monospace fw-bold text-dark">DOUT-001</span></td>
                <td>
                  <div class="font-monospace" style="font-size: 12px;">2026-06-02</div>
                  <div class="text-muted" style="font-size: 11px;">08:15</div>
                </td>
                <td><span class="font-monospace text-warning fw-bold">SKU-001</span></td>
                <td>
                  <div class="fw-bold">Laptop Asus ROG</div>
                  <div class="text-muted" style="font-size: 11px;">📝 Alokasi untuk divisi Dev</div>
                </td>
                <td>IT Developer Dept</td>
                <td><span class="badge bg-info text-white rounded-pill">Internal Transfer</span></td>
                <td class="font-monospace text-warning fw-bold">2 pcs</td>
                <td class="font-monospace fw-semibold">$3,000.00</td>
                <td>Rian Admin</td>
                <td>Hendra Manager</td>
                <td><span class="badge bg-success">Dispatched</span></td>
              </tr>
              <tr>
                <td><span class="font-monospace fw-bold text-dark">DOUT-002</span></td>
                <td>
                  <div class="font-monospace" style="font-size: 12px;">2026-06-04</div>
                  <div class="text-muted" style="font-size: 11px;">11:00</div>
                </td>
                <td><span class="font-monospace text-warning fw-bold">SKU-002</span></td>
                <td>
                  <div class="fw-bold">Logitech G Pro X Wireless</div>
                </td>
                <td>Production Line A</td>
                <td><span class="badge bg-secondary rounded-pill">Internal Use</span></td>
                <td class="font-monospace text-warning fw-bold">5 pcs</td>
                <td class="font-monospace fw-semibold">$625.00</td>
                <td>Siti Rahma</td>
                <td>Hendra Manager</td>
                <td><span class="badge bg-success">Dispatched</span></td>
              </tr>
              <tr>
                <td><span class="font-monospace fw-bold text-dark">DOUT-003</span></td>
                <td>
                  <div class="font-monospace" style="font-size: 12px;">2026-06-06</div>
                  <div class="text-muted" style="font-size: 11px;">15:30</div>
                </td>
                <td><span class="font-monospace text-warning fw-bold">SKU-003</span></td>
                <td>
                  <div class="fw-bold">Monitor Dell 24 Inch</div>
                  <div class="text-muted" style="font-size: 11px;">📝 Kirim ke Toko Cabang</div>
                </td>
                <td>Malang Branch Customer</td>
                <td><span class="badge bg-warning text-dark rounded-pill">Outbound Shipment</span></td>
                <td class="font-monospace text-warning fw-bold">2 unit</td>
                <td class="font-monospace fw-semibold">$450.00</td>
                <td>Budi Santoso</td>
                <td>Agus Head</td>
                <td><span class="badge bg-success">Dispatched</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-secondary">Ringkasan Pengeluaran per SKU</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 bg-light-subtle border-light-subtle">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="font-monospace text-warning fw-bold small">SKU-001</span>
                  <span class="badge bg-secondary-subtle text-dark rounded-pill">1 txn</span>
                </div>
                <h6 class="card-title fw-bold text-truncate">Laptop Asus ROG</h6>
                <hr class="my-2 opacity-50">
                <div class="d-flex justify-content-between small mb-1">
                  <span class="text-muted">Total Keluar</span>
                  <span class="text-warning fw-bold">2 pcs</span>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                  <span class="text-muted">Total Nilai</span>
                  <span class="fw-semibold">$3,000.00</span>
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
                  <span class="font-monospace text-warning fw-bold small">SKU-002</span>
                  <span class="badge bg-secondary-subtle text-dark rounded-pill">1 txn</span>
                </div>
                <h6 class="card-title fw-bold text-truncate">Logitech G Pro X Wireless</h6>
                <hr class="my-2 opacity-50">
                <div class="d-flex justify-content-between small mb-1">
                  <span class="text-muted">Total Keluar</span>
                  <span class="text-warning fw-bold">5 pcs</span>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                  <span class="text-muted">Total Nilai</span>
                  <span class="fw-semibold">$625.00</span>
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
                  <span class="font-monospace text-warning fw-bold small">SKU-003</span>
                  <span class="badge bg-secondary-subtle text-dark rounded-pill">1 txn</span>
                </div>
                <h6 class="card-title fw-bold text-truncate">Monitor Dell 24 Inch</h6>
                <hr class="my-2 opacity-50">
                <div class="d-flex justify-content-between small mb-1">
                  <span class="text-muted">Total Keluar</span>
                  <span class="text-warning fw-bold">2 unit</span>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                  <span class="text-muted">Total Nilai</span>
                  <span class="fw-semibold">$450.00</span>
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

    <div class="card shadow-sm">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-secondary">Distribusi Jenis Pengeluaran</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between small fw-semibold mb-1">
            <span><span class="text-info me-2">●</span>Internal Transfer</span>
            <span class="text-muted">73% ($3,000.00)</span>
          </div>
          <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-info" role="progressbar" style="width: 73%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between small fw-semibold mb-1">
            <span><span class="text-secondary me-2">●</span>Internal Use</span>
            <span class="text-muted">15% ($625.00)</span>
          </div>
          <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-secondary" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
        <div class="mb-0">
          <div class="d-flex justify-content-between small fw-semibold mb-1">
            <span><span class="text-warning me-2">●</span>Outbound Shipment</span>
            <span class="text-muted">12% ($450.00)</span>
          </div>
          <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>

  </main>
</div>

<script>
$(document).ready(function() {
  // Inisialisasi DataTables terintegrasi Bootstrap 5
  $('#dout-table').DataTable({
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
  const myCollapseOut = document.getElementById('dout-form-collapse');
  const toggleBtnOut = document.getElementById('toggle-form-btn-out');
  
  myCollapseOut.addEventListener('shown.bs.collapse', function () {
    toggleBtnOut.textContent = 'Tutup Form ▴';
  });
  myCollapseOut.addEventListener('hidden.bs.collapse', function () {
    toggleBtnOut.textContent = 'Buka Form ▾';
  });
});

let currentStock = 0;
let unitCost = 0;

function fillItemOut(sel) {
  const opt = sel.options[sel.selectedIndex];
  document.getElementById('dout-item-name').value = opt.dataset.name || '';
  document.getElementById('dout-unit').value       = opt.dataset.unit || '';
  
  currentStock = parseInt(opt.dataset.stock) || 0;
  unitCost     = parseFloat(opt.dataset.cost) || 0;
  
  document.getElementById('dout-stock').value = opt.dataset.stock
    ? currentStock + ' ' + opt.dataset.unit
    : '—';
  checkStock();
}

function checkStock() {
  const qty = parseInt(document.getElementById('dout-qty').value) || 0;
  const warn = document.getElementById('dout-stock-warn');
  
  if (qty > currentStock && currentStock > 0) {
    warn.classList.remove('d-none');
  } else {
    warn.classList.add('d-none');
  }
  
  const val = qty * unitCost;
  document.getElementById('dout-value').value = val > 0 ? '$' + val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '—';
}

function submitForm(type) {
  alert('Pengeluaran berhasil dicatat! (Demo — tidak tersimpan ke database)');
}
</script>
