<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id        = intval($_POST["id"]);
    $full_name = $conn->real_escape_string(trim($_POST["full_name"]));
    $email     = $conn->real_escape_string(trim($_POST["email"]));
    $role      = $conn->real_escape_string($_POST["role"]);
    $class_id  = !empty($_POST["class_id"]) ? intval($_POST["class_id"]) : "NULL";

    // Если ввели новый пароль — обновляем хэш
    $passwordSql = "";
    if (!empty($_POST["password"])) {
        $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $passwordSql = ", password_hash = '$hashedPassword'";
    }

    // Если загрузили новый аватар — меняем файл
    $avatarSql = "";
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../../uploads/avatars/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $oldRes = $conn->query("SELECT avatar FROM users WHERE id = $id");
        if ($oldRow = $oldRes->fetch_assoc()) {
            if (!empty($oldRow['avatar']) && file_exists($uploadDir . $oldRow['avatar'])) {
                unlink($uploadDir . $oldRow['avatar']);
            }
        }

        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatarName = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $avatarName);
        $avatarSql = ", avatar = '$avatarName'";
    }

    $sql = "UPDATE users 
            SET full_name = '$full_name', 
                email = '$email', 
                role = '$role', 
                class_id = $class_id 
                $passwordSql 
                $avatarSql 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?page=users");
        exit();
    } else {
        echo "Ошибка обновления: " . $conn->error;
    }
} else {
    header("Location: ../../index.php?page=users");
    exit();
}
?>
