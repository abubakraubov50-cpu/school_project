<?php
require_once __DIR__ . '/conn.php';

$currentUserName   = $_SESSION['user_name']   ?? 'Пользователь';
$currentUserEmail  = $_SESSION['user_email']  ?? '';
$currentUserRole   = $_SESSION['user_role']   ?? 'student';
$currentUserAvatar = $_SESSION['user_avatar'] ?? '';
$currentUserId     = intval($_SESSION['user_id'] ?? 0);

$initials = mb_strtoupper(mb_substr($currentUserName, 0, 1));

$roleBadges = [
        'admin'   => ['label' => 'Админ',   'class' => 'bg-soft-danger text-danger'],
        'teacher' => ['label' => 'Учитель', 'class' => 'bg-soft-primary text-primary'],
        'student' => ['label' => 'Ученик',  'class' => 'bg-soft-success text-success']
];
$badgeInfo = $roleBadges[$currentUserRole] ?? ['label' => $currentUserRole, 'class' => 'bg-soft-secondary text-secondary'];

// Загружаем последние 4 новости для колокольчика уведомлений
$notifNews = [];
$nRes = $conn->query("SELECT id, title, created_at FROM news ORDER BY id DESC LIMIT 4");
if ($nRes) {
    $notifNews = $nRes->fetch_all(MYSQLI_ASSOC);
}
?>

