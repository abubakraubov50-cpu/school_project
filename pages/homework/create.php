<?php
require_once __DIR__ . '/../../includes/conn.php';

$subjects = $conn->query("SELECT id, title FROM subjects ORDER BY title");
$classes  = $conn->query("SELECT id, title FROM classes ORDER BY title");
$teachers = $conn->query("SELECT id, full_name FROM users WHERE role = 'teacher' ORDER BY full_name");
?>

<div class="card">
  <div class="card-header">
    <h4 class="card-header-title">Добавить домашнее задание</h4>
  </div>
  <div class="card-body">
    <form action="pages/homework/insert.php" method="POST">
      
      <div class="mb-3">
        <label class="form-label">Предмет</label>
        <select name="subject_id" class="form-select" required>
          <option value="">Выберите предмет...</option>
          <?php while ($s = $subjects->fetch_assoc()): ?>
            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['title']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Класс</label>
        <select name="class_id" class="form-select" required>
          <option value="">Выберите класс...</option>
          <?php while ($c = $classes->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Учитель</label>
        <select name="teacher_id" class="form-select" required>
          <option value="">Выберите учителя...</option>
          <?php while ($t = $teachers->fetch_assoc()): ?>
            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['full_name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Описание задания</label>
        <textarea name="description" class="form-control" rows="4" placeholder="Например: Решить задачи №15-20 на стр. 42" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Срок сдачи</label>
        <input type="date" name="due_date" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Сохранить задание</button>
      <a href="index.php?page=homework" class="btn btn-white">Отмена</a>
    </form>
  </div>
</div>