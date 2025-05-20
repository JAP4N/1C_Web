<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'users_db');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Ошибка подключения к БД']);
    exit;
}
$mysqli->set_charset('utf8mb4');
$res = $mysqli->query("SELECT id, user_id, username, name, telephone, question, created_at FROM advantages_requests ORDER BY created_at DESC");
$requests = [];
while ($row = $res->fetch_assoc()) {
    $requests[] = $row;
}
echo json_encode(['success' => true, 'requests' => $requests]);
?>