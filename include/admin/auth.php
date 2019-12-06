<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');
require_once(FOLDER_INCLUDE . '/functions/auth.php');

auth\startSession();

// Проверка авторизации по sessiom и cookie
$successAuth = (isset($_SESSION['login']) && isset($_COOKIE['login']) && $_COOKIE['login'] === $_SESSION['login']);
$loginOut = (isset($_GET['login']) && $_GET['login'] == 'out');

if(! $loginOut && $successAuth  && isset($allowGroups) && auth\isUsersGroup(...$allowGroups)) {
	setcookie('login', $_SESSION['login'], time() + COOKIE_LIFETIME, '/'); // Обновление cookie на месяц
} else {
	auth\logout();
}
