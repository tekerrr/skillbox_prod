<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');

$result = toggleOrderStatus();
echo json_encode($result);