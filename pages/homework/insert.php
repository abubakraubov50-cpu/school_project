<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id  = intval($_POST["subject_id"]);
    $class_id    = intval($_POST["class_id"]);
    $teacher_id  = intval($_POST["teacher_id"]);
    $description = $conn->real_escape_string(trim($_POST["description"]));
    $due_date    = $conn->real_escape_string(trim($_POST["due_date"]));

    if ($subject_id && $class_id && $teacher_id && !empty($description) && !empty($due_date)) {
        $sql = "INSERT INTO homework (subject_id, class_id, teacher_id, description, due_date) 
                VALUES ($subject_id, $class_id, $teacher_id, '$description', '$due_date')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=homework");
            exit();
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } else {
        echo "Заполните все поля! <a href='../../index.php?page=homeworkCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=homework");
    exit();
}
?>