<?php
require_once __DIR__ . '/../auth.php';
require_admin();
$rows = db()->query('SELECT id,name,contact_number,email,id_number,guest_type,check_in,check_out,more_details,status,created_at FROM bookings ORDER BY created_at DESC')->fetchAll();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="lion-rock-haven-bookings.csv"');
$out=fopen('php://output','w');
fputcsv($out,['ID','Name','Contact','Email','NIC/Passport','Guest Type','Check In','Check Out','More Details','Status','Created At']);
foreach($rows as $r) fputcsv($out,$r);
fclose($out);
