<?php
require_once __DIR__ . '/../../includes/conn.php';

$classes  = $conn->query("SELECT id, title FROM classes ORDER BY title");
$subjects = $conn->query("SELECT id, title FROM subjects ORDER BY title");
$teachers = $conn->query("SELECT id, full_name FROM users WHERE role = 'teacher' ORDER BY full_name");
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Добавить урок в расписание</h4>
    </div>
    <div class="card-body">
        <form action="pages/schedule/insert.php" method="POST">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Класс</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">Выберите класс...</option>
                        <?php while ($c = $classes->fetch_assoc()): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">День недели</label>
                    <select name="day_of_week" class="form-select" required>
                        <option value="1">Понедельник</option>
                        <option value="2">Вторник</option>
                        <option value="3">Среда</option>
                        <option value="4">Четверг</option>
                        <option value="5">Пятница</option>
                        <option value="6">Суббота</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Предмет</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">Выберите предмет...</option>
                        <?php while ($s = $subjects->fetch_assoc()): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['title']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Учитель</label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">Выберите учителя...</option>
                        <?php while ($t = $teachers->fetch_assoc()): ?>
                            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['full_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Начало урока</label>
                    <input type="time" name="start_time" class="form-control" value="08:00" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Конец урока</label>
                    <input type="time" name="end_time" class="form-control" value="08:45" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Кабинет</label>
                    <input type="text" name="room" class="form-control" placeholder="Например: 204" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить урок</button>
            <a href="index.php?page=schedule" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>