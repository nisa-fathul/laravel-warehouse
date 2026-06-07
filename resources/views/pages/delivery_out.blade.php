<?php
// pages/delivery_out.php
$total_qty_out   = array_sum(array_column($delivery_out, 'qty'));
$total_value_out = array_sum(array_map(fn($d) => $d['qty'] * $d['unit_cost'], $delivery_out));

$type_counts = [];
foreach ($delivery_out as $d) $type_counts[$d['type']] = ($type_counts[$d['type']] ?? 0) + 1;
arsort($type_counts);

$inv_index = [];
foreach ($inventory as $item) $inv_index[$item['id']] = $item;
?>

<!-- KPI Strip -->
<div class="kpi-grid" style="grid-template-columns:repeat(4,1fr); margin-bottom:1.5rem">
  <div class="kpi-card" style="border-top:3px solid var(--warn)">
    <div class="kpi-label">Total Pengiriman</div>
    <div class="kpi-value"><?= count($delivery_out) ?></div>
    <div class="kpi-sub">Dokumen keluar</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Nilai Barang Keluar</div>
    <div class="kpi-value">$<?= fmt($total_value_out) ?></div>
    <div class="kpi-sub">Total nilai dikirim</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Internal Transfer</div>
    <div class="kpi-value"><?= $type_counts['Internal Transfer'] ?? 0 ?></div>
    <div class="kpi-sub">Pindah antar divisi</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Outbound Shipment</div>
    <div class="kpi-value"><?= $type_counts['Outbound Shipment'] ?? 0 ?></div>
    <div class="kpi-sub">Ke pelanggan / eksternal</div>
  </div>
</div>

<!-- Form: Tambah Pengeluaran -->
<div class="panel dout-form-panel" style="margin-bottom:1.5rem">
  <div class="panel-head">
    <span>+ Catat Pengeluaran Baru</span>
    <button class="toggle-form-btn" onclick="toggleFormOut('dout-form')">Buka Form ▾</button>
  </div>
  <div id="dout-form" style="display:none">
    <div class="form-grid">
      <div class="form-group">
        <label>Tanggal Keluar</label>
        <input type="date" class="form-input" value="<?= date('Y-m-d') ?>">
      </div>
      <div class="form-group">
        <label>Waktu</label>
        <input type="time" class="form-input" value="<?= date('H:i') ?>">
      </div>
      <div class="form-group">
        <label>Pilih SKU / Barang</label>
        <select class="form-input" onchange="fillItemOut(this)">
          <option value="">— Pilih item —</option>
          <?php foreach ($inventory as $item): ?>
          <option value="<?= $item['id'] ?>"
            data-name="<?= htmlspecialchars($item['name']) ?>"
            data-unit="<?= $item['unit'] ?>"
            data-cost="<?= $item['unit_cost'] ?>"
            data-stock="<?= $item['stock'] ?>">
            <?= $item['id'] ?> — <?= htmlspecialchars($item['name']) ?> (Stok: <?= number_format($item['stock']) ?> <?= $item['unit'] ?>)
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" class="form-input" id="dout-item-name" placeholder="Otomatis terisi" readonly>
      </div>
      <div class="form-group">
        <label>Tujuan / Departemen</label>
        <input type="text" class="form-input" placeholder="Cth: Production Line A">
      </div>
      <div class="form-group">
        <label>Jenis Pengeluaran</label>
        <select class="form-input">
          <option>Internal Transfer</option>
          <option>Internal Use</option>
          <option>Outbound Shipment</option>
          <option>Return to Supplier</option>
          <option>Write-off / Disposal</option>
        </select>
      </div>
      <div class="form-group">
        <label>Qty Keluar</label>
        <input type="number" class="form-input" id="dout-qty" placeholder="0" oninput="checkStock()">
      </div>
      <div class="form-group">
        <label>Satuan</label>
        <input type="text" class="form-input" id="dout-unit" placeholder="pcs / L / kg" readonly>
      </div>
      <div class="form-group">
        <label>Stok Tersedia</label>
        <input type="text" class="form-input" id="dout-stock" placeholder="—" readonly style="color:var(--accent)">
      </div>
      <div class="form-group">
        <label>Diminta Oleh</label>
        <input type="text" class="form-input" placeholder="Nama pemohon">
      </div>
      <div class="form-group">
        <label>Disetujui Oleh</label>
        <input type="text" class="form-input" placeholder="Nama approver">
      </div>
      <div class="form-group">
        <label>Estimasi Nilai ($)</label>
        <input type="text" class="form-input" id="dout-value" placeholder="—" readonly>
      </div>
      <div class="form-group form-full">
        <label>Catatan</label>
        <textarea class="form-input" rows="2" placeholder="Catatan pengiriman..."></textarea>
      </div>
    </div>
    <div id="dout-stock-warn" class="stock-warn-msg" style="display:none">
      ⚠️ Qty melebihi stok tersedia!
    </div>
    <div class="form-actions">
      <button class="btn-primary btn-warn" onclick="submitForm('dout')">✓ Simpan Pengeluaran</button>
      <button class="btn-ghost" onclick="toggleFormOut('dout-form')">Batal</button>
    </div>
  </div>
