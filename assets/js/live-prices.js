/**
 * AlphaEdge · Live Prices
 *   Crypto          → CoinGecko public API (free, CORS, works globally)
 *   Stocks & Indices → our PHP proxy (api/prices.php) — no CORS, no key
 */

(function () {
  'use strict';

  const API_BASE = (window.ALPHAEDGE && window.ALPHAEDGE.api) || '';

  // CoinGecko uses lowercase IDs; we map them to our display symbols
  const CRYPTO = [
    { id: 'bitcoin',     symbol: 'BTC',  name: 'Bitcoin',   color: '#f7931a' },
    { id: 'ethereum',    symbol: 'ETH',  name: 'Ethereum',  color: '#627eea' },
    { id: 'solana',      symbol: 'SOL',  name: 'Solana',    color: '#14f195' },
    { id: 'binancecoin', symbol: 'BNB',  name: 'BNB',       color: '#f0b90b' },
    { id: 'ripple',      symbol: 'XRP',  name: 'XRP',       color: '#23292f' },
    { id: 'dogecoin',    symbol: 'DOGE', name: 'Dogecoin',  color: '#c2a633' },
    { id: 'cardano',     symbol: 'ADA',  name: 'Cardano',   color: '#0033ad' },
    { id: 'pepe',        symbol: 'PEPE', name: 'Pepe',      color: '#3d8130' },
  ];

  const STOCKS = [
    { symbol: 'AAPL',  name: 'Apple Inc.',         color: '#a2aaad' },
    { symbol: 'MSFT',  name: 'Microsoft Corp.',    color: '#0078d4' },
    { symbol: 'NVDA',  name: 'NVIDIA Corp.',       color: '#76b900' },
    { symbol: 'TSLA',  name: 'Tesla Inc.',         color: '#e82127' },
    { symbol: 'AMD',   name: 'Adv. Micro Devices', color: '#ed1c24' },
    { symbol: 'META',  name: 'Meta Platforms',     color: '#0866ff' },
    { symbol: 'GOOGL', name: 'Alphabet Inc.',      color: '#4285f4' },
    { symbol: 'AMZN',  name: 'Amazon.com Inc.',    color: '#ff9900' },
    { symbol: 'NFLX',  name: 'Netflix Inc.',       color: '#e50914' },
  ];

  const INDICES = [
    { symbol: '^GSPC', display: 'SPX', name: 'S&P 500',              color: '#e50914' },
    { symbol: '^NDX',  display: 'NDX', name: 'US 100 Index',         color: '#0ecb81' },
    { symbol: '^DJI',  display: 'DJI', name: 'Dow Jones Industrial', color: '#3b82f6' },
    { symbol: '^VIX',  display: 'VIX', name: 'Volatility Index',     color: '#f0b90b' },
  ];

  const state = { prices: {}, intervalId: null, polling: false };
  const listeners = new Set();

  function emit() {
    listeners.forEach(fn => { try { fn(state.prices); } catch (e) { console.error(e); } });
    window.dispatchEvent(new CustomEvent('liveprices:update', { detail: state.prices }));
  }

  /* ---------- Crypto: CoinGecko ---------- */
  async function fetchCrypto() {
    const ids = CRYPTO.map(c => c.id).join(',');
    const url = `https://api.coingecko.com/api/v3/coins/markets`
              + `?vs_currency=usd&ids=${encodeURIComponent(ids)}`;

    try {
      const r = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!r.ok) throw new Error('HTTP ' + r.status);
      const data = await r.json();

      data.forEach(row => {
        const meta = CRYPTO.find(c => c.id === row.id);
        if (!meta) return;
        state.prices[meta.symbol] = {
          symbol: meta.symbol,
          name: meta.name,
          type: 'crypto',
          color: meta.color,
          price: row.current_price ?? 0,
          change: row.price_change_24h ?? 0,
          changePct: row.price_change_percentage_24h ?? 0,
          high: row.high_24h ?? row.current_price,
          low: row.low_24h ?? row.current_price,
          volume: row.total_volume ?? 0,
        };
      });
      console.log('✅ Crypto loaded:', Object.keys(state.prices).length);
    } catch (err) {
      console.warn('❌ Crypto fetch failed:', err.message);
    }
  }

  /* ---------- Stocks & Indices: PHP proxy ---------- */
  async function fetchStocks() {
    const all = [...STOCKS, ...INDICES];
    const symbols = all.map(s => s.symbol).join(',');
    const url = `${API_BASE}/api/prices.php?symbols=${encodeURIComponent(symbols)}`;

    try {
      const r = await fetch(url);
      if (!r.ok) throw new Error('HTTP ' + r.status);
      const data = await r.json();

      let loaded = 0;
      all.forEach(meta => {
        const q = data[meta.symbol];
        if (!q || !q.price) return;
        const display = meta.display || meta.symbol;
        state.prices[display] = {
          symbol: display,
          name: meta.name,
          type: INDICES.includes(meta) ? 'index' : 'stock',
          color: meta.color,
          price: q.price,
          change: q.change,
          changePct: q.changePct,
          high: q.high,
          low: q.low,
          volume: q.volume,
        };
        loaded++;
      });
      console.log('✅ Stocks & indices loaded:', loaded);
    } catch (err) {
      console.warn('❌ Stocks fetch failed:', err.message);
    }
  }

  /* ---------- Public API ---------- */
  async function fetchAll() {
    await Promise.all([fetchCrypto(), fetchStocks()]);
    emit();
    return state.prices;
  }

  function start(intervalMs = 20000) {
    if (state.polling) return;
    state.polling = true;
    fetchAll();
    state.intervalId = setInterval(fetchAll, intervalMs);
  }

  function stop() {
    state.polling = false;
    if (state.intervalId) { clearInterval(state.intervalId); state.intervalId = null; }
  }

  function get(symbol) { return state.prices[symbol] || null; }
  function getAll() { return { ...state.prices }; }
  function subscribe(fn) { listeners.add(fn); return () => listeners.delete(fn); }

  window.LivePrices = { fetchAll, start, stop, get, getAll, subscribe, CRYPTO, STOCKS, INDICES };
})();