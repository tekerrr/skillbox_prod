<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/configDB.php');

/**
 * Функция добавляет заказ в DB
 * @return array result
 */
function addOrder() : array {
	$result['success'] = false;

	$reqKeys = ['prodID', 'surname', 'name', 'phone', 'email', 'delivery', 'pay'];
	if ($post['delivery'] == 'dev-yes') {
		$reqKeys = array_merge($reqKeys, ['city', 'street', 'home', 'aprt']);
	}
	if (isEmptyForm($reqKeys, false)) {
		return $result;
	}

	$post = getRequestForSQL('post');

	$delivery = $post['delivery'] == 'dev-yes';
	$cash = $post['pay'] == 'cash';
	$cost = getOrderCost((int) $post['prodID'], $delivery);

	$format = 'INSERT INTO %1$s
		SET product_id	=\'%2$u\',
		create_time		=NOW(),
		cost			=\'%3$f\',
		first_name		=\'%4$s\',
		last_name		=\'%5$s\',
		phone			=\'%6$s\',
		email			=\'%7$s\',
		delivery		=%8$u,
		cash			=%9$u';
	$query = sprintf($format, DB_ORDERS_TABLE, $post['prodID'], $cost, $post['name'],
		$post['surname'], $post['phone'], $post['email'], $delivery, $cash);

	$query .= $post['thirdName'] ? sprintf(', middle_name =\'%s\'', $post['thirdName']) : '';
	$query .= $post['comment'] ? sprintf(', comment =\'%s\'', $post['comment']) : '';
	$query .= '; ';

	if ($delivery) {
		$format = 'INSERT INTO %1$s
			SET id		=LAST_INSERT_ID(),
			city		=\'%2$s\',
			street		=\'%3$s\',
			house		=\'%4$s\',
			apartment	=\'%5$s\';';
		$query .= sprintf($format, DB_ADDRESSES_TABLE, $post['city'], $post['street'], $post['home'], $post['aprt']);
	}

	if (mysqli_multi_query(getConnection(), $query)) {
		$result['success'] = true;
	}
	return $result;
}

/**
 * Функция добавления продукта в DB
 * @return array
 */
function addProduct() : array {
	$result['success'] = false;

	if (isEmptyForm(['name', 'price', 'imageBase64'], false)) {
		$result['error'] = 'Заполните все обязательные поля!';
		return $result;
	}

	if (((double) $_POST['price']) <= 0) {
		$result['error'] = 'Цена должна быть больше 0!';
		return $result;
	}

	$connection = getConnection();
	$post = getRequestForSQL('post', 'imageBase64');

	$id = $post['id'] ?? 0;

	$query = $id ? 'UPDATE ' : 'INSERT INTO ';
	$format = '%1$s SET `name` =\'%2$s\', price =\'%3$s\', new_flag=%4$u, sale_flag=%5$u, active_flag=%6$u';
	$query .= sprintf($format, DB_PRODUCTS_TABLE, $post['name'], (double) $post['price'],
		isset($post['new']), isset($post['sale']), isset($post['active']));
	$query .= $id ? sprintf(' WHERE `id`=%u', $post['id']) : '';

	if (! mysqli_query($connection, $query)) {
		$result['error'] = 'Ошибка при добавлении товара!';
		return $result;
	}

	$id = $id ?: mysqli_insert_id($connection);

	$image = saveImage($id);
	$format = 'UPDATE %1$s SET image=\'%3$s\' WHERE id=%2$u';
	$query = sprintf($format, DB_PRODUCTS_TABLE, $id, $image);
	if (! $image || ! mysqli_query($connection, $query)) {
		deleteProduct($id);
		$result['error'] = 'Ошибка при сохранении изображения!';
		return $result;
	}

	if (isset($post['id'])) {
		deleteCategories($id);
	}
	if (isset($post['category'])) {
		$query = '';
		foreach ($post['category'] as $category_id) {
			$format = 'INSERT INTO %1$s SET product_id=%2$u, category_id=%3$u; ';
			$query .= sprintf($format, DB_PRODUCT_CATEGORY_TABLE, $id, $category_id);
		}
		if (! mysqli_multi_query($connection, $query)) {
			deleteProduct($id);
			$result['error'] = 'Ошибка при добавлении категории!';
			return $result;
		}
	}

	$result['success'] = true;
	return $result;
}

/**
 * Функция возвращает продукт из DB
 * @return array description
 */
function getProduct() {
	$product['error'] = true;

	$id = (int) ($_GET['id'] ?? 0);
	if ($id <= 0) {
		return $product;
	}

	$connection = getConnection();
	$format = 'SELECT id, name, price, image, new_flag as new, sale_flag as sale, active_flag as active
		FROM %1$s WHERE id =%2$u LIMIT 1';
	$query = sprintf($format, DB_PRODUCTS_TABLE, $id);

	if (($result = mysqli_query($connection, $query)) && ($row = mysqli_fetch_assoc($result))) {
		$row['image'] = FOLDER_IMAGE . $row['image'];

		$format = 'SELECT category_id FROM %1$s WHERE product_id =%2$u';
		$query = sprintf($format, DB_PRODUCT_CATEGORY_TABLE, $id);
		if ($result = mysqli_query($connection, $query)) {
			while ($category = mysqli_fetch_assoc($result)) {
				$row['category'][] = $category['category_id'];
			}
		}
		$product = $row;
	}
	return $product;
}

