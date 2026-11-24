<?php
require_once __DIR__ . '/../../includes/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: index.php?page=grades");
    exit();
}

$res = $conn->query("SELECT * FROM grades WHERE id = $id");
$item = $res ? $res->fetch_assoc() : null;

if (!$item) {
    echo "<div class='alert alert-danger'>Запись не найдена!</div>";
    exit();
}

$students = $conn->query("SELECT id, full_name FROM users WHERE role = 'student' ORDER BY full_name");
$subjects = $conn->query("SELECT id, title FROM subjects ORDER BY title");
$teachers = $conn->query("SELECT id, full_name FROM users WHERE role = 'teacher' ORDER BY full_name");
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Редактировать оценку</h4>
    </div>
    <div class="card-body">
        <form action="pages/grades/update.php" method="POST">
            <input type="hidden" name="id" value="<?= $item['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Ученик</label>
                <select name="student_id" class="form-select" required>
                    <?php while ($st = $students->fetch_assoc()): ?>
                        <option value="<?= $st['id'] ?>" <?= $st['id'] == $item['student_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($st['full_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Предмет</label>
                <select name="subject_id" class="form-select" required>
                    <?php while ($s = $subjects->fetch_assoc()): ?>
                        <option value="<?= $s['id'] ?>" <?= $s['id'] == $item['subject_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['title']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Учитель</label>
                <select name="teacher_id" class="form-select" required>
                    <?php while ($t = $teachers->fetch_assoc()): ?>
                        <option value="<?= $t['id'] ?>" <?= $t['id'] == $item['teacher_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['full_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Оценка</label>
                <select name="grade" class="form-select" required>
                    <option value="5" <?= $item['grade'] == '5' ? 'selected' : '' ?>>5 (Отлично)</option>
                    <option value="4" <?= $item['grade'] == '4' ? 'selected' : '' ?>>4 (Хорошо)</option>
                    <option value="3" <?= $item['grade'] == '3' ? 'selected' : '' ?>>3 (Удовлетворительно)</option>
                    <option value="2" <?= $item['grade'] == '2' ? 'selected' : '' ?>>2 (Неудовлетворительно)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Дата оценки</label>
                <input type="date" name="grade_date" class="form-control" value="<?= htmlspecialchars($item['grade_date']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Комментарий</label>
                <textarea name="COMMENT" class="form-control" rows="3"><?= htmlspecialchars($item['COMMENT'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="index.php?page=grades" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>
