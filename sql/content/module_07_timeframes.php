<?php
/**
 * Module 07 — Timeframes
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_07_timeframes.php
 */

return [
    'module' => [
        'level_slug' => 'foundation',
        'slug'       => 'timeframes',
        'title'      => 'Timeframes',
        'description'=> 'The same chart can look bullish on one timeframe and bearish on another. Timeframes are not just zoom levels — they define entirely different trading approaches, from scalping to position trading. Learn which timeframe suits which style, and how to combine them.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Identify every standard timeframe from M1 to MN\n" .
            "• Match a timeframe to your trading style and lifestyle\n" .
            "• Explain why the same chart can look bullish and bearish at once\n" .
            "• Read a market top-down across multiple timeframes",
        'sort_order' => 7,
    ],

    'lessons' => [

        [
            'slug'   => 'understanding-timeframes',
            'title'  => 'Understanding Timeframes',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define a timeframe and how it changes a chart\n" .
                "• List the standard timeframes from shortest to longest\n" .
                "• Explain why a chart can look bullish on one timeframe and bearish on another",
            'prerequisites' => 'Candlestick Charts',
            'sort_order' => 1,
            'summary' => 'A timeframe is the period each candle represents. A 1-hour candle shows one hour of price action; a daily candle shows an entire day. Different timeframes reveal different trends — and the same market can be trending up on one and down on another at the same time.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Every candle on a chart covers a specific period of time. If you're looking at a 1-hour chart, each candle shows what happened during one hour. Switch to a daily chart, and each candle covers an entire day. The price data is the same — you're just changing how it's sliced.</p>
<p>Think of it like a photo album. You can look at today's photos, this month's photos, or this year's photos. Each view tells a different story about the same life.</p>

<h2>Real-world analogy</h2>
<p>Imagine watching a film. In one scene, the character is crying — they've just lost their job. Zoom out to the whole film, and this is a story of success — they lost the job, then started a business and became wealthy. Both views are true. The scene shows the painful moment; the film shows the overall arc. Timeframes work the same way.</p>

<h2>Professional explanation</h2>
<p>A <strong>timeframe</strong> is the length of time each candle on a chart represents. Standard timeframes in Forex, from shortest to longest:</p>
<ul>
    <li><strong>M1</strong> — 1 minute per candle</li>
    <li><strong>M5</strong> — 5 minutes</li>
    <li><strong>M15</strong> — 15 minutes</li>
    <li><strong>M30</strong> — 30 minutes</li>
    <li><strong>H1</strong> — 1 hour</li>
    <li><strong>H4</strong> — 4 hours</li>
    <li><strong>D1</strong> — 1 day</li>
    <li><strong>W1</strong> — 1 week</li>
    <li><strong>MN</strong> — 1 month</li>
</ul>
<p>Some platforms also offer H2, H8, H12, or M3 as custom timeframes, but the nine above are the market standard.</p>

<h3>Why the same chart can look bullish and bearish at once</h3>
<p>Imagine EUR/USD is at 1.0850.</p>
<ul>
    <li>On the <strong>monthly</strong> chart, it's been in a downtrend from 1.2500 for 18 months.</li>
    <li>On the <strong>daily</strong> chart, it's been ranging between 1.0800 and 1.1000 for a month.</li>
    <li>On the <strong>hourly</strong> chart, it's in a strong uptrend from 1.0820 to 1.0850 over the past day.</li>
</ul>
<p>All three are accurate. The monthly says "sell," the daily says "wait," the hourly says "buy." These aren't contradictions — they're the same reality viewed at different scales.</p>

<h3>Each timeframe has a "personality"</h3>
<ul>
    <li><strong>Short timeframes (M1–M15)</strong> — lots of noise, frequent signals, small pip targets.</li>
    <li><strong>Medium (H1–H4)</strong> — the balance point between noise and trend clarity. Many retail traders live here.</li>
    <li><strong>Long (D1–W1)</strong> — clean trends, fewer signals, larger moves, wider stops.</li>
    <li><strong>Very long (MN)</strong> — macroeconomic perspective. Used mainly for context, not entries.</li>
</ul>

<h2>Factual context</h2>
<p>The Turtle Traders experiment — run by Richard Dennis and William Eckhardt in 1983 — famously settled a debate about whether trading could be taught. Dennis bet Eckhardt $1 million that a group of novices could be trained to trade profitably using a rule-based system. The Turtles were taught a <strong>daily-chart trend-following system</strong> — no short timeframes, no discretionary entries. Over five years, the group reportedly earned an average annual return of over 80%. Their success was built entirely on long-timeframe analysis and disciplined execution.</p>
<p>Ed Seykota, one of the original Market Wizards, said:</p>
<blockquote><strong>"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules."</strong></blockquote>
<p>Those rules work on any timeframe — but they work <em>best</em> on the timeframes that filter noise. That's why so many legendary trend-followers operated on daily or weekly charts.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Jumping between timeframes mid-trade.</strong> If you entered on H1, don't check the M1 to "see how it's doing." You'll panic over noise.</li>
    <li><strong>Trading M1 as a beginner.</strong> The M1 chart is 90% noise. Even experienced scalpers struggle with it. Start on H1 or higher.</li>
    <li><strong>Ignoring the higher timeframe.</strong> A bullish signal on H1 that contradicts a strong downtrend on the daily is usually a trap.</li>
</ul>

<h2>Advanced notes</h2>
<p>There's a well-known rule of thumb called the <strong>"4-6× rule"</strong> — each timeframe should be roughly 4 to 6 times longer than the one below it for multi-timeframe analysis to work. That's why the standard progression (M15 → H1 → H4 → D1) skips some intermediate steps. If you try to combine M5 with H4, the gap is too wide — the higher-timeframe signal won't correlate usefully with the lower. Stick to the standard ladder.</p>
HTML,
        ],

        [
            'slug'   => 'scalping',
            'title'  => 'Scalping (M1 – M15)',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define scalping and its key characteristics\n" .
                "• Understand the time and cost demands of scalping\n" .
                "• Explain why most retail scalpers lose money",
            'prerequisites' => 'Understanding Timeframes',
            'sort_order' => 2,
            'summary' => 'Scalping is the practice of taking many small trades on very short timeframes, aiming for 5–20 pip moves. It demands full-screen attention, fast execution, low spreads, and iron discipline. It is the most demanding style of trading — and the one most retail traders lose money on.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Scalpers trade on the fastest timeframes — 1-minute to 15-minute charts — aiming for very small price movements. A scalper might take 20 trades a day, holding each one for a few minutes. The goal is to harvest lots of tiny wins.</p>
<p>It sounds appealing — more trades, more chances. In practice it's the hardest style of trading because costs eat into every trade.</p>

<h2>Real-world analogy</h2>
<p>Think of a street food vendor who sells 500 small items a day at $2 each. The margin per item is tiny — the business works only because of volume. Scalping is the same: small profits per trade, but lots of them.</p>

<h2>Professional explanation</h2>
<p><strong>Scalping</strong> is a trading style that targets very small price movements — typically 5–20 pips — on timeframes from 1 minute to 15 minutes. Trades are held for seconds to minutes, rarely longer than an hour.</p>

<h3>Characteristics of scalping</h3>
<ul>
    <li><strong>Timeframes:</strong> M1, M5, M15</li>
    <li><strong>Hold time:</strong> seconds to minutes</li>
    <li><strong>Target per trade:</strong> 5–20 pips</li>
    <li><strong>Stop per trade:</strong> 5–15 pips</li>
    <li><strong>Trades per day:</strong> often 10–50</li>
    <li><strong>Focus:</strong> pure execution, no fundamental analysis</li>
</ul>

<h3>The math problem</h3>
<p>Consider a scalper on EUR/USD. Spread is 0.8 pips. Target is 8 pips, stop is 8 pips.</p>
<ul>
    <li>Winning trade: +8 pips, minus 0.8 spread = <strong>+7.2 pips net</strong></li>
    <li>Losing trade: −8 pips, minus 0.8 spread = <strong>−8.8 pips net</strong></li>
</ul>
<p>The spread taxes every trade. To break even, the scalper needs a win rate above 55%. To be profitable, closer to 60%. This is achievable, but difficult — and it gets harder the more trades are taken.</p>

<h3>Why it's so demanding</h3>
<ul>
    <li><strong>Speed:</strong> you must react in seconds. By the time you analyse, the move is over.</li>
    <li><strong>Costs:</strong> spread and commission become significant percentages of profit.</li>
    <li><strong>Fatigue:</strong> watching M1 for hours is mentally exhausting. Fatigue causes mistakes.</li>
    <li><strong>Over-trading:</strong> the temptation to take "just one more trade" is enormous.</li>
    <li><strong>News spikes:</strong> a single news release can wipe out hours of small profits in seconds.</li>
</ul>

<h3>What scalpers need</h3>
<ul>
    <li>A <strong>fast internet connection</strong> — even 200ms latency matters.</li>
    <li>A <strong>low-spread broker</strong> — an ECN with sub-pip spreads on majors.</li>
    <li>A <strong>quiet trading environment</strong> — no distractions.</li>
    <li><strong>Strict rules</strong> — pre-defined entries and exits, no improvising.</li>
    <li><strong>Physical stamina</strong> — most successful scalpers trade only 2–3 hours a day.</li>
</ul>

<h2>Factual context</h2>
<p>Scalping is popular in institutional trading too — many proprietary trading firms run scalping desks. But institutional scalpers trade with sub-penny spreads, direct market access, and colocated servers metres away from the exchange. The retail trader competing against them from a home internet connection has a significant structural disadvantage.</p>
<p>Studies by EU regulators (ESMA) found that retail traders using leveraged products on short timeframes had the highest loss rates — as high as 89% for some categories. The pattern is consistent across markets: the shorter the timeframe, the higher the failure rate. This doesn't mean scalping is impossible — but it means the odds are stacked against beginners.</p>
<p>Larry Hite, one of the Market Wizards, once said:</p>
<blockquote><strong>"I don't think you can consistently make money day trading. It's a zero-sum game and the costs are too high."</strong></blockquote>
<p>Hite's view is shared by many professionals. It doesn't mean no one scalps profitably — it means the bar is very high.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Starting with scalping.</strong> Beginners are drawn to M1 charts for "more opportunities." The reality is more noise, more costs, more emotional pressure.</li>
    <li><strong>Ignoring the spread.</strong> A 5-pip target with a 1-pip spread is really a 4-pip target. Always subtract the spread from your expected profit.</li>
    <li><strong>Trading scalping strategies on news days.</strong> Slippage on news can be several pips. Your 8-pip target becomes a 3-pip target — or a loss.</li>
    <li><strong>Scalping without a hard stop.</strong> Scalping loss runs are common. Without a stop, one bad trade can undo twenty good ones.</li>
</ul>

<h2>Advanced notes</h2>
<p>If you're determined to scalp, the most reliable variant is <strong>session-based scalping</strong> — only trading during high-liquidity windows (London open, London/NY overlap) when spreads are tightest and volume is highest. Never scalp during off-hours. And trade only one or two pairs — split attention is fatal on the fastest timeframes.</p>
HTML,
        ],

        [
            'slug'   => 'day-trading',
            'title'  => 'Day Trading (M15 – H1)',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define day trading and its key characteristics\n" .
                "• Explain how it differs from scalping\n" .
                "• Understand the lifestyle requirements of intraday trading",
            'prerequisites' => 'Scalping',
            'sort_order' => 3,
            'summary' => 'Day trading targets larger moves than scalping — typically 20–60 pips — on timeframes from 15 minutes to 1 hour. All positions are closed by the end of the trading session; nothing is held overnight. It is a middle ground between scalping and swing trading.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Day traders open and close every position within the same day. No overnight risk, no surprises while you sleep. They use 15-minute and 1-hour charts, hold trades for a few hours, and aim for 20–60 pip moves.</p>
<p>It's less frantic than scalping, more active than swing trading. For many retail traders, it's the sweet spot.</p>

<h2>Real-world analogy</h2>
<p>Think of a shop that opens at 9am and closes at 5pm. Everything bought and sold within those hours; nothing carried over to tomorrow. Day trading is the same discipline applied to price moves.</p>

<h2>Professional explanation</h2>
<p><strong>Day trading</strong> (also called intraday trading) is the practice of opening and closing trades within the same trading day. Positions are not held overnight.</p>

<h3>Characteristics of day trading</h3>
<ul>
    <li><strong>Timeframes:</strong> M15, M30, H1</li>
    <li><strong>Hold time:</strong> minutes to a few hours</li>
    <li><strong>Target per trade:</strong> 20–60 pips</li>
    <li><strong>Stop per trade:</strong> 15–40 pips</li>
    <li><strong>Trades per day:</strong> typically 2–8</li>
    <li><strong>Focus:</strong> intraday trends and momentum</li>
</ul>

<h3>Day trading vs scalping</h3>
<table>
    <thead><tr><th>Feature</th><th>Scalping</th><th>Day Trading</th></tr></thead>
    <tbody>
        <tr><td>Timeframe</td><td>M1–M15</td><td>M15–H1</td></tr>
        <tr><td>Hold time</td><td>Seconds–minutes</td><td>Minutes–hours</td></tr>
        <tr><td>Target</td><td>5–20 pips</td><td>20–60 pips</td></tr>
        <tr><td>Trades/day</td><td>10–50+</td><td>2–8</td></tr>
        <tr><td>Spread impact</td><td>High</td><td>Moderate</td></tr>
        <tr><td>Screen time</td><td>Constant</td><td>Periodic</td></tr>
    </tbody>
</table>

<h3>Why day trading avoids overnight risk</h3>
<p>Most major market-moving events happen during specific sessions (London open, US open, major news releases). By closing positions before the end of the session, day traders avoid:</p>
<ul>
    <li>Overnight gap risk (a position can gap against you overnight)</li>
    <li>Weekend risk (weekend news can move markets at Monday open)</li>
    <li>Swap charges (which accumulate on overnight positions)</li>
</ul>
<p>The trade-off is that day traders have to be at their screens during active sessions — usually the London and New York opens if trading majors.</p>

<h3>Session-based day trading</h3>
<p>Most day traders specialise in one session:</p>
<ul>
    <li><strong>London session (07:00–16:00 UTC)</strong> — highest liquidity for EUR and GBP pairs.</li>
    <li><strong>New York session (13:00–22:00 UTC)</strong> — highest volatility for USD pairs, especially around US economic releases.</li>
    <li><strong>London/NY overlap (13:00–16:00 UTC)</strong> — the highest-volume window of the day. Most day trading happens here.</li>
</ul>

<h2>Factual context</h2>
<p>The day-trading phenomenon exploded during the 1990s dot-com era, when online brokers made real-time quotes and electronic execution available to retail traders. Brad Barber and Terrance Odean's landmark 2000 study <em>"Trading Is Hazardous to Your Wealth"</em> analysed 66,465 US households and found that the most active traders (day traders) underperformed the market by 6.5% annually, while the average household underperformed by 0.7%. Their follow-up study, <em>"The Behavior of Individual Investors,"</em> showed the pattern persisting worldwide.</p>
<p>The lesson isn't "day trading doesn't work." It's "day trading requires discipline, edge, and low costs — otherwise, it's an expensive hobby." Traders with a defined strategy, tight risk management, and a low-cost broker have a genuine chance. Traders who "just watch the chart and click" do not.</p>
<p>Paul Tudor Jones once said:</p>
<blockquote><strong>"Every day I assume every position I have is wrong."</strong></blockquote>
<p>That mindset — always questioning, always protecting — is what makes day trading survivable.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading all day instead of specific windows.</strong> The best day traders trade 2–4 hours during the highest-volatility sessions and stop.</li>
    <li><strong>Not having a hard stop-time.</strong> Trading past a certain hour — when focus fades — causes costly mistakes.</li>
    <li><strong>Revenge trading after a loss.</strong> Because day trading has more decisions per day, emotional spirals happen faster.</li>
    <li><strong>Trading the same pair for both morning and afternoon sessions.</strong> Session characteristics change. A strategy that worked in London may not work in New York.</li>
</ul>

<h2>Advanced notes</h2>
<p>Many day traders combine a higher-timeframe bias (daily or H4 trend direction) with a lower-timeframe entry (H1 setup). This filters out trades that fight the larger trend, boosting win rates without needing to over-optimise the strategy itself. We cover this in detail in the multi-timeframe lesson later in this module.</p>
HTML,
        ],

        [
            'slug'   => 'swing-trading',
            'title'  => 'Swing Trading (H4 – D1)',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define swing trading and its key characteristics\n" .
                "• Explain the lifestyle advantages over day trading\n" .
                "• Understand how swing traders manage overnight and weekend risk",
            'prerequisites' => 'Day Trading',
            'sort_order' => 4,
            'summary' => 'Swing trading holds positions for days to weeks on the H4 and daily charts, targeting 100–300 pip moves. It requires far less screen time than day trading, making it the most practical style for people with jobs. Most successful retail traders eventually settle here.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Swing traders don't watch charts all day. They identify a setup, place an order, set a stop and a target, and walk away. Positions are held for days or weeks, targeting larger moves than day traders.</p>
<p>It's slower, calmer, and fits around a normal life. For most retail traders, this is the most realistic path to consistent results.</p>

<h2>Real-world analogy</h2>
<p>Think of a fisherman who sets his nets in the morning and checks them in the evening. He doesn't sit watching the water all day. Swing traders work the same way — plan, place, walk away, review.</p>

<h2>Professional explanation</h2>
<p><strong>Swing trading</strong> is a style that holds positions for multiple days or weeks, targeting medium-term price swings. It uses the H4 and daily charts primarily.</p>

<h3>Characteristics of swing trading</h3>
<ul>
    <li><strong>Timeframes:</strong> H4, D1 (with H1 for entry timing)</li>
    <li><strong>Hold time:</strong> 1 day to several weeks</li>
    <li><strong>Target per trade:</strong> 100–300+ pips</li>
    <li><strong>Stop per trade:</strong> 50–150 pips</li>
    <li><strong>Trades per month:</strong> typically 4–15</li>
    <li><strong>Screen time:</strong> 30–60 minutes per day</li>
</ul>

<h3>Why swing trading suits most retail traders</h3>
<ol>
    <li><strong>Fits around a job.</strong> You can check charts once in the morning and once in the evening — no need to stare at screens all day.</li>
    <li><strong>Lower transaction costs.</strong> Fewer trades = less spread paid = less commission.</li>
    <li><strong>Less emotional pressure.</strong> No rapid decision-making. You have time to think.</li>
    <li><strong>Bigger moves per trade.</strong> 100-pip wins dwarf 10-pip scalps. A single winning swing trade can outweigh a week of scalping.</li>
    <li><strong>Less noise.</strong> Daily charts filter out most of the M1 noise that trips up day traders.</li>
</ol>

<h3>Handling overnight and weekend risk</h3>
<p>Swing traders hold through overnight sessions and sometimes weekends. To manage the added risk:</p>
<ul>
    <li><strong>Smaller position sizes.</strong> Wider stops are needed for longer timeframes, so position sizes must shrink proportionally.</li>
    <li><strong>News awareness.</strong> Major central bank meetings and NFP are scheduled — check the calendar weekly and reduce exposure around them.</li>
    <li><strong>Weekend risk.</strong> Some swing traders close positions Friday afternoon to avoid weekend news. Others accept the risk and rely on wider stops.</li>
    <li><strong>Swap cost.</strong> Every overnight position pays or receives swap. Long-held trades can accumulate significant swap costs.</li>
</ul>

<h3>The typical swing trading cycle</h3>
<ol>
    <li><strong>Weekend:</strong> analyse daily charts, identify key levels, plan potential trades.</li>
    <li><strong>Monday–Friday morning:</strong> place limit orders or wait for setups to trigger.</li>
    <li><strong>Evening:</strong> review open positions, adjust stops if needed, log new trades.</li>
    <li><strong>Friday:</strong> review the week, decide whether to hold or close before the weekend.</li>
</ol>
<p>That's roughly 5 hours a week of screen time — versus 25+ hours for a day trader.</p>

<h2>Factual context</h2>
<p>Some of the most successful traders in history operated on swing timeframes. The Turtle Traders, taught by Richard Dennis in 1983, traded daily-chart breakouts of 20- and 55-day ranges. Their system — a pure swing strategy — reportedly generated an average annual return of over 80% over five years.</p>
<p>Bill Dunn, founder of Dunn Capital Management, has run a trend-following program since 1974 that operates primarily on daily and weekly timeframes. As of 2023, Dunn Capital manages over $2 billion, and its long-term performance is widely cited as evidence that long-timeframe trend following has persistent edge.</p>
<p>Ed Seykota, when asked about timeframes, said:</p>
<blockquote><strong>"The long-term trend is your friend, and the short-term trend is your enemy."</strong></blockquote>
<p>Many professional traders — regardless of their personal style — agree that beginners should start on higher timeframes where noise is reduced and discipline is easier to develop.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Checking charts too often.</strong> Swing trades don't need hourly monitoring. Over-checking leads to premature exits.</li>
    <li><strong>Using day-trade-sized stops.</strong> A 20-pip stop on a daily chart trade is almost guaranteed to be hit by normal noise. Use 100+ pips on daily trades.</li>
    <li><strong>Not adjusting for swap.</strong> A trade held for two weeks may pay more in swap than it earns in profit if you've picked the wrong side.</li>
    <li><strong>Not respecting overnight gap risk.</strong> Weekend news can move the open significantly. Wide stops and small positions are how you survive this.</li>
</ul>

<h2>Advanced notes</h2>
<p>Many swing traders use <strong>weekly and monthly bias</strong> to filter their daily setups — they trade only in the direction of the higher-timeframe trend, taking daily-chart pullback entries. This combines the safety of higher-timeframe context with the precision of daily entries. It's the format we'll formalise in the multi-timeframe lesson in this module.</p>
HTML,
        ],

        [
            'slug'   => 'position-trading',
            'title'  => 'Position Trading (W1 – MN)',
            'difficulty' => 'beginner',
            'estimated_duration' => 9,
            'learning_objectives' =>
                "• Define position trading and its key characteristics\n" .
                "• Explain how it differs fundamentally from other styles\n" .
                "• Recognise which investors and funds use this approach",
            'prerequisites' => 'Swing Trading',
            'sort_order' => 5,
            'summary' => 'Position trading holds trades for months or years, using weekly and monthly charts. It requires patience, large capital, and a view based on macro fundamentals. Most individual retail traders don\'t have the temperament for it — but many institutions do.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Position traders make a few decisions a year, not a few a day. They look at weekly and monthly charts, and they hold positions for months or years. Think of it like buying a house — you commit, you wait, you sell when the thesis plays out.</p>
<p>This is the approach of many hedge funds, sovereign wealth funds, and large institutions. It's slow, patient, and grounded in macroeconomic analysis rather than technical patterns.</p>

<h2>Real-world analogy</h2>
<p>Think of a long-term real estate investor. They buy a property when they believe the area will appreciate, hold for years, and sell when the thesis is complete. They don't care about daily price fluctuations. Position trading works the same way with currencies.</p>

<h2>Professional explanation</h2>
<p><strong>Position trading</strong> is the practice of holding trades for months or years, using weekly and monthly charts. Decisions are driven by macroeconomic views rather than short-term price action.</p>

<h3>Characteristics of position trading</h3>
<ul>
    <li><strong>Timeframes:</strong> W1, MN</li>
    <li><strong>Hold time:</strong> months to years</li>
    <li><strong>Target per trade:</strong> 500–3,000+ pips</li>
    <li><strong>Stop per trade:</strong> 300–1,000+ pips</li>
    <li><strong>Trades per year:</strong> typically 2–10</li>
    <li><strong>Screen time:</strong> hours per month</li>
</ul>

<h3>What position traders analyse</h3>
<ul>
    <li><strong>Interest rate differentials</strong> — currencies with higher rates tend to attract capital.</li>
    <li><strong>Central bank policy</strong> — hawkish or dovish stances drive multi-month trends.</li>
    <li><strong>Inflation and growth data</strong> — GDP, CPI, employment trends.</li>
    <li><strong>Political and geopolitical risk</strong> — elections, trade disputes, wars.</li>
    <li><strong>Capital flows</strong> — where global money is moving.</li>
</ul>
<p>Technical analysis plays a smaller role — usually just for entry timing near major support or resistance.</p>

<h3>Why this style is dominated by institutions</h3>
<p>Position trading requires:</p>
<ul>
    <li><strong>Capital to survive drawdowns.</strong> Holding a 1,000-pip loss for months requires both the account size and the psychological resilience.</li>
    <li><strong>A macro thesis.</strong> You can't trade this style with just chart patterns.</li>
    <li><strong>Patience.</strong> Doing nothing for weeks is a skill most retail traders never develop.</li>
    <li><strong>Swap management.</strong> A position held for a year pays or receives 365 days of swap — this can be hugely positive (carry trade) or hugely negative.</li>
</ul>

<h3>The carry trade — the classic position trade</h3>
<p>The best-known position trade strategy is the <strong>carry trade</strong>: borrow a low-interest currency and buy a high-interest currency, collect the interest differential, and hold. Between 2000 and 2007, the carry trade (borrowing yen, buying Australian or New Zealand dollars) produced consistent returns for years.</p>
<p>It also blew up spectacularly in 2008 when the yen surged and wiped out years of accumulated swap. This is the risk of position trading: a single macro shock can destroy a multi-year thesis.</p>

<h2>Factual context</h2>
<p>George Soros' famous 1992 trade against the British pound was a position trade. He held a short GBP position for months, betting that the Bank of England could not maintain the pound's peg to the European Exchange Rate Mechanism. When the peg broke on 16 September 1992 ("Black Wednesday"), Soros reportedly made $1 billion in a single day. The thesis was macro; the execution was patient.</p>
<p>Stanley Druckenmiller, who executed the trade with Soros, later said:</p>
<blockquote><strong>"We had a huge short position in sterling. The Bank of England was buying pounds to defend the currency, and we were just selling into their buying."</strong></blockquote>
<p>That's position trading at the highest level — a macro view, a large position, and patience to wait for the thesis to play out.</p>
<p>For contrast, most retail traders can't hold positions through drawdowns like that. Druckenmiller had the capital, the conviction, and the infrastructure to do so. Position trading is not impossible for retail, but it demands the same qualities.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trying to position trade on a small account.</strong> A 1,000-pip stop on a 10,000-unit position is a $100 loss. If your account is $1,000, that's 10% in one trade. Position trading needs larger capital.</li>
    <li><strong>Confusing position trading with "just holding a losing trade."</strong> Position traders have theses. Retail traders who "hold through anything" often just don't want to admit a mistake.</li>
    <li><strong>Ignoring swap costs.</strong> A year-long trade pays 365 days of swap. If you're on the wrong side of the interest-rate differential, the swap cost can exceed the profit.</li>
    <li><strong>No exit plan.</strong> Position trades need a target, a stop, and a thesis-invalidation level. Without these, "long-term holding" is just avoiding decisions.</li>
</ul>

<h2>Advanced notes</h2>
<p>The most successful position traders combine macro views with technical entry points. They'll have a thesis like "the ECB will keep rates low while the Fed hikes" (macro), then wait for a specific technical setup (like a monthly break of support) before entering. This combination of top-down macro with bottom-up technicals is what distinguishes professional position trading from retail "buy and hope."</p>
HTML,
        ],

        [
            'slug'   => 'multi-timeframe-analysis',
            'title'  => 'Multi-Timeframe Analysis',
            'difficulty' => 'beginner',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Explain why multi-timeframe analysis is essential\n" .
                "• Apply top-down analysis across three timeframes\n" .
                "• Recognise and resolve timeframe conflicts",
            'prerequisites' => 'Position Trading',
            'sort_order' => 6,
            'summary' => 'The best trades align across multiple timeframes. A higher timeframe provides the direction, a middle timeframe identifies the setup, and a lower timeframe times the entry. When the three disagree, you have a conflict — and the higher timeframe wins.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Trading on one timeframe is like navigating with a single map. You might find the right street, but you'll miss the fact that it's a one-way. Multi-timeframe analysis uses three maps — a country map, a city map, and a street map — so you always know the big picture, the local context, and the exact entry point.</p>

<h2>Real-world analogy</h2>
<p>Think of military strategy. The general looks at the whole theatre of war. The colonel looks at the specific battle. The soldier looks at the specific objective. Each level of the hierarchy informs the one below. Trading works the same way: the daily chart is the general, the H4 is the colonel, and the H1 is the soldier.</p>

<h2>Professional explanation</h2>
<p><strong>Multi-timeframe analysis</strong> is the practice of reviewing price data on several timeframes to build a layered view of the market before trading.</p>

<h3>The three-timeframe framework</h3>
<p>A common structure uses three timeframes, each with a specific role:</p>

<h4>1. Higher timeframe (bias)</h4>
<p>Purpose: determine the overall trend direction.</p>
<p>Common choices: Daily or Weekly.</p>
<p>Example question: "Is the market in an uptrend, downtrend, or range?"</p>

<h4>2. Middle timeframe (setup)</h4>
<p>Purpose: find a setup that aligns with the higher-timeframe bias.</p>
<p>Common choices: H4 or H1.</p>
<p>Example question: "Is price pulling back to a level where I can enter in the direction of the trend?"</p>

<h4>3. Lower timeframe (trigger)</h4>
<p>Purpose: time the exact entry with a confirmation signal.</p>
<p>Common choices: M15 or M5.</p>
<p>Example question: "Has price given a bullish candle pattern or structure break at the level I identified?"</p>

<h3>Worked example — a EUR/USD long trade</h3>
<ol>
    <li><strong>Daily chart:</strong> strong uptrend, price above the 50 EMA, making higher highs and higher lows. Bias: bullish.</li>
    <li><strong>H4 chart:</strong> price has pulled back to a prior resistance-turned-support at 1.0850. Setup: buy the pullback.</li>
    <li><strong>M15 chart:</strong> at 1.0850, price forms a bullish engulfing candle followed by a break above a small consolidation. Trigger: enter long at 1.0855.</li>
    <li><strong>Stop:</strong> just below the recent swing low at 1.0830 (25 pips).</li>
    <li><strong>Target:</strong> the previous daily high at 1.0950 (95 pips). Risk-reward: 1:3.8.</li>
</ol>
<p>Every level of the hierarchy agrees. This is what a high-probability setup looks like.</p>

<h3>Handling timeframe conflicts</h3>
<p>What if the daily chart says "up" but the H4 says "down"?</p>
<p>Rule: <strong>the higher timeframe wins.</strong> If the daily is in an uptrend, a bearish H4 signal is probably just a pullback within that uptrend — an opportunity to buy, not to sell. If the daily is in a downtrend, a bullish H1 signal is likely a bounce within the bigger down move.</p>
<p>There are two ways to handle conflict:</p>
<ol>
    <li><strong>Wait.</strong> Do nothing until the timeframes align.</li>
    <li><strong>Trade with the higher timeframe.</strong> Treat the lower-timeframe signal as a trigger for a higher-timeframe trade — e.g., a bearish H4 signal within a daily uptrend becomes a "wait for the pullback to end" signal, not a short entry.</li>
</ol>

<h3>The 4–6× rule</h3>
<p>For multiple timeframes to work together cleanly, each should be roughly 4–6 times longer than the one below. That's why the standard combinations are:</p>
<ul>
    <li>Monthly / Weekly / Daily</li>
    <li>Weekly / Daily / H4</li>
    <li>Daily / H4 / H1</li>
    <li>H4 / H1 / M15</li>
</ul>
<p>If the gap is too wide — say, Monthly and M15 — the higher timeframe provides no useful context for the lower. Stick to the ladder.</p>

<h2>Factual context</h2>
<p>Multi-timeframe analysis was formalised in the West largely through the work of traders like Robert Krausz (who coined the term "Multiple Time Frame" in the 1990s) and later popularised by many educators. The underlying principle — that price behaves differently at different scales — has roots in fractal geometry.</p>
<p>Benoît Mandelbrot, the mathematician who developed fractal geometry, famously studied financial markets and observed that "price charts look statistically similar at all scales." A minute chart and a monthly chart have the same structural qualities — they just cover different periods. This is the mathematical basis for multi-timeframe analysis.</p>
<p>Stanley Druckenmiller, reflecting on his approach, said:</p>
<blockquote><strong>"I like to look at the big picture first, then work my way down. If the daily and weekly are aligned, the trade has a much higher probability."</strong></blockquote>
<p>That's the practical wisdom of multi-timeframe analysis in one sentence.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Only trading one timeframe.</strong> You'll be constantly caught off-guard by moves that are obvious on higher timeframes.</li>
    <li><strong>Trading the lower timeframe against the higher.</strong> The daily says "downtrend," you see a bullish M15, you buy. You're fighting the tide.</li>
    <li><strong>Overloading your screen.</strong> Five or six timeframes at once creates analysis paralysis. Three is enough.</li>
    <li><strong>Switching your top-down framework mid-trade.</strong> If you entered based on the daily trend, don't panic because the M5 looks bearish. Stick to the timeframe you planned with.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some traders use a "timeframe alignment score" — a simple count of how many timeframes agree on direction. If the weekly, daily, H4, and H1 are all bullish, that's a score of 4/4 — a high-conviction setup. If only two of four agree, the setup is mixed and should be traded smaller (or skipped). This formalisation turns multi-timeframe analysis from intuition into a repeatable process. You'll see this approach applied in advanced strategy modules later in the curriculum.</p>
HTML,
        ],

    ],
];