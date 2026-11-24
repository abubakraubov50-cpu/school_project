<?php
$host = "mysql-8.4";
$username = "root";
$password = "";
$dbname = "school_project";

$conn = new mysqli($host, $username, $password, $dbname);

if($conn -> connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>