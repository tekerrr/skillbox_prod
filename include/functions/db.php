<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');

/**
 * Функция добавляет заказ в DB
 * @return array result
 */
function addOrder() : array {
	$connection = getConnection();
	$post = getRequestForSQL('post');
	$result['success'] = 0;

	if (isOrderEmptyForm($post)) {
		return $result;	
	}

	$cost = getOrderCost((int)$post['prodID']);

	$delivery = $post['delivery'] == 'dev-yes' ? 1 : 0;
	$pay = $post['pay'] == 'cash' ? 1 : 0;

	$format = 'INSERT INTO %1$s 
		SET `product_id`=\'%2$u\',
		`create_time`	=NOW(), 
		`cost`			=\'%3$f\', 
		`first_name`	=\'%4$s\', 
		`last_name`		=\'%5$s\', 
		`phone`			=\'%6$s\', 
		`email`			=\'%7$s\', 
		`delivery`		=%8$u, 
		`cash`			=%9$u';
	$query = sprintf($format, DB_ORDERS_TABLE, $post['prodID'], $cost, $post['name'], 
		$post['surname'], $post['phone'], $post['email'], $delivery, $pay);

	if ($post['thirdName']) {
		$format = ', `middle_name` =\'%s\'';
		$query .= sprintf ($format, $post['thirdName']);
	}

	if ($post['comment']) {
		$format = ', `comment` =\'%s\'';
		$query .= sprintf ($format, $post['comment']);
	}
	$query .= '; ';

	if ($post['delivery'] == 'dev-yes') {
		$format = 'INSERT INTO `%1$s`
		SET `order_id`	=LAST_INSERT_ID(), 
		`city`			=\'%2$s\', 
		`street`		=\'%3$s\', 
		`house`			=\'%4$s\', 
		`apartment`		=\'%5$s\';';
		$query .= sprintf($format, DB_ADDRESSES_TABLE, $post['city'], $post['street'], $post['home'], $post['aprt']);
	}

	if (mysqli_multi_query($connection, $query)) {
		$result['success'] = 1;
	}
	return $result;
}


/**
 * Функция добавления продукта в DB
 * @return array
 */
function addProduct() : array {
	$connection = getConnection();
	$post = getRequestForSQL('post', 'imageBase64');
	$result['success'] = 0;

	$reqForm = ($post['name'] && $post['price'] && $_POST['imageBase64']);
	if (!$reqForm) {
		$result['error'] = 'Заполните все обязательные поля!';
		return $result;
	}

	$price = (double)$post['price'];
	if ($price <= 0) {
		$result['error'] = 'Цена должна быть больше 0!';
		return $result;
	}

	if ($post['id']) {
		$format = 'UPDATE `%1$s` SET `name` =\'%2$s\', `price` =\'%3$s\', `active_flag`=%4$u';
		$query = sprintf($format, DB_PRODUCTS_TABLE, $post['name'], $post['price'], (bool)$post['active']);
		$query .= $post['sale'] ? ', `sale_flag` = 1 ' : ', `sale_flag` = 0 ';
		$query .= $post['new'] ? ', `new_flag` = 1 ' : ', `new_flag` = 0 ';
		$query .= sprintf(' WHERE `product_id`=%u', $post['id']);
	} else {
		$format = 'INSERT INTO `%1$s` SET `name` =\'%2$s\', `price` =\'%3$s\', `active_flag`=%4$u';
		$query = sprintf($format, DB_PRODUCTS_TABLE, $post['name'], $post['price'], (bool)$post['active']);
		$query .= $post['sale'] ? ', `sale_flag` = 1 ' : null;
		$query .= $post['new'] ? ', `new_flag` = 1 ' : null;	
	}


	if (mysqli_query($connection, $query)) {
		if ($post['id']) {
			$id = $post['id'];
		} else {
			$id = mysqli_insert_id($connection);
		}

		$image = saveImage($id);
		if (!$image) {
			deleteProduct($id);
			$result['error'] = 'Ошибка при сохранении изображения!';
			return $result;
		}

		if ($post['id']) {
			$format = 'UPDATE `%1$s` SET `image`=\'%3$s\' WHERE `product_id`=%2$u';
			$query = sprintf($format, DB_IMAGES_TABLE, $post['id'], $image);
		} else {
			$format = 'INSERT INTO `%1$s` SET `product_id`=%2$u, `image`=\'%3$s\'';
			$query = sprintf($format, DB_IMAGES_TABLE, $id, $image);	
		}

		if (!mysqli_query($connection, $query)) {
			deleteProduct($id);
			$result['error'] = 'Ошибка при сохранении изображения!';
			return $result;
		}

		$categories = $post['category'];
		if ($categories) {
			if($post['id']) {
				deleteCategories($id);
			}

			foreach ($categories as $category) {
				$format = 'INSERT INTO `%1$s` SET `product_id`=%2$u, `category_id`=%3$u';
				$query = sprintf($format, DB_PRODUCT_CATEGORY_TABLE, $id, (int)$category);
				if (!mysqli_query($connection, $query)) {
					deleteProduct($id);
					$result['error'] = 'Ошибка при добавлении категории!';
					return $result;
				}
			}
		}
	}
	$result['success'] = 1;
	return $result;
}

