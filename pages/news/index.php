<?php
require_once __DIR__ . '/../../includes/conn.php';

$canManage = isAdmin() || isTeacher();

$sql = "SELECT n.*, u.full_name AS author_name 
        FROM news n 
        LEFT JOIN users u ON n.author_id = u.id 
        ORDER BY n.id DESC";
$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Список новостей</h4>
        <?php if ($canManage): ?>
            <a href="index.php?page=newsCreate" class="btn btn-primary btn-sm">+ Добавить новость</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
            <tr>
                <th>Фото</th>
                <th>Заголовок</th>
                <th>Текст</th>
                <th>Автор</th>
                <th>Дата</th>
                <?php if ($canManage): ?>
                    <th>Действия</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="uploads/news/<?= htmlspecialchars($row['image']) ?>" alt="Фото" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <span class="text-muted">Нет фото</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                        <td style="white-space: normal; max-width: 250px;">
                            <?= htmlspecialchars(mb_strimwidth($row['content'], 0, 80, "...")) ?>
                        </td>
                        <td><?= htmlspecialchars($row['author_name'] ?? 'Неизвестен') ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <?php if ($canManage): ?>
                            <td>
                                <a class="btn btn-white btn-sm" href="index.php?page=newsEdit&id=<?= $row['id'] ?>">
                                    <i class="bi-pencil-fill"></i>
                                </a>
                                <a class="btn btn-white btn-sm text-danger" href="pages/news/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Удалить эту новость?')">
                                    <i class="bi-trash"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $canManage ? 6 : 5 ?>" class="text-center">Новостей пока нет</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>