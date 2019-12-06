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
  <title>Добавление товара</title>

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
        <a class="main-menu__item" href="orders.php">Заказы</a>
      </li>
      <li>
        <a class="main-menu__item" href="products.php">Товары</a>
      </li>
      <li>
        <a class="main-menu__item" href="?login=out">Выйти</a>
      </li>
    </ul>
  </nav>
</header>
<main class="page-add">
  <h1 class="h h--1">Добавление товара</h1>
  <form class="custom-form" method="POST">
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
      <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
        <input type="text" class="custom-form__input" name="name" id="product-name">
        <p class="custom-form__input-label">Название товара <span class="req">*</span></p>
      </label>
      <label for="product-price" class="custom-form__input-wrapper">
        <input type="text" class="custom-form__input" name="price" id="product-price">
        <p class="custom-form__input-label">Цена товара <span class="req">*</span></p>
      </label>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
      <ul class="add-list">
        <li class="add-list__item add-list__item--add">
          <input type="file" name="product-photo" id="product-photo" hidden="">
          <label for="product-photo">Добавить фотографию <span class="req">*</span></label>
        </li>
      </ul>
    </fieldset>    
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Раздел</legend>
      <div class="page-add__select">
        <select name="category[]" class="custom-form__select" multiple="multiple">
          <option hidden="">Название раздела</option>
        </select>
      </div>
      <input type="checkbox" name="new" id="new" class="custom-form__checkbox">
      <label for="new" class="custom-form__checkbox-label">Новинка</label>
      <input type="checkbox" name="sale" id="sale" class="custom-form__checkbox">
      <label for="sale" class="custom-form__checkbox-label">Распродажа</label>
      <input type="checkbox" name="active" id="active" class="custom-form__checkbox" checked>
      <label for="active" class="custom-form__checkbox-label">Активен</label>
    </fieldset>
    <button class="button" type="submit">Добавить товар</button>
  </form>
  <section class="shop-page__popup-end page-add__popup-end" hidden="">
    <div class="shop-page__wrapper shop-page__wrapper--popup-end">
      <h2 class="h h--1 h--icon shop-page__end-title">Товар успешно добавлен</h2>
    </div>
  </section>
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

</body>
</html>
