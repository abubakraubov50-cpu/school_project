<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Удаляем прикрепленную картинку с сервера, чтобы не засорять диск
    $res = $conn->query("SELECT image FROM news WHERE id = $id");
    if ($row = $res->fetch_assoc()) {
        $filePath = "../../uploads/news/" . $row['image'];
        if (!empty($row['image']) && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $conn->query("DELETE FROM news WHERE id = $id");
}

header("Location: ../../index.php?page=news");
exit();
?>