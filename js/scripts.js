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

  ////////////////////////////////
 // Основная страница магазина //
////////////////////////////////

  /////////////
 // Функции //
/////////////

// Функция очистки дочерних элементов
const clearChild = (parrent) => {
  while (parrent.firstChild) {
    parrent.removeChild(parrent.firstChild);
  }
}

// Изменение адресной строки без перезагрузки (источник https://ru.stackoverflow.com/questions/85380/)
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

//
//  Функции для создания главного меню
//

// Фукнция выбирает активный элемент Главного меню согласно GET
const setActiveMainMenu = (elementTextContent) => {
  const mainMenu = document.querySelectorAll('.main-menu--header .main-menu__item');
  mainMenu.forEach((element) => {
    if (element.textContent == elementTextContent) {
      element.classList.add('active');
    } else {
      element.classList.remove('active');
    }
  });
}

// Фукнция возвращает элемент главного меню
const getMainMenuElement = (text, href) => {
  const element = document.createElement('li');
  const a = document.createElement('a');

  a.classList.add('main-menu__item');
  a.href = href;
  a.textContent = text;

  element.appendChild(a);
  return element;
}

// Функция создания главного меню для класса
const createMainMenuByClass = (className) => {

  const indexElements = [
    {text:'Главная', href:'/index.php'},
    {text:'Новинки', href:'/index.php?new=1'},
    {text:'Sale', href:'/index.php?sale=1'}
  ];
  const deliveryElement = {text:'Доставка', href:'/delivery.php'};

  const mainMenu = document.querySelector('.main-menu--' + className);

  // интерактивыне элементы
  indexElements.forEach((element) => {
    const indexElement = getMainMenuElement(element.text, element.href);
    // Проверка на активный элемент согласно GET
    if (className == 'header' && document.location.search === element.href.substr(10)) {
      indexElement.querySelector('a').classList.add('active');
    }

    indexElement.addEventListener('click', (evt) => {
      evt.preventDefault();
      window.scroll(0, 0);
      setActiveMainMenu(element.text);
      setLocation(element.href);
      refreshPage();
    })

    mainMenu.appendChild(indexElement);
  });

  // статичная ссылка
  mainMenu.appendChild(getMainMenuElement(deliveryElement.text, deliveryElement.href));

  // отсутствие активного элемента
  if (className == 'header' && !document.querySelector('.main-menu__item.active')) {
    setActiveMainMenu(indexElements[0].text);
  }
}

// Функция создания (верстки) главного меню
const createMainMenu = () => {
  createMainMenuByClass('header');
  createMainMenuByClass('footer');
}

//
// Функции для создания списка категорий
//

// Фукнция устанавливает активную категорию согласно GET
const setActiveCategory = () => {
  const currentActive = document.querySelector('.filter__list-item.active');
  if (currentActive) {
    currentActive.classList.remove('active');
  }

  const activeCategoryID = '#filterID__' + Number(getRequestValue('cat'));
  document.querySelector(activeCategoryID).classList.add('active');
}

// Фукнция возвращает элемент списка категорий
const getCategoryListElement = (filter) => {
  const element = document.createElement('li');
  const a = document.createElement('a');

  a.classList.add('filter__list-item');
  a.id = 'filterID__' + filter.id;
  a.href = '?cat=' + filter.id;
  a.textContent = filter.name;

  element.appendChild(a);
  return element;
}

// Функция создания (верстки) списка категорий
const createCategotyList = (filters) => {
    const categoryList = document.querySelector('.filter__list');

    filters.forEach((filter) => {
      var element = getCategoryListElement(filter);
      categoryList.appendChild(element);
    });
    setActiveCategory();
}

//
// Функции для создания (обновления) фильтров: слайдера, чекбоксов, способов сортировки
//

