<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/style.css">
    <script src="./script.js" defer></script>
</head>

<body>
    <header class="header content-section">
        <div class="header__container">
            <a class="header__logo" href="#0">
                <img class="header__logo-img" src="./imeges/1С_ БухОбслуживание.png" alt="-">
            </a>
            <h1 class="header__title">Ведение бухучета в Смоленске и Смоленской области</h1>
            <div class="header__btn-conteiner">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <button class="btn header__btn-register">Зарегистрироваться</button>
                    <button class="btn header__btn-login">Авторизоваться</button>
                <?php else: ?>
                    <img src="./imeges/cart-shopping-fast-svgrepo-com.svg" alt="Корзина" class="cart-icon" style="cursor:pointer;width:40px;height:40px;margin-right:10px;">
                    <p>Добро пожаловать, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
                    <a href="logout.php" class="btn header__btn-exit">Выйти</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="menu">
            <nav class="menu__main">
                <ul class="menu__list">
                    <li class="menu__list-item">
                        <a class="menu__list-item-link menu__list-item-link--about" href="">О нас</a>
                        <div class="menu__list-item-link--drop-info">
                            <p>
                                Мы — компания 1С:БухОбслуживание Простые решения. Живем и работаем в красивом древнем
                                городе Смоленске. Занимаемся автоматизацией предприятий России и Белоруссии,
                                бухгалтерским и юридическим сопровождением бизнеса. Мы используем современные
                                технологии, делаем свою работу хорошо и честно, стараясь найти простые решения для
                                сложных задач.
                                <br>
                                <br>
                                В команде БухОбслуживания 17 человек: руководитель, главные бухгалтеры, бухгалтеры,
                                оператор по вводу данных и менеджер. Такая небольшая команда помогает более 300 клиентам
                                вести учёт точно и без ошибок. Главные показатели регулярно проверяет автоматическая
                                система, а фирма 1С проводит Централизованный аудит и проверяет, как мы ведём учёт для
                                клиентов. Получается двойная проверка качества.
                            </p>
                        </div>
                    </li>
                    <li class="menu__list-item">
                        <a class="menu__list-item-link menu__list-item-link--services" href="">Бухгалтерские услуги</a>
                        <div class="menu__list-item-link--drop-info">
                            <ul class="menu__list-item-link--drop-info-list">
                                <li class="menu__list-item-link--drop-info-list-item">Регистрация ИП</li>
                                <li class="menu__list-item-link--drop-info-list-item">Регистрация ООО</li>
                                <li class="menu__list-item-link--drop-info-list-item">Восстановление бухгалтерского
                                    учёта</li>
                                <li class="menu__list-item-link--drop-info-list-item">Учёт для продавцов на
                                    маркетплейсах</li>
                                <li class="menu__list-item-link--drop-info-list-item">Услуги для граждан Белоруссии:<br>
                                    открытие фирмы, бухобслуживание</li>
                                <li class="menu__list-item-link--drop-info-list-item">Маркировка</li>
                                <li class="menu__list-item-link--drop-info-list-item">Экспресс-аудит бухгалтерского
                                    учёта</li>
                                <li class="menu__list-item-link--drop-info-list-item">Ввод первичных документов</li>
                                <li class="menu__list-item-link--drop-info-list-item">Доступ к программе для учёта</li>
                                <li class="menu__list-item-link--drop-info-list-item">Проверка контрагентов 1СПАРК Риски
                                </li>
                                <li class="menu__list-item-link--drop-info-list-item">Подключение к 1С-ЭДО</li>
                                <li class="menu__list-item-link--drop-info-list-item">Подключение 1С:Распознавание<br>
                                    первичных документов</li>
                                <li class="menu__list-item-link--drop-info-list-item">Аутстаффинг бизнес-ассистентов
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu__list-item">
                        <a class="menu__list-item-link menu__list-item-link--contacts" href="">Контакты</a>
                        <div class="menu__list-item-link--drop-info menu__list-item-link--drop-info--contacts">
                            <div class="menu__list-item-link--image">
                                <img src="./imeges/bff139fa05ac583f685a523ab3d110a0_1_1_1_1_1_1.png" alt="">
                            </div>
                            <div class="header__contacts header__contacts--drop-info">
                                <p class="header__contacts-date">Пн-Пт 09:00 – 18:00</p>
                                <p class="header__contacts-word">Контакты</p>
                                <a class="header__contacts-telephone" href="tel:+99995555555">
                                    +7 (951) 357-651
                                </a>
                                <a class="header__contacts-telephone" href="tel:+99995555555">
                                    +7 (952) 535-61-13
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
            <img class="navigation_loop" src="./imeges/Link.svg" alt="-">
        </div>
    </header>

    <form class="login-form" method="post" action="login.php">
        <div class="login-form__title">Вход</div>
        <?php if (!empty($error)): ?>
        <div class="login-form__error">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        <input class="login-form__input" type="text" name="username" placeholder="Логин" required>
        <input class="login-form__input" type="password" name="password" placeholder="Пароль" required>
        <button class="login-form__btn" type="submit">Войти</button>
    </form>

    <form class="register-form" method="post" action="register.php">
        <div class="register-form__title">Регистрация</div>
        <?php if (!empty($error)): ?>
        <div class="register-form__error">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        <input class="register-form__input" type="text" name="username" placeholder="Имя" required>
        <input class="register-form__input" type="password" name="password" placeholder="Пароль" required>
        <button class="register-form__btn" type="submit">Зарегистрироваться</button>
    </form>

    <main class="main">
        <section class="advantages content-section">
            <form class="advantages-form" action="" method="">
                <h2 class="advantages__form-title">Получить консультацию по
                    телефону</h2>
                <p class="advantages__form-text">Оставьте заявку и в ближайшее время с вами
                    свяжется наш специалист и ответит на
                    вопросы.</p>
                <label for="name">
                    <input class="advantages-form__input--name" placeholder="Ваше имя" type="text" name="name" id="name"
                        required>
                </label>

                <label for="telephone">
                    <input class="advantages-form__input--tel" placeholder="Ваш телефон*" type="tel" name="telephone"
                        id="telephone" required>
                </label>

                <label for="question">
                    <textarea class="advantages-form__input--question" placeholder="Вопрос" name="question"
                        id="question" required></textarea>
                </label>
                <label class="advantages-form__accept">
                    <input type="checkbox" class="advantages-form__input--check visually-hidden">
                    Я согласен на <span class="advantages-form__accept--text">обработку персональных
                        данных.</span>
                </label>
                <button class="btn advantages-form__btn" type="submit">Отправить</button>
            </form>
            <div class="advantages__conteiner-advantages">
                <h2 class="advantages__title">Бухгалтерское сопровождение на аутсорсинге помогает сократить
                    расходы:</h2>
                <ul class="advantages__list">
                    <li class="advantages__list-item">вы выбираете подходящий тариф, и если какие-то бухгалтерские
                        услуги
                        вам нужны не каждый
                        месяц, вы сможете оплатить их разово</li>
                    <li class="advantages__list-item">не нужно оплачивать отпускные, больничные, обустраивать рабочее
                        место
                        — о нашей команде
                        бухгалтеров заботимся мы сами</li>
                    <li class="advantages__list-item">стоимость бухгалтерских услуг будет еще ниже, если вы сами вносите
                        часть документов</li>
                    <li class="advantages__list-item">если понадобятся юридические услуги, тоже поможем: зарегистрируем
                        ИП
                        или ООО, оформим
                        внесение изменений в сведения о юрлице или поможем с ликвидацией</li>
                    <li class="advantages__list-item">вы можете выбрать удобный для вас способ передачи документов: по
                        электронной почте, через
                        мессенджеры или курьером</li>
                </ul>
            </div>
        </section>
        <section class="tariffs content-section">
            <h2 class="tariffs__title">Тарифы бухгалтерского сопровождения</h2>
            <div class="tariffs__cards">
                <div class="tariff-card">
                    <div class="tariff-card__h  eader">Отчетность</div>
                    <div class="tariff-card__desc">
                        Вы самостоятельно вносите в программу все документы, а мы будем рассчитывать налоги и сдавать
                        отчетность.<br><br>
                        Для клиентов с нулевой отчетностью цена будет еще меньше.
                    </div>
                    <div class="tariff-card__price">От 2 600 руб./мес</div>
                    <div class="tariff-card__subtitle">Что делаем мы</div>
                    <ul class="tariff-card__list">
                        <li>предоставляем вам доступ к облачной версии программы 1С:Бухгалтерия 3.0 для одного
                            пользователя</li>
                        <li>предоставляем цифровую подпись для 1С-Отчетности</li>
                        <li>составляем регистры бухгалтерского учета и отчеты об остатках денежных средств по расчетному
                            счету и кассе, состоянии дебиторской и кредиторской задолженности</li>
                        <li>рассчитываем налоги, формируем и своевременно сдаем отчетность</li>
                        <li>информируем вас о суммах и сроках</li>
                        <li>консультируем вас по налогам и учету</li>
                        <li>устанавливаем чат и личный кабинет для взаимодействия с бухгалтером</li>
                    </ul>
                    <button class="btn tariff-card__btn">Добавить в <br>корзину</button>
                </div>
                <div class="tariff-card">
                    <div class="tariff-card__header">Комплексный сервис</div>
                    <div class="tariff-card__desc">
                        Мы ведем бухгалтерский, налоговый, кадровый учет и рассчитываем зарплату для ваших
                        сотрудников.<br><br>
                        На любом тарифе предоставляем доступ к облачной 1С:Бухгалтерии и консультируем по налогам и
                        учету.
                    </div>
                    <div class="tariff-card__price">От 6 100 руб./мес</div>
                    <div class="tariff-card__subtitle">Что делаем мы</div>
                    <ul class="tariff-card__list">
                        <li>предоставляем в аренду одну или несколько лицензий 1С:Бухгалтерия 8</li>
                        <li>изготавливаем цифровую подпись для 1С-Отчетности</li>
                        <li>ведем бухгалтерский и налоговый учет, рассчитываем заработную плату</li>
                        <li>ведем кадровый учет</li>
                        <li>составляем и сдаем отчетность по электронным каналам связи</li>
                        <li>готовим платежные поручения на уплату налогов и сборов</li>
                        <li>собираем, систематизируем и контролируем оформление первичных документов</li>
                        <li>консультируем по бухгалтерскому и налоговому учету</li>
                    </ul>
                    <button class="btn tariff-card__btn">Добавить в <br>корзину</button>
                </div>
            </div>
        </section>

    </main>
    <footer class="footer">
        <div class="footer__main content-section">
            <div class="footer__col">
                <div class="footer__col-title">Компания</div>
                <ul class="footer__list">
                    <li><a href="#">Новости</a></li>
                    <li><a href="#">Сотрудники</a></li>
                    <li><a href="#">Вакансии</a></li>
                    <li><a href="#">Сертификаты</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <div class="footer__col-title">Тарифы ведения учета</div>
                <ul class="footer__list">
                    <li><a href="#">Отчетность</a></li>
                    <li><a href="#">Комплексный сервис</a></li>
                    <li><a href="#">Сравнение тарифов</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <div class="footer__col-title">Услуги</div>
                <ul class="footer__list">
                    <li><a href="#">Регистрация ИП</a></li>
                    <li><a href="#">Регистрация ООО</a></li>
                    <li><a href="#">Услуги для граждан Белоруссии</a></li>
                    <li><a href="#">Учет для маркетплейсов</a></li>
                    <li><a href="#">Ввод первичных документов</a></li>
                    <li><a href="#">Экспресс-аудит бухгалтерского учёта</a></li>
                    <li><a href="#">Восстановление бухгалтерского учёта</a></li>
                    <li><a href="#">Маркировка</a></li>
                    <li><a href="#">Проверка контрагентов 1СПАРК Риски</a></li>
                    <li><a href="#">Доступ к программе для учёта</a></li>
                    <li><a href="#">Подключение к 1С-ЭДО</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <div class="footer__col-title">Наш опыт</div>
                <ul class="footer__list">
                    <li><a href="#">Публикации</a></li>
                    <li><a href="#">Кейсы</a></li>
                    <li><a href="#">Отзывы клиентов</a></li>
                    <li><a href="#">Контакты</a></li>
                </ul>
            </div>
            <div class="footer__col footer__col--contacts">
                <div class="footer__col-title">Контакты</div>
                <div class="footer__contacts-phones">
                    <a href="tel:+74812302604">+7 (4812) 302-604</a><br>
                    <a href="tel:+79525356113">+7 (952) 535-61-13</a>
                </div>
                <div class="footer__contacts-mail">
                    Напишите нам: <a href="mailto:bo@1eska.ru">bo@1eska.ru</a>
                </div>
                <div class="footer__contacts-address">
                    Смоленск, Большая Краснофлотская, д. 34, второй этаж
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <div class="footer__privacy">
                <a href="#">Политика конфиденциальности</a>
            </div>
            <div class="footer__socials">
                <a href="#"><img src="./imeges/VKontakte-custom.svg" alt="VK"></a>
                <a href="#"><img src="./imeges/instagram.svg" alt="Instagram"></a>
                <a href="#"><img src="./imeges/odnoklassniki-svgrepo-com.svg" alt="OK"></a>
            </div>
        </div>
    </footer>

    <!-- Корзина -->
    <div class="cart-modal">
        <div class="cart-modal__content">
            <h2>Корзина</h2>
            <ul class="cart-list"></ul>
            <div class="cart-modal__footer">
                <button class="btn cart-modal__order-btn">Оформить заказ</button>
                <button class="btn cart-modal__close-btn">Закрыть</button>
            </div>
        </div>
    </div>
</body>

</html>