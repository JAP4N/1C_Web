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

// Получаем тип отчета и формат экспорта
$reportType = $_GET['report_type'] ?? null;
$format = $_GET['format'] ?? null;

if (!$reportType || !$format) {
    echo json_encode(['success' => false, 'message' => 'Не указан тип отчета или формат']);
    exit;
}

// Получение данных для отчета
$query = '';
if ($reportType === 'orders') {
    $query = "SELECT id, user_id, username, service, price, phone, created_at FROM orders ORDER BY created_at DESC";
} elseif ($reportType === 'requests') {
    $query = "SELECT id, user_id, username, name, telephone, question, created_at FROM advantages_requests ORDER BY created_at DESC";
} elseif ($reportType === 'overdue') {
    $query = "SELECT id, user_id, username, service, price, phone, created_at, deadline FROM orders WHERE completed_at IS NULL AND deadline < NOW() ORDER BY deadline DESC";
} elseif ($reportType === 'top_services') {
    $start_date = $_GET['start_date'] ?? null;
    $end_date = $_GET['end_date'] ?? null;
    if (!$start_date || !$end_date) {
        echo json_encode(['success' => false, 'message' => 'Укажите начальную и конечную дату']);
        exit;
    }
    $query = "
        SELECT service, SUM(price) AS total_income, COUNT(*) AS total_orders
        FROM orders
        WHERE created_at BETWEEN '$start_date' AND '$end_date'
        GROUP BY service
        ORDER BY total_income DESC
        LIMIT 10
    ";
}

// Проверяем, сформировался ли запрос
if (empty($query)) {
    echo json_encode(['success' => false, 'message' => 'Неверный тип отчета']);
    exit;
}

$result = $mysqli->query($query);
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Ошибка выполнения запроса']);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Экспорт в Word
if ($format === 'word') {
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="report.docx"');

    echo "<html><body><table border='1'>";
    foreach ($data as $index => $row) {
        if ($index === 0) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<th>$key</th>";
            }
            echo "</tr>";
        }
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table></body></html>";
    exit;
}

// Экспорт в Excel
if ($format === 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="report.xls"');

    echo "<table border='1'>";
    foreach ($data as $index => $row) {
        if ($index === 0) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<th>$key</th>";
            }
            echo "</tr>";
        }
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    exit;
}

echo json_encode(['success' => false, 'message' => 'Неверный формат']);
?>