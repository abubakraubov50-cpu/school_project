<?php
require_once __DIR__ . '/../../includes/conn.php';

$sql = "SELECT u.*, c.title AS class_title 
        FROM users u 
        LEFT JOIN classes c ON u.class_id = c.id 
        ORDER BY u.id DESC";
$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Список пользователей</h4>
        <a href="index.php?page=userCreate" class="btn btn-primary btn-sm">+ Добавить пользователя</a>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
            <tr>
                <th>Аватар</th>
                <th>ФИО</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Класс</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row['avatar'])): ?>
                                <img src="uploads/avatars/<?= htmlspecialchars($row['avatar']) ?>" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <span class="badge bg-soft-secondary text-secondary">Нет</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($row['full_name']) ?></strong></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <?php if ($row['role'] == 'admin'): ?>
                                <span class="badge bg-danger">Админ</span>
                            <?php elseif ($row['role'] == 'teacher'): ?>
                                <span class="badge bg-info">Учитель</span>
                            <?php else: ?>
                                <span class="badge bg-success">Ученик</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['class_title'] ?? '—') ?></td>
                        <td>
                            <!-- Изменить -->
                            <a class="btn btn-white btn-sm" href="index.php?page=userEdit&id=<?= $row['id'] ?>" title="Изменить">
                                <i class="bi-pencil-fill"></i>
                            </a>

                            <!-- Добавить оценку (только для учеников) -->
                            <?php if ($row['role'] == 'student'): ?>
                                <a class="btn btn-white btn-sm text-primary" href="index.php?page=gradeCreate&student_id=<?= $row['id'] ?>" title="Добавить оценку">
                                    <i class="bi-journal-plus"></i>
                                </a>
                            <?php endif; ?>

                            <!-- Удалить -->
                            <a class="btn btn-white btn-sm text-danger" href="pages/users/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Удалить пользователя?')" title="Удалить">
                                <i class="bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Пользователей пока нет</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>