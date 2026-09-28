<?php
/**
 * Module 15 — MACD
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_15_macd.php
 */

return [
    'module' => [
        'level_slug' => 'intermediate',
        'slug'       => 'macd',
        'title'      => 'MACD (Moving Average Convergence Divergence)',
        'description'=> 'MACD is one of the most versatile indicators in technical analysis. It combines trend-following and momentum into a single view — showing both direction and strength. Learn how to read its three components, trade its crossovers, and recognise its divergence signals.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Understand the three components of MACD (line, signal, histogram)\n" .
            "• Trade MACD crossovers correctly\n" .
            "• Identify MACD divergence and understand what it means\n" .
            "• Combine MACD with RSI and moving averages\n" .
            "• Recognise MACD's limitations",
        'sort_order' => 15,
    ],

    'lessons' => [

        [
            'slug'   => 'what-is-macd',
            'title'  => 'What Is MACD?',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define MACD and its purpose\n" .
                "• Explain the three components of MACD\n" .
                "• Understand how MACD is calculated",
            'prerequisites' => 'Putting RSI Together',
            'sort_order' => 1,
            'summary' => 'MACD is a momentum indicator that shows the relationship between two moving averages of price. It consists of the MACD line, the signal line, and a histogram. The interaction between these three components generates crossover and momentum signals.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two runners side by side — a fast one and a slow one. MACD measures the gap between them. When the gap widens, the fast runner is accelerating. When it narrows, the fast runner is slowing down. When the fast runner overtakes the slow one, that's a signal.</p>
<p>MACD does exactly this with two moving averages — the 12-period EMA and the 26-period EMA. The gap between them is the MACD line.</p>

<h2>Real-world analogy</h2>
<p>Think of a weather pattern. Barometric pressure measures absolute pressure; temperature measures heat. MACD measures the *change* in the relationship between two moving averages — which is a proxy for momentum.</p>

<h2>Professional explanation</h2>
<p><strong>MACD</strong> — Moving Average Convergence Divergence — was developed by <strong>Gerald Appel</strong> in the late 1970s. It measures the relationship between two exponential moving averages (EMAs) of price.</p>

<h3>The three components</h3>

<h4>1. MACD line</h4>
<p>Calculated as:</p>
<p><code>MACD Line = 12-period EMA − 26-period EMA</code></p>
<p>When the 12 EMA is above the 26 EMA, the MACD line is positive (bullish). When below, it's negative (bearish).</p>

<h4>2. Signal line</h4>
<p>A 9-period EMA of the MACD line:</p>
<p><code>Signal Line = 9-period EMA of MACD Line</code></p>
<p>Crossovers between the MACD line and the signal line generate buy/sell signals.</p>

<h4>3. Histogram</h4>
<p>The difference between the MACD line and the signal line:</p>
<p><code>Histogram = MACD Line − Signal Line</code></p>
<p>The histogram visualises momentum changes — when it's expanding, momentum is strengthening; when it's contracting, momentum is weakening.</p>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Price top -->
  <polyline points="40,60 90,50 140,55 190,35 240,45 290,25 340,40 390,20 440,30"
            fill="none" stroke="#8b93a7" stroke-width="1.5"/>
  <text x="60" y="20" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif">Price</text>

  <!-- Zero line -->
  <line x1="30" y1="160" x2="470" y2="160" stroke="#8b93a7" stroke-width="0.5"/>

  <!-- Histogram bars -->
  <rect x="70" y="155" width="6" height="8" fill="#4ade80"/>
  <rect x="85" y="148" width="6" height="15" fill="#4ade80"/>
  <rect x="100" y="140" width="6" height="20" fill="#4ade80"/>
  <rect x="115" y="145" width="6" height="15" fill="#4ade80"/>
  <rect x="130" y="150" width="6" height="10" fill="#4ade80"/>
  <rect x="145" y="157" width="6" height="6" fill="#ef4444"/>
  <rect x="160" y="165" width="6" height="8" fill="#ef4444"/>
  <rect x="175" y="172" width="6" height="12" fill="#ef4444"/>
  <rect x="190" y="170" width="6" height="10" fill="#ef4444"/>
  <rect x="205" y="165" width="6" height="8" fill="#ef4444"/>
  <rect x="220" y="158" width="6" height="5" fill="#4ade80"/>
  <rect x="235" y="152" width="6" height="10" fill="#4ade80"/>
  <rect x="250" y="145" width="6" height="15" fill="#4ade80"/>

  <!-- MACD line -->
  <polyline points="40,140 90,135 140,145 190,165 240,158 290,145 340,140 390,130 440,120"
            fill="none" stroke="#5b7cfa" stroke-width="2.5"/>

  <!-- Signal line -->
  <polyline points="40,145 90,138 140,142 190,155 240,160 290,150 340,138 390,128 440,122"
            fill="none" stroke="#f97316" stroke-width="2"/>

  <text x="400" y="110" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif">MACD</text>
  <text x="400" y="140" fill="#f97316" font-size="11" font-family="Inter,sans-serif">Signal</text>
  <text x="60" y="245" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Histogram (bullish above zero)</text>
</svg>

<h3>Why MACD is powerful</h3>
<ul>
    <li><strong>Combines trend and momentum.</strong> The MACD line and its crossovers tell you about trend direction; the histogram tells you about momentum strength.</li>
    <li><strong>Has clear signals.</strong> Crossovers, zero-line crosses, and divergence give specific entry points.</li>
    <li><strong>Works across timeframes.</strong> MACD is used on everything from M5 to monthly.</li>
    <li><strong>Universally watched.</strong> Like moving averages, MACD is a self-fulfilling indicator — millions of traders use the same settings.</li>
</ul>

<h2>Factual context</h2>
<p>MACD was developed by <strong>Gerald Appel</strong>, an American financial analyst and author, in the late 1970s. Appel's original publication, "<em>The Moving Average Convergence-Divergence Trading Method</em>" (1979), introduced the indicator to the trading community. He later refined the approach with the histogram, which was formalised by <strong>Thomas Aspray</strong> in 1986.</p>
<p>The default settings — 12, 26, and 9 — were chosen by Appel based on his observations of market cycles. The 12 and 26 come from the number of trading days in a two-week and five-week period. The 9 comes from roughly 7–10 day cycles observed in the markets.</p>
<p>MACD has been one of the most widely-used indicators since the 1980s. Studies by firms like <strong>Ned Davis Research</strong> and <strong>Bloomberg</strong> have documented its effectiveness as a trend-following tool, though its win rate is similar to most trend-following indicators (around 40–45% with larger winners to compensate).</p>
<p>Appel himself emphasised that MACD was designed as a trend-following tool:</p>
<blockquote><strong>"The MACD is not a leading indicator. It is a lagging indicator. Its value comes from its ability to confirm trends after they have begun, not to predict them before they start."</strong></blockquote>
<p>Appel's honesty about the indicator's limitations is important. MACD, like all momentum indicators, lags price action. Its edge comes from the reliability of its trend confirmation, not from predicting turns.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading MACD signals in isolation.</strong> Like all indicators, MACD needs context. A crossover in a ranging market is noise.</li>
    <li><strong>Using different settings.</strong> Non-standard settings (like 5, 35, 5) are sometimes promoted as "better," but they reduce the reliability that comes from the indicator being universally watched.</li>
    <li><strong>Ignoring the histogram.</strong> The histogram is where momentum is most visible. Many traders watch only the crossovers and miss the momentum context.</li>
    <li><strong>Confusing MACD with a leading indicator.</strong> MACD confirms trends, it doesn't predict them.</li>
</ul>

<h2>Advanced notes</h2>
<p>Different timeframes have different MACD behaviour. On the daily chart, MACD crossovers are relatively rare and meaningful. On the M5 chart, crossovers happen constantly and are mostly noise. For MACD, the rule is: <strong>the higher the timeframe, the more reliable the signal</strong>. Many professional traders use MACD only on the daily or weekly charts, where its signals are cleanest.</p>
HTML,
        ],

        [
            'slug'   => 'macd-crossovers',
            'title'  => 'MACD Crossovers',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Identify bullish and bearish MACD crossovers\n" .
                "• Understand the difference between MACD-signal cross and zero-line cross\n" .
                "• Trade MACD crossovers with proper confirmation",
            'prerequisites' => 'What Is MACD?',
            'sort_order' => 2,
            'summary' => 'MACD crossovers generate buy and sell signals. A bullish crossover occurs when the MACD line crosses above the signal line; a bearish crossover when it crosses below. Zero-line crosses (MACD crossing above or below zero) provide additional context about the underlying trend.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two runners — one fast (MACD line), one slower (signal line). When the fast runner overtakes the slow one, that's a bullish crossover — a signal that short-term momentum is now beating medium-term momentum. When the fast runner falls behind, that's a bearish crossover.</p>

<h2>Real-world analogy</h2>
<p>Think of a car's cruise control. When you set a new higher speed, the car gradually accelerates to it. The moment the car's actual speed matches the target is the crossover — a signal that the acceleration phase is confirming.</p>

<h2>Professional explanation</h2>

<h3>Bullish crossover</h3>
<p>Occurs when the MACD line crosses <em>above</em> the signal line. This is a short-term bullish signal.</p>
<ul>
    <li>More reliable when it happens <strong>above the zero line</strong> (confirming an existing uptrend).</li>
    <li>Less reliable when it happens <strong>below the zero line</strong> (could just be a bounce in a downtrend).</li>
</ul>

<h3>Bearish crossover</h3>
<p>Occurs when the MACD line crosses <em>below</em> the signal line. This is a short-term bearish signal.</p>
<ul>
    <li>More reliable when it happens <strong>below the zero line</strong> (confirming an existing downtrend).</li>
    <li>Less reliable when it happens <strong>above the zero line</strong> (could just be a pullback in an uptrend).</li>
</ul>

<h3>Zero-line cross</h3>
<p>A separate signal that many traders overlook. Occurs when the MACD line itself crosses the zero line:</p>
<ul>
    <li><strong>MACD crosses above zero</strong> — the 12 EMA is now above the 26 EMA. Bullish trend change.</li>
    <li><strong>MACD crosses below zero</strong> — the 12 EMA is now below the 26 EMA. Bearish trend change.</li>
</ul>
<p>Zero-line crosses are slower but more significant — they mark actual trend shifts rather than short-term momentum shifts.</p>

<h3>Visual reference — Bullish Crossover</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Zero line -->
  <line x1="30" y1="120" x2="470" y2="120" stroke="#8b93a7" stroke-width="0.5"/>
  <text x="480" y="124" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">0</text>

  <!-- MACD line -->
  <polyline points="40,180 90,170 140,155 190,140 240,110 290,90 340,80 390,70 440,60"
            fill="none" stroke="#5b7cfa" stroke-width="2.5"/>

  <!-- Signal line -->
  <polyline points="40,175 90,165 140,158 190,148 240,130 290,105 340,90 390,75 440,65"
            fill="none" stroke="#f97316" stroke-width="2"/>

  <!-- Crossover point -->
  <circle cx="220" cy="120" r="8" fill="none" stroke="#4ade80" stroke-width="2.5"/>
  <text x="220" y="105" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Cross ↑</text>

  <text x="400" y="50" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif">MACD</text>
  <text x="400" y="75" fill="#f97316" font-size="11" font-family="Inter,sans-serif">Signal</text>
</svg>

<h3>Trading MACD crossovers</h3>
<ol>
    <li><strong>Confirm the higher-timeframe trend.</strong> Only take bullish crossovers in a bullish trend, and bearish crossovers in a bearish trend.</li>
    <li><strong>Wait for the crossover to complete.</strong> Enter on the close of the candle that produces the cross.</li>
    <li><strong>Look for confirmation from price action.</strong> A bullish structure break or a bullish candle pattern on the same candle is ideal.</li>
    <li><strong>Stop-loss</strong> below the recent swing low (for longs).</li>
    <li><strong>Target</strong> the previous swing high, or use a trailing stop.</li>
</ol>

<h3>Signal quality ranking</h3>
<table>
    <thead><tr><th>Crossover Type</th><th>Reliability</th><th>Use For</th></tr></thead>
    <tbody>
        <tr><td>Bullish cross above zero</td><td>High</td><td>Trend continuation longs</td></tr>
        <tr><td>Bullish cross below zero</td><td>Medium</td><td>Reversal setups (need extra confirmation)</td></tr>
        <tr><td>Bearish cross below zero</td><td>High</td><td>Trend continuation shorts</td></tr>
        <tr><td>Bearish cross above zero</td><td>Medium</td><td>Reversal setups (need extra confirmation)</td></tr>
    </tbody>
</table>

<h2>Factual context</h2>
<p>MACD crossovers are one of the most widely-traded signals in the industry. Studies of algorithmic trading systems have found that simple MACD crossover strategies produce modest returns over time, with roughly 40% win rates and average win/loss ratios of about 2:1. This produces a positive expectancy when applied consistently with proper risk management.</p>
<p>Gerald Appel himself has emphasised that the crossover's reliability depends on context:</p>
<blockquote><strong>"The MACD is most reliable when the crossover occurs in the direction of the longer-term trend. Counter-trend crossovers should be treated with scepticism."</strong></blockquote>
<p>Appel's original system was based on a long-term trend filter (using the 200-day moving average) combined with MACD crossovers for entry timing. This combination — trend + momentum confirmation — remains one of the most robust approaches in technical analysis.</p>
<p>Modern quant research supports Appel's approach. Studies by academic researchers on MACD crossovers have found that filtered versions (with trend or volatility filters) significantly outperform unfiltered versions. The core insight: crossovers work in trends, fail in ranges.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading every crossover.</strong> Crossovers in ranging markets produce constant whipsaws. Only take crossovers in clear trends.</li>
    <li><strong>Ignoring zero-line position.</strong> A bullish crossover above zero is much stronger than one below zero.</li>
    <li><strong>Entering on the crossover candle blindly.</strong> The price may already be extended. Wait for a small pullback or confirmation candle.</li>
    <li><strong>Not considering the higher timeframe.</strong> A bullish crossover on the H1 that contradicts a daily downtrend is unlikely to work.</li>
    <li><strong>Using MACD on low timeframes.</strong> Crossovers on M1/M5 are mostly noise. Stick to H4 and above.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often use <strong>MACD crossover + trend filter</strong> as a simple, robust system. The trend filter can be a moving average (only take longs when price is above the 200 SMA), a higher-timeframe trend (only take longs on the H4 if the daily is bullish), or a strength indicator (only take signals when ADX > 25). The filter eliminates the biggest weakness of MACD crossovers — their tendency to produce whipsaws in ranging markets.</p>
HTML,
        ],

        [
            'slug'   => 'macd-histogram',
            'title'  => 'The MACD Histogram',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Interpret the MACD histogram correctly\n" .
                "• Recognise momentum shifts before crossovers\n" .
                "• Use the histogram to filter crossover signals",
            'prerequisites' => 'MACD Crossovers',
            'sort_order' => 3,
            'summary' => 'The MACD histogram shows the distance between the MACD line and the signal line. It visualises momentum strength — expanding histogram means momentum is building; contracting histogram means momentum is fading. The histogram turns before the crossover, giving an early warning.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>The MACD histogram is the gap between the two MACD lines — visualised as bars. When the bars are getting taller, momentum is building. When they're getting shorter, momentum is fading. The histogram is the earliest signal in the MACD system.</p>

<h2>Real-world analogy</h2>
<p>Think of a sprinter's stride length. When they're accelerating, strides get longer. When they're tiring, strides get shorter. The histogram is the "stride length" of momentum.</p>

<h2>Professional explanation</h2>

<h3>What the histogram shows</h3>
<p><code>Histogram = MACD line − Signal line</code></p>
<ul>
    <li><strong>Positive histogram</strong> — MACD line is above the signal line. Bullish momentum.</li>
    <li><strong>Negative histogram</strong> — MACD line is below the signal line. Bearish momentum.</li>
    <li><strong>Expanding histogram</strong> — momentum strengthening.</li>
    <li><strong>Contracting histogram</strong> — momentum weakening.</li>
</ul>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Zero line -->
  <line x1="30" y1="120" x2="470" y2="120" stroke="#8b93a7" stroke-width="0.5"/>

  <!-- Histogram bars showing expansion then contraction -->
  <rect x="50" y="115" width="10" height="10" fill="#4ade80"/>
  <rect x="70" y="105" width="10" height="20" fill="#4ade80"/>
  <rect x="90" y="90" width="10" height="30" fill="#4ade80"/>
  <rect x="110" y="75" width="10" height="45" fill="#4ade80"/>
  <rect x="130" y="65" width="10" height="55" fill="#4ade80"/>
  <rect x="150" y="70" width="10" height="50" fill="#4ade80"/>
  <rect x="170" y="85" width="10" height="35" fill="#4ade80"/>
  <rect x="190" y="100" width="10" height="25" fill="#4ade80"/>
  <rect x="210" y="115" width="10" height="12" fill="#4ade80"/>
  <rect x="230" y="120" width="10" height="5" fill="#ef4444"/>
  <rect x="250" y="120" width="10" height="15" fill="#ef4444"/>
  <rect x="270" y="120" width="10" height="25" fill="#ef4444"/>
  <rect x="290" y="120" width="10" height="40" fill="#ef4444"/>
  <rect x="310" y="120" width="10" height="50" fill="#ef4444"/>
  <rect x="330" y="120" width="10" height="45" fill="#ef4444"/>
  <rect x="350" y="120" width="10" height="30" fill="#ef4444"/>
  <rect x="370" y="120" width="10" height="20" fill="#ef4444"/>
  <rect x="390" y="120" width="10" height="10" fill="#ef4444"/>

  <text x="130" y="220" fill="#4ade80" font-size="11" font-family="Inter,sans-serif">Expansion</text>
  <text x="300" y="220" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Contraction</text>
</svg>

<h3>What the histogram tells you</h3>

<h4>Expanding positive histogram</h4>
<p>Bullish momentum is strengthening. In an uptrend, this confirms the trend. In a downtrend, this is a warning that a rally may be developing.</p>

<h4>Contracting positive histogram</h4>
<p>Bullish momentum is fading — even if price is still rising. This is often the first sign of a pullback or reversal. When the histogram crosses below zero, the bearish crossover is confirmed.</p>

<h4>Expanding negative histogram</h4>
<p>Bearish momentum is strengthening. Confirms the downtrend.</p>

<h4>Contracting negative histogram</h4>
<p>Bearish momentum is fading — even if price is still falling. Often the first sign of a bounce or reversal.</p>

<h3>The histogram as an early warning</h3>
<p>The key value of the histogram is that it turns before the crossover:</p>
<ul>
    <li><strong>Before a bullish crossover</strong> — the histogram contracts from negative territory and eventually crosses above zero, then the MACD line crosses above the signal line.</li>
    <li><strong>Before a bearish crossover</strong> — the histogram contracts from positive territory and eventually crosses below zero, then the MACD line crosses below the signal line.</li>
</ul>
<p>This means the histogram gives you a warning of an upcoming crossover before it actually happens.</p>

<h3>Trading with the histogram</h3>
<ol>
    <li><strong>Watch the histogram for momentum shifts.</strong> When it starts contracting, prepare for a potential reversal.</li>
    <li><strong>Wait for the histogram to cross zero.</strong> This is the leading edge of the crossover.</li>
    <li><strong>Enter on the MACD-signal crossover</strong> for confirmation.</li>
    <li><strong>Stop-loss</strong> based on price structure, not the histogram.</li>
</ol>

<h2>Factual context</h2>
<p>The MACD histogram was not part of Gerald Appel's original MACD formulation. It was added by <strong>Thomas Aspray</strong> in 1986. Aspray's goal was to provide earlier signals than the traditional MACD-signal crossover — and his insight was that the distance between the two lines is itself a momentum measure that leads the crossover.</p>
<p>Since its introduction, the histogram has become one of the most-watched components of MACD. Many traders prefer to trade based on the histogram alone (its expansion, contraction, and zero-line crosses) rather than waiting for crossovers.</p>
<p>The histogram's leading nature is documented in technical analysis literature. Unlike crossover signals (which are lagging), the histogram provides earlier momentum information. However, its signals are also less reliable than crossovers — false momentum shifts occur frequently.</p>
<p>Appel, reflecting on the histogram in a 1990s interview:</p>
<blockquote><strong>"The histogram is the fastest component of MACD — and therefore the most prone to false signals. Traders who use it must accept that it will occasionally mislead. The trade-off is that it gives you the earliest indication of momentum shifts."</strong></blockquote>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading the histogram alone.</strong> The histogram's signals are early but noisy. Combine it with crossovers and price action.</li>
    <li><strong>Misreading contraction as reversal.</strong> Momentum contraction can just mean a pause, not necessarily a reversal.</li>
    <li><strong>Ignoring the histogram's scale.</strong> The absolute size of the histogram matters less than the trend of the histogram — is it expanding or contracting?</li>
    <li><strong>Over-trading the histogram's zero-line crosses.</strong> These occur frequently and often whipsaw.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders sometimes use <strong>histogram divergence</strong> — where price makes a new high but the histogram peak is lower than the prior peak. This is a variant of MACD divergence, but with the histogram rather than the MACD line. Histogram divergence has the advantage of being visible earlier than traditional divergence, but it also produces more false signals. The trade-off is between early signal and reliability.</p>
HTML,
        ],

        [
            'slug'   => 'macd-divergence',
            'title'  => 'MACD Divergence',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Identify regular and hidden MACD divergence\n" .
                "• Understand why MACD divergence is powerful\n" .
                "• Trade divergence setups with confirmation",
            'prerequisites' => 'The MACD Histogram',
            'sort_order' => 4,
            'summary' => 'MACD divergence occurs when price makes a new high or low but MACD does not confirm — signalling that momentum is fading. It is one of the most reliable reversal signals in technical analysis, especially when combined with price action confirmation.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a climber scaling a mountain. They reach a new peak, but they're breathing harder and moving slower. The next attempt to reach a higher peak fails — they can't go further. Even though they reached a new high, their energy was fading. That's divergence.</p>
<p>MACD divergence works the same way — price makes a new extreme, but the indicator shows that momentum is fading.</p>

<h2>Real-world analogy</h2>
<p>Think of a company's revenue. They post a new record quarter, but the growth rate is slowing. Revenue is at a new high, but momentum isn't. The divergence between revenue (price) and growth rate (MACD) is a warning sign.</p>

<h2>Professional explanation</h2>

<h3>Regular (bearish) divergence</h3>
<ul>
    <li>Price makes a <strong>higher high</strong>.</li>
    <li>MACD makes a <strong>lower high</strong>.</li>
    <li>Signal: bullish momentum is fading. Potential reversal to the downside.</li>
</ul>

<h3>Regular (bullish) divergence</h3>
<ul>
    <li>Price makes a <strong>lower low</strong>.</li>
    <li>MACD makes a <strong>higher low</strong>.</li>
    <li>Signal: bearish momentum is fading. Potential reversal to the upside.</li>
</ul>

<h3>Hidden (bearish) divergence</h3>
<ul>
    <li>Price makes a <strong>lower high</strong>.</li>
    <li>MACD makes a <strong>higher high</strong>.</li>
    <li>Signal: downtrend is likely to continue.</li>
</ul>

<h3>Hidden (bullish) divergence</h3>
<ul>
    <li>Price makes a <strong>higher low</strong>.</li>
    <li>MACD makes a <strong>lower low</strong>.</li>
    <li>Signal: uptrend is likely to continue.</li>
</ul>

<h3>Visual reference — Regular Bearish Divergence</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Price making higher highs -->
  <polyline points="40,180 90,110 140,140 190,70 240,110 290,30 340,90"
            fill="none" stroke="#8b93a7" stroke-width="2"/>
  <circle cx="190" cy="70" r="4" fill="#4ade80"/>
  <circle cx="290" cy="30" r="4" fill="#4ade80"/>
  <line x1="190" y1="70" x2="290" y2="30" stroke="#4ade80" stroke-width="1.5" stroke-dasharray="3,3"/>

  <!-- MACD making lower highs -->
  <polyline points="40,200 90,140 140,160 190,120 240,150 290,140 340,170"
            fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <circle cx="190" cy="120" r="4" fill="#ef4444"/>
  <circle cx="290" cy="140" r="4" fill="#ef4444"/>
  <line x1="190" y1="120" x2="290" y2="140" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="3,3"/>

  <text x="400" y="35" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Price HH</text>
  <text x="400" y="145" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">MACD LH</text>
  <text x="250" y="225" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Regular bearish divergence</text>
</svg>

<h3>MACD vs RSI divergence</h3>
<p>Both indicators show divergence, but they differ:</p>
<ul>
    <li><strong>RSI divergence</strong> — measures momentum of recent gains vs losses. Reacts faster. More signals, more false positives.</li>
    <li><strong>MACD divergence</strong> — measures the relationship between two moving averages. Reacts slower. Fewer signals, higher reliability.</li>
</ul>
<p>When both RSI and MACD show divergence simultaneously at a key level, the reversal signal is significantly stronger.</p>

<h3>Trading MACD divergence</h3>
<ol>
    <li><strong>Identify the divergence.</strong> Price makes a new extreme; MACD doesn't.</li>
    <li><strong>Wait for the price structure break.</strong> Don't trade on divergence alone. Wait for price to break a recent swing low (bearish) or swing high (bullish).</li>
    <li><strong>Enter on the break or retest.</strong></li>
    <li><strong>Stop-loss</strong> beyond the recent extreme.</li>
    <li><strong>Target</strong> the next major level.</li>
</ol>

<h3>What makes divergence strong</h3>
<ul>
    <li><strong>Multiple divergences</strong> — two or three in a row.</li>
    <li><strong>Location</strong> — divergence at a key support/resistance level.</li>
    <li><strong>Higher timeframe</strong> — daily or weekly divergence is far more meaningful.</li>
    <li><strong>Combined with price action</strong> — a bearish candle pattern at the divergence point.</li>
</ul>

<h2>Factual context</h2>
<p>Gerald Appel described divergence in his original 1979 work as one of the most reliable MACD signals. His observation was that divergence often precedes major reversals by several candles — providing time to prepare for the trade before the actual break occurs.</p>
<p>Modern statistical research has confirmed this. Studies have found that MACD divergence at major levels has a success rate of approximately 60–70% for reversal signals — higher than simple crossover signals. However, the divergence must be accompanied by a structural break in price; without this confirmation, divergence alone fails frequently.</p>
<p>Linda Raschke — one of the most successful traders of the 1990s and author of <em>Street Smarts</em> — has said:</p>
<blockquote><strong>"The single most reliable technical pattern I know is divergence between price and momentum. It doesn't work every time, but when it does, it's a big trade."</strong></blockquote>
<p>Raschke's point: divergence doesn't just signal small reversals. When it works, it often precedes major trend changes — which is why it produces outsized rewards when it does succeed.</p>
<p>Al Brooks, while generally skeptical of indicators, acknowledges divergence's value in specific contexts:</p>
<blockquote><strong>"Divergence tells you momentum is fading. But fading momentum doesn't mean reversal. It could just be a pause. Wait for structure."</strong></blockquote>
<p>Brooks' caution — like Appel's original emphasis on confirmation — is the practical key to trading divergence successfully.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading divergence without confirmation.</strong> Divergence alone often produces false signals. Wait for the price structure break.</li>
    <li><strong>Forcing divergence.</strong> If you have to squint, it's not there.</li>
    <li><strong>Ignoring the higher timeframe.</strong> MACD divergence on the M15 in a strong daily trend is usually just a pullback.</li>
    <li><strong>Confusing MACD and RSI divergence.</strong> They look similar but have different reliability profiles. Track both.</li>
    <li><strong>Over-trading divergence signals.</strong> Real divergence is relatively rare. If you see it constantly, you're forcing it.</li>
</ul>

<h2>Advanced notes</h2>
<p>The most powerful divergence setup is called the <strong>"triple divergence"</strong> — three consecutive instances where price makes a new high (or low) but MACD fails to confirm. This pattern often precedes major trend reversals and is highly reliable when it occurs. Studies have found that triple divergences have a success rate of approximately 75–80% when combined with a structural break — one of the highest-probability setups in technical analysis.</p>
HTML,
        ],

        [
            'slug'   => 'putting-macd-together',
            'title'  => 'Putting MACD Together',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Combine MACD with RSI, moving averages, and price action\n" .
                "• Build a MACD-based trend-following framework\n" .
                "• Use MACD effectively across timeframes",
            'prerequisites' => 'MACD Divergence',
            'sort_order' => 5,
            'summary' => 'This final lesson brings together everything in the module: the three components of MACD, crossovers, the histogram, and divergence. The goal is a repeatable process for integrating MACD into a layered trading framework.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>MACD is powerful but not magical. It works best when combined with other tools — moving averages for trend context, RSI for momentum confirmation, price action for entries. The goal is to use MACD's signals when they align with everything else.</p>

<h2>The complete framework</h2>

<h3>Step 1: Establish trend context</h3>
<p>On your higher timeframe (daily or weekly):</p>
<ul>
    <li>Use the 50 and 200 MAs to identify the trend direction.</li>
    <li>Note the MACD's position relative to zero.</li>
    <li>If MACD is above zero and rising, the trend is bullish. If below zero and falling, bearish.</li>
    <li>Note any divergence on the higher timeframe (rare but significant).</li>
</ul>

<h3>Step 2: Drop to your trading timeframe</h3>
<p>On the H4 or H1 chart:</p>
<ul>
    <li>Plot MACD with default settings (12, 26, 9).</li>
    <li>Look for MACD-signal crossovers.</li>
    <li>Check the histogram's direction (expanding or contracting).</li>
    <li>Note the MACD line's position relative to zero.</li>
</ul>

<h3>Step 3: Wait for a signal that aligns with the trend</h3>
<p>The best MACD signals:</p>
<ul>
    <li><strong>Bullish crossover above zero</strong> in a bullish trend.</li>
    <li><strong>Bearish crossover below zero</strong> in a bearish trend.</li>
    <li><strong>Divergence</strong> at a major level that aligns with the higher-timeframe structure.</li>
</ul>
<p>Avoid counter-trend crossover signals unless they're supported by strong divergence and price action.</p>

<h3>Step 4: Confirm with price action</h3>
<p>MACD signals should be confirmed by price action:</p>
<ul>
    <li>A bullish crossover at a support level, with a bullish candle, is high-probability.</li>
    <li>A bearish crossover at resistance, with a bearish candle, is high-probability.</li>
    <li>Crossover signals without price-action confirmation are lower probability.</li>
</ul>

<h3>Step 5: Enter with proper risk</h3>
<ul>
    <li><strong>Entry:</strong> on the close of the confirmation candle.</li>
    <li><strong>Stop-loss:</strong> based on price structure (recent swing low/high).</li>
    <li><strong>Target:</strong> the next significant level, or use a trailing stop.</li>
    <li><strong>R:R:</strong> at least 2:1.</li>
</ul>

<h3>Step 6: Manage by MACD + structure</h3>
<ul>
    <li>Move stop to break-even after a BOS in your direction.</li>
    <li>Trail stop below each new higher low (for longs).</li>
    <li>Exit when MACD shows divergence against your position, or when the MACD-signal crossover reverses.</li>
</ul>

<h2>Worked example — USD/JPY</h2>

<h3>Daily chart (context)</h3>
<ul>
    <li>Price above 50 MA and 200 MA. Both sloping up.</li>
    <li>MACD above zero, histogram expanding.</li>
    <li>Structure: <strong>bullish</strong>. Bias: long only.</li>
</ul>

<h3>H4 chart (setup)</h3>
<ul>
    <li>Price pulls back to the 20 EMA.</li>
    <li>MACD histogram has contracted to near zero.</li>
    <li>MACD line is approaching the signal line from above — a bullish crossover is forming.</li>
</ul>

<h3>H1 chart (entry)</h3>
<ul>
    <li>Price forms a bullish engulfing candle at the 20 EMA.</li>
    <li>MACD line crosses above signal line — bullish crossover confirmed.</li>
    <li>MACD line is above zero, confirming the bullish bias.</li>
    <li>Entry trigger: <strong>yes</strong>.</li>
</ul>

<h3>Trade plan</h3>
<ul>
    <li><strong>Entry:</strong> close of the engulfing candle.</li>
    <li><strong>Stop:</strong> below the recent swing low (40 pips).</li>
    <li><strong>Target:</strong> prior swing high (100 pips).</li>
    <li><strong>R:R:</strong> 2.5:1.</li>
</ul>

<h3>Management</h3>
<ul>
    <li>Move stop to break-even when MACD histogram reaches a new high.</li>
    <li>Trail stop below each new H4 higher low.</li>
    <li>Exit if MACD shows bearish divergence (price higher high, MACD lower high).</li>
</ul>

<h2>MACD + RSI synergy</h2>
<p>MACD and RSI complement each other:</p>
<ul>
    <li><strong>MACD</strong> — trend and momentum direction. Slower, more reliable.</li>
    <li><strong>RSI</strong> — momentum strength. Faster, more signals.</li>
</ul>
<p>When both agree, the signal is much stronger:</p>
<ul>
    <li>MACD bullish crossover + RSI rising above 50 = strong long signal.</li>
    <li>MACD bearish crossover + RSI falling below 50 = strong short signal.</li>
    <li>MACD divergence + RSI divergence at the same level = high-probability reversal.</li>
</ul>
<p>When they disagree, be cautious. The disagreement itself is information — it means momentum is unclear.</p>

<h2>Factual context</h2>
<p>The combination of MACD and RSI is one of the most common setups in professional trading systems. Studies of systematic trading strategies have found that combining MACD (for trend confirmation) with RSI (for momentum confirmation) produces significantly better risk-adjusted returns than either alone.</p>
<p>Gerald Appel, in his later writings, discussed the value of combining indicators:</p>
<blockquote><strong>"No single indicator is sufficient. MACD tells you the trend; other tools tell you the strength and location. The best trades come when multiple tools agree."</strong></blockquote>
<p>Appel's point is echoed across professional trading literature. Ed Seykota's famous rule — "follow the trend, cut losses, keep bets small" — relies on combining indicators to identify trends and entries. Moving average + MACD + RSI is one such combination.</p>
<p>Modern algorithmic trading has formalised the multi-indicator approach. A typical systematic strategy might use:</p>
<ul>
    <li>Trend filter (moving average)</li>
    <li>Momentum signal (MACD crossover)</li>
    <li>Oscillator confirmation (RSI)</li>
    <li>Volatility filter (ATR for position sizing)</li>
</ul>
<p>Each indicator serves a specific purpose, and their combination produces more robust results than any single tool.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Using MACD in isolation.</strong> Even the best MACD signal needs context from trend and price action.</li>
    <li><strong>Over-trading crossovers.</strong> Crossovers are common. Only trade the ones that align with trend and price action.</li>
    <li><strong>Ignoring the histogram.</strong> The histogram gives you early warning of crossover signals.</li>
    <li><strong>Trading on low timeframes.</strong> MACD on M5 produces mostly noise. Use it on H4 and above.</li>
    <li><strong>Not managing trades by MACD + structure.</strong> Using both gives you a robust exit framework.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some professional traders use <strong>MACD on multiple timeframes</strong> to identify high-probability setups. A classic example: MACD bullish crossover on the daily chart, MACD bullish crossover on the H4 chart, and MACD bullish crossover on the H1 chart — all within a short window of each other. This "triple timeframe MACD alignment" is rare but produces extremely high-probability signals. The challenge is waiting for the alignment — most traders will take signals before all three timeframes agree.</p>
<p>With MACD mastered, you now have the two most important momentum indicators in the toolkit — RSI and MACD. The next module, ATR, covers a volatility indicator that helps you size positions and place stops based on current market conditions. ATR completes the trio of essential indicators (RSI, MACD, ATR) that most retail traders use as their core toolkit.</p>
HTML,
        ],

    ],
];