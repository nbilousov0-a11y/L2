// ============================================
// Данные учебного сервера (mock API)
// Предметная область: бронирование туруслуг
// ============================================

const bookings = [
  { id: 1, title: 'Египет, Шарм-эль-Шейх',   status: 'confirmed', priority: 'high'   },
  { id: 2, title: 'Турция, Анталия',          status: 'paid',      priority: 'medium' },
  { id: 3, title: 'Италия, Рим',              status: 'new',       priority: 'high'   },
  { id: 4, title: 'Испания, Барселона',       status: 'cancelled', priority: 'low'    },
  { id: 5, title: 'Таиланд, Пхукет',          status: 'confirmed', priority: 'medium' },
  { id: 6, title: 'Греция, Санторини',        status: 'new',       priority: 'high'   },
  { id: 7, title: 'ОАЭ, Дубай',               status: 'paid',      priority: 'medium' }
];

const clients = [
  { id: 1, name: 'Иванов Иван Иванович', phone: '+7 900 111-22-33' },
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
  }

  // --- Повышенная сложность (раскомментировать при необходимости) ---
  // ,'GET /api/bookings/5': function () {
  //   return bookings.find(b => b.id === 5);
  // }
  // ,'POST /api/bookings': function (query, body) {
  //   const newBooking = { id: bookings.length + 1, ...body };
  //   bookings.push(newBooking);
  //   return newBooking;
  // }
  // ,'DELETE /api/bookings/5': function () {
  //   const idx = bookings.findIndex(b => b.id === 5);
  //   if (idx !== -1) bookings.splice(idx, 1);
  //   return { ok: true };
  // }
};
