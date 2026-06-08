@extends('html.html')

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@section('content')
@include('components.navbar')
@include('components.sidebar')

<main id="main" class="main">

    <div
        class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3 mb-4">
        <div class="btn-group role=" group" aria-label="Period Filter">
            <a href="#" class="btn btn-outline-warning text-dark px-4">Harian</a>
            <a href="#" class="btn btn-outline-warning text-dark px-4">Mingguan</a>
            <a href="#" class="btn btn-warning text-dark fw-bold px-4 active">Bulanan</a>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-dark d-flex align-items-center gap-2 px-3" onclick="downloadPDF()">
                <span>⬇</span> Download PDF
            </button>
            <button class="btn btn-light border d-flex align-items-center gap-2 px-3" onclick="window.print()">
                <span>⎙</span> Print
            </button>
        </div>
    </div>

    <div id="pdf-report" class="bg-white rounded shadow-sm p-3 p-md-4">

        <div class="row align-items-center border-bottom pb-4 mb-4 g-3">
            <div class="col-12 col-md-7 d-flex align-items-center gap-3">
                <div class="bg-warning text-dark fw-black rounded d-flex align-items-center justify-content-center fw-bold fs-4"
                    style="width: 50px; height: 50px;">SW</div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark text-uppercase tracking-wide">STOCKWISE — Warehouse Intelligence
                    </h5>
                    <small class="text-muted fw-semibold">Laporan Sales Effectiveness · Monthly</small>
                </div>
            </div>
            <div class="col-12 col-md-5 text-md-end">
                <div class="text-muted small font-monospace">Dicetak: 08/06/2026 16:44</div>
                <div class="small fw-semibold mt-1 text-secondary">Periode: <span class="text-dark fw-bold">6 Bulan
                        Terakhir</span></div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl">
                <div class="card h-100 bg-warning bg-opacity-10 border-warning border-start border-4 shadow-sm">
                    <div class="card-body p-3">
                        <div class="text-muted small fw-bold text-uppercase">Total Revenue</div>
                        <div class="fs-4 fw-bold my-1 text-dark">Rp 4,250,000,000</div>
                        <div class="text-secondary small">Semua sales rep</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl">
                <div class="card h-100 border-start border-primary border-4 shadow-sm">
                    <div class="card-body p-3">
                        <div class="text-muted small fw-bold text-uppercase">Target</div>
                        <div class="fs-4 fw-bold my-1">Rp 4,500,000,000</div>
                        <div class="text-secondary small">Kumulatif target</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl">
                <div class="card h-100 border-start border-success border-4 shadow-sm">
                    <div class="card-body p-3">
                        <div class="text-muted small fw-bold text-uppercase">Avg Attainment</div>
                        <div class="fs-4 fw-bold my-1 text-success">94.4%</div>
                        <div class="text-secondary small">Pencapaian rata-rata</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl">
                <div class="card h-100 border-start border-info border-4 shadow-sm">
                    <div class="card-body p-3">
                        <div class="text-muted small fw-bold text-uppercase">Top Performer</div>
                        <div class="fs-4 fw-bold my-1 text-truncate">Budi</div>
                        <div class="text-secondary small">Rp 1,550,000,000</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl">
                <div class="card h-100 border-start border-dark border-4 shadow-sm">
                    <div class="card-body p-3">
                        <div class="text-muted small fw-bold text-uppercase">On-Time Delivery</div>
                        <div class="fs-4 fw-bold my-1">94.2%</div>
                        <div class="text-secondary small">Bulan terakhir</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-secondary">Tren Revenue per Sales Rep</h6>
                    </div>
                    <div class="card-body"><canvas id="lineChart" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-secondary">Pencapaian vs Target</h6>
                    </div>
                    <div class="card-body"><canvas id="barChart" height="220"></canvas></div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-secondary">Revenue per Kategori Produk</h6>
                    </div>
                    <div class="card-body"><canvas id="doughnutChart" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-secondary">On-Time Delivery & Conversion Rate (%)</h6>
                    </div>
                    <div class="card-body"><canvas id="lineChart2" height="220"></canvas></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-light mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-secondary">Detail Performa Sales Rep</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle w-100 report-datatable">
                        <thead class="table-light small text-uppercase text-secondary">
                            <tr>
                                <th>Rank</th>
                                <th>Sales Rep</th>
                                <th>Region</th>
                                <th>Total Revenue</th>
                                <th>Target</th>
                                <th>Attainment</th>
                                <th>Pertumbuhan</th>
                                <th>Periode Terbaru</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr>
                                <td><span class="badge bg-warning text-dark fw-bold px-2 py-1">#1</span></td>
                                <td>
                                    <div class="fw-bold">Budi Santoso</div>
                                    <small class="text-muted font-monospace">REP-001</small>
                                </td>
                                <td>DKI Jakarta</td>
                                <td class="font-monospace fw-bold">Rp 1,550,000,000</td>
                                <td class="font-monospace text-muted">Rp 1,500,000,000</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="min-width: 130px;">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 100%"></div>
                                        </div>
                                        <span class="font-monospace text-success fw-bold">103.3%</span>
                                    </div>
                                </td>
                                <td class="font-monospace text-success">▲ 12.4%</td>
                                <td class="font-monospace">Rp 280,000,000</td>
                                <td><span class="badge bg-success rounded-pill px-2">Achieved</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary text-white fw-bold px-2 py-1">#2</span></td>
                                <td>
                                    <div class="fw-bold">Siti Rahmawati</div>
                                    <small class="text-muted font-monospace">REP-002</small>
                                </td>
                                <td>Jawa Barat</td>
                                <td class="font-monospace fw-bold">Rp 1,420,000,000</td>
                                <td class="font-monospace text-muted">Rp 1,500,000,000</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar bg-info" style="width: 94.6%"></div>
                                        </div>
                                        <span class="font-monospace text-info fw-bold">94.6%</span>
                                    </div>
                                </td>
                                <td class="font-monospace text-success">▲ 4.1%</td>
                                <td class="font-monospace">Rp 245,000,000</td>
                                <td><span class="badge bg-info rounded-pill px-2">On Track</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger bg-opacity-70 text-white fw-bold px-2 py-1">#3</span>
                                </td>
                                <td>
                                    <div class="fw-bold">Rian Hidayat</div>
                                    <small class="text-muted font-monospace">REP-003</small>
                                </td>
                                <td>Jawa Timur</td>
                                <td class="font-monospace fw-bold">Rp 1,280,000,000</td>
                                <td class="font-monospace text-muted">Rp 1,500,000,000</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: 85.3%"></div>
                                        </div>
                                        <span class="font-monospace text-warning fw-bold">85.3%</span>
                                    </div>
                                </td>
                                <td class="font-monospace text-danger">▼ -2.5%</td>
                                <td class="font-monospace">Rp 210,000,000</td>
                                <td><span class="badge bg-warning text-dark rounded-pill px-2">At Risk</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-secondary">Revenue per Kategori</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped align-middle w-100 report-datatable-simple">
                                <thead class="table-light small text-secondary">
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Revenue</th>
                                        <th>Share</th>
                                        <th>Bar</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    <tr>
                                        <td class="fw-bold">Electronics</td>
                                        <td class="font-monospace">Rp 1,912,500,000</td>
                                        <td class="font-monospace">45.0%</td>
                                        <td style="width: 100px;">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 45%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Computers & Acc</td>
                                        <td class="font-monospace">Rp 1,487,500,000</td>
                                        <td class="font-monospace">35.0%</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 35%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Office Supplies</td>
                                        <td class="font-monospace">Rp 850,000,000</td>
                                        <td class="font-monospace">20.0%</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 20%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-secondary">Top Customers</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped align-middle w-100 report-datatable-simple">
                                <thead class="table-light small text-secondary">
                                    <tr>
                                        <th>Pelanggan</th>
                                        <th>Orders</th>
                                        <th>Revenue</th>
                                        <th>Growth</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    <tr>
                                        <td class="fw-bold">PT Mega Surya Nusantara</td>
                                        <td class="font-monospace">42</td>
                                        <td class="font-monospace">Rp 650,000,000</td>
                                        <td class="font-monospace text-success">▲ 14.2%</td>
                                        <td><span
                                                class="badge bg-success-subtle text-success rounded-pill px-2">Growing</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">CV Global Tech Utama</td>
                                        <td class="font-monospace">31</td>
                                        <td class="font-monospace">Rp 480,000,000</td>
                                        <td class="font-monospace text-success">▲ 5.8%</td>
                                        <td><span class="badge bg-light text-dark rounded-pill px-2">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Lazada Indonesia Hub</td>
                                        <td class="font-monospace">18</td>
                                        <td class="font-monospace">Rp 320,000,000</td>
                                        <td class="font-monospace text-danger">▼ -8.4%</td>
                                        <td><span class="badge bg-warning-subtle text-warning rounded-pill px-2">At
                                                Risk</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-light">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-secondary">Metrik Fulfillment Bulanan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle w-100 report-datatable-simple">
                        <thead class="table-light small text-secondary">
                            <tr>
                                <th>Bulan</th>
                                <th>On-Time Delivery</th>
                                <th>Return Rate</th>
                                <th>Avg Order Value</th>
                                <th>Total Orders</th>
                                <th>Conversion Rate</th>
                            </tr>
                        </thead>
                        <tbody class="small font-monospace">
                            <tr>
                                <td class="fw-bold text-dark">April 2026</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="width: 140px">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 92%"></div>
                                        </div>
                                        <span>92.0%</span>
                                    </div>
                                </td>
                                <td class="text-success">1.2%</td>
                                <td>Rp 14,500,000</td>
                                <td>148 orders</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="width: 140px">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: 74%"></div>
                                        </div>
                                        <span>74.0%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-dark">Mei 2026</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 94.2%"></div>
                                        </div>
                                        <span>94.2%</span>
                                    </div>
                                </td>
                                <td class="text-success">0.8%</td>
                                <td>Rp 15,200,000</td>
                                <td>165 orders</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: 78.5%"></div>
                                        </div>
                                        <span>78.5%</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top mt-4 pt-3 small text-muted font-monospace">
            <span>STOCKWISE Warehouse Intelligence · Warehouse A — Jakarta</span>
            <span>Laporan digenerate otomatis · 08 Jun 2026 16:44 WIB</span>
        </div>

    </div>
