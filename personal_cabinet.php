<?php
session_start();

// Параметры подключения к базе данных
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "FITDatabase"; 

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

$message = ""; // Инициализация сообщения о регистрации или входе

// Обработка регистрации или авторизации
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $email = $_POST['registerEmail'];
        $password = $_POST['registerPassword'];
        $fullName = $_POST['registerFullName'];
        $phoneNumber = $_POST['registerPhoneNumber'];

        // Защита от SQL-инъекций
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);
        $fullName = mysqli_real_escape_string($conn, $fullName);
        $phoneNumber = mysqli_real_escape_string($conn, $phoneNumber);

        // Проверка наличия пользователя с таким же email
        $check_sql = "SELECT * FROM Users WHERE email='$email'";
        $check_result = $conn->query($check_sql);
        if ($check_result->num_rows > 0) {
            $message = "error";
        } else {
            // Хэширование пароля
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL запрос на добавление пользователя в базу данных
            $insert_sql = "INSERT INTO Users (email, password, fullName, phoneNumber) VALUES ('$email', '$hashed_password', '$fullName', '$phoneNumber')";

            if ($conn->query($insert_sql) === TRUE) {
                $message = "register_success";
            } else {
                $message = "error";
            }
        }
    } elseif (isset($_POST['login'])) {
        $email = $_POST['loginEmail'];
        $password = $_POST['loginPassword'];

        // Проверка на пустые поля
        if (empty($email) || empty($password)) {
            $message = "empty";
        } else {
            // Защита от SQL-инъекций
            $email = mysqli_real_escape_string($conn, $email);

            // SQL запрос для получения пользователя с указанным email
            $sql = "SELECT * FROM Users WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Пользователь найден
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // Пароль верный - пользователь аутентифицирован
                    $message = "login_success";
                    // Сохраняем его идентификатор и данные в сессии
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['fullName'] = $row['fullName'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['phoneNumber'] = $row['phoneNumber'];

                    // Обновляем куки для сохранения сессии
                    setcookie(session_name(), session_id(), time() + 3600, "/");

                    // Проверяем роль пользователя
                    $user_role = $row['role'];
                    if ($user_role == 'admin') {
                        // Пользователь администратор, перенаправляем на страницу для админов
                        header("Location: admin_page.php");
                        exit;
                    } else {
                        // Пользователь не администратор, перенаправляем на страницу личного кабинета
                        header("Location: profile.php");
                        exit;
                    }
                } else {
                    $message = "error";
                }
            } else {
                $message = "error";
            }
        }
    }
}

// Закрытие соединения
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('21.png');
            background-repeat: no-repeat;
            background-size: cover;
            color: black;
        }
        .registration-card {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.7);
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.5) !important;
            font-family: 'Butcherman', cursive;
        }
        .navbar-brand {
            color: white !important;
            display: inline;
        }
        .navbar-nav .nav-link {
            font-family: 'Butcherman', cursive;
            color: white !important;
        }
        .navbar-nav .nav-link:hover {
            color: #D8D9E9 !important;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
            padding: 20px 0;
        }
        .border-success {
            border-color: #28a745 !important;
        }
        .border-danger {
            border-color: #dc3545 !important;
        }
        .hide {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-transparent">
        <div class="container">
            <a class="navbar-brand" href="#">Фитнес клуб</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Главная</a></li>
                    <li class="nav-item"><a class="nav-link" href="zapis.php">Расписание</a></li>
                    <li class="nav-item"><a class="nav-link" href="groupclasses.html">Групповые программы</a></li>
                    <li class="nav-item"><a class="nav-link" href="trainers.php">Тренеры</a></li>
                    <li class="nav-item"><a class="nav-link" href="vakancii.html">Вакансии</a></li>
                    <li class="nav-item"><a class="nav-link" href="atributika.html">Атрибутика</a></li>
                    <li class="nav-item"><a class="nav-link" href="personal_cabinet.php">Личный кабинет</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="registration-card <?php echo $message === 'register_success' ? 'border-success' : ''; ?>">
                    <h2 class="text-center mb-4">Личный кабинет</h2>
                    <?php if ($message == 'error'): ?>
                        <div class="alert alert-danger" role="alert">
                            Ошибка: неверный email или пароль!
                        </div>
                    <?php elseif ($message == 'empty'): ?>
                        <div class="alert alert-danger" role="alert">
                            Ошибка: введите логин и пароль!
                        </div>
                    <?php elseif ($message == 'register_success'): ?>
                        <div class="alert alert-success" role="alert">
                            Регистрация успешна! Теперь вы можете войти.
                        </div>
                    <?php endif; ?>
                    <div id="forms">
                        <form method="post" action="" id="loginForm" <?php echo $message == 'register_success' ? 'class="hide"' : ''; ?>>
                            <div class="form-group">
                                <label for="loginEmail">Email адрес</label>
                                <input type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="Введите email" name="loginEmail">
                            </div>
                            <div class="form-group">
                                <label for="loginPassword">Пароль</label>
                                <input type="password" class="form-control" id="loginPassword" placeholder="Пароль" name="loginPassword">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="login">Войти</button>
                        </form>
                        <form method="post" action="" id="registerForm" <?php echo $message == 'register_success' ? '' : 'class="hide"'; ?>>
                            <div class="form-group">
                                <label for="registerFullName">ФИО</label>
                                <input type="text" class="form-control" id="registerFullName" placeholder="Введите полное имя" name="registerFullName" required>
                            </div>
                            <div class="form-group">
                                <label for="registerPhoneNumber">Номер телефона</label>
                                <input type="text" class="form-control" id="registerPhoneNumber" placeholder="Введите номер телефона" name="registerPhoneNumber" required>
                            </div>
                            <div class="form-group">
                                <label for="registerEmail">Email адрес</label>
                                <input type="email" class="form-control" id="registerEmail" placeholder="Введите email" name="registerEmail" required>
                            </div>
                            <div class="form-group">
                                <label for="registerPassword">Пароль</label>
                                <input type="password" class="form-control" id="registerPassword" placeholder="Пароль" name="registerPassword" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="register">Зарегистрироваться</button>
                        </form>
                        <p class="text-center mt-3">
                            <a href="#" onclick="toggleForms()"><?php echo $message == 'register_success' ? 'Уже есть аккаунт? Войдите!' : 'Нет аккаунта? Зарегистрируйтесь!'; ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <span>© 2024 Ваш Фитнес Клуб</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function toggleForms() {
            $('#loginForm').toggleClass('hide');
            $('#registerForm').toggleClass('hide');
            var text = $('#loginForm').hasClass('hide') ? 'Уже есть аккаунт? Войдите!' : 'Нет аккаунта? Зарегистрируйтесь!';
            $('p a').text(text);
        }
    </script>
</body>
</html>
