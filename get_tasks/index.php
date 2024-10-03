<?php
header('Content-Type: application/json');
require '../db.php';
$data = json_decode(file_get_contents('php://input'), true);
$telegram_id = $data['telegram_id'];

// Fetch tasks along with user task status
$stmt = $pdo->prepare("
    SELECT t.id, t.description, t.link, t.reward, 
           IFNULL(ut.status, 'false') AS status
    FROM tasks t
    LEFT JOIN user_task ut ON t.id = ut.task_id AND ut.telegram_id = ?
");
$stmt->execute([$telegram_id]);
$tasks = $stmt->fetchAll();

// Map status to completed and claimed for frontend
$tasks = array_map(function($task) {
    return [
        'id' => $task['id'],
        'description' => $task['description'],
        'link' => $task['link'],
        'reward' => $task['reward'],
        'completed' => $task['status'] === 'complete',
        'claimed' => $task['status'] === 'claim',
        'false' => $task['status'] === 'false'
    ];
}, $tasks);

// Send tasks to the frontend
echo json_encode(['success' => true, 'tasks' => $tasks]);
?>
