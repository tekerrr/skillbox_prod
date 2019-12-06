<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');

$connection = getConnection();

$productCounter = countProducts();
$currentPage = getCurrentPage();
$pages = ceil($productCounter['count'] / PRODUCT_PER_PAGE);
if ($currentPage >= $pages) {
	$currentPage = $pages;
}

$productCounter['pages'] = $pages;
$productCounter['currentPage'] = $currentPage;

echo json_encode($productCounter);
mysqli_close($connection);
