<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');

$productList = getProductListWithCategory();
echo json_encode($productList);