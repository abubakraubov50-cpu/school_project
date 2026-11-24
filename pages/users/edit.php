<?php
require_once __DIR__ . '/../../includes/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: index.php?page=users");
    exit();
}

$res = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $res ? $res->fetch_assoc() : null;

if (!$user) {
    echo "<div class='alert alert-danger'>Пользователь не найден!</div>";
    exit();
}

$classes = $conn->query("SELECT id, title FROM classes ORDER BY title");
?>

    <div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Редактировать пользователя</h4>
    </div>
    <div class="card-body">
        <form action="pages/users/update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <div class="mb-3">
                <label class="form-label">ФИО (Полное имя)</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Новый пароль (оставьте пустым, если не хотите менять)</label>
                <input type="password" name="password" class="form-control" placeholder="Новый пароль">
            </div>

            <div class="mb-3">
                <label class="form-label">Роль</label>
                <select name="role" class="form-select" required>
                    <option value="student" <?= $user['role'] == 'student' ? 'selected' : '' ?>>Ученик (student)</option>
                    <option value="teacher" <?= $user['role'] == 'teacher' ? 'selected' : '' ?>>Учитель (teacher)</option>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Администратор (admin)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Класс</label>
                <select name="class_id" class="form-select">
                    <option value="">Без класса</option>
                    <?php while ($c = $classes->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $user['class_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['title']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Новый аватар (необязательно)</label>
                <?php if (!empty($user['avatar'])): ?>
                    <div class="mb-2">
                        <img src="uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                    </div>
                <?php endif; ?>
                <input type="file" name="avatar" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="index.php?page=users" class="btn btn-white">Отмена</a>
        </form>
    </div>
    </div>
