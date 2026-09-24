-- ============================================================
--  AlphaEdge Academy — Full curriculum content
-- ============================================================

-- Clear old placeholder content
DELETE FROM academy_lessons;
DELETE FROM academy_courses;
DELETE FROM academy_badges;

-- ============================================================
--  BADGES
-- ============================================================
INSERT INTO academy_badges (name, icon, description) VALUES
('First Steps',       '🐣', 'Completed your first lesson'),
('Student',           '📖', 'Completed 5 lessons'),
('Scholar',           '📚', 'Completed 15 lessons'),
('Basic Graduate',    '🎓', 'Finished the Basic stage'),
('Intermediate Pro',  '🎯', 'Finished the Intermediate stage'),
('Advanced Master',   '🏆', 'Finished the Advanced stage'),
('Strategist',        '⚔️', 'Finished the Strategies stage'),
('Perfect Student',   '💎', 'Completed every lesson'),
('First Trade',       '💰', 'Placed your first paper trade'),
('Verified',          '🪪', 'Completed KYC verification'),
('Secured',           '🔐', 'Enabled two-factor authentication'),
('Diversified',       '🧺', 'Held 5 or more positions at once');

-- ============================================================
--  BASIC COURSES
-- ============================================================
INSERT INTO academy_courses (stage, title, description, icon, sort_order) VALUES
('basic', 'What Are Financial Markets?', 'A beginner tour of stocks, crypto, forex, and how trading actually works.', '🌐', 1),
('basic', 'How to Read Candlestick Charts', 'The single most important chart in trading — what each candle tells you.', '🕯️', 2),
('basic', 'Crypto vs Stocks vs Indices', 'Understand the differences, risks, and opportunities across asset classes.', '⚖️', 3),
('basic', 'Your First Paper Trade', 'Execute your first trade step-by-step using the AlphaEdge terminal.', '💵', 4);

-- ============================================================
--  INTERMEDIATE COURSES
-- ============================================================
INSERT INTO academy_courses (stage, title, description, icon, sort_order) VALUES
('intermediate', 'Support, Resistance & Market Structure', 'Where price reacts and why — the core of reading charts.', '📐', 1),
('intermediate', 'Technical Indicators Explained', 'RSI, MACD, Bollinger Bands, moving averages — what they mean and how to use them.', '📊', 2),
('intermediate', 'Risk Management Fundamentals', 'Position sizing, stop-losses, R:R ratios, and portfolio heat.', '🛡️', 3),
('intermediate', 'Psychology of Trading', 'Emotions, discipline, and the habits of consistently good traders.', '🧠', 4);

-- ============================================================
--  ADVANCED COURSES
-- ============================================================
INSERT INTO academy_courses (stage, title, description, icon, sort_order) VALUES
('advanced', 'Advanced Chart Patterns', 'Head & shoulders, wedges, flags, triangles, and how to trade them.', '📈', 1),
('advanced', 'Multi-Timeframe Analysis', 'How professionals analyze H4 → H1 → M15 together for entry timing.', '🕰️', 2),
('advanced', 'Order Flow & Microstructure', 'Order books, volume, and what large orders reveal about direction.', '📖', 3),
('advanced', 'Portfolio Management & Correlation', 'Diversification, correlations, and risk-adjusted position sizing.', '🧺', 4);

-- ============================================================
--  STRATEGIES COURSES
-- ============================================================
INSERT INTO academy_courses (stage, title, description, icon, sort_order) VALUES
('strategies', 'Trend Following Strategies', 'SMA crossovers, breakouts, and how to ride long moves.', '🚀', 1),
('strategies', 'Mean Reversion Strategies', 'Buying dips, fading overextensions, and when reversion fails.', '🔄', 2),
('strategies', 'Breakout & Momentum Strategies', 'Range breakouts, volume confirmation, and false-break filters.', '⚡', 3),
('strategies', 'Build & Backtest Your Own Strategy', 'Put everything together. Design, test, and refine a strategy.', '🧪', 4);

-- ============================================================
--  LESSONS: markets-101
-- ============================================================
SET @c := (SELECT id FROM academy_courses WHERE title = 'What Are Financial Markets?');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'What Is a Financial Market?',
'WHAT IS A FINANCIAL MARKET?

A financial market is a system where buyers and sellers exchange assets at prices determined by supply and demand.

THE MAIN ASSET CLASSES:

1. STOCKS — Ownership in a company
   When you buy AAPL, you own a tiny piece of Apple. You profit if the company grows.
   Examples: AAPL, MSFT, NVDA, TSLA

2. CRYPTOCURRENCIES — Digital assets on a blockchain
   Decentralized, trade 24/7, extremely volatile.
   Examples: BTC, ETH, SOL, DOGE

3. FOREX — Currency pairs
   The largest market in the world — trillions per day.
   Examples: EUR/USD, GBP/JPY, USD/NGN

4. INDICES — A basket of stocks as one number
   Examples: S&P 500 (SPX), Nasdaq 100 (NDX), Dow Jones (DJI)

5. COMMODITIES — Physical goods
   Examples: Gold, Oil, Silver, Natural Gas

6. DERIVATIVES — Contracts based on other assets
   Futures, options, CFDs. Advanced. Not for beginners.

WHO PARTICIPATES?
- Retail traders (you)
- Institutional funds (banks, hedge funds)
- Market makers (provide liquidity, earn the spread)
- Arbitrageurs (keep prices consistent across markets)

WHY PRICES MOVE:
Price reflects the balance between BUYING PRESSURE and SELLING PRESSURE.
When more people want to buy than sell → price rises.
When more want to sell than buy → price falls.
News, earnings, and indicators are just drivers of that imbalance.

KEY TAKEAWAY:
Every market does the same fundamental thing — matching buyers and sellers. Once you understand this, you can analyze any asset class with the same framework.',
'https://www.youtube.com/watch?v=PHe0bXAIuk0', 1),

(@c, 'How Trades Actually Work',
'HOW TRADES ACTUALLY WORK

Every trade has TWO sides:

• BID — the highest price a buyer is willing to pay
• ASK — the lowest price a seller is willing to accept
• SPREAD — the gap between them

If BTC is quoted "76,234 / 76,240":
- Bid = $76,234 (you can sell here)
- Ask = $76,240 (you can buy here)
- Spread = $6

Tight spreads = liquid market = good for retail.
Wide spreads = illiquid = expensive to trade.

THE ORDER TYPES YOU WILL USE:

1. MARKET ORDER
   Buys or sells immediately at the best available price.
   Fast. Guaranteed fill. But price is not guaranteed.

2. LIMIT ORDER
   Buy below / sell above a specific price.
   Slower. Not guaranteed fill. Price is guaranteed if filled.

3. STOP ORDER
   Triggered only when price crosses a threshold.
   Used for stop-losses and breakout entries.

4. STOP-LIMIT
   A stop that places a limit order when triggered.
   More control, but can miss fills in fast markets.

FEES:
Exchanges charge per trade (usually 0.05% – 0.3%). They add up. In AlphaEdge demo, fees are off.

LIQUIDITY:
BTC and AAPL have billions in daily volume — tight spreads.
Small altcoins might have 0.5% spreads, meaning you lose 0.5% the moment you buy.

KEY TAKEAWAY:
Always know your bid, ask, and spread before trading. In thin markets, use limit orders.',
'https://www.youtube.com/watch?v=eezn8hcVIRU', 2),

(@c, 'Bull vs Bear Markets',
'BULL VS BEAR MARKETS

BULL MARKET
Prices rising over months or years. Optimism dominant. Buyers in control.
Signs:
- Higher highs and higher lows
- Positive news flow
- Volume higher on up days than down days

BEAR MARKET
Prices falling 20%+ from recent highs. Pessimism dominant. Sellers in control.
Signs:
- Lower highs and lower lows
- Rallies get sold aggressively
- High volume on down days

WHY IT MATTERS:
Strategies that work in one fail in the other.
- "Buy the dip" works in bulls, gets crushed in bears
- Trend-following works in both but requires discipline

WHAT TO DO:
1. Identify the higher-timeframe trend (daily or weekly)
2. Trade WITH that trend, not against it
3. Reduce size in bear markets — volatility is higher

SIDEWAYS / RANGE MARKET
Price oscillates between two levels. Neither bulls nor bears dominate.
Range-trading strategies work here: buy support, sell resistance.

KEY TAKEAWAY:
80% of retail losses come from trading against the higher-timeframe trend. Always check the big picture first.',
'https://www.youtube.com/watch?v=0QdLwoxnwoo', 3);

