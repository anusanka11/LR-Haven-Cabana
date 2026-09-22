<?php
require_once __DIR__ . '/../auth.php';
if (!empty($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    try {
        $stmt = db()->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php'); exit;
        }
        $error = 'Invalid username or password.';
    } catch (Throwable $e) {
        $error = 'Database connection failed. Import sql/database.sql and check config.php.';
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login | Lion Rock Haven Cabana</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="booking-page"><main class="container" style="min-height:100vh;display:grid;place-items:center;padding:30px 0"><div class="booking-form-card" style="width:min(460px,100%)"><span class="section-kicker">MANAGEMENT PORTAL</span><h2 style="font-size:44px;margin:12px 0">Admin login</h2><p style="color:var(--muted);font-size:13px">Lion Rock Haven Cabana booking dashboard.</p><?php if($error):?><div class="status-box show status-error"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post" style="margin-top:22px"><div class="field"><label>Username</label><input name="username" value="<?=htmlspecialchars($_POST['username'] ?? '')?>" autocomplete="username" required></div><div class="field" style="margin-top:15px"><label>Password</label><input type="password" name="password" autocomplete="current-password" required></div><div class="form-actions"><button class="btn btn-primary" type="submit">Login →</button><a class="btn btn-dark" href="../index.html">Website</a></div></form></div></main></body></html>
