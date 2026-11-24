<?php
require_once __DIR__ . '/../../includes/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: index.php?page=classes");
    exit();
}

$res = $conn->query("SELECT * FROM classes WHERE id = $id");
$classItem = $res ? $res->fetch_assoc() : null;

if (!$classItem) {
    echo "<div class='alert alert-danger'>Класс не найден! <a href='index.php?page=classes'>Назад</a></div>";
    exit();
}
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Редактировать класс</h4>
    </div>
    <div class="card-body">
        <form action="pages/classes/update.php" method="POST">
            <input type="hidden" name="id" value="<?= $classItem['id'] ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Название класса</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($classItem['title']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="index.php?page=classes" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>
