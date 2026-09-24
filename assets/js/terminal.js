/**
 * AlphaEdge · Trading terminal
 * Buy/Sell, Market/Limit/Stop orders, live calculations,
 * TP/SL rules, open-order cancellation, position sizing.
 */

(function () {
  'use strict';

  const $form = document.getElementById('orderForm');
  if (!$form) return;

  const DATA   = window.TERMINAL_DATA || {};
  const SYMBOL = DATA.symbol || 'BTC';
  const API    = window.ASSET_API || '';
  const CSRF   = DATA.csrf || '';

  const $sideTabs      = document.querySelectorAll('.side-tab');
  const $typeTabs      = document.querySelectorAll('.type-tab');
  const $sideInput     = document.getElementById('orderSide');
  const $typeInput     = document.getElementById('orderType');
  const $priceGroup    = document.getElementById('priceGroup');
  const $priceInput    = document.getElementById('orderPrice');
  const $priceLabelText= document.getElementById('priceLabelText');
  const $helpText      = document.getElementById('orderTypeHelp');
  const $useMarketBtn  = document.getElementById('useMarketBtn');
  const $qtyInput      = document.getElementById('orderQty');
  const $totalLabel    = document.getElementById('totalLabel');
  const $finalLabel    = document.getElementById('finalLabel');
  const $estTotal      = document.getElementById('estTotal');
  const $finalTotal    = document.getElementById('finalTotal');
  const $placeBtn      = document.getElementById('placeOrderBtn');
  const $feedback      = document.getElementById('orderFeedback');
  const $openOrdersList= document.getElementById('openOrdersList');

  let currentSide = 'BUY';
  let currentType = 'MARKET';
  let currentPrice = Number(DATA.currentPrice) || 0;

  /* ============================================================
   *  FORMATTING
   * ============================================================ */
  function fmtUsd(n) {
    if (n === null || n === undefined || isNaN(n)) return '$0.00';
    const neg = n < 0;
    const v = Math.abs(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    return (neg ? '-$' : '$') + v;
  }

  /* ============================================================
   *  LIVE PRICE HOOK
   * ============================================================ */
  function setCurrentPrice(p) {
    if (!p || isNaN(p)) return;
    currentPrice = Number(p);
    recalc();
    computeSizing();
  }

  if (window.LivePrices) {
    const p = window.LivePrices.get(SYMBOL);
    if (p) setCurrentPrice(p.price);
    window.LivePrices.subscribe((prices) => {
      const live = prices[SYMBOL];
      if (live) setCurrentPrice(live.price);
    });
  }
  window.addEventListener('liveprices:update', (e) => {
    const live = e.detail && e.detail[SYMBOL];
    if (live) setCurrentPrice(live.price);
  });

  /* ============================================================
   *  SIDE TOGGLE
   * ============================================================ */
  $sideTabs.forEach(btn => {
    btn.addEventListener('click', () => {
      currentSide = btn.dataset.side;
      $sideTabs.forEach(b => b.classList.toggle('active', b === btn));
      $sideInput.value = currentSide;

      if ($totalLabel) $totalLabel.textContent = currentSide === 'BUY' ? 'Estimated cost' : 'Estimated proceeds';
      if ($finalLabel) $finalLabel.textContent = currentSide === 'BUY' ? 'You pay' : 'You receive';

      updateButtonText();
      updateHelp();
      recalc();
      computeSizing();
    });
  });

  /* ============================================================
   *  TYPE TOGGLE
   * ============================================================ */
  $typeTabs.forEach(btn => {
    btn.addEventListener('click', () => {
      currentType = btn.dataset.type;
      $typeTabs.forEach(b => b.classList.toggle('active', b === btn));
      $typeInput.value = currentType;

      if (currentType === 'MARKET') {
        $priceGroup.style.display = 'none';
        $priceInput.value = '';
      } else {
        $priceGroup.style.display = 'block';
        if (!$priceInput.value && currentPrice > 0) {
          $priceInput.value = currentPrice.toFixed(8).replace(/0+$/, '').replace(/\.$/, '');
        }
      }

      updateButtonText();
      updateHelp();
      recalc();
    });
  });

  /* ============================================================
   *  HELP TEXT + BUTTON LABEL
   * ============================================================ */
  function updateHelp() {
    if (!$helpText) return;
    const texts = {
      'LIMIT_BUY':  'Buys ' + SYMBOL + ' when the price drops to or below this level.',
      'LIMIT_SELL': 'Sells ' + SYMBOL + ' when the price rises to or above this level.',
      'STOP_BUY':   'Buys ' + SYMBOL + ' when the price rises to or above this level.',
      'STOP_SELL':  'Sells ' + SYMBOL + ' when the price drops to or below this level.'
    };
    $helpText.textContent = texts[currentType + '_' + currentSide] || '';

    if (currentType === 'LIMIT') {
      $priceLabelText.textContent = currentSide === 'BUY' ? 'Buy limit price' : 'Sell limit price';
    } else if (currentType === 'STOP') {
      $priceLabelText.textContent = currentSide === 'BUY' ? 'Stop trigger (above market)' : 'Stop trigger (below market)';
    }
  }

  function updateButtonText() {
    if (!$placeBtn) return;
    let txt = 'Place ';
    if (currentType !== 'MARKET') txt += currentType.charAt(0) + currentType.slice(1).toLowerCase() + ' ';
    else txt += 'Market ';
    txt += currentSide === 'BUY' ? 'Buy' : 'Sell';
    $placeBtn.textContent = txt;
    $placeBtn.classList.toggle('btn-primary', currentSide === 'BUY');
    $placeBtn.classList.toggle('btn-danger',  currentSide === 'SELL');
  }

  /* ============================================================
   *  USE MARKET BUTTON
   * ============================================================ */
  if ($useMarketBtn) {
    $useMarketBtn.addEventListener('click', () => {
      if (currentPrice > 0) {
        $priceInput.value = currentPrice.toFixed(8).replace(/0+$/, '').replace(/\.$/, '');
        recalc();
      }
    });
  }

  /* ============================================================
   *  PERCENTAGE BUTTONS
   * ============================================================ */
  document.querySelectorAll('.pct-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const pct = parseInt(btn.dataset.pct, 10) / 100;

      if (currentSide === 'BUY') {
        const refPrice = currentType === 'MARKET'
          ? currentPrice
          : (parseFloat($priceInput.value) || currentPrice);
        if (!refPrice || refPrice <= 0) return;
        const qty = (Number(DATA.cash) * pct) / refPrice;
        $qtyInput.value = qty.toFixed(8).replace(/0+$/, '').replace(/\.$/, '');
      } else {
        const qty = Number(DATA.held) * pct;
        $qtyInput.value = qty.toFixed(8).replace(/0+$/, '').replace(/\.$/, '');
      }
      recalc();
    });
  });

  /* ============================================================
   *  RECALCULATION
   * ============================================================ */
  function recalc() {
    if (!$qtyInput) return '';
    const qty = parseFloat($qtyInput.value) || 0;
    const refPrice = currentType === 'MARKET'
      ? currentPrice
      : (parseFloat($priceInput ? $priceInput.value : 0) || currentPrice);
    const total = qty * refPrice;

    if ($estTotal)   $estTotal.textContent = fmtUsd(total);
    if ($finalTotal) $finalTotal.textContent = fmtUsd(total);

    let error = '';
    if (qty > 0 && currentSide === 'BUY' && total > Number(DATA.cash) + 0.000001) {
      error = 'Insufficient cash balance.';
    }
    if (qty > 0 && currentSide === 'SELL' && qty > Number(DATA.held) + 0.00000001) {
      error = 'You don\'t hold enough ' + SYMBOL + '.';
    }
    if (currentType !== 'MARKET' && refPrice <= 0) {
      error = 'Please enter a valid trigger price.';
    }

    if ($finalTotal) {
      $finalTotal.style.color = error ? 'var(--red)' : 'var(--accent)';
    }
    return error;
  }

  if ($qtyInput)   $qtyInput.addEventListener('input', recalc);
  if ($priceInput) $priceInput.addEventListener('input', recalc);

  /* ============================================================
   *  FEEDBACK
   * ============================================================ */
  function showFeedback(msg, kind) {
    if (!$feedback) return;
    $feedback.textContent = msg;
    $feedback.className = 'order-feedback ' + (kind || 'info');
    $feedback.style.display = 'block';
    clearTimeout($feedback._t);
    $feedback._t = setTimeout(() => { $feedback.style.display = 'none'; }, 5000);
  }

  /* ============================================================
   *  PLACE ORDER
   * ============================================================ */
  async function placeOrder() {
    const qty = parseFloat($qtyInput.value) || 0;
    if (qty <= 0) {
      showFeedback('Please enter a valid quantity.', 'error');
      return;
    }

    const inline = recalc();
    if (inline) {
      showFeedback(inline, 'error');
      return;
    }

    const payload = {
      symbol: SYMBOL,
      side: currentSide,
      order_type: currentType,
      quantity: qty,
      trigger_price: currentType === 'MARKET' ? '' : (parseFloat($priceInput.value) || 0),
      note: document.getElementById('orderNote').value || '',
      csrf: CSRF
    };

    $placeBtn.disabled = true;
    const original = $placeBtn.textContent;
    $placeBtn.textContent = 'Placing…';

    try {
      const res = await fetch(API + '/api/order.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(payload),
      });
      const json = await res.json();
      if (!res.ok) throw new Error(json.error || 'Order failed');

      showFeedback(json.message || 'Order placed', 'success');
      $qtyInput.value = '';
      document.getElementById('orderNote').value = '';
      recalc();
      setTimeout(() => window.location.reload(), 900);
    } catch (err) {
      showFeedback(err.message, 'error');
    } finally {
      $placeBtn.disabled = false;
      $placeBtn.textContent = original;
    }
  }

  if ($placeBtn) $placeBtn.addEventListener('click', placeOrder);

  /* ============================================================
   *  CANCEL OPEN ORDER
   * ============================================================ */
  if ($openOrdersList) {
    $openOrdersList.addEventListener('click', async (e) => {
      const btn = e.target.closest('[data-cancel-order]');
      if (!btn) return;
      const id = btn.dataset.cancelOrder;
      if (!confirm('Cancel this order?')) return;
      btn.disabled = true;
      try {
        const res = await fetch(API + '/api/cancel-order.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ order_id: id, csrf: CSRF }),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.error || 'Cancel failed');
        showFeedback(json.message || 'Order cancelled', 'success');
        setTimeout(() => window.location.reload(), 700);
      } catch (err) {
        showFeedback(err.message, 'error');
        btn.disabled = false;
      }
    });
  }

  /* ============================================================
   *  TP / SL HANDLER
   * ============================================================ */
  const $saveTpSl  = document.getElementById('saveTpSlBtn');
  const $clearTpSl = document.getElementById('clearTpSlBtn');

  if ($saveTpSl) {
    $saveTpSl.addEventListener('click', async () => {
      const tp = document.getElementById('takeProfit').value.trim();
      const sl = document.getElementById('stopLoss').value.trim();

      if (!tp && !sl) {
        showFeedback('Enter at least a take-profit or stop-loss level.', 'error');
        return;
      }

      $saveTpSl.disabled = true;
      const original = $saveTpSl.textContent;
      $saveTpSl.textContent = 'Saving…';

      try {
        const res = await fetch(API + '/api/position-rules.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({
            symbol: SYMBOL,
            take_profit: tp,
            stop_loss: sl,
            csrf: CSRF,
          }),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.error || 'Save failed');
        showFeedback(json.message || 'Rules saved', 'success');
        setTimeout(() => window.location.reload(), 800);
      } catch (err) {
        showFeedback(err.message, 'error');
        $saveTpSl.disabled = false;
        $saveTpSl.textContent = original;
      }
    });
  }

  if ($clearTpSl) {
    $clearTpSl.addEventListener('click', async () => {
      if (!confirm('Clear TP/SL on ' + SYMBOL + '?')) return;
      $clearTpSl.disabled = true;
      try {
        const res = await fetch(API + '/api/position-rules.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({
            symbol: SYMBOL,
            take_profit: '',
            stop_loss: '',
            csrf: CSRF,
          }),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.error || 'Clear failed');
        showFeedback(json.message || 'Rules cleared', 'success');
        setTimeout(() => window.location.reload(), 800);
      } catch (err) {
        showFeedback(err.message, 'error');
        $clearTpSl.disabled = false;
      }
    });
  }

  /* ============================================================
   *  POSITION SIZING CALCULATOR
   * ============================================================ */
  const $calcStop        = document.getElementById('calcStopPrice');
  const $useCurrentAsStop= document.getElementById('useCurrentAsStop');
  const $sizingBudget    = document.getElementById('sizingBudget');
  const $sizingPerUnit   = document.getElementById('sizingPerUnit');
  const $sizingQty       = document.getElementById('sizingQty');
  const $sizingValue     = document.getElementById('sizingValue');
  const $sizingMaxLoss   = document.getElementById('sizingMaxLoss');
  const $sizingWarning   = document.getElementById('sizingWarning');
  const $applySizing     = document.getElementById('applySizingBtn');

  const equity        = Number(DATA.equity) || 0;
  const riskPct       = Number(DATA.riskPerTradePct) || 1;
  const maxHeat       = Number(DATA.maxPortfolioHeat) || 6;
  const maxPosPct     = Number(DATA.maxPositionPct) || 20;

  let lastSuggestedQty = 0;

  function computeSizing() {
    if (!$calcStop) return;
    const stop = parseFloat($calcStop.value) || 0;

    if (stop <= 0 || currentPrice <= 0) {
      if ($sizingBudget)  $sizingBudget.textContent  = '—';
      if ($sizingPerUnit) $sizingPerUnit.textContent = '—';
      if ($sizingQty)     $sizingQty.textContent     = '—';
      if ($sizingValue)   $sizingValue.textContent   = '—';
      if ($sizingMaxLoss) $sizingMaxLoss.textContent = '—';
      if ($sizingWarning) $sizingWarning.style.display = 'none';
      lastSuggestedQty = 0;
      return;
    }

    if (currentSide === 'SELL') {
      if ($sizingWarning) {
        $sizingWarning.textContent = 'Position sizing is designed for BUY trades. Use the SELL tab to exit positions.';
        $sizingWarning.style.display = 'block';
        $sizingWarning.className = 'sizing-warning warn';
      }
      return;
    }

    if ($sizingWarning) $sizingWarning.style.display = 'none';

    const riskPerUnit = Math.abs(currentPrice - stop);
    const riskBudget  = equity * (riskPct / 100);
    const suggestedQty = riskPerUnit > 0 ? riskBudget / riskPerUnit : 0;
    const positionValue = suggestedQty * currentPrice;

    const maxPosValue = equity * (maxPosPct / 100);
    let capped = false;
    let finalQty = suggestedQty;
    let capReason = '';

    if (positionValue > maxPosValue) {
      finalQty = maxPosValue / currentPrice;
      capped = true;
      capReason = 'Position capped at ' + maxPosPct.toFixed(1) + '% of equity (your max position size).';
    }

    lastSuggestedQty = finalQty;

    if ($sizingBudget)  $sizingBudget.textContent  = fmtUsd(riskBudget);
    if ($sizingPerUnit) $sizingPerUnit.textContent = fmtUsd(riskPerUnit);
    if ($sizingQty)     $sizingQty.textContent     = finalQty.toFixed(8).replace(/0+$/, '').replace(/\.$/, '');
    if ($sizingValue)   $sizingValue.textContent   = fmtUsd(finalQty * currentPrice);
    if ($sizingMaxLoss) $sizingMaxLoss.textContent = fmtUsd(finalQty * riskPerUnit);

    if (finalQty * currentPrice > Number(DATA.cash)) {
      if ($sizingWarning) {
        $sizingWarning.textContent = '⚠️ This position exceeds your available cash (' + fmtUsd(Number(DATA.cash)) + ').';
        $sizingWarning.className = 'sizing-warning error';
        $sizingWarning.style.display = 'block';
      }
      return;
    }

    if (capped) {
      if ($sizingWarning) {
        $sizingWarning.textContent = 'ℹ️ ' + capReason;
        $sizingWarning.className = 'sizing-warning warn';
        $sizingWarning.style.display = 'block';
      }
    }
  }

  if ($calcStop) $calcStop.addEventListener('input', computeSizing);

  if ($useCurrentAsStop) {
    $useCurrentAsStop.addEventListener('click', () => {
      if (currentPrice > 0) {
        const stop = currentPrice * 0.95;
        $calcStop.value = stop.toFixed(8).replace(/0+$/, '').replace(/\.$/, '');
        computeSizing();
      }
    });
  }

  if ($applySizing) {
    $applySizing.addEventListener('click', () => {
      if (lastSuggestedQty <= 0) {
        showFeedback('Enter a stop-loss price to compute a position size.', 'error');
        return;
      }
      $qtyInput.value = lastSuggestedQty.toFixed(8).replace(/0+$/, '').replace(/\.$/, '');
      recalc();
      showFeedback('Quantity applied — review before placing the order.', 'success');
    });
  }

  /* ============================================================
   *  INIT
   * ============================================================ */
  updateHelp();
  updateButtonText();
  recalc();
  computeSizing();
})();