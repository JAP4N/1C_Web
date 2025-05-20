<?php
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $mysqli = new mysqli('localhost', 'root', '', 'users_db');
    if ($mysqli->connect_errno) {
        $error = 'Ошибка подключения к БД';
    } else {
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username=?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($id, $hash);
        if ($stmt->fetch() && password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            echo json_encode(['success' => true, 'message' => 'Вы успешно авторизовались']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Неверный логин или пароль']);
        }
        $stmt->close();
        $mysqli->close();
    }
}
?>