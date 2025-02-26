<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    // Перенаправление на страницу авторизации, если пользователь не авторизован
    header("Location: personal_cabinet.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фитнес клуб</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        ::-webkit-scrollbar {
            width: 0;
        }
        body {
            background-image: url('баки.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
            overflow-y: scroll;
            margin: 80px 0 100px;
            padding: 0;
            font-family: consolas;
            margin-bottom: 0;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        .navbar-nav .nav-link {
            font-family: 'Roboto', sans-serif;
        }
        .location-card, .special-offers-card {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
            color: white;
        }
        .location-card h2, .special-offers-card h2 {
            margin-bottom: 20px;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .location-card p, .special-offers-card p {
            margin-bottom: 10px;
            color: white;
        }
        .location-card p:last-child, .special-offers-card p:last-child {
            margin-bottom: 0;
            color: white;
        }
        .location-card img, .special-offers-card img {
            max-width: 50%;
            border-radius: 10px;
        }
        .card-deck {
            margin-top: 20px;
        }
        .card {
            background-color: transparent;
            padding: 20px;
            border-radius: 10px;
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
            width: calc(33.333% - 40px);
            margin-right: 20px;
            margin-left: 20px;
            position: relative;
        }
        .card .face {
            width: 100%;
            height: 100%;
            transition: 0.5s;
            overflow: hidden;
            position: relative;
        }
        .card .face.face1 .content {
            background-size: cover;
            background-position: center;
            height: 300px;
            border-radius: 10px;
        }
        .card .face.face1 .content img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        .card:hover .face.face2 {
            transform: translateY(0);
        }
        .card .face.face2 {
            position: absolute;
            bottom: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            transform: translateY(100%);
            border-radius: 10px;
            color: black;
            font-family: Arial, sans-serif;
        }
        .card .face.face2 .content p {
            margin: 0;
            padding: 0;
            color: black;
        }
        .card .face.face2 .content a {
            margin: 15px 0 0;
            display: inline-block;
            text-decoration: none;
            font-weight: 900;
            color: black;
            padding: 5px;
            border: 1px solid black;
        }
        .card .face.face2 .content a:hover {
            background: black;
            color: white;
        }
        .special-offers-card {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
            color: white;
        }
        .special-offers-card h2 {
            margin-bottom: 20px;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .special-offers-card p {
            margin-bottom: 10px;
            color: white;
        }
        .special-offers-card p:last-child {
            margin-bottom: 0;
            color: white;
        }
        .special-offers-card img {
            max-width: 50%;
            border-radius: 10px;
        }
        .footer {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 20px 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            margin-top: 20px;
        }
        .location-card {
            margin-bottom: 20px;
        }
        .nav-item.ml-auto {
            margin-left: auto !important;
        }
        .modal-content {
            color: black;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-transparent">
        <div class="container">
            <a class="navbar-brand" href="#">Фитнес клуб</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Главная</a></li>
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

    <div class="container">
        <div class="special-offers-card">
            <h2 class="display-4">Выгодные предложения</h2>
            <p class="lead">Тренируйся с сильнейшими.</p>
        </div>

        <div class="card-deck">
            <div class="card">
                <div class="face face1">
                    <div class="content">
                        <img src="готовая11.jpg" alt="Premium Image" class="img-fluid rounded">
                    </div>
                </div>
                <div class="face face2">
                    <div class="content">
                        <h3>BodyBlend</h3>
                        <ul>
                            <li>Тренажерный зал</li>
                            <li>Зал функционального тренинга</li>
                            <li>Групповые программы</li>
                            <li>Бассейн с морской водой</li>
                            <li>Финская сауна и хаммам</li>
                            <li>Полотенце предоставляется</li>
                            <li>Персональная тренировка в тренажерном зале</li>
                            <li>Цена: 17500</li>
                        </ul>
                        <a href="#" class="btn btn-whitesmoke buy-subscription" data-plan-name="BodyBlend" data-price="17500">Оформить</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="face face1">
                    <div class="content">
                        <img src="готовая2.jpg" alt="GymPlus Image" class="img-fluid rounded">
                    </div>
                </div>
                <div class="face face2">
                    <div class="content">
                        <h3>GymPlus</h3>
                        <ul>
                            <li>Кардио и силовые</li>
                            <li>Функциональные тренировки</li>
                            <li>Групповые занятия</li>
                            <li>Морской бассейн</li>
                            <li>Сауна и хаммам</li>
                            <li>Бесплатное полотенце</li>
                            <li>Персональные тренировки</li>
                            <li>Индивидуальные уроки</li>
                            <li>Цена: 10500</li>
                        </ul>
                        <a href="#" class="btn btn-whitesmoke buy-subscription" data-plan-name="GymPlus" data-price="10500">Оформить</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="face face1">
                    <div class="content">
                        <img src="готовая3.jpg" alt="FitWave Image" class="img-fluid rounded">
                    </div>
                </div>
                <div class="face face2">
                    <div class="content">
                        <h3>FitWave</h3>
                        <ul>
                            <li>Силовой зал</li>
                            <li>Функциональные занятия</li>
                            <li>Групповые тренировки</li>
                            <li>Морской план</li>
                            <li>Релакс-зона</li>
                            <li>Полотенце включено</li>
                            <li>Индивидуальное обучение</li>
                            <li>Персональные занятия</li>
                            <li>Цена: 7500</li>
                        </ul>
                        <a href="#" class="btn btn-whitesmoke buy-subscription" data-plan-name="FitWave" data-price="7500">Оформить</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="location-card">
            <h2 class="display-4">Место нахождения</h2>
            <p class="lead"><strong>Адрес:</strong> ул. Примерная, д. 123</p>
            <p class="lead"><strong>Телефон:</strong> +7 123 456 78 90</p>
            <p class="lead"><strong>График работы:</strong></p>
            <p class="lead">Пн-Пт: 7:00 - 22:00</p>
            <p class="lead">Сб-Вс: 9:00 - 20:00</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d22640.291855341135!2d50.165626631282!3d53.21794167471541!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4140f27e4ac1795d%3A0x2d9c4b75e9df6718!2sSamara%2C%20Samara%20Oblast%2C%20Russia!5e0!3m2!1sen!2s!4v1646814741957!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row text-center">
                <div class="col">
                    <button type="button" class="btn btn-outline-light" onclick="showPrivacyPolicy()">Соглашение о защите персональных данных</button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-outline-light" onclick="showSafetyRules()">Правила и техника безопасности на тренажерах</button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-outline-light" onclick="showUserAgreement()">Соглашение об использовании услуг и сервисов</button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyPolicyModal" tabindex="-1" role="dialog" aria-labelledby="privacyPolicyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyPolicyModalLabel" style="color: black;">Соглашение о защите персональных данных</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="color: black;">
                        Настоящая Политика обработки персональной информации (далее – Политика) действует в отношении всей информации, которую Общество с ограниченной ответственностью «ИЛОН» (далее – Администрация Сайта), могут получить о Пользователе во время использования им любого из сайтов, сервисов, служб, программ, продуктов Сайта, интернет-сервисов самостоятельной регистрации, в том числе мобильных приложений (далее – Сервисы или Сервисы Сайта). Согласие Пользователя на предоставление персональной информации, данное им в соответствии с настоящей Политикой в рамках использования одного из Сервисов, распространяется на все Сервисы Сайта.
                    </p>
                    <p style="color: black;">
                        Пожалуйста, обратите внимание, что использование любого из Сайтов и/или Сервисов может регулироваться дополнительными условиями, которые могут вносить в настоящую Политику изменения и/или дополнения, и/или иметь специальные условия в отношении персональной информации, размещенные в соответствующих разделах документов для таких Сайтов /или Сервисов.
                    </p>
                    <p style="color: black;">
                        Настоящее положение разработано с целью защиты информации, относящейся к персональным данным Пользователя, в соответствии с принципами, установленными:
                    </p>
                    <ul style="color: black;">
                        <li>ст. 24 Конституции РФ,</li>
                        <li>ФЗ от 27.07.2006 N 149-ФЗ "Об информации, информационных технологиях и о защите информации",</li>
                        <li>ФЗ от 27.07.2006 N 152-ФЗ "О персональных данных",</li>
                        <li>Главой 14 ТК РФ,</li>
                        <li>Постановлением Правительства РФ от 01.11.2012 N 1119 "Об утверждении требований к защите персональных данных при их обработке в информационных системах персональных данных",</li>
                        <li>Постановлением Правительства РФ от 15 сентября 2008 г. № 687 «Об утверждении Положения об особенностях обработки персональных данных, осуществляемой без использования средств автоматизации»,</li>
                        <li>Приказом ФСТЭК России от 18.02.2013 N 21 "Об утверждении Состава и содержания организационных и технических мер по обеспечению безопасности персональных данных при их обработке в информационных системах персональных данных".</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Safety Rules Modal -->
    <div class="modal fade" id="safetyRulesModal" tabindex="-1" role="dialog" aria-labelledby="safetyRulesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="safetyRulesModalLabel" style="color: black;">Правила и техника безопасности на тренажерах</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="color: black;">
                        Правила и техника безопасности на тренажерах играют важную роль в предотвращении травм и обеспечении эффективной тренировки. Вот некоторые основные правила и рекомендации:
                    </p>
                    <ul style="color: black;">
                        <li>Перед началом тренировки обязательно проконсультируйтесь с инструктором или специалистом по фитнесу для определения подходящей для вас программы тренировок и корректной техники выполнения упражнений.</li>
                        <li>Начинайте тренировку с разминки, чтобы подготовить мышцы и связки к более интенсивной нагрузке.</li>
                        <li>Следите за своим дыханием: при выполнении упражнений дышите ритмично и равномерно.</li>
                        <li>Поддерживайте правильную позу и выравнивание тела во время тренировки, избегайте излишнего наклона тела или перегибания в спине.</li>
                        <li>Используйте оборудование только в соответствии с его назначением и инструкциями по использованию.</li>
                        <li>Не пытайтесь перегружать себя слишком тяжелыми весами: выбирайте нагрузку, которую можете контролировать и выполнять упражнения в полном объеме движения.</li>
                        <li>Включайте в тренировку упражнения на разные группы мышц, чтобы обеспечить равномерное развитие тела.</li>
                        <li>После тренировки обязательно выполните растяжку для уменьшения напряжения в мышцах и предотвращения мышечной боли.</li>
                        <li>В случае возникновения боли или дискомфорта прекратите выполнение упражнения и обратитесь за консультацией к специалисту.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Agreement Modal -->
    <div class="modal fade" id="userAgreementModal" tabindex="-1" role="dialog" aria-labelledby="userAgreementModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userAgreementModalLabel" style="color: black;">Соглашение об использовании услуг и сервисов</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="color: black;">
                        Пользовательское соглашение представляет собой документ, определяющий условия использования сайта или сервиса, которые пользователь должен принять, чтобы иметь доступ к предоставляемым функциям и возможностям. Ниже приведены основные положения пользовательского соглашения:
                    </p>
                    <ol style="color: black;">
                        <li>Регистрация: Для доступа к определенным функциям сайта или сервиса может потребоваться регистрация пользователя. В рамках регистрации могут запрашиваться определенные персональные данные, которые будут использоваться для создания учетной записи.</li>
                        <li>Использование: Пользовательское соглашение определяет правила использования сайта или сервиса, включая запрет на незаконные действия, распространение вредоносного контента, нарушение прав других пользователей и т. д.</li>
                        <li>Конфиденциальность: Пользовательское соглашение может содержать положения о конфиденциальности информации, предоставленной пользователем, и обязательствах администрации сайта или сервиса по ее защите.</li>
                        <li>Ответственность: В тексте пользовательского соглашения определяются права и обязанности пользователей и администрации, а также условия ответственности сторон за нарушение условий соглашения.</li>
                        <li>Изменения и обновления: Администрация сайта или сервиса оставляет за собой право вносить изменения в пользовательское соглашение без предварительного уведомления пользователей.</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

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
                    <a href="personal_cabinet.php" class="btn btn-secondary">Зарегистрироваться</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для оформления подписки -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subscriptionModalLabel">Оформление подписки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="subscriptionForm" method="post" action="buy_subscription.php">
                        <div class="form-group">
                            <label for="plan_name">Название плана</label>
                            <input type="text" class="form-control" id="plan_name" name="plan_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="price">Цена (руб)</label>
                            <input type="number" class="form-control" id="price" name="price" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Подтвердить покупку</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showPrivacyPolicy() {
            $('#privacyPolicyModal').modal('show');
        }
        function showSafetyRules() {
            $('#safetyRulesModal').modal('show');
        }
        function showUserAgreement() {
            $('#userAgreementModal').modal('show');
        }

        $(document).ready(function() {
            $('.buy-subscription').click(function(e) {
                e.preventDefault();
                <?php if (!isset($_SESSION['user_id'])): ?>
                    $('#loginModal').modal('show');
                <?php else: ?>
                    var planName = $(this).data('plan-name');
                    var price = $(this).data('price');
                    $('#subscriptionModal #plan_name').val(planName);
                    $('#subscriptionModal #price').val(price);
                    $('#subscriptionModal').modal('show');
                <?php endif; ?>
            });
        });
    </script>
</body>
</html>
