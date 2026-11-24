<?php
require_once __DIR__ . '/../../includes/conn.php';

$canManage = isAdmin() || isTeacher();

$sql = "SELECT u.id AS teacher_id, 
               u.full_name AS teacher_name, 
               GROUP_CONCAT(s.title SEPARATOR ', ') AS subjects_list
        FROM teacher_subjects ts
        INNER JOIN users u ON ts.teacher_id = u.id
        INNER JOIN subjects s ON ts.subject_id = s.id
        GROUP BY u.id, u.full_name
        ORDER BY u.full_name ASC";

$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Предметы учителей</h4>
        <?php if ($canManage): ?>
            <a href="index.php?page=teacherSubjectCreate" class="btn btn-primary btn-sm">+ Назначить предметы</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
            <tr>
                <th>Учитель</th>
                <th>Предметы</th>
                <?php if ($canManage): ?>
                    <th>Действия</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['teacher_name']) ?></strong></td>
                        <td style="white-space: normal;">
                            <?php
                            $subjectsArr = explode(', ', $row['subjects_list']);
                            foreach ($subjectsArr as $sub):
                                ?>
                                <span class="badge bg-soft-primary text-primary me-1 mb-1"><?= htmlspecialchars($sub) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <?php if ($canManage): ?>
                            <td>
                                <a class="btn btn-white btn-sm" href="index.php?page=teacherSubjectEdit&teacher_id=<?= $row['teacher_id'] ?>">
                                    <i class="bi-pencil-fill"></i>
                                </a>
                                <a class="btn btn-white btn-sm text-danger" href="pages/teacher_subjects/delete.php?teacher_id=<?= $row['teacher_id'] ?>" onclick="return confirm('Удалить все предметы у этого учителя?')">
                                    <i class="bi-trash"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $canManage ? 3 : 2 ?>" class="text-center">Назначений пока нет</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>