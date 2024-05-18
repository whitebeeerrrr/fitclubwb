<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: personal_cabinet.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payment-form {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-form">
            <h1>Оплата</h1>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $itemName = $_POST['itemName'];
                $itemPrice = $_POST['itemPrice'];
                $cardNumber = $_POST['cardNumber'];
                $expiryDate = $_POST['expiryDate'];
                $cvv = $_POST['cvv'];

                // Здесь можно добавить логику обработки оплаты (например, интеграция с платежным шлюзом)

                echo "<h1>Оплата успешно выполнена!</h1>";
                echo "<p>Товар: $itemName</p>";
                echo "<p>Цена: $itemPrice руб.</p>";
            } else {
            ?>
            <form action="payment.php" method="post">
                <div class="form-group">
                    <label for="itemName">Название товара</label>
                    <input type="text" class="form-control" id="itemName" name="itemName" readonly>
                </div>
                <div class="form-group">
                    <label for="itemPrice">Цена</label>
                    <input type="text" class="form-control" id="itemPrice" name="itemPrice" readonly>
                </div>
                <div class="form-group">
                    <label for="cardNumber">Номер карты</label>
                    <input type="text" class="form-control" id="cardNumber" name="cardNumber" required>
                </div>
                <div class="form-group">
                    <label for="expiryDate">Срок действия</label>
                    <input type="text" class="form-control" id="expiryDate" name="expiryDate" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Оплатить</button>
            </form>
            <?php
            }
            ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            document.getElementById('itemName').value = urlParams.get('item');
            document.getElementById('itemPrice').value = urlParams.get('price');
        });
    </script>
</body>
</html>
