<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$cart = $data['cart'] ?? [];
if (!$cart) {
    echo json_encode(['success' => false, 'message' => 'Корзина пуста']);
    exit;
}
// Здесь можно добавить сохранение заказа в БД
echo json_encode(['success' => true]);
?>