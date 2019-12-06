<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/auth.php');

auth\startSession();
$connection = getConnection();
echo json_encode(auth\getUserRights());
mysqli_close($connection);
