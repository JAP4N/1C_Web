<?php
session_start();
header('Content-Type: application/json');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Гость';
$name = trim($_POST['name'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$question = trim($_POST['question'] ?? '');

if (!$name || !$telephone || !$question) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'users_db');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Ошибка подключения к БД']);
    exit;
}
$mysqli->set_charset('utf8mb4');

$stmt = $mysqli->prepare("INSERT INTO advantages_requests (user_id, username, name, telephone, question, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param('issss', $user_id, $username, $name, $telephone, $question);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка сохранения']);
}
$stmt->close();
$mysqli->close();