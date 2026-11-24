<?php
require_once __DIR__ . '/../../includes/conn.php';

$canManage = isAdmin() || isTeacher();

$whereSql = "";
if (isStudent()) {
    $studentId = intval($_SESSION['user_id'] ?? 0);
    $whereSql = "WHERE g.student_id = $studentId";
}

$sql = "SELECT g.*, 
               st.full_name AS student_name, 
               s.title AS subject_title, 
               t.full_name AS teacher_name
        FROM grades g
        LEFT JOIN users st ON g.student_id = st.id
        LEFT JOIN subjects s ON g.subject_id = s.id
        LEFT JOIN users t ON g.teacher_id = t.id
        $whereSql
        ORDER BY g.grade_date DESC, g.id DESC";

$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title"><?= isStudent() ? 'Мои оценки' : 'Журнал оценок' ?></h4>
        <?php if ($canManage): ?>
            <a href="index.php?page=gradeCreate" class="btn btn-primary btn-sm">+ Выставить оценку</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
            <tr>
                <th>Ученик</th>
                <th>Предмет</th>
                <th>Оценка</th>
                <th>Учитель</th>
                <th>Дата</th>
                <th>Комментарий</th>
                <?php if ($canManage): ?>
                    <th>Действия</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['student_name'] ?? '—') ?></strong></td>
                        <td><?= htmlspecialchars($row['subject_title'] ?? '—') ?></td>
                        <td>
                            <?php
                            $gVal = intval($row['grade']);
                            $badgeClass = 'bg-primary';
                            if ($gVal >= 5) $badgeClass = 'bg-success';
                            elseif ($gVal == 4) $badgeClass = 'bg-info';
                            elseif ($gVal == 3) $badgeClass = 'bg-warning';
                            elseif ($gVal <= 2) $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeClass ?> fs-6"><?= htmlspecialchars($row['grade']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($row['teacher_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['grade_date']) ?></td>
                        <td style="white-space: normal; max-width: 200px;"><?= htmlspecialchars($row['COMMENT'] ?? '') ?></td>
                        <?php if ($canManage): ?>
                            <td>
                                <a class="btn btn-white btn-sm" href="index.php?page=gradeEdit&id=<?= $row['id'] ?>">
                                    <i class="bi-pencil-fill"></i>
                                </a>
                                <a class="btn btn-white btn-sm text-danger" href="pages/grades/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Удалить эту оценку?')">
                                    <i class="bi-trash"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $canManage ? 7 : 6 ?>" class="text-center">Оценок пока нет</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>