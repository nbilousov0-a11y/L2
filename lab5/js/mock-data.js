// ============================================
// Данные учебного сервера (mock API)
// Предметная область: бронирование туруслуг
// ============================================

const bookings = [
  { id: 1, title: 'Египет, Шарм-эль-Шейх', status: 'confirmed', priority: 'high'   },
  { id: 2, title: 'Турция, Анталия',       status: 'paid',      priority: 'medium' },
  { id: 3, title: 'Италия, Рим',           status: 'new',       priority: 'high'   },
  { id: 4, title: 'Испания, Барселона',    status: 'cancelled', priority: 'low'    },
  { id: 5, title: 'Таиланд, Пхукет',       status: 'confirmed', priority: 'medium' },
  { id: 6, title: 'Греция, Санторини',     status: 'new',       priority: 'high'   },
  { id: 7, title: 'ОАЭ, Дубай',            status: 'paid',      priority: 'medium' }
];

const clients = [
  { id: 1, name: 'Иванов Иван Иванович',   phone: '+7 900 111-22-33' },
  { id: 2, name: 'Петрова Анна Сергеевна', phone: '+7 900 222-33-44' }
];

// ============================================
// Таблица маршрутов: запрос -> ответ
// ============================================

const ROUTES = {
  'GET /api/bookings': function (query) {
    return filterList(bookings, query);
  },

  'GET /api/clients': function () {
    return clients;
  },

  // POST: создать бронирование
  'POST /api/bookings': function (query, body) {
    if (!body || !body.title || body.title.trim().length < 3) {
      const error = new Error('Название тура должно быть не короче 3 символов');
      error.status = 422;
      throw error;
    }
    const newBooking = {
      id: bookings.length + 1,
      title: body.title.trim(),
      status: body.status || 'new',
      priority: body.priority || 'medium'
    };
    bookings.push(newBooking);
    return newBooking;
  },

  // DELETE: удалить бронирование по id
  'DELETE /api/bookings/:id': function (query, body, params) {
    const id = Number(params.id);
    const idx = bookings.findIndex(b => b.id === id);
    if (idx === -1) {
      const error = new Error('Бронирование не найдено');
      error.status = 404;
      throw error;
    }
    const removed = bookings.splice(idx, 1)[0];
    return { ok: true, removed };
  }
};