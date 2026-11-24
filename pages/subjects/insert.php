<?php
// Подключаем базу данных
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"])) {
    $title = trim($_POST["title"]);
    
    if (!empty($title)) {
        // Экранируем для безопасности
        $title_escaped = $conn->real_escape_string($title);
        
        $sql = "INSERT INTO subjects (title) VALUES ('$title_escaped')";
        
        if ($conn->query($sql) === TRUE) {
            // Сразу после успешного добавления кидает на список в index.php
            header("Location: ../../index.php?page=subjects");
            exit();
        } else {
            echo "Ошибка базы данных: " . $conn->error;
        }
    } else {
        echo "Название предмета не может быть пустым! <a href='../../index.php?page=subjectCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=subjects");
    exit();
}
?>