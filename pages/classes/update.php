<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"], $_POST["title"])) {
    $id    = intval($_POST["id"]);
    $title = trim($_POST["title"]);

    if ($id > 0 && !empty($title)) {
        $title_escaped = $conn->real_escape_string($title);

        $sql = "UPDATE classes SET title = '$title_escaped' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=classes");
            exit();
        } else {
            echo "Ошибка обновления: " . $conn->error;
        }
    } else {
        echo "Название класса не может быть пустым! <a href='../../index.php?page=classEdit&id=$id'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=classes");
    exit();
}
?>
