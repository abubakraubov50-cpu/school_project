<?php
require_once __DIR__ . '/../../includes/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: index.php?page=schedule");
    exit();
}

$res = $conn->query("SELECT * FROM schedule WHERE id = $id");
$item = $res ? $res->fetch_assoc() : null;

if (!$item) {
    echo "<div class='alert alert-danger'>Урок не найден!</div>";
    exit();
}

$classes  = $conn->query("SELECT id, title FROM classes ORDER BY title");
$subjects = $conn->query("SELECT id, title FROM subjects ORDER BY title");
$teachers = $conn->query("SELECT id, full_name FROM users WHERE role = 'teacher' ORDER BY full_name");

$daysMap = [
        1 => 'Понедельник',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота'
];
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Редактировать урок</h4>
    </div>
    <div class="card-body">
        <form action="pages/schedule/update.php" method="POST">
            <input type="hidden" name="id" value="<?= $item['id'] ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Класс</label>
                    <select name="class_id" class="form-select" required>
                        <?php while ($c = $classes->fetch_assoc()): ?>
                            <option value="<?= $c['id'] ?>" <?= $c['id'] == $item['class_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['title']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">День недели</label>
                    <select name="day_of_week" class="form-select" required>
                        <?php foreach ($daysMap as $num => $name): ?>
                            <option value="<?= $num ?>" <?= $num == $item['day_of_week'] ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Предмет</label>
                    <select name="subject_id" class="form-select" required>
                        <?php while ($s = $subjects->fetch_assoc()): ?>
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $item['subject_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['title']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Учитель</label>
                    <select name="teacher_id" class="form-select" required>
                        <?php while ($t = $teachers->fetch_assoc()): ?>
                            <option value="<?= $t['id'] ?>" <?= $t['id'] == $item['teacher_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['full_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Начало урока</label>
                    <input type="time" name="start_time" class="form-control" value="<?= htmlspecialchars($item['start_time']) ?>" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Конец урока</label>
                    <input type="time" name="end_time" class="form-control" value="<?= htmlspecialchars($item['end_time']) ?>" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Кабинет</label>
                    <input type="text" name="room" class="form-control" value="<?= htmlspecialchars($item['room'] ?? '') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="index.php?page=schedule" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>