</div>

<!-- Tabel Riwayat Delivery Out -->
<div class="panel">
  <div class="panel-head">
    <span>Riwayat Pengeluaran Barang (<?= count($delivery_out) ?> dokumen)</span>
    <div class="filter-row">
      <select class="form-input form-input-sm" id="dout-filter-type" onchange="filterDout()">
        <option value="">Semua Jenis</option>
        <?php foreach (array_keys($type_counts) as $t): ?>
        <option value="<?= strtolower($t) ?>"><?= $t ?></option>
        <?php endforeach; ?>
      </select>
      <input type="text" class="search-input" id="dout-search" placeholder="Cari item / tujuan…" oninput="filterDout()">
    </div>
  </div>
  <div class="table-wrap">
    <table class="data-table" id="dout-table">
      <thead>
        <tr>
          <th>No. Dokumen</th>
          <th>Tanggal</th>
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
      <tbody>
        <?php foreach ($delivery_out as $d):
          $value = $d['qty'] * $d['unit_cost'];
          $type_class = match($d['type']) {
            'Internal Transfer'  => 'badge-info',
            'Internal Use'       => 'badge-neutral',
            'Outbound Shipment'  => 'badge-warn',
            default              => 'badge-neutral',
          };
        ?>
        <tr class="dout-row"
            data-search="<?= strtolower($d['item'].' '.$d['destination'].' '.$d['sku']) ?>"
            data-type="<?= strtolower($d['type']) ?>">
          <td><span class="mono doc-id dout-id"><?= $d['id'] ?></span></td>
          <td>
            <div class="mono" style="font-size:11px"><?= $d['date'] ?></div>
            <div class="item-supplier"><?= $d['time'] ?></div>
          </td>
          <td><span class="mono text-warn"><?= $d['sku'] ?></span></td>
          <td>
            <div class="item-name"><?= htmlspecialchars($d['item']) ?></div>
            <?php if ($d['notes']): ?>
            <div class="item-supplier" title="<?= htmlspecialchars($d['notes']) ?>">📝 <?= mb_strimwidth($d['notes'],0,40,'…') ?></div>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($d['destination']) ?></td>
          <td><span class="badge-pill <?= $type_class ?>"><?= $d['type'] ?></span></td>
          <td class="mono text-warn"><?= number_format($d['qty']) ?> <?= $d['unit'] ?></td>
          <td class="mono">$<?= number_format($value, 2) ?></td>
          <td><?= htmlspecialchars($d['requested_by']) ?></td>
          <td><?= htmlspecialchars($d['approved_by']) ?></td>
          <td><span class="status-tag status-tag-ok"><?= $d['status'] ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Summary per SKU -->
