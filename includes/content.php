<?php
$page = $_GET["page"] ?? '';

// Страницы, доступные ТОЛЬКО админу
$adminOnlyPages = [
    'users', 'userCreate', 'userEdit',
    'classCreate', 'classEdit',
    'galleryCreate', 'galleryEdit',
    'scheduleCreate', 'scheduleEdit',
    'schoolInfoCreate', 'schoolInfoEdit'
];

// Страницы, доступные ТОЛЬКО админу и учителю
$staffOnlyPages = [
    'subjectCreate', 'subjectEdit',
    'homeworkCreate', 'homeworkEdit',
    'newsCreate', 'newsEdit',
    'teacher_subjects', 'teacherSubjectCreate', 'teacherSubjectEdit',
    'gradeCreate', 'gradeEdit'
];

if (in_array($page, $adminOnlyPages, true)) {
    requireRoles(['admin']);
} else if (in_array($page, $staffOnlyPages, true)) {
    requireRoles(['admin', 'teacher']);
}

if ($page == "subjectCreate") {
    require_once("pages/subjects/create.php");
}
else if ($page == "subjects") {
    require_once("pages/subjects/index.php");
}
else if ($page == "subjectEdit") {
    require_once("pages/subjects/edit.php");
}
else if ($page == "homework") {
    require_once("pages/homework/index.php");
} else if ($page == "homeworkCreate") {
    require_once("pages/homework/create.php");
} else if ($page == "homeworkEdit") {
    require_once("pages/homework/edit.php");
}
else if ($page == "news") {
    require_once("pages/news/index.php");
} else if ($page == "newsCreate") {
    require_once("pages/news/create.php");
} else if ($page == "newsEdit") {
    require_once("pages/news/edit.php");
}
else if ($page == "users") {
    require_once("pages/users/index.php");
} else if ($page == "userCreate") {
    require_once("pages/users/create.php");
} else if ($page == "userEdit") {
    require_once("pages/users/edit.php");
}
else if ($page == "teacher_subjects") {
    require_once("pages/teacher_subjects/index.php");
} else if ($page == "teacherSubjectCreate") {
    require_once("pages/teacher_subjects/create.php");
} else if ($page == "teacherSubjectEdit") {
    require_once("pages/teacher_subjects/edit.php");
}
else if ($page == "classes") {
    require_once("pages/classes/index.php");
} else if ($page == "classCreate") {
    require_once("pages/classes/create.php");
} else if ($page == "classEdit") {
    require_once("pages/classes/edit.php");
}
else if ($page == "gallery") {
    require_once("pages/gallery/index.php");
} else if ($page == "galleryCreate") {
    require_once("pages/gallery/create.php");
} else if ($page == "galleryEdit") {
    require_once("pages/gallery/edit.php");
}
else if ($page == "grades") {
    require_once("pages/grades/index.php");
} else if ($page == "gradeCreate") {
    require_once("pages/grades/create.php");
} else if ($page == "gradeEdit") {
    require_once("pages/grades/edit.php");
}
else if ($page == "schedule") {
    require_once("pages/schedule/index.php");
} else if ($page == "scheduleCreate") {
    require_once("pages/schedule/create.php");
} else if ($page == "scheduleEdit") {
    require_once("pages/schedule/edit.php");
}
else if ($page == "school_info") {
    require_once("pages/school_info/index.php");
} else if ($page == "schoolInfoCreate") {
    require_once("pages/school_info/create.php");
} else if ($page == "schoolInfoEdit") {
    require_once("pages/school_info/edit.php");
}
else if ($page == "search") {
    require_once("pages/search.php");
}
else if ($page == "profile") {
    require_once("pages/profile/index.php");
}
else {
    require_once("pages/dashboard.php");
}