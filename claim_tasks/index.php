<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$taskId = $data['taskId'] ?? null;
$telegram_id = $data['telegram_id'];

if (!$taskId) {
    echo json_encode(['success' => false, 'error' => 'Task ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM user_task WHERE telegram_id = ? AND task_id = ?");
    $stmt->execute([$telegram_id, $taskId]);
    $task = $stmt->fetch();

    if ($task && $task['status'] === 'complete') {
        $stmt = $pdo->prepare("UPDATE user_task SET status = 'claim', claimed_at = NOW() WHERE telegram_id = ? AND task_id = ?");
        $stmt->execute([$telegram_id, $taskId]);

        $stmt = $pdo->prepare("SELECT reward FROM tasks WHERE id = ?");
        $stmt->execute([$taskId]);
        $taskData = $stmt->fetch();

        if (!$taskData) {
            echo json_encode(['success' => false, 'error' => 'Task reward not found']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO history (telegram_id, type, amount, status, created_at) VALUES (?, 'Task', ?, 'Completed', NOW())");
        $stmt->execute([$telegram_id, $taskData['reward']]);

        $stmt = $pdo->prepare("UPDATE user SET hours = hours + ? WHERE telegram_id = ?");
        $stmt->execute([$taskData['reward'], $telegram_id]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Task not completed or already claimed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
