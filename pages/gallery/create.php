<?php
require_once __DIR__ . '/../../includes/conn.php';
$users = $conn->query("SELECT id, full_name FROM users ORDER BY full_name");
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Добавить фото в галерею</h4>
    </div>
    <div class="card-body">
        <form action="pages/gallery/insert.php" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Название или подпись к фото</label>
                <input type="text" name="title" class="form-control" placeholder="Например: Олимпиада по математике 2026" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Выберите изображение</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Кто загрузил</label>
                <select name="uploaded_by" class="form-select" required>
                    <option value="">Выберите пользователя...</option>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Загрузить фото</button>
            <a href="index.php?page=gallery" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>
