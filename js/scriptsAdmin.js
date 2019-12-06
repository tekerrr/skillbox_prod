'use strict';

  ///////////////////
 // Общие функции //
///////////////////

// Функция однократного вывод сообщения об ошибки
const alertOnce = (message) => {
  if (!alertOnce.count) {
    alert(message);
  }

  alertOnce.count = 1;
}

// Футкция "Увядание"
const fade = (element) => {
  element.classList.add('fade');
  setTimeout(() => {
    element.classList.remove('fade');
  }, 1000);
}

// Функция сокрытия label формы input при потери фокуса при наличии введенного текста
// label располагается непосредтсвенно в форме input как подсказка
const labelHidden = (form) => {
  form.addEventListener('focusout', (evt) => {
    const field = evt.target;
    const label = field.nextElementSibling;

    if (field.tagName === 'INPUT' && field.value && label) {
      label.hidden = true;
    } else if (label) {
      label.hidden = false;
    }
  });
};

// Функция сокрытия label формы input (для формы авторизации) при потери фокуса при наличии введенного текста
// label располагается непосредтсвенно в форме input как подсказка
const labelHiddenLogin = (form) => {
  form.addEventListener('change', (evt) => {
    const field = evt.target;
    const label = field.nextElementSibling;

    if (field.tagName === 'INPUT' && field.value && label) {
      label.hidden = true;
    } else if (label) {
      label.hidden = false;
    }
  });
};

const addError = (text) => {
  var error = document.querySelector('h2.error');
  if (!error) {
    error = document.createElement('h2');
    error.classList.add('error');
    error.style.color = '#e45446';
    document.querySelector('main').appendChild(error);
  }
  error.textContent = text;
  fade(error);
}

  ////////////////////////////
 // Авторизация index.php //
////////////////////////////

  /////////////////////////
 // Асинхронные Функции //
/////////////////////////

// Функция авторизации
const login = () => {
  // AJAX
  $.post('/include/admin/login.php', $('.custom-form').serialize())
    .fail(function() {
      alertOnce('Ошибка загрузки данных');
    })
    .done(function(data) {
      var result = JSON.parse(data);
      if (result.success) {
        document.location.href = '/admin/orders.php';
      } else {
        addError(result.error);
      }
    });
}

  ////////////
 // Скрипт //
////////////

const authPage = document.querySelector('.page-authorization');
if (authPage) {
  const form = authPage.querySelector('.custom-form');
  labelHiddenLogin(form);

  form.addEventListener('submit', (evt) => {
    evt.preventDefault();
    login();
  })
}

  ///////////////////////////////
 // Список заказов order.php //
///////////////////////////////

  /////////////
 // Функции //
/////////////

// Фукнция возвращает элемент главного меню
const getMainMenuElement = (text, href) => {
  var element = document.createElement('li');
  var a = document.createElement('a');

  a.classList.add('main-menu__item');
  a.href = href;
  a.textContent = text;

  element.appendChild(a);
  return element;
}

// Функция создания элемента "Товары" главного меню
const createProductElemenetMenu = () => {
  const element = getMainMenuElement('Товары', 'products.php');
  var mainMenu = document.querySelector('.main-menu--header');
  mainMenu.insertBefore(element, mainMenu.children[2]);
}

// Функция изменяет textContent элемента с удалением id
const changeElementById = (parent, id, text) => {
  var element = parent.querySelector('#' + id);
  element.textContent = text;
  element.removeAttribute('id');
}

