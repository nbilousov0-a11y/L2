// ============================================
// Движок учебного сервера (mock API)
// ============================================

(function () {
  const originalFetch = window.fetch;

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

    const handler = ROUTES[key];
    if (!handler) {
      return makeResponse(404, {
        error: 'Нет строки в таблице ROUTES: ' + key
      });
    }

    try {
      const result = handler(query, body);
      return makeResponse(200, result);
    } catch (e) {
      return makeResponse(500, { error: e.message });
    }
  };

  window.MockApi = {
    setDelay(min, max) { delayMin = min; delayMax = max; },
    setErrorRate(rate) { errorRate = rate; },
    setNetworkDown(value) { networkDown = value; }
  };
})();
