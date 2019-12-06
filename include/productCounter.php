<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');

$productCounter = countProducts();
$pages = ceil($productCounter['count'] / PRODUCT_PER_PAGE);
$currentPage = getCurrentPage();
if ($currentPage >= $pages) {
	$currentPage = $pages;
}

$productCounter['pages'] = $pages;
$productCounter['currentPage'] = $currentPage;
echo json_encode($productCounter);