<?php
require_once("../../includes/conn.php");

require_once __DIR__ . '/../../includes/auth.php';
requireApiRoles(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id          = intval($_POST["id"]);
    $description = $conn->real_escape_string(trim($_POST["description"] ?? ""));
    $address     = $conn->real_escape_string(trim($_POST["address"] ?? ""));
    $phone       = $conn->real_escape_string(trim($_POST["phone"] ?? ""));
    $email       = $conn->real_escape_string(trim($_POST["email"] ?? ""));

    if ($id > 0 && !empty($description) && !empty($address) && !empty($phone) && !empty($email)) {
        $sql = "UPDATE school_info 
                SET description = '$description', 
                    address = '$address', 
                    phone = '$phone', 
                    email = '$email' 
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../index.php?page=school_info");
            exit();
        } else {
            echo "Ошибка обновления: " . $conn->error;
        }
    } else {
        echo "Заполните все поля! <a href='../../index.php?page=schoolInfoEdit&id=$id'>Назад</a>";
    }
} else {
    header("Location: ../../index.php?page=school_info");
    exit();
}
?>