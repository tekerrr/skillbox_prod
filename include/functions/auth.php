<?php
nameSpace auth;

require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');

/**
 * Функция авторизациии
 * @return int UID
 */
function login() : int {
	$UID = 0;
	if ($post['email'] === '' || $post['password'] === '') {
		return $UID;
	}

	$connection = getConnection();
	$login = mysqli_real_escape_string($connection, $_POST['email']);
	$password = $_POST['password'];

	$format = 'SELECT user_id, password_hash as hash FROM `%1$s` WHERE login=\'%2$s\' LIMIT 1';
	$query = sprintf($format, DB_USERS_TABLE, $login);

	if (($result = mysqli_query($connection, $query)) && ($row = mysqli_fetch_assoc($result))) {
		if (password_verify($password, $row['hash'])) {
			$UID = (int)$row['user_id'];
		}
	}
	return $UID;
}

/**
 * Функция получени прав пользователя
 * @return array
 */
function getUserRights() {
	$connection = getConnection();

	$groups = [];

	$format = 'SELECT `name` FROM `%1$s` 
	INNER JOIN `%2$s` ON `%1$s`.group_id = `%2$s`.group_id
	WHERE user_id=%3$u';
	$query = sprintf($format, DB_USER_GROUP_TABLE, DB_GROUPS_TABLE, (int)$_SESSION['UID']);

	if (($result = mysqli_query($connection, $query)) && ($row = mysqli_fetch_assoc($result))) {
		$groups[$row['name']] = 1; 
	}
	return $groups;
}

/**
 * Функция установки прав пользователя в его сессию
 */
function setUserRightsToSession() {
	$rights = getUserRights();	
	$_SESSION['rights'] = $rights;
}

/**
 * Функция проверки вхождения пользователя в группы
 * @return bool
 */
function isUsersGroup(string ...$groups) : bool {
	foreach ($groups as $group) {
		if ($_SESSION['rights'][$group]) {
			return true;
		}
	}
	return false;
}

/**
 * Функция установки и обновления Session
 */
function startSession() {
	session_name(SESSON_NAME); // имя cookie сессии
	ini_set('session.gc_maxlifetime', SESSON_LIFETIME); // установка продолжительности сессии
	session_start(); // пуск сессии
	setcookie(session_name(),session_id(),time() + SESSON_LIFETIME, '/'); // установка продолжительности cookie сессии +обновление
}

/**
 * Функция разавторизациии
 */ 
function logout() {
	session_destroy(); // Удаление сессии
	setcookie('login', '', time() - 3600 * 24 * 30, '/'); // Удаление cookie login
	unset($_SESSION);
	unset($_COOKIE);
	header('Location: ../admin/index.html');
}