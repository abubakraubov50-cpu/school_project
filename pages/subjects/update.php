<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

// Проверяем, пришли ли данные через POST и заполнены ли ID с названием
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["title"])) {
    $id = intval($_POST["id"]);
    $title = trim($_POST["title"]);

    if (!empty($title)) {
        // Экранируем текст для безопасности
        $title_escaped = $conn->real_escape_string($title);

        // SQL-запрос на обновление данных в таблице subjects
        $sql = "UPDATE subjects SET title = '$title_escaped' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // После успешного сохранения возвращаем пользователя на список предметов
            header("Location: ../../index.php?page=subjects");
            exit();
        } else {
            echo "Ошибка при обновлении: " . $conn->error;
        }
    } else {
        echo "Название предмета не может быть пустым! <a href='../../index.php?page=subjects'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=subjects");
    exit();
}
?>