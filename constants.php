<?php
define('FOLDER_INCLUDE', $_SERVER['DOCUMENT_ROOT'] . '/include');
define('FOLDER_TEMPLATE', $_SERVER['DOCUMENT_ROOT'] . '/template');
define('FOLDER_IMAGE', '/img/products/');
define('UPLOAD_FOLDER', $_SERVER['DOCUMENT_ROOT'] . FOLDER_IMAGE);

// Cookie, Session
define('SESSON_NAME', 'session_id');
define('SESSON_LIFETIME', 60 * 60);
define('COOKIE_LIFETIME', 3600 * 24 * 30);

// Paginator
define('PRODUCT_PER_PAGE', 9);

// Delivery
define('DELIVERY_PRICE', 280);
define('DELIVERY_FREE_LIMIT', 2000);

//URL
define('URL_ADMIN_INDEX', '/admin/index.php');