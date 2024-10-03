<?php
require('db.php');

function updateAllUsersTime($pdo) {
    $sql = "SELECT days, hours, minutes, seconds, speed, telegram_id FROM user";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users_data as $user_data) {
        $current_days = intval($user_data['days']);
        $current_hours = intval($user_data['hours']);
        $current_minutes = intval($user_data['minutes']);
        $current_seconds = intval($user_data['seconds']);
        $telegram_id = intval($user_data['telegram_id']);

        $speed = intval($user_data['speed']);

        $current_seconds += $speed;

        if ($current_seconds >= 60) {
            $current_seconds = $current_seconds % 60;
            $current_minutes += 1;
        }

        if ($current_minutes >= 60) {
            $current_minutes = $current_minutes % 60;
            $current_hours += 1;
        }

        if ($current_hours >= 24) {
            $current_hours = $current_hours % 24;
            $current_days += 1;
        }

        $update_sql = "UPDATE user 
                       SET days = :days, hours = :hours, minutes = :minutes, seconds = :seconds 
                       WHERE telegram_id = :telegram_id";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':days', $current_days, PDO::PARAM_INT);
        $update_stmt->bindParam(':hours', $current_hours, PDO::PARAM_INT);
        $update_stmt->bindParam(':minutes', $current_minutes, PDO::PARAM_INT);
        $update_stmt->bindParam(':seconds', $current_seconds, PDO::PARAM_INT);
        $update_stmt->bindParam(':telegram_id', $telegram_id, PDO::PARAM_INT);

        if (!$update_stmt->execute()) {
            return ['status' => 'error', 'message' => 'Failed to update user time for telegram_id: ' . $telegram_id];
        }
    }

    return ['status' => 'success', 'message' => 'All users updated successfully'];
}

$result = updateAllUsersTime($pdo);

echo json_encode($result);
?>
