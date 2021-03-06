<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Авторизация</title>

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
</header>
<main class="page-authorization">
  <h1 class="h h--1">Авторизация</h1>
  <form class="custom-form" method="POST">
    <label class="custom-form__input-wrapper" for="email">
      <input type="email" id="email" class="custom-form__input" name="email" autocomplete="username email">
      <p class="custom-form__input-label">E-mail <span class="req">*</span></p>
    </label>
    <label class="custom-form__input-wrapper" for="email">
      <input type="password" id="password" class="custom-form__input" name="password" autocomplete="current-password">
      <p class="custom-form__input-label">Пароль <span class="req">*</span></p>
    </label>
    <button class="button" type="submit">Войти в личный кабинет</button>
  </form>
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
</footer>
</body>
</html>
