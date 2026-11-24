<?php
require_once __DIR__ . '/../../includes/conn.php';

$sql = "SELECT sch.*, 
               c.title AS class_title, 
               s.title AS subject_title, 
               t.full_name AS teacher_name
        FROM schedule sch
        LEFT JOIN classes c ON sch.class_id = c.id
        LEFT JOIN subjects s ON sch.subject_id = s.id
        LEFT JOIN users t ON sch.teacher_id = t.id
        ORDER BY c.title ASC, sch.day_of_week ASC, sch.start_time ASC";

$result = $conn->query($sql);

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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Расписание уроков</h4>
        <?php if (isAdmin()): ?>
            <a href="index.php?page=scheduleCreate" class="btn btn-primary btn-sm">+ Добавить урок</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
            <tr>
                <th>Класс</th>
                <th>День недели</th>
                <th>Время</th>
                <th>Предмет</th>
                <th>Учитель</th>
                <th>Кабинет</th>
                <?php if (isAdmin()): ?>
                    <th>Действия</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['class_title'] ?? '—') ?></strong></td>
                        <td><?= $daysMap[$row['day_of_week']] ?? htmlspecialchars($row['day_of_week']) ?></td>
                        <td><?= htmlspecialchars(substr($row['start_time'], 0, 5)) ?> – <?= htmlspecialchars(substr($row['end_time'], 0, 5)) ?></td>
                        <td><?= htmlspecialchars($row['subject_title'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['teacher_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['room'] ?? '—') ?></td>
                        <?php if (isAdmin()): ?>
                            <td>
                                <a class="btn btn-white btn-sm" href="index.php?page=scheduleEdit&id=<?= $row['id'] ?>">
                                    <i class="bi-pencil-fill"></i>
                                </a>
                                <a class="btn btn-white btn-sm text-danger" href="pages/schedule/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Удалить этот урок из расписания?')">
                                    <i class="bi-trash"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= isAdmin() ? 7 : 6 ?>" class="text-center">Расписание пока не заполнено</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>