// Фукнция возвращает элемент для списка заказов
const getOrderListElement = (order, template) => {
  var card = template.cloneNode(true);

  const status = card.querySelector('.order-item__info--no');
  status.id = 'orderID__' + order.id;

  var fullName = order.last_name + ' ' + order.first_name;
  var delivery = 'Самовывоз';
  var cash = 'Банковской картой';
  var address = 'Пункт самовывоза'

  if (order.middle_name) {
    fullName += ' ' + order.middle_name;
  }
  if(order.delivery !== '0') {
    delivery = 'Курьерная доставка';
    address = order.city + ', ' + order.street + ', ' + order.house + ', ' + order.apartment;
  }
  if (order.cash !== '0') {
    cash = 'Наличными';
  }

  var elements = [
    {name: 'order_id',    text: order.id},
    {name: 'cost',        text: order.cost},
    {name: 'product_id',  text: order.product_id},
    {name: 'create_time', text: order.create_time},
    {name: 'full_name',   text: fullName},
    {name: 'phone',       text: order.phone},
    {name: 'delivery',    text: delivery},
    {name: 'cash',        text: cash},
    {name: 'address',     text: address},
    {name: 'comment',     text: order.comment},
  ];

  elements.forEach((element) => {
    changeElementById(card, element.name, element.text);
  });

  if (order.processed_flag === '1') {
    status.textContent = 'Выполнено';
    status.classList.remove('order-item__info--no');
    status.classList.add('order-item__info--yes');
  } else {
    card.classList.add('order-item--active');
  }

  return card;
}

// Функция создания списка заказов
const createOrderList = (orders) => {
  const orderList = document.querySelector('.page-order__list');
  const template = document.querySelector('#page-order__list').content.querySelector('.page-order__item');

  // заполнение списка
  orders.forEach((order) => {
    var element = getOrderListElement(order, template);
    orderList.appendChild(element);
  });
}

// Функция показа подробной информации о заказе
const showOrderFullCard = (evt) => {
  var path = evt.path || (evt.composedPath && evt.composedPath());
  Array.from(path).forEach(element => {
    if (element.classList && element.classList.contains('page-order__item')) {
      element.classList.toggle('order-item--active');
    }
  });
  evt.target.classList.toggle('order-item__toggle--active');
}


// Функция изменения статуса заказа
const toggleStatusOrder = (statusElement) => {
  if (statusElement.classList && statusElement.classList.contains('order-item__info--no')) {
    statusElement.textContent = 'Выполнено';
  } else {
    statusElement.textContent = 'Не выполнено';
  }

  statusElement.classList.toggle('order-item__info--no');
  statusElement.classList.toggle('order-item__info--yes');
}

// Функция добавляет eventListener для страницы заказов
const addPageOrderListener = () => {
  const pageOrderList = document.querySelector('.page-order__list');

  pageOrderList.addEventListener('click', evt => {
    if (evt.target.classList && evt.target.classList.contains('order-item__toggle')) {
      showOrderFullCard(evt);
    }

    if (evt.target.classList && evt.target.classList.contains('order-item__btn')) {
      const status = evt.target.previousElementSibling;
      toggleStatusOrderDB(status);
    }
  });
}

  /////////////////////////
 // Асинхронные Функции //
/////////////////////////

// Функция запроса списка товаров из DB
const requestExtendedMenu = () => {
  // AJAX
  $.get('/include/admin/userRights.php')
    .fail(function() {
      alertOnce('Ошибка загрузки данных'); // изменить
    })
    .done(function(data) {
      const right = JSON.parse(data);
      if (right.admins) {
        createProductElemenetMenu();
      }
    });
}

// Функция запроса списка товаров из DB
const requestOrderList = () => {
  // AJAX
  $.get('/include/admin/orderList.php')
    .fail(function() {
      alertOnce('Ошибка загрузки данных'); // изменить
    })
    .done(function(data) {
      const orders = JSON.parse(data);
      createOrderList(orders);
    });
}

// Функция переключения статусаза заказа в DB
const toggleStatusOrderDB = (status) => {
  var id = status.id.substring(9);
  var processedFlag = 0;
  if (status.classList.contains('order-item__info--no')) {
    processedFlag = 1;
  }
  var get = 'id=' + id + '&processed_flag=' + processedFlag;

  // AJAX
  $.get('/include/admin/toggleOrderStatus.php', get)
    .fail(function() {
      alertOnce('Ошибка загрузки данных'); // изменить
    })
    .done(function(data) {
      const result = JSON.parse(data);
      if (result.success) {
        toggleStatusOrder(status);
      }
    });
}

  ////////////
 // Скрипт //
////////////

