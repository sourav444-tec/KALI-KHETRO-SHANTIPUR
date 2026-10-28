<?php
require __DIR__ . '/config/database.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php#feedback'); exit; }
if (isset($_POST['website']) && trim((string)$_POST['website']) !== '') { header('Location: index.php?feedback=sent#feedback'); exit; }
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$type = trim($_POST['feedback_type'] ?? 'Website');
$ratingRaw = $_POST['rating'] ?? '';
$allowed = ['Website','Map','Puja','Transport','Helpline','Suggestion','Problem'];
$rating = ($ratingRaw === '' ? null : filter_var($ratingRaw, FILTER_VALIDATE_INT));
if ($message === '' || mb_strlen($message) > 3000 || mb_strlen($name)>120 || mb_strlen($email)>180 || ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) || !in_array($type,$allowed,true) || ($rating !== null && ($rating === false || $rating < 1 || $rating > 5))) {
  header('Location: index.php?feedback=invalid#feedback'); exit;
}
$stmt = $pdo->prepare('INSERT INTO feedback (name,email,message,feedback_type,rating) VALUES (?,?,?,?,?)');
$stmt->execute([$name,$email,$message,$type,$rating]);
header('Location: index.php?feedback=sent#feedback'); exit;