-- ============================================================
--  LESSONS: candlesticks
-- ============================================================
SET @c := (SELECT id FROM academy_courses WHERE title = 'How to Read Candlestick Charts');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Anatomy of a Candlestick',
'ANATOMY OF A CANDLESTICK

Each candle shows FOUR prices for a period of time (1 minute, 1 hour, 1 day, etc.):

• OPEN — first traded price in the period
• HIGH — highest traded price
• LOW — lowest traded price
• CLOSE — last traded price

THE BODY:
The rectangle between open and close.

GREEN CANDLE (bullish)
Close is ABOVE open. Buyers won the period.

RED CANDLE (bearish)
Close is BELOW open. Sellers won the period.

THE WICKS (shadows)
The thin lines above and below the body.
They show the extremes — where price was rejected.

INTERPRETING WICKS:
- Long upper wick = buyers tried to push up, got rejected
- Long lower wick = sellers tried to push down, got rejected
- No wicks (marubozu) = full conviction in one direction

TIMEFRAMES:
The chart timeframe tells you how much time each candle covers.
1m chart = 1 minute per candle
1h chart = 1 hour per candle
1d chart = 1 day per candle

A single 1-hour candle contains 60 one-minute candles.

KEY TAKEAWAY:
A candle is a story — it tells you what happened between four prices, and who won.

CHART EXAMPLE:
Look at any BTC candle on the AlphaEdge chart. The body shows the battle; the wicks show where the losing side was pushed back.',
'https://www.youtube.com/watch?v=lEk4cSA7cqc', 1),

(@c, 'Five Key Candlestick Patterns',
'FIVE KEY CANDLESTICK PATTERNS

1. DOJI
Tiny body, wicks on both sides. Indecision.
Where it matters: after a long trend, at support/resistance.
Meaning: potential reversal or continuation pause.

2. HAMMER
Small body at top, long lower wick (at least 2× the body).
Where it matters: after a downtrend, at support.
Meaning: bullish reversal. Sellers pushed down, buyers took over.

3. SHOOTING STAR
Small body at bottom, long upper wick.
Where it matters: after an uptrend, at resistance.
Meaning: bearish reversal. Buyers pushed up, sellers took over.

4. ENGULFING
A large candle that completely covers the previous candle''s body.
Bullish engulfing = green candle covers prior red.
Bearish engulfing = red candle covers prior green.
Meaning: strong shift in control.

5. MARUBOZU
No wicks. Full body.
Meaning: extreme conviction. Everyone agreed on direction.

CRITICAL RULE:
Candles mean nothing on their own.
A hammer in the middle of a range is noise.
A hammer at support after a downtrend is a signal.

Always ask: WHERE did this candle form?',
'https://www.youtube.com/watch?v=2uXnfc-SCwI', 2),

(@c, 'Timeframes and Aggregation',
'TIMEFRAMES AND AGGREGATION

Candles aggregate. A 1-hour candle is 60 one-minute candles combined.

OPEN  = open of the FIRST 1m candle
HIGH  = highest high of all 60
LOW   = lowest low of all 60
CLOSE = close of the LAST 1m candle

WHY THIS MATTERS:
- 1m candles are NOISY — most moves are random noise
- 15m / 1h candles show intraday structure
- 4h / 1d candles show the REAL trend
- Weekly candles show the macro trend

THE PROBLEM WITH LOW TIMEFRAMES:
More candles = more noise = more fake signals.
Trading off the 1m chart is a beginner mistake.

THE PROFESSIONAL APPROACH (TOP-DOWN):
1. Daily or 4H → what is the overall trend?
2. 1H → where is the setup forming?
3. 15m or 5m → precise entry and stop

AlphaEdge lets you switch timeframes with one click on the asset page.

KEY TAKEAWAY:
Trade in the direction of the higher timeframe. Use the lower timeframe only for entry timing.',
'https://www.youtube.com/watch?v=prhtC_9E7uU', 3);

-- ============================================================
--  LESSONS: crypto-vs-stocks
-- ============================================================
SET @c := (SELECT id FROM academy_courses WHERE title = 'Crypto vs Stocks vs Indices');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Crypto: High Risk, High Reward',
'CRYPTO: HIGH RISK, HIGH REWARD

Cryptocurrencies are decentralized digital assets secured by blockchain cryptography.

CATEGORIES:
1. Store of value — BTC (digital gold)
2. Smart contract platforms — ETH, SOL
3. Meme coins — DOGE, PEPE (pure speculation)
4. Stablecoins — USDT, USDC (pegged to $1)
5. DeFi tokens — UNI, AAVE

WHAT MAKES CRYPTO DIFFERENT:
- Trades 24/7 — no market close
- Extreme volatility — 10% daily moves are normal
- Less regulation — more fraud, more freedom
- Zero earnings — price is purely supply/demand + sentiment
- Can go to $0

VOLATILITY COMPARISON:
- S&P 500 typical daily range: 0.5–1%
- BTC typical daily range: 2–5%
- Small altcoins: 10–30%

SIZING RULE:
Never risk more than 5–10% of your total portfolio on crypto.
Never put money in crypto you cannot afford to lose entirely.

WHEN CRYPTO IS ATTRACTIVE:
- Bull markets with strong momentum
- When you want asymmetric upside
- High time preference (long horizon)

WHEN TO AVOID:
- Bear markets (everything drops 70%+)
- If you need the capital within 1–2 years',
'https://www.youtube.com/watch?v=uevqxHmz5iM', 1),

(@c, 'Stocks: Ownership in a Company',
'STOCKS: OWNERSHIP IN A COMPANY

A stock is a share of ownership in a business. When you buy AAPL, you own a fractional slice of Apple.

WHY STOCKS GO UP OVER TIME:
Companies grow. They hire, sell more, expand into new markets. Shareholders capture that growth.

KEY METRICS:
- Market cap: total value = price × shares outstanding
- P/E ratio: price ÷ earnings per share (valuation)
- EPS: earnings per share
- Dividend yield: annual dividend ÷ price (%)
- ROE: return on equity (profitability)

WHAT MOVES STOCK PRICES:
1. Quarterly earnings (4× per year per company)
2. Guidance for future quarters
3. Product launches, partnerships
4. Macro (interest rates, inflation, GDP)
5. Sector sentiment (AI hype, EV hype, etc.)

DIFFERENT FROM CRYPTO:
- Regulated (SEC, FCA)
- Companies have real earnings and assets
- Pay dividends
- Have "catalyst events" (earnings, launches)
- Market hours only (9:30am–4pm ET)

WHY MOST PROFESSIONALS PREFER STOCKS:
They have intrinsic value. You can analyze them fundamentally, not just chart them.',
'https://www.youtube.com/watch?v=p7HKvqRl_Bo', 2),

(@c, 'Indices: The Big Picture',
'INDICES: THE BIG PICTURE

An index tracks a basket of stocks as a single number.

POPULAR INDICES:
- S&P 500 (SPX): 500 largest US companies, cap-weighted
- Nasdaq 100 (NDX): 100 largest non-financial Nasdaq stocks
- Dow Jones (DJI): 30 major US companies, price-weighted
- VIX: volatility index — the "fear gauge" of the S&P

WHY TRADE INDICES?
1. Diversification without picking individual stocks
2. Cleaner technical patterns (aggregated)
3. Lower single-company blow-up risk
4. Broad market sentiment captured in one number

HOW INDICES ARE TRADED:
You can''t buy an index directly. You trade:
- ETFs (SPY, QQQ) that track it
- Futures (ES, NQ)
- CFDs

VIX — THE FEAR GAUGE:
- Below 15: complacency, low volatility
- 15–25: normal
- Above 30: fear, panic
- Above 50: crisis-level fear

VIX spikes when markets crash. Traders use it to gauge sentiment.

WHEN SOMEONE SAYS "THE MARKET IS UP":
They almost always mean an index — usually the S&P 500 or Nasdaq.',
'https://www.youtube.com/watch?v=R2ZFgLROtTY', 3);