<!-- ========== HEADER ========== -->
<header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
    <div class="navbar-nav-wrap">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php" aria-label="Школьный портал">
            <img class="navbar-brand-logo" src="assets/svg/logos/logo.svg" alt="Logo" data-hs-theme-appearance="default">
            <img class="navbar-brand-logo" src="assets/svg/logos-light/logo.svg" alt="Logo" data-hs-theme-appearance="dark">
            <img class="navbar-brand-logo-mini" src="assets/svg/logos/logo-short.svg" alt="Logo" data-hs-theme-appearance="default">
            <img class="navbar-brand-logo-mini" src="assets/svg/logos-light/logo-short.svg" alt="Logo" data-hs-theme-appearance="dark">
        </a>
        <!-- End Logo -->

        <div class="navbar-nav-wrap-content-start">
            <!-- Navbar Vertical Toggle -->
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-toggle="tooltip" data-bs-placement="right" title="Свернуть меню"></i>
                <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-toggle="tooltip" data-bs-placement="right" title="Развернуть меню"></i>
            </button>
            <!-- End Navbar Vertical Toggle -->

            <!-- Живой поиск по базе -->
            <div class="position-relative ms-2 d-none d-md-block" style="width: 320px;">
                <div class="input-group input-group-merge input-group-borderless input-group-hover-light navbar-input-group">
                    <div class="input-group-prepend input-group-text">
                        <i class="bi-search"></i>
                    </div>
                    <input type="search" id="headerLiveSearch" class="form-control"
                           placeholder="Поиск ученика, учителя, предмета..." autocomplete="off">
                </div>

                <div id="headerSearchResults" class="dropdown-menu w-100 shadow-lg border-0 mt-2 p-2" style="display: none; max-height: 380px; overflow-y: auto; z-index: 1050;"></div>
            </div>
            <!-- End Search Form -->
        </div>

        <div class="navbar-nav-wrap-content-end">
            <ul class="navbar-nav align-items-center">

                <!-- Бейдж текущей роли -->
                <li class="nav-item d-none d-lg-inline-block me-2">
          <span class="badge <?= $badgeInfo['class'] ?> px-3 py-2 rounded-pill">
            <?= $badgeInfo['label'] ?>
          </span>
                </li>

                <!-- Колокольчик уведомлений (свежие новости школы) -->
                <li class="nav-item">
                    <div class="dropdown">
                        <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle position-relative" id="navbarNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi-bell"></i>
                            <?php if (!empty($notifNews)): ?>
                                <span class="btn-status btn-sm-status btn-status-danger"></span>
                            <?php endif; ?>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="navbarNotificationsDropdown" style="width: 22rem;">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Объявления школы</h4>
                                    <span class="badge bg-soft-primary text-primary"><?= count($notifNews) ?></span>
                                </div>

                                <div class="card-body-height" style="max-height: 260px; overflow-y: auto;">
                                    <ul class="list-group list-group-flush navbar-card-list-group">
                                        <?php if (!empty($notifNews)): ?>
                                            <?php foreach ($notifNews as $item): ?>
                                                <li class="list-group-item">
                                                    <a href="index.php?page=news" class="text-body d-block">
                                                        <div class="d-flex align-items-center">
                              <span class="icon icon-soft-info icon-xs icon-circle me-3">
                                <i class="bi-megaphone"></i>
                              </span>
                                                            <div class="flex-grow-1 text-truncate">
                                                                <h5 class="mb-0 text-truncate"><?= htmlspecialchars($item['title']) ?></h5>
                                                                <small class="text-muted"><?= htmlspecialchars($item['created_at']) ?></small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-group-item text-center text-muted py-4">
                                                Новых объявлений нет
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <a class="card-footer text-center border-top" href="index.php?page=news">
                                    Все новости <i class="bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Быстрые действия (только для Админа и Учителя) -->
                <?php if (isAdmin() || isTeacher()): ?>
                    <li class="nav-item d-none d-sm-inline-block">
                        <div class="dropdown">
                            <button type="button" class="btn btn-icon btn-ghost-secondary rounded-circle" id="navbarAppsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi-app-indicator"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="navbarAppsDropdown" style="width: 22rem;">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Быстрые действия</h4>
                                    </div>
                                    <div class="card-body">
                                        <a class="dropdown-item" href="index.php?page=gradeCreate">
                                            <div class="d-flex align-items-center">
                                                <span class="icon icon-soft-success icon-xs icon-circle"><i class="bi-star"></i></span>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-0">Выставить оценку</h5>
                                                    <p class="card-text text-body fs-6">Добавить запись в журнал</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a class="dropdown-item" href="index.php?page=newsCreate">
                                            <div class="d-flex align-items-center">
                                                <span class="icon icon-soft-info icon-xs icon-circle"><i class="bi-newspaper"></i></span>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-0">Опубликовать новость</h5>
                                                    <p class="card-text text-body fs-6">Объявления для школы</p>
                                                </div>
                                            </div>
                                        </a>

                                        <?php if (isAdmin()): ?>
                                            <a class="dropdown-item" href="index.php?page=scheduleCreate">
                                                <div class="d-flex align-items-center">
                                                    <span class="icon icon-soft-primary icon-xs icon-circle"><i class="bi-calendar3"></i></span>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="mb-0">Добавить урок</h5>
                                                        <p class="card-text text-body fs-6">Заполнить расписание</p>
                                                    </div>
                                                </div>
                                            </a>

                                            <a class="dropdown-item" href="index.php?page=userCreate">
                                                <div class="d-flex align-items-center">
                                                    <span class="icon icon-soft-warning icon-xs icon-circle"><i class="bi-person-plus"></i></span>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="mb-0">Новый пользователь</h5>
                                                        <p class="card-text text-body fs-6">Добавить ученика или учителя</p>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>

                <!-- Профиль пользователя -->
                <li class="nav-item">
                    <div class="dropdown">
                        <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <?php if (!empty($currentUserAvatar) && file_exists("uploads/avatars/" . $currentUserAvatar)): ?>
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="uploads/avatars/<?= htmlspecialchars($currentUserAvatar) ?>" alt="Аватар">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            <?php else: ?>
                                <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                                    <span class="avatar-initials"><?= htmlspecialchars($initials) ?></span>
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            <?php endif; ?>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account" aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
                            <div class="dropdown-item-text">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                                        <?php if (!empty($currentUserAvatar) && file_exists("uploads/avatars/" . $currentUserAvatar)): ?>
                                            <img class="avatar-img" src="uploads/avatars/<?= htmlspecialchars($currentUserAvatar) ?>" alt="Аватар">
                                        <?php else: ?>
                                            <span class="avatar-initials"><?= htmlspecialchars($initials) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1 ms-3 text-truncate">
                                        <h5 class="mb-0 text-truncate"><?= htmlspecialchars($currentUserName) ?></h5>
                                        <p class="card-text text-body text-truncate mb-0"><?= htmlspecialchars($currentUserEmail) ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="index.php?page=profile">
                                <i class="bi-person dropdown-item-icon"></i> Мой профиль
                            </a>

                            <?php if (isAdmin()): ?>
                                <a class="dropdown-item" href="index.php?page=school_info">
                                    <i class="bi-building dropdown-item-icon"></i> Настройки школы
                                </a>
                            <?php endif; ?>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item text-danger" href="logout.php">
                                <i class="bi-box-arrow-right dropdown-item-icon"></i> Выйти
                            </a>
                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('headerLiveSearch');
        const resultsBox = document.getElementById('headerSearchResults');
        let timer = null;

        if (input && resultsBox) {
            input.addEventListener('input', function () {
                clearTimeout(timer);
                const query = this.value.trim();

                if (query.length < 2) {
                    resultsBox.style.display = 'none';
                    resultsBox.innerHTML = '';
                    return;
                }

                timer = setTimeout(() => {
                    fetch('includes/ajax_search.php?q=' + encodeURIComponent(query))
                        .then(res => res.text())
                        .then(html => {
                            resultsBox.innerHTML = html;
                            resultsBox.style.display = 'block';
                        });
                }, 250);
            });

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.style.display = 'none';
                }
            });
        }
    });
</script>
<!-- ========== END HEADER ========== -->