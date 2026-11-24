<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin', 'teacher']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = isset($_POST["teacher_id"]) ? intval($_POST["teacher_id"]) : 0;
    $subjects   = $_POST["subjects"] ?? [];

    // Проверяем, выбран ли учитель и передан ли непустой массив предметов
    if ($teacher_id > 0 && is_array($subjects) && !empty($subjects)) {

        foreach ($subjects as $sub_id) {
            $subject_id = intval($sub_id);

            if ($subject_id > 0) {

                $check = $conn->query("SELECT id FROM teacher_subjects WHERE teacher_id = $teacher_id AND subject_id = $subject_id");

                // Если такой связки ещё нет — добавляем в базу
                if ($check && $check->num_rows == 0) {
                    $sql = "INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES ($teacher_id, $subject_id)";
                    if ($conn->query($sql) !== TRUE) {
                        echo "Ошибка при добавлении предмета ID $subject_id: " . $conn->error;
                        exit();
                    }
                }
            }
        }

        // После обработки всех выбранных предметов возвращаемся на список
        header("Location: ../../index.php?page=teacher_subjects");
        exit();

    } else {
        echo "Выберите учителя и хотя бы один предмет! <a href='../../index.php?page=teacherSubjectCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=teacher_subjects");
    exit();
}
?>