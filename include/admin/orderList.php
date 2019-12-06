<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');

$orderList = getOrderList();
echo json_encode($orderList);