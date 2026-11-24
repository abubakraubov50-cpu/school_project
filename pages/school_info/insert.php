<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $conn->real_escape_string(trim($_POST["description"] ?? ""));
    $address     = $conn->real_escape_string(trim($_POST["address"] ?? ""));
    $phone       = $conn->real_escape_string(trim($_POST["phone"] ?? ""));
    $email       = $conn->real_escape_string(trim($_POST["email"] ?? ""));

    if (!empty($description) && !empty($address) && !empty($phone) && !empty($email)) {
        $sql = "INSERT INTO school_info (description, address, phone, email) 
                VALUES ('$description', '$address', '$phone', '$email')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=school_info");
            exit();
        } else {
            echo "Ошибка базы данных: " . $conn->error;
        }
    } else {
        echo "Заполните все поля! <a href='../../index.php?page=schoolInfoCreate'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=school_info");
    exit();
}
?>