-- ============================================================
--  LESSONS: first-trade
-- ============================================================
SET @c := (SELECT id FROM academy_courses WHERE title = 'Your First Paper Trade');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Tour the Trading Terminal',
'TOUR THE TRADING TERMINAL

Open any asset page (for example BTC) from the Markets page.

THE LAYOUT:

LEFT PANEL — ORDER FORM
• Buy / Sell tabs
• Market / Limit / Stop order types
• Quantity input with 25% / 50% / 75% / MAX buttons
• Position sizing calculator (tells you how many units to buy based on risk)
• Order summary with live cost calculation

RIGHT PANEL — MARKET DATA
• Live order book with bids and asks
• Time & Sales (the tape) showing every trade
• Open orders list

CENTER — PRICE CHART
• Range tabs (1D → MAX) — how far back
• Timeframe tabs (1m → 1W) — size of each candle
• Chart type buttons (Candles / HA / Renko / Line / Area)
• Indicator toggles (SMA, EMA, Bollinger, RSI, MACD)
• Drawing tools (trendline, horizontal, rect, fib)
• Tools (PDH/PDL, Sessions, Volume Profile, Bar Replay)

BELOW THE CHART
• Open Orders (limit/stop orders waiting to fill)
• Trade History (your past fills)

GET FAMILIAR:
Before placing any trade, click every button once. Understand what each does.',
'https://www.youtube.com/watch?v=FGA-KJEDB6', 1),

(@c, 'Place Your First Trade',
'PLACE YOUR FIRST TRADE

STEP 1 — Open BTC asset page
Click Markets → BTC → you are on the asset page.

STEP 2 — On the order form
Keep Buy and Market selected.

STEP 3 — Enter quantity
For BTC, enter 0.001 (about $75).
Small size to learn without emotion.

STEP 4 — Verify
The order summary shows the estimated cost. Make sure it matches your intent.

STEP 5 — Click "Place Market Buy"
The order fills immediately at the current price.

STEP 6 — Confirm the fill
You see a green toast. Your cash drops. Your holding appears in Portfolio.

STEP 7 — To exit
Go to Portfolio, click the BTC holding. Sell modal opens.
Enter quantity and confirm.

STEP 8 — Review the trade
Open the Trading Journal from the sidebar. Add notes, tags, emotions.

KEY RULE:
Start with tiny size. Your first 10 trades are for LEARNING, not profit.',
'https://www.youtube.com/watch?v=p7HKvqRl_Bo', 2),

(@c, 'Review Every Trade',
'REVIEW EVERY TRADE

After every trade, answer these 5 questions:

1. WHY DID I ENTER?
   Was it a specific setup? Or a random feeling?
   Good answer: "RSI < 30 at support with bullish reversal candle."
   Bad answer: "It felt like it was going up."

2. WHERE WAS MY STOP-LOSS?
   Did you have a defined exit point?
   Without a stop, you are gambling.

3. WHAT WAS THE RISK?
   In dollars and percent.
   If you risked > 2% of your account on one trade, you are oversizing.

4. HOW DID I FEEL?
   Calm, patient, confident? Or FOMO, anxious, revengeful?
   Emotions are data.

5. WHAT WOULD I DO DIFFERENTLY?
   Maybe nothing. Maybe size up. Maybe skip the trade entirely.

USE THE TRADING JOURNAL:
AlphaEdge has a built-in journal.
Open it from the sidebar and log every trade.

Fields:
- Setup type (Breakout, Reversal, Trend Continuation, etc.)
- Emotion (Confident, FOMO, Anxious, Greedy, Fearful)
- Notes (what you saw, what you did, what happened)
- Tags (custom labels like "BTC-4H", "Fibonacci-618")

WHY IT WORKS:
Traders who journal improve 2–3× faster than those who do not.
Because they see PATTERNS in their own behavior.',
'https://www.youtube.com/watch?v=3zxYgZmVnU4', 3);

-- ============================================================
--  INTERMEDIATE LESSONS
-- ============================================================
SET @c := (SELECT id FROM academy_courses WHERE title = 'Support, Resistance & Market Structure');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Support & Resistance',
'SUPPORT & RESISTANCE

SUPPORT = a price level where buyers repeatedly step in and stop the fall.
RESISTANCE = a price level where sellers repeatedly step in and cap the rise.

HOW TO DRAW THEM:
1. Look for areas where price reversed 2+ times
2. Draw ZONES, not exact lines (price is fractal)
3. Higher timeframes = stronger levels (Daily > 1H > 15m)
4. Round numbers matter (BTC at $80,000, SPX at 5,000)

THE MAGIC OF FLIP:
- Broken resistance often becomes new support
- Broken support often becomes new resistance
This is called a "flip" and is one of the highest-probability patterns.

HOW TO TRADE THEM:
BUY AT SUPPORT when:
- Price rejects the level (long lower wick)
- Volume dries up on the approach
- A bullish reversal candle forms
- Stop goes just below the level

SELL AT RESISTANCE when:
- Price rejects the level (long upper wick)
- Momentum weakens (RSI divergence)
- A bearish reversal candle forms

CONFLUENCE:
Two or more signals pointing to the same level = high-probability trade.

KEY TAKEAWAY:
Support and resistance are the single most useful tool for beginners.
Master these before touching indicators.',
'https://www.youtube.com/watch?v=7tsGYdL_HqI', 1),

(@c, 'Trends: Higher Highs & Lower Lows',
'TRENDS: HIGHER HIGHS & LOWER LOWS

UPTREND
Series of higher highs (HH) and higher lows (HL).

Structure:
Price makes a high, pulls back, makes a higher high, pulls back to a higher low, repeat.

DOWNTREND
Series of lower highs (LH) and lower lows (LL).
Mirror image of the uptrend.

RANGE / SIDEWAYS
Price oscillates between support and resistance. No clear directional bias.

HOW TO TRADE EACH:

UPTREND:
- Buy pullbacks to support (or to moving averages)
- Hold winners until structure breaks
- Do NOT short

DOWNTREND:
- Sell rallies into resistance
- Or stay completely out
- Do NOT buy "because it is cheap"

RANGE:
- Buy support, sell resistance
- Reduce size — breakout can happen any time

BREAK OF STRUCTURE
When price breaks the prior low in an uptrend (or prior high in a downtrend), the trend is changing.

Early recognition of BOS saves you from large losses.

KEY TAKEAWAY:
The trend is your friend until it ends. Trade WITH it, not against it.',
'https://www.youtube.com/watch?v=PrhtC_9E7uU', 2),

(@c, 'Supply & Demand Zones',
'SUPPLY & DEMAND ZONES

A DEMAND ZONE is where large buyers previously absorbed selling pressure.
A SUPPLY ZONE is where large sellers overwhelmed buyers.

HOW TO SPOT THEM:
Look for the BASE before a big move.
- Before a strong rally = demand zone (accumulation)
- Before a strong dump = supply zone (distribution)

The base is where institutions built their position.

DIFFERENCE FROM SUPPORT/RESISTANCE:
- Support/resistance are horizontal lines
- Supply/demand are ZONES (rectangles with a range)
- Zones capture the "footprint" of large players

HOW TO TRADE:

DEMAND ZONE:
- Wait for price to return to the zone
- Enter on the first bullish candle inside the zone
- Stop below the zone
- Target: prior high or 2× the zone height

SUPPLY ZONE:
- Wait for price to return
- Enter short on first bearish candle
- Stop above the zone

CONFLUENCE IS EVERYTHING:
A demand zone + a support level + a bullish candle = high-probability trade.
A demand zone in the middle of nowhere = low probability.

KEY TAKEAWAY:
Zones show WHERE institutions are likely to defend a level. That''s where you want to be.',
'https://www.youtube.com/watch?v=30petm6SZz0', 3);

-- ============================================================
--  Risk management, psychology, advanced lessons (abbreviated)
-- ============================================================
SET @c := (SELECT id FROM academy_courses WHERE title = 'Technical Indicators Explained');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Moving Averages (SMA & EMA)',
'MOVING AVERAGES (SMA & EMA)

