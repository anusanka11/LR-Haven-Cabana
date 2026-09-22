<?php
require_once __DIR__ . '/../auth.php';
require_admin();
$id = (int)($_POST['id'] ?? 0);
if ($id) { $stmt=db()->prepare('DELETE FROM bookings WHERE id = ?'); $stmt->execute([$id]); }
header('Location: dashboard.php'); exit;
