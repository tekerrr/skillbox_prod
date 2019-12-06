<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Доставка</title>

  <meta name="description" content="Fashion - интернет-магазин">
  <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">

  <meta name="theme-color" content="#393939">

  <link rel="icon" href="/img/favicon.png">
  <link rel="stylesheet" href="/css/style.min.css">
</head>
<body>
<header class="page-header">
  <a class="page-header__logo" href="#">
    <img src="/img/logo.svg" alt="Fashion">
  </a>
  <nav class="page-header__menu">
    <ul class="main-menu main-menu--header">
      <li>
        <a class="main-menu__item" href="/index.php">Главная</a>
      </li>
      <li>
        <a class="main-menu__item" href="/index.php?new=1">Новинки</a>
      </li>
      <li>
        <a class="main-menu__item" href="/index.php?sale=1">Sale</a>
      </li>
      <li>
        <a class="main-menu__item active">Доставка</a>
      </li>
    </ul>
  </nav>
</header>
<main class="page-delivery">
  <h1 class="h h--1">Доставка</h1>
  <p class="page-delivery__desc">
    Способы доставки могут изменяться взависимости от адреса доставки, времени осуществления покупки и наличия товаров.
  </p>
  <p class="page-delivery__desc page-delivery__desc--strong">
    <b>При оформлении покупки мы проинформируем вас о доступных способах доставки, стоимости и дате доставки вашего заказа.</b>
  </p>
  <section class="page-delivery__info">
    <header class="page-delivery__desc">
      Возможные варианты доставки:
      <b class="page-delivery__variant">Доставка на дом:</b>
    </header>
    <ul class="page-delivery__list">
      <li>
        <b class="page-delivery__item-title">Стандартная доставка - <?=DELIVERY_PRICE?> РУБ / БЕСПЛАТНО (ДЛЯ ЗАКАЗОВ ОТ <?=DELIVERY_FREE_LIMIT?> РУБ)</b>
        <p class="page-delivery__item-desc">
          Примерный срок доставки составит около 2-7 рабочих дней, в зависимости от адреса доставки.
        </p>
      </li>
      <li>
        <b class="page-delivery__item-title">Доставка с примеркой перед покупкой по Москве - <?=DELIVERY_PRICE?> РУБ / БЕСПЛАТНО (ПРИ ВЫКУПЕ НА СУММУ ОТ <?=DELIVERY_FREE_LIMIT?> РУБ)</b>
        <p class="page-delivery__item-desc">
          Доставка возможна только по Москве (в пределах МКАД) в течение 2-3 дней.
          Воспользовавшись услугой «Примерка перед покупкой», вы можете получить свой заказ и примерить заказанные товары. Вы оплачиваете только то, что вам подошло. Максимальное количество позиций в заказе, при котором доступна примерка, составляет 10 вещей. Время на примерку одного заказа – 15 минут.
        </p>
      </li>
    </ul>
    <p class="page-delivery__desc">
      Мы свяжемся с вами, чтобы подтвердить дату и время доставки. Кроме того, вы будете получать уведомления по электронной почте и SMS с информацией о номере заказа, его стоимости, а также с информацией о том, что заказ готов к выдаче. В день доставки заказа мы отправим вам SMS-уведомлениес номером телефона сотрудника службы доставки.
    </p>
    <a class="page-delivery__button button" href="index.php">Продолжить покупки</a>
  </section>
</main>
<footer class="page-footer">
  <div class="container">
    <a class="page-footer__logo" href="#">
      <img src="/img/logo--footer.svg" alt="Fashion">
    </a>
    <nav class="page-footer__menu">
      <ul class="main-menu main-menu--footer">
        <li>
          <a class="main-menu__item" href="/index.php">Главная</a>
        </li>
        <li>
          <a class="main-menu__item" href="/index.php?new=1">Новинки</a>
        </li>
        <li>
          <a class="main-menu__item" href="/index.php?sale=1">Sale</a>
        </li>
        <li>
          <a class="main-menu__item">Доставка</a>
        </li>
      </ul>
    </nav>
    <address class="page-footer__copyright">
      © Все права защищены
    </address>
  </div>
</footer>
</body>
</html>
