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
$res = $mysqli->query("SELECT id, user_id, username, service, price, phone, created_at FROM orders ORDER BY created_at DESC");
$orders = [];
while ($row = $res->fetch_assoc()) {
    $orders[] = $row;
}
echo json_encode(['success' => true, 'orders' => $orders]);
?>