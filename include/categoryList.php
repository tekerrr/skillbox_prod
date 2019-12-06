<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/constants.php');
require_once(FOLDER_INCLUDE . '/functions/db.php');

$connection = getConnection();
echo json_encode(getCategoryList());
mysqli_close($connection);
