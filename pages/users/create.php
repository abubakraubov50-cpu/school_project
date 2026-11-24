<?php
require_once __DIR__ . '/../../includes/conn.php';
$classes = $conn->query("SELECT id, title FROM classes ORDER BY title");
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Добавить пользователя</h4>
    </div>
    <div class="card-body">
        <form action="pages/users/insert.php" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">ФИО (Полное имя)</label>
                <input type="text" name="full_name" class="form-control" placeholder="Например: Али Валиев" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="user@example.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input type="password" name="password" class="form-control" placeholder="Введите пароль" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Роль</label>
                <select name="role" class="form-select" required>
                    <option value="student">Ученик (student)</option>
                    <option value="teacher">Учитель (teacher)</option>
                    <option value="admin">Администратор (admin)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Класс (необязательно)</label>
                <select name="class_id" class="form-select">
                    <option value="">Без класса</option>
                    <?php while ($c = $classes->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Аватар (необязательно)</label>
                <input type="file" name="avatar" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Создать пользователя</button>
            <a href="index.php?page=users" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>
