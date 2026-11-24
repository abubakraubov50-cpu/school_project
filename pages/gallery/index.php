<?php
require_once __DIR__ . '/../../includes/conn.php';

$sql = "SELECT g.*, u.full_name AS uploader_name 
        FROM gallery g 
        LEFT JOIN users u ON g.uploaded_by = u.id 
        ORDER BY g.id DESC";
$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Школьная галерея</h4>
        <?php if (isAdmin()): ?>
            <a href="index.php?page=galleryCreate" class="btn btn-primary btn-sm">+ Добавить фото</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
            <tr>
                <th>Фото</th>
                <th>Название / Описание</th>
                <th>Загрузил</th>
                <th>Дата</th>
                <?php if (isAdmin()): ?>
                    <th>Действия</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row['image_path'])): ?>
                                <img src="uploads/gallery/<?= htmlspecialchars($row['image_path']) ?>" alt="Фото" style="width: 80px; height: 55px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <span class="text-muted">Нет фото</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                        <td><?= htmlspecialchars($row['uploader_name'] ?? 'Неизвестен') ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <?php if (isAdmin()): ?>
                            <td>
                                <a class="btn btn-white btn-sm" href="index.php?page=galleryEdit&id=<?= $row['id'] ?>">
                                    <i class="bi-pencil-fill"></i>
                                </a>
                                <a class="btn btn-white btn-sm text-danger" href="pages/gallery/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Удалить это фото из галереи?')">
                                    <i class="bi-trash"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= isAdmin() ? 5 : 4 ?>" class="text-center">В галерее пока нет фотографий</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>