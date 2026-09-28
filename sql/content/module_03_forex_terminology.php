<?php
/**
 * Module 03 — Forex Terminology
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_03_forex_terminology.php
 */

return [
    'module' => [
        'level_slug' => 'beginner',
        'slug'       => 'forex-terminology',
        'title'      => 'Forex Terminology',
        'description'=> 'The language of the market. Pips, lots, leverage, margin, spread, bid, ask — every term explained from first principles with real calculations. If you only master one module before trading, make it this one.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Define every core Forex term correctly\n" .
            "• Calculate pip value, lot size, margin, and leverage manually\n" .
            "• Explain the real costs of a trade (spread, commission, swap, slippage)\n" .
            "• Read a broker's quote sheet without confusion",
        'sort_order' => 3,
    ],

    'lessons' => [

        [
            'slug'   => 'what-is-a-pip',
            'title'  => 'What Is a Pip?',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define a pip\n" .
                "• Identify pip movement on any pair\n" .
                "• Distinguish pips from pipettes and points",
            'prerequisites' => 'Base Currency and Quote Currency',
            'sort_order' => 1,
            'summary' => 'A pip is the standard unit of price movement in Forex — usually 0.0001 for most pairs and 0.01 for yen pairs. Pips are how traders measure profit, loss, stop distance, and target size consistently across different pairs and account sizes.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a ruler. The smallest marked step on it is your unit of measurement. In Forex, that smallest step is called a <strong>pip</strong>. It's how traders describe how far price has moved without saying long, messy decimal numbers every time.</p>
<p>Instead of saying "EUR/USD moved from 1.08503 to 1.08513," a trader says "EUR/USD moved 1 pip."</p>

<h2>Real-world analogy</h2>
<p>Pips are like centimetres on a ruler. If someone says "the table grew 3 centimetres," you know exactly how much. If they say "the table grew 0.03 metres," you know too — but it's clunkier. Pips are the centimetres of currency prices.</p>

<h2>Professional explanation</h2>
<p>A <strong>pip</strong> (short for "percentage in point" or "price interest point") is the smallest conventional price move in a currency pair. It is the fourth decimal place in most pairs, and the second decimal place in yen pairs.</p>

<h3>For most pairs</h3>
<p>A pip is <strong>0.0001</strong>.</p>
<ul>
    <li>EUR/USD moves from 1.0850 to 1.0851 — that's <strong>1 pip</strong>.</li>
    <li>EUR/USD moves from 1.0850 to 1.0860 — that's <strong>10 pips</strong>.</li>
    <li>GBP/USD moves from 1.2700 to 1.2750 — that's <strong>50 pips</strong>.</li>
</ul>

<h3>For yen pairs (JPY)</h3>
<p>A pip is <strong>0.01</strong>.</p>
<ul>
    <li>USD/JPY moves from 149.50 to 149.51 — that's <strong>1 pip</strong>.</li>
    <li>USD/JPY moves from 149.50 to 150.00 — that's <strong>50 pips</strong>.</li>
</ul>
<p>The difference exists because the yen trades at a different price scale — around 150 per dollar rather than 1 per dollar. The market convention adjusts the pip size to keep the numbers manageable.</p>

<h3>Pipettes and points</h3>
<p>Modern brokers often quote prices to <strong>five decimals</strong> for non-JPY pairs (1.08503) and <strong>three decimals</strong> for JPY pairs (149.503). That extra digit is called a <strong>pipette</strong> — one-tenth of a pip.</p>
<ul>
    <li>1 pip = 10 pipettes</li>
    <li>1 pipette = 0.1 pip</li>
</ul>
<p>A "point" is another term sometimes used interchangeably with pipette, depending on the broker. If a broker says "1 point," check their documentation — it could mean 1 pip or 1 pipette. This is a common source of confusion.</p>

<h2>How pips translate to money</h2>
<p>A pip's monetary value depends on the position size. For a standard lot (100,000 units) on EUR/USD, 1 pip = roughly $10. For a mini lot (10,000 units), 1 pip = $1. For a micro lot (1,000 units), 1 pip = $0.10. We'll calculate this exactly in the lot size lesson.</p>

<h2>Factual context</h2>
<p>Jesse Livermore, arguably the greatest speculator of the early 20th century, wrote in <em>Reminiscences of a Stock Operator</em>:</p>
<blockquote><strong>"It never was my thinking that made the big money for me. It always was my sitting."</strong>[reference:8]</blockquote>
<p>Pips are how you measure whether your "sitting" is paying off. A single pip isn't exciting — but consistent pip accumulation over hundreds of trades is how accounts grow. Livermore was talking about discipline, and pips are the unit that discipline is measured in.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Confusing pips with pipettes.</strong> A move of 1.08503 to 1.08504 is 1 pipette, not 1 pip. This matters when brokers quote five decimals.</li>
    <li><strong>Assuming every pair has the same pip value.</strong> It doesn't. A pip on GBP/JPY is worth different money than a pip on EUR/USD, even at the same lot size.</li>
    <li><strong>Confusing pip movement with profit.</strong> 100 pips on a micro lot is a smaller dollar profit than 50 pips on a standard lot. Pips measure distance, not money.</li>
</ul>

<h2>Advanced notes</h2>
<p>The word "pip" comes from "percentage in point," but the acronym has drifted over time and now just means "the smallest conventional move." Some brokers quote to five decimals specifically to offer tighter spreads — a 0.3 pip spread is only visible with five decimals. Always check how many decimals your broker uses before you calculate costs.</p>
HTML,
        ],

        [
            'slug'   => 'lots-and-position-size',
            'title'  => 'Lots and Position Size',
            'difficulty' => 'beginner',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Define standard, mini, micro, and nano lots\n" .
                "• Calculate pip value for any lot size\n" .
                "• Explain why position size determines risk",
            'prerequisites' => 'What Is a Pip?',
            'sort_order' => 2,
            'summary' => 'A lot is a standardised batch of currency units. Standard lots are 100,000 units, minis are 10,000, micros are 1,000, and nanos are 100. Position size is the single most important risk variable in any trade.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>You don't buy currency one unit at a time — that would be absurd. You buy in batches called <strong>lots</strong>. The size of your lot determines how much money each pip is worth to you.</p>
<p>Bigger lot = bigger pip value = bigger profit or loss for the same move. Smaller lot = smaller pip value = smaller swing. Position size is how you control your risk.</p>

<h2>Real-world analogy</h2>
<p>Think of buying eggs. You can buy one egg, a dozen, or a crate of 360. The price of one egg is the same, but the total cost depends on how many you buy. Lots work the same way — the pip is the "egg," the lot is how many you're buying.</p>

<h2>Professional explanation</h2>
<p>A <strong>lot</strong> is a standardised quantity of currency units. There are four standard sizes:</p>

<table>
    <thead><tr><th>Lot type</th><th>Units</th><th>Pip value (EUR/USD)</th></tr></thead>
    <tbody>
        <tr><td>Standard lot</td><td>100,000</td><td>~$10.00</td></tr>
        <tr><td>Mini lot</td><td>10,000</td><td>~$1.00</td></tr>
        <tr><td>Micro lot</td><td>1,000</td><td>~$0.10</td></tr>
        <tr><td>Nano lot</td><td>100</td><td>~$0.01</td></tr>
    </tbody>
</table>

<h3>Calculating pip value</h3>
<p>The exact pip value depends on the pair and the prevailing exchange rate. The general formula for a USD-quoted pair is:</p>
<p><code>Pip value = (1 pip / exchange rate) × lot size</code></p>
<p>For EUR/USD at 1.0850 with a standard lot (100,000):</p>
<ul>
    <li>1 pip = 0.0001</li>
    <li>Pip value = (0.0001 / 1.0850) × 100,000 = <strong>$9.22</strong> approximately</li>
</ul>
<p>Most brokers round this to $10 for simplicity. For pairs where USD is the base (USD/JPY, USD/CHF), the calculation is slightly different but still results in a pip value close to $10 per standard lot.</p>

<h3>Why lot size is risk control</h3>
<p>Suppose you have a $10,000 account and want to risk 1% per trade. That's $100 of risk.</p>
<ul>
    <li>If your stop is 50 pips away, your position size should be $100 / 50 pips = $2 per pip</li>
    <li>That means a <strong>mini lot</strong> (10,000 units) is appropriate</li>
    <li>If you traded a <strong>standard lot</strong> instead, 50 pips would cost $500 — five times your intended risk</li>
</ul>
<p>This is why position sizing is more important than entry timing. You can be right about direction and still blow up your account if the position is too large.</p>

<h2>Factual context</h2>
<p>Warren Buffett's famous rule:</p>
<blockquote><strong>"Rule No. 1: Never lose money. Rule No. 2: Never forget Rule No. 1."</strong>[reference:9]</blockquote>
<p>Every professional trader understands this at the position-sizing level. You don't control whether a trade wins or loses — you control how much you risk on it. Lot size is the lever that decides whether a normal losing trade is a minor setback or a fatal hit.</p>
<p>The BIS 2025 survey showed global FX turnover at <strong>$9.6 trillion per day</strong>[reference:10]. Retail traders are a tiny fraction of that, but they still lose disproportionately because of oversized positions. Institutional desks survive losing streaks because their position sizes are small relative to capital. That's the lesson.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading a full standard lot on a small account.</strong> Unless your account is $50,000+, a standard lot with a 50-pip stop is too risky.</li>
    <li><strong>Ignoring pip value for non-USD quote pairs.</strong> A pip on GBP/JPY is not worth the same as a pip on EUR/USD. Always check the actual pip value for the pair you're trading.</li>
    <li><strong>Adjusting lot size emotionally.</strong> Increasing size after a loss ("to make it back") is the classic path to ruin. Size should be calculated, not felt.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often size positions using the <strong>fixed fractional</strong> method: risk a fixed percentage of account equity on every trade, regardless of conviction. Some use the <strong>Kelly Criterion</strong> — a formula that sizes based on win rate and payoff ratio. Both approaches prioritise survival over maximum return on any single trade. For retail traders, fixed fractional at 0.5–1% per trade is the standard starting point.</p>
HTML,
        ],

        [
            'slug'   => 'bid-ask-and-spread',
            'title'  => 'Bid, Ask, and Spread',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define bid, ask, and spread\n" .
                "• Explain who gets which price\n" .
                "• Calculate the cost of a round-trip trade",
            'prerequisites' => 'Lots and Position Size',
            'sort_order' => 3,
            'summary' => 'The bid is the price a buyer will pay. The ask is the price a seller will accept. The spread is the difference — and it is your first cost on every trade. Understanding the spread tells you exactly what the market charges you to enter and exit.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Walk into any market and you'll see two prices: the price someone will <em>buy</em> from you, and the price someone will <em>sell</em> to you. The gap between them is how the market makes money. That gap is the <strong>spread</strong>.</p>

<h2>Real-world analogy</h2>
<p>Think of a currency exchange at an airport. The sign says "We buy USD at 1.08, we sell USD at 1.09." The 1-cent gap is their profit. Forex brokers work the same way — just on a much finer scale.</p>

<h2>Professional explanation</h2>

<h3>Bid</h3>
<p>The <strong>bid</strong> is the highest price a buyer is currently willing to pay. It's the price at which <em>you</em> can sell. When a broker shows EUR/USD bid at 1.08503, that's what you'll get if you sell euros right now.</p>

<h3>Ask (or Offer)</h3>
<p>The <strong>ask</strong> (also called the <em>offer</em>) is the lowest price a seller is currently willing to accept. It's the price at which <em>you</em> can buy. If EUR/USD ask is 1.08511, that's what you'll pay to buy euros right now.</p>

<h3>Spread</h3>
<p>The <strong>spread</strong> is the difference between ask and bid:</p>
<p><code>Spread = Ask − Bid</code></p>
<p>For the example above: 1.08511 − 1.08503 = <strong>0.8 pips</strong>. This is the broker's compensation for executing your trade.</p>

<h3>Who gets which price</h3>
<ul>
    <li>If you <strong>buy</strong>, you pay the <strong>ask</strong> (higher price).</li>
    <li>If you <strong>sell</strong>, you receive the <strong>bid</strong> (lower price).</li>
    <li>You always lose the spread on entry. You then need to overcome it to reach profit.</li>
</ul>
<p>This is why every trade starts slightly negative. If you buy EUR/USD at 1.08511 and immediately sell, you get 1.08503 — a loss of 0.8 pips. The market isn't rigged; that's just the cost of doing business.</p>

<h3>Calculating the cost</h3>
<p>On a standard lot (100,000 units), a 1-pip spread costs $10. An 0.8-pip spread costs $8 per round trip. On a micro lot (1,000 units), it costs $0.08. Smaller lots mean smaller spread costs, which is why beginners should start small.</p>

<h2>Factual context</h2>
<p>Spreads are one of the few costs that are guaranteed on every trade. This is why Paul Tudor Jones, one of the most successful macro traders in history, said:</p>
<blockquote><strong>"Don't focus on making money, focus on protecting what you have."</strong>[reference:11]</blockquote>
<p>If you focus on protecting capital, one of the first places to look is your cost structure. Traders who ignore spread eventually pay more in costs than they make in profits. Every pip of spread is a pip you don't get to keep.</p>
<p>Brokers typically charge either a wide spread (no commission) or a tight spread plus a commission. ECN-style brokers might quote 0.1 pips on EUR/USD plus $3.50 per side per standard lot — which works out cheaper for high-volume traders. Market-maker brokers bury the cost in the spread. Neither is inherently better; it depends on your trading frequency and style.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading during low-liquidity hours.</strong> Spreads widen dramatically outside of London/New York overlap. Trading at 3am UTC on EUR/USD can cost 3–5 pips instead of 0.5.</li>
    <li><strong>Ignoring the spread on exotics.</strong> USD/TRY spread can be 20+ pips. The same 50-pip move costs far more to capture on an exotic than on a major.</li>
    <li><strong>Counting spread as profit.</strong> If your take-profit is 10 pips and the spread is 2 pips, you only net 8. Always subtract the spread from your target.</li>
</ul>

<h2>Advanced notes</h2>
<p>Spreads are not fixed — they react to liquidity and volatility. During major news events (NFP, FOMC, ECB), spreads on EUR/USD can widen from 0.5 pips to 5+ pips in seconds. This is why many traders avoid holding positions through major scheduled news, or widen their stops to account for the wider spread. It's not the broker being unfair — it's the market makers protecting themselves from the risk of a sudden move.</p>
HTML,
        ],

        [
            'slug'   => 'leverage-and-margin',
            'title'  => 'Leverage and Margin',
            'difficulty' => 'beginner',
            'estimated_duration' => 13,
            'learning_objectives' =>
                "• Define leverage and margin\n" .
                "• Calculate required margin for a position\n" .
                "• Explain how leverage amplifies both gains and losses",
            'prerequisites' => 'Bid, Ask, and Spread',
            'sort_order' => 4,
            'summary' => 'Leverage lets you control a large position with a small deposit. Margin is that deposit. Leverage magnifies both profits and losses — it is a tool, not a strategy. Using it well is the difference between a growing account and a blown one.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine buying a $100,000 house with only $1,000 of your own money and $99,000 borrowed from the bank. That's leverage — using a small amount of capital to control a much larger asset. In Forex, leverage lets you trade large positions with small deposits. It's powerful, and it's dangerous.</p>

<h2>Real-world analogy</h2>
<p>Think of a lever in physics. A small force on one end moves a heavy object on the other. Leverage in trading does the same thing — a small deposit controls a large position. But if the object moves the wrong way, the same lever amplifies the loss just as much as the gain.</p>

<h2>Professional explanation</h2>

<h3>Leverage</h3>
<p><strong>Leverage</strong> is a ratio that expresses how much market exposure you control per unit of your own capital. It's quoted like 1:100, 1:200, or 1:500.</p>
<ul>
    <li>1:100 means $1 of your capital controls $100 of position</li>
    <li>1:200 means $1 controls $200</li>
    <li>1:500 means $1 controls $500</li>
</ul>

<h3>Margin</h3>
<p><strong>Margin</strong> is the deposit your broker requires to open a leveraged position. It's not a fee — it's collateral. When you close the trade, the margin is released back to your account.</p>
<p>Formula:</p>
<p><code>Required margin = Position size / Leverage</code></p>
<p>For a standard lot (100,000 units) of EUR/USD at 1:100 leverage:</p>
<ul>
    <li>Position size = $100,000</li>
    <li>Leverage = 100</li>
    <li>Required margin = $100,000 / 100 = <strong>$1,000</strong></li>
</ul>
<p>So $1,000 in your account lets you control a $100,000 position. At 1:500, the required margin drops to $200. The position size is the same — only the deposit changes.</p>

<h3>The trap of leverage</h3>
<p>Leverage doesn't change the dollar value of a pip. On a standard lot of EUR/USD, 1 pip is worth $10 whether you're trading at 1:100 or 1:500. What leverage changes is how much of your account is tied up — and therefore how quickly a losing move can wipe you out.</p>
<p>If you have $1,000 and use 1:500 leverage to open a standard lot, a 100-pip adverse move costs $1,000 — your entire account. With 1:100 leverage, the same 100-pip move costs $1,000 too, but you'd likely have been stopped out or received a margin call long before.</p>
<p>In practice, brokers close your position when your <strong>margin level</strong> (equity / used margin) falls below a certain threshold — often 50% or 100%. This is called a <strong>margin call</strong>, and if you can't add funds, your position is <strong>liquidated</strong>. The broker sells your position to protect itself from your loss.</p>

<h2>Factual context</h2>
<p>Bruce Kovner, one of the most successful macro traders of all time, famously advised:</p>
<blockquote><strong>"Don't be a hero. Don't have an ego. Always question yourself and your ability. Don't ever feel that you are very good."</strong>[reference:12]</blockquote>
<p>Nothing triggers ego more than high leverage. The trader who uses 1:500 to "make it big" is the one most likely to blow up. Kovner's point is that survival comes from humility — and humility means keeping position sizes small enough that no single trade can end you.</p>
<p>The BIS 2025 survey puts daily FX turnover at <strong>$9.6 trillion</strong>[reference:13]. Institutional traders move billions with tiny leverage because their edge is consistency, not swinging for the fences. Retail traders often do the opposite and pay the price.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Thinking higher leverage is better.</strong> Higher leverage means higher risk. Most professional traders use less leverage than retail brokers offer.</li>
    <li><strong>Confusing margin with loss.</strong> Margin is a deposit, not a cost. You get it back when the trade closes. The loss (or profit) is separate.</li>
    <li><strong>Not understanding margin call mechanics.</strong> If your equity drops below the broker's margin requirement, they will close your positions. It's not optional.</li>
</ul>

<h2>Advanced notes</h2>
<p>Regulated brokers in the US, EU, and UK are capped at 1:30 or 1:50 leverage for retail traders. Offshore brokers often offer 1:500 or higher. The tighter caps exist because studies by EU regulators found that 74–89% of retail CFD accounts lose money, and high leverage was a major factor. If you're choosing a broker, regulation matters more than leverage. A 1:30 broker that returns your withdrawals is worth far more than a 1:1000 broker that doesn't.</p>
HTML,
        ],

        [
            'slug'   => 'swap-commission-slippage',
            'title'  => 'Swap, Commission, and Slippage',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define swap, commission, and slippage\n" .
                "• Calculate the full cost of a trade\n" .
                "• Explain why holding overnight changes your cost structure",
            'prerequisites' => 'Leverage and Margin',
            'sort_order' => 5,
            'summary' => 'Spread is not the only cost. Swap is the overnight financing charge. Commission is a per-trade fee. Slippage is the difference between the price you wanted and the price you got. Together, these costs determine whether your strategy is actually profitable after expenses.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>When you trade, you pay more than just the spread. If you hold overnight, you pay (or receive) a financing charge. Some brokers charge a flat commission per trade. And on fast-moving days, your order might fill at a worse price than you wanted. These are real costs, and they add up.</p>

<h2>Real-world analogy</h2>
<p>Think of renting a car. The rental fee is the spread. The fuel surcharge is the commission. If you keep the car an extra day, there's an overnight fee — that's the swap. And if you return it late and have to pay a penalty, that's slippage. Every cost matters.</p>

<h2>Professional explanation</h2>

<h3>Swap (rollover)</h3>
<p>When you trade Forex, you're effectively borrowing one currency to buy another. If you hold the position past the daily rollover time (usually 5pm New York), you pay or receive the <strong>interest rate differential</strong> between the two currencies. This is the <strong>swap</strong>.</p>
<ul>
    <li>If you're long a high-interest currency and short a low-interest currency, you may <strong>receive</strong> swap.</li>
    <li>If you're long a low-interest currency and short a high-interest currency, you may <strong>pay</strong> swap.</li>
    <li>Wednesdays usually carry triple swap to account for the weekend.</li>
</ul>
<p>Swap is not a fee invented by the broker — it reflects real interest-rate differences between the two currencies[reference:14].</p>

<h3>Commission</h3>
<p>Some brokers charge a fixed commission per trade instead of (or in addition to) a wider spread. A typical ECN commission is $3.50 per side per standard lot — so $7 round-trip on a full lot. This is cheaper than a 0.7-pip spread if you trade large size, and more expensive if you trade tiny size.</p>

<h3>Slippage</h3>
<p><strong>Slippage</strong> is the difference between the price you expected and the price you actually got. It happens when:</p>
<ul>
    <li>Volatility is high (news releases, market opens)</li>
    <li>Liquidity is thin (weekends, holidays, off-hours)</li>
    <li>Your order is large relative to available liquidity</li>
</ul>
<p>On a normal day, EUR/USD slippage is near zero. During a central bank announcement, it can be several pips. When your stop-loss triggers, slippage means you lose more than you planned. This is why many traders avoid holding positions through major news[reference:15].</p>

<h2>Calculating the full cost</h2>
<p>Let's calculate a complete trade:</p>
<ul>
    <li>Buy 1 standard lot EUR/USD at 1.0850</li>
    <li>Spread = 0.8 pips = $8</li>
    <li>Commission = $7 round trip</li>
    <li>Held for 2 days = 2 × 1 pip in swap (roughly) = $20 in swap paid</li>
    <li>Total cost = $8 + $7 + $20 = <strong>$35</strong></li>
</ul>
<p>To break even, the trade must move at least 3.5 pips in your favour. To profit meaningfully, it needs to move much further. This is why scalping with wide costs is so difficult.</p>

<h2>Factual context</h2>
<p>Jesse Livermore wrote:</p>
<blockquote><strong>"A man may see straight and clearly and yet become impatient or doubtful when the market takes its time about doing as he figured it must do."</strong>[reference:16]</blockquote>
<p>The flip side of patience is cost awareness. The longer you hold a trade, the more swap you pay. The more frequently you trade, the more spread and commission you accumulate. Every professional trader knows their cost per trade — if you don't, you're flying blind.</p>
<p>The BIS 2025 survey reported that FX spot and outright forward turnover grew 42% and 60% respectively since 2022[reference:17]. The market is getting busier, which means more competition and more opportunity — but also more noise and more cost if you're not disciplined.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Ignoring swap on long-held positions.</strong> A position held for weeks can accumulate more swap than the profit from the trade itself.</li>
    <li><strong>Trading news without accounting for slippage.</strong> If your stop is 20 pips away and slippage is 10 pips, your actual loss is 30 pips. Size accordingly.</li>
    <li><strong>Choosing a broker on spread alone.</strong> A broker with a tight spread but $10 commission per side is more expensive than one with a 1-pip spread and no commission for most retail traders. Compare total cost, not headline spread.</li>
</ul>

<h2>Advanced notes</h2>
<p>Positive swap is a strategy in itself — the "carry trade." Borrow a low-interest currency (like JPY at near-zero rates) and buy a high-interest currency (like AUD at 4%+), hold the position, and collect swap. The risk is that the exchange rate moves against you by more than the swap you collect. The carry trade famously blew up in 2008 when the yen surged and wiped out years of swap gains. Positive swap is not free money — it's compensation for taking on currency risk.</p>
HTML,
        ],

    ],
];