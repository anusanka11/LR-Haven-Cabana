<?php
require_once __DIR__ . '/../auth.php';
require_admin();

$pdo = db();
$filter = $_GET['status'] ?? 'All';
$allowed = ['All','New','Confirmed','Cancelled','Completed'];
if (!in_array($filter, $allowed, true)) $filter = 'All';

if ($filter === 'All') {
    $stmt = $pdo->query('SELECT * FROM bookings ORDER BY created_at DESC');
} else {
    $stmt = $pdo->prepare('SELECT * FROM bookings WHERE status = ? ORDER BY created_at DESC');
    $stmt->execute([$filter]);
}
$bookings = $stmt->fetchAll();
$counts = array_fill_keys(['New','Confirmed','Cancelled','Completed'], 0);
foreach ($pdo->query('SELECT status, COUNT(*) total FROM bookings GROUP BY status') as $r) $counts[$r['status']] = (int)$r['total'];
function e($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Bookings Dashboard | Lion Rock Haven Cabana</title><link href="../assets/css/style.css" rel="stylesheet"></head>
<body style="background:#eef2ed">
<header style="background:#0b1511;color:#fff;padding:18px 0"><div class="container" style="display:flex;justify-content:space-between;align-items:center;gap:15px"><div><strong>Lion Rock Haven Cabana</strong><div style="font-size:11px;color:#93a199">Booking management</div></div><div style="display:flex;gap:9px"><a class="btn btn-primary" href="export.php">Export CSV</a><a class="btn btn-ghost" href="../index.html">Website</a><a class="btn btn-ghost" href="logout.php">Logout</a></div></div></header>
<main class="container" style="padding:32px 0 60px">
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px">
    <?php foreach($counts as $key=>$count):?><div style="background:#fff;border:1px solid var(--line);border-radius:18px;padding:18px"><div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.08em"><?=e($key)?></div><strong style="font-size:28px"><?=e($count)?></strong></div><?php endforeach;?>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px">
    <?php foreach($allowed as $s):?><a href="?status=<?=urlencode($s)?>" class="btn <?=$filter===$s?'btn-dark':'btn-primary'?>"><?=e($s)?></a><?php endforeach;?>
  </div>
  <div style="background:#fff;border:1px solid var(--line);border-radius:22px;overflow:auto">
    <table style="width:100%;border-collapse:collapse;min-width:1180px;font-size:12px"><thead style="background:#f5f7f2"><tr><th style="padding:14px;text-align:left">#</th><th style="padding:14px;text-align:left">Guest</th><th style="padding:14px;text-align:left">Contact</th><th style="padding:14px;text-align:left">Email</th><th style="padding:14px;text-align:left">NIC / Passport</th><th style="padding:14px;text-align:left">Type</th><th style="padding:14px;text-align:left">Dates</th><th style="padding:14px;text-align:left">Details</th><th style="padding:14px;text-align:left">Status</th><th style="padding:14px;text-align:left">Created</th><th style="padding:14px;text-align:left">Action</th></tr></thead><tbody>
    <?php if(!$bookings):?><tr><td colspan="11" style="padding:30px;text-align:center;color:var(--muted)">No booking requests found.</td></tr><?php endif;?>
    <?php foreach($bookings as $b):?><tr style="border-top:1px solid var(--line)"><td style="padding:14px;font-weight:700">#<?=e($b['id'])?></td><td style="padding:14px;font-weight:700"><?=e($b['name'])?></td><td style="padding:14px"><?=e($b['contact_number'])?></td><td style="padding:14px"><?=e($b['email'])?></td><td style="padding:14px"><?=e($b['id_number'])?></td><td style="padding:14px"><?=e($b['guest_type'])?></td><td style="padding:14px;white-space:nowrap"><?=e($b['check_in'])?> → <?=e($b['check_out'])?></td><td style="padding:14px;max-width:240px;white-space:normal"><?=e($b['more_details'])?></td><td style="padding:14px"><form action="update_status.php" method="post"><input type="hidden" name="id" value="<?=e($b['id'])?>"><select name="status" onchange="this.form.submit()" style="border:1px solid var(--line);border-radius:10px;padding:7px"><option <?=$b['status']==='New'?'selected':''?>>New</option><option <?=$b['status']==='Confirmed'?'selected':''?>>Confirmed</option><option <?=$b['status']==='Cancelled'?'selected':''?>>Cancelled</option><option <?=$b['status']==='Completed'?'selected':''?>>Completed</option></select></form></td><td style="padding:14px;white-space:nowrap"><?=e($b['created_at'])?></td><td style="padding:14px"><form action="delete_booking.php" method="post" onsubmit="return confirm('Delete this booking?');"><input type="hidden" name="id" value="<?=e($b['id'])?>"><button class="btn" style="padding:8px 11px;background:#f4d9d4;color:#6f2b22">Delete</button></form></td></tr><?php endforeach;?>
    </tbody></table>
  </div>
  <p style="font-size:10px;color:var(--muted);margin-top:12px">For privacy, protect this admin area with HTTPS and update the sample credentials before going live.</p>
</main>
</body></html>
