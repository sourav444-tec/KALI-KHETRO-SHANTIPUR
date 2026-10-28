<?php
require __DIR__ . '/../config/database.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
verify_csrf();
$kind=$_POST['kind']??''; $id=(int)($_POST['id']??0);
$tables=['puja'=>'pujas','service'=>'services','emergency'=>'emergency_contacts','partner'=>'partners','team'=>'team_members'];
if ($id>0 && isset($tables[$kind])) {
  $stmt=$pdo->prepare('DELETE FROM '.$tables[$kind].' WHERE id=?'); $stmt->execute([$id]);
}
header('Location: dashboard.php?deleted=1#'.(['puja'=>'pujas','service'=>'services','emergency'=>'emergency','partner'=>'partners','team'=>'team'][$kind]??'overview'));
