<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Отвязываем учеников от удаляемого класса и очищаем связанные ДЗ, чтобы не было ошибки Foreign Key
    $conn->query("UPDATE users SET class_id = NULL WHERE class_id = $id");
    $conn->query("DELETE FROM homework WHERE class_id = $id");

    $conn->query("DELETE FROM classes WHERE id = $id");
}

header("Location: ../../index.php?page=classes");
exit();
?>
