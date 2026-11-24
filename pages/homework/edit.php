<?php
require_once __DIR__ . '/../../includes/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: index.php?page=homework");
    exit();
}

$res = $conn->query("SELECT * FROM homework WHERE id = $id");
$hw = $res ? $res->fetch_assoc() : null;

if (!$hw) {
    echo "<div class='alert alert-danger'>Задание не найдено!</div>";
    exit();
}

$subjects = $conn->query("SELECT id, title FROM subjects ORDER BY title");
$classes  = $conn->query("SELECT id, title FROM classes ORDER BY title");
$teachers = $conn->query("SELECT id, full_name FROM users WHERE role = 'teacher' ORDER BY full_name");
?>

<div class="card">
  <div class="card-header">
    <h4 class="card-header-title">Редактировать домашнее задание</h4>
  </div>
  <div class="card-body">
    <form action="pages/homework/update.php" method="POST">
      <input type="hidden" name="id" value="<?= $hw['id'] ?>">

      <div class="mb-3">
        <label class="form-label">Предмет</label>
        <select name="subject_id" class="form-select" required>
          <?php while ($s = $subjects->fetch_assoc()): ?>
            <option value="<?= $s['id'] ?>" <?= $s['id'] == $hw['subject_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['title']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Класс</label>
        <select name="class_id" class="form-select" required>
          <?php while ($c = $classes->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id'] == $hw['class_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['title']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Учитель</label>
        <select name="teacher_id" class="form-select" required>
          <?php while ($t = $teachers->fetch_assoc()): ?>
            <option value="<?= $t['id'] ?>" <?= $t['id'] == $hw['teacher_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($t['full_name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Описание задания</label>
        <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($hw['description']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Срок сдачи</label>
        <input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($hw['due_date']) ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">Сохранить изменения</button>
      <a href="index.php?page=homework" class="btn btn-white">Отмена</a>
    </form>
  </div>
</div>