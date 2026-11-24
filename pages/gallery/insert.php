<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title       = $conn->real_escape_string(trim($_POST["title"]));
    $uploaded_by = intval($_POST["uploaded_by"]);

    if (!empty($title) && $uploaded_by && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../../uploads/gallery/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . "_" . uniqid() . "." . $ext;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
            $sql = "INSERT INTO gallery (title, image_path, uploaded_by) 
                    VALUES ('$title', '$imageName', $uploaded_by)";

            if ($conn->query($sql) === TRUE) {
                header("Location: ../../index.php?page=gallery");
                exit();
            } else {
                echo "Ошибка базы данных: " . $conn->error;
            }
        } else {
            echo "Ошибка при сохранении файла на сервер!";
        }
    } else {
        echo "Заполните все поля и выберите картинку! <a href='../../index.php?page=galleryCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=gallery");
    exit();
}
?>
