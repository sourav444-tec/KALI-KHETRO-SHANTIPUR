<?php
require __DIR__ . '/../config/database.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
verify_csrf();
$kind = $_POST['kind'] ?? '';
function upload_image(string $field, string $folder): string {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return '';
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024) throw new RuntimeException('Image upload failed or exceeds 5 MB.');
    $finfo = new finfo(FILEINFO_MIME_TYPE); $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if (!isset($allowed[$mime])) throw new RuntimeException('Only JPG, PNG, and WebP images are allowed.');
    $dir = __DIR__ . '/../uploads/' . $folder;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) throw new RuntimeException('Could not save uploaded image.');
    return 'uploads/' . $folder . '/' . $name;
}
try {
 if ($kind === 'puja-update') {
   $id = (int)($_POST['id'] ?? 0); if ($id < 1) throw new RuntimeException('Invalid Puja record.');
   $mapUrl = trim($_POST['map_url'] ?? '');
   if ($mapUrl !== '') {
     if (!filter_var($mapUrl, FILTER_VALIDATE_URL)) throw new RuntimeException('Enter a valid Google Maps URL.');
     $host = strtolower((string)parse_url($mapUrl, PHP_URL_HOST));
     $allowedHosts = ['google.com','www.google.com','maps.google.com','maps.app.goo.gl','goo.gl','maps.google.co.in','www.google.co.in'];
     $allowed = false; foreach ($allowedHosts as $allowedHost) { if ($host === $allowedHost || str_ends_with($host, '.'.$allowedHost)) { $allowed = true; break; } }
     if (!$allowed) throw new RuntimeException('The location link must be from Google Maps (maps.google.com or maps.app.goo.gl).');
   }
   $photo = upload_image('photo','pujas');
   $sql = 'UPDATE pujas SET name_en=?,name_bn=?,description_en=?,description_bn=?,category=?,address=?,map_url=?,lat=?,lng=?,years_continued=?,theme=?,contact=?,featured=?,published=?';
   $args = [trim($_POST['name_en']??''),trim($_POST['name_bn']??''),trim($_POST['description_en']??''),trim($_POST['description_bn']??''),$_POST['category']??'Popular',trim($_POST['address']??''),$mapUrl,($_POST['lat']??'')!==''?$_POST['lat']:null,($_POST['lng']??'')!==''?$_POST['lng']:null,trim($_POST['years_continued']??''),trim($_POST['theme']??''),trim($_POST['contact']??''),isset($_POST['featured'])?1:0,isset($_POST['published'])?1:0];
   if ($photo !== '') { $sql .= ',photo=?'; $args[] = $photo; }
   $sql .= ' WHERE id=?'; $args[] = $id; $stmt=$pdo->prepare($sql); $stmt->execute($args);
   header('Location: edit-puja.php?id='.$id.'&saved=1'); exit;
 } elseif ($kind === 'puja') {
   $photo = upload_image('photo','pujas');
   $mapUrl = trim($_POST['map_url'] ?? '');
   if ($mapUrl !== '') {
     if (!filter_var($mapUrl, FILTER_VALIDATE_URL)) throw new RuntimeException('Enter a valid Google Maps URL.');
     $host = strtolower((string)parse_url($mapUrl, PHP_URL_HOST));
     $allowedHosts = ['google.com','www.google.com','maps.google.com','maps.app.goo.gl','goo.gl','maps.google.co.in','www.google.co.in'];
     $allowed = false; foreach ($allowedHosts as $allowedHost) { if ($host === $allowedHost || str_ends_with($host, '.'.$allowedHost)) { $allowed = true; break; } }
     if (!$allowed) throw new RuntimeException('The location link must be from Google Maps (maps.google.com or maps.app.goo.gl).');
   }
   $stmt=$pdo->prepare('INSERT INTO pujas (name_en,name_bn,description_en,description_bn,category,address,map_url,lat,lng,years_continued,theme,contact,photo,featured,published) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
   $stmt->execute([trim($_POST['name_en']??''),trim($_POST['name_bn']??''),trim($_POST['description_en']??''),trim($_POST['description_bn']??''),$_POST['category']??'Popular',trim($_POST['address']??''),$mapUrl,($_POST['lat']??'')!==''?$_POST['lat']:null,($_POST['lng']??'')!==''?$_POST['lng']:null,trim($_POST['years_continued']??''),trim($_POST['theme']??''),trim($_POST['contact']??''),$photo,isset($_POST['featured'])?1:0,isset($_POST['published'])?1:0]);
 } elseif ($kind === 'service') {
   $stmt=$pdo->prepare('INSERT INTO services (name_en,name_bn,service_type,address,phone,distance,notes_en,notes_bn,lat,lng,puja_id,published) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
   $stmt->execute([trim($_POST['name_en']??''),trim($_POST['name_bn']??''),$_POST['service_type']??'Other',trim($_POST['address']??''),trim($_POST['phone']??''),trim($_POST['distance']??''),trim($_POST['notes_en']??''),trim($_POST['notes_bn']??''),($_POST['lat']??'')!==''?$_POST['lat']:null,($_POST['lng']??'')!==''?$_POST['lng']:null,($_POST['puja_id']??'')!==''?(int)$_POST['puja_id']:null,isset($_POST['published'])?1:0]);
 } elseif ($kind === 'emergency') {
   $stmt=$pdo->prepare('INSERT INTO emergency_contacts (label_en,label_bn,phone,instructions_en,instructions_bn,sort_order,published) VALUES (?,?,?,?,?,?,?)');
   $stmt->execute([trim($_POST['label_en']??''),trim($_POST['label_bn']??''),trim($_POST['phone']??''),trim($_POST['instructions_en']??''),trim($_POST['instructions_bn']??''),(int)($_POST['sort_order']??10),isset($_POST['published'])?1:0]);
 } elseif ($kind === 'partner') {
   $logo=upload_image('logo','partners');
   $website=trim($_POST['website']??'');
   if ($website && !filter_var($website,FILTER_VALIDATE_URL)) throw new RuntimeException('Enter a valid website URL.');
   $stmt=$pdo->prepare('INSERT INTO partners (name,partner_type,logo,website,description,sort_order,published) VALUES (?,?,?,?,?,?,?)');
   $stmt->execute([trim($_POST['name']??''),$_POST['partner_type']??'Collaborator',$logo,$website,trim($_POST['description']??''),(int)($_POST['sort_order']??10),isset($_POST['published'])?1:0]);
 } elseif ($kind === 'team') {
   $photo=upload_image('photo','team');
   $stmt=$pdo->prepare('INSERT INTO team_members (name,role,photo,bio,sort_order,published) VALUES (?,?,?,?,?,?)');
   $stmt->execute([trim($_POST['name']??''),trim($_POST['role']??''),$photo,trim($_POST['bio']??''),(int)($_POST['sort_order']??10),isset($_POST['published'])?1:0]);
 } else { throw new RuntimeException('Unknown record type.'); }
 header('Location: dashboard.php?saved=1#'.(['puja'=>'pujas','service'=>'services','emergency'=>'emergency','partner'=>'partners','team'=>'team'][$kind]??'overview')); exit;
} catch (Throwable $ex) {
 http_response_code(400);
 echo '<!doctype html><meta charset="utf-8"><link rel="stylesheet" href="../assets/css/style.css"><main class="error-page"><h1>Could not save this record</h1><p>'.e($ex->getMessage()).'</p><a class="btn btn-gold" href="dashboard.php">Return to dashboard</a></main>';
}
