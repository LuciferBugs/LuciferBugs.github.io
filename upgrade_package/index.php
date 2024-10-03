<?php
require '../db.php';
// Fungsi untuk membeli paket dan memperbarui speed user
function buyPackage($telegram_id, $amount, $hash, $pdo) {
    // Lihat speed saat ini di tabel user
    $sql = "SELECT speed FROM user WHERE telegram_id = :telegram_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':telegram_id', $telegram_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        // Misalnya, setiap paket menambah 3 kecepatan
        $speed_increase = $amount * 3;

        // Update speed dengan menambahkan jumlah speed yang dihitung
        $new_speed = $user_data['speed'] + $speed_increase;

        // Update speed di tabel user
        $update_sql = "UPDATE user SET speed = :speed WHERE telegram_id = :telegram_id";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':speed', $new_speed, PDO::PARAM_INT);
        $update_stmt->bindParam(':telegram_id', $telegram_id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            // Jika berhasil memperbarui speed, masukkan data transaksi ke tabel history
            $insert_sql = "INSERT INTO history (telegram_id, type, amount, hash, status) 
                           VALUES (:telegram_id, 'Upgrade', :amount, :hash, 'Completed')";
            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->bindParam(':telegram_id', $telegram_id, PDO::PARAM_INT);
            $insert_stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $insert_stmt->bindParam(':hash', $hash, PDO::PARAM_STR);

            if ($insert_stmt->execute()) {
                // Mengembalikan respons JSON dengan hasil sukses
                return ['status' => 'success', 'new_speed' => $new_speed];
            } else {
                return ['status' => 'error', 'message' => 'Failed to insert transaction into history'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Failed to update speed'];
        }
    } else {
        return ['status' => 'error', 'message' => 'User not found'];
    }
}

// Mendapatkan input JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Memeriksa apakah request POST berisi data yang diperlukan
if (isset($data['telegram_id'], $data['amount'], $data['hash'])) {
    // Ambil data dari input JSON
    $telegram_id = $data['telegram_id'];
    $amount = $data['amount'];
    $hash = $data['hash'];

    // Panggil fungsi untuk membeli paket dan memperbarui user serta history
    $result = buyPackage($telegram_id, $amount, $hash, $pdo);

    // Kembalikan respons dalam format JSON
    echo json_encode($result);
} else {
    // Kembalikan pesan error jika data tidak lengkap
    echo json_encode(['status' => 'error', 'message' => 'Required fields are missing']);
}
?>
