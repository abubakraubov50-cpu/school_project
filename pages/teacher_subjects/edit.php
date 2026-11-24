<?php
require_once __DIR__ . '/../../includes/conn.php';

$teacher_id = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;
if (!$teacher_id) {
    header("Location: index.php?page=teacher_subjects");
    exit();
}

// Получаем данные учителя
$tRes = $conn->query("SELECT id, full_name FROM users WHERE id = $teacher_id");
$teacher = $tRes ? $tRes->fetch_assoc() : null;

if (!$teacher) {
    echo "<div class='alert alert-danger'>Учитель не найден!</div>";
    exit();
}

// Получаем массив ID предметов, которые УЖЕ назначены этому учителю
$assignedRes = $conn->query("SELECT subject_id FROM teacher_subjects WHERE teacher_id = $teacher_id");
$assignedIds = [];
if ($assignedRes) {
    while ($row = $assignedRes->fetch_assoc()) {
        $assignedIds[] = $row['subject_id'];
    }
}

// Достаем вообще все предметы для вывода чекбоксов
$allSubjects = $conn->query("SELECT id, title FROM subjects ORDER BY title");
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Редактировать предметы: <?= htmlspecialchars($teacher['full_name']) ?></h4>
    </div>
    <div class="card-body">
        <form action="pages/teacher_subjects/update.php" method="POST">
            <input type="hidden" name="teacher_id" value="<?= $teacher['id'] ?>">

            <div class="mb-3">
                <label class="form-label d-block">Выберите предметы для учителя:</label>
                <div class="row">
                    <?php while ($s = $allSubjects->fetch_assoc()): ?>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="subjects[]" value="<?= $s['id'] ?>" id="sub_<?= $s['id'] ?>"
                                        <?= in_array($s['id'], $assignedIds) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sub_<?= $s['id'] ?>">
                                    <?= htmlspecialchars($s['title']) ?>
                                </label>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="index.php?page=teacher_subjects" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>