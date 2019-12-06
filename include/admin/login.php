<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');
require_once(FOLDER_INCLUDE . '/functions/auth.php');

$result['success'] = false;

$connection = getConnection();
auth\startSession();

if (isEmptyForm(['email', 'password'], false)) {
	$result['error'] = 'Заполните все поля!';
} elseif ($UID = auth\getUID()) {
	$_SESSION['UID'] = $UID;
	$_SESSION['login'] = $_POST['email'];
	setcookie('login', $_SESSION['login'], time() + COOKIE_LIFETIME, '/'); // Установка cookie на месяц

	auth\setUserRightsToSession();
	if (! ($result['success'] = auth\isUsersGroup(...DB_ALLOWED_GROUPS))) {
		$result['error'] = 'Недостаточно прав';
	}
} else {
	$result['error'] = 'Ошибка авторизации';
}

echo json_encode($result);
mysqli_close($connection);