/**
 * Функция возвращает продукт из DB
 * @return array|null description
 */
function getProduct() {
	$connection = getConnection();
	$get = getRequestForSQL();

	$id = (int)$get['id'];
	if ($id <= 0) {
		return null;
	}
	
	$format = 'SELECT product_id FROM `%1$s` WHERE `%1$s`.product_id =%2$u LIMIT 1';
	$query = sprintf($format, DB_PRODUCTS_TABLE, $id);
	if (!($result = mysqli_query($connection, $query)) || !($product = mysqli_fetch_assoc($result))) {
		return null;
	}

	$format = 'SELECT `%1$s`.product_id as id, name, price, image, new_flag as new, sale_flag as sale, active_flag as active 
		FROM `%1$s` LEFT JOIN `%2$s` ON `%1$s`.product_id = `%2$s`.product_id 
		WHERE `%1$s`.product_id =%3$u LIMIT 1';
	$query = sprintf($format, DB_PRODUCTS_TABLE, DB_IMAGES_TABLE, $id);

	$product = null;
	if (($result = mysqli_query($connection, $query)) && ($product = mysqli_fetch_assoc($result))) {
		$product['image'] = FOLDER_IMAGE . '/' . $product['image'];

		$format = 'SELECT category_id FROM `%1$s` WHERE product_id =%2$u';
		$query = sprintf($format, DB_PRODUCT_CATEGORY_TABLE, $id);

		if ($result = mysqli_query($connection, $query)) {
			while ($row = mysqli_fetch_assoc($result)) {
				$product['category'][] = $row['category_id'];
			}
		}

	}
	return $product;
}


/**
 * Функция возвращает список заказов из DB
 * @return array|null description
 */
function getOrderList() {
	$connection = getConnection();

	$format = 'SELECT * FROM `%2$s` 
	RIGHT JOIN `%1$s` ON `%2$s`.order_id = `%1$s`.order_id
	ORDER BY `processed_flag` ASC, `create_time` DESC';

	$query = sprintf($format, DB_ORDERS_TABLE, DB_ADDRESSES_TABLE);

	$rows = null;
	if ($result = mysqli_query($connection, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$row['cost'] = getPrice($row['cost']);
			$rows[] = $row;
		}
	}
	return $rows;	
}

/**
 * Функция возвращает список фильтров (категорий) из DB
 * @return array|null description
 */
function getCategoryList() {
	$connection = getConnection();

	$format = 'SELECT category_id as id, name FROM `%s`';
	$query = sprintf($format, DB_CATEGORIES_TABLE);

	$rows = null;
	if ($result = mysqli_query($connection, $query)) {		
		while ($row = mysqli_fetch_assoc($result)) {			
			$rows[] = $row;
		}
	}
	return $rows;
}

/**
 * Функция возвращает список продуктов из DB
 * @return array|null description
 */
function getProductList() {
	$connection = getConnection();

	$format = 'SELECT `%s`.product_id as id, name, price, image ';
	$query = sprintf($format, DB_PRODUCTS_TABLE) . getQueryEnding(true, true, true);

	$rows = null;
	if ($result = mysqli_query($connection, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$row['price'] = getPrice($row['price']);
			$row['image'] = FOLDER_IMAGE . '/' . $row['image'];
			$rows[] = $row;
		}		
	}
	return $rows;
}

