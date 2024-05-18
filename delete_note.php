<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: personal_cabinet.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FITDatabase";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['note_id'])) {
        $note_id = $_POST['note_id'];

        $stmt = $conn->prepare("DELETE FROM Notes WHERE id = ?");
        $stmt->bind_param("i", $note_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: personal_cabinet.php");
exit;
?>
