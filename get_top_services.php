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

// Получаем параметры даты из GET-запроса
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

if (!$start_date || !$end_date) {
    echo json_encode(['success' => false, 'message' => 'Укажите начальную и конечную дату']);
    exit;
}

// Запрос для получения услуг с максимальным доходом
$query = "
    SELECT service, SUM(price) AS total_income, COUNT(*) AS total_orders
    FROM orders
    WHERE created_at BETWEEN ? AND ?
    GROUP BY service
    ORDER BY total_income DESC
    LIMIT 10
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

echo json_encode(['success' => true, 'services' => $services]);

$stmt->close();
$mysqli->close();
?>