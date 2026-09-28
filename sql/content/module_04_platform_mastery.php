<?php
/**
 * Module 04 — Trading Platform Mastery
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_04_platform_mastery.php
 */

return [
    'module' => [
        'level_slug' => 'foundation',
        'slug'       => 'platform-mastery',
        'title'      => 'Trading Platform Mastery',
        'description'=> 'The mechanics of placing, modifying, and closing trades. Knowing the buttons is not enough — you need to know which order type suits which situation, and why the wrong choice can cost you money even when your analysis is correct.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Read a live quote and understand every number on screen\n" .
            "• Distinguish market orders from pending orders\n" .
            "• Use stop-losses and take-profits correctly\n" .
            "• Read your account dashboard without guessing what equity, balance, and margin mean",
        'sort_order' => 4,
    ],

    'lessons' => [

        [
            'slug'   => 'getting-started-with-your-platform',
            'title'  => 'Getting Started with Your Trading Platform',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Identify the main areas of a trading interface\n" .
                "• Read a live quote correctly\n" .
                "• Understand what happens between clicking Buy and seeing a filled position",
            'prerequisites' => 'Base Currency and Quote Currency',
            'sort_order' => 1,
            'summary' => 'A trading platform is a window into the market. Before you place a single trade, you should be able to point at any element on the screen and say what it does. This lesson walks you through the interface from top to bottom.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>A trading platform is just a screen that shows you prices and buttons that let you act on them. It sounds simple, but there are dozens of numbers and controls, and each one means something specific. Learning the layout is like learning the dashboard of a car — once you know what every dial does, driving gets easier.</p>

<h2>Real-world analogy</h2>
<p>Think of a cockpit. A pilot sees altitude, speed, fuel, heading, and dozens of smaller gauges. To a passenger, it's noise. To a pilot, it's a story about what the plane is doing right now. Your trading platform is the same — the numbers tell a story, if you know how to read them.</p>

<h2>The five core areas of any trading platform</h2>

<h3>1. The watchlist</h3>
<p>A list of instruments you're monitoring — EUR/USD, GBP/USD, USD/JPY, gold, and any other pairs you follow. This is where you scan for opportunities. A good watchlist is short. Ten pairs you actually understand beat fifty you don't.</p>

<h3>2. The chart</h3>
<p>The visual representation of price over time. This is where analysis happens. Every chart on every platform shows the same information — open, high, low, close for each period — but different platforms present it differently. In AlphaEdge, you'll find chart access through the Markets and Multi-Chart pages.</p>

<h3>3. The order ticket</h3>
<p>The form where you enter a trade. It contains:</p>
<ul>
    <li><strong>Symbol</strong> — which pair you're trading</li>
    <li><strong>Side</strong> — Buy (long) or Sell (short)</li>
    <li><strong>Order type</strong> — Market, Limit, or Stop</li>
    <li><strong>Quantity</strong> — the lot size</li>
    <li><strong>Stop loss</strong> (optional) — the price where you'll exit if wrong</li>
    <li><strong>Take profit</strong> (optional) — the price where you'll exit if right</li>
</ul>

<h3>4. The positions panel</h3>
<p>Every open trade you currently hold. Shows symbol, direction, size, entry price, current price, and running profit or loss (P&L). This is your "what's happening right now" view.</p>

<h3>5. The account dashboard</h3>
<p>Balance, equity, margin, free margin, and margin level. We'll break these down in a dedicated lesson later in this module.</p>

<h2>Reading a live quote</h2>
<p>A typical EUR/USD quote looks like this:</p>
<pre>
    BID          ASK
  1.08503      1.08511
</pre>
<ul>
    <li><strong>Bid</strong> — the price a buyer will pay you. You sell here.</li>
    <li><strong>Ask</strong> — the price a seller will accept. You buy here.</li>
    <li><strong>Spread</strong> — the gap. Here it's 0.8 pips, the broker's cut.</li>
</ul>
<p>Notice the bid is always lower than the ask. If you buy at the ask and immediately sell at the bid, you lose the spread. That's the market's cost of entry. Every trader pays it.</p>

<h2>What happens when you click Buy</h2>
<ol>
    <li>Your order is sent to the broker's servers.</li>
    <li>The broker (or its liquidity provider) matches your order with a counterparty.</li>
    <li>You receive a fill confirmation with the exact price and time.</li>
    <li>Your position appears in the positions panel.</li>
    <li>Your cash balance decreases by the margin requirement (not the position size).</li>
</ol>
<p>From that moment, your P&L moves tick by tick with the market. You can close at any time during market hours.</p>

<h2>Factual context</h2>
<p>Ed Seykota — one of the original "Market Wizards" featured in Jack Schwager's 1989 book — famously said:</p>
<blockquote><strong>"The elements of good trading are: (1) cutting losses, (2) cutting losses, and (3) cutting losses. If you can follow these three rules, you may have a chance."</strong></blockquote>
<p>Seykota's point is that everything else — analysis, indicators, platform features — is secondary to the discipline of exiting losing trades. Your platform makes exiting easy. Your psychology makes it hard. Know both.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Skipping the demo phase.</strong> Always place 20–30 practice trades before risking real capital. The platform is simple; the discipline isn't.</li>
    <li><strong>Confusing the watchlist with the market.</strong> The watchlist shows what you're watching. The market is bigger than any list.</li>
    <li><strong>Assuming fill is instant.</strong> On volatile days or illiquid pairs, your order may take seconds to fill, or fill at a worse price (slippage).</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional platforms (Bloomberg Terminal, Reuters Eikon, TT) have more features than retail platforms — depth-of-market views, order book ladders, algorithmic execution. But the concepts are identical. If you understand the five areas above, you can sit down at any institutional terminal and know what you're looking at.</p>
HTML,
        ],

        [
            'slug'   => 'market-vs-pending-orders',
            'title'  => 'Market Orders vs Pending Orders',
            'difficulty' => 'beginner',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Define market, limit, and stop orders\n" .
                "• Explain when to use each type\n" .
                "• Recognise the trade-offs of each order type",
            'prerequisites' => 'Getting Started with Your Trading Platform',
            'sort_order' => 2,
            'summary' => 'A market order executes immediately at the best available price. A limit order waits for a better price. A stop order triggers when price hits a specified level. Each has a purpose — and the wrong choice costs you money even when you are right about direction.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine ordering food at a restaurant. You can say "bring me whatever's ready now" (market order), or "only if the steak is under $30" (limit order), or "if they run out of chicken, order the fish" (stop order). Same kitchen, different instructions.</p>
<p>Trading platforms offer the same three options. Each is designed for a specific situation.</p>

<h2>Real-world analogy</h2>
<p>Market order = "buy now, whatever the price." Limit order = "buy only if the price drops to my level." Stop order = "buy only if the price breaks above my level." Three different goals, three different order types.</p>

<h2>Professional explanation</h2>

<h3>Market order</h3>
<p>Executes <strong>immediately</strong> at the best available price. You don't specify a price; you accept whatever the market offers right now.</p>
<ul>
    <li><strong>Use when:</strong> speed matters more than a perfect price. Breaking news, urgent entries, exits on stops.</li>
    <li><strong>Pros:</strong> guaranteed fill (in liquid markets).</li>
    <li><strong>Cons:</strong> price is not guaranteed — slippage can occur.</li>
</ul>
<p>Example: you click Buy on EUR/USD, and it fills at 1.08511 instead of the 1.08510 you saw a second ago. One pipette of slippage. Normal.</p>

<h3>Limit order</h3>
<p>Places a pending order that executes <strong>only at your specified price or better</strong>. If the market never reaches that price, the order never fills.</p>
<ul>
    <li><strong>Use when:</strong> you want a specific entry and you're willing to wait.</li>
    <li><strong>Pros:</strong> price is guaranteed. No slippage against you.</li>
    <li><strong>Cons:</strong> fill is not guaranteed. The market may never reach your level.</li>
</ul>
<p>Example: EUR/USD is at 1.0850, and you want to buy if it dips to 1.0840. You place a limit buy at 1.0840. If the price drops there, you're filled. If it turns around and rallies instead, you miss the trade entirely.</p>

<h3>Stop order</h3>
<p>Places a pending order that triggers when price <strong>reaches or passes a specified level</strong>, then executes at market.</p>
<ul>
    <li><strong>Use when:</strong> you want to enter on a breakout, or you want to exit on adverse movement.</li>
    <li><strong>Pros:</strong> automatic reaction when price hits a level.</li>
    <li><strong>Cons:</strong> because it becomes a market order when triggered, slippage is possible.</li>
</ul>
<p>Example: EUR/USD is at 1.0850, and you want to buy if it breaks above 1.0860 (a resistance level). You place a stop buy at 1.0860. When price hits 1.0860, the order triggers and executes at market — likely a pip or two above 1.0860.</p>

<h2>Comparison table</h2>
<table>
    <thead><tr><th>Order type</th><th>Fill price</th><th>Fill guaranteed?</th><th>Best for</th></tr></thead>
    <tbody>
        <tr><td>Market</td><td>Best available now</td><td>Yes (in liquid markets)</td><td>Urgent entries/exits</td></tr>
        <tr><td>Limit</td><td>Your specified price or better</td><td>No</td><td>Patient entries at a level</td></tr>
        <tr><td>Stop</td><td>Market price at trigger</td><td>Yes, once triggered</td><td>Breakout entries, protective exits</td></tr>
    </tbody>
</table>

<h2>Factual context</h2>
<p>Jesse Livermore wrote in <em>Reminiscences of a Stock Operator</em>:</p>
<blockquote><strong>"There is a time to go long, a time to go short, and a time to go fishing."</strong></blockquote>
<p>Limit orders are the trading equivalent of "going fishing." You set a level, walk away, and let the market come to you. This is often the more profitable approach — because trading with patience is far more reliable than chasing. Livermore's edge came from waiting for the right moment, not from forcing trades.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Using market orders in illiquid conditions.</strong> During news, weekend opens, or exotic pairs, market orders can fill several pips away from what you expected.</li>
    <li><strong>Placing a limit order too close to current price.</strong> If the price barely needs to move, the order triggers immediately — you might as well have used a market order.</li>
    <li><strong>Assuming a stop order protects you.</strong> A stop <em>entry</em> order opens a position. A stop <em>loss</em> order closes one. Both are types of stop orders, but they do opposite things.</li>
</ul>

<h2>Advanced notes</h2>
<p>Advanced order types exist but are less common in Forex:</p>
<ul>
    <li><strong>OCO (One-Cancels-Other)</strong> — two orders where filling one cancels the other. Useful for range trades.</li>
    <li><strong>Trailing stop</strong> — a stop-loss that moves with price in your favour, locking in profits as the trade runs.</li>
    <li><strong>IOC / FOK</strong> — Immediate-Or-Cancel and Fill-Or-Kill. Institutional order types that either fill instantly or cancel. Rarely needed for retail.</li>
</ul>
<p>For 95% of your trading as a beginner, market, limit, and stop-loss orders cover everything you need.</p>
HTML,
        ],

        [
            'slug'   => 'stop-loss-orders',
            'title'  => 'Stop-Loss Orders (And Why They Save Accounts)',
            'difficulty' => 'beginner',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Explain what a stop-loss is and how it works\n" .
                "• Place a stop-loss at a level based on structure, not emotion\n" .
                "• Calculate position size from stop distance",
            'prerequisites' => 'Market Orders vs Pending Orders',
            'sort_order' => 3,
            'summary' => 'A stop-loss is an order that closes your position automatically if price moves against you by a defined amount. It is the single most important risk tool available to a trader. Without one, a single bad trade can undo months of profits.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>A stop-loss is a safety net. It says to the market: "If this trade goes wrong by this much, close it and take the loss." You decide the level in advance. The market enforces it. This is the most important risk-management tool in all of trading.</p>

<h2>Real-world analogy</h2>
<p>Think of a car's airbag. You never plan to use it. But if you crash, it's the difference between walking away and something much worse. A stop-loss is your airbag — you hope you never need it, but when you do, it saves the account.</p>

<h2>Professional explanation</h2>
<p>A <strong>stop-loss</strong> (often abbreviated SL) is an order placed alongside your trade that closes the position if price moves a specified distance against you. Once placed, it stays active until either triggered or cancelled by you.</p>

<h3>How it works — example</h3>
<ul>
    <li>You buy EUR/USD at 1.0850.</li>
    <li>You set a stop-loss at 1.0820 (30 pips below).</li>
    <li>EUR/USD drops to 1.0820 — your stop triggers, the position closes at market, and you've lost 30 pips.</li>
</ul>
<p>You decided that loss in advance. The market enforced it. No hesitation. No "just this once." That's why stops work.</p>

<h3>Where to place a stop-loss</h3>
<p>A stop-loss should be placed at a price where, if hit, the <em>reason you entered the trade is no longer valid</em>. Not at an arbitrary distance. Not at a round number. At a structurally meaningful level.</p>
<p>Examples:</p>
<ul>
    <li>If you bought a breakout above resistance, your stop belongs just below that resistance — because a return below it means the breakout failed.</li>
    <li>If you bought a pullback to support, your stop belongs just below that support.</li>
    <li>If you bought based on a moving average, your stop belongs on the other side of that MA.</li>
</ul>
<p>Placement is a technical decision, not a dollar amount. Never say "I'll risk $50." Say "my stop is 30 pips below the level, and I'll size the trade so $50 is what I lose."</p>

<h3>Position size follows from stop distance</h3>
<p>Once you know your stop distance in pips and your maximum tolerable loss in dollars, position size is a simple calculation:</p>
<p><code>Lot size = (Account × Risk %) / (Stop pips × Pip value per lot)</code></p>
<p>For a $10,000 account risking 1% ($100) with a 25-pip stop on EUR/USD:</p>
<ul>
    <li>Pip value per standard lot = $10</li>
    <li>Pip value needed per pip = $100 / 25 = $4</li>
    <li>Lot size = $4 / $10 = 0.4 standard lots</li>
</ul>
<p>The stop determines the size. Not the other way round.</p>

<h2>Factual context</h2>
<p>Paul Tudor Jones, one of the greatest macro traders of all time, famously said:</p>
<blockquote><strong>"Risk control is the most important thing in trading. If you have a losing position that is making you uncomfortable, the solution is very simple: Get out, because you can always get back in."</strong></blockquote>
<p>Jones' $1,000+ per-hour mantra was "protect, protect, protect." He always knew his maximum loss before entering. This is the same discipline a stop-loss enforces at the platform level — but it only works if you actually place one and don't move it against your position.</p>
<p>Studies by the EU's securities regulator (ESMA) between 2018 and 2022 found that 74–89% of retail CFD traders lose money. Many of those losses could have been smaller with disciplined stop-loss use.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Not placing a stop at all.</strong> The single most common cause of blown accounts. "I'll watch it closely" is not a risk management plan.</li>
    <li><strong>Moving the stop further away.</strong> When a stop is hit and you move it to "give the trade room," you have no stop. You have hope.</li>
    <li><strong>Placing stops at round numbers.</strong> Stops at 1.0800, 1.0850, 1.0900 sit where everyone else's stops sit. Price often sweeps these levels before continuing. Place stops just <em>beyond</em> round numbers, not on them.</li>
    <li><strong>Setting stops too tight.</strong> If a stop is 5 pips away on a pair with 30-pip daily noise, you'll be stopped out by normal market movement, not by an actual reversal.</li>
</ul>

<h2>Advanced notes</h2>
<p>Advanced traders often use <strong>ATR-based stops</strong> — placing the stop at 1.5× or 2× the Average True Range, which dynamically adjusts to current volatility. In calm markets, stops are closer. In volatile markets, they're further. This prevents the "too tight stop" problem automatically. We cover ATR in detail in a later module, but the principle is worth knowing now: the stop should reflect the market's own volatility, not a fixed pip distance.</p>
HTML,
        ],

        [
            'slug'   => 'take-profit-orders',
            'title'  => 'Take-Profit Orders',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Explain what a take-profit is\n" .
                "• Decide where to place TP based on structure and R:R\n" .
                "• Explain why some traders prefer to manage exits manually",
            'prerequisites' => 'Stop-Loss Orders',
            'sort_order' => 4,
            'summary' => 'A take-profit is an order that closes your position automatically when it reaches a target price. It removes the temptation to give back gains and enforces exit discipline. Not every trade needs one — but every trade needs a plan for exit.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>A take-profit is the opposite of a stop-loss. Where a stop closes your losing trade, a take-profit closes your winning one. You decide in advance how much profit is enough, and the platform closes it for you when price gets there.</p>

<h2>Real-world analogy</h2>
<p>Imagine selling a house. You could set an asking price at $500,000 and hold firm. Or you could accept whatever someone offers. A take-profit is like saying "when I get $500,000, I'm out." It removes the emotional back-and-forth when the offers come in.</p>

<h2>Professional explanation</h2>
<p>A <strong>take-profit</strong> (TP) is a limit order placed on the opposite side of your position that closes it when the target price is reached. For a long trade, TP is placed above entry. For a short trade, TP is placed below entry.</p>

<h3>How it works — example</h3>
<ul>
    <li>You buy EUR/USD at 1.0850.</li>
    <li>You set a TP at 1.0900 (50 pips above).</li>
    <li>EUR/USD rises to 1.0900 — your position closes automatically, and you've captured 50 pips.</li>
</ul>

<h3>Where to place a take-profit</h3>
<p>The best targets sit at levels where the market is likely to pause or reverse — not at round numbers chosen arbitrarily.</p>
<ul>
    <li><strong>At the next resistance level</strong> (for longs) — the price area where sellers are likely to step in.</li>
    <li><strong>At a prior swing high/low</strong> — an obvious level where the market previously turned.</li>
    <li><strong>At a measured move</strong> — the size of a prior swing projected from the breakout point.</li>
    <li><strong>Based on risk-reward ratio</strong> — e.g., 2× the distance of your stop.</li>
</ul>

<h3>Risk-reward and TP placement</h3>
<p>Professionals generally aim for a minimum reward-to-risk ratio of 2:1 — meaning the potential profit is at least twice the potential loss. If your stop is 30 pips away, your target should be at least 60 pips away. This ratio lets you be profitable even when you lose more trades than you win.</p>
<p>Example: with a 40% win rate and 2:1 R:R, you're profitable. With a 60% win rate and 1:1 R:R, you're also profitable — but you have far less margin for error.</p>

<h2>Why use a take-profit at all?</h2>
<p>Some traders manage exits manually — watching the trade and deciding when to close. There's nothing wrong with this in theory, but in practice:</p>
<ul>
    <li>Greed often stops you from closing when you should.</li>
    <li>Fear often makes you close too early, before the trade hits its real target.</li>
    <li>You can't watch every trade every minute.</li>
    <li>A pre-placed TP removes the emotional decision entirely.</li>
</ul>
<p>A take-profit is a commitment device. It forces the disciplined "yes, close here" decision you made <em>before</em> emotions were involved.</p>

<h2>Factual context</h2>
<p>Warren Buffett said:</p>
<blockquote><strong>"The stock market is a device for transferring money from the impatient to the patient."</strong></blockquote>
<p>Take-profits embody patience — but only if you let them. The impatient trader closes winners at +5 pips "before they turn around." The patient trader waits for the level. Take-profit orders enforce the patient behaviour, even when you don't feel patient.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Placing TP too close.</strong> A tight TP means frequent small wins but rare big ones — and the spread often eats the profit. Aim for at least 2:1 R:R.</li>
    <li><strong>Moving TP further away mid-trade.</strong> "It's running well, let me push the target up." If the original target was logical, moving it is just greed talking. If it wasn't logical, don't place it in the first place.</li>
    <li><strong>Cancelling the TP to "let it run."</strong> Sometimes valid — but only if you have a clear reason (a trend-following strategy with a trailing stop, for example). Not out of emotion.</li>
    <li><strong>No TP and no plan.</strong> If you close positions "when it feels right," you don't have a strategy. You have a feeling.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some traders use <strong>multiple take-profits</strong> — closing half the position at 1R and letting the rest run to 3R or beyond. This captures a partial win early while keeping exposure to a larger move. It's a common approach in trend-following and breakout strategies. The trade-off is that you need a bigger initial move to make the first partial worthwhile — and if the trade reverses after the first TP, you may end with a smaller win than a single-target exit would have produced. Test both approaches on your own strategy before committing.</p>
HTML,
        ],

        [
            'slug'   => 'reading-your-account-dashboard',
            'title'  => 'Reading Your Account Dashboard',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define balance, equity, margin, free margin, and margin level\n" .
                "• Explain the difference between balance and equity\n" .
                "• Recognise the warning signs of a margin call",
            'prerequisites' => 'Take-Profit Orders',
            'sort_order' => 5,
            'summary' => 'The account dashboard shows six numbers that tell you the health of your trading account at any moment. Balance is settled cash. Equity is balance plus floating P&L. Margin is collateral in use. Free margin is what is still available. Margin level tells you how close you are to a forced closure.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Your trading account has several numbers on it, and they mean different things. If you only look at one and ignore the others, you can be surprised by a margin call. This lesson teaches you what each number means, using a real example.</p>

<h2>Real-world analogy</h2>
<p>Think of a bank account with a mortgage. Your "balance" is what you have in cash. Your "equity" is your cash plus the value of your house minus the mortgage. Your "margin" is the deposit you put down. Your "free margin" is what's left after the deposit. Same concepts, different context.</p>

<h2>Professional explanation</h2>

<h3>Balance</h3>
<p>The cash in your account <em>ignoring</em> any open positions. If you have no open trades, balance equals equity. When you have open trades, balance is what's left after deposits and withdrawals, but before unrealised profits or losses.</p>

<h3>Equity</h3>
<p>Your balance plus the floating profit or loss of all open positions. This is your real, live account value at this second. It moves tick-by-tick with the market.</p>
<p><code>Equity = Balance + Floating P&L</code></p>

<h3>Used margin</h3>
<p>The total collateral locked up by your open positions. Every open trade requires a margin deposit based on the lot size and leverage. That deposit is "used margin" and cannot be spent on new trades until the position is closed.</p>

<h3>Free margin</h3>
<p>Equity minus used margin. This is what you have available to open new positions or absorb further losses.</p>
<p><code>Free margin = Equity − Used margin</code></p>

<h3>Margin level</h3>
<p>The ratio of equity to used margin, expressed as a percentage:</p>
<p><code>Margin level = (Equity / Used margin) × 100</code></p>
<p>This is the number that matters most. Every broker sets a minimum margin level — often 100% or 50%. If your margin level falls below that threshold, you get a margin call, and eventually a forced liquidation of your positions.</p>

<h2>Worked example</h2>
<p>You deposit $10,000 and open one standard lot of EUR/USD at 1:100 leverage.</p>
<ul>
    <li>Position size = $100,000</li>
    <li>Required margin = $100,000 / 100 = $1,000</li>
    <li>Balance = $10,000</li>
    <li>Used margin = $1,000</li>
    <li>Free margin = $10,000 − $1,000 = $9,000</li>
    <li>Margin level = ($10,000 / $1,000) × 100 = <strong>1,000%</strong></li>
</ul>
<p>You're well capitalised. Now assume EUR/USD drops 100 pips against you. P&L = −$1,000.</p>
<ul>
    <li>Equity = $10,000 − $1,000 = $9,000</li>
    <li>Used margin = $1,000</li>
    <li>Free margin = $9,000 − $1,000 = $8,000</li>
    <li>Margin level = ($9,000 / $1,000) × 100 = <strong>900%</strong></li>
</ul>
<p>Still safe. Now assume it drops 950 pips against you. P&L = −$9,500.</p>
<ul>
    <li>Equity = $10,000 − $9,500 = $500</li>
    <li>Used margin = $1,000</li>
    <li>Free margin = $500 − $1,000 = −$500 (negative!)</li>
    <li>Margin level = ($500 / $1,000) × 100 = <strong>50%</strong></li>
</ul>
<p>A 50% margin level often triggers a margin call or liquidation. The broker is about to close your position to prevent further loss — and to protect itself. This is why using the correct lot size matters so much.</p>

<h2>Factual context</h2>
<p>Bruce Kovner, founder of Caxton Associates, said:</p>
<blockquote><strong>"I know where I'm getting out before I get in."</strong></blockquote>
<p>Kovner's point applies directly to the dashboard. If you know your maximum loss before opening the trade, you know your equity will never drop below a level that triggers a margin call. Traders who blow up accounts are traders who didn't know — or didn't care — where their equity could fall.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Only looking at balance.</strong> Balance doesn't reflect open trades. You can have a healthy balance and be five minutes from a margin call.</li>
    <li><strong>Ignoring free margin.</strong> If your free margin hits zero, you can't open new positions — and a further adverse move will start consuming used margin.</li>
    <li><strong>Treating margin level like a distant statistic.</strong> Margin level below 200% is a yellow flag. Below 100% is a red flag. Below your broker's minimum is a forced exit.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some brokers use <strong>hedged margin</strong> — if you have both a long and short on the same pair, they cancel out and require less margin. Others use <strong>net margin</strong> — offsetting positions reduce total exposure. Both affect your margin level calculation. Check your broker's documentation. It matters when you're running multiple positions or using a hedge strategy. For most beginners trading one position at a time, the standard calculation above applies directly.</p>
HTML,
        ],

        [
            'slug'   => 'closing-and-partial-closes',
            'title'  => 'Closing Positions and Partial Closes',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Close positions manually and understand the mechanics\n" .
                "• Use partial closes to scale out of a trade\n" .
                "• Explain the trade-offs of partial closes vs full exits",
            'prerequisites' => 'Reading Your Account Dashboard',
            'sort_order' => 6,
            'summary' => 'Closing a position is the act of exiting a trade. You can close the entire position at once, or close a portion of it (partial close) to lock in profit while leaving the rest running. Both approaches are valid — the choice depends on your strategy.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Opening a trade is only half the process. Closing it is where the profit or loss is realised. You can close all at once — "in for 1 lot, out for 1 lot" — or close part of it at different prices to smooth your exit.</p>

<h2>Real-world analogy</h2>
<p>Imagine selling a collection of 100 rare coins. You could sell them all at once for a fixed price, or sell 30 now, 30 next week, and 40 the week after — averaging out the price you get. Same inventory, different exit strategy.</p>

<h2>Professional explanation</h2>

<h3>Full close</h3>
<p>Closes the entire position at market. One exit, one realised profit or loss. Simple, clean, and what most beginner traders use.</p>
<ul>
    <li><strong>Pros:</strong> clean accounting, no partial-position management.</li>
    <li><strong>Cons:</strong> gives up further upside if the trade keeps running.</li>
</ul>

<h3>Partial close</h3>
<p>Closes a portion of your position, leaving the rest running. For example, closing 50% of a 1-lot position means you still hold 0.5 lots at the same entry price.</p>
<ul>
    <li><strong>Pros:</strong> locks in some profit early, reduces risk on the remaining position, keeps exposure to a larger move.</li>
    <li><strong>Cons:</strong> you need discipline to know where to close each portion. Ad-hoc partial closes usually hurt more than they help.</li>
</ul>

<h3>Example — a classic 50/50 scale-out</h3>
<ul>
    <li>You buy EUR/USD at 1.0850 with a 30-pip stop at 1.0820 and a target at 1.0950.</li>
    <li>At 1.0910 (+60 pips, +2R), you close 50% of the position.</li>
    <li>You move the stop on the remaining 50% to break-even (1.0850).</li>
    <li>If the trade reaches 1.0950, you take the rest for +100 pips.</li>
    <li>If the trade reverses, you got +60 pips on half, and break-even on the other half — a net positive trade even if it "failed."</li>
</ul>
<p>This is why partial closes are popular: they convert a would-be small loser into a small winner.</p>

<h3>Move stop to break-even</h3>
<p>When you close part of a position, it's standard practice to move the stop on the remainder to at least break-even. This turns a potentially losing trade into a "no-lose" trade — if the market reverses, you take nothing on the remaining portion rather than losing.</p>

<h2>The trade-off</h2>
<p>Partial closes are not automatically better than full closes. Every partial close reduces your potential upside on the remainder. If a trade runs to a big winner, you took profit too early on the part you closed.</p>
<p>The right choice depends on your strategy:</p>
<ul>
    <li><strong>Trend-following strategies</strong> usually prefer full closes with a trailing stop — let winners run.</li>
    <li><strong>Mean-reversion or scalping strategies</strong> often use full closes at fixed targets.</li>
    <li><strong>Swing strategies</strong> often use partial closes — they balance the desire to bank profit with the possibility of a larger move.</li>
</ul>

<h2>Factual context</h2>
<p>Larry Hite, one of the original Market Wizards, said:</p>
<blockquote><strong>"I have two basic rules about winning in trading as well as in life: (1) If you don't bet, you can't win. (2) If you lose all your chips, you can't bet."</strong></blockquote>
<p>Hite's second rule is the case for partial closes. They reduce the chance that one trade wipes out your capital. By banking some profit early, you protect your "chips" — and you're still in the game tomorrow.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Partial closing without a plan.</strong> If you decide to take 50% off "when it feels right," you don't have a partial-close strategy. You have indecision.</li>
    <li><strong>Forgetting to move the stop.</strong> Closing half and leaving the stop at the original level means the remaining position can still produce the full loss you originally planned for. Move it.</li>
    <li><strong>Partial closing too frequently.</strong> Closing 10% at a time creates a complex, hard-to-account position. Keep it simple: usually one partial, maybe two.</li>
</ul>

<h2>Advanced notes</h2>
<p>Some traders use <strong>pyramiding</strong> — the opposite of scaling out. Instead of closing in stages, they add to winning positions as the trade runs. This increases exposure on a move that's already profitable, but it also raises the average entry price and widens the effective risk. Pyramiding works well in strong trends but is dangerous in choppy markets. It's an advanced technique, covered in the "Advanced Trade Management" module later in the curriculum. Don't attempt it as a beginner.</p>
HTML,
        ],

    ],
];