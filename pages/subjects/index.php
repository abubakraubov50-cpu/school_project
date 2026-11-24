<?php
require_once __DIR__ . '/../../includes/conn.php';

$canManage = isAdmin() || isTeacher();
$subjects = [];
$errorMsg = null;

$result = $conn->query("SELECT * FROM subjects ORDER BY id DESC");

if ($result) {
    $subjects = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $errorMsg = $conn->error;
}
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Список предметов</h4>
        <?php if ($canManage): ?>
            <a class="btn btn-primary btn-sm" href="index.php?page=subjectCreate">
                <i class="bi-plus-lg me-1"></i> Добавить предмет
            </a>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <?php if ($errorMsg): ?>
            <div class="alert alert-danger">
                <strong>Ошибка базы данных:</strong> <?= htmlspecialchars($errorMsg) ?>
            </div>
        <?php elseif (empty($subjects)): ?>
            <div class="text-center p-4">
                <p class="text-muted mb-2">Список предметов пока пуст</p>
                <?php if ($canManage): ?>
                    <a class="btn btn-sm btn-primary" href="index.php?page=subjectCreate">Создать первый предмет</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>Название предмета</th>
                        <?php if ($canManage): ?>
                            <th class="text-end">Действия</th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td>
                                <span class="text-dark fw-semibold"><?= htmlspecialchars($subject['title']) ?></span>
                            </td>
                            <?php if ($canManage): ?>
                                <td class="text-end">
                                    <a href="index.php?page=subjectEdit&id=<?= $subject['id'] ?>" class="btn btn-white btn-sm" title="Редактировать">
                                        <i class="bi-pencil"></i>
                                    </a>
                                    <a href="pages/subjects/delete.php?id=<?= $subject['id'] ?>" class="btn btn-white btn-sm text-danger" title="Удалить" onclick="return confirm('Точно удалить этот предмет?');">
                                        <i class="bi-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>