const pageOrder = document.querySelector('.page-order');
if (pageOrder) {
  requestExtendedMenu();
  requestOrderList();
  addPageOrderListener();
}

  ////////////////////////////////////
 // Список продуктов products.php //
////////////////////////////////////

  /////////////
 // Функции //
/////////////

// Функция очистки дочерних элементов
const clearChild = (parrent) => {
  while (parrent.firstChild) {
    parrent.removeChild(parrent.firstChild);
  }
}

// Изменение адресной строки без перезагрузки (https://ru.stackoverflow.com/questions/85380/)
const setLocation = (curLoc) => {
  try {
    history.pushState(null, null, curLoc);
    return;
  } catch(e) {}

  location.hash = '#' + curLoc;
}

// Функция изменения GET-url (основа https://stackoverflow.com/questions/486896/)
const insertParamMultiple = (obj) => {
  var kvp = document.location.search.substr(1).split('&');

  var keys = Object.keys(obj);

  for (var i = 0; i < keys.length; i++) {
    for (var j = kvp.length - 1; j >= 0; j--) {
      var x = kvp[j].split('=');

      if (x[0] == keys[i]) {
        x[1] = obj[keys[i]];
        kvp[j] = x.join('=');
        break;
      }
    }

    if (j < 0) {
      kvp[kvp.length] = [keys[i], obj[keys[i]]].join('=');
    }
  }

  if (!kvp[0]) {
    kvp.shift();
  }
  setLocation('?' + kvp.join('&'));
}

// Фукнция возвращает элемент для списка товаров
const getProductListElement = (product, template) => {
  var card = template.cloneNode(true);
  card.id = 'prodID__' + product.id;

    var new_flag = 'Нет';
    if (product.new_flag === '1') {
      new_flag = 'Да';
    }

    var elements = [
    {name: 'name',        text: product.name},
    {name: 'product_id',  text: product.id},
    {name: 'price',       text: product.price},
    {name: 'new_flag',    text: new_flag},
    {name: 'categories',  text: product.categories}
  ];

  elements.forEach((element) => {
    changeElementById(card, element.name, element.text);
  });

  card.querySelector('a').href = 'add.php?id=' + product.id;
  return card;
}

// Функция создания (верстки) списка товаров
const refreshProductList = (products) => {
  const productList = document.querySelector('.page-products__list');
  const template = document.querySelector('#page-products__list').content.querySelector('.product-item');

  fade(productList);
  clearChild(productList);

  // заполнение списка
  products.forEach((product) => {
    var element = getProductListElement(product, template);
    productList.appendChild(element);
  });
}

// Фукнция возвращает элемент paginator'а
const getPaginatorElement = (page, currentPage ,pages) => {
  var element = document.createElement('li');
  var a = document.createElement('a');

  a.classList.add('paginator__item');
  if (page != currentPage) {
    a.href = '?page=' + page;
    a.addEventListener('click', function(evt) {
      evt.preventDefault();

      insertParamMultiple({'page':page});

      requestProductList();
      refreshPaginator(pages, page);
    })
  }
  a.textContent = page;

  element.appendChild(a);
  return element;
}

// Функция создания кнопок страниц навигации
const refreshPaginator = (pages, currentPage) => {

  var paginator = document.querySelector('.shop__paginator');

  fade(paginator);
  clearChild(paginator);

  var startPage = currentPage - 2;
  if (startPage < 1) {
    startPage = 1;
  }

  var endPage = currentPage + 2;
  if (endPage > pages) {
    endPage = pages;
  }

  for (let i = startPage; i <= endPage; i++) {
    var element = getPaginatorElement(i, currentPage ,pages)
    paginator.appendChild(element);
  }
}

  /////////////////////////
 // Асинхронные Функции //
/////////////////////////

// Функция запроса списка товаров из DB
const requestProductList = () => {
  // AJAX
  $.get('/include/admin/productListWithCategory.php', window.location.search.substring(1) + '&type=id&order=desc')
    .fail(function() {
      alertOnce('Ошибка загрузки данных'); // изменить
    })
    .done(function(data) {
      var products = JSON.parse(data);
      refreshProductList(products);
    });
}

