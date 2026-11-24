<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $res = $conn->query("SELECT image_path FROM gallery WHERE id = $id");
    if ($row = $res->fetch_assoc()) {
        $filePath = "../../uploads/gallery/" . $row['image_path'];
        if (!empty($row['image_path']) && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $conn->query("DELETE FROM gallery WHERE id = $id");
}

header("Location: ../../index.php?page=gallery");
exit();
?>
