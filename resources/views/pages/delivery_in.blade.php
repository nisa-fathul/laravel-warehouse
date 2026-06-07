<?php
// pages/delivery_in.php
$total_qty_in   = array_sum(array_column($delivery_in, 'qty_received'));
$total_value_in = array_sum(array_map(fn($d) => $d['qty_received'] * $d['unit_cost'], $delivery_in));
$complete_count = count(array_filter($delivery_in, fn($d) => $d['status']==='Complete'));
$partial_count  = count(array_filter($delivery_in, fn($d) => $d['status']==='Partial'));

// Build SKU index for quick lookup
$inv_index = [];
foreach ($inventory as $item) $inv_index[$item['id']] = $item;
?>

<!-- KPI Strip -->
<div class="kpi-grid" style="grid-template-columns:repeat(4,1fr); margin-bottom:1.5rem">
  <div class="kpi-card accent">
    <div class="kpi-label">Total Penerimaan</div>
    <div class="kpi-value"><?= count($delivery_in) ?></div>
    <div class="kpi-sub">Dokumen masuk</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Nilai Barang Masuk</div>
    <div class="kpi-value">$<?= fmt($total_value_in) ?></div>
    <div class="kpi-sub">Total nilai diterima</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Selesai</div>
    <div class="kpi-value"><?= $complete_count ?></div>
    <div class="kpi-sub">Penerimaan lengkap</div>
  </div>
  <div class="kpi-card <?= $partial_count > 0 ? 'warn' : '' ?>">
    <div class="kpi-label">Sebagian / Rusak</div>
    <div class="kpi-value"><?= $partial_count ?></div>
    <div class="kpi-sub">Perlu tindak lanjut</div>
  </div>
</div>

