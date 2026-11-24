<?php
session_start();
require_once __DIR__ . '/../../includes/conn.php';

$userId = intval($_SESSION['user_id'] ?? 0);
if ($userId <= 0 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php?page=profile");
    exit();
}

$action = $_POST['action'] ?? '';

// 1. Обновление личных данных
if ($action === 'info') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    if (empty($fullName) || empty($email)) {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Заполните ФИО и Email"));
        exit();
    }

    $safeName  = $conn->real_escape_string($fullName);
    $safeEmail = $conn->real_escape_string($email);

    // Проверяем, не занят ли email другим пользователем
    $check = $conn->query("SELECT id FROM users WHERE email = '$safeEmail' AND id != $userId LIMIT 1");
    if ($check && $check->num_rows > 0) {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Этот Email уже используется другим пользователем"));
        exit();
    }

    if ($conn->query("UPDATE users SET full_name = '$safeName', email = '$safeEmail' WHERE id = $userId")) {
        $_SESSION['user_name']  = $fullName;
        $_SESSION['user_email'] = $email;
        header("Location: ../../index.php?page=profile&success=" . urlencode("Личные данные успешно обновлены"));
    } else {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Ошибка базы данных: " . $conn->error));
    }
    exit();
}

// 2. Обновление аватара
if ($action === 'avatar') {
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Выберите файл изображения"));
        exit();
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Допустимы только форматы JPG, PNG или WEBP"));
        exit();
    }

    if ($_FILES['avatar']['size'] > 3 * 1024 * 1024) {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Размер фото не должен превышать 3 МБ"));
        exit();
    }

    $uploadDir = __DIR__ . '/../../uploads/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $ext;
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $newFileName)) {
        // Удаляем старый аватар, если он был
        if (!empty($_SESSION['user_avatar']) && file_exists($uploadDir . $_SESSION['user_avatar'])) {
            @unlink($uploadDir . $_SESSION['user_avatar']);
        }

        $safeFile = $conn->real_escape_string($newFileName);
        $conn->query("UPDATE users SET avatar = '$safeFile' WHERE id = $userId");
        $_SESSION['user_avatar'] = $newFileName;

        header("Location: ../../index.php?page=profile&success=" . urlencode("Фото профиля обновлено"));
    } else {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Не удалось сохранить файл на сервере"));
    }
    exit();
}

// 3. Смена пароля
if ($action === 'password') {
    $currentPass = $_POST['current_password'] ?? '';
    $newPass     = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if (mb_strlen($newPass) < 6) {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Новый пароль должен быть не короче 6 символов"));
        exit();
    }

    if ($newPass !== $confirmPass) {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Новые пароли не совпадают"));
        exit();
    }

    $res = $conn->query("SELECT password_hash FROM users WHERE id = $userId LIMIT 1");
    $row = $res ? $res->fetch_assoc() : null;

    if (!$row || !password_verify($currentPass, $row['password_hash'])) {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Текущий пароль введён неверно"));
        exit();
    }

    $newHash = $conn->real_escape_string(password_hash($newPass, PASSWORD_DEFAULT));
    if ($conn->query("UPDATE users SET password_hash = '$newHash' WHERE id = $userId")) {
        header("Location: ../../index.php?page=profile&success=" . urlencode("Пароль успешно изменён"));
    } else {
        header("Location: ../../index.php?page=profile&error=" . urlencode("Ошибка при смене пароля"));
    }
    exit();
}

header("Location: ../../index.php?page=profile");
exit();
?>
