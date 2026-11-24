<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id          = intval($_POST["id"]);
    $class_id    = intval($_POST["class_id"]);
    $subject_id  = intval($_POST["subject_id"]);
    $teacher_id  = intval($_POST["teacher_id"]);
    $day_of_week = intval($_POST["day_of_week"]);
    $start_time  = $conn->real_escape_string(trim($_POST["start_time"]));
    $end_time    = $conn->real_escape_string(trim($_POST["end_time"]));
    $room        = !empty($_POST["room"]) ? "'" . $conn->real_escape_string(trim($_POST["room"])) . "'" : "NULL";

    $sql = "UPDATE schedule 
            SET class_id = $class_id, 
                subject_id = $subject_id, 
                teacher_id = $teacher_id, 
                day_of_week = $day_of_week, 
                start_time = '$start_time', 
                end_time = '$end_time', 
                room = $room 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?page=schedule");
        exit();
    } else {
        echo "Ошибка обновления: " . $conn->error;
    }
} else {
    header("Location: ../../index.php?page=schedule");
    exit();
}
?>