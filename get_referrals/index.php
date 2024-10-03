<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$telegram_id = $data['telegram_id'];

try {
    // Fetch users referred by this telegram_id from the referral table
    $stmt = $pdo->prepare("
        SELECT r.telegram_id, u.firstname, u.lastname, u.username, r.created_at 
        FROM referral r
        LEFT JOIN user u ON r.telegram_id = u.telegram_id
        WHERE r.referral_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$telegram_id]);
    $referrals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($referrals) > 0) {
        // Send referral data to the frontend
        echo json_encode(['success' => true, 'referrals' => $referrals]);
    } else {
        // If no data found, send an empty result
        echo json_encode(['success' => true, 'referrals' => [], 'message' => 'No referrals found for this user']);
    }
} catch (Exception $e) {
    // Handle errors
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