SMA (Simple Moving Average)
Average of the last N closing prices, equal weight.
Smoother, slower to react.

EMA (Exponential Moving Average)
Recent prices weighted more heavily.
Faster to react. Preferred by traders.

COMMON SETTINGS:
- 20 period: short-term trend
- 50 period: medium-term trend
- 200 period: long-term trend (the "big picture")

HOW TRADERS USE THEM:

1. TREND FILTER
Price above SMA 200 = uptrend
Price below SMA 200 = downtrend

2. CROSSOVERS
SMA 50 crossing ABOVE SMA 200 = "golden cross" (bullish)
SMA 50 crossing BELOW SMA 200 = "death cross" (bearish)

3. DYNAMIC SUPPORT/RESISTANCE
Pullbacks often bounce at SMA 20 or 50.
The SMA acts as moving support.

4. TREND STRENGTH
- Steep MAs = strong trend
- Flat MAs = consolidation

THE LAG PROBLEM:
MAs lag. They tell you what already happened.
Do NOT enter trades just because of an MA crossover.
Combine with price action for entries.

AlphaEdge: toggle SMA 20 / 50 / 200 on any chart.',
'https://www.youtube.com/watch?v=QfOSbmeKU_g', 1),

(@c, 'RSI & MACD',
'RSI & MACD

RELATIVE STRENGTH INDEX (RSI)
Momentum oscillator from 0 to 100.

Interpretation:
- Above 70 = overbought (possible reversal down)
- Below 30 = oversold (possible reversal up)
- 50 = neutral

DIVERGENCE (the key signal):
Price makes HIGHER high, RSI makes LOWER high → bearish divergence.
Price makes LOWER low, RSI makes HIGHER low → bullish divergence.
Divergence often precedes reversals.

MACD (Moving Average Convergence Divergence)
- MACD line = EMA 12 − EMA 26
- Signal line = EMA 9 of MACD
- Histogram = MACD − Signal

Signals:
- MACD crosses above signal = bullish
- MACD crosses below signal = bearish
- Histogram flipping sign = momentum shift

COMBINING THEM:
Both signal overbought/oversold + trend shifts.
When both agree, the signal is stronger.

CAUTION:
Indicators are lagging. They confirm, they do not predict.
Use them WITH price action, not instead of it.

AlphaEdge: toggle RSI and MACD panels on any chart.',
'https://www.youtube.com/watch?v=Um4nVNKFtdE', 2),

(@c, 'Bollinger Bands & ATR',
'BOLLINGER BANDS & ATR

BOLLINGER BANDS
An SMA (usually 20) with upper and lower bands 2 standard deviations away.

Interpretation:
- Price at upper band = stretched high (overbought)
- Price at lower band = stretched low (oversold)
- Narrow bands = low volatility (often precedes a big move)
- Wide bands = high volatility

STRATEGIES:
Mean Reversion:
- Buy when price touches lower band
- Sell when price touches upper band
- Works in RANGE markets

Trend Following:
- Buy when price closes above upper band and holds
- Ride until it closes below middle

BAND SQUEEZE:
When bands narrow dramatically, a big move often follows.
Trade the breakout, not the squeeze itself.

AVERAGE TRUE RANGE (ATR)
Average distance price moves per bar.

Uses:
- Stop-loss: 1.5–2× ATR below entry
- Position sizing: bigger ATR = smaller position
- Target setting: 2–3× ATR from entry

ATR is how AlphaEdge''s signal engine computes targets internally.',
'https://www.youtube.com/watch?v=FGA-KJEDB6', 3);

SET @c := (SELECT id FROM academy_courses WHERE title = 'Risk Management Fundamentals');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Position Sizing',
'POSITION SIZING

The single most important skill in trading.

THE RULE:
Never risk more than 1–2% of your account on one trade.
Professionals use 0.5% for a reason — even 10 losses in a row only drops the account 5%.

THE FORMULA:
Position size = (Account × Risk %) ÷ Stop distance

EXAMPLE:
- Account: $10,000
- Risk per trade: 1% = $100
- Entry: $100
- Stop: $95 (5% stop distance)
- Risk per unit: $5
- Position size = $100 ÷ $5 = 20 units
- Position value = 20 × $100 = $2,000

NOTICE:
You risk only $100 on a $2,000 position.
That''s how professionals control losses.

POSITION SIZING IN ALPHAEDGE:
The Position Sizing calculator on every asset page does this automatically.

THE MATH OF WHY THIS WORKS:
10 losses in a row at 1% each = 9.6% drawdown.
10 losses in a row at 5% each = 40% drawdown.

The smaller sizing lets you survive bad streaks.',
'https://www.youtube.com/watch?v=nJtL9MBVj48', 1),

(@c, 'Risk : Reward Ratio',
'RISK : REWARD RATIO

For every trade, you risk $X to make $Y. The RATIO matters more than your win rate.

EXAMPLES:
- 1:1 with 60% win rate → profitable
- 1:3 with 30% win rate → profitable
- 1:0.5 with 80% win rate → LOSING (small wins, huge losses)

MINIMUM RECOMMENDED: 1:2

BREAKEVEN WIN RATE FOR ANY R:R:
Breakeven % = 1 ÷ (1 + RR)

- 1:1 → need 50% win rate
- 1:2 → need 33% win rate
- 1:3 → need 25% win rate

This is the counterintuitive part:
You can lose 7 out of 10 trades and still be profitable if your winners are 3× your losers.

HOW TO IMPROVE R:R:
1. Wait for better entries (pullbacks)
2. Tighter stops (more precise entries)
3. Bigger targets (ride trends)
4. Skip trades with unclear R:R

HOW TO RUIN R:R:
1. Move stop further away mid-trade (never do this)
2. Take profit too early (fear)
3. Enter late after a big move

AlphaEdge''s Trade Calculator has an R:R tab with breakeven built in.',
'https://www.youtube.com/watch?v=nJtL9MBVj48', 2),

(@c, 'Portfolio Heat & Drawdown',
'PORTFOLIO HEAT & DRAWDOWN

PORTFOLIO HEAT
The total dollars you would lose if ALL positions hit their stop simultaneously.

If your total open risk is above 6% of equity, you are overleveraged.

WHY IT MATTERS:
Correlated positions hit stops together.
If you hold BTC, ETH, and SOL — all three can drop 10% on the same day.

RECOMMENDED LIMITS:
- Beginner: max 3% heat
- Intermediate: 3–6%
- Aggressive: 6–10%

MAX DRAWDOWN
Worst peak-to-trough drop in your equity curve.

Example:
Peak: $10,000
Trough: $8,000
Max drawdown = 20%

WHY DRAWDOWN MATTERS MORE THAN RETURNS:
- 20% loss requires 25% gain to recover
- 50% loss requires 100% gain to recover
- 80% loss requires 400% gain to recover

Professionals try to keep max drawdown under 20%.

ALPHAEDGE TOOLS:
- Risk Settings page: configure your heat limit
- Risk Monitor: live portfolio heat and drawdown tracking
- Portfolio snapshots: tracks equity curve over time

KEY TAKEAWAY:
Protecting capital > maximizing returns. Always.',
'https://www.youtube.com/watch?v=9zaS0NOuHBE', 3);

SET @c := (SELECT id FROM academy_courses WHERE title = 'Psychology of Trading');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'The Four Emotions That Cost Money',
'THE FOUR EMOTIONS THAT COST MONEY

FEAR
Cuts winners early. Misses valid entries.
Result: small gains, missed opportunity.

GREED
Holds winners too long. Oversizes positions.
Result: giving back profits, blow-up risk.

HOPE
Holds losers past their stop.
Result: small losses become catastrophic losses.

REGRET
Revenge trades after a loss. Doubles down to "get it back."
Result: emotional spiral, account destruction.

ANTIDOTES:

For fear:
- Trust the system. If the setup is valid, take it.
- Set a target and don''t touch it. Walk away.

For greed:
- Pre-define your exit. Follow it.
- Use the SAME size on every trade.

For hope:
- The stop is non-negotiable. Never move it further.
- If it hits, close the trade. Move on.

For regret:
- After 2 losses in a row, stop for the day.
- Come back tomorrow with fresh eyes.

