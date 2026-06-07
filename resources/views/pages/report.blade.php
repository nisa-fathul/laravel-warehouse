<?php
// pages/report.php

// Determine period
$period = $_GET['period'] ?? 'monthly';
$allowed_periods = ['daily','weekly','monthly'];
if (!in_array($period, $allowed_periods)) $period = 'monthly';

// Period label map
$period_labels = [
  'daily'   => ['Hari Ini','Kemarin','2 Hari Lalu','3 Hari Lalu','4 Hari Lalu','5 Hari Lalu','6 Hari Lalu'],
  'weekly'  => ['Minggu Ini','Minggu Lalu','2 Mgg Lalu','3 Mgg Lalu'],
  'monthly' => ['Jul','Agu','Sep','Okt','Nov','Des'],
];
$xLabels = $period_labels[$period];

// Choose dataset based on period
$sales_data = match($period) {
  'daily'   => $daily_sales,
  'weekly'  => $weekly_sales,
  default   => $monthly_sales,
};

// Compute totals per rep for selected period
$rep_totals = [];
foreach ($sales_reps as $rep) {
  $total = array_sum($sales_data[$rep['id']]);
  $last  = end($sales_data[$rep['id']]);
  $prev  = $sales_data[$rep['id']][count($sales_data[$rep['id']]) - 2] ?? 0;
  $growth = $prev > 0 ? round(($last - $prev) / $prev * 100, 1) : 0;
  $target = match($period) {
    'daily'  => $rep['target_monthly'] / 25,
    'weekly' => $rep['target_monthly'] * 4,
    default  => $rep['target_monthly'] * 6,
  };
  $attainment = $target > 0 ? round($total / $target * 100, 1) : 0;
  $rep_totals[] = [
    'rep'        => $rep,
    'total'      => $total,
    'last'       => $last,
    'growth'     => $growth,
    'target'     => $target,
    'attainment' => $attainment,
  ];
}

// Sort by total desc
usort($rep_totals, fn($a,$b) => $b['total'] <=> $a['total']);

// Aggregate
$grand_total    = array_sum(array_column($rep_totals, 'total'));
$grand_target   = array_sum(array_column($rep_totals, 'target'));
$avg_attainment = round(array_sum(array_column($rep_totals, 'attainment')) / count($rep_totals), 1);
$top_rep        = $rep_totals[0];

// Category sales for period
$cat_period_sales = [];
foreach ($category_sales as $cat => $vals) {
  $n = count($vals);
  $cat_period_sales[$cat] = match($period) {
    'daily'   => end($vals) / 20, // approximate daily
    'weekly'  => array_sum(array_slice($vals, -1)) / 4,
    default   => array_sum($vals),
  };
}
arsort($cat_period_sales);

// Latest fulfillment metrics
$latest_fm = end($fulfillment_metrics);

// Prepare JSON for charts
$chartRepNames  = array_map(fn($r) => $r['rep']['name'], $rep_totals);
$chartRepTotals = array_map(fn($r) => $r['total'], $rep_totals);
$chartRepTargets= array_map(fn($r) => $r['target'], $rep_totals);

// Multi-line data: all reps over time
$lineData = [];
foreach ($sales_reps as $rep) {
  $lineData[] = ['name' => $rep['name'], 'data' => $sales_data[$rep['id']]];
}

// Category chart data
$catNames  = array_keys($cat_period_sales);
$catValues = array_values($cat_period_sales);

$fm_labels = array_column($fulfillment_metrics, 'month');
$fm_ontime = array_column($fulfillment_metrics, 'on_time');
$fm_conv   = array_column($fulfillment_metrics, 'conversion_rate');
?>

