<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');
require_once(FOLDER_INCLUDE . '/functions/auth.php');

$allowGroups = [DB_GROUP_ADMINS, DB_GROUP_OPERATORS];
$result['success'] = 0;

auth\startSession();
if ($UID = auth\login()) {
	$_SESSION['UID'] = $UID;
	$_SESSION['login'] = $_POST['email'];
	auth\setUserRightsToSession();
	setcookie('login', $_SESSION['login'], time() + COOKIE_LIFETIME, '/'); // Установка cookie на месяц
	if (auth\isUsersGroup(...$allowGroups)) {
		$result['success'] = 1;
	} else {
		$result['error'] = 'Недостаточно прав';
	}
} else {
	$result['error'] = 'Ошибка авторизации';
}
echo json_encode($result);