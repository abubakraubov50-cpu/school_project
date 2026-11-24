<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id         = intval($_POST["id"]);
    $student_id = intval($_POST["student_id"]);
    $subject_id = intval($_POST["subject_id"]);
    $teacher_id = intval($_POST["teacher_id"]);
    $grade      = $conn->real_escape_string(trim($_POST["grade"]));
    $grade_date = $conn->real_escape_string(trim($_POST["grade_date"]));
    $comment    = !empty($_POST["COMMENT"]) ? "'" . $conn->real_escape_string(trim($_POST["COMMENT"])) . "'" : "NULL";

    $sql = "UPDATE grades 
            SET student_id = $student_id, 
                subject_id = $subject_id, 
                teacher_id = $teacher_id, 
                grade = '$grade', 
                grade_date = '$grade_date', 
                COMMENT = $comment 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?page=grades");
        exit();
    } else {
        echo "Ошибка обновления: " . $conn->error;
    }
} else {
    header("Location: ../../index.php?page=grades");
    exit();
}
?>
