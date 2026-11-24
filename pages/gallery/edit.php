<?php
require_once __DIR__ . '/../../includes/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: index.php?page=gallery");
    exit();
}

$res = $conn->query("SELECT * FROM gallery WHERE id = $id");
$item = $res ? $res->fetch_assoc() : null;

if (!$item) {
    echo "<div class='alert alert-danger'>Фотография не найдена!</div>";
    exit();
}

$users = $conn->query("SELECT id, full_name FROM users ORDER BY full_name");
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Редактировать запись в галерее</h4>
    </div>
    <div class="card-body">
        <form action="pages/gallery/update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $item['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Название или подпись</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($item['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Новое изображение (оставьте пустым, чтобы не менять текущее)</label>
                <?php if (!empty($item['image_path'])): ?>
                    <div class="mb-2">
                        <img src="uploads/gallery/<?= htmlspecialchars($item['image_path']) ?>" alt="Текущее фото" style="max-height: 120px; border-radius: 6px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Кто загрузил</label>
                <select name="uploaded_by" class="form-select" required>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <option value="<?= $u['id'] ?>" <?= $u['id'] == $item['uploaded_by'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['full_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="index.php?page=gallery" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>