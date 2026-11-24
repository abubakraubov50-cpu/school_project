<?php
require_once __DIR__ . '/../../includes/conn.php';
$authors = $conn->query("SELECT id, full_name FROM users ORDER BY full_name");
?>

<div class="card">
  <div class="card-header">
    <h4 class="card-header-title">Добавить новость</h4>
  </div>
  <div class="card-body">
    <!-- enctype="multipart/form-data" обязателен для загрузки картинок -->
    <form action="pages/news/insert.php" method="POST" enctype="multipart/form-data">
      
      <div class="mb-3">
        <label class="form-label">Заголовок новости</label>
        <input type="text" name="title" class="form-control" placeholder="Например: День открытых дверей" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Текст новости</label>
        <textarea name="content" class="form-control" rows="5" placeholder="Введите текст новости..." required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Изображение (необязательно)</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
        <label class="form-label">Автор</label>
        <select name="author_id" class="form-select" required>
          <option value="">Выберите автора...</option>
          <?php while ($a = $authors->fetch_assoc()): ?>
            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['full_name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Опубликовать новость</button>
      <a href="index.php?page=news" class="btn btn-white">Отмена</a>
    </form>
  </div>
</div>