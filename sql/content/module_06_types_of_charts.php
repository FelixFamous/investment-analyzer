<?php
/**
 * Module 06 — Types of Charts
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_06_types_of_charts.php
 */

return [
    'module' => [
        'level_slug' => 'foundation',
        'slug'       => 'types-of-charts',
        'title'      => 'Types of Charts',
        'description'=> 'Line charts, bar charts, candlestick charts, and Heikin Ashi. Each presents the same underlying price data in a different way. Knowing which to use — and when — is a small but real edge.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Identify the four main chart types and how they are constructed\n" .
            "• Choose the right chart type for the job\n" .
            "• Explain why Heikin Ashi candles differ from standard candles",
        'sort_order' => 6,
    ],

    'lessons' => [

        [
            'slug'   => 'line-charts',
            'title'  => 'Line Charts',
            'difficulty' => 'beginner',
            'estimated_duration' => 7,
            'learning_objectives' =>
                "• Explain how a line chart is constructed\n" .
                "• Identify when a line chart is more useful than a candle chart\n" .
                "• Recognise the trade-offs of losing OHLC detail",
            'prerequisites' => 'Anatomy of a Candlestick',
            'sort_order' => 1,
            'summary' => 'A line chart connects the closing prices of each period with a continuous line. It strips out the noise of highs, lows, and intrabar movement, giving a clean view of the overall direction.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>A line chart is exactly what it sounds like: a single line drawn from one price point to the next. For each period — a day, an hour, a minute — you plot <em>only the closing price</em>, then connect the dots.</p>
<p>The result is clean and easy to read. No bodies, no wicks, no confusion. Just the trend.</p>

<h2>Real-world analogy</h2>
<p>Think of a temperature chart in a weather app. You don't see hourly fluctuations on the graph — you see a smooth curve from one day's high to the next. That's a line chart. It's showing you the general path, not every wiggle.</p>

<h2>Professional explanation</h2>
<p>A <strong>line chart</strong> plots a series of data points (usually closing prices) and connects them with straight line segments. On a price chart, each point represents the close of a specific timeframe.</p>

<h3>What you see</h3>
<ul>
    <li>The general trend — up, down, or sideways</li>
    <li>Support and resistance levels</li>
    <li>Chart patterns (head and shoulders, triangles)</li>
</ul>

<h3>What you don't see</h3>
<ul>
    <li>Where price opened each period</li>
    <li>Where price reached its high or low</li>
    <li>Whether buyers or sellers dominated within the period</li>
    <li>Rejection wicks or momentum candles</li>
</ul>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <polyline points="40,180 90,150 140,160 190,120 240,130 290,90 340,100 390,60 440,70"
            fill="none" stroke="#5b7cfa" stroke-width="2.5" stroke-linejoin="round"/>
  <circle cx="40" cy="180" r="3" fill="#5b7cfa"/>
  <circle cx="90" cy="150" r="3" fill="#5b7cfa"/>
  <circle cx="140" cy="160" r="3" fill="#5b7cfa"/>
  <circle cx="190" cy="120" r="3" fill="#5b7cfa"/>
  <circle cx="240" cy="130" r="3" fill="#5b7cfa"/>
  <circle cx="290" cy="90" r="3" fill="#5b7cfa"/>
  <circle cx="340" cy="100" r="3" fill="#5b7cfa"/>
  <circle cx="390" cy="60" r="3" fill="#5b7cfa"/>
  <circle cx="440" cy="70" r="3" fill="#5b7cfa"/>
  <text x="240" y="240" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Each dot = close of one period</text>
</svg>

<h3>When to use a line chart</h3>
<ul>
    <li><strong>Long-term analysis</strong> — for seeing months or years of price at a glance</li>
    <li><strong>Identifying major support and resistance</strong> — cleaner without the noise of wicks</li>
    <li><strong>Presenting to non-traders</strong> — easier for clients or reporters to understand</li>
    <li><strong>Comparing multiple assets</strong> — line charts overlay easily (e.g., EUR/USD vs USD index)</li>
</ul>

<h3>When not to use it</h3>
<p>Line charts are useless for reading candle patterns. Every reversal signal — pin bars, engulfing patterns, morning stars — relies on seeing the open, high, low, and close of each period. A line chart hides all of that.</p>

<h2>Factual context</h2>
<p>Line charts were the original form of financial charting, used long before candlesticks reached the West. Charles Dow (founder of the Dow Jones Industrial Average and the Wall Street Journal in 1889) relied on line-style charts and simple price averages to identify trends. His work became known as <strong>Dow Theory</strong> — the foundation of modern technical analysis.</p>
<p>Dow Theory's central claim — that "the trend is in force until it is definitively reversed" — is easier to see on a line chart than on a cluttered candlestick chart. This is one reason professional analysts often use line charts for their primary trend view, then switch to candlesticks for entry timing.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trying to read candle signals on a line chart.</strong> You can't. Line charts don't show wicks or bodies — patterns simply don't exist there.</li>
    <li><strong>Assuming line charts are "simpler" than candles.</strong> They are cleaner, but they carry less information. "Simpler" is a trade-off, not an upgrade.</li>
    <li><strong>Using the wrong closing reference.</strong> Different platforms sometimes use different closes (broker close, exchange close, session close). This can shift the line slightly between sources.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some traders use line charts on very high timeframes (weekly, monthly) to identify the primary trend, then switch to daily candlesticks for intermediate trend analysis, then to 4H or 1H candlesticks for entry timing. This multi-timeframe approach uses line charts specifically for their ability to remove noise — you only switch to candles when you actually need pattern detail.</p>
HTML,
        ],

        [
            'slug'   => 'bar-charts',
            'title'  => 'Bar Charts (OHLC Bars)',
            'difficulty' => 'beginner',
            'estimated_duration' => 8,
            'learning_objectives' =>
                "• Read a bar chart and identify OHLC\n" .
                "• Distinguish between bar charts and candlestick charts\n" .
                "• Explain why bar charts are still used in institutional settings",
            'prerequisites' => 'Line Charts',
            'sort_order' => 2,
            'summary' => 'A bar chart (also called an OHLC bar chart) shows open, high, low, and close for each period using a single vertical line with a small tick on each side. It predates candlesticks in Western markets and remains common in institutional software.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>A bar chart (or OHLC bar chart) looks like a thin vertical stick with two small horizontal lines attached — one on the left, one on the right. It shows the same four prices as a candlestick, just arranged differently.</p>

<h2>Real-world analogy</h2>
<p>Think of a ruler with two notches marked on it. The ruler itself shows the high-to-low range. The two notches mark where the period opened and where it closed. That's essentially a bar chart.</p>

<h2>Professional explanation</h2>
<p>An <strong>OHLC bar chart</strong> (also called a "bar" or "HLC bar" in some older references) represents each period with:</p>
<ul>
    <li>A vertical line spanning the period's <strong>high</strong> to <strong>low</strong></li>
    <li>A small horizontal tick on the <strong>left</strong> representing the <strong>open</strong></li>
    <li>A small horizontal tick on the <strong>right</strong> representing the <strong>close</strong></li>
</ul>
<p>The convention is: <em>left tick = open, right tick = close</em>. This is universal — every trading platform shows bar charts the same way.</p>

<h3>Visual reference</h3>
<svg viewBox="0 0 400 260" width="400" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Bullish bar -->
  <line x1="100" y1="40" x2="100" y2="220" stroke="#4ade80" stroke-width="2"/>
  <line x1="88" y1="60" x2="112" y2="60" stroke="#4ade80" stroke-width="2"/>
  <line x1="88" y1="200" x2="112" y2="200" stroke="#4ade80" stroke-width="2"/>
  <text x="100" y="245" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Bullish bar</text>

  <!-- Bearish bar -->
  <line x1="250" y1="40" x2="250" y2="220" stroke="#ef4444" stroke-width="2"/>
  <line x1="238" y1="200" x2="262" y2="200" stroke="#ef4444" stroke-width="2"/>
  <line x1="238" y1="60" x2="262" y2="60" stroke="#ef4444" stroke-width="2"/>
  <text x="250" y="245" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Bearish bar</text>

  <!-- Labels -->
  <text x="20" y="55" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif">High</text>
  <text x="20" y="225" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif">Low</text>
</svg>
<p style="text-align:center;color:#8b93a7;font-size:13px;margin-top:12px;">
  Left tick = open. Right tick = close. The vertical line = high to low.
</p>

<h3>Reading a bar chart</h3>
<p>For a bullish bar (close > open), the right tick sits above the left tick. For a bearish bar (close < open), the right tick sits below the left tick. Some platforms colour-code the bar, but many don't — you read direction from the position of the two ticks.</p>

<h3>Bar charts vs candlestick charts</h3>
<table>
    <thead><tr><th>Feature</th><th>Bar Chart</th><th>Candlestick Chart</th></tr></thead>
    <tbody>
        <tr><td>Body</td><td>Not filled in — just two ticks</td><td>Filled rectangle between open and close</td></tr>
        <tr><td>Visual density</td><td>Lower — takes more effort to read</td><td>Higher — instant visual read</td></tr>
        <tr><td>Space efficiency</td><td>Uses less horizontal space</td><td>Uses more — bigger visual footprint</td></tr>
        <tr><td>Origin</td><td>Western (US, 19th century)</td><td>Japanese (18th century)</td></tr>
        <tr><td>Industry use</td><td>Common in institutional software (Bloomberg, Reuters)</td><td>Standard on retail platforms</td></tr>
    </tbody>
</table>

<h2>Factual context</h2>
<p>Bar charts were the standard in US financial markets long before Steve Nison introduced candlesticks to the West in 1991. Many institutional traders — especially at hedge funds and banks using Bloomberg or Reuters terminals — still use OHLC bars because they were trained on them.</p>
<p>The Dow Theory work of Charles Dow, William Hamilton, and Robert Rhea was developed entirely using bar charts. The concept of "the trend is your friend" — a phrase popularised by trader Ed Seykota in the 1980s — emerged from bar-chart analysis of the Dow Jones averages.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Reading the ticks backwards.</strong> Left = open. Right = close. Getting this wrong flips your interpretation of every candle.</li>
    <li><strong>Assuming all bar charts are colour-coded.</strong> Many institutional platforms don't colour-code bars. You read direction from the tick positions.</li>
    <li><strong>Confusing a bar chart with a line chart.</strong> A line chart has one line, no vertical bars. A bar chart has one vertical bar per period.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some professional traders use bar charts because they find them less "emotionally manipulative" than candlesticks. The thick green and red bodies of candlestick charts can trigger stronger emotional reactions — a long red candle can make you panic-close a position; the same period on a bar chart looks less dramatic. This is a subtle but real psychological effect, and it's one reason some veterans prefer bars even though candlesticks are objectively more information-dense.</p>
HTML,
        ],

        [
            'slug'   => 'candlestick-charts',
            'title'  => 'Candlestick Charts (The Modern Standard)',
            'difficulty' => 'beginner',
            'estimated_duration' => 9,
            'learning_objectives' =>
                "• Explain why candlestick charts are the default on most platforms\n" .
                "• Compare candlesticks to bar charts practically\n" .
                "• Recognise the emotional impact of the visual design",
            'prerequisites' => 'Bar Charts (OHLC Bars)',
            'sort_order' => 3,
            'summary' => 'Candlestick charts are the most common chart type in retail trading. They combine the OHLC information of a bar chart with a coloured body that instantly communicates direction. Their visual density is both their greatest strength and their hidden weakness.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>You've already studied the anatomy of a candlestick in Module 5 — the body, the wicks, the open/close relationship. A <strong>candlestick chart</strong> is just a chart made of hundreds of those candles, arranged left-to-right by time.</p>

<h2>Real-world analogy</h2>
<p>Imagine a bar chart, then colour in each bar with a thick rectangle between its open and close ticks. That's a candlestick chart. Same information, visually denser and easier to read at a glance.</p>

<h2>Professional explanation</h2>
<p>A <strong>candlestick chart</strong> displays price data using candlesticks. Each candle represents one period (1 minute, 1 hour, 1 day, etc.) and contains:</p>
<ul>
    <li><strong>Body</strong> — the rectangle from open to close</li>
    <li><strong>Upper wick</strong> — line from body top to period high</li>
    <li><strong>Lower wick</strong> — line from body bottom to period low</li>
</ul>
<p>Candles are typically coloured green/white for bullish (close > open) and red/black for bearish (close < open). Colour conventions can be inverted (some Asian platforms use red for up and blue/green for down) — always check your platform's settings.</p>

<h3>Why candlesticks dominate retail trading</h3>
<ol>
    <li><strong>Visual density</strong> — a candle's colour instantly communicates direction without needing to inspect its structure.</li>
    <li><strong>Pattern recognition</strong> — dozens of named patterns (doji, hammer, engulfing) only exist in candlestick form.</li>
    <li><strong>Body-to-wick ratio</strong> — clean way to measure conviction versus rejection.</li>
    <li><strong>Wide support</strong> — every major retail platform (MT4/MT5, TradingView, cTrader) uses candlesticks by default.</li>
</ol>

<h3>The hidden weakness</h3>
<p>Candlesticks are more emotionally powerful than bar charts. A long red candle is dramatic. Three red candles in a row can feel like a disaster even when the price has barely moved in percentage terms. This visual weight can cause traders to overreact to normal market noise.</p>
<p>Professional traders learn to notice this emotional response and adjust for it. Some use bar charts specifically to avoid the effect. Others use candlesticks but zoom out to higher timeframes so individual candles carry less weight.</p>

<h3>Visual reference — the same data, three views</h3>
<svg viewBox="0 0 520 240" width="520" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Line chart -->
  <polyline points="30,140 70,110 110,130 150,90 190,110 230,70"
            fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <text x="130" y="220" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Line</text>

  <!-- Bar chart -->
  <line x1="300" y1="60" x2="300" y2="160" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="290" y1="80" x2="310" y2="80" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="290" y1="140" x2="310" y2="140" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="340" y1="80" x2="340" y2="180" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="330" y1="100" x2="350" y2="100" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="330" y1="160" x2="350" y2="160" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="380" y1="60" x2="380" y2="140" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="370" y1="80" x2="390" y2="80" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="370" y1="120" x2="390" y2="120" stroke="#e6e9ef" stroke-width="2"/>
  <text x="340" y="220" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Bar</text>

  <!-- Candlestick -->
  <line x1="450" y1="40" x2="450" y2="180" stroke="#4ade80" stroke-width="2"/>
  <rect x="440" y="60" width="20" height="80" fill="#4ade80"/>
  <line x1="490" y1="60" x2="490" y2="200" stroke="#ef4444" stroke-width="2"/>
  <rect x="480" y="80" width="20" height="60" fill="#ef4444"/>
  <text x="470" y="220" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Candle</text>
</svg>

<h2>Factual context</h2>
<p>Steve Nison's 1991 book <em>Japanese Candlestick Charting Techniques</em> is widely credited with introducing candlestick charting to Western retail traders. Before Nison, most US and European traders used bar charts. Within a decade, candlesticks had become the default on nearly every retail platform.</p>
<p>Nison has said that his goal was not to replace bar charts but to give Western traders "another lens" through which to view price. The lens proved far more popular than expected — partly because of its visual clarity, and partly because pattern names (hammer, evening star, three black crows) are more memorable than bar-chart structures.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Assuming colour conventions are universal.</strong> In Japan and some Asian markets, red often means "up" and blue or green means "down." Always check the platform's legend.</li>
    <li><strong>Reacting emotionally to individual candles.</strong> A single large candle can look dramatic but mean very little if the overall structure hasn't changed. Zoom out.</li>
    <li><strong>Confusing candlestick charts with Heikin Ashi.</strong> Heikin Ashi looks similar but uses averaged values — the candles are not actual OHLC data. See the next lesson.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some platforms allow <strong>hollow candlesticks</strong> — candles where the body is drawn as an outline instead of a filled rectangle. This is a hybrid style that combines the visual readability of candlesticks with the lighter footprint of bar charts. It's popular among traders who want the pattern information but find filled candlesticks too visually heavy for dense charts.</p>
HTML,
        ],

        [
            'slug'   => 'heikin-ashi',
            'title'  => 'Heikin Ashi Charts',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Explain how Heikin Ashi candles are calculated\n" .
                "• Distinguish Heikin Ashi from standard candlesticks\n" .
                "• Identify when Heikin Ashi is more useful than standard candles",
            'prerequisites' => 'Candlestick Charts',
            'sort_order' => 4,
            'summary' => 'Heikin Ashi is a Japanese charting method that smooths price data using averaged values. Each candle is calculated from the previous candle\'s open and close, so the chart looks smoother and trends are easier to see. The trade-off: the actual current price is not directly visible on the chart.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Heikin Ashi looks like candlesticks, but each candle isn't the actual price — it's an average of the actual price plus the previous candle. The result is a smoother, cleaner chart that filters out noise and makes trends easier to see.</p>
<p>The trade-off: the current price on a Heikin Ashi chart is not the real price. That's fine for spotting trends, but useless for precise entries.</p>

<h2>Real-world analogy</h2>
<p>Think of the difference between a raw photo and a filtered one. The filtered photo is smoother and easier on the eyes, but it isn't exactly what the camera captured. Heikin Ashi is the filtered version of a candlestick chart.</p>

<h2>Professional explanation</h2>
<p><strong>Heikin Ashi</strong> (Japanese for "average bar") is a charting technique where each candle is calculated from average values rather than raw OHLC data. The formulas are:</p>
<ul>
    <li><code>HA Close = (Open + High + Low + Close) / 4</code></li>
    <li><code>HA Open = (Previous HA Open + Previous HA Close) / 2</code></li>
    <li><code>HA High = Maximum of (High, HA Open, HA Close)</code></li>
    <li><code>HA Low = Minimum of (Low, HA Open, HA Close)</code></li>
</ul>
<p>The critical detail: <strong>each Heikin Ashi candle depends on the previous Heikin Ashi candle</strong>. This creates a smoothing effect where a single volatile period is dampened by the surrounding candles.</p>

<h3>What Heikin Ashi looks like</h3>
<ul>
    <li><strong>Long green bodies with tiny wicks</strong> — strong uptrend</li>
    <li><strong>Long red bodies with tiny wicks</strong> — strong downtrend</li>
    <li><strong>Small bodies and larger wicks</strong> — trend is weakening or transitioning</li>
    <li><strong>Frequent colour changes</strong> — choppy, ranging market (best to stand aside)</li>
</ul>

<h3>Visual reference — same data, standard vs Heikin Ashi</h3>
<svg viewBox="0 0 520 240" width="520" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Standard candles -->
  <g>
    <line x1="60" y1="80" x2="60" y2="200" stroke="#4ade80" stroke-width="2"/>
    <rect x="50" y="100" width="20" height="60" fill="#4ade80"/>
    <line x1="100" y1="90" x2="100" y2="180" stroke="#ef4444" stroke-width="2"/>
    <rect x="90" y="110" width="20" height="40" fill="#ef4444"/>
    <line x1="140" y1="60" x2="140" y2="160" stroke="#4ade80" stroke-width="2"/>
    <rect x="130" y="70" width="20" height="70" fill="#4ade80"/>
    <line x1="180" y1="50" x2="180" y2="140" stroke="#4ade80" stroke-width="2"/>
    <rect x="170" y="60" width="20" height="60" fill="#4ade80"/>
  </g>
  <text x="120" y="225" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Standard candles</text>

  <!-- Heikin Ashi -->
  <g>
    <line x1="340" y1="90" x2="340" y2="200" stroke="#4ade80" stroke-width="2"/>
    <rect x="330" y="100" width="20" height="80" fill="#4ade80"/>
    <line x1="380" y1="80" x2="380" y2="170" stroke="#4ade80" stroke-width="2"/>
    <rect x="370" y="90" width="20" height="60" fill="#4ade80"/>
    <line x1="420" y1="60" x2="420" y2="140" stroke="#4ade80" stroke-width="2"/>
    <rect x="410" y="70" width="20" height="55" fill="#4ade80"/>
    <line x1="460" y1="50" x2="460" y2="110" stroke="#4ade80" stroke-width="2"/>
    <rect x="450" y="55" width="20" height="45" fill="#4ade80"/>
  </g>
  <text x="400" y="225" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Heikin Ashi</text>
</svg>
<p style="text-align:center;color:#8b93a7;font-size:13px;margin-top:12px;">
  The Heikin Ashi version smooths the pullback into a clean uptrend.
</p>

<h3>When to use Heikin Ashi</h3>
<ul>
    <li><strong>Trend identification</strong> — HA candles make it much easier to see whether a market is trending and in which direction.</li>
    <li><strong>Riding a trend</strong> — HA keeps you in a strong trend longer by filtering out small pullbacks that would otherwise scare you out.</li>
    <li><strong>Beginners</strong> — HA smooths the emotional noise of individual candles, letting you see the bigger picture more clearly.</li>
</ul>

<h3>When not to use Heikin Ashi</h3>
<ul>
    <li><strong>Precise entries and exits</strong> — the HA price is not the real price. If you place a limit order based on an HA candle, you'll be off by the averaging error.</li>
    <li><strong>Reading classical candlestick patterns</strong> — hammers, engulfing patterns, doji — these rely on real OHLC data. HA candles produce false versions of these patterns.</li>
    <li><strong>Support/resistance precision</strong> — since HA highs and lows are also averaged, a specific resistance level drawn on HA may not align with the real market level.</li>
</ul>

<h2>Factual context</h2>
<p>Heikin Ashi has been used in Japan for decades, but like candlesticks, it was introduced to the West by Steve Nison in the 1990s. Nison's research found that the technique was used by Japanese traders to <em>stay in trends</em> — not to enter them. The averaged candles make a trend visually obvious and keep the trader in the position until the trend clearly weakens.</p>
<p>The famous quote often attributed to Jesse Livermore — </p>
<blockquote><strong>"It never was my thinking that made the big money for me. It always was my sitting."</strong></blockquote>
<p>— is essentially the philosophy behind Heikin Ashi. The chart's purpose is to help you sit through a trend, not to give you precise entries.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Executing trades at the HA candle's price.</strong> The HA close is not the market price. Always confirm actual prices on a standard candlestick chart before placing an order.</li>
    <li><strong>Drawing support/resistance on HA charts.</strong> The levels will be slightly off from real market levels. Use standard candles for structure work.</li>
    <li><strong>Trading a HA chart in a ranging market.</strong> HA candles excel at filtering noise in trends, but in a range they flip colours constantly, producing whipsaws.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some traders use a hybrid approach: identify the trend on a Heikin Ashi chart, then switch to a standard candlestick chart for entry and stop placement. This gives you the smoothing benefits of HA for direction and the precise OHLC data of standard candles for execution.</p>
<p>There are also variations: <strong>Heikin Ashi with moving averages</strong> (using an HA chart combined with a 20-period EMA to confirm trend), and <strong>Renko charts</strong> (a completely different smoothed chart type that only draws a new "brick" when price moves by a fixed amount). Both are advanced tools and worth exploring later, once you're comfortable with the fundamental chart types.</p>
HTML,
        ],

    ],
];