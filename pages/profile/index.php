<?php
require_once __DIR__ . '/../../includes/conn.php';

$userId = intval($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    echo "<div class='alert alert-danger'>Пользователь не авторизован!</div>";
    exit();
}

$res = $conn->query("SELECT u.*, c.title AS class_title 
                     FROM users u 
                     LEFT JOIN classes c ON u.class_id = c.id 
                     WHERE u.id = $userId LIMIT 1");
$user = $res ? $res->fetch_assoc() : null;

if (!$user) {
    echo "<div class='alert alert-danger'>Профиль не найден!</div>";
    exit();
}

$rolesMap = [
    'admin'   => ['label' => 'Администратор', 'badge' => 'bg-soft-danger text-danger'],
    'teacher' => ['label' => 'Учитель',       'badge' => 'bg-soft-primary text-primary'],
    'student' => ['label' => 'Ученик',        'badge' => 'bg-soft-success text-success']
];
$roleInfo = $rolesMap[$user['role']] ?? ['label' => $user['role'], 'badge' => 'bg-soft-secondary text-secondary'];
$initials = mb_strtoupper(mb_substr($user['full_name'], 0, 1));
$hasAvatar = !empty($user['avatar']) && file_exists(__DIR__ . '/../../uploads/avatars/' . $user['avatar']);
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Готово!</strong> <?= htmlspecialchars($_GET['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Ошибка!</strong> <?= htmlspecialchars($_GET['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Левая колонка: Карточка профиля -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100 overflow-hidden">
            <!-- Градиентная шапка карточки -->
            <div style="height: 110px; background: linear-gradient(135deg, #377dff 0%, #00c9a7 100%);"></div>

            <div class="card-body text-center" style="margin-top: -55px;">
                <!-- Аватар -->
                <div class="mx-auto mb-3 position-relative d-inline-block">
                    <?php if ($hasAvatar): ?>
                        <img src="uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>"
                             alt="Аватар"
                             class="rounded-circle border border-4 border-white shadow-sm"
                             style="width: 110px; height: 110px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle border border-4 border-white shadow-sm d-flex align-items-center justify-content-center bg-soft-primary text-primary fw-bold mx-auto"
                             style="width: 110px; height: 110px; font-size: 2.5rem;">
                            <?= htmlspecialchars($initials) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h3 class="mb-1"><?= htmlspecialchars($user['full_name']) ?></h3>
                <div class="mb-3">
                    <span class="badge <?= $roleInfo['badge'] ?> px-3 py-2"><?= $roleInfo['label'] ?></span>
                    <?php if (!empty($user['class_title'])): ?>
                        <span class="badge bg-soft-info text-info px-3 py-2 ms-1">Класс: <?= htmlspecialchars($user['class_title']) ?></span>
                    <?php endif; ?>
                </div>

                <p class="text-muted small mb-4">
                    <i class="bi-envelope me-1"></i> <?= htmlspecialchars($user['email']) ?>
                </p>

                <hr class="my-3">

                <!-- Стильная загрузка нового фото -->
                <form action="pages/profile/update.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="avatar">
                    <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" required>

                    <label for="avatarInput" id="avatarDropZone"
                           class="d-flex align-items-center justify-content-center gap-2 border rounded-3 py-2 px-3 mb-2 text-primary bg-soft-primary"
                           style="cursor: pointer; border-style: dashed !important; transition: 0.2s;">
                        <i class="bi-camera-fill fs-5"></i>
                        <span id="avatarFileName" class="small fw-semibold text-truncate">Выбрать новое фото</span>
                    </label>

                    <button type="submit" id="avatarSubmitBtn" class="btn btn-primary btn-sm w-100 d-none">
                        <i class="bi-cloud-arrow-up me-1"></i> Сохранить фото
                    </button>

                </form>
            </div>
        </div>
    </div>

    <!-- Правая колонка: Настройки данных и пароля -->
    <div class="col-lg-8">
        <!-- Основная информация -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-header-title"><i class="bi-person-lines-fill me-2 text-primary"></i>Личные данные</h4>
            </div>
            <div class="card-body">
                <form action="pages/profile/update.php" method="POST">
                    <input type="hidden" name="action" value="info">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ФИО</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email (логин для входа)</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Роль в системе</label>
                            <input type="text" class="form-control bg-light" value="<?= $roleInfo['label'] ?>" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Дата регистрации</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['created_at'] ?? '—') ?>" disabled>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Смена пароля -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title"><i class="bi-shield-lock me-2 text-warning"></i>Безопасность и смена пароля</h4>
            </div>
            <div class="card-body">
                <form action="pages/profile/update.php" method="POST">
                    <input type="hidden" name="action" value="password">

                    <div class="mb-3">
                        <label class="form-label">Текущий пароль</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Введите текущий пароль" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Новый пароль</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Минимум 6 символов" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Повторите новый пароль</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Повторите новый пароль" required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-warning">Обновить пароль</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('avatarInput').addEventListener('change', function () {
        const fileNameSpan = document.getElementById('avatarFileName');
        const submitBtn = document.getElementById('avatarSubmitBtn');
        const dropZone = document.getElementById('avatarDropZone');

        if (this.files && this.files.length > 0) {
            fileNameSpan.textContent = this.files[0].name;
            dropZone.classList.remove('bg-soft-primary', 'text-primary');
            dropZone.classList.add('bg-soft-success', 'text-success', 'border-success');
            submitBtn.classList.remove('d-none');
        }
    });
</script>