<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');

$connection = getConnection();
$price['product'] = getOrderCost((int) $_GET['prodID'], false);
$price['delivery'] = getDeliveryCost($price['product']);
echo json_encode($price);
mysqli_close($connection);