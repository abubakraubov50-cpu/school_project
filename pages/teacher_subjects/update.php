<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["teacher_id"])) {
    $teacher_id = intval($_POST["teacher_id"]);
    $subjects   = $_POST["subjects"] ?? [];

    if ($teacher_id > 0) {
        // Удаляем старые привязки этого учителя
        $conn->query("DELETE FROM teacher_subjects WHERE teacher_id = $teacher_id");

        // Записываем заново все отмеченные в форме предметы
        if (is_array($subjects) && !empty($subjects)) {
            foreach ($subjects as $sub_id) {
                $subject_id = intval($sub_id);
                if ($subject_id > 0) {
                    $conn->query("INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES ($teacher_id, $subject_id)");
                }
            }
        }
    }

    header("Location: ../../index.php?page=teacher_subjects");
    exit();
} else {
    header("Location: ../../index.php?page=teacher_subjects");
    exit();
}
?>