ALPHAEDGE FEATURE:
The Trading Journal has an EMOTION field.
Log your emotion on every trade. You will see patterns in 2 weeks.',
'https://www.youtube.com/watch?v=3zxYgZmVnU4', 1),

(@c, 'Discipline & Routine',
'DISCIPLINE & ROUTINE

Successful traders follow the SAME daily routine.

PRE-MARKET (15 minutes):
1. Check the Economic Calendar — is there high-impact news today?
2. Review open positions — are stops still valid?
3. Identify 2–3 levels on 2–3 assets you''ll watch
4. Set alerts at those levels

DURING MARKET:
1. Wait for setups matching your rules
2. No impulsive entries. If it''s not on the plan, skip it.
3. Log every trade

POST-MARKET (10 minutes):
1. Journal each trade you made
2. Review P&L by setup type
3. Note one thing to improve tomorrow

THE 2-LOSS RULE:
After two consecutive losing trades, stop trading for the day.
No exceptions. Reset tomorrow.

WHY THIS WORKS:
Losses compound emotionally. A third loss after two is usually an emotional trade, not a technical one.

WEEKLY REVIEW:
- Check Performance Analytics for setup win rates
- Reduce size on losing setups
- Increase size on winning setups (gradually)

KEY TAKEAWAY:
Trading is a business. Successful businesses have processes.',
'https://www.youtube.com/watch?v=3zxYgZmVnU4', 2),

(@c, 'Process Over Outcome',
'PROCESS OVER OUTCOME

A single trade''s result means NOTHING.
A process produces consistent results over 100 trades.

JUDGE YOURSELF BY:
- Did I follow my rules?
- Was the risk within limits?
- Did I journal the trade?
- Did I avoid emotional decisions?
- Was the R:R at least 1:2?

DO NOT JUDGE YOURSELF BY:
- "Did I make money today?"
- "Did that trade win?"

THE PARADOX:
You can follow every rule perfectly and still lose 6 out of 10 trades.
You can break every rule and win 6 out of 10 trades.

Over 100 trades, the rule-follower wins. Every time.

REFRAME LOSING TRADES:
A losing trade that followed the process is a GOOD trade.
A winning trade that broke the rules is a BAD trade (it encourages bad habits).

THINK IN EXPECTANCY:
If your system wins 40% at 1:3 R:R, your expectancy is +0.6R per trade.
That means over 100 trades, you expect +60R.
Individual outcomes don''t matter — the average does.

Focus on the next 100 trades, not the next one.

Use AlphaEdge Analytics to track your process, not just P&L.',
'https://www.youtube.com/watch?v=3zxYgZmVnU4', 3);

-- ============================================================
--  ADVANCED LESSONS
-- ============================================================
SET @c := (SELECT id FROM academy_courses WHERE title = 'Advanced Chart Patterns');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Reversal Patterns',
'REVERSAL PATTERNS

HEAD & SHOULDERS
Three peaks — middle one highest. Neckline break confirms reversal.
Volume typically decreases across the three peaks.

DOUBLE TOP / DOUBLE BOTTOM
Two failed pushes at the same level.
The second failure is the reversal signal.
Break of the middle low/high confirms.

RISING / FALLING WEDGE
Converging trend lines against the trend.
A rising wedge (in an uptrend) is bearish.
A falling wedge (in a downtrend) is bullish.
Breakout from the wedge is the signal.

TRIPLE TOP / BOTTOM
Three tests of the same level, all rejected.
Even stronger than double tops.

TRADING RULES:
1. Wait for the pattern to COMPLETE — do not front-run
2. Enter on the breakout with volume confirmation
3. Stop beyond the pattern''s extreme
4. Target = pattern''s height projected from breakout

CONFIRMATION:
- Volume should expand on breakout
- Momentum (RSI) should support the direction
- Higher timeframe should not be strongly opposed

Use the drawing tools on AlphaEdge to mark these patterns.',
'https://www.youtube.com/watch?v=O5yxHsC68BU', 1),

(@c, 'Continuation Patterns',
'CONTINUATION PATTERNS

FLAGS & PENNANTS
Small consolidations after a strong move.
- Flag: rectangular consolidation
- Pennant: triangular consolidation

Both suggest the prior move will continue.

TRIANGLES
- Symmetrical: neutral, direction depends on which side breaks
- Ascending: bullish bias (higher lows into flat resistance)
- Descending: bearish bias (lower highs into flat support)

CUP & HANDLE
Long rounding base (the cup), then a shallow pullback (the handle).
Breakout above the handle is the entry.

VOLUME IS KEY:
A breakout without volume often fails.
A breakout with 2× average volume usually runs.

The best continuation patterns occur at:
- Trend support (in uptrends)
- Trend resistance (in downtrends)
- Higher-timeframe confluence levels

TARGETS:
Measure the prior move and project from the breakout point.
That''s where the "measured move" lands.

ALPHAEDGE TIP:
Toggle Volume Profile on the chart to see if the breakout level has real activity.',
'https://www.youtube.com/watch?v=2uXnfc-SCwI', 2),

(@c, 'False Breaks & Liquidity Grabs',
'FALSE BREAKS & LIQUIDITY GRABS

Smart money often pushes price JUST past a level to trigger stops, then reverses.

WHY IT HAPPENS:
Retail traders place stops just below support.
Institutions know this.
They push price down to trigger those stops (collecting the liquidity), then buy.

SIGNS OF A FAKE BREAKOUT:
1. Wicks beyond the level but close back inside
2. Low volume on the break
3. Immediate rejection after the break
4. Volume spike on the rejection (absorption)

HOW TO TRADE THEM:
1. Wait for the break to FAIL
2. Enter in the OPPOSITE direction
3. Stop beyond the failed move (above the wick)
4. Target = opposite side of the range

EXAMPLE:
Support at $100. Price wicks to $98, immediately closes back at $101.
That''s a fake break. Enter long at $101, stop at $97, target $110.

This is one of the most profitable patterns in crypto and forex.

ALPHAEDGE TOOL:
Toggle Volume Profile to see if the breakout level is thin (easy to fake) or thick (likely to hold).',
'https://www.youtube.com/watch?v=d0T8mh5pd_w', 3);

SET @c := (SELECT id FROM academy_courses WHERE title = 'Multi-Timeframe Analysis');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Top-Down Analysis',
'TOP-DOWN ANALYSIS

Start with the biggest timeframe and drill down.

STEP 1 — DAILY CHART
What is the overall trend?
- Higher highs and higher lows? Uptrend.
- Lower highs and lower lows? Downtrend.
- Sideways? Range.
Where are the major support/resistance levels?

STEP 2 — 4H CHART
Is there a setup forming?
- Pullback to support in an uptrend?
- Rally to resistance in a downtrend?
- Breakout approaching?
Where would a good entry be?

STEP 3 — 1H or 15m CHART
Time the exact entry.
- Wait for a reversal signal at the zone
- Set the stop precisely
- Execute

NEVER REVERSE THIS:
Starting from 1m and working up makes you see trends that don''t exist.
The big picture filters out noise.

ALPHAEDGE FEATURE:
Use Multi-Chart view to see Daily + 4H + 1H side by side.
All charts sync symbol. Change one, all change.

KEY TAKEAWAY:
Direction from higher TF. Entry from lower TF. Always in that order.',
'https://www.youtube.com/watch?v=prhtC_9E7uU', 1),

(@c, 'Confluence Zones',
'CONFLUENCE ZONES

A CONFLUENCE is when 2+ independent signals point to the same price level.

EXAMPLES:

BUY CONFLUENCE:
- Fibonacci 61.8% retracement
- Previous support level
- SMA 50 dynamic support
- Bullish reversal candle

SELL CONFLUENCE:
- Supply zone
- RSI overbought
- Bearish engulfing at resistance
- Fibonacci extension target

THE MORE CONFLUENCE, THE HIGHER THE PROBABILITY.

Two overlapping signals: good.
Three: strong.
Four: excellent.

THE FIBONACCI TOOL IN ALPHAEDGE:
Open any chart, use the Fibonacci drawing tool.
Draw from swing low to swing high (uptrend) or high to low (downtrend).
Levels appear at 23.6%, 38.2%, 50%, 61.8%, 78.6%.

The 61.8% level is the "golden ratio" — most watched.

HOW TO BUILD A SETUP:
1. Higher TF trend direction
2. Pullback to a confluence zone
3. Lower TF reversal candle
4. Entry with stop below the zone
5. Target at next major level

This is how professional traders actually trade.',
'https://www.youtube.com/watch?v=prhtC_9E7uU', 2),