// Функция запроса количество товара из DB
const requestProductCounter = () => {
  // AJAX
  $.get('/include/productCounter.php', window.location.search.substring(1))
    .fail(function() {
      alertOnce('Oшибка загруки данных'); // изменить
    })
    .done(function(data) {
      var counter = JSON.parse(data);
      refreshPaginator(counter.pages, counter.currentPage);
    });
}

// Функция удаления заказа
const deactivateProduct = (target) => {
  // AJAX
  $.get('/include/admin/deactivateProduct.php', 'id=' + target.id.substring(8))
    .fail(function() {
      alertOnce('Oшибка загруки данных'); // изменить
    })
    .done(function(data) {
      const result = JSON.parse(data);
      if (result.success) {
        target.parentElement.removeChild(target);
      }
    });
}


  ////////////
 // Скрипт //
////////////

const pageProducts = document.querySelector('.page-products');
if (pageProducts) {
  requestProductList();
  requestProductCounter();

  const productsList = pageProducts.querySelector('.page-products__list')
  productsList.addEventListener('click', evt => {
    const target = evt.target;
    if (target.classList && target.classList.contains('product-item__delete')) {
      deactivateProduct(target.parentElement);
    }
  });
}

  //////////////////////////////////
 // добавление продукта add.php //
//////////////////////////////////

  /////////////
 // Функции //
/////////////

// Функция получения значения GET запроса
const getRequestValue = (key) => {
  var kvp = document.location.search.substr(1).split('&');

  var value = null;

  for(var i = kvp.length - 1; i >= 0; i--) {
    var x = kvp[i].split('=');
    if (x[0] == key) {
      value = x[1];
      return value;
    }
  }

  return value;
}

// Функция сокрытия кнопки при длине списка больше 1
const checkList = (list, btn) => {
  if (list.children.length === 1) {
    btn.hidden = false;
  } else {
    btn.hidden = true;
  }
};

// Фукнция возвращает элемент списка категорий
const getCategoryListElement = (filter) => {
  const element = document.createElement('option');
  element.value = filter.id;
  element.textContent = filter.name;
  return element;
}

// Функция создания (верстки) списка категорий
const createCategotyList = (filters) => {
  const categoryList = document.querySelector('.custom-form__select');

  filters.forEach((filter) => {
    var element = getCategoryListElement(filter);
    categoryList.appendChild(element);
  });
}

 // Функция перевода файла по сслыке в base64. Источник http://qaru.site/questions/3994/

function toDataURL(url, callback) {
  var xhr = new XMLHttpRequest();
  xhr.onload = function() {
    var reader = new FileReader();
    reader.onloadend = function() {
      callback(reader.result);
    }
    reader.readAsDataURL(xhr.response);
  };
  xhr.open('GET', url);
  xhr.responseType = 'blob';
  xhr.send();
}

// Добавление фото
const addImage = (url) => {
  const addList = document.querySelector('.add-list');
  const addInput = addList.querySelector('#product-photo');
  const addButton = addList.querySelector('.add-list__item--add');

  const template = document.createElement('LI');
  const img = document.createElement('IMG');

  template.className = 'add-list__item add-list__item--active';
  // Удаление фото
  template.addEventListener('click', evt => {
    addList.removeChild(evt.target);
    addInput.value = '';
    checkList(addList, addButton);
  });

  toDataURL(url, (src) => {
    img.src = src;
    img.id = 'img';
    template.appendChild(img);
    addList.appendChild(template);
    checkList(addList, addButton);
  });
}

// Фукнция обновления чекбоксов
const setCheckboxes = (checkboxes) => {
  const newCheckbox = document.querySelector('#new');
  const saleCheckbox = document.querySelector('#sale');
  const activeCheckbox = document.querySelector('#active');

  newCheckbox.checked = (checkboxes.new == '1');
  saleCheckbox.checked = (checkboxes.sale == '1');
  activeCheckbox.checked = (checkboxes.active == '1');
}

