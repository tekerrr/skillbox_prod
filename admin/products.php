<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');
$allowGroups = [DB_GROUP_ADMINS];
require_once(FOLDER_INCLUDE . '/admin/auth.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Товары</title>

  <meta name="description" content="Fashion - интернет-магазин">
  <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">

  <meta name="theme-color" content="#393939">

  <link rel="icon" href="/img/favicon.png">
  <link rel="stylesheet" href="/css/style.min.css">

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="/js/scriptsAdmin.js" defer=""></script>
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
        <a class="main-menu__item" href="/admin/orders.php">Заказы</a>
      </li>
      <li>
        <a class="main-menu__item active" href="/admin/products.php">Товары</a>
      </li>
      <li>
        <a class="main-menu__item" href="?login=out">Выйти</a>
      </li>
    </ul>
  </nav>
</header>
<main class="page-products">
  <h1 class="h h--1">Товары</h1>
  <a class="page-products__button button" href="add.php">Добавить товар</a>
  <div class="page-products__header">
    <span class="page-products__header-field">Название товара</span>
    <span class="page-products__header-field">ID</span>
    <span class="page-products__header-field">Цена</span>
    <span class="page-products__header-field">Категория</span>
    <span class="page-products__header-field">Новинка</span>
  </div>
  <ul class="page-products__list">
  </ul>
  <section class="shop__list">
  </section>
  <ul class="shop__paginator paginator">
    <li>
      <a class="paginator__item">1</a>
    </li>
    <li>
      <a class="paginator__item" href="?page=2">2</a>
    </li>
  </ul>
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
          <a class="main-menu__item" href="/delivery.php">Доставка</a>
        </li>
      </ul>
    </nav>
    <address class="page-footer__copyright">
      © Все права защищены
    </address>
  </div>
<template id="page-products__list">
  <li class="product-item page-products__item">
    <b class="product-item__name" id="name">Туфли черные</b>
    <span class="product-item__field" id="product_id">235454345</span>
    <span class="product-item__field" id="price">2 500 руб.</span>
    <span class="product-item__field" id="categories">Женщины</span>
    <span class="product-item__field" id="new_flag">Да</span>
    <a href="add.php" class="product-item__edit" aria-label="Редактировать"></a>
    <button class="product-item__delete"></button>
  </li>
</template>
</footer>
</body>
</html>
