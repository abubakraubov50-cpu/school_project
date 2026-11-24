<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getUserRole(): string {
    return $_SESSION['user_role'] ?? 'student';
}

function isAdmin(): bool {
    return getUserRole() === 'admin';
}

function isTeacher(): bool {
    return getUserRole() === 'teacher';
}

function isStudent(): bool {
    return getUserRole() === 'student';
}

// Запрет доступа к странице, если роль не подходит
function requireRoles(array $allowedRoles): void {
    if (!in_array(getUserRole(), $allowedRoles, true)) {
        echo "<div class='alert alert-danger m-3'>
                <strong>Доступ запрещён!</strong> У вашей роли нет прав для выполнения этого действия.
              </div>";
        exit();
    }
}

// Запрет для обработчиков (insert.php, update.php, delete.php)
function requireApiRoles(array $allowedRoles): void {
    if (!in_array(getUserRole(), $allowedRoles, true)) {
        die("Ошибка 403: Недостаточно прав для выполнения операции.");
    }
}
