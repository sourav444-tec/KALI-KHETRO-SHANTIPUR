<?php
require __DIR__ . '/../config/database.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
verify_csrf();
$current=$_POST['current_password']??''; $new=$_POST['new_password']??'';
$stmt=$pdo->prepare('SELECT password_hash FROM admin_users WHERE id=?'); $stmt->execute([$_SESSION['admin_id']]); $row=$stmt->fetch();
if (!$row || !password_verify($current,$row['password_hash']) || strlen($new)<10) { http_response_code(400); exit('Password change failed. Check your current password and use at least 10 characters for the new password. <a href="dashboard.php">Back</a>'); }
$stmt=$pdo->prepare('UPDATE admin_users SET password_hash=? WHERE id=?'); $stmt->execute([password_hash($new,PASSWORD_DEFAULT),$_SESSION['admin_id']]);
header('Location: dashboard.php?saved=1');
