<?php
// Simple Power Cut Banner component.
// Expects $power_cut = ['district'=>..., 'start_time'=>..., 'end_time'=>...]
$pc = $power_cut ?? null;
if (!$pc) return;

$district = htmlspecialchars($pc['district'] ?? '', ENT_QUOTES, 'UTF-8');
$start = htmlspecialchars($pc['start_time'] ?? '', ENT_QUOTES, 'UTF-8');
$end = htmlspecialchars($pc['end_time'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<div class="bg-warning mb-6 card shadow-sm">
  <div class="card-body d-flex align-center">
    <i class="fas fa-exclamation-triangle text-warning mr-3" aria-hidden="true"></i>
    <div>
      <strong>Power Cut Alert:</strong>
      <span> Scheduled outage in <?php echo $district; ?> today from <?php echo $start; ?> to <?php echo $end; ?>.</span>
    </div>
  </div>
</div>