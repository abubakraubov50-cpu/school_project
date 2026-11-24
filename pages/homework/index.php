<?php
require_once __DIR__ . '/../../includes/conn.php';

$canManage = isAdmin() || isTeacher();

$whereSql = "";
if (isStudent()) {
    $studentId = intval($_SESSION['user_id'] ?? 0);
    $stRes = $conn->query("SELECT class_id FROM users WHERE id = $studentId LIMIT 1");
    $stRow = $stRes ? $stRes->fetch_assoc() : null;
    if (!empty($stRow['class_id'])) {
        $classId = intval($stRow['class_id']);
        $whereSql = "WHERE h.class_id = $classId";
    }
}

$sql = "SELECT h.*, 
               s.title AS subject_title, 
               c.title AS class_title, 
               u.full_name AS teacher_name
        FROM homework h
        LEFT JOIN subjects s ON h.subject_id = s.id
        LEFT JOIN classes c ON h.class_id = c.id
        LEFT JOIN users u ON h.teacher_id = u.id
        $whereSql
        ORDER BY h.id DESC";

$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Список домашних заданий</h4>
        <?php if ($canManage): ?>
            <a href="index.php?page=homeworkCreate" class="btn btn-primary btn-sm">+ Добавить задание</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
            <tr>
                <th>Предмет</th>
                <th>Класс</th>
                <th>Учитель</th>
                <th>Задание</th>
                <th>Срок сдачи</th>
                <?php if ($canManage): ?>
                    <th>Действия</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['subject_title'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['class_title'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['teacher_name'] ?? '—') ?></td>
                        <td style="white-space: normal; max-width: 250px;"><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['due_date']) ?></td>
                        <?php if ($canManage): ?>
                            <td>
                                <a class="btn btn-white btn-sm" href="index.php?page=homeworkEdit&id=<?= $row['id'] ?>">
                                    <i class="bi-pencil-fill"></i>
                                </a>
                                <a class="btn btn-white btn-sm text-danger" href="pages/homework/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Удалить это задание?')">
                                    <i class="bi-trash"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $canManage ? 6 : 5 ?>" class="text-center">Домашних заданий пока нет</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>