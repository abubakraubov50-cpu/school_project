<?php
require_once __DIR__ . '/conn.php';

$q = trim($_GET['q'] ?? '');
if (mb_strlen($q) < 2) {
    exit();
}

$safeQ = $conn->real_escape_string($q);

$users    = $conn->query("SELECT id, full_name, role FROM users WHERE full_name LIKE '%$safeQ%' OR email LIKE '%$safeQ%' LIMIT 5");
$subjects = $conn->query("SELECT id, title FROM subjects WHERE title LIKE '%$safeQ%' LIMIT 4");
$classes  = $conn->query("SELECT id, title FROM classes WHERE title LIKE '%$safeQ%' LIMIT 4");

$rolesMap = ['admin' => 'Админ', 'teacher' => 'Учитель', 'student' => 'Ученик'];
$foundAny = false;

if ($users && $users->num_rows > 0) {
    $foundAny = true;
    echo '<div class="dropdown-header text-uppercase fs-6 text-muted">Пользователи</div>';
    while ($u = $users->fetch_assoc()) {
        $role = $rolesMap[$u['role']] ?? $u['role'];
        echo '<a class="dropdown-item rounded d-flex justify-content-between align-items-center" href="index.php?page=userEdit&id=' . $u['id'] . '">';
        echo '<span>' . htmlspecialchars($u['full_name']) . '</span>';
        echo '<span class="badge bg-soft-primary text-primary">' . $role . '</span>';
        echo '</a>';
    }
}

if ($subjects && $subjects->num_rows > 0) {
    $foundAny = true;
    echo '<div class="dropdown-header text-uppercase fs-6 text-muted mt-2">Предметы</div>';
    while ($s = $subjects->fetch_assoc()) {
        echo '<a class="dropdown-item rounded" href="index.php?page=subjectEdit&id=' . $s['id'] . '">';
        echo '<i class="bi-book me-2 text-muted"></i>' . htmlspecialchars($s['title']);
        echo '</a>';
    }
}

if ($classes && $classes->num_rows > 0) {
    $foundAny = true;
    echo '<div class="dropdown-header text-uppercase fs-6 text-muted mt-2">Классы</div>';
    while ($c = $classes->fetch_assoc()) {
        echo '<a class="dropdown-item rounded" href="index.php?page=classEdit&id=' . $c['id'] . '">';
        echo '<i class="bi-people me-2 text-muted"></i>' . htmlspecialchars($c['title']);
        echo '</a>';
    }
}

if (!$foundAny) {
    echo '<div class="text-muted text-center py-2 fs-6">Ничего не найдено</div>';
}
