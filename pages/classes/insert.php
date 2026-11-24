<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"])) {
    $title = trim($_POST["title"]);

    if (!empty($title)) {
        $title_escaped = $conn->real_escape_string($title);

        $check = $conn->query("SELECT id FROM classes WHERE title = '$title_escaped'");
        if ($check && $check->num_rows > 0) {
            echo "Такой класс уже существует! <a href='../../index.php?page=classCreate'>Назад</a>";
            exit();
        }

        $sql = "INSERT INTO classes (title) VALUES ('$title_escaped')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=classes");
            exit();
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } else {
        echo "Введите название класса! <a href='../../index.php?page=classCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=classes");
    exit();
}
?>