/**
 * Функция возвращает список заказов из DB
 * @return array description
 */
function getOrderList() {
	$format = 'SELECT %1$s.*, city, street, house, apartment FROM %1$s
		LEFT JOIN %2$s ON %1$s.id = %2$s.id
		ORDER BY processed_flag ASC, create_time DESC';
	$query = sprintf($format, DB_ORDERS_TABLE, DB_ADDRESSES_TABLE);

	$list = [];
	if ($result = mysqli_query(getConnection(), $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$row['cost'] = getPrice($row['cost']);
			$list[] = $row;
		}
	}
	return $list;
}

/**
 * Функция возвращает список фильтров (категорий) из DB
 * @return array description
 */
function getCategoryList() {
	$format = 'SELECT id, name FROM %s';
	$query = sprintf($format, DB_CATEGORIES_TABLE);

	$list = [];
	if ($result = mysqli_query(getConnection(), $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$list[] = $row;
		}
	}
	return $list;
}

/**
 * Функция возвращает список продуктов из DB
 * @return array description
 */
function getProductList() {
	$format = 'SELECT %s.id, name, price, image ';
	$query = sprintf($format, DB_PRODUCTS_TABLE) . getQueryEnding(true, true, true);

	$list = [];
	if ($result = mysqli_query(getConnection(), $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$row['price'] = getPrice($row['price']);
			$row['image'] = FOLDER_IMAGE . $row['image'];
			$list[] = $row;
		}
	}
	return $list;
}

/**
 * Функция возвращает список продуктов из DB
 * @return array|null description
 */
function getProductListWithCategory() {
	$connection = getConnection();

	$format = 'SELECT %s.id, name, price, image, new_flag ';
	$query = sprintf($format, DB_PRODUCTS_TABLE) . getQueryEnding(true, true, true);

	$list = [];
	if ($result = mysqli_query($connection, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$row['price'] = getPrice($row['price']);
			$row['image'] = FOLDER_IMAGE . $row['image'];

			$format = 'SELECT `name` FROM %1$s LEFT JOIN %2$s ON %1$s.category_id = %2$s.id
				WHERE product_id =\'%3$u\'';
			$query = sprintf($format, DB_PRODUCT_CATEGORY_TABLE, DB_CATEGORIES_TABLE, $row['id']);

			if ($result2 = mysqli_query($connection, $query)) {
				$row['categories'] = '';
				while ($row2 = mysqli_fetch_assoc($result2)) {
					$row['categories'] .= $row['categories'] ? ', ' . $row2['name'] : $row2['name'];
				}
			}
			$list[] = $row;
		}
	}
	return $list;
}

/**
 * Функция возвращает число продуктов из DB
 * @return array
 */
function countProducts() {
	$count = ['count' => 0, 'word' => 'моделей'];

	$query = 'SELECT COUNT(*) as count ' . getQueryEnding(true);
	if (($result = mysqli_query(getConnection(), $query)) && ($count = mysqli_fetch_assoc($result))) {
		$count['word'] = getEnding((int) $count['count'], 'модель', 'модели', 'моделей');
	}
	return $count;
}

/**
 * Функция возвращает min/max цену для слайдера из DB
 * @return array description
 */
