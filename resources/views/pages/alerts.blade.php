<?php // pages/alerts.php
$critical = array_filter($alerts, fn($a) => $a['level']==='critical');
$warnings = array_filter($alerts, fn($a) => $a['level']==='warning');
$infos    = array_filter($alerts, fn($a) => $a['level']==='info');
?>

<div class="kpi-grid" style="grid-template-columns:repeat(3,1fr)">
  <div class="kpi-card danger">
    <div class="kpi-label">Critical</div>
    <div class="kpi-value"><?= count($critical) ?></div>
    <div class="kpi-sub">Immediate action needed</div>
  </div>
  <div class="kpi-card warn">
    <div class="kpi-label">Warnings</div>
    <div class="kpi-value"><?= count($warnings) ?></div>
    <div class="kpi-sub">Monitor closely</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Informational</div>
    <div class="kpi-value"><?= count($infos) ?></div>
    <div class="kpi-sub">No action required</div>
  </div>
</div>

<div class="panel" style="margin-top:1.5rem">
  <div class="panel-head"><span>All Alerts</span></div>

  <?php if (empty($alerts)): ?>
    <div class="empty-state">✓ No active alerts. All inventory levels are healthy.</div>
  <?php endif; ?>

  <?php foreach ($alerts as $a): ?>
  <div class="alert-card level-<?= $a['level'] ?>">
    <div class="alert-icon">
      <?php
        echo match($a['level']) {
          'critical' => '🔴',
          'warning'  => '🟡',
          default    => '🔵',
        };
      ?>
    </div>
    <div class="alert-body">
      <div class="alert-level"><?= strtoupper($a['level']) ?></div>
      <div class="alert-msg"><?= htmlspecialchars($a['message']) ?></div>
      <?php if ($a['sku']): ?>
        <div class="alert-sku mono"><?= $a['sku'] ?>
          <a href="?page=inventory" class="panel-link">→ View inventory</a>
          <?php if ($a['level']==='critical' || $a['level']==='warning'): ?>
            <a href="?page=orders" class="panel-link">→ Create order</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
    <button class="dismiss-btn" onclick="this.closest('.alert-card').remove()">✕</button>
  </div>
  <?php endforeach; ?>
</div>

<!-- Alert Rules -->
<div class="panel" style="margin-top:1.5rem">
  <div class="panel-head"><span>Alert Configuration</span></div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr><th>Rule</th><th>Trigger</th><th>Level</th><th>Notification</th><th>Active</th></tr>
      </thead>
      <tbody>
        <tr><td>Below Minimum Stock</td><td>stock &lt; min_stock</td><td><span class="badge-pill badge-crit">Critical</span></td><td>Email + Dashboard</td><td><span class="toggle on">ON</span></td></tr>
        <tr><td>Below Reorder Point</td><td>stock &lt; reorder_point</td><td><span class="badge-pill badge-warn">Warning</span></td><td>Dashboard</td><td><span class="toggle on">ON</span></td></tr>
        <tr><td>Overstock Alert</td><td>stock &gt; 85% of max</td><td><span class="badge-pill badge-info">Info</span></td><td>Dashboard</td><td><span class="toggle on">ON</span></td></tr>
        <tr><td>Forecast Deviation</td><td>demand &gt;±20% vs forecast</td><td><span class="badge-pill badge-warn">Warning</span></td><td>Email</td><td><span class="toggle off">OFF</span></td></tr>
        <tr><td>Supplier Lead Time Breach</td><td>PO delivery &gt; ETA</td><td><span class="badge-pill badge-crit">Critical</span></td><td>Email + SMS</td><td><span class="toggle on">ON</span></td></tr>
      </tbody>
    </table>
  </div>
</div>
