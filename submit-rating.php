<?php
require __DIR__ . '/config/database.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php#pujas'); exit; }
$pujaId = filter_input(INPUT_POST, 'puja_id', FILTER_VALIDATE_INT);
$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
if (!$pujaId || !$rating || $rating < 1 || $rating > 5) { header('Location: index.php?rating=invalid#pujas'); exit; }
$check = $pdo->prepare('SELECT id FROM pujas WHERE id=? AND published=1'); $check->execute([$pujaId]);
if (!$check->fetch()) { header('Location: index.php#pujas'); exit; }
// One vote per puja per session. This is a basic duplicate-vote deterrent, not strong identity verification.
if (empty($_SESSION['rating_voter'])) $_SESSION['rating_voter'] = bin2hex(random_bytes(32));
$voterHash = hash('sha256', $_SESSION['rating_voter']);
try {
 $stmt=$pdo->prepare('INSERT INTO puja_ratings (puja_id,rating,voter_hash) VALUES (?,?,?)');
 $stmt->execute([$pujaId,$rating,$voterHash]);
 header('Location: index.php?rating=sent#pujas');
} catch (PDOException $e) {
 if ($e->getCode()==='23000') { header('Location: index.php?rating=duplicate#pujas'); }
 else { error_log($e->getMessage()); http_response_code(500); echo 'Rating could not be saved. Please try again later.'; }
}
exit;
