<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $show_modal = true; // Флаг для отображения модального окна
} else {
    $show_modal = false;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "FITDatabase";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Ошибка подключения к базе данных: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['plan_name'], $_POST['price'])) {
            $plan_name = $_POST['plan_name'];
            $price = $_POST['price'];

            $stmt = $conn->prepare("INSERT INTO Subscriptions (user_id, plan_name, price, status) VALUES (?, ?, ?, 'pending')");
            $stmt->bind_param("isd", $user_id, $plan_name, $price);
            $stmt->execute();
            $stmt->close();

            header("Location: personal_cabinet.php");
            exit;
        }
    }

    $plan_name = isset($_GET['plan_name']) ? htmlspecialchars($_GET['plan_name']) : '';
    $price = isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '';

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение покупки</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php if (!$show_modal): ?>
    <div class="container mt-5">
        <h2>Подтверждение покупки</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="plan_name">Название плана</label>
                <input type="text" class="form-control" id="plan_name" name="plan_name" value="<?php echo $plan_name; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="price">Цена (руб)</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $price; ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Подтвердить покупку</button>
        </form>
    </div>
    <?php else: ?>
    <!-- Модальное окно для неавторизованных пользователей -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Требуется авторизация</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Для оформления покупки вам необходимо войти в систему или зарегистрироваться.</p>
                    <a href="login.php" class="btn btn-primary">Войти</a>
                    <a href="register.php" class="btn btn-secondary">Зарегистрироваться</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        <?php if ($show_modal): ?>
        $(document).ready(function() {
            $('#loginModal').modal('show');
        });
        <?php endif; ?>
    </script>
</body>
</html>
