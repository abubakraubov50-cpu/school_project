<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title     = $conn->real_escape_string(trim($_POST["title"]));
    $content   = $conn->real_escape_string(trim($_POST["content"]));
    $author_id = intval($_POST["author_id"]);
    $imageName = null;

    // Обработка загрузки картинки
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../../uploads/news/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
    }

    if (!empty($title) && !empty($content) && $author_id) {
        $imgSql = $imageName ? "'$imageName'" : "NULL";
        $sql = "INSERT INTO news (title, content, image, author_id) 
                VALUES ('$title', '$content', $imgSql, $author_id)";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=news");
            exit();
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } else {
        echo "Заполните обязательные поля! <a href='../../index.php?page=newsCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=news");
    exit();
}
?>