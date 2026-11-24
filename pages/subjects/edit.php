<?php
// Подключаем базу данных через абсолютный путь
require_once __DIR__ . '/../../includes/conn.php';

// Получаем ID предмета из ссылки
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php?page=subjects");
    exit();
}

$id = intval($id);

$result = $conn->query("SELECT * FROM subjects WHERE id = $id");
$subject = $result ? $result->fetch_assoc() : null;

if (!$subject) {
    echo "<div class='alert alert-danger'>Предмет не найден! <a href='index.php?page=subjects'>Назад к списку</a></div>";
    exit();
}
?>

<div class="card">
  <div class="card-header">
    <h4 class="card-header-title">Редактировать предмет</h4>
  </div>
  <div class="card-body">
    <form action="pages/subjects/update.php" method="POST">
      
      <input type="hidden" name="id" value="<?= $subject['id'] ?>">

      <div class="mb-3">
        <label for="title" class="form-label">Название предмета</label>
        <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($subject['title']) ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">Сохранить изменения</button>
      <a href="index.php?page=subjects" class="btn btn-white">Отмена</a>
    </form>
  </div>
</div>