<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id        = intval($_POST["id"]);
    $title     = $conn->real_escape_string(trim($_POST["title"]));
    $content   = $conn->real_escape_string(trim($_POST["content"]));
    $author_id = intval($_POST["author_id"]);

    $imageUpdateSql = "";

    // Если загрузили новую картинку — сохраняем её и удаляем старую
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../../uploads/news/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Удаляем старый файл с диска, если был
        $oldRes = $conn->query("SELECT image FROM news WHERE id = $id");
        if ($oldRow = $oldRes->fetch_assoc()) {
            if (!empty($oldRow['image']) && file_exists($uploadDir . $oldRow['image'])) {
                unlink($uploadDir . $oldRow['image']);
            }
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        $imageUpdateSql = ", image = '$imageName'";
    }

    $sql = "UPDATE news 
            SET title = '$title', 
                content = '$content', 
                author_id = $author_id 
                $imageUpdateSql 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?page=news");
        exit();
    } else {
        echo "Ошибка обновления: " . $conn->error;
    }
} else {
    header("Location: ../../index.php?page=news");
    exit();
}
?>