<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = intval($_POST["student_id"]);
    $subject_id = intval($_POST["subject_id"]);
    $teacher_id = intval($_POST["teacher_id"]);
    $grade      = $conn->real_escape_string(trim($_POST["grade"]));
    $grade_date = $conn->real_escape_string(trim($_POST["grade_date"]));
    $comment    = !empty($_POST["COMMENT"]) ? "'" . $conn->real_escape_string(trim($_POST["COMMENT"])) . "'" : "NULL";

    if ($student_id && $subject_id && $teacher_id && !empty($grade) && !empty($grade_date)) {
        $sql = "INSERT INTO grades (student_id, subject_id, teacher_id, grade, grade_date, COMMENT) 
                VALUES ($student_id, $subject_id, $teacher_id, '$grade', '$grade_date', $comment)";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=grades");
            exit();
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } else {
        echo "Заполните все обязательные поля! <a href='../../index.php?page=gradeCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=grades");
    exit();
}
?>
