<?php
require_once __DIR__ . '/../../includes/conn.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: index.php?page=school_info");
    exit();
}

$res = $conn->query("SELECT * FROM school_info WHERE id = $id");
$item = $res ? $res->fetch_assoc() : null;

if (!$item) {
    echo "<div class='alert alert-danger'>Запись не найдена!</div>";
    exit();
}
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Редактировать информацию о школе</h4>
    </div>
    <div class="card-body">
        <form action="pages/school_info/update.php" method="POST">
            <input type="hidden" name="id" value="<?= $item['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Описание школы</label>
                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Адрес</label>
                <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($item['address'] ?? '') ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Телефон</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($item['phone'] ?? '') ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($item['email'] ?? '') ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="index.php?page=school_info" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>
