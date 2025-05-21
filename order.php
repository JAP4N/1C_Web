<?php
session_start();
header('Content-Type: application/json');

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

// Получаем данные из запроса
$data = json_decode(file_get_contents('php://input'), true);
$cart = $data['cart'] ?? [];
$phone = trim($data['phone'] ?? '');

if (!$cart || !$phone) {
    echo json_encode(['success' => false, 'message' => 'Корзина пуста или номер телефона не указан']);
    exit;
}

// Привязка цен к услугам
$prices = [
    'Отчетность' => 2600,
    'Комплексный сервис' => 6100
];

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', 'users_db');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Ошибка подключения к БД']);
    exit;
}
$mysqli->set_charset('utf8mb4');

// Подготовка SQL-запроса
$stmt = $mysqli->prepare("INSERT INTO orders (user_id, username, service, price, phone, created_at, deadline) VALUES (?, ?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY))");

// Добавляем каждую услугу из корзины в таблицу `orders`
foreach ($cart as $service) {
    $price = isset($prices[$service]) ? $prices[$service] : 0;
    $stmt->bind_param('issds', $user_id, $username, $service, $price, $phone);
    $stmt->execute();
}

// Закрываем соединение
$stmt->close();
$mysqli->close();

// Возвращаем успешный ответ
echo json_encode(['success' => true]);
?>