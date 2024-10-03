<?php
header("Content-Type: application/json");
require_once('../db.php');

try {
    // Query untuk mendapatkan adminWallet
    $sql = "SELECT adminWallet, linkTMA, linkHost FROM config LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // Mengambil hasil
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Mengirim response dalam format JSON
        echo json_encode([
            'adminWallet' => $result['adminWallet'],
            'linkHost' => $result['linkHost'],
            'linkTMA' => $result['linkTMA']
        ]);
    } else {
        echo json_encode(['error' => 'No admin wallet found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}
?>