function getSliderRange() : array {
	$slider['min'] = 350;
	$slider['max'] = 32000;
	$slider['lo'] = $slider['min'];
	$slider['hi'] = $slider['max'];

	$format = 'SELECT MIN(price) as min, MAX(price) as max ';
	$query = sprintf($format, DB_PRODUCTS_TABLE) . getQueryEnding();

	if (($result = mysqli_query(getConnection(), $query)) && ($row = mysqli_fetch_assoc($result))) {
		$slider['min'] = (int) $row['min'] > 0 ? $row['min'] : $slider['min'];
		$slider['max'] = (int) $row['max'] > 0 ? $row['max'] : $slider['max'];
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

	$cat = (int) ($get['cat'] ?? 0); // Категория - 'cat'
	if ($cat) {
		$format = 'FROM %1$s
			RIGHT JOIN %2$s ON %1$s.id = %2$s.product_id
			WHERE active_flag = 1 AND category_id = %3$u ';
		$queryEnding = sprintf($format, DB_PRODUCTS_TABLE, DB_PRODUCT_CATEGORY_TABLE, $cat);
	} else {
		$format = 'FROM %1$s WHERE active_flag = 1 ';
		$queryEnding = sprintf($format, DB_PRODUCTS_TABLE);
	}

	if ($price) {
		$min = (int) ($get['min'] ?? 0); // Минимальная цена - 'min'
		$queryEnding .= $min > 0 ? sprintf('AND price >= %u ', $min) : '';

		$max = (int) ($get['max'] ?? 0); // Максимальная цена - 'max'
		$queryEnding .= $max > 0 ? sprintf('AND price <= %u ', $max) : '';
	}

	$queryEnding .= (isset($get['sale']) && $get['sale'] === '1') ? 'AND sale_flag = 1 ' : ''; // Новинка - 'sale'
	$queryEnding .= (isset($get['new']) && $get['new'] === '1') ? 'AND new_flag = 1 ' : ''; // Распродажа - 'new'

	if ($sort) {
		$type = (isset($get['type']) && in_array($get['type'], ['name', 'price', 'id'])) ? $get['type'] : 'price'; // Тип сортировки - 'type'
		$order = (isset($get['order']) && ($get['order'] == 'desc')) ? 'DESC' : 'ASC'; // Порядок сортировки - 'order'
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
 * @param bool $delivery
 * @return float
 */
function getOrderCost(int $id, bool $delivery = true) : float {
	$format = 'SELECT price FROM %1$s WHERE id = %2$u';
	$query = sprintf($format, DB_PRODUCTS_TABLE, $id);

	$cost = 0;
	if (($result = mysqli_query(getConnection(), $query)) && ($row = mysqli_fetch_assoc($result))) {
		$cost = $row['price'] ?? 0;
		if ($delivery && $cost) {
			$cost += getDeliveryCost($cost);
		}
	}
	return $cost;
}

/**
 * Функция получения цены доставки
 * @param double $productPrice
 * @return float
 */
function getDeliveryCost(float $productPrice) : float {
	return $productPrice < DELIVERY_FREE_LIMIT ? DELIVERY_PRICE : 0;
}

/**
 * Функция удаления продукта (деактивация) в DB
 * @return array
 */
function deactivateProduct() : array {
	$result['success'] = false;

	$format = 'UPDATE %1$s SET active_flag=0  WHERE id=%2$u';
	$query = sprintf($format, DB_PRODUCTS_TABLE, $_GET['id'] ?? 0);
	if (mysqli_query(getConnection(), $query)) {
		$result['success'] = true;
	}
	return $result;
}

/**
 * Функция удаляет товар из DB по ID
 * @param int $id
 */
function deleteProduct(int $id) {
	$query = sprintf('DELETE FROM %1$s WHERE id = %2$u', DB_PRODUCTS_TABLE, $id);
	mysqli_query(getConnection(), $query);
}

/**
 * Функция удаляет категории товара из DB по ID товара
 * @param int $id
 */
function deleteCategories(int $id) {
	$query = sprintf('DELETE FROM %1$s WHERE product_id = %2$u', DB_PRODUCT_CATEGORY_TABLE, $id);
	mysqli_query(getConnection(), $query);
}

/**
 * Функция переключает статус заказа в DB
 * @return array
 */
function toggleOrderStatus() : array {
	$result['success'] = 0;

	$format = 'UPDATE `%1$s` SET processed_flag=%2$u  WHERE id=%3$u';
	$query = sprintf($format, DB_ORDERS_TABLE, $_GET['processed_flag'], $_GET['id']);
	if (mysqli_query(getConnection(), $query)) {
		$result['success'] = 1;
	}
	return $result;
}

/**
 * Функция сохранения изображения из SRC(в формате base64)
 * @param int $id
 * @return string название изображения
 */
function saveImage(int $id) : string {
	$result = '';

	$url = explode(',', $_POST['imageBase64']);
	$urlData = explode(';', $url[0]);
	$mimeType = substr($urlData[0], 5);

	if (! ($ext = getImageType($mimeType))) {
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
 * Функция проверки заполененсти формы POST
 * @param array $keys
 * @param bool $withGet all(GET и POST) или post
 * @return bool true|false
 */
function isEmptyForm(array $keys, bool $requestWithGet = true) : bool {
	$request = $requestWithGet ? $_REQUEST : $_POST;
	foreach($keys as $key) {
		if (!isset($request[$key]) || $request[$key] === '') {
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
	$slider['lo'] = (int) $_GET['min'];
	$slider['hi'] = (int) $_GET['max'];

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
	$page = (int) ($_GET['page'] ?? 1);
	return $page > 1 ? $page : 1;
}

/**
 * Функция возвращает цену товара в формате 'xxx xxx,xx руб.' или 'xxx xxx руб.' (если число целое)
 * @param numeric $price цена
 * @return string
 */
function getPrice($price) : string {
	$decimals = (double) $price === round($price) ? 0 : 2;
	return number_format($price, $decimals, ',', ' ') . ' руб.';
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
	$request = $requestType == 'get' ? $_GET : $_POST;
	if ($exception) {
		unset($request[$exception]);
	}

	return getArrayforSQL($request);
}

/**
 * Функция получения SQL безопасного текста для массива
 * @param array|scalar $array
 * @return array|sring $array
 */
function getArrayforSQL($array) {
	if (is_scalar($array)) {
		$array = mysqli_real_escape_string(getConnection(), $array);
	} elseif (is_array($array)) {
		foreach ($array as &$element) {
			$element = getArrayforSQL($element);
		}
	}
	return $array;
}
