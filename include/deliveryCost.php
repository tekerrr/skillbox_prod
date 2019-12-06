<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');

$delivery['cost'] = DELIVERY_PRICE;
$delivery['free'] = DELIVERY_FREE_LIMIT;

echo json_encode($delivery);