<!-- Period Tabs -->
<div class="period-tabs">
  <div class="period-tabs-inner">
    <?php foreach (['daily'=>'Harian','weekly'=>'Mingguan','monthly'=>'Bulanan'] as $p => $label): ?>
    <a href="?page=report&period=<?= $p ?>"
       class="period-tab <?= $period===$p ? 'active':'' ?>">
      <?= $label ?>
    </a>
    <?php endforeach; ?>
  </div>
  <div class="report-actions">
    <button class="btn-download" onclick="downloadPDF()">
      <span>⬇</span> Download PDF
    </button>
    <button class="btn-download btn-download-secondary" onclick="window.print()">
      <span>⎙</span> Print
    </button>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- PRINTABLE / PDF AREA                                       -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div id="pdf-report">

  <!-- Report Header (only visible in PDF/print) -->
  <div class="report-header-block">
    <div class="rhb-left">
      <div class="rhb-logo">SW</div>
      <div>
        <div class="rhb-title">STOCKWISE — Warehouse Intelligence</div>
        <div class="rhb-sub">Laporan Sales Effectiveness · <?= ucfirst($period) ?></div>
      </div>
    </div>
    <div class="rhb-right">
      <div class="rhb-date">Dicetak: <?= date('d/m/Y H:i') ?></div>
      <div class="rhb-period">Periode: <strong><?= match($period){'daily'=>'7 Hari Terakhir','weekly'=>'4 Minggu Terakhir',default=>'6 Bulan Terakhir'} ?></strong></div>
    </div>
  </div>

  <!-- KPI Row -->
  <div class="kpi-grid report-kpi" style="grid-template-columns:repeat(5,1fr); margin-bottom:1.5rem">
    <div class="kpi-card accent">
      <div class="kpi-label">Total Revenue</div>
      <div class="kpi-value">Rp <?= fmt($grand_total) ?></div>
      <div class="kpi-sub">Semua sales rep</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Target</div>
      <div class="kpi-value">Rp <?= fmt($grand_target) ?></div>
      <div class="kpi-sub">Kumulatif target</div>
    </div>
    <div class="kpi-card <?= $avg_attainment >= 90 ? '' : ($avg_attainment >= 70 ? 'warn' : 'danger') ?>">
      <div class="kpi-label">Avg Attainment</div>
      <div class="kpi-value"><?= $avg_attainment ?>%</div>
      <div class="kpi-sub">Pencapaian rata-rata</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Top Performer</div>
      <div class="kpi-value" style="font-size:16px;line-height:1.2"><?= explode(' ', $top_rep['rep']['name'])[0] ?></div>
      <div class="kpi-sub">Rp <?= fmt($top_rep['total']) ?></div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">On-Time Delivery</div>
      <div class="kpi-value"><?= $latest_fm['on_time'] ?>%</div>
      <div class="kpi-sub">Bulan terakhir</div>
    </div>
  </div>

  <!-- Row 1: Trend + Attainment -->
  <div class="two-col" style="margin-bottom:1.4rem">
    <div class="panel">
      <div class="panel-head"><span>Tren Revenue per Sales Rep</span></div>
      <div class="chart-wrap"><canvas id="lineChart" height="200"></canvas></div>
    </div>
    <div class="panel">
      <div class="panel-head"><span>Pencapaian vs Target</span></div>
      <div class="chart-wrap"><canvas id="barChart" height="200"></canvas></div>
    </div>
  </div>

  <!-- Row 2: Category + Fulfillment -->
  <div class="two-col" style="margin-bottom:1.4rem">
    <div class="panel">
      <div class="panel-head"><span>Revenue per Kategori Produk</span></div>
      <div class="chart-wrap"><canvas id="doughnutChart" height="200"></canvas></div>
    </div>
    <div class="panel">
      <div class="panel-head"><span>On-Time Delivery & Conversion Rate (%)</span></div>
      <div class="chart-wrap"><canvas id="lineChart2" height="200"></canvas></div>
    </div>
  </div>

  <!-- Sales Rep Detail Table -->
  <div class="panel" style="margin-bottom:1.4rem">
    <div class="panel-head"><span>Detail Performa Sales Rep</span></div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
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
        <tbody>
          <?php foreach ($rep_totals as $idx => $r):
            $pct = min(100, $r['attainment']);
            $bar_col = $r['attainment'] >= 100 ? 'var(--ok)' : ($r['attainment'] >= 80 ? 'var(--accent)' : ($r['attainment'] >= 60 ? 'var(--warn)' : 'var(--danger)'));
            $status_label = $r['attainment'] >= 100 ? ['Achieved','ok'] : ($r['attainment'] >= 80 ? ['On Track','info'] : ($r['attainment'] >= 60 ? ['At Risk','warn'] : ['Below Target','crit']));
          ?>
          <tr>
            <td>
              <?php if ($idx===0): ?>
                <span class="rank-gold">#1</span>
              <?php elseif ($idx===1): ?>
                <span class="rank-silver">#2</span>
              <?php elseif ($idx===2): ?>
                <span class="rank-bronze">#3</span>
              <?php else: ?>
                <span class="text-muted mono">#<?= $idx+1 ?></span>
              <?php endif; ?>
            </td>
            <td>
              <div class="rep-name"><?= htmlspecialchars($r['rep']['name']) ?></div>
              <div class="item-supplier"><?= $r['rep']['id'] ?></div>
            </td>
            <td><?= htmlspecialchars($r['rep']['region']) ?></td>
            <td class="mono"><strong>Rp <?= fmt($r['total']) ?></strong></td>
            <td class="mono text-muted">Rp <?= fmt($r['target']) ?></td>
            <td style="min-width:140px">
              <div style="display:flex;align-items:center;gap:8px">
                <div class="mini-bar" style="flex:1">
                  <div class="mini-bar-fill" style="width:<?= $pct ?>%;background:<?= $bar_col ?>"></div>
                </div>
                <span class="mono" style="color:<?= $bar_col ?>;font-size:11px;min-width:38px"><?= $r['attainment'] ?>%</span>
              </div>
            </td>
            <td class="mono <?= $r['growth'] >= 0 ? 'text-ok' : 'text-danger' ?>">
              <?= $r['growth'] >= 0 ? '▲' : '▼' ?> <?= abs($r['growth']) ?>%
            </td>
            <td class="mono">Rp <?= fmt($r['last']) ?></td>
            <td><span class="badge-pill badge-<?= $status_label[1] ?>"><?= $status_label[0] ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Category Revenue Table -->
  <div class="two-col" style="margin-bottom:1.4rem">
    <div class="panel">
      <div class="panel-head"><span>Revenue per Kategori</span></div>
      <table class="data-table">
        <thead>
          <tr><th>Kategori</th><th>Revenue</th><th>Share</th><th>Bar</th></tr>
        </thead>
        <tbody>
          <?php foreach ($cat_period_sales as $cat => $val):
            $share = round($val / array_sum($cat_period_sales) * 100, 1);
          ?>
          <tr>
            <td><?= htmlspecialchars($cat) ?></td>
            <td class="mono">Rp <?= fmt($val) ?></td>
            <td class="mono"><?= $share ?>%</td>
            <td style="min-width:100px">
              <div class="mini-bar"><div class="mini-bar-fill status-ok" style="width:<?= $share ?>%"></div></div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="panel">
      <div class="panel-head"><span>Top Customers</span></div>
      <table class="data-table">
        <thead>
          <tr><th>Pelanggan</th><th>Orders</th><th>Revenue</th><th>Growth</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($top_customers as $c): ?>
          <tr>
            <td><div class="rep-name"><?= htmlspecialchars($c['name']) ?></div></td>
            <td class="mono"><?= $c['orders'] ?></td>
            <td class="mono">Rp <?= fmt($c['revenue']) ?></td>
            <td class="mono <?= $c['growth'] >= 0 ? 'text-ok':'text-danger' ?>">
              <?= $c['growth'] >= 0 ? '▲' : '▼' ?> <?= abs($c['growth']) ?>%
            </td>
            <td>
              <?php
                $sc = match($c['status']){
                  'Active'  => 'badge-neutral',
                  'Growing' => 'badge-ok',
                  'At Risk' => 'badge-warn',
                  default   => 'badge-neutral',
                };
              ?>
              <span class="badge-pill <?= $sc ?>"><?= $c['status'] ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Fulfillment Metrics Table -->
  <div class="panel" style="margin-bottom:1.4rem">
    <div class="panel-head"><span>Metrik Fulfillment Bulanan</span></div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Bulan</th>
            <th>On-Time Delivery</th>
            <th>Return Rate</th>
            <th>Avg Order Value</th>
            <th>Total Orders</th>
            <th>Conversion Rate</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($fulfillment_metrics as $fm): ?>
          <tr>
            <td class="mono"><?= $fm['month'] ?></td>
            <td>
              <div style="display:flex;align-items:center;gap:6px">
                <div class="mini-bar" style="width:80px"><div class="mini-bar-fill status-ok" style="width:<?= $fm['on_time'] ?>%"></div></div>
                <span class="mono"><?= $fm['on_time'] ?>%</span>
              </div>
            </td>
            <td class="mono <?= $fm['return_rate'] <= 2 ? 'text-ok' : 'text-warn' ?>"><?= $fm['return_rate'] ?>%</td>
            <td class="mono">Rp <?= fmt($fm['avg_order_val']) ?></td>
            <td class="mono"><?= $fm['total_orders'] ?> orders</td>
            <td>
              <div style="display:flex;align-items:center;gap:6px">
                <div class="mini-bar" style="width:80px"><div class="mini-bar-fill" style="width:<?= $fm['conversion_rate'] ?>%;background:var(--accent)"></div></div>
                <span class="mono"><?= $fm['conversion_rate'] ?>%</span>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Report Footer -->
  <div class="report-footer-block">
    <span>STOCKWISE Warehouse Intelligence · Warehouse A — Jakarta</span>
    <span>Laporan digenerate otomatis · <?= date('d M Y H:i') ?> WIB</span>
  </div>

