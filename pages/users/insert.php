<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string(trim($_POST["full_name"]));
    $email     = $conn->real_escape_string(trim($_POST["email"]));
    $password  = $_POST["password"];
    $role      = $conn->real_escape_string($_POST["role"]);
    $class_id  = !empty($_POST["class_id"]) ? intval($_POST["class_id"]) : "NULL";
    $avatarName = null;

    if (!empty($full_name) && !empty($email) && !empty($password)) {
        // 1. Проверяем, не занят ли этот email
        $checkEmail = $conn->query("SELECT id FROM users WHERE email = '$email'");
        if ($checkEmail && $checkEmail->num_rows > 0) {
            echo "<div style='font-family: sans-serif; padding: 20px;'>";
            echo "<h3>Ошибка: Пользователь с email <b>" . htmlspecialchars($email) . "</b> уже существует!</h3>";
            echo "<a href='../../index.php?page=userCreate'>← Вернуться назад</a>";
            echo "</div>";
            exit();
        }

        // 2. Загружаем аватар только после проверки email
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../../uploads/avatars/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $avatarName = time() . "_" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $avatarName);
        }

        // 3. Сохраняем нового пользователя
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $avatarSql = $avatarName ? "'$avatarName'" : "NULL";

        $sql = "INSERT INTO users (full_name, email, password_hash, role, class_id, avatar) 
                VALUES ('$full_name', '$email', '$hashedPassword', '$role', $class_id, $avatarSql)";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=users");
            exit();
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } else {
        echo "Заполните обязательные поля! <a href='../../index.php?page=userCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=users");
    exit();
}
?>