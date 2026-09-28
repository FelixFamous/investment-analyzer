<?php
/**
 * Module 14 — RSI (Relative Strength Index)
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_14_rsi.php
 */

return [
    'module' => [
        'level_slug' => 'intermediate',
        'slug'       => 'rsi',
        'title'      => 'RSI (Relative Strength Index)',
        'description'=> 'RSI is the most widely used momentum indicator in trading — and the most widely misunderstood. It does not tell you when to buy or sell. It tells you how strong the current momentum is, and where momentum is diverging from price. Understanding those distinctions is what makes RSI useful.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Understand what RSI measures and how it is calculated\n" .
            "• Correctly interpret the 30, 50, and 70 levels\n" .
            "• Avoid the overbought/oversold trap\n" .
            "• Use RSI divergence to spot potential reversals\n" .
            "• Combine RSI with trend context for higher-probability trades",
        'sort_order' => 14,
    ],

    'lessons' => [

        [
            'slug'   => 'what-is-rsi',
            'title'  => 'What Is RSI?',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define RSI and what it measures\n" .
                "• Explain the 0–100 scale\n" .
                "• Understand how RSI is calculated",
            'prerequisites' => 'Putting Moving Averages Together',
            'sort_order' => 1,
            'summary' => 'RSI (Relative Strength Index) is a momentum indicator that measures the speed and magnitude of recent price changes. It oscillates between 0 and 100, with values above 70 suggesting strong upward momentum and values below 30 suggesting strong downward momentum.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a runner on a treadmill. Sometimes they're sprinting — max speed, high energy. Sometimes they're barely jogging. Sometimes they're walking. RSI is like a speedometer for the market — it tells you how fast price has been moving recently, not just in which direction.</p>
<p>High RSI = strong recent upward momentum. Low RSI = strong recent downward momentum. Middle RSI (around 50) = balanced momentum.</p>

<h2>Real-world analogy</h2>
<p>Think of a car's RPM gauge. It doesn't tell you where the car is going — only how hard the engine is working. RSI works the same way. It doesn't tell you direction; it tells you intensity.</p>

<h2>Professional explanation</h2>
<p><strong>RSI</strong> — Relative Strength Index — is a momentum oscillator developed by <strong>J. Welles Wilder Jr.</strong> in 1978. It measures the ratio of recent gains to recent losses, producing a value between 0 and 100.</p>

<h3>The basic formula</h3>
<p><code>RSI = 100 − [100 / (1 + RS)]</code></p>
<p>Where:</p>
<ul>
    <li><code>RS = Average Gain over period / Average Loss over period</code></li>
    <li>The default period is 14 (using 14 recent candles)</li>
</ul>
<p>Interpretation:</p>
<ul>
    <li>If average gains are much larger than average losses, RS is high, and RSI approaches 100.</li>
    <li>If average losses are much larger than average gains, RS is low, and RSI approaches 0.</li>
    <li>If gains and losses are equal, RS = 1, and RSI = 50.</li>
</ul>

<h3>Reading the scale</h3>
<table>
    <thead><tr><th>RSI Value</th><th>Traditional Meaning</th><th>Actual Meaning</th></tr></thead>
    <tbody>
        <tr><td>Above 70</td><td>"Overbought"</td><td>Strong recent upward momentum</td></tr>
        <tr><td>50–70</td><td>Bullish bias</td><td>Moderate upward momentum</td></tr>
        <tr><td>30–50</td><td>Bearish bias</td><td>Moderate downward momentum</td></tr>
        <tr><td>Below 30</td><td>"Oversold"</td><td>Strong recent downward momentum</td></tr>
    </tbody>
</table>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Price chart top -->
  <polyline points="40,80 90,60 140,70 190,40 240,55 290,25 340,45 390,15 440,30"
            fill="none" stroke="#8b93a7" stroke-width="2"/>
  <text x="60" y="30" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif">Price</text>

  <!-- RSI bottom -->
  <line x1="30" y1="140" x2="470" y2="140" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="30" y1="170" x2="470" y2="170" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="30" y1="200" x2="470" y2="200" stroke="#8b93a7" stroke-width="0.5"/>

  <!-- RSI line -->
  <polyline points="40,180 90,150 140,165 190,145 240,155 290,140 340,150 390,135 440,145"
            fill="none" stroke="#5b7cfa" stroke-width="2"/>

  <!-- Overbought/oversold zones -->
  <rect x="30" y="140" width="440" height="15" fill="#ef4444" fill-opacity="0.1"/>
  <rect x="30" y="185" width="440" height="15" fill="#4ade80" fill-opacity="0.1"/>
  <text x="480" y="152" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">70</text>
  <text x="480" y="175" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">50</text>
  <text x="480" y="198" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">30</text>
</svg>

<h3>Why "relative strength"</h3>
<p>The name is slightly misleading. "Relative strength" in RSI doesn't mean the strength of one currency relative to another. It means the strength of recent gains relative to recent losses — a self-contained momentum measure.</p>

<h2>Factual context</h2>
<p>RSI was developed by <strong>J. Welles Wilder Jr.</strong>, an engineer-turned-trader, in his 1978 book <em>New Concepts in Technical Trading Systems</em>. Wilder was one of the most important contributors to modern technical analysis — he also developed the ATR, ADX, and Parabolic SAR indicators.</p>
<p>Wilder's original research suggested that an RSI above 70 indicated an overbought condition and below 30 indicated an oversold condition. This was based on his analysis of commodities markets in the 1970s. Over time, traders discovered that RSI behaviour varies by market and timeframe, and the 70/30 thresholds are guidelines rather than absolute rules.</p>
<p>The default period of 14 is also Wilder's recommendation. He arrived at this number by splitting a 28-day cycle (which he considered a lunar month) in half. There's no strong mathematical reason for 14 — but because so many traders use it, it remains the standard.</p>
<p>Al Brooks has repeatedly emphasised that indicators like RSI "should never be used in isolation." His point: they measure momentum, which is useful — but momentum alone doesn't tell you the direction or the probability of a trade.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Treating 70 as a sell signal.</strong> RSI above 70 means strong upward momentum. In a strong uptrend, RSI can stay above 70 for weeks.</li>
    <li><strong>Treating 30 as a buy signal.</strong> RSI below 30 means strong downward momentum. In a strong downtrend, RSI can stay below 30 for weeks.</li>
    <li><strong>Using RSI without trend context.</strong> RSI in isolation is nearly meaningless. Combined with trend, it becomes useful.</li>
    <li><strong>Assuming RSI predicts the future.</strong> RSI measures what has already happened. It's a lagging indicator.</li>
</ul>

<h2>Advanced notes</h2>
<p>Different markets show different RSI behaviour. In trending markets, RSI tends to stay in the 40–80 range (bullish) or 20–60 range (bearish), rarely touching the extremes. In ranging markets, RSI oscillates regularly between 30 and 70. Some traders adjust the RSI thresholds based on the market state — for example, using 80/40 in strong uptrends and 60/20 in strong downtrends. This adaptive approach is more accurate than using fixed 70/30 thresholds in all conditions.</p>
HTML,
        ],

        [
            'slug'   => 'rsi-levels',
            'title'  => 'The 30, 50, and 70 Levels',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Interpret the 30, 50, and 70 levels correctly\n" .
                "• Distinguish between overbought/oversold in trends vs ranges\n" .
                "• Use the 50 level as a trend filter",
            'prerequisites' => 'What Is RSI?',
            'sort_order' => 2,
            'summary' => 'The RSI levels mean different things in different market contexts. In a ranging market, 30 and 70 mark reversal zones. In a trending market, they mark momentum extremes that often continue. The 50 level is the true divider between bullish and bearish momentum.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Think of RSI as three zones:</p>
<ul>
    <li><strong>Above 70</strong> — the bulls are running.</li>
    <li><strong>Around 50</strong> — momentum is balanced.</li>
    <li><strong>Below 30</strong> — the bears are running.</li>
</ul>
<p>The critical insight is that "running" doesn't mean "about to stop." A market can stay in the "above 70" zone for weeks during a strong uptrend.</p>

<h2>Real-world analogy</h2>
<p>Think of a marathon runner's heart rate. During the race, their heart rate stays above 150 for hours. It doesn't mean they're about to collapse — it means they're working hard. RSI works the same way. High readings mean high effort, not imminent reversal.</p>

<h2>Professional explanation</h2>

<h3>The 30 level</h3>
<p>Traditional interpretation: oversold. Actual interpretation: strong recent downward momentum.</p>
<p>When RSI hits 30:</p>
<ul>
    <li><strong>In a ranging market</strong> — often a buy signal as the range extends to its lower bound.</li>
    <li><strong>In a downtrend</strong> — often just a pause; RSI can stay below 30 for extended periods.</li>
    <li><strong>In a reversal setup</strong> — RSI at 30 after a strong downtrend can precede a bounce.</li>
</ul>

<h3>The 70 level</h3>
<p>Traditional interpretation: overbought. Actual interpretation: strong recent upward momentum.</p>
<p>When RSI hits 70:</p>
<ul>
    <li><strong>In a ranging market</strong> — often a sell signal as the range extends to its upper bound.</li>
    <li><strong>In an uptrend</strong> — often just a pause; RSI can stay above 70 for extended periods.</li>
    <li><strong>In a reversal setup</strong> — RSI at 70 after a strong uptrend can precede a pullback.</li>
</ul>

<h3>The 50 level</h3>
<p>The 50 level is the true divider between bullish and bearish momentum. It's often overlooked but is arguably more important than 30/70.</p>
<ul>
    <li><strong>RSI holding above 50</strong> — bullish bias. In a healthy uptrend, RSI often bounces from around 50.</li>
    <li><strong>RSI holding below 50</strong> — bearish bias. In a healthy downtrend, RSI often stalls around 50 before falling further.</li>
    <li><strong>RSI oscillating around 50</strong> — no clear momentum. Range-bound market.</li>
</ul>

<h3>Visual reference — RSI in trend vs range</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Trending RSI (stays high) -->
  <line x1="30" y1="80" x2="230" y2="80" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="30" y1="120" x2="230" y2="120" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="30" y1="160" x2="230" y2="160" stroke="#8b93a7" stroke-width="0.5"/>
  <polyline points="40,110 70,85 100,95 130,75 160,90 190,70 220,80"
            fill="none" stroke="#4ade80" stroke-width="2"/>
  <text x="130" y="200" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Trending — RSI stays elevated</text>

  <!-- Ranging RSI (oscillates) -->
  <line x1="270" y1="80" x2="470" y2="80" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="270" y1="120" x2="470" y2="120" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="270" y1="160" x2="470" y2="160" stroke="#8b93a7" stroke-width="0.5"/>
  <polyline points="280,150 310,85 340,155 370,85 400,150 430,85 460,150"
            fill="none" stroke="#f97316" stroke-width="2"/>
  <text x="370" y="200" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Ranging — RSI oscillates</text>
</svg>

<h3>Using 50 as a trend filter</h3>
<p>A common professional approach:</p>
<ul>
    <li><strong>Only look for longs when RSI is above 50.</strong> Below 50, longs fight momentum.</li>
    <li><strong>Only look for shorts when RSI is below 50.</strong> Above 50, shorts fight momentum.</li>
    <li><strong>Skip trades when RSI is oscillating around 50.</strong> Momentum is neutral.</li>
</ul>
<p>This simple filter removes many bad trades. It doesn't tell you when to enter — it tells you when to consider entering.</p>

<h2>Factual context</h2>
<p>Wilder's original research in 1978 was based on commodities markets, which were largely range-bound in that era. The 30/70 thresholds worked well in that context. As markets evolved and trends became more persistent, traders discovered that the thresholds needed adjustment.</p>
<p>Modern research by <strong>Constance Brown</strong> (author of <em>Technical Analysis for the Trading Professional</em>) and others has documented that RSI behaves differently in bull and bear markets. In bull markets, RSI tends to find support around 40 rather than 30, and resistance around 80 rather than 70. In bear markets, the ranges invert. This is why the 50 level and adaptive thresholds are more useful than fixed 30/70.</p>
<p>Andrew Cardwell, a prominent RSI researcher, developed a framework called <strong>"RSI ranges"</strong> — the observation that in a bull market, RSI typically fluctuates between 40 and 80, and in a bear market, between 20 and 60. His work extended Wilder's original analysis significantly and is considered the definitive modern research on RSI.</p>
<p>Martin Pring, another prominent technical analyst, has emphasised that the RSI level alone tells you nothing — it must be interpreted in the context of trend direction and momentum behaviour.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Selling every time RSI > 70.</strong> In strong trends, this produces nothing but losses as RSI stays elevated.</li>
    <li><strong>Buying every time RSI < 30.</strong> Same problem in reverse.</li>
    <li><strong>Ignoring the 50 level.</strong> The 50 level is a better filter than the 30/70 thresholds in trending markets.</li>
    <li><strong>Using fixed thresholds in all markets.</strong> Adjust the thresholds based on whether the market is trending or ranging.</li>
    <li><strong>Not distinguishing between trend and range.</strong> RSI in a trend behaves differently from RSI in a range. Identify the market state first.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional RSI traders use a concept called <strong>"range rules"</strong>:</p>
<ul>
    <li><strong>In a bull trend</strong> — RSI 40 is support, RSI 80 is resistance. Buy the dips to 40–50.</li>
    <li><strong>In a bear trend</strong> — RSI 60 is resistance, RSI 20 is support. Sell the rallies to 50–60.</li>
    <li><strong>In a range</strong> — RSI 30 and 70 are the boundaries. Trade the oscillations.</li>
</ul>
<p>This adaptive framework is more nuanced than the standard "overbought/oversold" reading and reflects the reality of how RSI behaves in different market conditions.</p>
HTML,
        ],

        [
            'slug'   => 'overbought-oversold-misconception',
            'title'  => 'The Overbought/Oversold Misconception',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Explain why overbought ≠ sell and oversold ≠ buy\n" .
                "• Recognise when RSI extremes indicate strength rather than exhaustion\n" .
                "• Adjust your RSI interpretation based on trend context",
            'prerequisites' => 'The 30, 50, and 70 Levels',
            'sort_order' => 3,
            'summary' => 'The most common RSI mistake is treating "overbought" as a sell signal and "oversold" as a buy signal. In trending markets, RSI can stay in extreme zones for extended periods — because strong momentum tends to continue. The trick is knowing when extremes mean exhaustion and when they mean strength.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine you're watching a car accelerate. If someone tells you "the engine is at max RPM — it must be about to stop," you'd think they're crazy. Cars at high RPM are moving fast, not stopping.</p>
<p>RSI works the same way. When RSI hits 70, the market is at "high RPM" — strong momentum. It doesn't mean the trend is about to reverse. It means the trend is strong.</p>

<h2>Real-world analogy</h2>
<p>Think of a rocket launch. During the initial ascent, the rocket is at maximum thrust for minutes. If you tried to "sell" every time the thrust was at max, you'd miss the entire launch. Market trends work the same way.</p>

<h2>Professional explanation</h2>

<h3>The misconception</h3>
<p>Wilder's original work suggested that RSI above 70 = overbought (likely to fall) and RSI below 30 = oversold (likely to rise). This works in ranging markets — where price oscillates between two levels.</p>
<p>But in trending markets, the traditional reading fails. Here's why:</p>
<ul>
    <li><strong>In a strong uptrend</strong> — RSI stays elevated because buyers are consistently stronger than sellers. RSI above 70 is the norm, not an exception.</li>
    <li><strong>In a strong downtrend</strong> — RSI stays depressed because sellers are consistently stronger. RSI below 30 is the norm.</li>
</ul>
<p>If you sell every time RSI > 70 in a strong uptrend, you'll take a series of small losses. If you buy every time RSI < 30 in a strong downtrend, you'll take a series of larger losses.</p>

<h3>The correct interpretation</h3>
<p>RSI extremes mean one of two things, depending on context:</p>
<ul>
    <li><strong>In a range</strong> — RSI 70/30 mean "at the boundary of the range." Trade the reversal back toward the middle.</li>
    <li><strong>In a trend</strong> — RSI 70/30 mean "strong momentum." Trade in the direction of the trend; don't fight it.</li>
</ul>

<h3>When extremes DO signal exhaustion</h3>
<p>RSI extremes only become meaningful reversal signals when combined with other factors:</p>
<ul>
    <li><strong>RSI divergence</strong> — price makes a new high but RSI does not. This is the strongest exhaustion signal.</li>
    <li><strong>Failed RSI extremes</strong> — the first time RSI hits 70 in a strong uptrend, it's a signal of strength. The second time it hits 70 with a lower peak, and price also makes a lower high, that's a warning.</li>
    <li><strong>RSI breaking structure</strong> — RSI breaking below a prior low while price holds above its own prior low can precede a price reversal.</li>
    <li><strong>Confluence with price action</strong> — a bearish reversal pattern at resistance, with RSI at 70 and fading, is more meaningful than RSI at 70 alone.</li>
</ul>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Trending uptrend with high RSI -->
  <text x="120" y="20" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Strong uptrend — RSI stays >70</text>
  <polyline points="40,180 80,140 120,150 160,110 200,120 240,80 280,90 320,50"
            fill="none" stroke="#4ade80" stroke-width="2"/>
  <line x1="30" y1="210" x2="400" y2="210" stroke="#8b93a7" stroke-width="0.5"/>
  <polyline points="40,200 90,180 140,185 190,170 240,175 290,160 340,165"
            fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <line x1="30" y1="170" x2="400" y2="170" stroke="#ef4444" stroke-width="0.8" stroke-dasharray="3,3"/>
  <text x="410" y="173" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">70</text>
  <text x="150" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">RSI stays high — signal of strength</text>
</svg>

<h3>The practical rule</h3>
<p>Treat RSI levels as follows:</p>
<ul>
    <li><strong>In a trend</strong> — RSI 70/30 confirm the trend. Trade with the trend; RSI extremes are not reversal signals.</li>
    <li><strong>In a range</strong> — RSI 70/30 mark boundaries. Trade the reversal back toward the middle.</li>
    <li><strong>With divergence</strong> — RSI extremes become reversal signals when RSI fails to confirm new price extremes.</li>
</ul>

<h2>Factual context</h2>
<p>Wilder's original work was based on the 1970s commodities markets, which were largely range-bound. In that context, the overbought/oversold interpretation was reasonable. But the markets have changed — modern trends tend to be more persistent, driven by algorithmic trading and global capital flows.</p>
<p>In 1989, <strong>Andrew Cardwell</strong> published research showing that RSI behaves fundamentally differently in bull and bear markets. His work demonstrated that RSI ranges shift — in bull markets, RSI typically fluctuates between 40 and 80; in bear markets, between 20 and 60. This extended Wilder's original work and fundamentally changed how professional traders use RSI.</p>
<p>More recent research by traders like <strong>Constance Brown</strong> has confirmed these findings and added nuance. Brown's work emphasises that RSI "positive reversals" and "negative reversals" are more meaningful than simple overbought/oversold readings.</p>
<p>Paul Tudor Jones has commented on the danger of trading extremes:</p>
<blockquote><strong>"I believe the very best money is made at the market turns. But you can't predict them. You have to wait for the turn to be confirmed."</strong></blockquote>
<p>Jones' point applies directly to RSI. An overbought reading is not a "turn." It's just a state of strong momentum. Waiting for confirmation — a divergence, a price structure break, or a bearish candle pattern — is what turns RSI from noise into signal.</p>
<p>Al Brooks' advice on indicators applies here:</p>
<blockquote><strong>"All indicators lag. They tell you what has happened, not what will happen. You must interpret them in the context of price action."</strong></blockquote>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Selling every RSI > 70.</strong> The single most common RSI mistake in retail trading.</li>
    <li><strong>Buying every RSI < 30.</strong> Same problem in reverse.</li>
    <li><strong>Ignoring trend context.</strong> The same RSI level means different things in trending vs ranging markets.</li>
    <li><strong>Assuming high RSI = reversal imminent.</strong> High RSI means strong momentum. It says nothing about when the momentum will fade.</li>
    <li><strong>Not waiting for confirmation.</strong> RSI extremes need confirmation from price action or divergence before they become signals.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use a concept called <strong>"failure swings"</strong> to identify when RSI extremes are actually significant. A failure swing is when RSI:</p>
<ol>
    <li>Reaches an extreme (e.g., above 70).</li>
    <li>Pulls back.</li>
    <li>Fails to reach a new extreme on the next attempt.</li>
    <li>Breaks back below the prior pullback low (for an overbought scenario).</li>
</ol>
<p>This three-part structure is a much stronger signal than a simple overbought reading. It combines RSI's momentum measure with a formal structure — the failure to make a new high — which is directly analogous to the "lower high" concept in price action. Failure swings are one of the earliest technical signals that momentum is actually reversing, and they remain one of the most reliable uses of RSI today.</p>
HTML,
        ],

        [
            'slug'   => 'rsi-divergence',
            'title'  => 'RSI Divergence',
            'difficulty' => 'intermediate',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Define regular and hidden RSI divergence\n" .
                "• Identify divergence on a chart\n" .
                "• Trade divergence with proper confirmation",
            'prerequisites' => 'The Overbought/Oversold Misconception',
            'sort_order' => 4,
            'summary' => 'RSI divergence is one of the most powerful signals in technical analysis. It occurs when price makes a new high or low but RSI does not — signalling that momentum is failing to confirm the price move. Regular divergence signals reversals; hidden divergence signals continuations.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two friends running a race. One is sprinting ahead — gaining ground quickly. But the other friend is only jogging, and starting to slow down. Despite being ahead, the first friend's lead is built on diminishing energy.</p>
<p>RSI divergence works the same way. Price keeps making new highs, but RSI doesn't confirm — signalling that the momentum behind the move is fading.</p>

<h2>Real-world analogy</h2>
<p>Think of a car engine revving loudly but the car not accelerating. The engine says "fast," the car says "not really." The disagreement is the signal — something is wrong.</p>

<h2>Professional explanation</h2>

<h3>Regular (bearish) divergence</h3>
<p>Occurs at the end of an uptrend:</p>
<ul>
    <li>Price makes a <strong>higher high</strong>.</li>
    <li>RSI makes a <strong>lower high</strong>.</li>
    <li>Interpretation: momentum is weakening even as price rises. Potential reversal to the downside.</li>
</ul>

<h3>Regular (bullish) divergence</h3>
<p>Occurs at the end of a downtrend:</p>
<ul>
    <li>Price makes a <strong>lower low</strong>.</li>
    <li>RSI makes a <strong>higher low</strong>.</li>
    <li>Interpretation: selling pressure is easing even as price falls. Potential reversal to the upside.</li>
</ul>

<h3>Hidden (bearish) divergence</h3>
<p>Occurs during a downtrend, signalling continuation:</p>
<ul>
    <li>Price makes a <strong>lower high</strong>.</li>
    <li>RSI makes a <strong>higher high</strong>.</li>
    <li>Interpretation: momentum is building despite a pullback. The downtrend is likely to continue.</li>
</ul>

<h3>Hidden (bullish) divergence</h3>
<p>Occurs during an uptrend, signalling continuation:</p>
<ul>
    <li>Price makes a <strong>higher low</strong>.</li>
    <li>RSI makes a <strong>lower low</strong>.</li>
    <li>Interpretation: momentum is building despite a pullback. The uptrend is likely to continue.</li>
</ul>

<h3>Visual reference — Regular Bearish Divergence</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Price making higher highs -->
  <polyline points="40,180 90,120 140,140 190,80 240,110 290,40 340,80"
            fill="none" stroke="#8b93a7" stroke-width="2"/>
  <!-- Higher highs in price -->
  <circle cx="190" cy="80" r="4" fill="#4ade80"/>
  <circle cx="290" cy="40" r="4" fill="#4ade80"/>
  <line x1="190" y1="80" x2="290" y2="40" stroke="#4ade80" stroke-width="1.5" stroke-dasharray="3,3"/>

  <!-- RSI making lower highs -->
  <polyline points="40,200 90,170 140,185 190,150 240,170 290,165 340,190"
            fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <!-- Lower highs in RSI -->
  <circle cx="190" cy="150" r="4" fill="#ef4444"/>
  <circle cx="290" cy="165" r="4" fill="#ef4444"/>
  <line x1="190" y1="150" x2="290" y2="165" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="3,3"/>

  <text x="380" y="50" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Price: HH</text>
  <text x="380" y="170" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">RSI: LH</text>
  <text x="250" y="225" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Regular bearish divergence</text>
</svg>

<h3>Trading divergence</h3>
<p>Divergence is a warning, not a signal by itself. Wait for confirmation:</p>
<ol>
    <li><strong>Identify the divergence.</strong> Price makes a new extreme; RSI doesn't.</li>
    <li><strong>Wait for price structure to break.</strong> In a bearish divergence, wait for price to break its most recent swing low.</li>
    <li><strong>Enter on the break.</strong> Or on the retest of the broken structure.</li>
    <li><strong>Stop-loss</strong> beyond the recent swing high (for bearish) or low (for bullish).</li>
    <li><strong>Target</strong> the next significant level.</li>
</ol>

<h3>What makes strong divergence</h3>
<ul>
    <li><strong>Multiple divergences</strong> — two or three consecutive divergences is stronger than one.</li>
    <li><strong>Deep divergence</strong> — a large gap between the two RSI peaks is more meaningful.</li>
    <li><strong>Location</strong> — divergence at a key resistance or support level is stronger.</li>
    <li><strong>Higher timeframe</strong> — divergence on the daily or weekly chart carries more weight than on the M15.</li>
    <li><strong>Confirmation with other signals</strong> — bearish candles, structure break, exhaustion patterns.</li>
</ul>

<h2>Factual context</h2>
<p>RSI divergence is one of the most studied and documented phenomena in technical analysis. Wilder mentioned it briefly in his 1978 book but didn't develop it extensively. It was extended by later researchers — particularly <strong>Andrew Cardwell</strong> and <strong>Constance Brown</strong>.</p>
<p>Cardwell's research in the late 1980s and 1990s popularised the use of divergence in professional trading. His observations, documented in his trading courses and research papers, showed that divergence at the end of trends was statistically more reliable than divergence mid-trend.</p>
<p>Modern statistical research on divergence — including studies by academic researchers and practitioner research from firms like <strong>Bloomberg</strong> and <strong>Ned Davis Research</strong> — has found that regular divergence has a moderate but real predictive edge, with success rates around 55–65% depending on context. Divergence at major support/resistance, on higher timeframes, and with confirmation is significantly more reliable.</p>
<p>Al Brooks is generally skeptical of divergence as a standalone signal, but acknowledges its use in specific contexts:</p>
<blockquote><strong>"Divergence tells you momentum is fading. But fading momentum doesn't mean the trend is over — it might just be pausing. Wait for structure to break before acting."</strong></blockquote>
<p>Brooks' caution is important. Divergence can persist for a long time while the trend continues. The failure to break structure is common with divergence setups. The lesson: always wait for confirmation.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading divergence without confirmation.</strong> Divergence alone often fails. Wait for the price structure break.</li>
    <li><strong>Confusing regular and hidden divergence.</strong> Regular signals reversal; hidden signals continuation. Getting them mixed up flips your trade direction.</li>
    <li><strong>Forcing divergence.</strong> If you have to squint to see it, it's not there. Real divergence is visually obvious.</li>
    <li><strong>Ignoring the trend.</strong> Divergence against the higher-timeframe trend is low probability. Divergence aligned with it is high probability.</li>
    <li><strong>Over-relying on RSI.</strong> Divergence is one signal. Combine it with price action, structure, and levels.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often use <strong>multi-timeframe divergence analysis</strong>. Bearish divergence on the daily chart combined with bullish divergence on the H4 chart can produce very specific setups — the H4 divergence signals a short-term bounce within a larger topping structure on the daily. These setups require experience to trade well, but they represent the kind of nuanced analysis that separates professional RSI users from beginners.</p>
HTML,
        ],

        [
            'slug'   => 'rsi-failure-swings',
            'title'  => 'RSI Failure Swings',
            'difficulty' => 'intermediate',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define an RSI failure swing\n" .
                "• Identify failure swings on a chart\n" .
                "• Trade them as early reversal signals",
            'prerequisites' => 'RSI Divergence',
            'sort_order' => 5,
            'summary' => 'A failure swing is a specific RSI structure that occurs at extremes — a pattern of peaks and troughs in the RSI itself that signals a genuine momentum reversal. Wilder considered it one of the most reliable RSI signals.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine climbing a hill and reaching a peak. Then you slip down slightly, try to reach the peak again, but can't — you only reach a lower point. Then you fall further. That's a failure swing — a specific pattern of failure at the top.</p>

<h2>Real-world analogy</h2>
<p>Think of a tennis player who dominates the first set, loses the second, and then starts losing the third. They had the peak of their energy in set one — the rest is a decline. The moment they fail to reach their peak again is the "failure swing."</p>

<h2>Professional explanation</h2>

<h3>Bearish failure swing</h3>
<ol>
    <li>RSI rises above 70 (into overbought territory).</li>
    <li>RSI pulls back but stays above 30 (usually above 50 or 70).</li>
    <li>RSI rallies again but fails to exceed the prior peak — it makes a lower high.</li>
    <li>RSI falls below the prior pullback low. The failure swing is complete.</li>
</ol>
<p>Signal: bearish reversal.</p>

<h3>Bullish failure swing</h3>
<ol>
    <li>RSI falls below 30 (into oversold territory).</li>
    <li>RSI bounces but stays below 70 (usually below 50 or 30).</li>
    <li>RSI falls again but fails to exceed the prior trough — it makes a higher low.</li>
    <li>RSI rises above the prior bounce high. The failure swing is complete.</li>
</ol>
<p>Signal: bullish reversal.</p>

<h3>Visual reference — Bullish Failure Swing</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- RSI line -->
  <polyline points="40,80 90,150 140,100 190,190 240,110 290,180 340,60"
            fill="none" stroke="#5b7cfa" stroke-width="2.5"/>
  <!-- Prior low marker -->
  <circle cx="190" cy="190" r="6" fill="none" stroke="#4ade80" stroke-width="2"/>
  <text x="190" y="215" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">First low</text>
  <!-- Bounce high -->
  <circle cx="240" cy="110" r="6" fill="none" stroke="#f97316" stroke-width="2"/>
  <text x="240" y="95" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Bounce</text>
  <!-- Failed attempt -->
  <circle cx="290" cy="180" r="6" fill="none" stroke="#4ade80" stroke-width="2"/>
  <text x="290" y="215" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Higher low</text>
  <!-- Break above -->
  <line x1="240" y1="110" x2="340" y2="110" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="360" y="70" fill="#4ade80" font-size="11" font-family="Inter,sans-serif">Break ↑</text>
</svg>

<h3>Why failure swings matter</h3>
<p>Wilder considered failure swings one of the most reliable RSI signals, and his original 1978 book described them extensively. The pattern formalises momentum reversal in a way that's more structured than simple divergence.</p>
<p>The key difference from regular divergence:</p>
<ul>
    <li><strong>Divergence</strong> — compares price extremes to RSI extremes.</li>
    <li><strong>Failure swing</strong> — compares RSI extremes to each other, defining a formal structure in the RSI alone.</li>
</ul>
<p>Because failure swings are self-contained in RSI, they don't require price to be making new extremes. This makes them useful in a broader range of conditions.</p>

<h3>Trading failure swings</h3>
<ol>
    <li><strong>Wait for the failure swing to complete.</strong> The pattern isn't valid until RSI breaks the prior pullback level.</li>
    <li><strong>Confirm with price action.</strong> Look for a candle pattern or structure break in the same direction.</li>
    <li><strong>Enter on the price confirmation.</strong></li>
    <li><strong>Stop-loss</strong> beyond the recent swing high (bearish) or low (bullish).</li>
    <li><strong>Target</strong> the next significant level or use a trailing stop.</li>
</ol>

<h2>Factual context</h2>
<p>Wilder's original 1978 research emphasised failure swings as one of the primary signals for RSI. In <em>New Concepts in Technical Trading Systems</em>, he described failure swings in detail and argued they were more reliable than simple overbought/oversold readings.</p>
<p>Despite Wilder's emphasis, failure swings are less commonly taught than divergence. This is partly because they require more attention to identify and partly because divergence has become the "standard" RSI reversal signal. But professional RSI traders often prefer failure swings because they're more structured and produce clearer trading rules.</p>
<p>Cardwell's research extended Wilder's original failure swing concept by introducing the range-shift framework. His work showed that failure swings in a bull market tend to occur at different RSI levels than those in a bear market — reinforcing the idea that RSI behaviour depends on context.</p>
<p>Wilder himself summarised the philosophy:</p>
<blockquote><strong>"The failure swing is a much stronger indication of a reversal than an overbought/oversold reading. It requires the market to actually fail at a price level before confirming the reversal."</strong></blockquote>
<p>Wilder's point is important: failure swings require actual failure, not just a reading. This makes them more reliable than simple threshold signals.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Confusing failure swings with divergence.</strong> They're related but distinct. Learn both.</li>
    <li><strong>Entering before the failure swing completes.</strong> The break of the prior pullback level is the trigger. Wait for it.</li>
    <li><strong>Ignoring trend context.</strong> A bullish failure swing in a strong downtrend is low-probability. The best failure swings align with the higher-timeframe trend or occur at major levels.</li>
    <li><strong>Over-trading.</strong> True failure swings are relatively rare. If you see them constantly, you're misidentifying them.</li>
</ul>

<h2>Advanced notes</h2>
<p>Failure swings are especially useful when combined with horizontal levels or trendlines. A bullish failure swing that occurs exactly at a support level is far more meaningful than one in the middle of nowhere. Similarly, a bearish failure swing at major resistance is a higher-probability short setup. The combination of RSI structure and price structure is what makes failure swings a professional-level tool.</p>
HTML,
        ],

        [
            'slug'   => 'putting-rsi-together',
            'title'  => 'Putting RSI Together',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Combine RSI with trend, structure, and price action\n" .
                "• Build a repeatable RSI framework\n" .
                "• Apply RSI to real trading scenarios",
            'prerequisites' => 'RSI Failure Swings',
            'sort_order' => 6,
            'summary' => 'This final lesson brings together everything in the module: RSI basics, the 30/50/70 levels, the overbought/oversold misconception, divergence, and failure swings. The goal is a repeatable process for using RSI as one component of a layered analysis.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>RSI isn't a standalone signal. It's a tool that adds context to your other analysis. When combined with trend, structure, and price action, RSI becomes a powerful confirmation tool. Alone, it's noisy and prone to false signals.</p>

<h2>The complete framework</h2>

<h3>Step 1: Determine the market state</h3>
<p>On your higher timeframe, identify:</p>
<ul>
    <li><strong>Trend or range?</strong> Use structure (HH/HL vs LH/LL vs neither).</li>
    <li><strong>Trend direction</strong> — bullish, bearish, or ranging.</li>
    <li><strong>Trend strength</strong> — use ADX or visual assessment.</li>
</ul>
<p>This determines how you'll interpret RSI.</p>

<h3>Step 2: Interpret RSI according to market state</h3>
<ul>
    <li><strong>In a bull trend</strong> — RSI 40 acts as support, RSI 80 acts as resistance. Long setups from 40–60 are the highest probability.</li>
    <li><strong>In a bear trend</strong> — RSI 60 acts as resistance, RSI 20 acts as support. Short setups from 40–60 are the highest probability.</li>
    <li><strong>In a range</strong> — RSI 30/70 mark range boundaries. Trade reversals from these levels.</li>
</ul>

<h3>Step 3: Look for RSI extremes with context</h3>
<p>When RSI reaches an extreme (>70 or <30):</p>
<ul>
    <li>Check if it's at a key level (support/resistance).</li>
    <li>Check if the trend is exhausted (RSI divergence, failed structure).</li>
    <li>Check if there's a candle pattern (pin bar, engulfing).</li>
</ul>
<p>If all three align, RSI extremes become meaningful.</p>

<h3>Step 4: Watch for divergence or failure swings</h3>
<ul>
    <li><strong>Divergence</strong> — price makes new extreme, RSI doesn't.</li>
    <li><strong>Failure swing</strong> — RSI fails to reach a new extreme and then breaks structure.</li>
</ul>
<p>Both signal momentum reversal. Wait for price confirmation.</p>

<h3>Step 5: Use RSI 50 as a bias filter</h3>
<ul>
    <li><strong>RSI > 50</strong> — bullish momentum. Prefer longs.</li>
    <li><strong>RSI < 50</strong> — bearish momentum. Prefer shorts.</li>
    <li><strong>RSI near 50</strong> — neutral. Wait for a clear signal.</li>
</ul>

<h3>Step 6: Combine with price structure</h3>
<p>RSI signals should always be confirmed by price action:</p>
<ul>
    <li><strong>Bullish RSI signal + bullish price structure break</strong> = high-probability long.</li>
    <li><strong>Bearish RSI signal + bearish price structure break</strong> = high-probability short.</li>
    <li><strong>RSI signal alone</strong> = wait for confirmation.</li>
</ul>

<h2>Worked example — EUR/USD</h2>

<h3>Daily chart analysis</h3>
<ul>
    <li>Price is making HH/HL — clear uptrend.</li>
    <li>RSI is above 50 and oscillating between 40 and 75.</li>
    <li>Structure: bullish.</li>
    <li>Bias: <strong>long only</strong>.</li>
</ul>

<h3>H4 chart analysis</h3>
<ul>
    <li>Price has pulled back to a support level.</li>
    <li>RSI is at 42 — dipping into the bull-market support zone.</li>
    <li>A bullish engulfing candle forms at the support.</li>
    <li>Entry trigger: <strong>yes</strong>.</li>
</ul>

<h3>Trade plan</h3>
<ul>
    <li><strong>Entry:</strong> on the close of the engulfing candle.</li>
    <li><strong>Stop:</strong> below the support level and the engulfing candle's low.</li>
    <li><strong>Target:</strong> prior swing high.</li>
    <li><strong>R:R:</strong> at least 2:1.</li>
</ul>

<h3>Management</h3>
<ul>
    <li>Move stop to break-even after a bullish BOS on the H4.</li>
    <li>Watch RSI: if it hits 75+ while price makes a new high, be alert for divergence.</li>
    <li>If RSI makes a lower high while price makes a higher high, tighten stops — potential reversal forming.</li>
</ul>

<h2>Factual context</h2>
<p>This framework is essentially what professional RSI traders do — they use RSI as one component of a layered analysis, not as a standalone signal. Andrew Cardwell's original research on RSI ranges and professional traders' frameworks all point to the same conclusion: RSI is useful when it adds context to price action, not when it's traded in isolation.</p>
<p>Constance Brown, whose work extended Cardwell's research, has emphasised that "RSI is not a magic indicator — it's a tool. Traders who use it well combine it with price action and structure."</p>
<p>The most important RSI quote to remember comes from Wilder's original work:</p>
<blockquote><strong>"The Relative Strength Index is a momentum indicator. It does not predict the future — it measures the strength of recent price action relative to the past. Traders who understand this use it well; traders who don't treat it as a crystal ball."</strong></blockquote>
<p>Wilder himself would have been the first to say that RSI is a tool, not a system. Its value comes from how you apply it, not from what it does in isolation.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Using RSI in isolation.</strong> The most common mistake. RSI is a confirmation tool, not a signal generator.</li>
    <li><strong>Trading overbought/oversold blindly.</strong> Context is everything. RSI levels behave differently in trends vs ranges.</li>
    <li><strong>Forcing divergence.</strong> If you have to squint, it's not there.</li>
    <li><strong>Ignoring the higher timeframe.</strong> RSI on the M15 that contradicts the daily RSI is noise.</li>
    <li><strong>Over-relying on any single signal.</strong> Even the best RSI setup should be confirmed with price action and structure.</li>
</ul>

<h2>Advanced notes</h2>
<p>Once you're comfortable with the basics of RSI, the next step is combining it with other momentum tools — MACD in particular. The two indicators measure similar things but in different ways, and their signals often confirm each other. When RSI shows divergence AND MACD shows a bearish cross, the reversal signal is significantly stronger than either alone. We cover MACD in the next module, and its complementary role with RSI is a recurring theme throughout professional technical analysis.</p>
HTML,
        ],

    ],
];