</div><!-- end #pdf-report -->

<!-- Chart.js & html2canvas & jsPDF via CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// ── Chart data from PHP ──────────────────────────────────────
const xLabels    = <?= json_encode($xLabels) ?>;
const lineData   = <?= json_encode($lineData) ?>;
const repNames   = <?= json_encode($chartRepNames) ?>;
const repTotals  = <?= json_encode($chartRepTotals) ?>;
const repTargets = <?= json_encode($chartRepTargets) ?>;
const catNames   = <?= json_encode($catNames) ?>;
const catValues  = <?= json_encode($catValues) ?>;
const fmLabels   = <?= json_encode($fm_labels) ?>;
const fmOntime   = <?= json_encode($fm_ontime) ?>;
const fmConv     = <?= json_encode($fm_conv) ?>;

// ── Color Palette ────────────────────────────────────────────
const COLORS = ['#00e5a0','#3b82f6','#f59e0b','#a78bfa','#f472b6'];
const COLORS_FADE = COLORS.map(c => c + '33');

const chartDefaults = {
  color: '#7a8799',
  borderColor: '#1e242d',
};
Chart.defaults.color = '#7a8799';
Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';
Chart.defaults.font.family = "'JetBrains Mono', monospace";
Chart.defaults.font.size = 11;

// ── Line Chart: Trend per rep ────────────────────────────────
new Chart(document.getElementById('lineChart'), {
  type: 'line',
  data: {
    labels: xLabels,
    datasets: lineData.map((rep, i) => ({
      label: rep.name.split(' ')[0],
      data: rep.data,
      borderColor: COLORS[i],
      backgroundColor: COLORS_FADE[i],
      borderWidth: 2,
      tension: 0.4,
      pointRadius: 3,
      pointHoverRadius: 5,
    }))
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14 } } },
    scales: {
      y: { ticks: { callback: v => 'Rp ' + (v >= 1e9 ? (v/1e9).toFixed(1)+'M' : (v/1e6).toFixed(0)+'jt') } }
    }
  }
});

