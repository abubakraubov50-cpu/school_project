<?php
require_once __DIR__ . '/../includes/conn.php';

$userId   = intval($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['user_name'] ?? 'Пользователь';

// Общие последние новости для всех ролей
$newsList = [];
$nRes = $conn->query("SELECT n.title, n.content, n.created_at, u.full_name AS author 
                      FROM news n 
                      LEFT JOIN users u ON n.author_id = u.id 
                      ORDER BY n.id DESC LIMIT 3");
if ($nRes) {
    $newsList = $nRes->fetch_all(MYSQLI_ASSOC);
}
?>

<!-- Приветственный баннер -->
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-header-title">Добро пожаловать, <?= htmlspecialchars($userName) ?>!</h1>
            <p class="page-header-text text-muted mb-0">
                <?php if (isAdmin()): ?>
                    Панель управления школой • Полный контроль системы
                <?php elseif (isTeacher()): ?>
                    Кабинет преподавателя • Журнал оценок и домашние задания
                <?php else: ?>
                    Личный кабинет ученика • Успеваемость и задания
                <?php endif; ?>
            </p>
        </div>
        <div class="col-auto">
            <?php if (isAdmin() || isTeacher()): ?>
                <a href="index.php?page=gradeCreate" class="btn btn-primary btn-sm">
                    <i class="bi-star me-1"></i> Выставить оценку
                </a>
            <?php else: ?>
                <a href="index.php?page=grades" class="btn btn-primary btn-sm">
                    <i class="bi-star me-1"></i> Все мои оценки
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (isAdmin()): ?>
    <?php
    // Статистика для Админа
    $studentsCount = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role = 'student'")->fetch_assoc()['c'] ?? 0;
    $teachersCount = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role = 'teacher'")->fetch_assoc()['c'] ?? 0;
    $classesCount  = $conn->query("SELECT COUNT(*) AS c FROM classes")->fetch_assoc()['c'] ?? 0;
    $subjectsCount = $conn->query("SELECT COUNT(*) AS c FROM subjects")->fetch_assoc()['c'] ?? 0;

    $recentGrades = $conn->query("SELECT g.grade, g.grade_date, st.full_name AS student_name, s.title AS subject_title
                                  FROM grades g
                                  LEFT JOIN users st ON g.student_id = st.id
                                  LEFT JOIN subjects s ON g.subject_id = s.id
                                  ORDER BY g.id DESC LIMIT 5");
    ?>

    <!-- 4 карточки статистики Админа -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-hover-shadow h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Учеников</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= $studentsCount ?></span>
                        <span class="icon icon-soft-success icon-sm icon-circle"><i class="bi-people"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-hover-shadow h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Учителей</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= $teachersCount ?></span>
                        <span class="icon icon-soft-primary icon-sm icon-circle"><i class="bi-person-badge"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-hover-shadow h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Классов</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= $classesCount ?></span>
                        <span class="icon icon-soft-warning icon-sm icon-circle"><i class="bi-door-open"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-hover-shadow h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Предметов</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= $subjectsCount ?></span>
                        <span class="icon icon-soft-info icon-sm icon-circle"><i class="bi-book"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Таблица последних оценок по школе -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-header-title">Последние оценки по школе</h4>
            <a href="index.php?page=grades" class="btn btn-white btn-sm">Весь журнал</a>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-align-middle card-table">
                <thead class="thead-light">
                <tr>
                    <th>Ученик</th>
                    <th>Предмет</th>
                    <th>Оценка</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($recentGrades && $recentGrades->num_rows > 0): ?>
                    <?php while ($g = $recentGrades->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($g['student_name'] ?? '—') ?></strong></td>
                            <td><?= htmlspecialchars($g['subject_title'] ?? '—') ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($g['grade']) ?></span></td>
                            <td><?= htmlspecialchars($g['grade_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted">Оценок пока нет</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif (isTeacher()): ?>
    <?php
    // Статистика для Учителя
    $myGradesCount = $conn->query("SELECT COUNT(*) AS c FROM grades WHERE teacher_id = $userId")->fetch_assoc()['c'] ?? 0;
    $myHwCount     = $conn->query("SELECT COUNT(*) AS c FROM homework WHERE teacher_id = $userId")->fetch_assoc()['c'] ?? 0;
    $mySubjCount   = $conn->query("SELECT COUNT(*) AS c FROM teacher_subjects WHERE teacher_id = $userId")->fetch_assoc()['c'] ?? 0;

    $teacherGrades = $conn->query("SELECT g.grade, g.grade_date, st.full_name AS student_name, s.title AS subject_title
                                   FROM grades g
                                   LEFT JOIN users st ON g.student_id = st.id
                                   LEFT JOIN subjects s ON g.subject_id = s.id
                                   WHERE g.teacher_id = $userId
                                   ORDER BY g.id DESC LIMIT 5");
    ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Выставлено оценок</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= $myGradesCount ?></span>
                        <span class="icon icon-soft-success icon-sm icon-circle"><i class="bi-star"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Задано домашних работ</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= $myHwCount ?></span>
                        <span class="icon icon-soft-warning icon-sm icon-circle"><i class="bi-journal-text"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Моих предметов</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= $mySubjCount ?></span>
                        <span class="icon icon-soft-primary icon-sm icon-circle"><i class="bi-book"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-header-title">Последние выставленные вами оценки</h4>
            <a href="index.php?page=grades" class="btn btn-white btn-sm">Открыть журнал</a>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-align-middle card-table">
                <thead class="thead-light">
                <tr>
                    <th>Ученик</th>
                    <th>Предмет</th>
                    <th>Оценка</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($teacherGrades && $teacherGrades->num_rows > 0): ?>
                    <?php while ($g = $teacherGrades->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($g['student_name'] ?? '—') ?></strong></td>
                            <td><?= htmlspecialchars($g['subject_title'] ?? '—') ?></td>
                            <td><span class="badge bg-success"><?= htmlspecialchars($g['grade']) ?></span></td>
                            <td><?= htmlspecialchars($g['grade_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted">Вы ещё не выставляли оценки</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <?php
    // Статистика для Ученика
    $stStats = $conn->query("SELECT COUNT(*) AS cnt, ROUND(AVG(grade), 2) AS avg_g FROM grades WHERE student_id = $userId")->fetch_assoc();
    $gradesTotal = $stStats['cnt'] ?? 0;
    $avgGrade    = $stStats['avg_g'] ?? '—';

    $stInfo = $conn->query("SELECT u.class_id, c.title AS class_title 
                            FROM users u 
                            LEFT JOIN classes c ON u.class_id = c.id 
                            WHERE u.id = $userId LIMIT 1")->fetch_assoc();
    $myClassId    = intval($stInfo['class_id'] ?? 0);
    $myClassTitle = $stInfo['class_title'] ?? 'Не указан';

    $hwWhere = $myClassId > 0 ? "WHERE h.class_id = $myClassId" : "";
    $myHwList = $conn->query("SELECT h.description, h.due_date, s.title AS subject_title
                              FROM homework h
                              LEFT JOIN subjects s ON h.subject_id = s.id
                              $hwWhere
                              ORDER BY h.id DESC LIMIT 4");

    $myRecentGrades = $conn->query("SELECT g.grade, g.grade_date, s.title AS subject_title
                                    FROM grades g
                                    LEFT JOIN subjects s ON g.subject_id = s.id
                                    WHERE g.student_id = $userId
                                    ORDER BY g.id DESC LIMIT 5");
    ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Средний балл</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-primary"><?= $avgGrade ?></span>
                        <span class="icon icon-soft-primary icon-sm icon-circle"><i class="bi-graph-up"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Всего оценок</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= $gradesTotal ?></span>
                        <span class="icon icon-soft-success icon-sm icon-circle"><i class="bi-star"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted mb-2">Мой класс</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-6 fw-bold text-dark"><?= htmlspecialchars($myClassTitle) ?></span>
                        <span class="icon icon-soft-info icon-sm icon-circle"><i class="bi-door-open"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Последние оценки ученика -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-header-title">Последние оценки</h4>
                    <a href="index.php?page=grades" class="btn btn-white btn-sm">Все</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            <th>Предмет</th>
                            <th>Оценка</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($myRecentGrades && $myRecentGrades->num_rows > 0): ?>
                            <?php while ($g = $myRecentGrades->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($g['subject_title'] ?? '—') ?></td>
                                    <td><span class="badge bg-success"><?= htmlspecialchars($g['grade']) ?></span></td>
                                    <td><?= htmlspecialchars($g['grade_date']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted">Оценок пока нет</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Актуальные домашние задания -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-header-title">Домашние задания</h4>
                    <a href="index.php?page=homework" class="btn btn-white btn-sm">Все</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            <th>Предмет</th>
                            <th>Задание</th>
                            <th>Срок</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($myHwList && $myHwList->num_rows > 0): ?>
                            <?php while ($hw = $myHwList->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($hw['subject_title'] ?? '—') ?></strong></td>
                                    <td style="white-space: normal;"><?= htmlspecialchars(mb_strimwidth($hw['description'], 0, 50, '...')) ?></td>
                                    <td><?= htmlspecialchars($hw['due_date']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted">Заданий пока нет</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Блок последних новостей для всех -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Последние новости школы</h4>
        <a href="index.php?page=news" class="btn btn-white btn-sm">Все новости</a>
    </div>
    <div class="card-body">
        <?php if (!empty($newsList)): ?>
            <div class="row g-3">
                <?php foreach ($newsList as $item): ?>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block mb-1"><?= htmlspecialchars($item['created_at']) ?></small>
                            <h5 class="mb-2"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="text-body small mb-0"><?= htmlspecialchars(mb_strimwidth($item['content'], 0, 110, '...')) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted text-center mb-0">Новостей пока нет</p>
        <?php endif; ?>
    </div>
</div>
