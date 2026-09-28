<?php
/**
 * Module 05 — Reading Price Candles
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_05_price_candles.php
 */

return [
    'module' => [
        'level_slug' => 'foundation',
        'slug'       => 'price-candles',
        'title'      => 'Reading Price Candles',
        'description'=> 'Candlesticks are the alphabet of price. Every candle tells a small story about who won a battle in the market — buyers or sellers. Learn to read that story accurately, and patterns stop being magic and start being probabilities.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Read any candlestick and describe exactly what happened during its period\n" .
            "• Distinguish between momentum, rejection, and indecision candles\n" .
            "• Recognise the classical patterns (doji, pin bar, engulfing, inside/outside, morning/evening star)\n" .
            "• Understand why patterns fail and how context determines whether a signal means anything",
        'sort_order' => 5,
    ],

    'lessons' => [

        [
            'slug'   => 'anatomy-of-a-candlestick',
            'title'  => 'Anatomy of a Candlestick',
            'difficulty' => 'beginner',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Identify the four prices a candle represents\n" .
                "• Distinguish between the body and the wicks\n" .
                "• Explain what a candle tells you about the period it represents",
            'prerequisites' => 'Getting Started with Your Trading Platform',
            'sort_order' => 1,
            'summary' => 'A candlestick shows four prices for a fixed period of time: open, high, low, and close. The body represents the range between open and close. The wicks (also called shadows) represent the extremes that were reached but not held.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a football match. At the start, the score was 0–0. At the end, it was 3–1. But during the match, the score briefly touched 3–2, and at one point it was 0–1. If you only looked at the final score, you'd miss the drama. A candlestick shows you the whole story — start, best and worst moments, and finish — for any period of time.</p>

<h2>Real-world analogy</h2>
<p>Think of a candle as a diary entry for a specific time block. In one hour, the price opened at X, went as high as Y, as low as Z, and closed at W. That's the whole story. Candlesticks let you read thousands of these diary entries at a glance.</p>

<h2>Professional explanation</h2>
<p>A <strong>candlestick</strong> (also called a "candle") represents price action over a fixed time period — one minute, one hour, one day, and so on. It shows four data points:</p>
<ul>
    <li><strong>Open</strong> — the first price of the period</li>
    <li><strong>High</strong> — the highest price reached during the period</li>
    <li><strong>Low</strong> — the lowest price reached during the period</li>
    <li><strong>Close</strong> — the last price of the period</li>
</ul>
<p>These four prices are abbreviated as <strong>OHLC</strong>.</p>

<h3>The body</h3>
<p>The <strong>body</strong> is the thick rectangle in the middle of the candle. It spans from the open to the close. Its height tells you how much the price moved during the period, and its colour tells you the direction:</p>
<ul>
    <li><strong>Bullish candle</strong> — close is higher than open (usually green or white)</li>
    <li><strong>Bearish candle</strong> — close is lower than open (usually red or black)</li>
</ul>

<h3>The wicks (shadows)</h3>
<p>The thin lines above and below the body are called <strong>wicks</strong> or <strong>shadows</strong>.</p>
<ul>
    <li>The <strong>upper wick</strong> extends from the top of the body to the period's high.</li>
    <li>The <strong>lower wick</strong> extends from the bottom of the body to the period's low.</li>
</ul>
<p>Long wicks mean price reached a level and then reversed — a sign of rejection. Short wicks mean price stayed close to where it opened or closed.</p>

<h3>An SVG example</h3>
<svg viewBox="0 0 200 320" width="200" height="320" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <line x1="100" y1="20" x2="100" y2="60" stroke="#4ade80" stroke-width="2"/>
  <rect x="80" y="60" width="40" height="200" fill="none" stroke="#4ade80" stroke-width="2"/>
  <line x1="100" y1="260" x2="100" y2="300" stroke="#4ade80" stroke-width="2"/>
  <text x="120" y="30" fill="#e6e9ef" font-size="13" font-family="Inter,sans-serif">High</text>
  <text x="120" y="80" fill="#e6e9ef" font-size="13" font-family="Inter,sans-serif">Open</text>
  <text x="120" y="250" fill="#e6e9ef" font-size="13" font-family="Inter,sans-serif">Close</text>
  <text x="120" y="295" fill="#e6e9ef" font-size="13" font-family="Inter,sans-serif">Low</text>
  <text x="10" y="170" fill="#93a5ff" font-size="12" font-family="Inter,sans-serif" transform="rotate(-90 30 170)">Body</text>
</svg>
<p style="text-align:center;color:#8b93a7;font-size:13px;margin-top:12px;">A bullish candle: green body, upper wick to the high, lower wick to the low.</p>

<h3>What a candle actually tells you</h3>
<p>Combined, the four prices tell a story about <strong>who won the battle</strong> during that period:</p>
<ul>
    <li>A long green body means buyers dominated from open to close.</li>
    <li>A long red body means sellers dominated.</li>
    <li>A small body means neither side won decisively.</li>
    <li>A long upper wick means buyers pushed price up but sellers fought back.</li>
    <li>A long lower wick means sellers pushed price down but buyers fought back.</li>
</ul>

<h2>Factual context</h2>
<p>Candlestick charting was developed in Japan by <strong>Munehisa Homma (1724–1803)</strong>, a rice trader from Sakata. Homma discovered that while supply and demand affected rice prices, market psychology played an equally important role — and that prices often moved in patterns driven by that psychology. His techniques were used for over a century before being compiled into the candlestick charts we know today. The method was introduced to Western traders by <strong>Steve Nison</strong> in his 1991 book <em>Japanese Candlestick Charting Techniques</em>.</p>
<p>Jesse Livermore said in <em>Reminiscences of a Stock Operator</em>:</p>
<blockquote><strong>"There is nothing new in Wall Street. There can't be because speculation is as old as the hills."</strong></blockquote>
<p>Candlesticks are proof of this — 250 years old, and still the standard way professional traders read price.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Confusing the body with the whole candle.</strong> The body is only open-to-close. The full candle spans high to low.</li>
    <li><strong>Assuming green always means "up."</strong> Green means the close was higher than the <em>open of that candle</em>, not that the period was up on the whole.</li>
    <li><strong>Ignoring the timeframe.</strong> A green daily candle might contain hours of red within it. The candle's "direction" depends entirely on the timeframe you're looking at.</li>
</ul>

<h2>Advanced notes</h2>
<p>The first candle of a session has no "previous close." Brokers solve this by treating the previous candle's close as the session open. This is why gaps occur on daily charts — the daily candle opens where the previous day's close finished, but a new session can open at a different price if news hit between sessions. On 24-hour Forex pairs, gaps are rare (the market is essentially continuous), but on stocks, gaps are common and often meaningful.</p>
HTML,
        ],

        [
            'slug'   => 'bullish-and-bearish-candles',
            'title'  => 'Bullish and Bearish Candles',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Distinguish between bullish and bearish candles\n" .
                "• Explain what the colour and body represent\n" .
                "• Read the story of a candle without knowing the raw OHLC numbers",
            'prerequisites' => 'Anatomy of a Candlestick',
            'sort_order' => 2,
            'summary' => 'A bullish candle closes higher than it opens. A bearish candle closes lower than it opens. But the size of the body — and the length of the wicks — tells you how decisive that direction actually was.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Every candle is either green or red. Green means price finished higher than it started. Red means it finished lower. Simple. But the size matters more than the colour.</p>
<ul>
    <li>Big green candle — buyers were in complete control.</li>
    <li>Small green candle — buyers were slightly ahead at the close, but the period was choppy.</li>
    <li>Big red candle — sellers dominated.</li>
    <li>Small red candle — sellers barely won.</li>
</ul>

<h2>Real-world analogy</h2>
<p>Think of the final score of a football match. A 5–0 win is dominance. A 2–1 win is close. Both are wins, but they tell very different stories. Candle sizes do the same thing.</p>

<h2>Professional explanation</h2>

<h3>Bullish candle</h3>
<p>Close > Open. The candle is drawn with the body from open (bottom) to close (top). Typically green or white. The larger the body relative to the wicks, the more decisively buyers were in control.</p>

<h3>Bearish candle</h3>
<p>Close < Open. The body is drawn from close (bottom) to open (top). Typically red or black. The larger the body, the more decisively sellers controlled the period.</p>

<h3>Reading the body-to-wick ratio</h3>
<p>The relationship between the body and the wicks tells you the <strong>quality</strong> of the move:</p>
<table>
    <thead><tr><th>Shape</th><th>Meaning</th></tr></thead>
    <tbody>
        <tr><td>Large body, tiny wicks</td><td>One-sided dominance. Buyers (green) or sellers (red) controlled nearly the whole period.</td></tr>
        <tr><td>Small body, long upper wick</td><td>Buyers pushed up but were rejected. Sellers regained control.</td></tr>
        <tr><td>Small body, long lower wick</td><td>Sellers pushed down but were rejected. Buyers regained control.</td></tr>
        <tr><td>Small body, balanced wicks</td><td>Indecision. Neither side won.</td></tr>
    </tbody>
</table>

<h3>Visual example</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Bullish strong -->
  <line x1="50" y1="40" x2="50" y2="260" stroke="#4ade80" stroke-width="2"/>
  <rect x="35" y="60" width="30" height="180" fill="#4ade80"/>
  <text x="50" y="285" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Strong bull</text>

  <!-- Bearish strong -->
  <line x1="140" y1="40" x2="140" y2="260" stroke="#ef4444" stroke-width="2"/>
  <rect x="125" y="60" width="30" height="180" fill="#ef4444"/>
  <text x="140" y="285" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Strong bear</text>

  <!-- Rejection up -->
  <line x1="230" y1="40" x2="230" y2="260" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="215" y="180" width="30" height="40" fill="#4ade80"/>
  <text x="230" y="285" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Rejected up</text>

  <!-- Rejection down -->
  <line x1="320" y1="40" x2="320" y2="260" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="305" y="80" width="30" height="40" fill="#ef4444"/>
  <text x="320" y="285" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Rejected down</text>

  <!-- Indecision -->
  <line x1="410" y1="40" x2="410" y2="260" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="395" y="145" width="30" height="10" fill="#e6e9ef"/>
  <text x="410" y="285" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Indecision</text>
</svg>

<h2>Factual context</h2>
<p>Steve Nison, the trader who brought candlesticks to the West, wrote that the candlestick "provides a visual depiction of the battle between bulls and bears." This is exactly right. Every candle is a snapshot of which side had more conviction during that period — and the size of the body measures that conviction.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Judging candles by colour alone.</strong> A green candle with a huge upper wick is bearish news, not bullish. The colour only reflects open vs close, not the whole story.</li>
    <li><strong>Ignoring body size.</strong> Small candles mean indecision. Trading a small, choppy candle is usually lower probability than trading a decisive one.</li>
    <li><strong>Reading candles in isolation.</strong> A single candle means little. A candle in context — where it sits in a trend or at a level — means a lot.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some traders use candle-body percentages: measure the body as a fraction of the total candle range. A candle whose body is 80% of its range is a very strong signal — buyers or sellers dominated. A candle whose body is 20% of its range is almost purely wicks — indecision. This simple calculation lets you filter out noisy candles quickly when scanning a chart.</p>
HTML,
        ],

        [
            'slug'   => 'body-wicks-and-range',
            'title'  => 'Body, Wicks, and Range',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define candle range, body, and wick lengths\n" .
                "• Interpret long wicks as evidence of rejection\n" .
                "• Explain what candle range tells you about volatility",
            'prerequisites' => 'Bullish and Bearish Candles',
            'sort_order' => 3,
            'summary' => 'The distance from high to low is the candle range. The body shows conviction; the wicks show rejection. Reading the shape, not just the colour, is what separates skilled candle readers from beginners.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Every candle has three measurable parts: the body (open to close), the upper wick (body top to high), and the lower wick (body bottom to low). The way these three parts interact tells you what really happened during the period — not just where it ended.</p>

<h2>Real-world analogy</h2>
<p>Imagine someone bidding on a house. They start at $500K, the bidding goes up to $620K, then comes back to close at $540K. The final price ($540K) is $40K above the opening bid — a "green" outcome. But the fact that it briefly touched $620K and got rejected tells you a lot. That's a long upper wick. Bidders tried to push higher, and the market refused.</p>

<h2>Professional explanation</h2>

<h3>Candle range</h3>
<p>The <strong>range</strong> is the total distance from high to low. It measures volatility over the period.</p>
<ul>
    <li>Wide range — high volatility, strong movement, likely news-driven.</li>
    <li>Narrow range — low volatility, consolidation, indecision.</li>
</ul>

<h3>Body</h3>
<p>The distance from open to close. Represents the period's directional conviction.</p>
<ul>
    <li>Large body — decisive direction.</li>
    <li>Small body — indecision.</li>
</ul>

<h3>Wicks (shadows)</h3>
<p>Wicks represent the extremes that were reached but <em>not held</em>.</p>
<ul>
    <li><strong>Long upper wick</strong> — buyers pushed price up, but sellers drove it back down. Rejection of higher prices.</li>
    <li><strong>Long lower wick</strong> — sellers pushed price down, but buyers drove it back up. Rejection of lower prices.</li>
    <li><strong>Long wicks on both sides</strong> — both sides tried and failed. Extreme indecision or a "battle" with no winner.</li>
</ul>

<h3>The interplay — three examples</h3>

<h4>1. Big green body, small wicks</h4>
<p>Buyers dominated from start to finish. Nothing stopped them. This is a strong continuation signal — especially in an uptrend.</p>

<h4>2. Small green body, long upper wick</h4>
<p>Buyers opened strong and pushed up, but sellers overpowered them by the close. The candle is technically bullish (closed above open), but the wick tells a bearish story. Watch for a reversal.</p>

<h4>3. Small red body, long lower wick</h4>
<p>Sellers drove price down, but buyers stepped in and pushed it back up. The candle is technically bearish, but the lower wick suggests buyers are defending that level. Watch for a bounce.</p>

<h2>Factual context</h2>
<p>Steve Nison emphasised that "candlestick patterns should be considered in the context of the overall chart." A candle with a long lower wick in the middle of a choppy range means nothing. The same candle at the bottom of a downtrend, at a major support level, might mark the exact reversal point. Context is everything.</p>
<p>Paul Tudor Jones once said:</p>
<blockquote><strong>"I believe the very best money is made at the market turns. Everyone says you get killed trying to pick tops and bottoms and you make all your money by playing the trend in the middle. For me, being a defensive player, I'd rather be in the turns."</strong></blockquote>
<p>Rejection candles — candles with long wicks — are how turns show up on a chart. Learning to read them is learning to read reversals.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Reading the colour, not the shape.</strong> A green candle with a huge upper wick is not bullish. Look at the wick ratio, not just open vs close.</li>
    <li><strong>Ignoring the range.</strong> A 5-pip candle on EUR/USD means nothing. A 50-pip candle on the same pair is a significant event. Scale matters.</li>
    <li><strong>Treating every wick as a signal.</strong> Wicks in the middle of nowhere are noise. Wicks at key levels are information.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often compare the current candle's range to the average range of the previous 14 or 20 candles. This gives an <strong>average true range (ATR)</strong> baseline. A candle twice the size of the average ATR is an outlier — either a strong signal or a news spike. A candle half the size of ATR is compressed — often the precursor to a breakout. We cover ATR in the indicators module later, but the concept applies to candle reading directly.</p>
HTML,
        ],

        [
            'slug'   => 'doji-and-indecision',
            'title'  => 'Doji and Indecision Candles',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Identify a doji and its variants\n" .
                "• Explain what indecision means in market context\n" .
                "• Know when a doji is meaningful and when it is noise",
            'prerequisites' => 'Body, Wicks, and Range',
            'sort_order' => 4,
            'summary' => 'A doji is a candle where open and close are (nearly) equal, producing a tiny body and visible wicks on one or both sides. It represents a market in balance — buyers and sellers equally matched. On its own it means nothing; in context, it can mark a turning point.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two people arm-wrestling. Neither wins. They hold the same position for the whole match. That's a <strong>doji</strong> — a candle where buyers and sellers end up exactly where they started, even if the price wiggled around in the middle.</p>

<h2>Real-world analogy</h2>
<p>Think of a tennis match that ends 6–6, 6–6, 6–6. Nobody wins. That's a doji. It doesn't tell you who's better — but it does tell you they're evenly matched right now. In trading, that often precedes a decisive move in one direction.</p>

<h2>Professional explanation</h2>
<p>A <strong>doji</strong> is a candle where the open and close are essentially equal (within a pip or two), leaving a very small or nonexistent body. The wicks can vary:</p>

<ul>
    <li><strong>Standard doji</strong> — small body, wicks of roughly equal length on both sides. Pure indecision.</li>
    <li><strong>Long-legged doji</strong> — small body, very long wicks both sides. Extreme volatility with no net movement. Often marks a turning point.</li>
    <li><strong>Gravestone doji</strong> — open, close, and low are near-identical, with a long upper wick. Buyers were decisively rejected.</li>
    <li><strong>Dragonfly doji</strong> — open, close, and high are near-identical, with a long lower wick. Sellers were decisively rejected.</li>
</ul>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Standard doji -->
  <line x1="60" y1="30" x2="60" y2="200" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="45" y1="115" x2="75" y2="115" stroke="#e6e9ef" stroke-width="3"/>
  <text x="60" y="235" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Standard</text>

  <!-- Long-legged -->
  <line x1="170" y1="20" x2="170" y2="210" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="155" y1="115" x2="185" y2="115" stroke="#e6e9ef" stroke-width="3"/>
  <text x="170" y="235" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Long-legged</text>

  <!-- Gravestone -->
  <line x1="280" y1="20" x2="280" y2="180" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="265" y1="180" x2="295" y2="180" stroke="#e6e9ef" stroke-width="3"/>
  <text x="280" y="235" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Gravestone</text>

  <!-- Dragonfly -->
  <line x1="390" y1="20" x2="390" y2="180" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="375" y1="20" x2="405" y2="20" stroke="#e6e9ef" stroke-width="3"/>
  <text x="390" y="235" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Dragonfly</text>
</svg>

<h3>What a doji means</h3>
<p>A doji is a snapshot of equilibrium. Neither side won the period. That matters most in two situations:</p>
<ol>
    <li><strong>After a strong trend</strong> — a doji can signal that the trend is losing momentum and might reverse.</li>
    <li><strong>At a key support or resistance level</strong> — a doji at a well-defined level suggests buyers or sellers are defending that level, but not yet overcoming it.</li>
</ol>
<p>In the middle of a choppy range, a doji means almost nothing. Context decides.</p>

<h2>Factual context</h2>
<p>Steve Nison's original research noted that the doji "is one of the most important candlestick signals" — but he was careful to add that its meaning depends entirely on where it appears. The Japanese term <em>doji</em> literally means "same time" or "same thing," referring to the fact that open and close are identical. It was used for centuries by Japanese rice traders as a signal that a market was indecisive and might be about to reverse or break out.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading a doji in isolation.</strong> A doji alone is not a signal. It needs confirmation from the next candle.</li>
    <li><strong>Confusing doji with spinning top.</strong> A spinning top has a small body but a clear direction. A doji has essentially no body.</li>
    <li><strong>Ignoring the wick shape.</strong> A gravestone doji at resistance is far more meaningful than a standard doji in the middle of nowhere.</li>
</ul>

<h2>Advanced notes</h2>
<p>Combining a doji with volume gives you a stronger read. A doji on very low volume simply means nobody was trading — that's not indecision, it's absence. A doji on very high volume means buyers and sellers both showed up with conviction and ended the period in a stalemate. High-volume doji at a key level often precede the biggest moves. Volume data is limited in spot Forex (since the market is decentralised), but platforms often approximate it using tick volume — the number of price updates per period. It's not perfect, but it's useful.</p>
HTML,
        ],

        [
            'slug'   => 'pin-bars-hammers-and-shooting-stars',
            'title'  => 'Pin Bars, Hammers, and Shooting Stars',
            'difficulty' => 'beginner',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Identify hammer and shooting star candles\n" .
                "• Explain what rejection means in practice\n" .
                "• Understand why pin bars at key levels are meaningful",
            'prerequisites' => 'Doji and Indecision Candles',
            'sort_order' => 5,
            'summary' => 'A hammer has a long lower wick and a small body at the top — sellers pushed down but buyers reversed it. A shooting star has a long upper wick and a small body at the bottom — buyers pushed up but sellers reversed it. Together they are called "pin bars." At key levels, they are among the most powerful single-candle signals.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Sometimes a candle looks like a pin stuck into the chart — a small body with a long wick. That long wick is the market telling you "we tried to go that way and we couldn't hold it." At the right place on a chart, that rejection is a strong signal.</p>

<h2>Real-world analogy</h2>
<p>Imagine a shop trying to raise its prices. It raises them, customers stop buying, and it has to put prices back where they were. That failed attempt is exactly what a pin bar shows — the market tried a direction, found no support, and reversed.</p>

<h2>Professional explanation</h2>

<h3>Hammer (bullish pin bar)</h3>
<p>A hammer forms when price opens, sells off sharply, then rallies back to close near the top of the range. The result is a small body with a long lower wick — usually at least 2× the height of the body.</p>
<ul>
    <li>Open and close are near the top of the candle.</li>
    <li>Long lower wick represents the sellers' failed push.</li>
    <li>Interpretation: buyers defended the low and pushed price back up.</li>
</ul>
<p>A hammer is most meaningful at the <strong>bottom of a downtrend</strong> or at a strong support level.</p>

<h3>Shooting star (bearish pin bar)</h3>
<p>A shooting star is the mirror image. Price opens, rallies sharply, then falls back to close near the low of the range. Small body at the bottom, long upper wick.</p>
<ul>
    <li>Interpretation: buyers pushed price up, sellers overwhelmed them.</li>
    <li>Most meaningful at the <strong>top of an uptrend</strong> or at a strong resistance level.</li>
</ul>

<h3>Inverted hammer and hanging man</h3>
<p>Two related variants:</p>
<ul>
    <li><strong>Inverted hammer</strong> — small body at the bottom, long upper wick, appearing at the bottom of a downtrend. A potential bullish reversal, though weaker than a hammer.</li>
    <li><strong>Hanging man</strong> — small body at the top, long lower wick, appearing at the top of an uptrend. A potential bearish reversal, though weaker than a shooting star.</li>
</ul>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 280" width="500" height="280" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Hammer -->
  <line x1="100" y1="60" x2="100" y2="220" stroke="#4ade80" stroke-width="2"/>
  <rect x="85" y="60" width="30" height="40" fill="#4ade80"/>
  <text x="100" y="255" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Hammer</text>

  <!-- Shooting Star -->
  <line x1="250" y1="60" x2="250" y2="220" stroke="#ef4444" stroke-width="2"/>
  <rect x="235" y="180" width="30" height="40" fill="#ef4444"/>
  <text x="250" y="255" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Shooting Star</text>

  <!-- Inverted Hammer -->
  <line x1="400" y1="60" x2="400" y2="220" stroke="#4ade80" stroke-width="2"/>
  <rect x="385" y="180" width="30" height="40" fill="#4ade80"/>
  <text x="400" y="255" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Inverted Hammer</text>
</svg>

<h3>What makes a pin bar powerful</h3>
<p>Not every long-wick candle is a signal. The strongest pin bars have:</p>
<ol>
    <li><strong>Location</strong> — they form at a key support or resistance level, not randomly.</li>
    <li><strong>Wick-to-body ratio</strong> — at least 2:1, ideally 3:1 or more.</li>
    <li><strong>Confirming wick direction</strong> — the wick points the "wrong" way (down for a hammer, up for a shooting star).</li>
    <li><strong>Size relative to nearby candles</strong> — larger than the recent average.</li>
    <li><strong>Session/timing context</strong> — a pin bar forming during the London or New York session carries more weight than one during the Asian session.</li>
</ol>

<h2>Factual context</h2>
<p>The pin bar is one of the oldest candle patterns in Japanese candlestick methodology, where the hammer is known as <em>takuri</em> (a deep pull) and the shooting star as <em>nagare boshi</em> (a shooting star). Modern price action traders — including Al Brooks and Lance Beggs — use pin bars as foundational signals because they cleanly show a failed directional attempt.</p>
<p>Steve Nison noted that "the longer the shadow, the greater the importance of the candle." A pin bar with a 5-pip wick on EUR/USD is noise. The same candle with a 50-pip wick is a major event.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading every pin bar.</strong> In isolation, a pin bar is a coin flip. Trade them only at key levels with a directional bias.</li>
    <li><strong>Ignoring the wick-to-body ratio.</strong> A "pin bar" with a 1:1 wick-to-body ratio isn't a pin bar — it's a spinning top.</li>
    <li><strong>Assuming a hammer is guaranteed bullish.</strong> A hammer means sellers failed <em>for that one period</em>. The next candle must confirm. Without confirmation, hammers fail regularly.</li>
    <li><strong>Entering on the hammer's close without a stop.</strong> Some pin bar strategies enter on the next candle, others on a breakout above the hammer high. Each has different stop placements.</li>
</ul>

<h2>Advanced notes</h2>
<p>Pin bars become significantly more powerful when combined with:</p>
<ul>
    <li><strong>A prior liquidity sweep</strong> — price dipped below a prior low (taking out stops) and reversed.</li>
    <li><strong>Divergence on RSI or MACD</strong> — momentum showing weakness while price makes a new extreme.</li>
    <li><strong>Multi-timeframe confluence</strong> — a pin bar on the 4H forming at a level visible on the daily chart.</li>
</ul>
<p>In advanced modules we'll cover how pin bars integrate with liquidity concepts and multi-timeframe analysis. For now, master the single-candle reading — you'll build on it later.</p>
HTML,
        ],

        [
            'slug'   => 'engulfing-patterns',
            'title'  => 'Engulfing Patterns',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Identify bullish and bearish engulfing patterns\n" .
                "• Explain why engulfing signals conviction\n" .
                "• Know where engulfing patterns are most reliable",
            'prerequisites' => 'Pin Bars, Hammers, and Shooting Stars',
            'sort_order' => 6,
            'summary' => 'A bullish engulfing is a green candle that completely covers the previous red candle. A bearish engulfing is a red candle that covers the previous green candle. Both show a clear shift in control from one side to the other, and they are strongest at key levels.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two boxers trading punches. Round one goes to the red corner. Round two, the green corner not only wins, but dominates so completely that the second round's range swallows the first round's range entirely. That's an <strong>engulfing pattern</strong> — one side took complete control.</p>

<h2>Real-world analogy</h2>
<p>Think of a tug-of-war. If one team pulls the rope two feet and holds, that's a win. If the other team pulls it back four feet on the next pull, that's not a win — that's domination. Engulfing patterns show domination.</p>

<h2>Professional explanation</h2>

<h3>Bullish engulfing</h3>
<p>Two-candle pattern at the bottom of a downtrend or at support:</p>
<ol>
    <li>First candle is bearish (red).</li>
    <li>Second candle is bullish (green) and its body completely covers the first candle's body — opens below the first candle's close and closes above its open.</li>
</ol>
<p>Interpretation: sellers pushed price down, then buyers took over with such force that the second candle's range swallowed the first. Buyers are now in control.</p>

<h3>Bearish engulfing</h3>
<p>Mirror image at the top of an uptrend or at resistance:</p>
<ol>
    <li>First candle is bullish (green).</li>
    <li>Second candle is bearish (red) and its body completely covers the first candle's body.</li>
</ol>
<p>Interpretation: buyers pushed up, then sellers overwhelmed them. Sellers are now in control.</p>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Bullish engulfing -->
  <line x1="100" y1="60" x2="100" y2="220" stroke="#ef4444" stroke-width="2"/>
  <rect x="85" y="110" width="30" height="60" fill="#ef4444"/>
  <line x1="150" y1="50" x2="150" y2="240" stroke="#4ade80" stroke-width="2"/>
  <rect x="135" y="80" width="30" height="140" fill="#4ade80"/>
  <text x="125" y="270" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Bullish engulfing</text>

  <!-- Bearish engulfing -->
  <line x1="340" y1="60" x2="340" y2="220" stroke="#4ade80" stroke-width="2"/>
  <rect x="325" y="110" width="30" height="60" fill="#4ade80"/>
  <line x1="390" y1="50" x2="390" y2="240" stroke="#ef4444" stroke-width="2"/>
  <rect x="375" y="80" width="30" height="140" fill="#ef4444"/>
  <text x="365" y="270" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Bearish engulfing</text>
</svg>

<h3>What makes engulfing patterns meaningful</h3>
<ol>
    <li><strong>Location</strong> — at a key level, after an extended move, or at the end of a trend.</li>
    <li><strong>Size of the engulfing candle</strong> — the bigger the body, the more decisive the shift.</li>
    <li><strong>Full body engulfing</strong> — the second candle should engulf the <em>body</em> of the first, not just the wick.</li>
    <li><strong>Volume</strong> — heavier volume on the engulfing candle confirms conviction.</li>
    <li><strong>Context of the prior move</strong> — after a long trend, engulfing signals a possible reversal. In a range, they may just mean a return to the range.</li>
</ol>

<h2>Factual context</h2>
<p>Engulfing patterns are among the oldest documented candlestick patterns in Japanese methodology, appearing in Munehisa Homma's 18th-century writings on rice trading. Steve Nison's 1991 book reintroduced them to Western traders. His research found them most reliable when combined with trend context — an engulfing pattern in the direction of the larger trend has a higher success rate than one against it.</p>
<p>Al Brooks, a prominent price action author, notes that "the context around the pattern matters far more than the pattern itself." A bullish engulfing at the bottom of a strong uptrend is a pullback entry. The same pattern at the top of a downtrend is often a trap.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Only engulfing the wick.</strong> A candle that covers the first candle's wick but not its body is not a proper engulfing pattern.</li>
    <li><strong>Trading engulfing in the middle of a range.</strong> In a choppy range, engulfing patterns fire constantly and produce nothing but whipsaws.</li>
    <li><strong>Ignoring the volume.</strong> An engulfing candle on low volume is a much weaker signal than one on high volume.</li>
    <li><strong>Entering on the second candle's close without waiting for confirmation.</strong> Many traders wait for the <em>next</em> candle to hold above/below the engulfing candle's extreme before entering.</li>
</ul>

<h2>Advanced notes</h2>
<p>Engulfing patterns are often more meaningful when they occur at the precise level where the market has previously reversed. A bullish engulfing at a support level that has been tested three times is far stronger than one in the middle of nowhere. In advanced modules on supply and demand and market structure, you'll see how engulfing patterns often mark the transition between price imbalances.</p>
<p>Also note: engulfing patterns can appear at <em>any</em> timeframe. A daily engulfing pattern is a major event; a 5-minute engulfing is often just noise. The timeframe determines the significance.</p>
HTML,
        ],

        [
            'slug'   => 'inside-and-outside-bars',
            'title'  => 'Inside and Outside Bars',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Identify inside and outside bars\n" .
                "• Explain what each signals about volatility and direction\n" .
                "• Use them as break-out anticipation tools",
            'prerequisites' => 'Engulfing Patterns',
            'sort_order' => 7,
            'summary' => 'An inside bar is completely contained within the previous candle\'s range — a pause, a compression, often a signal that a breakout is coming. An outside bar is the opposite: it engulfs the previous candle entirely — a sudden expansion, often a signal of a shift in control.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a ball bouncing. Sometimes it bounces lower and lower, getting quieter and quieter — that's an <strong>inside bar</strong>. And sometimes it suddenly jumps higher than any previous bounce — that's an <strong>outside bar</strong>. Both tell you something about what's coming next.</p>

<h2>Real-world analogy</h2>
<p>Think of a business quarter. If Q2's results are entirely within Q1's range (neither a record high nor a record low), the market pauses and waits. If Q3 blows past Q2 in both directions — new highs and new lows — something dramatic happened. Inside bars are pauses; outside bars are events.</p>

<h2>Professional explanation</h2>

<h3>Inside bar</h3>
<p>An inside bar's high is below (or equal to) the previous candle's high, and its low is above (or equal to) the previous candle's low. In other words, the entire candle sits inside the previous candle's range.</p>
<ul>
    <li><strong>Meaning:</strong> A contraction. The market paused after a move.</li>
    <li><strong>Signal:</strong> Often a precursor to a breakout — either direction.</li>
    <li><strong>Direction bias:</strong> Given by the candle that came <em>before</em> the inside bar (the "mother bar").</li>
</ul>
<p>Trading inside bars: a common approach is to wait for a break of the mother bar's high or low in the direction of the preceding trend.</p>

<h3>Outside bar</h3>
<p>An outside bar's high is above the previous candle's high, and its low is below the previous candle's low. It completely engulfs the previous candle.</p>
<ul>
    <li><strong>Meaning:</strong> A sudden expansion. Both sides were active and one decisively won.</li>
    <li><strong>Signal:</strong> Often marks a shift in control or the start of a strong move.</li>
    <li><strong>Direction:</strong> Determined by where the close ends. Close near the high = bullish outside bar. Close near the low = bearish outside bar.</li>
</ul>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Inside bar -->
  <line x1="100" y1="40" x2="100" y2="200" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="85" y="80" width="30" height="80" fill="#4ade80"/>
  <line x1="150" y1="90" x2="150" y2="160" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="135" y="110" width="30" height="30" fill="#ef4444"/>
  <text x="125" y="235" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Inside bar</text>

  <!-- Outside bar -->
  <line x1="340" y1="110" x2="340" y2="160" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="325" y="120" width="30" height="30" fill="#4ade80"/>
  <line x1="390" y1="40" x2="390" y2="220" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="375" y="60" width="30" height="140" fill="#4ade80"/>
  <text x="365" y="250" fill="#e6e9ef" font-size="12" font-family="Inte,sans-serif" text-anchor="middle">Outside bar</text>
</svg>

<h3>How traders use these patterns</h3>
<p><strong>Inside bar breakouts:</strong></p>
<ul>
    <li>Identify the mother bar (the candle before the inside bar).</li>
    <li>Set a buy stop above the mother bar's high and a sell stop below its low.</li>
    <li>Whichever direction price breaks is your entry.</li>
</ul>
<p>This works because inside bars represent compressed volatility — and compressed volatility often expands into a directional move.</p>

<p><strong>Outside bar shifts:</strong></p>
<ul>
    <li>Look for an outside bar at a key level (resistance, support, or a recent swing high/low).</li>
    <li>Check where the close finished — near the high (bullish) or low (bearish).</li>
    <li>Enter on the close of the outside bar or on a slight pullback.</li>
</ul>

<h2>Factual context</h2>
<p>Volatility clustering — the tendency for high-volatility periods to follow high-volatility periods and low-volatility periods to follow low-volatility periods — is one of the most documented phenomena in financial markets. Robert Engle won the 2003 Nobel Prize in Economics for his work on this (ARCH models). Inside bars are a visual representation of volatility compression — the market catching its breath before the next move.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading every inside bar as a breakout.</strong> Most inside bars produce small breakouts that fail. Only trade them in the direction of the larger trend or at key levels.</li>
    <li><strong>Ignoring the mother bar's size.</strong> An inside bar inside a small mother bar has no room to breathe. The bigger the mother bar, the more meaningful the subsequent break.</li>
    <li><strong>Treating outside bars as guaranteed reversals.</strong> An outside bar is a shift in activity, not a shift in trend. Wait for follow-through.</li>
</ul>

<h2>Advanced notes</h2>
<p>Institutional traders often look at <strong>inside-week and inside-day patterns</strong> as well. A weekly inside bar means the entire week's range sat inside the previous week — an extreme compression that often precedes a major multi-week move. Similarly, a daily inside bar is a common pattern before a trend day. The larger the timeframe, the more significant the compression or expansion.</p>
HTML,
        ],

        [
            'slug'   => 'morning-and-evening-stars',
            'title'  => 'Morning Star and Evening Star',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Identify morning star and evening star patterns\n" .
                "• Explain why three-candle patterns carry more weight than single candles\n" .
                "• Recognise when these patterns are most reliable",
            'prerequisites' => 'Inside and Outside Bars',
            'sort_order' => 8,
            'summary' => 'A morning star is a three-candle bullish reversal pattern: a down candle, a small indecision candle, then a strong up candle. An evening star is the bearish opposite. The three-candle structure signals a genuine shift in momentum, not just a pause.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>A single candle tells you about one period. Two candles tell you about a shift. Three candles — especially in a specific order — tell you a story with a beginning, a middle, and an end. Morning and evening stars are the classic three-candle reversal patterns.</p>

<h2>Real-world analogy</h2>
<p>Think of a car crash in slow motion. It doesn't happen in one instant — there's the moment of impact, a brief moment where everything freezes, then the aftermath. Reversal patterns work the same way: the trend pushes hard, hesitates, then reverses.</p>

<h2>Professional explanation</h2>

<h3>Morning Star (bullish reversal)</h3>
<p>Three candles, at the bottom of a downtrend:</p>
<ol>
    <li><strong>Candle 1:</strong> Long bearish candle. Sellers still in control.</li>
    <li><strong>Candle 2:</strong> Small-bodied candle (doji or spinning top), often with a gap down from the first candle. Represents the moment of hesitation.</li>
    <li><strong>Candle 3:</strong> Strong bullish candle closing well into (or above) the body of Candle 1. Buyers now in control.</li>
</ol>
<p>Interpretation: sellers exhausted themselves in Candle 1. Candle 2 showed they had no follow-through. Candle 3 confirmed the reversal.</p>

<h3>Evening Star (bearish reversal)</h3>
<p>Mirror image at the top of an uptrend:</p>
<ol>
    <li><strong>Candle 1:</strong> Long bullish candle.</li>
    <li><strong>Candle 2:</strong> Small-bodied candle, often gapping up.</li>
    <li><strong>Candle 3:</strong> Strong bearish candle closing well into (or below) the body of Candle 1.</li>
</ol>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Morning star -->
  <line x1="80" y1="40" x2="80" y2="180" stroke="#ef4444" stroke-width="2"/>
  <rect x="65" y="50" width="30" height="120" fill="#ef4444"/>
  <line x1="140" y1="170" x2="140" y2="200" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="125" y="180" width="30" height="10" fill="#e6e9ef"/>
  <line x1="200" y1="70" x2="200" y2="200" stroke="#4ade80" stroke-width="2"/>
  <rect x="185" y="80" width="30" height="100" fill="#4ade80"/>
  <text x="140" y="240" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Morning star</text>

  <!-- Evening star -->
  <line x1="320" y1="70" x2="320" y2="200" stroke="#4ade80" stroke-width="2"/>
  <rect x="305" y="80" width="30" height="100" fill="#4ade80"/>
  <line x1="380" y1="60" x2="380" y2="90" stroke="#e6e9ef" stroke-width="2"/>
  <rect x="365" y="75" width="30" height="10" fill="#e6e9ef"/>
  <line x1="440" y1="50" x2="440" y2="200" stroke="#ef4444" stroke-width="2"/>
  <rect x="425" y="60" width="30" height="120" fill="#ef4444"/>
  <text x="380" y="240" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Evening star</text>
</svg>

<h3>What makes a strong star pattern</h3>
<ul>
    <li><strong>Clear prior trend</strong> — the pattern needs a trend to reverse.</li>
    <li><strong>Long candles 1 and 3</strong> — the more decisive, the stronger the signal.</li>
    <li><strong>Small Candle 2</strong> — the smaller the middle candle, the more meaningful the pause.</li>
    <li><strong>Gaps</strong> — a gap down before Candle 2 (morning star) or a gap up (evening star) strengthens the pattern, though gaps are rare in 24-hour Forex.</li>
    <li><strong>Candle 3 closing into the body of Candle 1</strong> — the deeper the penetration, the stronger.</li>
</ul>

<h2>Factual context</h2>
<p>The morning and evening star patterns have been documented in Japanese candlestick literature for over two centuries. Steve Nison described them as "reversal patterns that mark the end of a trend and the beginning of a new one." The names come from the imagery of dawn (morning star) and dusk (evening star) — the transitions between night and day.</p>
<p>Nison also noted that these patterns are not "magic" — they're simply visual representations of shifts in buying and selling pressure. The middle candle represents the moment when the trend loses momentum, and the final candle represents the confirmation.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading the pattern without a prior trend.</strong> Morning stars at the bottom of a downtrend are meaningful. Morning stars in the middle of a range are noise.</li>
    <li><strong>Ignoring the size of the middle candle.</strong> A star with a large middle candle is really an engulfing-like pattern, not a clean star. The smaller the pause candle, the cleaner the signal.</li>
    <li><strong>Entering before Candle 3 closes.</strong> Many traders jump in during Candle 3, only to see it reverse before the close. Wait for the confirmation.</li>
    <li><strong>Not using a stop.</strong> Stars can fail. Place the stop below the low of the pattern (for a morning star) or above the high (for an evening star).</li>
</ul>

<h2>Advanced notes</h2>
<p>Stars often coincide with other reversal signals — RSI divergence, a break below a trendline, or a liquidity sweep. When a morning star forms at a level where the market has previously reversed, and RSI is showing oversold divergence on the higher timeframe, the pattern becomes much more reliable. This is the essence of <strong>confluence</strong> — stacking independent signals to increase probability. We'll cover confluence in the technical analysis modules, but the principle applies here: three-candle patterns become meaningful mostly in the context of other signals.</p>
HTML,
        ],

        [
            'slug'   => 'three-candle-formations',
            'title'  => 'Three-Candle Formations and the Wider Context',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Recognise the major three-candle patterns beyond stars\n" .
                "• Explain how three-candle patterns combine momentum, hesitation, and confirmation\n" .
                "• Understand why a chain of candles tells more than any single candle",
            'prerequisites' => 'Morning Star and Evening Star',
            'sort_order' => 9,
            'summary' => 'Beyond the classic stars, there are several three-candle patterns — three white soldiers, three black crows, three inside up/down. Their shared quality is that each candle builds on the previous one, creating a narrative rather than a single event.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>One candle is a snapshot. Two candles show a reaction. Three candles tell a story: the setup, the hesitation, the resolution. Learning to read the story, not the individual candles, is what separates pattern-enthusiasts from actual traders.</p>

<h2>Real-world analogy</h2>
<p>Think of a film. The first scene sets up the conflict. The second introduces a turning point. The third resolves it. Three-candle patterns follow the same structure — a beginning, a middle, and a conclusion that tells you what the market decided.</p>

<h2>Professional explanation</h2>

<h3>Three white soldiers (bullish continuation)</h3>
<p>Three consecutive long bullish candles, each closing higher than the last, each opening within the previous candle's body. Appears after a downtrend or a consolidation.</p>
<ul>
    <li><strong>Meaning:</strong> sustained buying pressure with no meaningful retracement.</li>
    <li><strong>Strength:</strong> strongest when the candles are large, closing near their highs, and appear after a period of weakness.</li>
    <li><strong>Watch out for:</strong> exhaustion — three big candles in a row can precede a pullback.</li>
</ul>

<h3>Three black crows (bearish continuation)</h3>
<p>Mirror image — three consecutive long bearish candles, each closing lower than the last.</p>
<ul>
    <li><strong>Meaning:</strong> sustained selling pressure with no meaningful pullback.</li>
    <li><strong>Strength:</strong> strongest after an uptrend or at resistance.</li>
</ul>

<h3>Three inside up / three inside down</h3>
<p>A two-candle inside-bar pattern followed by a confirmation candle in the direction of the breakout.</p>
<ul>
    <li><strong>Three inside up:</strong> bearish candle → bullish inside bar → bullish breakout candle above the mother bar's high. Bullish signal.</li>
    <li><strong>Three inside down:</strong> bullish candle → bearish inside bar → bearish breakout candle below the mother bar's low. Bearish signal.</li>
</ul>
<p>These patterns add a confirmation layer to inside bars, reducing false signals.</p>

<h3>Three outside up / three outside down</h3>
<p>An engulfing pattern followed by a candle confirming the new direction.</p>
<ul>
    <li><strong>Three outside up:</strong> bearish candle → bullish engulfing candle → higher bullish candle. Bullish signal.</li>
    <li><strong>Three outside down:</strong> bullish candle → bearish engulfing candle → lower bearish candle. Bearish signal.</li>
</ul>

<h3>Visual reference — three white soldiers</h3>
<svg viewBox="0 0 340 260" width="340" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <line x1="60" y1="180" x2="60" y2="220" stroke="#4ade80" stroke-width="2"/>
  <rect x="45" y="180" width="30" height="30" fill="#4ade80"/>
  <line x1="130" y1="120" x2="130" y2="185" stroke="#4ade80" stroke-width="2"/>
  <rect x="115" y="120" width="30" height="50" fill="#4ade80"/>
  <line x1="200" y1="60" x2="200" y2="125" stroke="#4ade80" stroke-width="2"/>
  <rect x="185" y="60" width="30" height="50" fill="#4ade80"/>
  <text x="130" y="245" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Three white soldiers</text>
</svg>

<h2>Why three-candle patterns carry weight</h2>
<p>Each additional candle reduces the chance that the pattern is random. One candle could be noise. Two candles could be noise. Three candles that tell a coherent story — setup, hesitation, resolution — is much harder to fake. This is not because the pattern has any predictive magic, but because it reflects a real, gradual shift in market sentiment.</p>

<h2>Factual context</h2>
<p>Steve Nison's original research on candlestick patterns classified them into three groups: single-candle (doji, hammer), two-candle (engulfing, inside), and three-candle (stars, soldiers, crows). His empirical work found that three-candle patterns had the highest signal-to-noise ratio, because the additional candles filtered out the random noise of single-candle fluctuations.</p>
<p>Trading educator Rayner Teo has repeatedly noted that "no pattern works in isolation — what matters is the context around it." A three-candle pattern that appears at a key level, in the direction of the higher-timeframe trend, with momentum confirmation, is a much stronger signal than the same pattern in a random location.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Treating three-candle patterns as guaranteed.</strong> They are probabilities, not predictions. Even the best setups fail.</li>
    <li><strong>Confusing pattern names.</strong> A "three inside up" is not a "morning star." Both are three-candle patterns, but their structures are different.</li>
    <li><strong>Chasing three white soldiers mid-move.</strong> By the time three consecutive green candles have formed, much of the move may be complete. Wait for a pullback or use a smaller timeframe for entry.</li>
</ul>

<h2>Advanced notes</h2>
<p>Three-candle patterns are often most useful as <em>confirmation</em> tools rather than entry signals on their own. For example, if you already have a bullish bias based on higher-timeframe structure, a three white soldiers pattern on the 1-hour chart provides a clean trigger. But if you're just scanning for three white soldiers to trade randomly, you'll lose money — the pattern's value depends entirely on where it appears.</p>
HTML,
        ],

        [
            'slug'   => 'candlestick-patterns-in-context',
            'title'  => 'Candlestick Patterns in Context (Why Most Fail)',
            'difficulty' => 'beginner',
            'estimated_duration' => 13,
            'learning_objectives' =>
                "• Explain why the same pattern can succeed or fail depending on context\n" .
                "• Identify the four contextual factors that give a pattern meaning\n" .
                "• Avoid the most common beginner mistake: pattern-chasing",
            'prerequisites' => 'Three-Candle Formations',
            'sort_order' => 10,
            'summary' => 'A pattern in isolation means nothing. The location, the trend, the timeframe, and the surrounding price action all determine whether a candlestick signal is meaningful. This lesson is the most important one in the module.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine seeing a "SLOW" sign on a road. On a quiet suburban street, it's advice. On a highway at night in fog, it's critical. The sign itself doesn't change — the context does. Candlestick patterns are the same. A hammer at the bottom of a downtrend, at a major support level, is critical information. A hammer in the middle of a choppy range is background noise.</p>

<h2>Real-world analogy</h2>
<p>Consider a doctor reading an X-ray. The same shadow on a lung might be nothing in a healthy 25-year-old, but a serious warning sign in a 60-year-old smoker. The shadow is the same; the context changes its meaning. Patterns work the same way.</p>

<h2>Professional explanation</h2>
<p>Every candlestick pattern is a description of what happened during a specific period. It is not a prediction. Its meaning depends on four contextual factors:</p>

<h3>1. Location</h3>
<p>Where did the pattern form? At a key level (support, resistance, trendline, round number) it carries weight. In the middle of nowhere it doesn't.</p>
<p>Example: a bullish engulfing at a double-bottom support is a strong reversal signal. A bullish engulfing in the middle of a downtrend is often just a pause before continuation.</p>

<h3>2. Trend context</h3>
<p>Patterns that align with the higher-timeframe trend have a higher success rate. Patterns that fight the trend are riskier.</p>
<ul>
    <li>In an uptrend, bullish continuation patterns (inside bars, three white soldiers) work well.</li>
    <li>In an uptrend, bearish reversal patterns (evening star, bearish engulfing) can still work at major resistance — but the odds are lower.</li>
    <li>In a range, most candle patterns fail because there's no trend to reverse or continue.</li>
</ul>

<h3>3. Timeframe</h3>
<p>A pattern on the daily chart represents an entire day of trading. A pattern on the 5-minute chart represents five minutes. The same pattern type has very different significance at different timeframes.</p>
<ul>
    <li>A daily hammer can mark a multi-week low.</li>
    <li>A 5-minute hammer is often noise that resolves within the hour.</li>
    <li>Multi-timeframe alignment — where a signal on a smaller timeframe agrees with a signal on a larger one — is much more reliable.</li>
</ul>

<h3>4. Surrounding price action</h3>
<p>The candles <em>around</em> a pattern tell you whether the signal is confirmed or contradicted.</p>
<ul>
    <li>A hammer followed by a green candle = confirmation. The reversal held.</li>
    <li>A hammer followed by a red candle closing below the hammer's low = failure. The reversal did not hold.</li>
    <li>A pattern that appears after a long extension of the trend is more likely to mark a genuine reversal than one appearing mid-trend.</li>
</ul>

<h2>Putting it together</h2>
<p>The best candlestick signals are:</p>
<ol>
    <li>At a key level (support, resistance, trendline, round number)</li>
    <li>In alignment with the higher-timeframe trend</li>
    <li>On the higher timeframes (4H, Daily) rather than the tiny ones</li>
    <li>Confirmed by the following candle(s)</li>
</ol>
<p>When all four align, you have a high-probability setup. When only one or two align, the pattern is likely noise.</p>

<h2>Factual context</h2>
<p>Steve Nison's most quoted line is arguably:</p>
<blockquote><strong>"Candlestick patterns do not give any indication of the duration of a trend, only the reversal points. The candle provides a visual depiction of the battle between bulls and bears."</strong></blockquote>
<p>Al Brooks, whose price action work is considered authoritative, was even more direct: he argues that <strong>context is 80% of the signal, the pattern itself only 20%</strong>. His charts show the same patterns succeeding and failing based on location alone. Brooks has repeatedly stated that a trader who only memorises patterns will lose money, while a trader who understands context can trade with almost no pattern memorisation at all.</p>
<p>This is the core lesson: <em>patterns are the vocabulary, but context is the grammar</em>. You can't write sentences with words alone.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Pattern-chasing.</strong> Scanning charts for patterns and trading every one you find. Without context, this is guessing with extra steps.</li>
    <li><strong>Ignoring the higher timeframe.</strong> A bullish hammer on the 5-minute chart means nothing if the daily chart is in a strong downtrend.</li>
    <li><strong>Overtrading patterns in a range.</strong> In choppy markets, every pattern fires constantly and produces nothing but whipsaws. Learn to recognise range conditions and stand aside.</li>
    <li><strong>Memorising hundreds of patterns.</strong> You don't need 50 patterns. Ten — understood deeply and read in context — will outperform 50 shallowly applied.</li>
</ul>

<h2>Advanced notes</h2>
<p>Many professional traders use only a handful of candle patterns: pin bars, engulfing, and inside bars — combined with market structure and liquidity concepts. They trade these few patterns in specific, well-defined contexts. The patterns themselves are simple. The skill is in knowing <em>when</em> to apply them.</p>
<p>This is why the next modules focus on structure, support and resistance, and trend — because those are the frameworks that give your candle reading meaning. Patterns come after context, not before.</p>
HTML,
        ],

    ],
];