// Функция обновления слайдера jquery
const refreshSlidersLabels = () => {
  $('.min-price').text($('.range__line').slider('values', 0).toLocaleString('ru') + ' руб.');
  $('.max-price').text($('.range__line').slider('values', 1).toLocaleString('ru') + ' руб.');
}

// Функция задания слайдера
const setSlider = (slider) => {
  $('.range__line').slider({
    min: slider.min,
    max: slider.max,
    values: [slider.lo, slider.hi],
    range: true,
    stop: () => {refreshSlidersLabels()},
    slide: () => {refreshSlidersLabels()}
  });
  refreshSlidersLabels();
}

// Фукнция обновления чекбоксов
const setCheckboxes = (checkboxes) => {
  const newCheckbox = document.querySelector('#new');
  const saleCheckbox = document.querySelector('#sale');

  newCheckbox.checked = (checkboxes.new == '1');
  saleCheckbox.checked = (checkboxes.sale == '1');
}

// Функции обновления способов сортировки
const setSortFilters = (sortFilters) => {
  const typeSelector = document.querySelector('select[name="type"]');
  const orderSelector = document.querySelector('select[name="order"]');

  if (sortFilters.type) {
    typeSelector.value = sortFilters.type;
  } else {
    typeSelector.selectedIndex = 0;
  }

  if (sortFilters.order) {
    orderSelector.value = sortFilters.order;
  } else {
    orderSelector.selectedIndex = 0;
  }
}

//
// Функции для создания (обновления) списка товаров
//

// Фукнция возвращает элемент для списка товаров
const getProductListElement = (product, template) => {
  var element = template.cloneNode(true);
  element.id = 'prodID__' + product.id;

  var image = element.querySelector('.product__image').children[0];
  image.src = product.image;
  image.alt = product.name;

  var name = element.querySelector('.product__name');
  name.textContent = product.name;

  var price = element.querySelector('.product__price');
  price.textContent = product.price;

  return element;
}

// Функция создания (верстки) списка товаров
const refreshProductList = (products) => {
  const productList = document.querySelector('.shop__list');
  const template = document.querySelector('#shop__item').content.querySelector('.shop__item');

  fade(productList);
  clearChild(productList);

  // заполнение списка
  products.forEach((product) => {
    var element = getProductListElement(product, template);
    productList.appendChild(element);
  });
}

//
// Функции для создания (обновления) paginator'а и счетчика товаров
//

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

// Фукнция обновляет счетчик товаров (Просто проба пера в jquery)
const refreshProductCounter = (count, word) => {
  $('.res-sort').text(count);
  $('#ending').text(word);
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

//
// Функции обновления страницы в соответсвии с GET-url
//

// Функция задания фильтров в соответсвии с GET;
const setFilters = () => {
  // slider
  requestSlider();

  // checkbox
  var checkboxes = {
    new: getRequestValue('new'),
    sale: getRequestValue('sale')
  };
  setCheckboxes(checkboxes);

  // sortFilter
  var sortFilters = {
    type: getRequestValue('type'),
    order: getRequestValue('order')
  }
  setSortFilters(sortFilters);
}


// Функция обновления формы ввода фильтров (исходя из строки GET браузера)
const refreshFilterForm = () => {
  setActiveCategory();
  setFilters();
}

// Функция обновления товаров через GET запрос на сервер
const refreshProducts = () => {
  requestProductList();
  requestProductCounter();
}

// Функция обновления страницы
const refreshPage = () => {
  refreshFilterForm();
  refreshProducts();
}

  /////////////////////////
 // Асинхронные Функции //
/////////////////////////

// Функция запроса списка категорий из DB
const requestCategoryList = () => {
  // AJAX
  $.get('include/categoryList.php')
    .fail(function() {
      alertOnce('Ошибка загрузки данных');
    })
    .done(function(data) {
      var filters = JSON.parse(data);
      createCategotyList(filters);
    });
}

// Функция запроса слайдера (максимальной и минимальной цены) из DB
const requestSlider = () => {
  // AJAX
  $.get('include/slider.php', window.location.search.substring(1))
    .fail(function() {
      alertOnce('Ошибка загрузки данных');
    })
    .done(function(data) {
      var slider = JSON.parse(data);
      setSlider(slider);
    });
}

// Функция запроса списка товаров из DB
const requestProductList = () => {
  // AJAX
  $.get('/include/productList.php', window.location.search.substring(1))
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
      refreshProductCounter(counter.count, counter.word);
      refreshPaginator(counter.pages, counter.currentPage);
    });
}

  ////////////
 // Скрипт //
