<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/auth.php');

auth\startSession();
$userRights = auth\getUserRights();
echo json_encode($userRights);
