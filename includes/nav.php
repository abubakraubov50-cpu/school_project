<aside class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white">
    <div class="navbar-vertical-container">
        <div class="navbar-vertical-footer-offset">

            <!-- Logo -->
            <a class="navbar-brand" href="index.php" aria-label="Front">
                <img class="navbar-brand-logo" src="assets/svg/logos/logo.svg" alt="Logo" data-hs-theme-appearance="default">
                <img class="navbar-brand-logo" src="assets/svg/logos-light/logo.svg" alt="Logo" data-hs-theme-appearance="dark">
                <img class="navbar-brand-logo-mini" src="assets/svg/logos/logo-short.svg" alt="Logo" data-hs-theme-appearance="default">
                <img class="navbar-brand-logo-mini" src="assets/svg/logos-light/logo-short.svg" alt="Logo" data-hs-theme-appearance="dark">
            </a>
            <!-- End Logo -->

            <!-- Navbar Vertical Toggle -->
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
            </button>
            <!-- End Navbar Vertical Toggle -->

            <!-- Content -->
            <div class="navbar-vertical-content">
                <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">

                    <!-- Главная / Dashboard -->
                    <div class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="bi-house-door nav-icon"></i>
                            <span class="nav-link-title">Главная</span>
                        </a>
                    </div>
                    <!-- End Главная -->

                    <span class="dropdown-header mt-4">Школа</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>

                    <!-- Раздел: Предметы -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuSubjects" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuSubjects" aria-expanded="false" aria-controls="navbarVerticalMenuSubjects">
                            <i class="bi-book nav-icon"></i>
                            <span class="nav-link-title">Предметы</span>
                        </a>

                        <div id="navbarVerticalMenuSubjects" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="index.php?page=subjects">Список предметов</a>
                            <?php if (isAdmin() || isTeacher()): ?>
                                <a class="nav-link" href="index.php?page=subjectCreate">Добавить предмет</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Раздел: Домашние задания -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuHomework" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuHomework" aria-expanded="false" aria-controls="navbarVerticalMenuHomework">
                            <i class="bi-journal-text nav-icon"></i>
                            <span class="nav-link-title">Домашние задания</span>
                        </a>

                        <div id="navbarVerticalMenuHomework" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="index.php?page=homework">Список заданий</a>
                            <?php if (isAdmin() || isTeacher()): ?>
                                <a class="nav-link" href="index.php?page=homeworkCreate">Добавить задание</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Раздел: Новости -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuNews" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuNews" aria-expanded="false" aria-controls="navbarVerticalMenuNews">
                            <i class="bi-newspaper nav-icon"></i>
                            <span class="nav-link-title">Новости</span>
                        </a>

                        <div id="navbarVerticalMenuNews" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="index.php?page=news">Список новостей</a>
                            <?php if (isAdmin() || isTeacher()): ?>
                                <a class="nav-link" href="index.php?page=newsCreate">Добавить новость</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Раздел: Пользователи (Только для Админа) -->
                    <?php if (isAdmin()): ?>
                        <div class="nav-item">
                            <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuUsers" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuUsers" aria-expanded="false" aria-controls="navbarVerticalMenuUsers">
                                <i class="bi-people nav-icon"></i>
                                <span class="nav-link-title">Пользователи</span>
                            </a>

                            <div id="navbarVerticalMenuUsers" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                                <a class="nav-link" href="index.php?page=users">Список пользователей</a>
                                <a class="nav-link" href="index.php?page=userCreate">Добавить пользователя</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Раздел: Предметы учителей -->
                    <?php if (isAdmin() || isTeacher()): ?>
                        <div class="nav-item">
                            <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuTeacherSubjects" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuTeacherSubjects" aria-expanded="false" aria-controls="navbarVerticalMenuTeacherSubjects">
                                <i class="bi-person-badge nav-icon"></i>
                                <span class="nav-link-title">Предметы учителей</span>
                            </a>

                            <div id="navbarVerticalMenuTeacherSubjects" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                                <a class="nav-link" href="index.php?page=teacher_subjects">Список назначений</a>
                                <a class="nav-link" href="index.php?page=teacherSubjectCreate">Назначить предмет</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Раздел: Классы -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuClasses" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuClasses" aria-expanded="false" aria-controls="navbarVerticalMenuClasses">
                            <i class="bi-door-open nav-icon"></i>
                            <span class="nav-link-title">Классы</span>
                        </a>

                        <div id="navbarVerticalMenuClasses" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="index.php?page=classes">Список классов</a>
                            <?php if (isAdmin()): ?>
                                <a class="nav-link" href="index.php?page=classCreate">Добавить класс</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Раздел: Галерея -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuGallery" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuGallery" aria-expanded="false" aria-controls="navbarVerticalMenuGallery">
                            <i class="bi-images nav-icon"></i>
                            <span class="nav-link-title">Галерея</span>
                        </a>

                        <div id="navbarVerticalMenuGallery" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="index.php?page=gallery">Все фотографии</a>
                            <?php if (isAdmin()): ?>
                                <a class="nav-link" href="index.php?page=galleryCreate">Добавить фото</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Раздел: Оценки -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuGrades" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuGrades" aria-expanded="false" aria-controls="navbarVerticalMenuGrades">
                            <i class="bi-star nav-icon"></i>
                            <span class="nav-link-title">Оценки</span>
                        </a>

                        <div id="navbarVerticalMenuGrades" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="index.php?page=grades"><?= isStudent() ? 'Мои оценки' : 'Журнал оценок' ?></a>
                            <?php if (isAdmin() || isTeacher()): ?>
                                <a class="nav-link" href="index.php?page=gradeCreate">Выставить оценку</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Раздел: Расписание -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuSchedule" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuSchedule" aria-expanded="false" aria-controls="navbarVerticalMenuSchedule">
                            <i class="bi-calendar3 nav-icon"></i>
                            <span class="nav-link-title">Расписание</span>
                        </a>

                        <div id="navbarVerticalMenuSchedule" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="index.php?page=schedule">Список уроков</a>
                            <?php if (isAdmin()): ?>
                                <a class="nav-link" href="index.php?page=scheduleCreate">Добавить урок</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Раздел: Информация о школе -->
                    <div class="nav-item">
                        <a class="nav-link <?= ($page ?? '') === 'school_info' ? 'active' : '' ?>" href="index.php?page=school_info">
                            <i class="bi-building nav-icon"></i>
                            <span class="nav-link-title">О школе</span>
                        </a>
                    </div>

                </div>
            </div>
            <!-- End Content -->

            <!-- Footer -->
            <div class="navbar-vertical-footer">
                <ul class="navbar-vertical-footer-list">
                    <li class="navbar-vertical-footer-list-item">
                        <!-- Style Switcher -->
                        <div class="dropdown dropup">
                            <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle" id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-dropdown-animation="">
                                <i class="bi-moon-stars"></i>
                            </button>

                            <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="selectThemeDropdown">
                                <a class="dropdown-item" href="#" data-icon="bi-moon-stars" data-value="auto">
                                    <i class="bi-moon-stars me-2"></i>
                                    <span class="text-truncate" title="Auto (system default)">Auto</span>
                                </a>
                                <a class="dropdown-item" href="#" data-icon="bi-brightness-high" data-value="default">
                                    <i class="bi-brightness-high me-2"></i>
                                    <span class="text-truncate" title="Default (light mode)">Light</span>
                                </a>
                                <a class="dropdown-item active" href="#" data-icon="bi-moon" data-value="dark">
                                    <i class="bi-moon me-2"></i>
                                    <span class="text-truncate" title="Dark">Dark</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Style Switcher -->
                    </li>
                </ul>
            </div>
            <!-- End Footer -->

        </div>
    </div>
</aside>