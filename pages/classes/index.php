<?php
require_once __DIR__ . '/../../includes/conn.php';

$result = $conn->query("SELECT * FROM classes ORDER BY title ASC");
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Список классов</h4>
        <?php if (isAdmin()): ?>
            <a href="index.php?page=classCreate" class="btn btn-primary btn-sm">+ Добавить класс</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
            <tr>
                <th>Название класса</th>
                <?php if (isAdmin()): ?>
                    <th>Действия</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                        <?php if (isAdmin()): ?>
                            <td>
                                <a class="btn btn-white btn-sm" href="index.php?page=classEdit&id=<?= $row['id'] ?>">
                                    <i class="bi-pencil-fill"></i>
                                </a>
                                <a class="btn btn-white btn-sm text-danger" href="pages/classes/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Удалить этот класс?')">
                                    <i class="bi-trash"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= isAdmin() ? 2 : 1 ?>" class="text-center">Классов пока нет</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>