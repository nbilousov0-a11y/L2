// ============================================
// Клиентская логика: бронирование туруслуг
// ============================================

// ---- Справочники названий ----
const STATUS_NAMES = {
  'new':       'Новое',
  'confirmed': 'Подтверждено',
  'paid':      'Оплачено',
  'cancelled': 'Отменено'
};

const PRIORITY_NAMES = {
  'high':   'Высокий',
  'medium': 'Средний',
  'low':    'Низкий'
};

// ---- Ссылки на элементы страницы ----
const form          = document.getElementById('booking-form');
const titleInput    = document.getElementById('booking-title');
const statusSelect  = document.getElementById('booking-status');
const prioritySelect= document.getElementById('booking-priority');
const formError     = document.getElementById('form-error');

const filterStatus  = document.getElementById('filter-status');
const searchInput   = document.getElementById('search-input');
const loadButton    = document.getElementById('load-button');

const info          = document.getElementById('info');
const counter       = document.getElementById('counter');
const table         = document.getElementById('bookings-table');
const tbody         = document.getElementById('bookings-body');

// ============================================
// Шаг 2. Обработка пользовательских событий
// ============================================

// TODO 2.1 + 2.2: обработка формы
function onFormSubmit(event) {
  event.preventDefault(); // 2.1 отменяем перезагрузку

  const title = titleInput.value.trim();

  // 2.2 проверка длины
  if (title.length < 3) {
    formError.textContent = 'Название тура должно быть не короче 3 символов';
    formError.hidden = false;
    return;
  }
  formError.hidden = true;

  // 3.7 добавляем строку в начало таблицы
  const status = statusSelect.value;
  const priority = prioritySelect.value;

  const row = createTaskRow({
    id: 'local-' + Date.now(),
    title: title,
    status: status,
    priority: priority
  });
  tbody.insertBefore(row, tbody.firstChild);
  table.hidden = false;

  // очистка формы
  titleInput.value = '';
  statusSelect.value = 'new';
  prioritySelect.value = 'high';

  updateCounter();
  showInfo('Бронирование добавлено', 'ok');
}

// 2.3: обработчики кнопок, фильтра, поиска
function onLoadClick() {
  console.log('Нажата кнопка «Загрузить с сервера»');
  refreshList();
}

function onFilterChange() {
  console.log('Фильтр изменён:', filterStatus.value);
  refreshList();
}

function onSearchInput() {
  console.log('Поиск:', searchInput.value);
  refreshList();
}

// ============================================
// Шаг 3. Работа с DOM
// ============================================

// Вспомогательная: создать ячейку
function addCell(row, text) {
  const cell = document.createElement('td');
  cell.textContent = text; // безопасно: только текст
  row.appendChild(cell);
  return cell;
}

// TODO 3.1–3.4: создание строки таблицы
function createTaskRow(booking) {
  const row = document.createElement('tr');
  row.setAttribute('data-id', booking.id); // 3.3 атрибут

  // 3.1 ячейки с данными
  addCell(row, booking.title);
  addCell(row, STATUS_NAMES[booking.status] || booking.status);
  addCell(row, PRIORITY_NAMES[booking.priority] || booking.priority);

  // 3.2 ячейка с кнопками
  const actionsCell = document.createElement('td');

  const doneButton = document.createElement('button');
  doneButton.textContent = 'Выполнено';
  doneButton.addEventListener('click', onDoneClick);
  actionsCell.appendChild(doneButton);

  const deleteButton = document.createElement('button');
  deleteButton.textContent = 'Удалить';
  deleteButton.addEventListener('click', onDeleteClick);
  actionsCell.appendChild(deleteButton);

  row.appendChild(actionsCell);

  // 3.4 класс done для выполненных (paid/cancelled)
  if (booking.status === 'paid' || booking.status === 'cancelled') {
    row.classList.add('done');
  }

  return row;
}

// TODO 3.5: переключение статуса «Выполнено»
function onDoneClick(event) {
  const row = event.target.closest('tr');
  row.classList.toggle('done');

  const statusCell = row.children[1];
  const isDone = row.classList.contains('done');
  statusCell.textContent = isDone ? 'Оплачено' : 'Новое';

  event.target.textContent = isDone ? 'Вернуть' : 'Выполнено';
  event.target.setAttribute('title', isDone ? 'Вернуть в работу' : 'Отметить выполненным');

  updateCounter();
}

// TODO 3.6: удаление строки
function onDeleteClick(event) {
  const row = event.target.closest('tr');
  const title = row.children[0].textContent;

  if (!confirm('Удалить бронирование «' + title + '»?')) return;

  row.remove();
  updateCounter();
  showInfo('Бронирование удалено', 'ok');
}

// Обновление счётчика
function updateCounter() {
  const rows = tbody.querySelectorAll('tr');
  const done = tbody.querySelectorAll('tr.done');
  counter.textContent = 'Всего бронирований: ' + rows.length +
                        ' (выполнено: ' + done.length + ')';
  counter.hidden = rows.length === 0;
}

// Сообщение о состоянии
function showInfo(message, type) {
  info.textContent = message;
  info.className = 'info ' + (type || '');
  info.hidden = false;
}

// ============================================
// Шаг 4. Асинхронные запросы
// ============================================

// TODO 4.1: загрузка данных с сервера
async function loadBookings() {
  const params = new URLSearchParams();

  if (filterStatus.value) params.append('status', filterStatus.value);
  if (searchInput.value.trim()) params.append('q', searchInput.value.trim());

  const queryString = params.toString();
  const url = '/api/bookings' + (queryString ? '?' + queryString : '');

  const response = await fetch(url);
  if (!response.ok) {
    throw new Error('Ошибка сервера: ' + response.status);
  }
  return await response.json();
}

// TODO 4.2: загрузка и отображение списка
async function refreshList() {
  showInfo('Загрузка…', 'loading');
  loadButton.disabled = true;

  try {
    const data = await loadBookings();

    tbody.innerHTML = '';

    if (!data.length) {
      table.hidden = true;
      showInfo('Бронирования не найдены. Измените фильтр или текст поиска.', 'empty');
      counter.hidden = true;
      return;
    }

    data.forEach(booking => {
      tbody.appendChild(createTaskRow(booking));
    });

    table.hidden = false;
    updateCounter();
    showInfo('Загружено записей: ' + data.length, 'ok');

  } catch (error) {
    table.hidden = true;
    counter.hidden = true;
    showInfo('Не удалось загрузить данные: ' + error.message, 'error');
    console.error(error);

  } finally {
    loadButton.disabled = false;
  }
}

// ============================================
// Подключение обработчиков
// ============================================

form.addEventListener('submit', onFormSubmit);
loadButton.addEventListener('click', onLoadClick);
filterStatus.addEventListener('change', onFilterChange);
searchInput.addEventListener('input', onSearchInput);

// TODO 4.3: автозагрузка при открытии страницы
refreshList();