/**
 * Функция возвращает список продуктов из DB
 * @return array|null description
 */
function getProductListWithCategory() {
	$connection = getConnection();

	$format = 'SELECT `%s`.product_id as id, name, price, image, new_flag ';
	$query = sprintf($format, DB_PRODUCTS_TABLE) . getQueryEnding(true, true, true);

	$rows = null;
	if ($result = mysqli_query($connection, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$row['price'] = getPrice($row['price']);
			$row['image'] = FOLDER_IMAGE . '/' . $row['image'];

			$format = 'SELECT `name` FROM `%1$s` LEFT JOIN `%2$s` ON `%1$s`.category_id = `%2$s`.category_id 
				WHERE product_id =\'%3$u\'';
			$query = sprintf($format, DB_PRODUCT_CATEGORY_TABLE, DB_CATEGORIES_TABLE, $row['id']);

			if ($result2 = mysqli_query($connection, $query)) {
				$categories = '';
				while ($row2 = mysqli_fetch_assoc($result2)) {
					$categories .= $categories ? ', ' . $row2['name'] : $row2['name'];
				}
				$row['categories'] = $categories;
			}

			$rows[] = $row;
		}		
	}
	return $rows;
}

/**
 * Функция возвращает число продуктов из DB
 * @return array
 */
function countProducts() {
	$connection = getConnection();

	$query = 'SELECT COUNT(*) as count ' . getQueryEnding(true);

	$row['count'] = 0;
	$row['word'] = 'моделей';
	if (($result = mysqli_query($connection, $query)) && ($row = mysqli_fetch_assoc($result))) {
		$row['word'] = getEnding((int)$row['count'], 'модель', 'модели', 'моделей');
	}
	return $row;
}

/**
 * Функция возвращает min/max цену для слайдера из DB
 * @return array description
 */
function getSliderRange() : array {
	$connection = getConnection();

	$slider['min'] = 350;
	$slider['max'] = 32000;
	$slider['lo'] = $slider['min'];
	$slider['hi'] = $slider['max'];

	$format = 'SELECT MIN(price) as min, MAX(price) as max ';
	$query = sprintf($format, DB_PRODUCTS_TABLE) . getQueryEnding();

	if (($result = mysqli_query($connection, $query)) && ($row = mysqli_fetch_assoc($result))) {
		$slider['min'] = (int)$row['min'] > 0 ? $row['min'] : $slider['min'];
		$slider['max'] = (int)$row['max'] > 0 ? $row['max'] : $slider['max'];
		$slider = setCorrectSliderRange($slider);
	}	
	return $slider;
}

/**
 * Функция возвращает окончание запроса для SQL
 * @param bool $price c учетом цены
 * @param bool $sort c учетом сортировки
 * @param bool $page с учетом страниц
 * @return string
 */
function getQueryEnding(bool $price = false, bool $sort = false, bool $page = false) : string {
	$get = getRequestForSQL();
	
	$cat = (int)$get['cat']; // Категория - 'cat'
	if ($cat > 0) {
		$format = 'FROM `%1$s` 
		RIGHT JOIN `%3$s` ON `%1$s`.product_id = `%3$s`.product_id 
		LEFT JOIN `%2$s` ON `%1$s`.product_id = `%2$s`.product_id
			WHERE active_flag = 1 AND category_id = %4$u ';
		$queryEnding = sprintf($format, DB_PRODUCTS_TABLE, DB_IMAGES_TABLE, DB_PRODUCT_CATEGORY_TABLE, $cat);
		$where = 'AND';
	} else {
		$format = 'FROM `%1$s` LEFT JOIN `%2$s` ON `%1$s`.product_id = `%2$s`.product_id 
		WHERE active_flag = 1 ';
		$queryEnding = sprintf($format, DB_PRODUCTS_TABLE, DB_IMAGES_TABLE);
	}

	if ($price) {
		$min = (int)$get['min']; // Минимальная цена - 'min'
		$queryEnding .= $min > 0 ? sprintf('AND price >= %u ', $min) : null;

		$max = (int)$get['max']; // Максимальная цена - 'max'
		$queryEnding .= $max > 0 ? sprintf('AND price <= %u ', $max) : null;		
	}

	$sale = $get['sale']; // Новинка - 'sale'
	$queryEnding .= $sale ? 'AND sale_flag = 1 ' : null;
	
	$new = $get['new']; // Распродажа - 'new'
	$queryEnding .= $new ? 'AND new_flag = 1 ' : null;

	if ($sort) {
		$correctType = ($get['type'] == 'name' || $get['type'] == 'price' || $get['type'] == 'id');
		$type = $correctType ? $get['type'] : 'price'; // Тип сортировки - 'type'
		$order = $get['order'] == 'desc' ? 'DESC' : 'ASC'; // Порядок сортировки - 'order'
		$queryEnding .= sprintf('ORDER BY %1$s %2$s ', $type, $order);
	}

	if ($page) {
		$startProduct = (getCurrentPage() - 1) * PRODUCT_PER_PAGE; // Страница - 'page'
		$queryEnding .= sprintf('LIMIT %1$u, %2$u', $startProduct, PRODUCT_PER_PAGE);
	}

	return $queryEnding;
}