<div class="panel" style="margin-top:1.5rem">
  <div class="panel-head"><span>Ringkasan Pengeluaran per SKU</span></div>
  <div class="sku-summary-grid">
    <?php
    $sku_out = [];
    foreach ($delivery_out as $d) {
      if (!isset($sku_out[$d['sku']])) $sku_out[$d['sku']] = ['name'=>$d['item'],'qty'=>0,'value'=>0,'txn'=>0];
      $sku_out[$d['sku']]['qty']   += $d['qty'];
      $sku_out[$d['sku']]['value'] += $d['qty'] * $d['unit_cost'];
      $sku_out[$d['sku']]['txn']++;
    }
    foreach ($sku_out as $sku => $s):
      $item = $inv_index[$sku] ?? null;
    ?>
    <div class="sku-sum-card dout-card">
      <div class="sku-sum-head">
        <span class="mono text-warn" style="font-size:11px"><?= $sku ?></span>
        <span class="badge-pill badge-neutral"><?= $s['txn'] ?> txn</span>
      </div>
      <div class="sku-sum-name"><?= htmlspecialchars($s['name']) ?></div>
      <div class="sku-sum-rows">
        <div class="sku-sum-row"><span>Total Keluar</span><strong class="text-warn"><?= number_format($s['qty']) ?> <?= $item['unit'] ?? '' ?></strong></div>
        <div class="sku-sum-row"><span>Total Nilai</span><strong>$<?= fmt($s['value']) ?></strong></div>
        <div class="sku-sum-row"><span>Stok Saat Ini</span><strong><?= $item ? number_format($item['stock']) : '—' ?></strong></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Type Breakdown -->
<div class="panel" style="margin-top:1.5rem">
  <div class="panel-head"><span>Distribusi Jenis Pengeluaran</span></div>
  <div class="type-breakdown">
    <?php
    $type_vals = [];
    foreach ($delivery_out as $d) $type_vals[$d['type']] = ($type_vals[$d['type']] ?? 0) + $d['qty'] * $d['unit_cost'];
    $total_tv = array_sum($type_vals);
    $colors = ['Internal Transfer'=>'var(--info)','Internal Use'=>'var(--text3)','Outbound Shipment'=>'var(--warn)'];
    foreach ($type_vals as $type => $val):
      $pct = $total_tv > 0 ? round($val / $total_tv * 100) : 0;
      $col = $colors[$type] ?? 'var(--accent)';
    ?>
    <div class="type-row">
      <div class="type-label">
        <span style="color:<?= $col ?>;margin-right:6px">●</span>
        <span><?= $type ?></span>
        <span class="type-pct"><?= $pct ?>%</span>
      </div>
      <div class="type-bar-wrap">
        <div class="type-bar"><div class="type-bar-fill" style="width:<?= $pct ?>%;background:<?= $col ?>"></div></div>
        <span class="type-val mono">$<?= fmt($val) ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
function toggleFormOut(id) {
  const el = document.getElementById(id);
  const btn = document.querySelector('.dout-form-panel .toggle-form-btn');
  if (el.style.display === 'none') {
    el.style.display = 'block'; btn.textContent = 'Tutup Form ▴';
  } else {
    el.style.display = 'none'; btn.textContent = 'Buka Form ▾';
  }
}

let currentStock = 0;
let unitCost = 0;

function fillItemOut(sel) {
  const opt = sel.options[sel.selectedIndex];
  document.getElementById('dout-item-name').value = opt.dataset.name || '';
  document.getElementById('dout-unit').value       = opt.dataset.unit || '';
  currentStock = parseInt(opt.dataset.stock) || 0;
  unitCost     = parseFloat(opt.dataset.cost) || 0;
  document.getElementById('dout-stock').value = opt.dataset.stock
    ? number_format(currentStock) + ' ' + opt.dataset.unit
    : '—';
  checkStock();
}

function checkStock() {
  const qty = parseInt(document.getElementById('dout-qty').value) || 0;
  const warn = document.getElementById('dout-stock-warn');
  if (qty > currentStock && currentStock > 0) {
    warn.style.display = 'block';
  } else {
    warn.style.display = 'none';
  }
  const val = qty * unitCost;
  document.getElementById('dout-value').value = val > 0 ? '$' + val.toFixed(2) : '—';
}

function number_format(n) {
  return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function submitForm(type) {
  alert('Pengeluaran berhasil dicatat! (Demo — tidak tersimpan ke database)');
}

function filterDout() {
  const q  = document.getElementById('dout-search').value.toLowerCase();
  const tp = document.getElementById('dout-filter-type').value.toLowerCase();
  document.querySelectorAll('.dout-row').forEach(r => {
    const matchQ  = r.dataset.search.includes(q);
    const matchTp = !tp || r.dataset.type.includes(tp);
    r.style.display = matchQ && matchTp ? '' : 'none';
  });
}
</script>
