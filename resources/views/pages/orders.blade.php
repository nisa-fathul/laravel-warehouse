<?php // pages/orders.php
$total_pending = array_sum(array_map(
  fn($o) => in_array($o['status'], ['Processing','In Transit','Pending Approval']) ? $o['qty'] * $o['unit_cost'] : 0,
  $orders
));
?>

<div class="kpi-grid" style="grid-template-columns:repeat(3,1fr)">
  <div class="kpi-card">
    <div class="kpi-label">Active Orders</div>
    <div class="kpi-value"><?= count(array_filter($orders, fn($o)=>$o['status']!=='Delivered')) ?></div>
    <div class="kpi-sub">In progress</div>
  </div>
  <div class="kpi-card accent">
    <div class="kpi-label">Pending Value</div>
    <div class="kpi-value">$<?= fmt($total_pending) ?></div>
    <div class="kpi-sub">Uncommitted spend</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Total Orders</div>
    <div class="kpi-value"><?= count($orders) ?></div>
    <div class="kpi-sub">This period</div>
  </div>
</div>

<div class="panel" style="margin-top:1.5rem">
  <div class="panel-head"><span>Purchase Orders</span></div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Item</th>
          <th>Supplier</th>
          <th>Qty</th>
          <th>Value</th>
          <th>Ordered</th>
          <th>ETA</th>
          <th>Progress</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
          <td><span class="mono"><?= $o['id'] ?></span></td>
          <td>
            <div class="item-name"><?= htmlspecialchars($o['item']) ?></div>
            <div class="item-supplier mono"><?= $o['sku'] ?></div>
          </td>
          <td><?= htmlspecialchars($o['supplier']) ?></td>
          <td class="mono"><?= number_format($o['qty']) ?></td>
          <td class="mono">$<?= number_format($o['qty'] * $o['unit_cost'], 2) ?></td>
          <td class="mono"><?= $o['ordered_date'] ?></td>
          <td class="mono"><?= $o['eta'] ?></td>
          <td style="min-width:120px">
            <div class="progress-bar">
              <div class="progress-fill <?= $o['progress']==100 ? 'done' : '' ?>" style="width:<?= $o['progress'] ?>%"></div>
            </div>
            <div class="mini-bar-label"><?= $o['progress'] ?>%</div>
          </td>
          <td><?= statusBadge($o['status']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Suggested Reorders -->
<div class="panel" style="margin-top:1.5rem">
  <div class="panel-head"><span>⚡ AI-Suggested Reorders</span></div>
  <p class="panel-desc">Items below reorder point with recommended quantities based on demand forecast and lead time.</p>
  <div class="reorder-grid">
    <?php foreach ($inventory as $item):
      if ($item['stock'] >= $item['reorder_point']) continue;
      $f = $forecasts[$item['id']];
      $suggest_qty = round($f['avg_monthly'] * 2);
      $suggest_cost = $suggest_qty * $item['unit_cost'];
    ?>
    <div class="reorder-card">
      <div class="rc-header">
        <span class="mono"><?= $item['id'] ?></span>
        <?= riskBadge($forecasts[$item['id']]['stockout_risk']) ?>
      </div>
      <div class="rc-name"><?= htmlspecialchars($item['name']) ?></div>
      <div class="rc-rows">
        <div class="rc-row"><span>Current Stock</span><strong class="text-warn"><?= number_format($item['stock']) ?> <?= $item['unit'] ?></strong></div>
        <div class="rc-row"><span>Reorder Point</span><strong><?= number_format($item['reorder_point']) ?></strong></div>
        <div class="rc-row"><span>Suggested Qty</span><strong class="text-accent"><?= number_format($suggest_qty) ?> <?= $item['unit'] ?></strong></div>
        <div class="rc-row"><span>Estimated Cost</span><strong>$<?= fmt($suggest_cost) ?></strong></div>
        <div class="rc-row"><span>Supplier</span><strong><?= htmlspecialchars($item['supplier']) ?></strong></div>
        <div class="rc-row"><span>Lead Time</span><strong><?= $item['lead_days'] ?> days</strong></div>
      </div>
      <button class="reorder-btn" onclick="alert('Purchase order created for <?= htmlspecialchars($item['name']) ?>!')">Create PO</button>
    </div>
    <?php endforeach; ?>
  </div>
</div>
