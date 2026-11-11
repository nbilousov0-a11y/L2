// ============================================
// Клиентская логика: бронирование туруслуг
// ============================================

// ---- Справочники ----
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

// ---- Ссылки на элементы ----
const form           = document.getElementById('booking-form');
const titleInput     = document.getElementById('booking-title');
const statusSelect   = document.getElementById('booking-status');
const prioritySelect = document.getElementById('booking-priority');
const formError      = document.getElementById('form-error');

const filterStatus   = document.getElementById('filter-status');
const searchInput    = document.getElementById('search-input');
const loadButton     = document.getElementById('load-button');

const info           = document.getElementById('info');
const counter        = document.getElementById('counter');
const table          = document.getElementById('bookings-table');
const tbody          = document.getElementById('bookings-body');

// Счётчики для защиты от гонки и debounce
let currentRequestId = 0;
let searchTimer = null;

// ============================================
// Шаг 3. Работа с DOM
// ============================================

function addCell(row, text) {
  const cell = document.createElement('td');
  cell.textContent = text;
  row.appendChild(cell);
  return cell;
}

function createTaskRow(booking) {
  const row = document.createElement('tr');
  row.setAttribute('data-id', booking.id);

  addCell(row, booking.title);
  addCell(row, STATUS_NAMES[booking.status] || booking.status);
  addCell(row, PRIORITY_NAMES[booking.priority] || booking.priority);

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

  if (booking.status === 'paid' || booking.status === 'cancelled') {
    row.classList.add('done');
  }

  return row;
}

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

async function onDeleteClick(event) {
  const row = event.target.closest('tr');
  const title = row.children[0].textContent;
  const id = row.getAttribute('data-id');

  if (!confirm('Удалить бронирование «' + title + '»?')) return;

  // Локальная запись (ещё не на сервере)
  if (!id || id.startsWith('local-')) {
    row.remove();
    updateCounter();
    showInfo('Бронирование удалено', 'ok');
    return;
  }

  try {
    await deleteBookingOnServer(id);
    row.remove();
    updateCounter();
    showInfo('Бронирование удалено', 'ok');
  } catch (error) {
    showInfo('Не удалось удалить: ' + error.message, 'error');
  }
}

function updateCounter() {
  const rows = tbody.querySelectorAll('tr');
  const done = tbody.querySelectorAll('tr.done');
  counter.textContent = 'Всего бронирований: ' + rows.length +
                        ' (выполнено: ' + done.length + ')';
  counter.hidden = rows.length === 0;
}

function showInfo(message, type) {
  info.textContent = message;
  info.className = 'info ' + (type || '');
  info.hidden = false;
}

// ============================================
// Шаг 2. Обработка событий
// ============================================

async function onFormSubmit(event) {
  event.preventDefault();

  const title = titleInput.value.trim();
  if (title.length < 3) {
    formError.textContent = 'Название тура должно быть не короче 3 символов';
    formError.hidden = false;
    return;
  }
  formError.hidden = true;

  const status = statusSelect.value;
  const priority = prioritySelect.value;

  try {
    await createBookingOnServer(title, status, priority);
    await refreshList();

    titleInput.value = '';
    statusSelect.value = 'new';
    prioritySelect.value = 'high';
    showInfo('Бронирование добавлено', 'ok');

  } catch (error) {
    if (error.status === 422) {
      formError.textContent = error.message;
      formError.hidden = false;
    } else {
      showInfo('Не удалось добавить: ' + error.message, 'error');
    }
  }
}

function onLoadClick() {
  console.log('Кнопка «Загрузить»');
  refreshList();
}

function onFilterChange() {
  console.log('Фильтр:', filterStatus.value);
  refreshList();
}

function onSearchInput() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    console.log('Поиск:', searchInput.value);
    refreshList();
  }, 300);
}

// ============================================
// Шаг 4. Асинхронные запросы
// ============================================

async function loadBookings() {
  const params = new URLSearchParams();
  if (filterStatus.value)       params.append('status', filterStatus.value);
  if (searchInput.value.trim()) params.append('q', searchInput.value.trim());

  const queryString = params.toString();
  const url = '/api/bookings' + (queryString ? '?' + queryString : '');

  const response = await fetch(url);
  if (!response.ok) {
    throw new Error('Ошибка сервера: ' + response.status);
  }
  return await response.json();
}

async function createBookingOnServer(title, status, priority) {
  const response = await fetch('/api/bookings', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ title, status, priority })
  });

  if (response.status === 422) {
    const errorData = await response.json();
    const error = new Error(errorData.error);
    error.status = 422;
    throw error;
  }
  if (!response.ok) {
    throw new Error('Ошибка сервера: ' + response.status);
  }
  return await response.json();
}

async function deleteBookingOnServer(id) {
  const response = await fetch('/api/bookings/' + id, {
    method: 'DELETE'
  });
  if (!response.ok) {
    throw new Error('Ошибка сервера: ' + response.status);
  }
  return await response.json();
}

async function refreshList() {
  const requestId = ++currentRequestId;

  showInfo('Загрузка…', 'loading');
  loadButton.disabled = true;

  try {
    const data = await loadBookings();

    if (requestId !== currentRequestId) return;

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
    if (requestId !== currentRequestId) return;
    table.hidden = true;
    counter.hidden = true;
    showInfo('Не удалось загрузить данные: ' + error.message, 'error');
    console.error(error);

  } finally {
    if (requestId === currentRequestId) {
      loadButton.disabled = false;
    }
  }
}

// ============================================
// Подключение обработчиков
// ============================================

form.addEventListener('submit', onFormSubmit);
loadButton.addEventListener('click', onLoadClick);
filterStatus.addEventListener('change', onFilterChange);
searchInput.addEventListener('input', onSearchInput);

// Автозагрузка
refreshList();