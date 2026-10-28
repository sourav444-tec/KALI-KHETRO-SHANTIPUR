<?php
require __DIR__ . '/../config/database.php';
if (is_admin()) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Incorrect username or password.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Login · Kali Khetro Santipur</title><link rel="stylesheet" href="../assets/css/style.css"></head><body class="admin-body"><div class="login-card"><a href="../index.php" class="back-link">← Back to website</a><div class="admin-emblem">♆</div><p class="eyebrow">KALI KHETRO SANTIPUR</p><h1>Admin login</h1><p class="muted">Sign in to manage Puja information and site content.</p><?php if($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Username<input name="username" required autocomplete="username"></label><label>Password<input type="password" name="password" required autocomplete="current-password"></label><button class="btn btn-gold full" type="submit">Sign in securely →</button></form><p class="small-help">First-time setup: run <code>setup-admin.php</code> once, then delete that file.</p></div></body></html>
