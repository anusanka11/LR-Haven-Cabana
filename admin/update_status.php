<?php
require_once __DIR__ . '/../auth.php';
require_admin();
$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';
$allowed = ['New','Confirmed','Cancelled','Completed'];
if ($id && in_array($status, $allowed, true)) {
    $stmt = db()->prepare('UPDATE bookings SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
}
header('Location: dashboard.php'); exit;
