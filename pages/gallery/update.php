<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id          = intval($_POST["id"]);
    $title       = $conn->real_escape_string(trim($_POST["title"]));
    $uploaded_by = intval($_POST["uploaded_by"]);

    $imageUpdateSql = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../../uploads/gallery/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Удаляем старую картинку
        $oldRes = $conn->query("SELECT image_path FROM gallery WHERE id = $id");
        if ($oldRow = $oldRes->fetch_assoc()) {
            if (!empty($oldRow['image_path']) && file_exists($uploadDir . $oldRow['image_path'])) {
                unlink($uploadDir . $oldRow['image_path']);
            }
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        $imageUpdateSql = ", image_path = '$imageName'";
    }

    $sql = "UPDATE gallery 
            SET title = '$title', 
                uploaded_by = $uploaded_by 
                $imageUpdateSql 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?page=gallery");
        exit();
    } else {
        echo "Ошибка обновления: " . $conn->error;
    }
} else {
    header("Location: ../../index.php?page=gallery");
    exit();
}
?>
