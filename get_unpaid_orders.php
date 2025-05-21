<?php
session_start();
header('Content-Type: application/json');

// Проверяем, является ли пользователь администратором
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
    exit;
}

// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', 'users_db');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Ошибка подключения к БД']);
    exit;
}
$mysqli->set_charset('utf8mb4');

// Получение неоплаченных заказов
$query = "SELECT id, user_id, username, service, price, phone, created_at, deadline 
          FROM orders 
          WHERE paid = FALSE 
          ORDER BY created_at DESC";

$result = $mysqli->query($query);
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode(['success' => true, 'orders' => $orders]);

$mysqli->close();
?>