(@c, 'Entry Timing Across Timeframes',
'ENTRY TIMING ACROSS TIMEFRAMES

Once the higher timeframe gives you direction and zone, the lower timeframe gives you TIMING.

COMMON TRIGGERS ON THE LOWER TIMEFRAME:
1. Break of a small structure (change of character)
2. Bullish engulfing at the zone
3. RSI turning up from oversold
4. Volume spike on the reversal candle
5. Second test of the zone holding

IDEAL SEQUENCE:
1. Daily = uptrend
2. 4H = pullback to support
3. 15m = bullish reversal signal
4. Entry, stop just below the 15m structure

WHY IT WORKS:
The tighter the entry, the smaller the stop.
Smaller stop = bigger position for the same risk.
Bigger position = bigger profit when the trade works.

EXAMPLE:
Entry at 100, stop at 98 = 2% risk.
Position sized for $100 risk = 50 units.
If target = 106, profit = $300 = 3R.

vs.
Entry at 100, stop at 95 = 5% risk.
Position sized for $100 risk = 20 units.
If target = 106, profit = $120 = 1.2R.

Same trade idea. Different execution. Very different R:R.',
'https://www.youtube.com/watch?v=prhtC_9E7uU', 3);

SET @c := (SELECT id FROM academy_courses WHERE title = 'Order Flow & Microstructure');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Reading the Order Book',
'READING THE ORDER BOOK

BIDS = resting buy orders (green side, below current price)
ASKS = resting sell orders (red side, above current price)

The gap between the best bid and best ask is the SPREAD.

WHAT TO LOOK FOR:

1. LARGE WALLS
A single large order (e.g. 500 BTC at $76,000).
Acts as temporary support or resistance.
But can be spoofed (placed then removed).

2. IMBALANCE
More total volume on bids than asks = buying pressure.
More on asks than bids = selling pressure.

3. DEPTH
Thick depth = price moves slowly.
Thin depth = price moves fast (slippage).

4. SPOOFING
Large orders that appear and disappear quickly.
A manipulative tactic. Common in crypto.

ALPHAEDGE ORDER BOOK:
Open the Depth & Tape page.
The book updates live with simulated depth.
The imbalance stat shows buy/sell pressure.

CAUTION:
Order book depth can be manipulated.
Use it as context, not as a signal on its own.
Combine with price action and volume.',
'https://www.youtube.com/watch?v=jBS4utCt3eU', 1),

(@c, 'Time & Sales (The Tape)',
'TIME & SALES (THE TAPE)

The tape shows every executed trade in real time:
- Time
- Price
- Size
- Side (buy or sell)

WHAT TO WATCH:

1. AGGRESSIVE BUYS
Trades hitting the ask repeatedly.
Buyers are in control.

2. AGGRESSIVE SELLS
Trades hitting the bid repeatedly.
Sellers are in control.

3. BIG BLOCKS
Single trades of unusual size.
Institutional activity.

4. DELTA
Cumulative difference between buy and sell volume.
Positive delta = net buying.
Negative delta = net selling.

INTERPRETATION:
- Positive delta + rising price = healthy uptrend
- Positive delta + falling price = absorption (potential reversal)
- Negative delta + falling price = healthy downtrend
- Negative delta + rising price = distribution (potential reversal)

ALPHAEDGE TAPE:
Open Depth & Tape page.
Live tape with buy/sell coloring.
Running delta in the summary panel.

CAUTION:
On crypto exchanges, wash trading exists.
Some volume is fake. Use the tape as one signal among many.',
'https://www.youtube.com/watch?v=FGA-KJEDB6', 2),

(@c, 'Volume Profile & POC',
'VOLUME PROFILE & POC

Volume Profile shows how much volume traded at each PRICE LEVEL.
Different from the standard volume histogram which shows volume over TIME.

KEY CONCEPTS:

1. POC (Point of Control)
The price level with the highest traded volume.
Acts as a magnet. Price tends to revert to it.

2. VALUE AREA (VA)
The price range containing 70% of all volume.
- VAH = Value Area High
- VAL = Value Area Low

3. HVN (High Volume Node)
Price levels with lots of activity.
Price slows down here. Acts as support/resistance.

4. LVN (Low Volume Node)
Price levels with little activity.
Price moves fast here. Acts as a magnet for price to travel through.

TRADING THEM:
- Price tends to revert to POC after moving away
- Breakouts above VAH often continue
- Breakdowns below VAL often continue
- LVNs act as fast-travel zones

ALPHAEDGE FEATURE:
Toggle "Vol Profile" on any chart.
A histogram appears on the right side.
The POC is labeled at the top.

COMBINED WITH:
- Support/resistance: if a level overlaps the POC, it''s very strong
- Sessions: use session VAH/VAL for intraday levels',
'https://www.youtube.com/watch?v=d0T8mh5pd_w', 3);

SET @c := (SELECT id FROM academy_courses WHERE title = 'Portfolio Management & Correlation');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Diversification',
'DIVERSIFICATION

Don''t put all eggs in one basket.

BUT: real diversification is more than buying 10 cryptos.
Crypto assets move together 80%+ of the time.
Holding BTC, ETH, SOL, and DOGE is basically ONE big bet.

TRUE DIVERSIFICATION:
1. Different asset classes (equities + crypto + commodities)
2. Different geographies (US + EU + Asia)
3. Different sectors (tech + healthcare + energy)
4. Different strategies (trend + mean reversion)

THE MATH:
Two uncorrelated assets reduce portfolio volatility by ~30%.
Three reduce it by ~45%.

vs.

Five correlated assets reduce volatility by ~5% (almost nothing).

ALPHAEDGE TOOLS:
- Correlations page: see the actual correlation matrix
- Portfolio page: allocation breakdown
- Risk Monitor: concentration alerts

HOW MUCH TO DIVERSIFY:
- Retail traders: 3–8 positions maximum
- More than 10: impossible to track properly
- Quality > quantity

KEY TAKEAWAY:
Real diversification means UNCORRELATED bets. Not many similar ones.',
'https://www.youtube.com/watch?v=7AjFKAVVbpA', 1),

(@c, 'Correlation & Hidden Risk',
'CORRELATION & HIDDEN RISK

CORRELATION COEFFICIENT ranges from -1 to +1:

+1 = perfect positive (move together)
 0 = no relationship
-1 = perfect negative (move opposite)

WHY IT MATTERS:
If your 5 positions all correlate above 0.7, you effectively have ONE position — five times the size.

EXAMPLE:
You buy $1,000 each of BTC, ETH, SOL, DOGE, and ADA.
Total: $5,000.
They all drop 10% together.
You lose $500.

vs.

You buy $1,000 each of BTC, Gold, EUR/USD, S&P 500, and Natural Gas.
BTC drops 10%, but Gold is flat, EUR is up 0.5%, S&P is up 0.2%.
You lose only $50.

Same capital. Very different risk.

ALPHAEDGE CORRELATIONS PAGE:
Computes a live matrix across your tracked assets.
Shows:
- Highest correlated pair (concentration risk)
- Most anti-correlated pair (natural hedge)

AIM FOR:
- No two positions correlating above 0.7
- At least one anti-correlated pair in your portfolio

Note: correlations change over time. Recheck monthly.',
'https://www.youtube.com/watch?v=7AjFKAVVbpA', 2),

