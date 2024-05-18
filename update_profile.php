<?php
session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: personal_cabinet.php");
    exit;
}

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FITDatabase";
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Обновление данных пользователя
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE Users SET fullName='$fullName', phoneNumber='$phoneNumber', email='$email' WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        // Обновление данных в сессии
        $_SESSION['fullName'] = $fullName;
        $_SESSION['phoneNumber'] = $phoneNumber;
        $_SESSION['email'] = $email;

        header("Location: profile.php");
        exit;
    } else {
        echo "Ошибка обновления данных: " . $conn->error;
    }
}

$conn->close();
?>