</main>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    $(document).ready(function() {
  // Inisialisasi DataTables untuk tabel utama performa sales
  $('.report-datatable').DataTable({
    "pageLength": 5,
    "responsive": true,
    "order": [[ 0, "asc" ]],
    "language": { "search": "Cari Rep:", "lengthMenu": "_MENU_ baris" }
  });

  // Inisialisasi DataTables Tanpa Fitur Search/Paging untuk tabel ringkasan kecil
  $('.report-datatable-simple').DataTable({
    "paging": false,
    "searching": false,
    "info": false,
    "responsive": true
  });
});

// ── Dummy Chart Configuration & Palette ────────────────────────
const COLORS = ['#00e5a0','#3b82f6','#f59e0b','#a78bfa','#f472b6'];
const COLORS_FADE = ['#00e5a022','#3b82f622','#f59e0b22','#a78bfa22','#f472b622'];

Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = '#f1f5f9';
Chart.defaults.font.family = "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif";
Chart.defaults.font.size = 11;

// ── Line Chart: Trend per rep ────────────────────────────────
new Chart(document.getElementById('lineChart'), {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
    datasets: [
      { label: 'Budi', data: [200, 240, 220, 290, 320, 280], borderColor: COLORS[0], backgroundColor: COLORS_FADE[0], tension: 0.4, fill: true },
      { label: 'Siti', data: [180, 210, 250, 230, 305, 245], borderColor: COLORS[1], backgroundColor: COLORS_FADE[1], tension: 0.4, fill: true },
      { label: 'Rian', data: [150, 190, 210, 240, 280, 210], borderColor: COLORS[2], backgroundColor: COLORS_FADE[2], tension: 0.4, fill: true }
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } },
    scales: { y: { ticks: { callback: v => 'Rp ' + v + 'M' } } }
  }
});