// ── Bar Chart: Actual vs Target ──────────────────────────────
new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: repNames.map(n => n.split(' ')[0]),
    datasets: [
      {
        label: 'Actual',
        data: repTotals,
        backgroundColor: '#00e5a066',
        borderColor: '#00e5a0',
        borderWidth: 1.5,
        borderRadius: 4,
      },
      {
        label: 'Target',
        data: repTargets,
        backgroundColor: '#3b82f622',
        borderColor: '#3b82f6',
        borderWidth: 1.5,
        borderDash: [4, 2],
        borderRadius: 4,
        type: 'bar',
      }
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14 } } },
    scales: {
      y: { ticks: { callback: v => 'Rp ' + (v/1e6).toFixed(0)+'jt' } }
    }
  }
});

// ── Doughnut: Category revenue ───────────────────────────────
new Chart(document.getElementById('doughnutChart'), {
  type: 'doughnut',
  data: {
    labels: catNames,
    datasets: [{
      data: catValues,
      backgroundColor: ['#00e5a0','#3b82f6','#f59e0b','#a78bfa','#f472b6','#34d399','#fb923c','#60a5fa'],
      borderColor: '#111418',
      borderWidth: 2,
      hoverOffset: 8,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'right', labels: { boxWidth: 10, padding: 10, font: { size: 10 } } },
      tooltip: {
        callbacks: {
          label: ctx => ' Rp ' + (ctx.parsed/1e6).toFixed(1) + ' jt (' + Math.round(ctx.parsed / catValues.reduce((a,b)=>a+b,0)*100) + '%)'
        }
      }
    }
  }
});

