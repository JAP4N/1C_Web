<?php
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        $mysqli = new mysqli('localhost', 'root', '', 'users_db');
        if ($mysqli->connect_errno) {
            echo json_encode(['success' => false, 'message' => 'Ошибка подключения к БД']);
        } else {
            $stmt = $mysqli->prepare("SELECT id FROM users WHERE username=?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'Пользователь уже существует']);
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param('ss', $username, $hash);
                if ($stmt->execute()) {
                    // Получаем ID нового пользователя
                    $user_id = $stmt->insert_id;

                    // Создаем сессию для авторизации
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;

                    echo json_encode(['success' => true, 'message' => 'Вы успешно зарегистрировались и авторизовались']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Ошибка регистрации']);
                }
            }
            $stmt->close();
            $mysqli->close();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    }
}
?>