<?php
header('Content-Type: application/json');
require('../db.php');

$data = json_decode(file_get_contents('php://input'), true);
$telegram_id = $data['telegram_id'];

// Fungsi untuk mengambil start_time berdasarkan telegram_id
function getUserStartTime($telegram_id, $pdo) {
    $sql = "SELECT * FROM user WHERE telegram_id = :telegram_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':telegram_id', $telegram_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {

        // Mengembalikan respons JSON dengan format hari, jam, menit, dan detik
        return [
            'status' => 'success',
            'start_time' => $user_data['start_time'],
            'speed' => $user_data['speed'],
            'elapsed_time' => [
                'days' => $user_data['days'],
                'hours' => $user_data['hours'],
                'minutes' => $user_data['minutes'],
                'seconds' => $user_data['seconds']
            ]
        ];
    } else {
        return null;
    }
}

// Panggil fungsi untuk mengambil data
$user_data = getUserStartTime($telegram_id, $pdo);

if ($user_data) {
    echo json_encode($user_data); // Kembalikan dalam format JSON
} else {
    echo json_encode(['error' => 'User not found']);
}
?>