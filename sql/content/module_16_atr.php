<?php
/**
 * Module 16 — ATR (Average True Range)
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_16_atr.php
 */

return [
    'module' => [
        'level_slug' => 'intermediate',
        'slug'       => 'atr',
        'title'      => 'ATR (Average True Range)',
        'description'=> 'ATR is the market\'s volatility gauge. It tells you how much a market is moving on average — not in which direction. Knowing volatility lets you size positions correctly, place stops where they belong, and avoid the two most common trading mistakes: stops too tight and positions too large.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Understand what ATR measures and how it is calculated\n" .
            "• Use ATR to size positions correctly\n" .
            "• Place stops based on market volatility rather than arbitrary pip counts\n" .
            "• Adapt your strategy to changing market conditions\n" .
            "• Recognise the limits of ATR as a volatility measure",
        'sort_order' => 16,
    ],

    'lessons' => [

        [
            'slug'   => 'what-is-atr',
            'title'  => 'What Is ATR?',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define ATR and what it measures\n" .
                "• Explain the concept of True Range\n" .
                "• Understand why volatility matters in trading",
            'prerequisites' => 'Putting MACD Together',
            'sort_order' => 1,
            'summary' => 'ATR measures the average range a market moves in a given period. It doesn\'t tell you direction — only how much the market typically moves. This is essential information for position sizing and stop placement.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two cars. One drives smoothly at 60 mph with almost no variation. The other one is bouncing all over the road, changing speed constantly — 40, 80, 55, 75. The second car is much harder to predict.</p>
<p>ATR measures this "bouncing" for a market. High ATR means the market is moving a lot. Low ATR means it's moving very little. Either way, ATR doesn't tell you which direction — just how much movement is happening.</p>

<h2>Real-world analogy</h2>
<p>Think of weather forecasts. A meteorologist can tell you "the temperature will vary by about 8°C today" without knowing if it's going to be warm or cold. That range is the forecast's "volatility." ATR is the market's version of that range forecast.</p>

<h2>Professional explanation</h2>
<p><strong>ATR</strong> — Average True Range — was developed by <strong>J. Welles Wilder Jr.</strong> in 1978 (the same person who created RSI). It measures the average volatility of an asset over a specified period, typically 14 candles.</p>

<h3>What is True Range?</h3>
<p>True Range is the largest of the following three values for any given candle:</p>
<ol>
    <li>Current high minus current low (the candle's range)</li>
    <li>Absolute value of current high minus previous close</li>
    <li>Absolute value of current low minus previous close</li>
</ol>
<p>Why three values? Because gaps can occur between candles. If the current candle opens well above the previous close, the "true" range includes that gap. ATR captures this — regular "high minus low" wouldn't.</p>

<h3>ATR calculation</h3>
<p><code>ATR = Moving average of True Range over N periods</code></p>
<p>Wilder originally used a smoothed moving average, but modern platforms often use a simple or exponential moving average. The default period is 14.</p>

<h3>What ATR tells you</h3>
<ul>
    <li><strong>Volatility level</strong> — How much the market is moving.</li>
    <li><strong>Expected range</strong> — A rough estimate of how far price is likely to move in a period.</li>
    <li><strong>Volatility trend</strong> — Whether volatility is rising or falling.</li>
</ul>
<p>What ATR does NOT tell you:</p>
<ul>
    <li>Direction (up or down)</li>
    <li>Where to enter</li>
    <li>When to exit</li>
</ul>
<p>ATR is a supporting tool, not a signal generator.</p>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Low volatility phase -->
  <g>
    <rect x="50" y="140" width="6" height="20" fill="#4ade80"/>
    <rect x="70" y="145" width="6" height="18" fill="#4ade80"/>
    <rect x="90" y="138" width="6" height="22" fill="#ef4444"/>
    <rect x="110" y="142" width="6" height="20" fill="#4ade80"/>
    <rect x="130" y="140" width="6" height="19" fill="#ef4444"/>
    <rect x="150" y="145" width="6" height="18" fill="#4ade80"/>
    <rect x="170" y="138" width="6" height="22" fill="#4ade80"/>
  </g>

  <!-- High volatility phase -->
  <g>
    <rect x="220" y="80" width="10" height="80" fill="#4ade80"/>
    <rect x="250" y="120" width="10" height="60" fill="#ef4444"/>
    <rect x="280" y="60" width="10" height="100" fill="#4ade80"/>
    <rect x="310" y="140" width="10" height="60" fill="#ef4444"/>
    <rect x="340" y="50" width="10" height="90" fill="#4ade80"/>
    <rect x="370" y="130" width="10" height="70" fill="#ef4444"/>
    <rect x="400" y="40" width="10" height="100" fill="#4ade80"/>
  </g>

  <!-- ATR line -->
  <polyline points="50,170 100,168 150,168 200,150 250,120 300,100 350,80 400,60 450,50"
            fill="none" stroke="#5b7cfa" stroke-width="2.5"/>

  <text x="120" y="215" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Low volatility — small ATR</text>
  <text x="340" y="215" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">High volatility — rising ATR</text>
  <text x="420" y="45" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif">ATR</text>
</svg>

<h3>Why ATR matters</h3>
<p>ATR answers a fundamental question every trader faces: <em>How much should I expect this market to move?</em></p>
<ul>
    <li><strong>Position sizing</strong> — If ATR is high, use smaller positions. If low, larger positions.</li>
    <li><strong>Stop placement</strong> — Stops should be wider than ATR to avoid being stopped by normal noise.</li>
    <li><strong>Trade selection</strong> — Compare ATR across markets to find the best trading opportunities.</li>
    <li><strong>Regime detection</strong> — Rising ATR = increasing volatility = potentially trending. Falling ATR = compressing = potential breakout coming.</li>
</ul>

<h2>Factual context</h2>
<p>Wilder introduced ATR in his 1978 book <em>New Concepts in Technical Trading Systems</em> — the same book that introduced RSI and ADX. He designed ATR as a general-purpose volatility measure that could be used across markets and timeframes.</p>
<p>The concept of measuring volatility has deep roots in financial analysis. Statisticians have used standard deviation to measure volatility since the early 20th century. Wilder's insight was to use a simpler measure based on the actual intra-candle range, which proved more useful for trading.</p>
<p>ATR has become one of the most widely-used indicators in professional trading. Its value has been validated by modern quant research — volatility is one of the few market characteristics that can be predicted with reasonable accuracy. Studies going back to Robert Engle's Nobel Prize-winning work on ARCH (2003) have shown that volatility clusters: high-volatility periods follow high-volatility periods, and low follows low.</p>
<p>Wilder described the purpose of ATR in his book:</p>
<blockquote><strong>"The Average True Range is a measure of volatility. Its primary use is not to tell the trader when to enter or exit a market, but to give the trader a sense of how much the market is likely to move in a given period. This information is essential for calculating proper stop-loss levels and position sizes."</strong></blockquote>
<p>Wilder's emphasis is important — ATR is a supporting tool, not a signal generator. Its value comes from what it lets you do with your other analysis.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Using ATR as a signal.</strong> ATR doesn't tell you when to trade — it tells you how to size and place stops.</li>
    <li><strong>Using ATR across markets without normalisation.</strong> An ATR of 50 pips means different things on EUR/USD vs GBP/JPY. Always look at ATR relative to price.</li>
    <li><strong>Ignoring ATR changes.</strong> ATR that's rising means increasing volatility — your position sizes may need to adjust.</li>
    <li><strong>Confusing ATR with a directional indicator.</strong> ATR tells you volatility, not direction. High ATR could mean a strong uptrend, a strong downtrend, or a volatile range.</li>
</ul>

<h2>Advanced notes</h2>
<p>Many professional traders use ATR as part of a broader volatility framework, along with standard deviation, Bollinger Bands, and other measures. Each has slightly different properties:</p>
<ul>
    <li><strong>ATR</strong> — measures actual range (high-low, adjusted for gaps). Very robust.</li>
    <li><strong>Standard deviation</strong> — measures statistical dispersion of closes. Used in Bollinger Bands.</li>
    <li><strong>Bollinger Band width</strong> — measures the distance between upper and lower bands, reflecting volatility.</li>
</ul>
<p>For most traders, ATR is the most practical volatility measure — simple to understand and directly applicable to stop placement and position sizing.</p>
HTML,
        ],

        [
            'slug'   => 'atr-based-stop-loss',
            'title'  => 'ATR-Based Stop-Loss Placement',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Explain why ATR-based stops are superior to fixed-pip stops\n" .
                "• Calculate ATR-based stop distances\n" .
                "• Adapt stop sizes to current market volatility",
            'prerequisites' => 'What Is ATR?',
            'sort_order' => 2,
            'summary' => 'Fixed-pip stop-losses ignore market volatility. ATR-based stops place the stop at a distance proportional to current market conditions — wider in volatile markets, tighter in calm markets. This dramatically reduces the chance of being stopped by normal market noise.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine setting a rule that you'll always stop 10 steps behind your friend. On a calm street, that works fine. But if your friend suddenly starts running, 10 steps might be too close — you'll lose them the moment they sprint. If they slow down, 10 steps is too far — you'll be way behind.</p>
<p>ATR-based stops work like adjusting your distance to match your friend's speed. When the market moves fast, give your stop more room. When it moves slowly, you can place it tighter.</p>

<h2>Real-world analogy</h2>
<p>Think of a safety margin in construction. You don't specify "support beams must be 2 metres from the edge." You specify "support beams must extend 20% beyond the load-bearing area." The margin scales with the requirement. ATR-based stops scale with the market's volatility.</p>

<h2>Professional explanation</h2>

<h3>The problem with fixed-pip stops</h3>
<p>Many beginner traders use fixed-pip stops — e.g., "always use a 30-pip stop." This seems reasonable but has two problems:</p>
<ol>
    <li><strong>In quiet markets</strong> — a 30-pip stop is too wide. Your risk is unnecessarily large.</li>
    <li><strong>In volatile markets</strong> — a 30-pip stop is too tight. You'll be stopped out by normal noise before the trade has a chance to work.</li>
</ol>

<h3>The ATR solution</h3>
<p>Instead of a fixed pip distance, place your stop at a multiple of ATR:</p>
<p><code>Stop distance = N × ATR</code></p>
<p>Where N is usually 1.5 or 2. Common values:</p>
<ul>
    <li><strong>1.5 × ATR</strong> — tighter, fewer pips of risk, but may be stopped by noise.</li>
    <li><strong>2 × ATR</strong> — standard, balances risk and room.</li>
    <li><strong>3 × ATR</strong> — wide, used for swing trades on higher timeframes.</li>
</ul>

<h3>Example</h3>
<p>Suppose EUR/USD has an ATR of 20 pips on the H4 chart. Your stop would be:</p>
<ul>
    <li>1.5 × ATR = 30 pips</li>
    <li>2 × ATR = 40 pips</li>
    <li>3 × ATR = 60 pips</li>
</ul>
<p>Now suppose EUR/USD has an ATR of 50 pips (volatile market):</p>
<ul>
    <li>1.5 × ATR = 75 pips</li>
    <li>2 × ATR = 100 pips</li>
    <li>3 × ATR = 150 pips</li>
</ul>
<p>Notice how the stop automatically adjusts to volatility. In quiet markets, tighter. In volatile markets, wider.</p>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 220" width="500" height="220" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Quiet market with tight stop -->
  <text x="120" y="20" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Low volatility — tight stop</text>
  <rect x="60" y="60" width="4" height="20" fill="#4ade80"/>
  <rect x="80" y="65" width="4" height="18" fill="#ef4444"/>
  <rect x="100" y="58" width="4" height="22" fill="#4ade80"/>
  <rect x="120" y="62" width="4" height="20" fill="#ef4444"/>
  <rect x="140" y="60" width="4" height="18" fill="#4ade80"/>
  <rect x="160" y="63" width="4" height="20" fill="#4ade80"/>
  <!-- Stop line close -->
  <line x1="50" y1="100" x2="180" y2="100" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="185" y="104" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">1.5×ATR</text>

  <!-- Volatile market with wider stop -->
  <text x="370" y="20" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">High volatility — wider stop</text>
  <rect x="290" y="50" width="8" height="40" fill="#4ade80"/>
  <rect x="320" y="70" width="8" height="30" fill="#ef4444"/>
  <rect x="350" y="40" width="8" height="50" fill="#4ade80"/>
  <rect x="380" y="60" width="8" height="40" fill="#ef4444"/>
  <rect x="410" y="35" width="8" height="45" fill="#4ade80"/>
  <rect x="440" y="55" width="8" height="50" fill="#4ade44"/>
  <!-- Stop line farther -->
  <line x1="280" y1="150" x2="480" y2="150" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="420" y="170" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">1.5×ATR (wider)</text>
</svg>

<h3>Where to place the stop relative to structure</h3>
<p>ATR-based stops should still respect market structure:</p>
<ol>
    <li><strong>Calculate the ATR-based distance.</strong></li>
    <li><strong>Check if this distance covers your structural stop.</strong> If your trade's invalidation point is 20 pips away but the ATR-based stop is 40 pips, use the 40 (or place the stop just beyond the structural point + a small buffer).</li>
    <li><strong>Never place the stop closer than ATR.</strong> If the structural stop is closer, adjust your entry so the stop distance is at least 1× ATR.</li>
</ol>

<h3>Why ATR stops work</h3>
<ul>
    <li><strong>Adaptive.</strong> They automatically adjust to market conditions.</li>
    <li><strong>Objective.</strong> No more guessing what a "reasonable" stop distance is.</li>
    <li><strong>Statistically sound.</strong> If the market's typical move is X pips, your stop should be wider than X pips.</li>
    <li><strong>Works across markets.</strong> The same rule works on EUR/USD, GBP/JPY, gold, and BTC.</li>
</ul>

<h2>Factual context</h2>
<p>Wilder's original work on ATR emphasised exactly this use case: placing stops based on volatility rather than arbitrary pip distances. His argument was that stop placement should reflect how much the market is actually moving, not what feels comfortable to the trader.</p>
<p>The Turtle Traders — Richard Dennis' famous experiment — used a variation of ATR-based stops. Their system used "N" (essentially ATR) to size positions and place stops. Each Turtle unit was defined as 1% of account equity divided by N. Their approach proved extremely robust across multiple markets, and is one of the earliest documented uses of ATR-based position sizing.</p>
<p>Modern systematic trading increasingly uses ATR-based stops because they're more robust than fixed-pip stops across different markets and timeframes. Studies have found that ATR-based stops produce better risk-adjusted returns than fixed-pip approaches, particularly in markets with variable volatility.</p>
<p>Larry Hite, one of the original Market Wizards, emphasised the importance of volatility-based stops:</p>
<blockquote><strong>"The market is never wrong. You must adapt to it. Stop placement based on volatility keeps you out of the noise while letting you stay in trends."</strong></blockquote>
<p>Hite's point captures the essence of ATR-based stops: they keep you out of the market's normal noise, while giving the trade enough room to develop into a winner.</p>
<p>Bruce Kovner, another Market Wizard, has said something similar:</p>
<blockquote><strong>"I know where I'm getting out before I get in. And I always size my positions so that a loss doesn't affect my ability to trade tomorrow."</strong></blockquote>
<p>ATR-based stops and position sizing (covered in the next lesson) are the practical tools that implement these principles.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Using ATR from the wrong timeframe.</strong> If you're trading on H1, use the H1 ATR (or H4 for a wider view), not the daily ATR.</li>
    <li><strong>Not accounting for news events.</strong> ATR doesn't predict news-driven spikes. During high-impact news, add extra buffer to your stop.</li>
    <li><strong>Using ATR without structure.</strong> ATR tells you how wide, but structure tells you where. Combine both.</li>
    <li><strong>Tightening stops too much.</strong> Some traders use 0.5×ATR for "tighter risk," but this almost guarantees stop-outs by noise.</li>
    <li><strong>Assuming ATR is constant.</strong> ATR changes with market conditions. Check it regularly, especially around major events.</li>
</ul>

<h2>Advanced notes</h2>
<p>For intraday traders, ATR on the M15 or M30 might be more relevant than the daily ATR. For swing traders, the daily ATR is the standard reference. For position traders, the weekly ATR. The rule: <strong>use the ATR of the timeframe you're trading on</strong>, and place your stop relative to that ATR.</p>
<p>Some traders use <strong>ATR normalisation</strong> across markets. To compare ATR between two markets with different price scales, divide the ATR by the current price. This gives you the ATR as a percentage, which is directly comparable. For example, EUR/USD might have an ATR of 0.6% while GBP/JPY has an ATR of 0.8% — meaning GBP/JPY is proportionally more volatile.</p>
HTML,
        ],

        [
            'slug'   => 'atr-position-sizing',
            'title'  => 'ATR-Based Position Sizing',
            'difficulty' => 'intermediate',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Explain why position sizing is the most important risk variable\n" .
                "• Calculate position size using ATR and account risk\n" .
                "• Adapt position sizes to changing volatility",
            'prerequisites' => 'ATR-Based Stop-Loss Placement',
            'sort_order' => 3,
            'summary' => 'Position sizing determines how much you risk on each trade. Using ATR to size positions means you risk the same percentage of your account regardless of market volatility — larger positions in calm markets, smaller positions in volatile markets.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two trades. One has a 20-pip stop; the other has a 100-pip stop. If you risk $100 on each trade, the first trade must use a position size 5× larger than the second — because a 20-pip loss × 5× position = a 100-pip loss × 1× position.</p>
<p>ATR-based position sizing formalises this: adjust your position size based on your stop distance (which itself is ATR-based), so that each trade risks the same percentage of your account.</p>

<h2>Real-world analogy</h2>
<p>Think of a business budgeting for different projects. Some projects take 10 hours; others take 100. You don't invest the same dollar amount in each — you invest proportionally so each project's risk is manageable. Trading works the same way.</p>

<h2>Professional explanation</h2>

<h3>The formula</h3>
<p><code>Position size = (Account × Risk %) / (Stop distance × Pip value)</code></p>
<p>For Forex, pip value is typically $10 per standard lot (for USD-quoted pairs). For other instruments, it varies.</p>

<h3>Step-by-step calculation</h3>
<ol>
    <li><strong>Determine account risk.</strong> Common: 0.5% to 2% per trade. Let's use 1%.</li>
    <li><strong>Determine stop distance</strong> using ATR. Let's say 2× ATR = 40 pips.</li>
    <li><strong>Calculate dollar risk.</strong> 1% of $10,000 account = $100.</li>
    <li><strong>Calculate pip value per unit.</strong> Assume $10 per pip per standard lot.</li>
    <li><strong>Calculate position size.</strong> $100 / (40 × $10 per standard lot) = 0.25 standard lots.</li>
</ol>
<p>So you would trade 0.25 lots on this setup — risking $100 if the 40-pip stop is hit.</p>

<h3>How ATR changes position size</h3>
<p>Suppose the market becomes more volatile. ATR rises from 20 to 40 pips. Your 2× ATR stop becomes 80 pips.</p>
<ul>
    <li>Dollar risk remains $100.</li>
    <li>Stop distance is now 80 pips.</li>
    <li>Position size = $100 / (80 × $10) = 0.125 standard lots.</li>
</ul>
<p>Your position is halved — automatically — because the market is twice as volatile. This is the power of ATR-based sizing: it self-adjusts.</p>

<h3>Why this matters so much</h3>
<p>Position sizing is the single biggest determinant of long-term trading success. Here's why:</p>
<ul>
    <li>You cannot control which trades win or lose.</li>
    <li>You cannot control how far price moves.</li>
    <li>You <strong>can</strong> control how much you risk on each trade.</li>
</ul>
<p>If you risk a consistent, small percentage of your account on every trade, you can survive long losing streaks. If you risk large amounts inconsistently, one bad trade can wipe out months of profits.</p>

<h3>Visual reference — same risk, different position sizes</h3>
<svg viewBox="0 0 500 200" width="500" height="200" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Quiet market -->
  <text x="120" y="20" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Low ATR (20 pips)</text>
  <rect x="60" y="50" width="120" height="30" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80"/>
  <text x="120" y="70" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Position: 0.25 lots</text>
  <text x="120" y="120" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Risk: $100</text>

  <!-- Volatile market -->
  <text x="370" y="20" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">High ATR (40 pips)</text>
  <rect x="310" y="50" width="60" height="30" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80"/>
  <text x="340" y="70" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">0.125 lots</text>
  <text x="370" y="120" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Risk: $100</text>
</svg>

<h3>The Turtle formula</h3>
<p>Richard Dennis' Turtles used essentially the same formula:</p>
<p><code>Unit size = (1% of account) / N</code></p>
<p>Where N was their volatility measure (essentially ATR). Every trade was sized so that a 1N move against them would cost 1% of the account. This was one of the keys to their success — consistent risk across all trades and all markets.</p>

<h3>Practical considerations</h3>
<ul>
    <li><strong>Risk %:</strong> 0.5%–1% is conservative; 1%–2% is standard; above 2% is aggressive. Professional traders rarely risk more than 1–2% per trade.</li>
    <li><strong>Consecutive trades:</strong> If you take multiple trades, total risk should be capped. E.g., 5 trades × 1% risk = 5% total. If all hit stops, you lose 5%. Most professional risk guidelines cap this at 3–6% at any given time.</li>
    <li><strong>Account currency:</strong> For non-USD accounts, convert pip value into your account currency. Platforms handle this automatically.</li>
</ul>

<h2>Factual context</h2>
<p>Position sizing is one of the most important concepts in trading, but it's often overlooked by beginners. The Turtle Traders experiment proved that even a group of complete novices could trade profitably with proper position sizing — the rules were simple, but the risk management was rigorous.</p>
<p>Van Tharp, in his book <em>Trade Your Way to Financial Freedom</em>, emphasised that position sizing is more important than entry technique. His research found that position sizing explains the majority of the difference in returns between traders using the same entry system.</p>
<p>Ed Seykota's famous rule — "keep bets small" — is the practical application of this principle. So is the classic Wall Street adage:</p>
<blockquote><strong>"There are old traders and there are bold traders, but there are very few old, bold traders."</strong></blockquote>
<p>Market Wizard Bruce Kovner's advice reinforces this:</p>
<blockquote><strong>"Don't be a hero. Don't have an ego. Always question yourself and your ability. Don't ever feel that you are very good."</strong></blockquote>
<p>The practical result of humility is small, consistent position sizes. You never know which trade will be the one that changes your account — but you also never know which trade will be the one that blows it up.</p>
<p>Paul Tudor Jones makes the same point differently:</p>
<blockquote><strong>"I'm always thinking about losing money as opposed to making money. Don't focus on making money, focus on protecting what you have."</strong></blockquote>
<p>ATR-based position sizing is the practical implementation of "protecting what you have." It ensures that no single trade can meaningfully damage your account.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Risking a fixed dollar amount regardless of stop distance.</strong> This produces wildly varying risk per trade. Always risk a fixed percentage.</li>
    <li><strong>Risking too much per trade.</strong> More than 2% per trade is aggressive and risky. Stay at 1% or less until you're consistently profitable.</li>
    <li><strong>Ignoring aggregate risk.</strong> If you have 5 open trades, your total risk might be 5% — that's too much. Cap total risk.</li>
    <li><strong>Adjusting position size emotionally.</strong> Increasing size after a loss or decreasing after a win is emotional trading. Stick to the formula.</li>
    <li><strong>Not accounting for correlated trades.</strong> If you're long EUR/USD and long GBP/USD, these are highly correlated — your actual risk is higher than the sum of individual risks. Treat correlated trades as one position.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often add a <strong>volatility-adjusted cap</strong> on total position size. Even if the formula says 2 lots, they'll cap at 1 lot if volatility is unusually high or if they're unsure about the trade. This is a defensive measure that prevents the formula from producing outsized positions during extreme volatility.</p>
<p>Another refinement is <strong>Kelly Criterion</strong> — a mathematical formula that optimises position size based on win rate and payoff ratio. However, full Kelly is too aggressive for most traders because it produces extreme drawdowns. Most professional traders use "fractional Kelly" — typically ¼ to ½ of the full Kelly recommendation. ATR-based sizing with a 1% risk rule is essentially a very conservative application of these principles.</p>
HTML,
        ],

        [
            'slug'   => 'putting-atr-together',
            'title'  => 'Putting ATR Together',
            'difficulty' => 'intermediate',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Combine ATR with trend, structure, and other indicators\n" .
                "• Build a complete ATR-based framework\n" .
                "• Apply ATR to real trading scenarios",
            'prerequisites' => 'ATR-Based Position Sizing',
            'sort_order' => 4,
            'summary' => 'This final lesson brings together everything in the module: what ATR is, ATR-based stop placement, and ATR-based position sizing. The goal is a framework where ATR becomes a natural part of your trading process — not an afterthought.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>ATR is like the scaffolding of your trading — it doesn't create the trade, but it holds everything in place. It tells you how wide your stops should be, how big your positions should be, and how to adjust when the market changes.</p>

<h2>The complete framework</h2>

<h3>Step 1: Identify the trade</h3>
<p>Use your usual analysis — trend, structure, patterns, indicators — to identify a potential trade.</p>

<h3>Step 2: Calculate your stop level</h3>
<p>Based on structure and ATR:</p>
<ul>
    <li><strong>Structural stop</strong> — where price needs to go to invalidate your idea.</li>
    <li><strong>ATR stop</strong> — 1.5× or 2× the ATR of your trading timeframe.</li>
    <li><strong>Use the wider of the two.</strong> Never place a stop closer than 1× ATR. And never place a stop inside recent structure.</li>
</ul>

<h3>Step 3: Calculate position size</h3>
<p><code>Position size = (Account × Risk %) / (Stop distance × Pip value)</code></p>
<p>Standard risk per trade: 1% of account equity.</p>

<h3>Step 4: Set target and R:R</h3>
<p>Targets should still respect structure and measured moves, but you can use ATR to sanity-check:</p>
<ul>
    <li><strong>Minimum target</strong> — 2× the stop distance (2:1 R:R).</li>
    <li><strong>Typical target</strong> — 3× the stop distance (3:1 R:R).</li>
    <li><strong>Aggressive target</strong> — 4× or more, if the trend is strong and the level is far.</li>
</ul>

<h3>Step 5: Adjust for volatility regime</h3>
<p>Check whether ATR is rising or falling:</p>
<ul>
    <li><strong>Rising ATR</strong> — volatility expanding. Consider reducing position size, widening stops, or tightening targets.</li>
    <li><strong>Falling ATR</strong> — volatility contracting. Expect a potential breakout. Consider that the current range will likely resolve soon.</li>
</ul>

<h3>Step 6: Manage the trade</h3>
<ul>
    <li>Move stop to break-even after 1× risk has been reached.</li>
    <li>Trail stop using structure.</li>
    <li>If ATR spikes unexpectedly (news event), consider reducing exposure.</li>
    <li>Exit at target or when structure invalidates the trade.</li>
</ul>

<h2>Worked example — GBP/JPY</h2>

<h3>Setup</h3>
<ul>
    <li>Daily trend: bullish (HH/HL).</li>
    <li>H4 pullback to a support level.</li>
    <li>H1 bullish engulfing at support, in the direction of the daily trend.</li>
</ul>

<h3>Calculations</h3>
<ul>
    <li>Account: $10,000.</li>
    <li>Risk per trade: 1% = $100.</li>
    <li>H1 ATR: 25 pips.</li>
    <li>Structural stop: 40 pips (below support).</li>
    <li>ATR stop: 2 × 25 = 50 pips.</li>
    <li>Use the wider: 50 pips.</li>
    <li>Pip value per standard lot: ~$9 (approximate for GBP/JPY).</li>
</ul>

<h3>Position size</h3>
<p><code>Position size = $100 / (50 × $9) = 0.22 standard lots</code></p>
<p>So you'd trade about 0.22 lots.</p>

<h3>Target</h3>
<ul>
    <li>Structural target: prior swing high, 150 pips away (3:1 R:R).</li>
    <li>Position: 0.22 lots, 150-pip target = $297 profit if hit.</li>
    <li>Loss if stopped: $100 (1% risk).</li>
</ul>

<h3>Management</h3>
<ul>
    <li>Move stop to break-even after 50 pips of favourable movement (1× risk).</li>
    <li>Trail stop below each new H1 higher low.</li>
    <li>Take partial profit at 100 pips (2:1 R:R), let the rest run.</li>
    <li>Exit fully at 150-pip target or if H1 structure breaks.</li>
</ul>

<h2>ATR in different market conditions</h2>
<table>
    <thead><tr><th>Market State</th><th>ATR Behaviour</th><th>What It Means</th></tr></thead>
    <tbody>
        <tr><td>Quiet range</td><td>Low, flat</td><td>Prepare for breakout; small stops OK</td></tr>
        <tr><td>Active trend</td><td>Rising moderately</td><td>Good conditions; standard sizing</td></tr>
        <tr><td>Climax move</td><td>Spiking</td><td>Reduce size; expect reversal or pause</td></tr>
        <tr><td>Consolidation</td><td>Falling steadily</td><td>Volatility compression — breakout pending</td></tr>
        <tr><td>News event</td><td>Sudden spike</td><td>Avoid new entries; widen stops on existing</td></tr>
    </tbody>
</table>

<h2>ATR with other indicators</h2>
<ul>
    <li><strong>ATR + Moving averages</strong> — MA determines trend; ATR determines stop distance.</li>
    <li><strong>ATR + RSI</strong> — RSI identifies momentum; ATR determines position size.</li>
    <li><strong>ATR + MACD</strong> — MACD gives entry signals; ATR manages risk on those signals.</li>
    <li><strong>ATR + Bollinger Bands</strong> — Both measure volatility; Bollinger Band width and ATR should move together.</li>
</ul>
<p>ATR is essentially a "meta-indicator" — it doesn't generate signals but improves the quality of every signal you do generate.</p>

<h2>Factual context</h2>
<p>ATR's importance in professional trading is undisputed. Every major institutional trading system uses volatility-based position sizing in some form. The Turtles' N-based sizing was one of the earliest documented examples, and modern hedge funds continue to use ATR-like measures.</p>
<p>AQR Capital Management, one of the largest quant hedge funds in the world, has published extensively on volatility-based position sizing. Their research shows that risk parity strategies — which size positions inversely to volatility — produce better risk-adjusted returns than equal-weighted strategies. This is the same principle as ATR-based sizing, applied at the portfolio level.</p>
<p>Van Tharp, whose work on position sizing is considered foundational in the trading community, has emphasised:</p>
<blockquote><strong>"The golden rule of trading is: 'Cut your losses short and let your winners run.' Position sizing is how you implement this rule."</strong></blockquote>
<p>ATR-based sizing is the practical embodiment of Tharp's principle. It ensures you risk a small, consistent amount on each trade while giving your winners room to develop.</p>
<p>Ed Seykota's rules from the Market Wizards interviews — "cut losses, ride winners, keep bets small, follow the rules" — are all captured in an ATR-based framework:</p>
<ul>
    <li><strong>Cut losses</strong> — ATR-based stops.</li>
    <li><strong>Ride winners</strong> — trailing stops, partial profit taking.</li>
    <li><strong>Keep bets small</strong> — 1% risk per trade.</li>
    <li><strong>Follow the rules</strong> — everything mechanical, nothing emotional.</li>
</ul>
<p>Seykota's system, like the Turtle system, and like most successful trading systems in history, is built on the foundation of volatility-adjusted risk management. ATR is the practical tool that makes this possible.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Ignoring ATR entirely.</strong> Trading without volatility-based sizing means you're risking wildly different amounts on different trades.</li>
    <li><strong>Using ATR only for stops, not position sizing.</strong> The two work together. One without the other leaves risk uncontrolled.</li>
    <li><strong>Not updating ATR.</strong> ATR changes constantly. Recalculate it before every trade.</li>
    <li><strong>Fighting the volatility regime.</strong> If ATR is spiking, don't try to force normal-sized trades. Reduce size and wait.</li>
    <li><strong>Assuming one ATR rule works everywhere.</strong> Different markets and timeframes have different volatility profiles. Adapt the multiplier.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders sometimes use <strong>ATR-normalised performance metrics</strong> to compare strategies across markets. Instead of measuring returns in dollars or pips, they measure them in "R" — where R is the initial risk of the trade (which itself is ATR-based). A return of 3R means the trade made three times the initial risk. This normalisation allows apples-to-apples comparison of trades across different markets and volatility regimes, and it's the standard way professional traders evaluate their performance.</p>
<p>With ATR mastered, you have completed the essential technical toolkit: RSI for momentum, MACD for trend confirmation, ATR for volatility, and moving averages for trend direction. Together, these four indicators form the core of most professional trading systems — used by both retail and institutional traders across the world.</p>
HTML,
        ],

    ],
];