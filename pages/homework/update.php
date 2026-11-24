<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id          = intval($_POST["id"]);
    $subject_id  = intval($_POST["subject_id"]);
    $class_id    = intval($_POST["class_id"]);
    $teacher_id  = intval($_POST["teacher_id"]);
    $description = $conn->real_escape_string(trim($_POST["description"]));
    $due_date    = $conn->real_escape_string(trim($_POST["due_date"]));

    $sql = "UPDATE homework 
            SET subject_id = $subject_id, 
                class_id = $class_id, 
                teacher_id = $teacher_id, 
                description = '$description', 
                due_date = '$due_date' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?page=homework");
        exit();
    } else {
        echo "Ошибка обновления: " . $conn->error;
    }
} else {
    header("Location: ../../index.php?page=homework");
    exit();
}
?>