////////////

// 1. Загруза главного меню
createMainMenu();

// 2. Загрузка списка категорий
requestCategoryList();

// 2.1. Установка обработчика для категорий
const categoryWrapper = document.querySelector('.filter__list');
categoryWrapper.addEventListener('click', evt => {
  evt.preventDefault();
  const category = evt.target;

  if (category.tagName == 'A' && !category.classList.contains('active')) {
    const categoryList = categoryWrapper.querySelectorAll('.filter__list-item');

    categoryList.forEach(category => {
      if (category.classList.contains('active')) {
        category.classList.remove('active');
      }
    });
    category.classList.add('active');

    const mainMenuHref = document.querySelector('.main-menu__item.active').href.split('?');
    var get = category.href;
    if (mainMenuHref[1]) {
      get += '&' + mainMenuHref[1];
    }
    setLocation(get);

    refreshPage();
  }
});

// 3. установка фильтров согласно GET-url
setFilters();

// 3.1. Установка обработчика на фильтры
const filterForm = document.querySelector('.shop__filter form');
filterForm.addEventListener('submit', (evt) => {
  evt.preventDefault();


  var min = filterForm.querySelector('.min-price').textContent.slice(0, -5).replace(/\s/g, '');
  var max = filterForm.querySelector('.max-price').textContent.slice(0, -5).replace(/\s/g, '');
  if ($('.range__line').slider('option', 'min') === $('.range__line').slider('values', 0)) {
    min = 'min';
  }
  if ($('.range__line').slider('option', 'max') === $('.range__line').slider('values', 1)) {
    max = 'max';
  }

  const get = {
    min: min,
    max: max,
    new: Number(filterForm.querySelector('#new').checked),
    sale: Number(filterForm.querySelector('#sale').checked)
  };

  insertParamMultiple(get);
  requestSlider();
  refreshProducts();
});

 // 3.2. Установка обработчика на сортировку
const typeSelector = document.querySelector('select[name="type"]');
typeSelector.addEventListener('change', () => {
  insertParamMultiple({type:typeSelector.value});
  refreshProducts();
});

const orderSelector = document.querySelector('select[name="order"]');
orderSelector.addEventListener('change', () => {
  insertParamMultiple({order:orderSelector.value});
  refreshProducts();
});


// 4. Наполение магазина
refreshProducts();


  ///////////////////////
 // Оформление заказа //
///////////////////////

  /////////////
 // Функции //
/////////////

// Функция переключателя hidden
const toggleHidden = (...fields) => {
  fields.forEach((field) => {
    if (field.hidden === true) {
      field.hidden = false;
    } else {
      field.hidden = true;
    }
  });
};

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

// Функция вывода заглушки после удачного оформления заказа
const addOrderSucсess = () => {
  const shopOrder = document.querySelector('.shop-page__order');
  const popupEnd = document.querySelector('.shop-page__popup-end');
  toggleHidden(shopOrder, popupEnd);
  fade(popupEnd);

  const buttonEnd = popupEnd.querySelector('.button');
  buttonEnd.removeEventListener('click', handlerButtonEnd);
  buttonEnd.addEventListener('click', handlerButtonEnd);
}