(@c, 'Position Sizing with Kelly',
'POSITION SIZING WITH KELLY

The Kelly Criterion gives the mathematically optimal bet size.

FORMULA:
f = (p × b − q) ÷ b

Where:
- f = fraction of capital to bet
- p = win probability
- q = 1 − p (loss probability)
- b = win/loss ratio

EXAMPLE:
Win rate = 55%, R:R = 1:2
f = (0.55 × 2 − 0.45) ÷ 2 = 0.325 = 32.5%

This says "bet 32.5% of your capital on this trade."

BUT: full Kelly is too aggressive for real trading.
Why? Because your estimated win rate is almost certainly wrong.

THE PRACTICAL RULE:
Use quarter Kelly or less.
In practice, most professionals use fixed 1% per trade.

WHY FIXED 1% BEATS KELLY FOR RETAIL:
1. You don''t know your actual win rate until 100+ trades
2. Win rates change as markets change
3. Kelly maximizes growth but ignores psychological pain
4. Fixed 1% is simple, consistent, and survivable

WHEN TO USE KELLY:
- After 500+ trades with stable statistics
- Only for position sizing WITHIN a portfolio
- Only as a CEILING, never as your actual bet size

KEY TAKEAWAY:
For retail: fixed 1% per trade. Simple. Survivable. Effective.',
'https://www.youtube.com/watch?v=nJtL9MBVj48', 3);

-- ============================================================
--  STRATEGIES LESSONS
-- ============================================================
SET @c := (SELECT id FROM academy_courses WHERE title = 'Trend Following Strategies');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'The SMA Crossover Strategy',
'THE SMA CROSSOVER STRATEGY

A classic trend-following system.

RULES:
- ENTER LONG when SMA 50 crosses ABOVE SMA 200 (golden cross)
- EXIT when SMA 50 crosses BELOW SMA 200 (death cross)
- Stop-loss: below the most recent swing low

CHARACTERISTICS:
- Few trades per year (2–4 per asset)
- Low win rate (~40%)
- Big winners cover many small losers
- Profitable in trending markets, bad in ranges

WHY IT WORKS:
Captures major trends. Misses the top and bottom but captures the middle 60–70% of big moves.

WHY IT FAILS:
In choppy/sideways markets, you get many false signals.
The MA crossover happens AFTER the move has started.

IMPROVEMENTS:
1. Add a trend filter (only trade when price > SMA 200)
2. Use EMA instead of SMA for faster signals
3. Add an ATR-based stop
4. Only trade when the higher timeframe trend agrees

TEST IT:
Run the preset "SMA 50/200 Golden Cross" on BTC in Backtesting.
1-year range, check the equity curve.',
'https://www.youtube.com/watch?v=prhtC_9E7uU', 1),

(@c, 'Momentum Breakout Strategy',
'MOMENTUM BREAKOUT STRATEGY

RULES:
1. Identify a consolidation range (tight price action, low volatility)
2. Enter long on close above resistance with a volume spike
3. Stop just below the breakout candle
4. Target: 2× the height of the range

FILTERS TO AVOID FALSE BREAKS:
- Volume must be 1.5× average
- Breakout candle must be large-bodied
- Higher timeframe trend must agree
- Breakout must close ABOVE the level (not just wick through)

WHY IT WORKS:
Consolidation = energy buildup.
Breakout = release.
Momentum traders pile in, pushing price further.

WHY IT FAILS:
False breakouts are common (see Liquidity Grabs lesson).
Without volume confirmation, breakouts often reverse.

TARGET MANAGEMENT:
Option 1: Fixed target at 2× range height (simple)
Option 2: Trail stop below each new swing low (captures trends)
Option 3: Scale out — 50% at 1×, 50% at 2×

CRYPTO SUITABILITY:
This works especially well in crypto where momentum runs can be huge.

TEST IT:
Run "Momentum Breakout" in Backtesting on BTC or ETH.',
'https://www.youtube.com/watch?v=2uXnfc-SCwI', 2),

(@c, 'Trend Pullback Strategy',
'TREND PULLBACK STRATEGY

RULES:
1. Trend confirmed: price > SMA 200 AND SMA 50 > SMA 200
2. Wait for pullback to SMA 20 or SMA 50
3. Enter on bullish reversal candle at the MA
4. Stop below the reversal candle
5. Target: prior swing high, then trail

WHY IT WORKS:
Institutional money adds to positions on pullbacks.
You''re riding along with smart money.

BEST TIMEFRAME:
4H or Daily for cleaner pullbacks.
1H works intraday.

COMMON MISTAKE:
Chasing extended trends. If price has moved 5%+ from the MA without a pullback, wait.

CONFLUENCE BOOSTERS:
- Pullback coincides with a previous support level
- RSI dips into 40–50 zone but stays above 30
- Volume dries up on the pullback (no selling pressure)
- Bullish reversal candle at the MA

EXIT STRATEGY:
Option 1: Prior swing high (safe)
Option 2: Trail with SMA 20
Option 3: Trail with 2× ATR

This is one of the highest win-rate trend strategies.',
'https://www.youtube.com/watch?v=prhtC_9E7uU', 3);

SET @c := (SELECT id FROM academy_courses WHERE title = 'Mean Reversion Strategies');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'RSI Mean Reversion Strategy',
'RSI MEAN REVERSION STRATEGY

RULES:
1. Only take LONG trades when price > SMA 200 (uptrend filter)
2. Enter when RSI crosses below 30
3. Exit when RSI returns to 50 OR at the middle Bollinger Band
4. Stop 2× ATR below entry

WHY THE SMA 200 FILTER:
"Buy oversold" fails in downtrends.
RSI can stay below 30 for weeks in a bear market.
The SMA 200 filter avoids this trap.

WHY IT WORKS IN UPTRENDS:
Pullbacks are temporary. Buyers return.
The oversold RSI signals the pullback is extended.

EXIT DISCIPLINE:
Don''t hold for a big move. Take the bounce.
Target is the middle of the range, not the top.

STOP LOSS:
2× ATR below entry protects against continued drops.
If stopped, do not re-enter for at least 1 day.

IDEAL MARKET CONDITIONS:
- Uptrending market
- Pullback phase
- Volatility moderate (not in panic)

TEST IT:
Run the preset "RSI Oversold Bounce" in Backtesting.
Compare to buy-and-hold over 1 year.',
'https://www.youtube.com/watch?v=Um4nVNKFtdE', 1),

(@c, 'Bollinger Band Reversion',
'BOLLINGER BAND REVERSION

RULES:
1. Enter LONG when price closes below the LOWER Bollinger Band
2. Exit at the MIDDLE band (SMA 20)
3. Stop below the recent swing low
4. Only in range-bound markets

WHEN IT WORKS:
Ranging markets.
Volatility is normal.
No strong directional bias.

HOW TO IDENTIFY RANGE CONDITIONS:
- Bollinger Bands narrow (a "squeeze")
- ADX below 20 (weak trend)
- Price oscillating between clear support and resistance

WHEN IT FAILS:
Trending markets.
"Oversold" stays oversold.
Bands widen as volatility expands.

COMBINING WITH RSI:
Bollinger + RSI oversold = stronger signal.
Both must signal the same thing.

RISK MANAGEMENT:
Because mean reversion can fail catastrophically, ALWAYS use a hard stop.
If price doesn''t reverse within 3 bars, cut the position.

ALPHAEDGE TOOL:
Toggle Bollinger Bands and RSI on the same chart.
Watch for the double signal.',
'https://www.youtube.com/watch?v=FGA-KJEDB6', 2),

(@c, 'When Mean Reversion Fails',
'WHEN MEAN REVERSION FAILS

Mean reversion is DANGEROUS because losses can be massive when it fails.

FAILURE SCENARIOS:

1. STRONG NEWS PUSH
An earnings miss, a hack, a regulatory ban.
Price blows past the bands and keeps going.

2. TREND ACCELERATION
"Oversold" keeps getting more oversold.
RSI stays below 30 for weeks.

3. LIQUIDATION CASCADES
In leveraged crypto, forced liquidations accelerate drops.
Price drops 30% in hours.

4. STRUCTURAL BREAKS
A company reveals fraud. A government bans crypto.
The "value" of the asset changes fundamentally.

RULES TO PROTECT YOURSELF:

1. ALWAYS use a hard stop. Never "average down."
2. Cut the position if price does not reverse within 3 bars
3. Reduce position size by 50% for mean-reversion trades
4. Avoid mean reversion on small-cap/low-liquidity assets
5. Never risk more than 0.5% per mean-reversion trade

THE STATISTICAL REALITY:
Trend-following: many small losses, few big wins.
Mean reversion: many small wins, few big losses (if unmanaged).

Manage the tail risk or the tail risk will manage you.

THE MANTRA:
"Mean reversion is safe until it isn''t."',
'https://www.youtube.com/watch?v=3zxYgZmVnU4', 3);

