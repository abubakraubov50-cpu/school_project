<?php
require_once __DIR__ . '/../../includes/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: index.php?page=news");
    exit();
}

$res = $conn->query("SELECT * FROM news WHERE id = $id");
$news = $res ? $res->fetch_assoc() : null;

if (!$news) {
    echo "<div class='alert alert-danger'>Новость не найдена!</div>";
    exit();
}

$authors = $conn->query("SELECT id, full_name FROM users ORDER BY full_name");
?>

<div class="card">
  <div class="card-header">
    <h4 class="card-header-title">Редактировать новость</h4>
  </div>
  <div class="card-body">
    <form action="pages/news/update.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $news['id'] ?>">

      <div class="mb-3">
        <label class="form-label">Заголовок новости</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($news['title']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Текст новости</label>
        <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($news['content']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Новое изображение (оставьте пустым, чтобы не менять текущее)</label>
        <?php if (!empty($news['image'])): ?>
          <div class="mb-2">
            <img src="uploads/news/<?= htmlspecialchars($news['image']) ?>" alt="Текущее фото" style="max-height: 100px; border-radius: 4px;">
          </div>
        <?php endif; ?>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
        <label class="form-label">Автор</label>
        <select name="author_id" class="form-select" required>
          <?php while ($a = $authors->fetch_assoc()): ?>
            <option value="<?= $a['id'] ?>" <?= $a['id'] == $news['author_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($a['full_name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Сохранить изменения</button>
      <a href="index.php?page=news" class="btn btn-white">Отмена</a>
    </form>
  </div>
</div>