// Обработчик для delivery EventListener
const handlerDelivery = (evt) => {
  const deliveryYes = document.querySelector('.shop-page__delivery--yes');
  const deliveryNo = document.querySelector('.shop-page__delivery--no');
  const fields = deliveryYes.querySelectorAll('.custom-form__input');

  if (evt.target.id === 'dev-no') {
    fields.forEach(inp => {
      if (inp.required === true) {
        inp.required = false;
      }
    });

    toggleHidden(deliveryYes, deliveryNo);
    fade(deliveryNo);
  } else {
    fields.forEach(inp => {
      if (inp.required === false) {
        inp.required = true;
      }
    });

    toggleHidden(deliveryYes, deliveryNo);
    fade(deliveryYes);
  }
}

// Функция переключателя способа доставки (radio-button)
const toggleDelivery = (elem) => {
  const delivery = elem.querySelector('.js-radio');

  delivery.removeEventListener('change',handlerDelivery);
  delivery.addEventListener('change',handlerDelivery);
};

// Обработчик для buttonEnd EventListener
const handlerButtonEnd = () => {
  const popupEnd = document.querySelector('.shop-page__popup-end');
  popupEnd.classList.add('fade-reverse');
  setTimeout(() => {
    popupEnd.classList.remove('fade-reverse');
    toggleHidden(popupEnd, document.querySelector('.intro'), document.querySelector('.shop'));
  }, 1000);
}

// Обработчик для buttonOrder EventListener
const handlerButtonOrder = (evt) => {
  const shopOrder = document.querySelector('.shop-page__order');
  shopOrder.querySelector('.custom-form').noValidate = true;

  const inputs = Array.from(shopOrder.querySelectorAll('[required]'));
  // Обработка required input
  inputs.forEach(inp => {
    if (!!inp.value) {
      if (inp.classList.contains('custom-form__input--error')) {
        inp.classList.remove('custom-form__input--error');
      }
    } else {
      inp.classList.add('custom-form__input--error');
    }
  });

  //  Вывод формы успешного оформления заказа
  if (inputs.every(inp => !!inp.value)) {
    evt.preventDefault();
    addOrder(); // обработка заказа. В случии удачи addOrderSucсess()
  } else {
    window.scroll(0, 0);
    evt.preventDefault();
  }
}

// Обработчик для shopList EventListener
const handlerSlopList = (evt) => {
  const prod = evt.path || (evt.composedPath && evt.composedPath());;

  if (prod.some(pathItem => pathItem.classList && pathItem.classList.contains('shop__item'))) {
    const prodIDForm = document.querySelector('#prodID');
    prodIDForm.value = evt.target.id.substring(8);

    const shopOrder = document.querySelector('.shop-page__order');
    toggleHidden(document.querySelector('.intro'), document.querySelector('.shop'), shopOrder);

    window.scroll(0, 0);
    fade(shopOrder);

    const form = shopOrder.querySelector('.custom-form');
    labelHidden(form);
    toggleDelivery(shopOrder);

    const buttonOrder = shopOrder.querySelector('.button');
    buttonOrder.removeEventListener('click', handlerButtonOrder);
    buttonOrder.addEventListener('click', handlerButtonOrder);
  }
}


  /////////////////////////
 // Асинхронные Функции //
/////////////////////////

 // Функция добавления заказа в DB
const addOrder = () => {
  // AJAX
  $.post('/include/addOrder.php', $('.custom-form').serialize())
    .fail(function() {
      window.scroll(0, 0);
      alertOnce('Oшибка загруки данных'); // изменить
    })
    .done(function(data) {
      window.scroll(0, 0);
      var result = JSON.parse(data);
      if (result.success) {
        addOrderSucсess();
      }
    });
}


  ////////////
 // Скрипт //
////////////


// Переключение на оформление заказа при выборе товара
const shopList = document.querySelector('.shop__list');
shopList.removeEventListener('click', handlerSlopList);
shopList.addEventListener('click', handlerSlopList);
