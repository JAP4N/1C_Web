<?php
session_start();
session_unset(); // Очистить все данные сессии
session_destroy(); // Уничтожить сессию
header('Location: index.php'); // Перенаправить на главную страницу
exit;
?>