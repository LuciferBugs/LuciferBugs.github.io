<?php
header('Content-Type: application/json');
require '../db.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$taskId = $data['taskId'];
$telegramId = $data['telegram_id'];

// Check if task exists
$stmt = $pdo->prepare("SELECT * FROM user_task WHERE telegram_id = ? AND task_id = ?");
$stmt->execute([$telegramId, $taskId]);
$task = $stmt->fetch();

if ($task) {
    if ($task['status'] === 'false') {
        // Mark task as complete
        $stmt = $pdo->prepare("UPDATE user_task SET status = 'complete', completed_at = NOW() WHERE telegram_id = ? AND task_id = ?");
        $stmt->execute([$telegramId, $taskId]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Task already completed']);
    }
} else {
    // Insert new task entry
    $stmt = $pdo->prepare("INSERT INTO user_task (telegram_id, task_id, status, completed_at) VALUES (?, ?, 'complete', NOW())");
    $stmt->execute([$telegramId, $taskId]);
    echo json_encode(['success' => true]);
}
?>
