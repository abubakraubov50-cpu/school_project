<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Удаляем аватар с диска, если есть
    $res = $conn->query("SELECT avatar FROM users WHERE id = $id");
    if ($row = $res->fetch_assoc()) {
        $filePath = "../../uploads/avatars/" . $row['avatar'];
        if (!empty($row['avatar']) && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Очищаем связи в других таблицах перед удалением, чтобы не было ошибки Foreign Key
    $conn->query("DELETE FROM homework WHERE teacher_id = $id");
    $conn->query("DELETE FROM news WHERE author_id = $id");

    $conn->query("DELETE FROM users WHERE id = $id");
}

header("Location: ../../index.php?page=users");
exit();
?>