// Функция заполнения полей из редактировании товара
const setProductForms = (product) => {
  const main = document.querySelector('.page-add');
  fade(main);
  main.querySelector('h1').textContent = 'Редактирование товара';
  main.querySelector('.button').textContent = 'Редактировать товар';
  main.querySelector('.shop-page__end-title').textContent = 'Товар успешно изменен';

  const form = main.querySelector('.custom-form');

  // hidden input
  var inputID = document.createElement('input');
  inputID.type = 'hidden';
  inputID.name = 'id';
  inputID.value = product.id;
  form.appendChild(inputID);

  // text input
  const nameForm = form.querySelector('#product-name');
  const priceForm = form.querySelector('#product-price');
  nameForm.value = product.name;
  priceForm.value = product.price;
  nameForm.nextElementSibling.hidden = true;
  priceForm.nextElementSibling.hidden = true;

  //image
  addImage(product.image);

  // select
  if (product.category) {
    const select = main.querySelector('.custom-form__select')
    product.category.forEach((category) => {
      const option = select.querySelector('[value="' + category + '"]');
      option.setAttribute('selected', '');
    });
  }

  // checkbox
  var checkboxes = {
    new: product.new,
    sale: product.sale,
    active: product.active
  };
  setCheckboxes(checkboxes);
}

  /////////////////////////
 // Асинхронные Функции //
/////////////////////////

// Функция запроса списка категорий из DB
const requestCategoryList = () => {
  // AJAX
  $.get('/include/categoryList.php')
    .fail(function() {
      alertOnce('Ошибка загрузки данных');
    })
    .done(function(data) {
      var filters = JSON.parse(data);
      createCategotyList(filters);
    });
}

// Функция запроса товара из DB
const requestProduct = (id) => {
  // AJAX
  $.get('/include/admin/product.php', 'id=' + id)
    .fail(function() {
      alertOnce('Ошибка загрузки данных');
    })
    .done(function(data) {
      var product = JSON.parse(data);
      if (!product.error) {
        setProductForms(product);
      }
    });
}


 // Функция добавления заказа в DB
const addProduct = () => {
  var imgBase64 = document.querySelector('#img').src;
  var $post = $('.custom-form').serialize() + '&imageBase64=' + imgBase64;
  // AJAX
  $.post('/include/admin/addProduct.php', $post)
    .fail(function() {
      alertOnce('Oшибка загруки данных'); // изменить
    })
    .done(function(data) {
      var result = JSON.parse(data);
      if (result.success) {
        const form = document.querySelector('.custom-form');
        const popupEnd = document.querySelector('.page-add__popup-end');

        form.hidden = true;
        popupEnd.hidden = false;
      } else {
        addError(result.error);
      }
    });
}

  ////////////
 // Скрипт //
////////////

// Добавление товара, загрузка фото
const addList = document.querySelector('.add-list');
if (addList) {
  requestCategoryList();

  const form = document.querySelector('.custom-form');
  labelHidden(form);

  const addButton = addList.querySelector('.add-list__item--add');
  const addInput = addList.querySelector('#product-photo');

  checkList(addList, addButton);

  // Добавление фото
  addInput.addEventListener('change', evt => {

    const template = document.createElement('LI');
    const img = document.createElement('IMG');

    template.className = 'add-list__item add-list__item--active';
    // Удаление фото
    template.addEventListener('click', evt => {
      addList.removeChild(evt.target);
      addInput.value = '';
      checkList(addList, addButton);
    });

    const file = evt.target.files[0];
    const reader = new FileReader();

    reader.onload = (evt) => {
      img.src = evt.target.result;
      img.id = 'img';
      template.appendChild(img);
      addList.appendChild(template);
      checkList(addList, addButton);
    };

    reader.readAsDataURL(file);

  });

  // Товар успешно добавлен
  const button = document.querySelector('.button');
  button.addEventListener('click', (evt) => {
    evt.preventDefault();

    const name = form.querySelector('#product-name');
    const price = form.querySelector('#product-price');
    const image = form.querySelector('.add-list__item--active');

    if (name.value !== '' && price.value !== '' && image) {
      addProduct();
    } else {
      addError('Заполните все обязательные поля!');
    }
  });
}

var productID = getRequestValue('id');
if (productID) {
  requestProduct(productID);
}

