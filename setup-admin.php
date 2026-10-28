<?php
require __DIR__ . '/config/database.php';
$count=(int)$pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
$message='';
if ($_SERVER['REQUEST_METHOD']==='POST' && $count===0) {
  $username=trim($_POST['username']??''); $password=$_POST['password']??'';
  if ($username!=='' && strlen($password)>=10) {
    $stmt=$pdo->prepare('INSERT INTO admin_users (username,password_hash) VALUES (?,?)');
    $stmt->execute([$username,password_hash($password,PASSWORD_DEFAULT)]);
    $message='Admin created. IMPORTANT: delete setup-admin.php now, then open admin/login.php.';
    $count=1;
  } else $message='Enter a username and password of at least 10 characters.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>One-time admin setup</title><link rel="stylesheet" href="assets/css/style.css"></head><body class="admin-body"><div class="login-card"><p class="eyebrow">ONE-TIME SETUP</p><h1>Create admin account</h1><?php if($message): ?><div class="alert"><?= e($message) ?></div><?php endif; ?><?php if($count===0): ?><form class="admin-form" method="post"><label>Username<input name="username" required></label><label>Password (10+ characters)<input name="password" type="password" minlength="10" required></label><button class="btn btn-gold full">Create admin</button></form><?php else: ?><p>An admin account already exists, or has just been created. Delete this file for security.</p><?php endif; ?><p><a href="admin/login.php">Go to admin login →</a></p></div></body></html>
