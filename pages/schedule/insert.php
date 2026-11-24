<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id    = isset($_POST["class_id"]) ? intval($_POST["class_id"]) : 0;
    $subject_id  = isset($_POST["subject_id"]) ? intval($_POST["subject_id"]) : 0;
    $teacher_id  = isset($_POST["teacher_id"]) ? intval($_POST["teacher_id"]) : 0;
    $day_of_week = isset($_POST["day_of_week"]) ? intval($_POST["day_of_week"]) : 0;
    $start_time  = isset($_POST["start_time"]) ? $conn->real_escape_string(trim($_POST["start_time"])) : "";
    $end_time    = isset($_POST["end_time"]) ? $conn->real_escape_string(trim($_POST["end_time"])) : "";
    $room        = !empty($_POST["room"]) ? "'" . $conn->real_escape_string(trim($_POST["room"])) . "'" : "NULL";

    if ($class_id && $subject_id && $teacher_id && $day_of_week && !empty($start_time) && !empty($end_time)) {
        $sql = "INSERT INTO schedule (class_id, subject_id, teacher_id, day_of_week, start_time, end_time, room) 
                VALUES ($class_id, $subject_id, $teacher_id, $day_of_week, '$start_time', '$end_time', $room)";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=schedule");
            exit();
        } else {
            echo "Ошибка базы данных: " . $conn->error;
        }
    } else {
        echo "Заполните все обязательные поля! <a href='../../index.php?page=scheduleCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=schedule");
    exit();
}
?>