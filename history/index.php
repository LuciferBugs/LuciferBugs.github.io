<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$telegram_id = $data['telegram_id'];

// Fetch history data from the database
$stmt = $pdo->prepare("
    SELECT type, amount, hash, address, status, created_at
    FROM history
    WHERE telegram_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$telegram_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Send history data to the frontend
echo json_encode(['success' => true, 'history' => $history]);
?>