SET @c := (SELECT id FROM academy_courses WHERE title = 'Breakout & Momentum Strategies');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'Range Breakouts',
'RANGE BREAKOUTS

A RANGE is a period where price oscillates between two horizontal levels.
The longer the range, the bigger the eventual breakout.

BREAKOUT CRITERIA:
1. Price closes above the upper level
2. Volume > 1.5× average
3. Follow-through the next bar

ENTRY OPTIONS:

Option A (aggressive):
Enter on the close of the breakout candle.
Stop just below the breakout candle.
Fast entry, tighter stop, but more false signals.

Option B (conservative):
Wait for a pullback to the broken level.
Enter when price holds the level as new support.
Slower entry, wider stop, but higher win rate.

TARGETS:
1. Range height projected from breakout
2. Next major resistance level
3. Trail with a moving average

FILTERS:
- Higher timeframe trend must agree
- Volume must confirm
- Avoid breakouts before major news

RANGE DURATION MATTERS:
- 1-week range → small breakout, small move
- 3-month range → large breakout, large move

The energy accumulates.',
'https://www.youtube.com/watch?v=2uXnfc-SCwI', 1),

(@c, 'Volume Confirmation',
'VOLUME CONFIRMATION

VOLUME IS TRUTH.
Price can be faked. Volume cannot be hidden.

RULES OF THUMB:

1. BREAKOUT WITH 2× VOLUME
Strong. High probability of follow-through.

2. BREAKOUT WITH 0.5× VOLUME
Suspect. High risk of failure.

3. RISING PRICE + FALLING VOLUME
Weakening trend. Possible reversal.

4. FALLING PRICE + RISING VOLUME
Panic selling or capitulation. Often near a bottom.

TOOLS IN ALPHAEDGE:

1. Volume histogram (below the chart)
Shows total volume per bar.

2. Volume Profile (right side)
Shows where the volume traded.

3. Order book depth (Depth & Tape)
Shows resting orders.

4. Time & Sales tape
Shows real-time aggressive buying and selling.

CONFIRMING RULE:
A breakout without volume is a trap.
A breakout with volume is a signal.

If in doubt, wait for volume.

MISSING A TRADE IS FINE.
Getting trapped in a false breakout is not.

The market will always offer another setup.',
'https://www.youtube.com/watch?v=FGA-KJEDB6', 2),

(@c, 'Filtering False Breakouts',
'FILTERING FALSE BREAKOUTS

A FALSE BREAKOUT is when price breaks a level then immediately reverses.

COMMON FILTERS:

1. WAIT FOR A DAILY CLOSE
A wick above a level is not a breakout.
A close above a level is a breakout.

2. REQUIRE VOLUME SPIKE
Minimum 1.5× the 20-bar average.
Better: 2× average.

3. REQUIRE A RETEST
After breakout, price often pulls back to the level.
If the level holds as support, the breakout is confirmed.

4. HIGHER TIMEFRAME AGREEMENT
If the 4H trend is down, a 15m breakout is likely to fail.

5. AVOID BREAKOUTS INTO RESISTANCE
If the next level is just above, the breakout has no room.

FADING THE FALSE BREAK (advanced):
When a breakout fails and price closes back inside the range:
1. Enter in the OPPOSITE direction
2. Stop just beyond the false wick
3. Target = opposite side of the range

This is a HIGH-PROBABILITY setup.
The failed breakout traps both sides.

ALPHAEDGE FEATURE:
Use the "PDH/PDL" toggle on the chart.
Previous day highs and lows are common false-break levels.',
'https://www.youtube.com/watch?v=d0T8mh5pd_w', 3);

SET @c := (SELECT id FROM academy_courses WHERE title = 'Build & Backtest Your Own Strategy');

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) VALUES
(@c, 'A Strategy Framework',
'A STRATEGY FRAMEWORK

Every complete strategy has 6 components:

1. MARKET
Which assets? Which timeframe?
Example: BTC and ETH on the 4H timeframe.

2. SETUP
The condition that must exist.
Example: Price above SMA 200 (uptrend) AND RSI < 40 (pullback).

3. ENTRY TRIGGER
What specifically causes the buy.
Example: Bullish engulfing candle at SMA 50.

4. STOP-LOSS
Where and why.
Example: 2% below the entry, below the reversal candle.

5. TARGET
Where and why.
Example: Prior swing high, or 3× risk.

6. POSITION SIZE
How much.
Example: 1% of account risk.

IF ANY OF THOSE IS MISSING, YOU HAVE A HUNCH, NOT A STRATEGY.

USE THE STRATEGY BUILDER:
The AlphaEdge Strategy Builder page lets you define each component.
Build a rule-based strategy in under 2 minutes.

EXAMPLE:
- Market: BTC, 4H
- Setup: RSI < 40 AND close > SMA 200
- Entry: RSI crosses back above 40
- Stop: 2% below entry
- Target: +5% OR RSI > 70
- Size: 1% risk

That''s a complete strategy.',
'https://www.youtube.com/watch?v=prhtC_9E7uU', 1),

(@c, 'How to Backtest Properly',
'HOW TO BACKTEST PROPERLY

Use at least:
- 100 trades
- 1+ year of data
- Multiple market conditions (bull + bear + range)

METRICS THAT MATTER:

1. PROFIT FACTOR
Gross wins ÷ gross losses.
Must be > 1.3.
Above 1.5 = good.
Above 2.0 = excellent.

2. SHARPE RATIO
Risk-adjusted return.
> 1 = good
> 2 = excellent
< 1 = not worth the risk

3. MAX DRAWDOWN
Worst peak-to-trough drop.
Should be < 20% for most strategies.

4. WIN RATE
Percent of winning trades.
Not the primary metric — R:R matters more.

5. EXPECTANCY
Average $ or R per trade.
Positive expectancy = profitable.

RED FLAGS:

1. FEWER THAN 30 TRADES
Statistically meaningless.
The "success" is luck.

2. ONLY TESTED IN A BULL MARKET
Every long-only strategy looks great in a bull market.
Test it in 2022 (bear) too.

3. OVERFIT PARAMETERS
If you tried 100 parameter combinations and picked the best, you overfit.
The result won''t repeat live.

4. NO TRANSACTION COSTS
Real trading has fees + spread.
Subtract 0.2% per trade minimum.

RUN BACKTESTS IN ALPHAEDGE:
Open the Backtesting page.
Choose a strategy, symbol, and range.
See metrics + equity curve + trade list.',
'https://www.youtube.com/watch?v=3zxYgZmVnU4', 2),

(@c, 'Iterate & Refine',
'ITERATE & REFINE

A backtest is the BEGINNING, not the end.

THE ITERATION LOOP:

1. Define strategy
2. Backtest it
3. Analyze losing trades — WHY did they lose?
4. Add ONE filter to eliminate the biggest problem
5. Re-test
6. Repeat

EXAMPLES:

Problem: Many losses at resistance.
Filter: Add "skip trades when RSI > 60."

Problem: Big losses during news events.
Filter: Skip trades on days with high-impact economic events.

Problem: Choppy market whipsaws.
Filter: Require ADX > 25 (trending market).

AVOID CURVE FITTING:
If a filter only helps one specific period, discard it.
You want ROBUST, not perfect.

THE DIFFERENCE:
Robust: Works across multiple years, assets, and conditions.
Overfit: Works only on the exact historical data you tested.

LIVE-TRADE IT SMALL FIRST:
Run the strategy for 30 days on the demo account with the same rules.
If it behaves similarly to the backtest, size up.
If it doesn''t, review and adjust.

THEN SIZE UP GRADUALLY:
Start with 0.5% risk per trade.
After 20 profitable trades, go to 1%.
After 50 more, consider 1.5%.
Never jump straight to 3%.

CONGRATULATIONS:
You now know more than 95% of retail traders.
The rest is repetition and discipline.',
'https://www.youtube.com/watch?v=3zxYgZmVnU4', 3);

-- ============================================================
--  Done.
-- ============================================================
SELECT 'Academy full content imported.' AS status;