<!-- Form: Tambah Penerimaan -->
<div class="panel din-form-panel" style="margin-bottom:1.5rem">
  <div class="panel-head">
    <span>+ Catat Penerimaan Baru</span>
    <button class="toggle-form-btn" onclick="toggleForm('din-form')">Buka Form ▾</button>
  </div>
  <div id="din-form" style="display:none">
    <div class="form-grid">
      <div class="form-group">
        <label>Tanggal Terima</label>
        <input type="date" class="form-input" value="<?= date('Y-m-d') ?>">
      </div>
      <div class="form-group">
        <label>Waktu</label>
        <input type="time" class="form-input" value="<?= date('H:i') ?>">
      </div>
      <div class="form-group">
        <label>Pilih SKU / Barang</label>
        <select class="form-input" onchange="fillItem(this)">
          <option value="">— Pilih item —</option>
          <?php foreach ($inventory as $item): ?>
          <option value="<?= $item['id'] ?>" data-name="<?= htmlspecialchars($item['name']) ?>" data-unit="<?= $item['unit'] ?>" data-cost="<?= $item['unit_cost'] ?>">
            <?= $item['id'] ?> — <?= htmlspecialchars($item['name']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" class="form-input" id="din-item-name" placeholder="Otomatis terisi" readonly>
      </div>
      <div class="form-group">
        <label>Supplier</label>
        <input type="text" class="form-input" placeholder="Nama supplier">
      </div>
      <div class="form-group">
        <label>No. PO Referensi</label>
        <input type="text" class="form-input" placeholder="PO-XXXX-XXX (opsional)">
      </div>
      <div class="form-group">
        <label>Qty Dipesan</label>
        <input type="number" class="form-input" placeholder="0" id="din-qty-ordered">
      </div>
      <div class="form-group">
        <label>Qty Diterima</label>
        <input type="number" class="form-input" placeholder="0" id="din-qty-received">
      </div>
      <div class="form-group">
        <label>Satuan</label>
        <input type="text" class="form-input" id="din-unit" placeholder="pcs / L / kg" readonly>
      </div>
      <div class="form-group">
        <label>Harga Satuan ($)</label>
        <input type="number" class="form-input" id="din-cost" placeholder="0.00" step="0.01">
      </div>
      <div class="form-group">
        <label>Kondisi Barang</label>
        <select class="form-input">
          <option>Good</option>
          <option>Damaged</option>
          <option>Partial Damage</option>
        </select>
      </div>
      <div class="form-group">
        <label>Diterima Oleh</label>
        <input type="text" class="form-input" placeholder="Nama petugas">
      </div>
      <div class="form-group form-full">
        <label>Catatan</label>
        <textarea class="form-input" rows="2" placeholder="Catatan penerimaan..."></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button class="btn-primary" onclick="submitForm('din')">✓ Simpan Penerimaan</button>
      <button class="btn-ghost" onclick="toggleForm('din-form')">Batal</button>
    </div>
  </div>
</div>

<!-- Tabel Riwayat Delivery In -->
<div class="panel">
  <div class="panel-head">
    <span>Riwayat Penerimaan Barang (<?= count($delivery_in) ?> dokumen)</span>
    <div class="filter-row">
      <select class="form-input form-input-sm" id="din-filter-status" onchange="filterDin()">
        <option value="">Semua Status</option>
        <option value="complete">Complete</option>
        <option value="partial">Partial</option>
      </select>
      <input type="text" class="search-input" id="din-search" placeholder="Cari item / supplier…" oninput="filterDin()">
    </div>
  </div>
  <div class="table-wrap">
    <table class="data-table" id="din-table">
      <thead>
        <tr>
          <th>No. Dokumen</th>
          <th>Tanggal</th>
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
      <tbody>
        <?php foreach ($delivery_in as $d):
          $diff = $d['qty_received'] - $d['qty_ordered'];
          $value = $d['qty_received'] * $d['unit_cost'];
          $cond_class = $d['condition'] === 'Good' ? 'badge-ok' : 'badge-crit';
        ?>
        <tr class="din-row"
            data-search="<?= strtolower($d['item'].' '.$d['supplier'].' '.$d['sku']) ?>"
            data-status="<?= strtolower($d['status']) ?>">
          <td><span class="mono doc-id"><?= $d['id'] ?></span></td>
          <td>
            <div class="mono" style="font-size:11px"><?= $d['date'] ?></div>
            <div class="item-supplier"><?= $d['time'] ?></div>
          </td>
          <td><span class="mono text-accent"><?= $d['sku'] ?></span></td>
          <td>
            <div class="item-name"><?= htmlspecialchars($d['item']) ?></div>
            <?php if ($d['notes']): ?>
            <div class="item-supplier" title="<?= htmlspecialchars($d['notes']) ?>">📝 <?= mb_strimwidth($d['notes'],0,40,'…') ?></div>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($d['supplier']) ?></td>
          <td>
            <?php if ($d['ref_po']): ?>
              <span class="badge-pill badge-neutral"><?= $d['ref_po'] ?></span>
            <?php else: ?>
              <span class="text-muted">—</span>
            <?php endif; ?>
          </td>
          <td class="mono"><?= number_format($d['qty_ordered']) ?> <?= $d['unit'] ?></td>
          <td class="mono"><?= number_format($d['qty_received']) ?> <?= $d['unit'] ?></td>
          <td class="mono <?= $diff < 0 ? 'text-danger' : ($diff > 0 ? 'text-ok' : 'text-muted') ?>">
            <?= $diff === 0 ? '—' : ($diff > 0 ? '+' : '') . number_format($diff) ?>
          </td>
          <td class="mono">$<?= number_format($value, 2) ?></td>
          <td><span class="badge-pill <?= $cond_class ?>"><?= $d['condition'] ?></span></td>
          <td><?= htmlspecialchars($d['received_by']) ?></td>
          <td>
            <?php if ($d['status']==='Complete'): ?>
              <span class="status-tag status-tag-ok">Complete</span>
            <?php else: ?>
              <span class="status-tag status-tag-warning">Partial</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Summary per SKU -->
<div class="panel" style="margin-top:1.5rem">
  <div class="panel-head"><span>Ringkasan Penerimaan per SKU</span></div>
  <div class="sku-summary-grid">
    <?php
    $sku_in = [];
    foreach ($delivery_in as $d) {
      if (!isset($sku_in[$d['sku']])) $sku_in[$d['sku']] = ['name'=>$d['item'],'qty'=>0,'value'=>0,'txn'=>0];
      $sku_in[$d['sku']]['qty']   += $d['qty_received'];
      $sku_in[$d['sku']]['value'] += $d['qty_received'] * $d['unit_cost'];
      $sku_in[$d['sku']]['txn']++;
    }
    foreach ($sku_in as $sku => $s):
      $item = $inv_index[$sku] ?? null;
    ?>
    <div class="sku-sum-card din-card">
      <div class="sku-sum-head">
        <span class="mono text-accent" style="font-size:11px"><?= $sku ?></span>
        <span class="badge-pill badge-info"><?= $s['txn'] ?> txn</span>
      </div>
      <div class="sku-sum-name"><?= htmlspecialchars($s['name']) ?></div>
      <div class="sku-sum-rows">
        <div class="sku-sum-row"><span>Total Diterima</span><strong class="text-accent"><?= number_format($s['qty']) ?> <?= $item['unit'] ?? '' ?></strong></div>
        <div class="sku-sum-row"><span>Total Nilai</span><strong>$<?= fmt($s['value']) ?></strong></div>
        <div class="sku-sum-row"><span>Stok Saat Ini</span><strong><?= $item ? number_format($item['stock']) : '—' ?></strong></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
function toggleForm(id) {
  const el = document.getElementById(id);
  const btn = document.querySelector('.toggle-form-btn');
  if (el.style.display === 'none') {
    el.style.display = 'block';
    btn.textContent = 'Tutup Form ▴';
  } else {
    el.style.display = 'none';
    btn.textContent = 'Buka Form ▾';
  }
}

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

function filterDin() {
  const q   = document.getElementById('din-search').value.toLowerCase();
  const st  = document.getElementById('din-filter-status').value.toLowerCase();
  document.querySelectorAll('.din-row').forEach(r => {
    const matchQ  = r.dataset.search.includes(q);
    const matchSt = !st || r.dataset.status.includes(st);
    r.style.display = matchQ && matchSt ? '' : 'none';
  });
}
</script>
