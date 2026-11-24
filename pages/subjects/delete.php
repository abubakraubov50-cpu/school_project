<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $conn->query("DELETE FROM teacher_subjects WHERE subject_id = $id");
    
    $conn->query("DELETE FROM subjects WHERE id = $id");
}

header("Location: ../../index.php?page=subjects");
exit();
?>