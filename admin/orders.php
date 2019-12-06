<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');
$allowGroups = [DB_GROUP_ADMINS, DB_GROUP_OPERATORS];
require_once(FOLDER_INCLUDE . '/admin/auth.php');
?><!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Список заказов</title>

  <meta name="description" content="Fashion - интернет-магазин">
  <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">

  <meta name="theme-color" content="#393939">

  <link rel="icon" href="../img/favicon.png">
  <link rel="stylesheet" href="../css/style.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="../js/scriptsAdmin.js" defer=""></script>
</head>
<body>
<header class="page-header">
  <a class="page-header__logo" href="#">
    <img src="../img/logo.svg" alt="Fashion">
  </a>
  <nav class="page-header__menu">
    <ul class="main-menu main-menu--header">
      <li>
        <a class="main-menu__item" href="../index.php">Главная</a>
      </li>
      <li>
        <a class="main-menu__item active" href="orders.php">Заказы</a>
      </li>      
      <li>
        <a class="main-menu__item" href="?login=out">Выйти</a>
      </li>
    </ul>
  </nav>
</header>
<main class="page-order">
  <h1 class="h h--1">Список заказов</h1>
  <ul class="page-order__list">    
  </ul>
</main>
<footer class="page-footer">
  <div class="container">
    <a class="page-footer__logo" href="#">
      <img src="../img/logo--footer.svg" alt="Fashion">
    </a>
    <nav class="page-footer__menu">
      <ul class="main-menu main-menu--footer">
        <li>
          <a class="main-menu__item" href="../index.php">Главная</a>
        </li>
        <li>
          <a class="main-menu__item" href="../index.php?new=1">Новинки</a>
        </li>
        <li>
          <a class="main-menu__item" href="../index.php?sale=1">Sale</a>
        </li>
        <li>
          <a class="main-menu__item" href="../delivery.php">Доставка</a>
        </li>
      </ul>
    </nav>
    <address class="page-footer__copyright">
      © Все права защищены
    </address>
  </div>
</footer>
<template id="page-order__list">
  <li class="order-item page-order__item">
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--id">
          <span class="order-item__title">Номер заказа</span>
          <span class="order-item__info order-item__info--id" id="order_id">235454345</span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Сумма заказа</span>
          <span id="cost">10 400 руб.</span>
        </div>
        <button class="order-item__toggle"></button>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Номер товара</span>
          <span class="order-item__info order-item__info--id" id="product_id">235454345</span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Дата создания заказа</span>
          <span class="order-item__info" id="create_time">10 400 руб.</span>
        </div>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--margin">
          <span class="order-item__title">Заказчик</span>
          <span class="order-item__info" id="full_name">Смирнов Павел Владимирович</span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Номер телефона</span>
          <span class="order-item__info" id="phone">+7 987 654 32 10</span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ доставки</span>
          <span class="order-item__info" id="delivery">Самовывоз</span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ оплаты</span>
          <span class="order-item__info" id="cash">Наличными</span>
        </div>
        <div class="order-item__group order-item__group--status">
          <span class="order-item__title">Статус заказа</span>
          <span class="order-item__info order-item__info--no">Не выполнено</span>
          <button class="order-item__btn">Изменить</button>
        </div>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Адрес доставки</span>
          <span class="order-item__info" id="address">г. Москва, ул. Пушкина, д.5, кв. 233</span>
        </div>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Комментарий к заказу</span>
          <span class="order-item__info" id="comment">Далеко-далеко за словесными горами в стране гласных и согласных живут рыбные тексты. Вдали от всех живут они в буквенных домах на берегу.</span>
        </div>
      </div>
    </li> 
</template>
</body>
</html>