/**
 * Функция получения цены товара 
 * @param int $id
 * @return float
 */
function getOrderCost(int $id) : float {
	$connection = getConnection();

	$format = 'SELECT price FROM `%1$s` WHERE product_id = %2$u';
	$query = sprintf($format, DB_PRODUCTS_TABLE, $id);

	$cost = 0; 
	if (($result = mysqli_query($connection, $query)) && ($row = mysqli_fetch_assoc($result))) {
		$cost = $row['price'] ?? 0;
		if ($cost) {
			$cost += $cost < DELIVERY_FREE_LIMIT ? DELIVERY_PRICE : 0;
		}
	}	
	return $cost;
}

/**
 * Функция удаления продукта (деактивация) в DB
 * @return array
 */
function deactivateProduct() : array {
	$connection = getConnection();
	$get = getRequestForSQL();	
	$result['success'] = 0;

	$format = 'UPDATE `%1$s` SET active_flag=0  WHERE product_id=%2$u';
	$query = sprintf($format, DB_PRODUCTS_TABLE, $get['id']);
	if (mysqli_query($connection, $query)) {
		$result['success'] = 1;
	}
	return $result;
}

/**
 * Функция удаляет товар из DB по ID
 * @param int $id
 */
function deleteProduct(int $id) {
	$connection = getConnection();
	$query = sprintf ('DELETE FROM `%1$s` WHERE product_id = %2$u', DB_PRODUCTS_TABLE, $id);
	mysqli_query($connection, $query);
}

/**
 * Функция удаляет категории товара из DB по ID товара
 * @param int $id
 */
function deleteCategories(int $id) {
	$connection = getConnection();
	$query = sprintf ('DELETE FROM `%1$s` WHERE product_id = %2$u', DB_PRODUCT_CATEGORY_TABLE, $id);
	mysqli_query($connection, $query);
}














/**
 * Функция переключает статус заказа в DB
 * @return array
 */
function toggleOrderStatus() {
	$connection = getConnection();
	$get = getRequestForSQL();	
	$result['success'] = 0;

	$format = 'UPDATE `%1$s` SET processed_flag=%2$u  WHERE order_id=%3$u';
	$query = sprintf($format, DB_ORDERS_TABLE, $get['processed_flag'], $get['id']);
	if (mysqli_query($connection, $query)) {
		$result['success'] = 1;
	}
	return $result;
}

/**
 * Функция сохранения изображения из SRC(в формате base64)
 * @param int $id
 * @return string|bool название изображения
 */
function saveImage(int $id) {
	$result = false;

	$url = explode(',', $_POST['imageBase64']);
	$urlData = explode(';', $url[0]);
	$mimeType = substr($urlData[0], 5);


	$ext = getImageType($mimeType);
	if (!$ext) {
		return $result;
	}

	$fill = str_replace(' ','+',$url[1]);
	$data = base64_decode($fill);

	$name = 'product-' . $id . '.' . $ext;

	if (file_put_contents(UPLOAD_FOLDER . $name, $data)) {
		$result = $name;
	}

	return $result;
}

/**
* Функция возвращает типа загружаемого файла картинке (jpeg, png) 
* @param string $type тип файла в формате mime
* @return string
*/
function getImageType(string $type) : string {
	if ($type == 'image/jpeg' || $type == 'image/pjpeg') {
		return 'jpg';
	} elseif ($type == 'image/png') {
		return 'png';
	}
	return '';
}