// ── Line Chart 2: On-time + Conversion ──────────────────────
new Chart(document.getElementById('lineChart2'), {
  type: 'line',
  data: {
    labels: fmLabels,
    datasets: [
      {
        label: 'On-Time Delivery %',
        data: fmOntime,
        borderColor: '#00e5a0',
        backgroundColor: '#00e5a022',
        borderWidth: 2,
        tension: 0.4,
        pointRadius: 4,
        fill: true,
      },
      {
        label: 'Conversion Rate %',
        data: fmConv,
        borderColor: '#3b82f6',
        backgroundColor: '#3b82f622',
        borderWidth: 2,
        tension: 0.4,
        pointRadius: 4,
        fill: true,
      }
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14 } } },
    scales: {
      y: { min: 60, max: 100, ticks: { callback: v => v + '%' } }
    }
  }
});

// ── PDF Download ─────────────────────────────────────────────
async function downloadPDF() {
  const btn = document.querySelector('.btn-download');
  btn.textContent = '⏳ Generating PDF…';
  btn.disabled = true;

  const el = document.getElementById('pdf-report');

  // Temporarily switch to print-friendly colors
  el.classList.add('pdf-mode');

  try {
    const canvas = await html2canvas(el, {
      scale: 2,
      useCORS: true,
      backgroundColor: '#ffffff',
      logging: false,
      windowWidth: 1200,
    });

    const imgData = canvas.toDataURL('image/jpeg', 0.92);
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

    const pdfW = pdf.internal.pageSize.getWidth();
    const pdfH = pdf.internal.pageSize.getHeight();
    const imgW = canvas.width;
    const imgH = canvas.height;
    const ratio = pdfW / imgW;
    const scaledH = imgH * ratio;

    const pageHeightPx = pdfH / ratio;
    let yOffset = 0;
    let pageNum = 0;

    while (yOffset < imgH) {
      if (pageNum > 0) pdf.addPage();
      const sliceCanvas = document.createElement('canvas');
      sliceCanvas.width = imgW;
      sliceCanvas.height = Math.min(pageHeightPx, imgH - yOffset);
      const ctx2 = sliceCanvas.getContext('2d');
      ctx2.drawImage(canvas, 0, yOffset, imgW, sliceCanvas.height, 0, 0, imgW, sliceCanvas.height);
      const sliceData = sliceCanvas.toDataURL('image/jpeg', 0.92);
      pdf.addImage(sliceData, 'JPEG', 0, 0, pdfW, sliceCanvas.height * ratio, '', 'FAST');
      yOffset += pageHeightPx;
      pageNum++;
    }

    const periodLabel = { daily: 'Harian', weekly: 'Mingguan', monthly: 'Bulanan' };
    const today = new Date().toISOString().slice(0,10);
    pdf.save(`Laporan_Sales_${periodLabel['<?= $period ?>']}_${today}.pdf`);

  } catch(e) {
    alert('Gagal generate PDF: ' + e.message);
  } finally {
    el.classList.remove('pdf-mode');
    btn.textContent = '⬇ Download PDF';
    btn.disabled = false;
  }
}
</script>
