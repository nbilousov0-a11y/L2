// ============================================
// Движок учебного сервера (mock API)
// НЕ ИЗМЕНЯТЬ логику, но поддерживает :id и коды ошибок
// ============================================

(function () {
  let delayMin = 300;
  let delayMax = 800;
  let errorRate = 0;
  let networkDown = false;

  function delay() {
    const ms = delayMin + Math.random() * (delayMax - delayMin);
    return new Promise(resolve => setTimeout(resolve, ms));
  }

  function makeResponse(status, body) {
    return new Response(JSON.stringify(body), {
      status: status,
      headers: { 'Content-Type': 'application/json' }
    });
  }

  function filterList(list, query) {
    if (!query) return list;
    return list.filter(item => {
      return Object.keys(query).every(key => {
        const value = query[key];
        if (value === '' || value === undefined || value === null) return true;
        if (key === 'q') {
          return Object.values(item).some(v =>
            String(v).toLowerCase().includes(String(value).toLowerCase())
          );
        }
        return String(item[key]) === String(value);
      });
    });
  }

  window.filterList = filterList;

  window.fetch = async function (url, options = {}) {
    if (networkDown) {
      await delay();
      throw new TypeError('Failed to fetch');
    }

    await delay();

    if (errorRate > 0 && Math.random() < errorRate) {
      return makeResponse(500, { error: 'Внутренняя ошибка сервера' });
    }

    const [path, queryString] = url.split('?');
    const method = (options.method || 'GET').toUpperCase();
    const key = method + ' ' + path;

    const query = {};
    if (queryString) {
      queryString.split('&').forEach(pair => {
        const [k, v] = pair.split('=');
        query[decodeURIComponent(k)] = decodeURIComponent(v || '');
      });
    }

    let body = null;
    if (options.body) {
      try { body = JSON.parse(options.body); } catch (e) { body = options.body; }
    }

    // Ищем обработчик: точное совпадение или шаблон с :id
    let handler = ROUTES[key];
    let params = {};

    if (!handler) {
      const routeKey = Object.keys(ROUTES).find(r => {
        if (!r.includes(':')) return false;
        const [rMethod, rPath] = r.split(' ');
        if (rMethod !== method) return false;
        const pattern = new RegExp(
          '^' + rPath.replace(/:[^/]+/g, '([^/]+)') + '$'
        );
        const match = path.match(pattern);
        if (match) {
          const paramNames = (rPath.match(/:[^/]+/g) || []).map(s => s.slice(1));
          paramNames.forEach((name, i) => { params[name] = match[i + 1]; });
          return true;
        }
        return false;
      });
      if (routeKey) handler = ROUTES[routeKey];
    }

    if (!handler) {
      return makeResponse(404, {
        error: 'Нет строки в таблице ROUTES: ' + key
      });
    }

    try {
      const result = handler(query, body, params);
      return makeResponse(200, result);
    } catch (e) {
      const status = e.status || 500;
      return makeResponse(status, { error: e.message });
    }
  };

  window.MockApi = {
    setDelay(min, max) { delayMin = min; delayMax = max; },
    setErrorRate(rate) { errorRate = rate; },
    setNetworkDown(value) { networkDown = value; }
  };
})();