/**
 * Функция проверки пустых форм заказа
 * @param array $post
 * @return bool
 */
function isOrderEmptyForm (array $post) : bool {
	$reqKeys = ['prodID', 'surname', 'name', 'phone', 'email', 'delivery', 'pay'];
	$reqKeysAddress = ['city', 'street', 'home', 'aprt'];

	if ($post['delivery'] == 'dev-yes') {
		$reqKeys = array_merge($reqKeys, $reqKeysAddress);
	}

	if (isEmptyElement($post, $reqKeys)) {
		return true;	
	}
	return false;
}

/**
 * Функция проверки пустых значений ассоциативного массива по ключу
 * @param array $array
 * @param array $keys
 * @return bool
 */
function isEmptyElement(array $array, array $keys) : bool {
	foreach($keys as $key) {
		if ($array[$key] === '') {
			return true;
		}
	}
	return false;
}

/**
 * Функция устанавливает корректные значения диапазона слайдера
 * @return array
 */
function setCorrectSliderRange(array $slider) : array {	
	$slider['min'] = floor($slider['min'] / 100) * 100;
	$slider['max'] = ceil($slider['max'] / 100) * 100;
	$slider['lo'] = (int)$_GET['min'];
	$slider['hi'] = (int)$_GET['max'];

	if ($slider['lo'] < $slider['min']) {
		$slider['lo'] = $slider['min'];
	} elseif ($slider['lo'] > $slider['max']) {
		$slider['lo'] = $slider['max'];
	}

	if ($slider['hi'] <= $slider['min'] || $slider['hi'] > $slider['max']) {
		$slider['hi'] = $slider['max'];
	} elseif ($slider['hi'] < $slider['lo']) {
		$slider['hi'] = $slider['lo'];
	}

	return $slider;
}

/**
 * Функция возвращает текущую страницу из GET
 * @return int
 */
function getCurrentPage() : int {
	return (int)$_GET['page'] > 1 ? (int)$_GET['page'] : 1;
}

/**
 * Функция возвращает цену товара в формате 'xxx xxx,xx руб.' или 'xxx xxx руб.' (если число целое)
 * @param numeric $price цена
 * @return string
 */
function getPrice($price) : string {
	$decimals = (double)$price === round($price) ? 0 : 2;
	$price = number_format($price, $decimals, ',', ' ') . ' руб.';
	return $price;
}

/**
 * Функция возвращает возвращает окончение слова в зависимости от числительного
 * @param int $int числительное
 * @param string $str1 вариант написания "один"
 * @param string $str2 вариант написания "четыре"
 * @param string $str3 вариант написания "много"
 * @return string
 */
function getEnding(int $int, string $str1, string $str2, string $str3) {
	$twoDigits = $int % 100;
	if ($twoDigits >= 10 && $twoDigits <= 19) {
		return $str3;
	}
	$oneDigit = $twoDigits % 10;
	if ($oneDigit == 1) {
		return $str1;
	} 
	if ($oneDigit >= 2 && $oneDigit <= 4) {
		return $str2;
	}
	return $str3;
}

/**
 * Функция возвращает подключение к серверу БД (mysqli)
 * @return connection (mysqli)
 */ 
function getConnection() {
	static $connection;
	if ($connection === null) {
		$connection = mysqli_connect(DB_HOST, DB_ADMIN, DB_PASSWORD, DB_NAME)
		or die ('Ошибка' . mysqli_error($connection));
	}	
	return $connection;
}

/**
 * Функция возвращает $_POST в виде безопасный запрос для SQL
 * @return array $_POST
 */ 
function getRequestForSQL($requestType = 'get', $exception = '') : array {
	$connection = getConnection();
	$request = $requestType == 'get' ? $_GET : $_POST;
	if ($exception) {
		unset($request[$exception]);
	}

	$result = getArrayforSQL($request);
	return $result;
}

/**
 * Функция вывода HTML безопасного текста для массива
 * @param array|scalar $array
 * @return array $array|sring
 */
function getArrayforSQL($array) {
	$connection = getConnection();
	if (is_scalar($array)) {
		$array = mysqli_real_escape_string($connection, $array);
	} elseif (is_array($array)) {
		foreach ($array as &$element) {
			$element = getArrayforSQL($element);
		}
	}
	return $array;
}
