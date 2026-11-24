<?php
require_once __DIR__ . '/../../includes/conn.php';

$result = $conn->query("SELECT * FROM school_info ORDER BY id DESC LIMIT 1");
$info = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
?>

<div class="card">
    <!-- Шапка карточки -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <span class="avatar avatar-sm avatar-soft-primary avatar-circle me-3">
                <span class="avatar-initials"><i class="bi-building"></i></span>
            </span>
            <h4 class="card-header-title mb-0">Информация о школе</h4>
        </div>

        <?php if (isAdmin()): ?>
            <?php if ($info): ?>
                <a href="index.php?page=schoolInfoEdit&id=<?= $info['id'] ?>" class="btn btn-primary btn-sm">
                    <i class="bi-pencil-fill me-1"></i> Изменить
                </a>
            <?php else: ?>
                <a href="index.php?page=schoolInfoCreate" class="btn btn-primary btn-sm">
                    <i class="bi-plus-lg me-1"></i> Заполнить информацию
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <!-- Конец шапки -->

    <!-- Тело карточки -->
    <div class="card-body">
        <?php if ($info): ?>
            <div class="row g-4">
                <!-- Блок описания -->
                <div class="col-lg-7">
                    <h5 class="text-cap text-muted mb-2">О школе</h5>
                    <div class="p-3 bg-light rounded-2">
                        <p class="text-dark mb-0" style="line-height: 1.6;">
                            <?= !empty($info['description']) ? nl2br(htmlspecialchars($info['description'])) : 'Описание пока не добавлено.' ?>
                        </p>
                    </div>
                </div>

                <!-- Блок контактов -->
                <div class="col-lg-5">
                    <h5 class="text-cap text-muted mb-2">Контактная информация</h5>
                    <ul class="list-group list-group-flush list-group-no-gutters">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-xs avatar-soft-secondary avatar-circle me-3">
                                    <span class="avatar-initials"><i class="bi-geo-alt"></i></span>
                                </span>
                                <div>
                                    <small class="d-block text-muted">Адрес</small>
                                    <span class="text-dark fw-semibold"><?= htmlspecialchars($info['address'] ?? '—') ?></span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-xs avatar-soft-secondary avatar-circle me-3">
                                    <span class="avatar-initials"><i class="bi-telephone"></i></span>
                                </span>
                                <div>
                                    <small class="d-block text-muted">Телефон</small>
                                    <span class="text-dark fw-semibold"><?= htmlspecialchars($info['phone'] ?? '—') ?></span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-xs avatar-soft-secondary avatar-circle me-3">
                                    <span class="avatar-initials"><i class="bi-envelope"></i></span>
                                </span>
                                <div>
                                    <small class="d-block text-muted">Email</small>
                                    <span class="text-dark fw-semibold"><?= htmlspecialchars($info['email'] ?? '—') ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi-building fs-1 text-muted d-block mb-2"></i>
                <p class="text-muted mb-0">Информация о школе пока не заполнена</p>
            </div>
        <?php endif; ?>
    </div>
    <!-- Конец тела карточки -->
</div>