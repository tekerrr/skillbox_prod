<?php
// MySQL version = 5.7.25 (x64)
// define('DB_HOST', '192.168.1.3');
define('DB_HOST', 'localhost');
define('DB_ADMIN', 'prod_admin');
define('DB_PASSWORD', 'prod_admin');
define('DB_NAME', 'prod_8');

define('DB_USERS_TABLE', 'users');
define('DB_GROUPS_TABLE', 'groups');
define('DB_USER_GROUP_TABLE', 'group_user');

define('DB_ORDERS_TABLE', 'orders');
define('DB_ADDRESSES_TABLE', 'addresses');

define('DB_PRODUCTS_TABLE', 'products');
define('DB_CATEGORIES_TABLE', 'сategories');
define('DB_PRODUCT_CATEGORY_TABLE', 'category_product');

define('DB_GROUP_ADMINS', 'admins'); // Группа "Админ"
define('DB_GROUP_OPERATORS', 'operators'); // Группа "Опепаторы"
define('DB_ALLOWED_GROUPS', [DB_GROUP_ADMINS, DB_GROUP_OPERATORS]);
