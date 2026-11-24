<?php
session_start();
require_once __DIR__ . '/includes/conn.php';

// Если пользователь уже вошёл — сразу отправляем в панель
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}



$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $safeEmail = $conn->real_escape_string($email);
        $res = $conn->query("SELECT * FROM users WHERE email = '$safeEmail' LIMIT 1");

        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();

            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['user_name']   = $user['full_name'];
                $_SESSION['user_email']  = $user['email'];
                $_SESSION['user_role']   = $user['role'];
                $_SESSION['user_avatar'] = $user['avatar'] ?? '';

                header("Location: index.php");
                exit();
            } else {
                $error = 'Неверный пароль!';
            }
        } else {
            $error = 'Пользователь с таким email не найден!';
        }
    } else {
        $error = 'Заполните все поля!';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Вход в систему | Школьный портал</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/theme.min.css">
</head>
<body class="bg-light">
<main id="content" role="main" class="main">
    <div class="container py-5 py-sm-7">
        <div class="mx-auto" style="max-width: 28rem;">
            <div class="text-center mb-4">
                <img class="zi-2" src="assets/svg/logos/logo.svg" alt="Logo" style="width: 8rem;">
            </div>

            <div class="card card-lg mb-5 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="login.php">
                        <div class="text-center mb-4">
                            <h1 class="display-5">Вход в панель</h1>
                            <p class="text-muted">Введите данные вашей учётной записи</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label" for="signinEmail">Email</label>
                            <input type="email" class="form-control form-control-lg" name="email" id="signinEmail"
                                   placeholder="name@school.tj" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="signinPassword">Пароль</label>
                            <input type="password" class="form-control form-control-lg" name="password" id="signinPassword"
                                   placeholder="••••••••" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Войти</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
