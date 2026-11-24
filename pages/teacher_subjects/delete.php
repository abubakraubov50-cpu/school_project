<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if (isset($_GET['teacher_id'])) {
    $teacher_id = intval($_GET['teacher_id']);
    $conn->query("DELETE FROM teacher_subjects WHERE teacher_id = $teacher_id");
}

header("Location: ../../index.php?page=teacher_subjects");
exit();
?>