// ── Bar Chart: Actual vs Target ──────────────────────────────
new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: ['Budi', 'Siti', 'Rian'],
    datasets: [
      { label: 'Actual', data: [1550, 1420, 1280], backgroundColor: '#00e5a0cc', borderRadius: 4 },
      { label: 'Target', data: [1500, 1500, 1500], backgroundColor: '#3b82f633', borderColor: '#3b82f6', borderWidth: 1, borderRadius: 4 }
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } },
    scales: { y: { ticks: { callback: v => 'Rp ' + v + 'jt' } } }
  }
});

// ── Doughnut: Category revenue ───────────────────────────────
new Chart(document.getElementById('doughnutChart'), {
  type: 'doughnut',
  data: {
    labels: ['Electronics', 'Computers', 'Office Supplies'],
    datasets: [{
      data: [1912500000, 1487500000, 850000000],
      backgroundColor: COLORS,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'right' } }
  }
});

// ── Line Chart 2: On-time + Conversion ──────────────────────
new Chart(document.getElementById('lineChart2'), {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
    datasets: [
      { label: 'On-Time Delivery %', data: [88, 91, 89, 92, 94.2, 95], borderColor: '#00e5a0', tension: 0.4 },
      { label: 'Conversion Rate %', data: [70, 72, 75, 74, 78.5, 80], borderColor: '#3b82f6', tension: 0.4 }
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } },
    scales: { y: { min: 60, max: 100, ticks: { callback: v => v + '%' } } }
  }
});

// ── PDF Download Function ────────────────────────────────────
async function downloadPDF() {
  const btn = document.querySelector('.btn-dark');
  btn.textContent = '⏳ Generating...';
  btn.disabled = true;

  const el = document.getElementById('pdf-report');

  try {
    const canvas = await html2canvas(el, { scale: 2, useCORS: true, backgroundColor: '#ffffff', logging: false });
    const imgData = canvas.toDataURL('image/jpeg', 0.95);
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

    const pdfW = pdf.internal.pageSize.getWidth();
    const pdfH = pdf.internal.pageSize.getHeight();
    const ratio = pdfW / canvas.width;

    pdf.addImage(imgData, 'JPEG', 0, 0, pdfW, canvas.height * ratio);
    pdf.save(`Laporan_Sales_Bulanan_2026.pdf`);
  } catch(e) {
    alert('Gagal membuat PDF: ' + e.message);
  } finally {
    btn.innerHTML = '<span>⬇</span> Download PDF';
    btn.disabled = false;
  }
}
</script>
