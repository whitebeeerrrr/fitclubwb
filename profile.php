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

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM Users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['fullName'] = $user['fullName'];
    $_SESSION['phoneNumber'] = $user['phoneNumber'];
    $_SESSION['email'] = $user['email'];
} else {
    echo "Пользователь не найден";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['workout_date'], $_POST['workout_type'], $_POST['workout_duration'])) {
        $workout_date = $_POST['workout_date'];
        $workout_type = $_POST['workout_type'];
        $workout_duration = $_POST['workout_duration'];

        $stmt = $conn->prepare("INSERT INTO Workouts (user_id, date, type, duration) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $workout_date, $workout_type, $workout_duration);
        $stmt->execute();
        $stmt->close();
    }
    if (isset($_POST['note'])) {
        $note = $_POST['note'];

        $stmt = $conn->prepare("INSERT INTO Notes (user_id, note) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $note);
        $stmt->execute();
        $stmt->close();
    }
    if (isset($_POST['plan_name'], $_POST['price'])) {
        $plan_name = $_POST['plan_name'];
        $price = $_POST['price'];

        $stmt = $conn->prepare("INSERT INTO Subscriptions (user_id, plan_name, price, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("isd", $user_id, $plan_name, $price);
        $stmt->execute();
        $stmt->close();
    }
}

$workouts_sql = "SELECT * FROM Workouts WHERE user_id='$user_id'";
$workouts_result = $conn->query($workouts_sql);

$notes_sql = "SELECT * FROM Notes WHERE user_id='$user_id'";
$notes_result = $conn->query($notes_sql);

$subscriptions_sql = "SELECT * FROM Subscriptions WHERE user_id='$user_id'";
$subscriptions_result = $conn->query($subscriptions_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
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
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card {
            width: 300px;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.7);
            flex: 1 1 300px;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.5) !important;
            font-family: 'Butcherman', cursive;
        }
        .navbar-brand, .navbar-nav .nav-link {
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
        .modal-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .modal-body form {
            width: 100%;
        }
        .modal-body form .form-group {
            width: 100%;
        }
        #bjuResult {
            width: 100%;
            margin-top: 20px;
        }
        .row-centered {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="personal_cabinet.php">Личный кабинет</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Выход</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="personal_cabinet.php">Войти</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <div class="container mt-5 card-container">
        <div class="card profile-card">
            <h2 class="text-center mb-4">Личный кабинет</h2>
            <p><strong>ФИО:</strong> <?php echo isset($_SESSION['fullName']) ? htmlspecialchars($_SESSION['fullName']) : ''; ?></p>
            <p><strong>Номер телефона:</strong> <?php echo isset($_SESSION['phoneNumber']) ? htmlspecialchars($_SESSION['phoneNumber']) : ''; ?></p>
            <p><strong>Email адрес:</strong> <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?></p>
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#editProfileModal">Редактировать</button>
        </div>

        <div class="card workouts-card">
            <h2 class="text-center mb-4">Мои тренировки</h2>
            <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#addWorkoutModal">Добавить тренировку</button>
            <ul class="list-group mt-3">
                <?php
                if ($workouts_result->num_rows > 0) {
                    while ($workout = $workouts_result->fetch_assoc()) {
                        echo '<li class="list-group-item">';
                        echo '<strong>Дата:</strong> ' . htmlspecialchars($workout['date']) . '<br>';
                        echo '<strong>Тип:</strong> ' . htmlspecialchars($workout['type']) . '<br>';
                        echo '<strong>Продолжительность (мин):</strong> ' . htmlspecialchars($workout['duration']);
                        echo '</li>';
                    }
                } else {
                    echo '<li class="list-group-item">Нет записей о тренировках</li>';
                }
                ?>
            </ul>
        </div>

        <div class="card notes-card">
            <h2 class="text-center mb-4">Заметки</h2>
            <form id="addNoteForm" method="post" action="">
                <div class="form-group">
                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Введите заметку" required></textarea>
                </div>
                <button type="submit" class="btn btn-success btn-block">Добавить заметку</button>
            </form>
            <ul class="list-group mt-3">
                <?php
                if ($notes_result->num_rows > 0) {
                    while ($note = $notes_result->fetch_assoc()) {
                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                        echo htmlspecialchars($note['note']);
                        echo '<form method="post" action="delete_note.php" style="margin: 0;">';
                        echo '<input type="hidden" name="note_id" value="' . $note['id'] . '">';
                        echo '<button type="submit" class="btn btn-danger btn-sm">Удалить</button>';
                        echo '</form>';
                        echo '</li>';
                    }
                } else {
                    echo '<li class="list-group-item">Нет заметок</li>';
                }
                ?>
            </ul>
        </div>

        <div class="card support-card">
            <h2 class="text-center mb-4">Поддержка</h2>
            <p>Если у вас есть вопросы или проблемы, наша служба поддержки готова помочь вам.</p>
            <a href="welcome.php" class="btn btn-info btn-block">Связаться с поддержкой</a>
        </div>

        <div class="card bju-card">
            <h2 class="text-center mb-4">Калькулятор БЖУ</h2>
            <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#bjuCalculatorModal">Открыть калькулятор</button>
        </div>

        <div class="card subscriptions-card">
            <h2 class="text-center mb-4">Мои абонементы</h2>
            <ul class="list-group">
                <?php
                if ($subscriptions_result->num_rows > 0) {
                    while ($subscription = $subscriptions_result->fetch_assoc()) {
                        echo '<li class="list-group-item">';
                        echo '<strong>План:</strong> ' . htmlspecialchars($subscription['plan_name']) . '<br>';
                        echo '<strong>Цена:</strong> ' . htmlspecialchars($subscription['price']) . ' руб.<br>';
                        echo '<strong>Статус:</strong> ' . htmlspecialchars($subscription['status']);
                        if ($subscription['status'] == 'pending') {
                            echo ' <button class="btn btn-success btn-sm">Оплатить</button>';
                        }
                        echo '</li>';
                    }
                } else {
                    echo '<li class="list-group-item">Нет активных абонементов</li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Модальное окно для редактирования профиля -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Редактировать профиль</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" method="post" action="update_profile.php">
                        <div class="form-group">
                            <label for="fullName">ФИО</label>
                            <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($_SESSION['fullName']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Номер телефона</label>
                            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($_SESSION['phoneNumber']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email адрес</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для добавления тренировки -->
    <div class="modal fade" id="addWorkoutModal" tabindex="-1" role="dialog" aria-labelledby="addWorkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWorkoutModalLabel">Добавить тренировку</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addWorkoutForm" method="post" action="">
                        <div class="form-group">
                            <label for="workout_date">Дата</label>
                            <input type="date" class="form-control" id="workout_date" name="workout_date" required>
                        </div>
                        <div class="form-group">
                            <label for="workout_type">Тип тренировки</label>
                            <input type="text" class="form-control" id="workout_type" name="workout_type" required>
                        </div>
                        <div class="form-group">
                            <label for="workout_duration">Продолжительность (мин)</label>
                            <input type="number" class="form-control" id="workout_duration" name="workout_duration" required>
                        </div>
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для калькулятора БЖУ -->
    <div class="modal fade" id="bjuCalculatorModal" tabindex="-1" role="dialog" aria-labelledby="bjuCalculatorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bjuCalculatorModalLabel">Калькулятор БЖУ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="bjuCalculatorForm">
                        <div class="form-group">
                            <label for="weight">Вес (кг)</label>
                            <input type="number" class="form-control" id="weight" name="weight" required>
                        </div>
                        <div class="form-group">
                            <label for="height">Рост (см)</label>
                            <input type="number" class="form-control" id="height" name="height" required>
                        </div>
                        <div class="form-group">
                            <label for="age">Возраст</label>
                            <input type="number" class="form-control" id="age" name="age" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Пол</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="male">Мужской</option>
                                <option value="female">Женский</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="activity">Уровень активности</label>
                            <select class="form-control" id="activity" name="activity" required>
                                <option value="1.2">Минимальная активность</option>
                                <option value="1.375">Легкая активность</option>
                                <option value="1.55">Средняя активность</option>
                                <option value="1.725">Высокая активность</option>
                                <option value="1.9">Экстремально высокая активность</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="calculateBJU()">Рассчитать</button>
                        <button type="button" class="btn btn-secondary" onclick="resetBJU()">Сбросить</button>
                    </form>
                    <div class="mt-4" id="bjuResult" style="display: none;">
                        <h5>Результаты:</h5>
                        <p><strong>Калории:</strong> <span id="calories"></span></p>
                        <p><strong>Белки:</strong> <span id="protein"></span> г</p>
                        <p><strong>Жиры:</strong> <span id="fat"></span> г</p>
                        <p><strong>Углеводы:</strong> <span id="carbs"></span> г</p>
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
        function calculateBJU() {
            var weight = parseFloat(document.getElementById('weight').value);
            var height = parseFloat(document.getElementById('height').value);
            var age = parseInt(document.getElementById('age').value);
            var gender = document.getElementById('gender').value;
            var activity = parseFloat(document.getElementById('activity').value);

            var bmr;

            if (gender === 'male') {
                bmr = 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age);
            } else {
                bmr = 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age);
            }

            var calories = bmr * activity;
            var protein = (calories * 0.3) / 4;
            var fat = (calories * 0.25) / 9;
            var carbs = (calories * 0.45) / 4;

            document.getElementById('calories').textContent = Math.round(calories);
            document.getElementById('protein').textContent = Math.round(protein);
            document.getElementById('fat').textContent = Math.round(fat);
            document.getElementById('carbs').textContent = Math.round(carbs);

            document.getElementById('bjuResult').style.display = 'block';
        }

        function resetBJU() {
            document.getElementById('bjuCalculatorForm').reset();
            document.getElementById('bjuResult').style.display = 'none';
        }
    </script>
</body>
</html>
