<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');
require_once(FOLDER_INCLUDE . '/functions/auth.php');

$allowGroups = [DB_GROUP_ADMINS, DB_GROUP_OPERATORS];

auth\startSession();

// Проверка авторизации по sessiom и cookie
$successAuth = ($_SESSION['login'] && $_COOKIE['login'] && $_COOKIE['login'] == $_SESSION['login']);

if($_GET['login'] != 'out' && $successAuth  && auth\isUsersGroup(...$allowGroups)) {	
	setcookie('login', $_SESSION['login'], time() + COOKIE_LIFETIME, '/'); // Обновление cookie на месяц	
} else {
	auth\logout();
}
