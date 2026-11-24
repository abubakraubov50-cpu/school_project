<?php
require_once __DIR__ . '/../../includes/conn.php';

$selected_student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

$current_teacher_id = (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher') ? (int)$_SESSION['user_id'] : 0;

$students = $conn->query("SELECT id, full_name FROM users WHERE role = 'student' ORDER BY full_name");
$subjects = $conn->query("SELECT id, title FROM subjects ORDER BY title");
$teachers = $conn->query("SELECT id, full_name FROM users WHERE role = 'teacher' ORDER BY full_name");
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Выставить оценку</h4>
    </div>
    <div class="card-body">
        <form action="pages/grades/insert.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Ученик</label>
                <select name="student_id" class="form-select" required>
                    <option value="">Выберите ученика...</option>
                    <?php while ($st = $students->fetch_assoc()): ?>
                        <option value="<?= $st['id'] ?>" <?= ($st['id'] == $selected_student_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($st['full_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

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
                <label class="form-label">Учитель</label>
                <select name="teacher_id" class="form-select" required>
                    <option value="">Выберите учителя...</option>
                    <?php while ($t = $teachers->fetch_assoc()): ?>
                        <option value="<?= $t['id'] ?>" <?= ($t['id'] == $current_teacher_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['full_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Оценка</label>
                <select name="grade" class="form-select" required>
                    <option value="5">5 (Отлично)</option>
                    <option value="4">4 (Хорошо)</option>
                    <option value="3">3 (Удовлетворительно)</option>
                    <option value="2">2 (Неудовлетворительно)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Дата оценки</label>
                <input type="date" name="grade_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Комментарий (необязательно)</label>
                <textarea name="COMMENT" class="form-control" rows="3" placeholder="Например: За контрольную работу"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить оценку</button>
            <a href="index.php?page=grades" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>