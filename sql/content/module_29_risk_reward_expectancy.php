<?php
/**
 * Module 29 — Risk/Reward & Expectancy
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_29_risk_reward_expectancy.php
 */

return [
    'module' => [
        'level_slug' => 'professional',
        'slug'       => 'risk-reward-expectancy',
        'title'      => 'Risk/Reward & Expectancy',
        'description'=> 'Risk-to-reward ratio and expectancy are the mathematical foundations of profitable trading. This module explores how win rate, payoff ratio, and expectancy interact, why some strategies with high win rates lose money, and how to design a system that produces positive expectancy over hundreds of trades.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Understand reward-to-risk ratios and their role\n" .
            "• Calculate expectancy for any strategy\n" .
            "• Analyse the win rate / payoff matrix\n" .
            "• Set realistic targets based on expectancy\n" .
            "• Combine expectancy with position sizing for optimal growth",
        'sort_order' => 29,
    ],

    'lessons' => [

        [
            'slug'   => 'understanding-risk-reward-ratios',
            'title'  => 'Understanding Risk-to-Reward Ratios',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define reward-to-risk ratio\n" .
                "• Calculate R:R for any trade\n" .
                "• Understand why higher R:R requires lower win rate",
            'prerequisites' => 'Putting Risk Management Together',
            'sort_order' => 1,
            'summary' => 'A reward-to-risk ratio compares the potential profit of a trade to its potential loss. A 3:1 ratio means the potential reward is three times the risk. Higher ratios produce more profits per winning trade, but require lower win rates to break even.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two bets. In the first, you can win $1 or lose $1. In the second, you can win $3 or lose $1. Obviously, the second is better — you risk the same but stand to win three times as much.</p>
<p>Reward-to-risk ratio measures exactly this. It is the potential profit divided by the potential loss.</p>

<h2>Real-world analogy</h2>
<p>Think of a shop buying products at wholesale and selling at retail. The ratio of retail price to wholesale price is the "reward-to-risk" ratio. Higher ratios mean more profit per sale, but they also require either a better product or a better marketing strategy to find buyers.</p>

<h2>Professional explanation</h2>

<h3>Defining reward-to-risk</h3>
<p>For a long trade:</p>
<ul>
    <li><strong>Reward</strong> = Target price − Entry price</li>
    <li><strong>Risk</strong> = Entry price − Stop price</li>
    <li><strong>R:R</strong> = Reward / Risk</li>
</ul>
<p>For a short trade, the calculations are inverted.</p>
<p>Example: Long EUR/USD at 1.0850, stop at 1.0820, target at 1.0950.</p>
<ul>
    <li>Reward = 1.0950 − 1.0850 = 100 pips</li>
    <li>Risk = 1.0850 − 1.0820 = 30 pips</li>
    <li>R:R = 100 / 30 = 3.33</li>
</ul>
<p>The ratio is 3.33:1 — you risk 30 pips to make 100.</p>

<h3>Common ratios and their meanings</h3>
<table>
    <thead><tr><th>R:R Ratio</th><th>Meaning</th><th>Win Rate Needed to Break Even</th></tr></thead>
    <tbody>
        <tr><td>1:1</td><td>Equal risk and reward</td><td>50%</td></tr>
        <tr><td>1.5:1</td><td>Slight edge</td><td>40%</td></tr>
        <tr><td>2:1</td><td>Standard minimum</td><td>33.3%</td></tr>
        <tr><td>3:1</td><td>Strong ratio</td><td>25%</td></tr>
        <tr><td>4:1</td><td>Very strong</td><td>20%</td></tr>
        <tr><td>5:1</td><td>Exceptional</td><td>16.7%</td></tr>
    </tbody>
</table>
<p>The break-even win rate is calculated as: <code>1 / (1 + R:R)</code>.</p>
<p>A 1:1 ratio needs 50% win rate to break even. A 3:1 ratio needs only 25%. The higher the ratio, the lower the win rate required.</p>

<h3>Visual reference — R:R ratios</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- 1:1 -->
  <rect x="60" y="100" width="40" height="40" fill="#ef4444" fill-opacity="0.4"/>
  <rect x="60" y="60" width="40" height="40" fill="#4ade80" fill-opacity="0.4"/>
  <text x="80" y="170" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">1:1</text>

  <!-- 2:1 -->
  <rect x="160" y="100" width="40" height="40" fill="#ef4444" fill-opacity="0.4"/>
  <rect x="160" y="20" width="40" height="80" fill="#4ade80" fill-opacity="0.4"/>
  <text x="180" y="170" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">2:1</text>

  <!-- 3:1 -->
  <rect x="260" y="100" width="40" height="40" fill="#ef4444" fill-opacity="0.4"/>
  <rect x="260" y="0" width="40" height="100" fill="#4ade80" fill-opacity="0.4"/>
  <text x="280" y="170" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">3:1</text>

  <!-- 5:1 -->
  <rect x="360" y="100" width="40" height="40" fill="#ef4444" fill-opacity="0.4"/>
  <rect x="360" y="0" width="40" height="100" fill="#4ade80" fill-opacity="0.6"/>
  <text x="380" y="170" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">5:1+</text>

  <text x="60" y="200" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">Red = Risk</text>
  <text x="160" y="200" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Green = Reward</text>
  <text x="250" y="225" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Higher R:R means more profit per winning trade</text>
</svg>

<h3>The trade-off</h3>
<p>Higher R:R ratios sound better, but they come with a trade-off:</p>
<ul>
    <li><strong>Lower win rate.</strong> Higher targets are further away, so trades reach them less often.</li>
    <li><strong>More price movement needed.</strong> A 5:1 ratio requires price to move 5× further than the stop distance.</li>
    <li><strong>More give-back.</strong> Trades that go into profit often reverse before hitting distant targets.</li>
    <li><strong>Fewer setups.</strong> Not every setup offers a high R:R. Waiting for high R:R means waiting longer.</li>
</ul>
<p>The optimal R:R depends on your strategy. Trend-following systems typically use high R:R (3:1 to 10:1) with lower win rates. Mean-reversion systems use lower R:R (1:1 to 2:1) with higher win rates.</p>

<h3>The classic mistake</h3>
<p>The most common R:R mistake is setting stops that are too tight and targets that are too far. This produces a mathematically favourable ratio but a practically unachievable one — the stop gets hit by normal market noise, and the target is never reached.</p>
<p>The solution: place stops at structurally meaningful levels (not arbitrary distances) and targets at realistic levels (not wishful ones). A trade with a 2:1 ratio where both levels are structurally valid is far better than a trade with a 5:1 ratio where the stop is inside the noise and the target is a fantasy.</p>

<h2>Factual context</h2>
<p>The concept of reward-to-risk ratio has been central to trading for over a century. Jesse Livermore's famous rules emphasised the importance of not taking trades where the potential reward did not justify the risk.</p>
<p>Modern practitioners use variations of the ratio. Some traders use fixed ratios (always 2:1 or 3:1). Others use dynamic ratios based on structural levels. Both approaches work when applied consistently.</p>
<p>Larry Hite, one of the original Market Wizards, described the importance of ratio:</p>
<blockquote><strong>\"The best traders are not the ones who win the most trades. They are the ones who make the most money per trade. Reward-to-risk is the driver of that.\"</strong></blockquote>
<p>Hite's point is critical. A trader with a 40% win rate and 3:1 ratio has far better expectancy than a trader with a 70% win rate and 1:1 ratio. The ratio matters more than the win rate.</p>
<p>Paul Tudor Jones, describing his approach:</p>
<blockquote><strong>\"I look for trades where the potential reward is at least three times the risk. Below that, it is not worth taking.\"</strong></blockquote>
<p>Jones' minimum of 3:1 is aggressive but reflects his trend-following style. Mean-reversion traders might accept 1.5:1. The key is consistency.</p>
<p>Bruce Kovner described his approach to R:R:</p>
<blockquote><strong>\"I want every trade to have the potential to make at least twice what I am risking. If the potential reward is only equal to the risk, it is not worth taking.\"</strong></blockquote>
<p>Kovner's 2:1 minimum is standard. Trades with lower ratios must have very high win rates to compensate — and high win rates are hard to sustain.</p>
<p>Ed Seykota's philosophy implicitly uses R:R:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>\"Cut losses short and let winners run\" is a prescription for high reward-to-risk. Small losses (tight stops), large wins (trailing targets). The ratio is the mechanism.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Setting stops too tight.</strong> A 5-pip stop on a pair with 30-pip daily noise will be hit by normal movement. The R:R looks good but never materialises.</li>
    <li><strong>Setting targets too far.</strong> A target that requires an unrealistic move will never be hit. The ratio is meaningless if the trade never gets there.</li>
    <li><strong>Ignoring the win rate.</strong> A 5:1 ratio only works if the win rate is high enough to prevent long losing streaks.</li>
    <li><strong>Assuming the ratio is fixed.</strong> The market doesn't guarantee the target will be reached. Trades end where they end.</li>
    <li><strong>Comparing across strategies.</strong> A trend-following system with 3:1 ratio and 40% win rate is not comparable to a mean-reversion system with 1:1 ratio and 70% win rate. Both can be profitable.</li>
    <li><strong>Optimising for the maximum ratio.</strong> Higher R:R is not always better. The optimal ratio depends on the strategy, market, and timeframe.</li>
    <li><strong>Forgetting about the spread.</strong> Spread costs reduce the effective R:R. A 2:1 ratio with a wide spread is really 1.8:1.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use dynamic R:R ratios based on market conditions. In strongly trending markets, targets can be extended (higher R:R). In ranging markets, targets should be closer (lower R:R).</p>
<p>The key insight: the ratio should be set <em>before</em> the trade, based on the market's actual structure. Not based on wishful thinking about how far price might move.</p>
<p>Some traders use \"partial targets\" — taking profit at multiple levels. For example, taking half the position at 2R, a quarter at 3R, and letting the rest run. This produces a blended R:R that's between the initial target and the max target.</p>
<p>Another advanced concept: <strong>R:R relative to market volatility</strong>. High-volatility markets support larger targets. Low-volatility markets require more conservative targets. Adjusting R:R to volatility is a professional standard.</p>
<p>The most successful traders don't obsess over R:R. They focus on finding high-quality setups with structurally valid stops and targets. The R:R emerges from the structure, not from the wish to have a specific number.</p>
HTML,
        ],

        [
            'slug'   => 'the-expectancy-formula',
            'title'  => 'The Expectancy Formula',
            'difficulty' => 'professional',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Master the expectancy formula\n" .
                "• Calculate expectancy from trade data\n" .
                "• Interpret expectancy across strategies",
            'prerequisites' => 'Understanding Risk-to-Reward Ratios',
            'sort_order' => 2,
            'summary' => 'Expectancy is the average R-multiple per trade. It combines win rate and payoff ratio into a single number that determines whether a strategy is profitable. Positive expectancy is the requirement for long-term success; negative expectancy guarantees losses.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a game where you flip a coin. Heads pays 2 dollars, tails loses 1 dollar. Even though you win only 50% of the time, you make money on average — 50 cents per flip. That average is expectancy.</p>
<p>Expectancy tells you what to expect, on average, from each trade. Positive expectancy means the system makes money over time.</p>

<h2>Real-world analogy</h2>
<p>Think of a casino. Every game has an expectancy — a small edge for the house. Individual players win or lose, but over thousands of hands, the house's expectancy ensures it profits. Traders need positive expectancy to be the "house" rather than the "player."</p>

<h2>Professional explanation</h2>

<h3>The formula</h3>
<p><code>Expectancy = (Win Rate × Avg Win) − (Loss Rate × Avg Loss)</code></p>
<p>Where:</p>
<ul>
    <li><strong>Win Rate</strong> — decimal percentage of winning trades.</li>
    <li><strong>Loss Rate</strong> — decimal percentage of losing trades (1 − Win Rate).</li>
    <li><strong>Avg Win</strong> — average profit in R-multiples.</li>
    <li><strong>Avg Loss</strong> — average loss in R-multiples (expressed as positive).</li>
</ul>
<p>Equivalently, in a single-line form:</p>
<p><code>Expectancy = (W × AW) − ((1 − W) × AL)</code></p>

<h3>Worked examples</h3>
<p><strong>Example 1 — Trend following system:</strong></p>
<ul>
    <li>Win rate: 40%</li>
    <li>Average win: +3R</li>
    <li>Average loss: -1R</li>
</ul>
<p><code>Expectancy = (0.40 × 3) − (0.60 × 1) = 1.20 − 0.60 = +0.60R</code></p>
<p>The system produces +0.6R per trade on average. Over 100 trades, this is +60R.</p>

<p><strong>Example 2 — Mean-reversion system:</strong></p>
<ul>
    <li>Win rate: 65%</li>
    <li>Average win: +1R</li>
    <li>Average loss: -1.5R</li>
</ul>
<p><code>Expectancy = (0.65 × 1) − (0.35 × 1.5) = 0.65 − 0.525 = +0.125R</code></p>
<p>The system produces +0.125R per trade. Positive but modest. Over 100 trades, +12.5R.</p>

<p><strong>Example 3 — High win rate, poor payoff:</strong></p>
<ul>
    <li>Win rate: 80%</li>
    <li>Average win: +0.5R</li>
    <li>Average loss: -2R</li>
</ul>
<p><code>Expectancy = (0.80 × 0.5) − (0.20 × 2) = 0.40 − 0.40 = 0R</code></p>
<p>Despite the high win rate, the system is breakeven. The poor payoff offsets the high win rate.</p>

<p><strong>Example 4 — Low win rate, excellent payoff:</strong></p>
<ul>
    <li>Win rate: 20%</li>
    <li>Average win: +5R</li>
    <li>Average loss: -1R</li>
</ul>
<p><code>Expectancy = (0.20 × 5) − (0.80 × 1) = 1.00 − 0.80 = +0.20R</code></p>
<p>Despite only winning 20% of the time, the system is profitable. Excellent payoffs can offset low win rates.</p>

<h3>Visual reference — Expectancy heat map</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Axes -->
  <line x1="80" y1="220" x2="470" y2="220" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="80" y1="30" x2="80" y2="220" stroke="#8b93a7" stroke-width="0.5"/>

  <!-- Axis labels -->
  <text x="275" y="245" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Win Rate (%)</text>
  <text x="40" y="125" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" transform="rotate(-90 40 125)">Payoff Ratio</text>

  <!-- X-axis labels -->
  <text x="130" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">30%</text>
  <text x="200" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">40%</text>
  <text x="270" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">50%</text>
  <text x="340" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">60%</text>
  <text x="410" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">70%</text>

  <!-- Y-axis labels -->
  <text x="70" y="60" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="end">4:1</text>
  <text x="70" y="110" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="end">2:1</text>
  <text x="70" y="160" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="end">1:1</text>
  <text x="70" y="210" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="end">0.5:1</text>

  <!-- Heat map cells: expectancy colors -->
  <!-- Row 4:1 -->
  <rect x="110" y="45" width="40" height="25" fill="#4ade80" fill-opacity="0.9"/>
  <rect x="180" y="45" width="40" height="25" fill="#4ade80" fill-opacity="0.9"/>
  <rect x="250" y="45" width="40" height="25" fill="#4ade80" fill-opacity="0.9"/>
  <rect x="320" y="45" width="40" height="25" fill="#4ade80" fill-opacity="0.9"/>
  <rect x="390" y="45" width="40" height="25" fill="#4ade80" fill-opacity="0.9"/>

  <!-- Row 2:1 -->
  <rect x="110" y="95" width="40" height="25" fill="#ef4444" fill-opacity="0.5"/>
  <rect x="180" y="95" width="40" height="25" fill="#ef4444" fill-opacity="0.3"/>
  <rect x="250" y="95" width="40" height="25" fill="#4ade80" fill-opacity="0.5"/>
  <rect x="320" y="95" width="40" height="25" fill="#4ade80" fill-opacity="0.7"/>
  <rect x="390" y="95" width="40" height="25" fill="#4ade80" fill-opacity="0.9"/>

  <!-- Row 1:1 -->
  <rect x="110" y="145" width="40" height="25" fill="#ef4444" fill-opacity="0.9"/>
  <rect x="180" y="145" width="40" height="25" fill="#ef4444" fill-opacity="0.7"/>
  <rect x="250" y="145" width="40" height="25" fill="#eab308" fill-opacity="0.7"/>
  <rect x="320" y="145" width="40" height="25" fill="#4ade80" fill-opacity="0.4"/>
  <rect x="390" y="145" width="40" height="25" fill="#4ade80" fill-opacity="0.6"/>

  <!-- Row 0.5:1 -->
  <rect x="110" y="195" width="40" height="25" fill="#ef4444" fill-opacity="0.9"/>
  <rect x="180" y="195" width="40" height="25" fill="#ef4444" fill-opacity="0.9"/>
  <rect x="250" y="195" width="40" height="25" fill="#ef4444" fill-opacity="0.9"/>
  <rect x="320" y="195" width="40" height="25" fill="#ef4444" fill-opacity="0.7"/>
  <rect x="390" y="195" width="40" height="25" fill="#ef4444" fill-opacity="0.5"/>

  <text x="270" y="30" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Green = positive expectancy</text>
</svg>

<h3>Interpreting expectancy</h3>
<ul>
    <li><strong>+0.5R or more</strong> — excellent. Rare in practice.</li>
    <li><strong>+0.2R to +0.5R</strong> — good. Produces meaningful profits over many trades.</li>
    <li><strong>+0.1R to +0.2R</strong> — modest. Profitable but requires patience.</li>
    <li><strong>0R to +0.1R</strong> — marginal. Vulnerable to small changes in win rate or payoff.</li>
    <li><strong>0R</strong> — breakeven. No profit before costs. After costs, negative.</li>
    <li><strong>Negative</strong> — losing. No position sizing can fix this.</li>
</ul>

<h3>Expectancy in R vs percent</h3>
<p>Expectancy can be measured in R-multiples or as a percentage of account per trade. The two are related:</p>
<p><code>Expectancy (percent) = Expectancy (R) × Risk per trade (percent)</code></p>
<p>Example: +0.5R expectancy with 1% risk per trade = +0.5% expected return per trade. Over 200 trades per year, +100% expected return (before costs).</p>

<h3>Expectancy vs average return</h3>
<p>Expectancy is not the same as average return. If your trades include outliers (one trade with +20R), the average return per trade is higher than expectancy. But the expectancy — the median-ish outcome — is more useful for understanding typical performance.</p>
<p>Two measures matter:</p>
<ul>
    <li><strong>Expectancy</strong> — the average outcome weighted by probability.</li>
    <li><strong>Average return per trade</strong> — the total return divided by number of trades.</li>
</ul>
<p>When outliers are frequent, average return is higher than expectancy. When outliers are rare, they're similar.</p>

<h3>Expectancy and confidence</h3>
<p>Expectancy is a probabilistic estimate. It tells you what to expect on average, but not what will happen next. Over 10 trades, results can deviate wildly from expectancy. Over 1000 trades, they converge.</p>
<p>Confidence intervals are useful:</p>
<ul>
    <li><strong>50 trades</strong> — expectancy is unreliable. Could be off by 50% or more.</li>
    <li><strong>100 trades</strong> — expectancy is roughly reliable. Could be off by 20–30%.</li>
    <li><strong>200 trades</strong> — expectancy is fairly reliable. Could be off by 10–15%.</li>
    <li><strong>500 trades</strong> — expectancy is reliable. Could be off by 5–10%.</li>
    <li><strong>1000+ trades</strong> — expectancy is very reliable.</li>
</ul>

<h2>Factual context</h2>
<p>The expectancy formula has been used in gambling for centuries and in trading for decades. It is a fundamental concept in both fields.</p>
<p>Van Tharp popularised expectancy in the trading community through his research on position sizing and system evaluation. His framework treats expectancy as the single most important metric for evaluating a trading strategy.</p>
<p>Ralph Vince's work formalised the mathematics of expectancy and extended it to portfolio-level analysis. His framework includes expectancy distributions, not just averages.</p>
<p>The concept is also central to Ed Thorp's work. His hedge fund achieved exceptional returns by exploiting small positive expectancies across thousands of trades.</p>
<p>Ed Thorp, describing the essence:</p>
<blockquote><strong>\"The key to long-term success is positive expectancy. Everything else — win rate, payoff ratio, position size — is secondary.\"</strong></blockquote>
<p>Thorp's point is the foundation of professional trading. Without positive expectancy, no amount of discipline or risk management produces profits.</p>
<p>Paul Tudor Jones, describing his approach:</p>
<blockquote><strong>\"I want every trade to have a positive expectancy. If the math isn't in my favour, I don't take the trade, no matter how good it looks.\"</strong></blockquote>
<p>Jones' discipline is the professional standard. Trades are taken when expectancy is positive, skipped when it's not.</p>
<p>Larry Hite, describing his framework:</p>
<blockquote><strong>\"The best traders are not the ones who win the most trades. They are the ones who make the most money per trade. That is expectancy.\"</strong></blockquote>
<p>Hite's point captures the essence. Win rate alone is meaningless. What matters is the combination of win rate and payoff, which is expectancy.</p>
<p>Bruce Kovner, describing his approach:</p>
<blockquote><strong>\"I look for trades where the potential reward justifies the risk. That's expectancy. When it's there, I take the trade. When it's not, I wait.\"</strong></blockquote>
<p>Kovner's approach is disciplined and mathematical. Expectancy is the filter that determines which trades to take.</p>
<p>Nassim Nicholas Taleb, whose work on risk has been widely influential, has criticised the emphasis on expectancy alone:</p>
<blockquote><strong>\"Expectancy is an average. Averages hide extreme events. A system with positive expectancy can still blow up if it has a fat left tail.\"</strong></blockquote>
<p>Taleb's critique is valid. Positive expectancy is necessary but not sufficient. The distribution of outcomes matters, not just the average.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Confusing expectancy with win rate.</strong> Win rate alone does not determine profitability. Expectancy does.</li>
    <li><strong>Using dollar-based expectancy.</strong> R-multiples are standard because they're account-size-independent.</li>
    <li><strong>Calculating from small samples.</strong> Fewer than 100 trades gives unreliable expectancy.</li>
    <li><strong>Assuming stable expectancy.</strong> Market conditions change. Expectancy that was positive last year may be negative now.</li>
    <li><strong>Ignoring distribution.</strong> Two systems with the same expectancy can have very different risk profiles.</li>
    <li><strong>Optimising for maximum expectancy.</strong> Higher expectancy often comes with higher variance. Balance both.</li>
    <li><strong>Not accounting for costs.</strong> Spreads and commissions reduce raw expectancy. Use net expectancy for real evaluation.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders track expectancy across multiple dimensions to identify the conditions where their edge is strongest:</p>
<ul>
    <li><strong>By setup type</strong> — which setups have the highest expectancy?</li>
    <li><strong>By market</strong> — which pairs produce the best expectancy?</li>
    <li><strong>By timeframe</strong> — which timeframes produce the best expectancy?</li>
    <li><strong>By session</strong> — which kill zones produce the best expectancy?</li>
    <li><strong>By market condition</strong> — trending vs ranging markets.</li>
    <li><strong>By day of week</strong> — some days are better than others.</li>
    <li><strong>By direction</strong> — long vs short setups may have different expectancy.</li>
</ul>
<p>Tracking these dimensions reveals patterns. Maybe your setup has strong expectancy in London but not NY. Maybe your system works in trending markets but fails in ranges. Detailed analysis allows specialisation — trading only in the conditions where your edge is strongest.</p>
<p>Another advanced concept: <strong>conditional expectancy</strong>. This is the expectancy given a specific condition (e.g., expectance when the daily trend aligns with the trade direction). Conditional expectancy is often much higher than unconditional expectancy, revealing where the real edge lies.</p>
<p>The most sophisticated traders use expectancy analysis to build portfolios. Trades with the highest conditional expectancy get larger positions. Trades with lower expectancy get smaller positions or are skipped entirely. This alignment of position size with edge is the practical application of expectancy theory.</p>
<p>Eventually, expectancy becomes a filter for all trading decisions. Every trade is evaluated against its expectancy. If expectancy is negative, skip. If positive, take it (with proper position sizing). If highly positive, take it with larger size. This framework removes emotion from trading and replaces it with mathematics.</p>
HTML,
        ],

        [
            'slug'   => 'the-win-rate-payoff-matrix',
            'title'  => 'The Win Rate / Payoff Matrix',
            'difficulty' => 'professional',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Understand how win rate and payoff interact\n" .
                "• Identify combinations that produce profits\n" .
                "• Design strategies with realistic metrics",
            'prerequisites' => 'The Expectancy Formula',
            'sort_order' => 3,
            'summary' => 'Win rate and payoff ratio are the two variables that determine expectancy. Different combinations can produce the same expectancy — a 70% win rate with 1:1 payoff is equivalent to a 30% win rate with 4:1 payoff. This lesson explores the full matrix of possibilities.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two traders. Trader A wins 70% of the time but only makes 1 dollar for every dollar risked. Trader B wins 30% of the time but makes 4 dollars for every dollar risked. Both are equally profitable. Different styles, same result.</p>
<p>The win rate / payoff matrix shows all the combinations that produce the same expectancy.</p>

<h2>Real-world analogy</h2>
<p>Think of a supermarket. A discount store sells many items at low margins. A luxury store sells few items at high margins. Both can be profitable — the profit per item and the number of items sold combine to determine the outcome.</p>

<h2>Professional explanation</h2>

<h3>The break-even relationship</h3>
<p>For any trade, the break-even win rate at a given payoff ratio is:</p>
<p><code>Break-even Win Rate = 1 / (1 + Payoff Ratio)</code></p>
<p>The matrix of break-even points:</p>
<table>
    <thead><tr><th>Payoff Ratio</th><th>Break-even Win Rate</th><th>Profitable at Higher Win Rates</th></tr></thead>
    <tbody>
        <tr><td>0.5:1</td><td>66.7%</td><td>Yes</td></tr>
        <tr><td>1:1</td><td>50%</td><td>Yes</td></tr>
        <tr><td>1.5:1</td><td>40%</td><td>Yes</td></tr>
        <tr><td>2:1</td><td>33.3%</td><td>Yes</td></tr>
        <tr><td>3:1</td><td>25%</td><td>Yes</td></tr>
        <tr><td>4:1</td><td>20%</td><td>Yes</td></tr>
        <tr><td>5:1</td><td>16.7%</td><td>Yes</td></tr>
        <tr><td>10:1</td><td>9.1%</td><td>Yes</td></tr>
    </tbody>
</table>

<h3>Equivalent combinations</h3>
<p>Different (win rate, payoff) combinations can produce the same expectancy. Example with +0.5R expectancy:</p>
<table>
    <thead><tr><th>Win Rate</th><th>Payoff Ratio</th><th>Expectancy</th></tr></thead>
    <tbody>
        <tr><td>75%</td><td>1:1</td><td>+0.5R</td></tr>
        <tr><td>60%</td><td>1.5:1</td><td>+0.5R</td></tr>
        <tr><td>50%</td><td>2:1</td><td>+0.5R</td></tr>
        <tr><td>40%</td><td>2.75:1</td><td>+0.5R</td></tr>
        <tr><td>30%</td><td>4:1</td><td>+0.5R</td></tr>
        <tr><td>25%</td><td>5:1</td><td>+0.5R</td></tr>
        <tr><td>20%</td><td>6.5:1</td><td>+0.5R</td></tr>
    </tbody>
</table>
<p>All these combinations produce the same expectancy. The strategy's style determines which one is appropriate.</p>

<h3>Visual reference — Expectancy curves</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Grid -->
  <line x1="60" y1="200" x2="470" y2="200" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="60" y1="30" x2="60" y2="200" stroke="#8b93a7" stroke-width="0.5"/>

  <!-- Y-axis labels -->
  <text x="40" y="200" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">20%</text>
  <text x="40" y="120" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">50%</text>
  <text x="40" y="40" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">80%</text>

  <!-- X-axis labels -->
  <text x="100" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">1:1</text>
  <text x="200" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">2:1</text>
  <text x="300" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">3:1</text>
  <text x="400" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">5:1</text>

  <!-- Break-even line -->
  <polyline points="60,200 130,120 200,80 270,60 340,45 410,35 460,30"
            fill="none" stroke="#f97316" stroke-width="2.5" stroke-dasharray="4,3"/>
  <text x="400" y="25" fill="#f97316" font-size="10" font-family="Inter,sans-serif">Break-even</text>

  <!-- +0.5R line -->
  <polyline points="60,160 130,60 200,30 270,20 340,15 410,10 460,8"
            fill="none" stroke="#4ade80" stroke-width="2.5"/>
  <text x="420" y="55" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">+0.5R</text>

  <!-- Axis labels -->
  <text x="275" y="235" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Payoff Ratio</text>
  <text x="30" y="120" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" transform="rotate(-90 30 120)">Win Rate</text>
</svg>

<h3>Which combination is best?</h3>
<p>No single combination is universally best. The optimal combination depends on:</p>

<h4>1. Psychological tolerance</h4>
<ul>
    <li><strong>High win rate, low payoff</strong> — psychologically easier. Frequent wins, small profits. But occasional large losses hurt.</li>
    <li><strong>Low win rate, high payoff</strong> — psychologically harder. Frequent losses, occasional big wins. Requires patience through losing streaks.</li>
</ul>

<h4>2. Market conditions</h4>
<ul>
    <li><strong>Trending markets</strong> — favour low win rate, high payoff. Trends produce large moves.</li>
    <li><strong>Ranging markets</strong> — favour high win rate, low payoff. Ranges produce small, frequent moves.</li>
</ul>

<h4>3. Strategy style</h4>
<ul>
    <li><strong>Trend following</strong> — typically 35–45% win rate, 2:1 to 5:1 payoff.</li>
    <li><strong>Mean reversion</strong> — typically 60–75% win rate, 1:1 to 1.5:1 payoff.</li>
    <li><strong>Breakout</strong> — typically 40–50% win rate, 2:1 to 3:1 payoff.</li>
    <li><strong>Scalping</strong> — typically 60–70% win rate, 0.5:1 to 1:1 payoff.</li>
</ul>

<h4>4. Time availability</h4>
<ul>
    <li><strong>Full-time traders</strong> — can handle high-frequency strategies with frequent small wins.</li>
    <li><strong>Part-time traders</strong> — often prefer high-payoff strategies requiring fewer trades.</li>
</ul>

<h3>The danger of high win rate</h3>
<p>High win rate strategies are psychologically appealing but can hide risk. A 90% win rate strategy that loses everything on the 10% losing trades is dangerous. The high win rate provides false comfort.</p>
<p>Key point: win rate alone tells you nothing. It must be paired with payoff ratio to determine viability.</p>

<h3>The danger of low win rate</h3>
<p>Low win rate strategies (20–30%) require psychological endurance. A run of 10 consecutive losses is common with a 30% win rate. Many traders abandon profitable strategies because they can't tolerate the losing streaks.</p>
<p>Key point: the strategy's expectancy must be strong enough to survive the inevitable losing streaks.</p>

<h3>Designing your combination</h3>
<ol>
    <li><strong>Identify your psychological profile.</strong> Do you tolerate losing streaks better than small wins? Or vice versa?</li>
    <li><strong>Choose a strategy style</strong> that matches your profile.</li>
    <li><strong>Backtest</strong> to estimate win rate and payoff.</li>
    <li><strong>Calculate expectancy.</strong></li>
    <li><strong>Test with small size</strong> before scaling.</li>
    <li><strong>Adapt based on results.</strong> The optimal combination may not be what you expected.</li>
</ol>

<h2>Factual context</h2>
<p>The win rate / payoff matrix has been understood since the earliest days of probability theory. Its application to trading was formalised in the 20th century by researchers like Ralph Vince and Van Tharp.</p>
<p>Different successful traders have used different combinations:</p>
<ul>
    <li><strong>Trend followers</strong> like the Turtle Traders — 35–40% win rate with 2:1 to 4:1 payoff.</li>
    <li><strong>Mean-reversion traders</strong> like Marty Schwartz — 60–70% win rate with 1:1 payoff.</li>
    <li><strong>Breakout traders</strong> like Jesse Livermore — 40–45% win rate with 3:1+ payoff.</li>
    <li><strong>Scalpers</strong> like Linda Raschke — 60–70% win rate with 0.5:1 payoff.</li>
</ul>
<p>All these approaches can be profitable. The key is the combination, not any single metric.</p>
<p>Paul Tudor Jones, describing his preference:</p>
<blockquote><strong>\"I would rather have a 30% win rate with a 5:1 payoff than a 70% win rate with a 1:1 payoff. The first is much more profitable.\"</strong></blockquote>
<p>Jones' preference for high payoff over high win rate is mathematically correct. Expectancy is the product, not either input alone.</p>
<p>Marty Schwartz, on the other hand, preferred higher win rates:</p>
<blockquote><strong>\"I could not tolerate losing 60% of my trades. I needed to win most of the time. So I developed strategies with high win rates, even if the payoffs were smaller.\"</strong></blockquote>
<p>Schwartz's approach reflects the psychological dimension. The "best" combination is the one you can trade consistently.</p>
<p>Bruce Kovner, describing his preference:</p>
<blockquote><strong>\"I want to be right more often than not. But I also want my winners to be much larger than my losers. It's the combination that matters.\"</strong></blockquote>
<p>Kovner's framework combines both high win rate and high payoff — but that combination is difficult to achieve. Most strategies must choose one or the other.</p>
<p>Van Tharp's research on successful traders found that both high-win-rate and high-payoff traders can succeed. The determining factor was not the combination they chose, but their consistency in applying it.</p>
<p>Ed Seykota, describing his philosophy:</p>
<blockquote><strong>\"Cut losses short and let winners run.\"</strong></blockquote>
<p>Seykota's maxim produces a low win rate with high payoff. Most trades are small losses; occasional trades are huge winners. This trend-following style has produced enormous returns over decades.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Optimising for win rate alone.</strong> A 90% win rate means nothing if the 10% loss is 20× the typical win.</li>
    <li><strong>Optimising for payoff alone.</strong> A 10:1 payoff with a 5% win rate is unprofitable (expectancy = -0.45R).</li>
    <li><strong>Ignoring psychological fit.</strong> The mathematically optimal combination may not be tradeable by you.</li>
    <li><strong>Assuming you can change your win rate.</strong> Win rate is largely determined by the strategy. Don't expect to improve it after the fact.</li>
    <li><strong>Assuming stable metrics.</strong> Win rate and payoff change with market conditions.</li>
    <li><strong>Comparing incompatible strategies.</strong> A scalping system and a trend-following system have different metric profiles. Don't compare them directly.</li>
    <li><strong>Not testing in demo.</strong> The combination that looks good on paper may not work in practice.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often blend multiple strategies to improve overall metrics:</p>
<ul>
    <li><strong>Trend following + mean reversion</strong> — combining high win rate strategies with high payoff strategies.</li>
    <li><strong>Different timeframes</strong> — scalping (high win rate) alongside swing trading (high payoff).</li>
    <li><strong>Different markets</strong> — FX (one profile) alongside commodities (another profile).</li>
</ul>
<p>The blended portfolio can produce a better overall expectancy and smoother equity curve than any single strategy.</p>
<p>Another advanced technique: <strong>adaptive payoff targeting</strong>. In strongly trending markets, targets are extended (higher payoff ratio). In ranging markets, targets are trimmed (lower payoff ratio but higher win rate). This adaptation improves performance across market conditions.</p>
<p>The most sophisticated traders adjust their strategies based on market regime. Trend-following in trends, mean-reversion in ranges. The metrics (win rate and payoff) change with the regime, but the underlying expectancy stays positive.</p>
<p>Ultimately, the goal is not a specific combination of win rate and payoff — it's a positive expectancy system that matches your psychological profile and market conditions. Many combinations can achieve this. The discipline is in choosing one, testing it, and applying it consistently.</p>
HTML,
        ],

        [
            'slug'   => 'position-sizing-by-expectancy',
            'title'  => 'Position Sizing by Expectancy',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Adjust position size based on expectancy\n" .
                "• Balance expectancy, variance, and drawdown\n" .
                "• Apply risk-per-trade adjustments dynamically",
            'prerequisites' => 'The Win Rate / Payoff Matrix',
            'sort_order' => 4,
            'summary' => 'Not all trades have equal expectancy. By adjusting position size based on expectancy, you can align risk with edge — larger positions on higher-expectancy trades, smaller on lower-expectancy trades. This improves overall portfolio returns.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two trades. Trade A has a 40% win rate with 4:1 payoff (expectancy = +1.0R). Trade B has a 60% win rate with 1.5:1 payoff (expectancy = +0.3R). Trade A is three times better. Why risk the same amount on both?</p>
<p>Position sizing by expectancy means allocating more capital to higher-expectancy trades and less to lower-expectancy ones.</p>

<h2>Real-world analogy</h2>
<p>Think of a farmer allocating land. Some fields yield more than others. The farmer plants more crops in high-yield fields and fewer in low-yield ones. Trading works the same way.</p>

<h2>Professional explanation</h2>

<h3>The basic principle</h3>
<p>Standard position sizing risks a fixed percentage per trade, regardless of trade quality. But if some trades have higher expectancy, they deserve larger positions.</p>
<p>Formula:</p>
<p><code>Position size = (Account × Base Risk %) × Expectancy Multiplier</code></p>
<p>Where the multiplier is proportional to the trade's expectancy relative to the average.</p>

<h3>Simple scaling approach</h3>
<table>
    <thead><tr><th>Trade Quality</th><th>Expectancy Range</th><th>Risk Multiplier</th></tr></thead>
    <tbody>
        <tr><td>Premium</td><td>+0.8R or higher</td><td>1.5× (1.5% for 1% base)</td></tr>
        <tr><td>High</td><td>+0.5R to +0.8R</td><td>1.25× (1.25% for 1% base)</td></tr>
        <tr><td>Standard</td><td>+0.3R to +0.5R</td><td>1.0× (1% for 1% base)</td></tr>
        <tr><td>Moderate</td><td>+0.1R to +0.3R</td><td>0.75× (0.75% for 1% base)</td></tr>
        <tr><td>Marginal</td><td>0R to +0.1R</td><td>0.5× (0.5% for 1% base)</td></tr>
        <tr><td>Negative</td><td>Below 0R</td><td>0× (skip trade)</td></tr>
    </tbody>
</table>

<h3>How to estimate trade expectancy</h3>
<p>For a specific trade, estimate expectancy based on:</p>
<ol>
    <li><strong>Setup type</strong> — what is this specific setup's historical expectancy?</li>
    <li><strong>Market conditions</strong> — is the current market favourable for this setup?</li>
    <li><strong>Confluence factors</strong> — how many independent factors align?</li>
    <li><strong>Higher-timeframe alignment</strong> — does the trade align with the higher timeframe?</li>
    <li><strong>Session timing</strong> — is the trade occurring in a favourable kill zone?</li>
    <li><strong>Recent performance</strong> — has the setup been working recently, or is it in a cold streak?</li>
</ol>

<h3>Visual reference — Expectancy-based sizing</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Bars -->
  <rect x="80" y="140" width="40" height="60" fill="#4ade80" fill-opacity="0.9"/>
  <text x="100" y="130" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1.5%</text>
  <text x="100" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Premium</text>

  <rect x="180" y="150" width="40" height="50" fill="#4ade80" fill-opacity="0.7"/>
  <text x="200" y="140" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1.25%</text>
  <text x="200" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">High</text>

  <rect x="280" y="160" width="40" height="40" fill="#eab308" fill-opacity="0.7"/>
  <text x="300" y="150" fill="#eab308" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1%</text>
  <text x="300" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Standard</text>

  <rect x="380" y="180" width="40" height="20" fill="#f97316" fill-opacity="0.7"/>
  <text x="400" y="170" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">0.5%</text>
  <text x="400" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Marginal</text>

  <text x="250" y="30" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Position size scales with expectancy</text>
  <text x="250" y="55" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Base risk: 1% per trade</text>
</svg>

<h3>Expectancy vs variance trade-off</h3>
<p>Adjusting position size by expectancy is not without trade-offs. High-expectancy trades often have higher variance (larger swings). If you increase position size on these trades, you also increase portfolio variance.</p>
<p>The balance:</p>
<ul>
    <li><strong>Higher expectancy + higher variance</strong> — modest size increase (1.25×, not 2×).</li>
    <li><strong>Higher expectancy + similar variance</strong> — larger size increase (1.5× or more).</li>
    <li><strong>Lower expectancy + lower variance</strong> — standard size or slightly reduced.</li>
    <li><strong>Lower expectancy + higher variance</strong> — significant size reduction or skip.</li>
</ul>

<h3>Static vs dynamic sizing</h3>

<h4>Static sizing</h4>
<p>Risk the same percentage on every trade. Simple, consistent, easy to implement. Works well for most traders.</p>

<h4>Dynamic sizing</h4>
<p>Adjust risk percentage based on trade quality. More complex but potentially higher returns. Requires accurate expectancy estimates.</p>
<p>Most traders should start with static sizing and only move to dynamic sizing after establishing a proven edge.</p>

<h3>Kelly-optimal sizing by expectancy</h3>
<p>For those comfortable with the mathematics, Kelly-optimal sizing by expectancy is the theoretical optimum:</p>
<p><code>Kelly % = (Win Rate × Payoff Ratio − Loss Rate) / Payoff Ratio</code></p>
<p>Simplified: <code>Kelly % = Expectancy / Payoff Ratio</code></p>
<p>For a trade with +0.5R expectancy and 2:1 payoff:</p>
<p><code>Kelly % = 0.5 / 2 = 0.25 (25%)</code></p>
<p>As we covered in the previous module, full Kelly is too aggressive. Fractional Kelly (1/10 to 1/4) is the practical approach.</p>

<h3>Practical implementation</h3>
<p>A practical implementation of expectancy-based sizing:</p>
<ol>
    <li><strong>Establish a base risk</strong> (typically 1%).</li>
    <li><strong>Categorise each trade</strong> as premium, high, standard, moderate, or marginal.</li>
    <li><strong>Apply the multiplier</strong> from the table above.</li>
    <li><strong>Check portfolio heat</strong> — total heat must remain within limits.</li>
    <li><strong>Adjust for correlation</strong> — correlated trades share the same bucket.</li>
    <li><strong>Round to the nearest 0.01 lots.</strong></li>
</ol>

<h3>Worked example</h3>
<p>Trader with $20,000 account, base risk 1% ($200 per trade):</p>
<ul>
    <li><strong>Trade A</strong> — Premium setup (expectancy +1.0R). Risk = 1.5% = $300.</li>
    <li><strong>Trade B</strong> — Standard setup (expectancy +0.4R). Risk = 1.0% = $200.</li>
    <li><strong>Trade C</strong> — Moderate setup (expectancy +0.2R). Risk = 0.75% = $150.</li>
</ul>
<p>Total heat if all open: 3.25% of account. Within the 5% limit.</p>

<h3>The case against dynamic sizing</h3>
<p>Dynamic sizing has critics. Arguments against it:</p>
<ul>
    <li><strong>Estimates are noisy.</strong> Expectancy estimates from few trades are unreliable.</li>
    <li><strong>Adds complexity.</strong> More decisions = more chances for error.</li>
    <li><strong>Reduces consistency.</strong> Variable position sizes make performance harder to evaluate.</li>
    <li><strong>Psychological burden.</strong> Larger positions on "premium" trades are harder to hold emotionally.</li>
    <li><strong>Diminishing returns.</strong> The improvement over static sizing is often modest.</li>
</ul>
<p>For many traders, static sizing with 1% risk per trade is the practical optimum. Dynamic sizing only adds value when expectancy estimates are highly reliable.</p>

<h2>Factual context</h2>
<p>Position sizing by expectancy is an advanced technique used by professional traders and quant funds. The mathematics are well-established but require accurate estimates of conditional expectancy.</p>
<p>Van Tharp's research emphasised the importance of position sizing as the primary determinant of returns. His framework includes conditional sizing based on trade quality — higher risk on higher-expectancy trades.</p>
<p>Ralph Vince's work on optimal f provides a mathematical framework for expectancy-based sizing. His research showed that the optimal fraction depends on the distribution of outcomes, not just the average expectancy.</p>
<p>Ed Thorp's approach to blackjack was essentially expectancy-based sizing. He bet larger when the count was favourable (higher expectancy) and smaller when it was unfavourable. His hedge fund applied the same principle to trading.</p>
<p>Ed Thorp, describing his approach:</p>
<blockquote><strong>\"When the odds are in my favour, I bet more. When they are neutral, I bet less. When they are against me, I don't bet at all. The size follows the edge.\"</strong></blockquote>
<p>Thorp's approach is the essence of expectancy-based sizing. Size proportional to edge.</p>
<p>Paul Tudor Jones, describing his approach:</p>
<blockquote><strong>\"I want my biggest bets to be my best trades. If I have a setup I have seen work a hundred times, I bet more than on a setup I have seen work ten times.\"</strong></blockquote>
<p>Jones' discipline is a practical application of expectancy-based sizing. Higher confidence in the setup leads to larger positions.</p>
<p>Bruce Kovner, describing his own approach:</p>
<blockquote><strong>\"I size my bets based on how much I believe in the trade. Not on my hope — on my analysis and the historical performance of the setup.\"</strong></blockquote>
<p>Kovner's approach balances conviction with evidence. Position size follows from analysis, not emotion.</p>
<p>Larry Hite, describing the challenge:</p>
<blockquote><strong>\"The temptation is to size every trade the same. But the edge is not the same on every trade. Adjusting size to edge is what separates professionals from amateurs.\"</strong></blockquote>
<p>Hite's point captures the practical application. The edge varies; position size should vary accordingly.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Sizing purely by conviction.</strong> Conviction is subjective. Expectancy is objective.</li>
    <li><strong>Making the multiplier too large.</strong> 3× or 5× multipliers create excessive risk.</li>
    <li><strong>Estimating expectancy from few trades.</strong> With less than 30 trades per setup, estimates are unreliable.</li>
    <li><strong>Ignoring portfolio heat.</strong> Dynamic sizing can quickly violate heat limits.</li>
    <li><strong>Forgetting correlation.</strong> Multiple "premium" trades in correlated pairs = one giant position.</li>
    <li><strong>Using dynamic sizing without static foundation.</strong> Master static sizing first.</li>
    <li><strong>Overtrading based on premium assessment.</strong> Not every trade is premium. Be honest.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use more sophisticated approaches than simple expectancy multipliers:</p>
<ul>
    <li><strong>Conditional expectancy models</strong> — expectancy estimated from multiple factors.</li>
    <li><strong>Bayesian updating</strong> — expectancy estimates adjusted as new trades come in.</li>
    <li><strong>Regime-conditional sizing</strong> — different sizing in trending vs ranging markets.</li>
    <li><strong>Time-of-day adjustment</strong> — different sizing during different kill zones.</li>
    <li><strong>Kelly-optimal with drawdown constraint</strong> — Kelly sizing capped by maximum drawdown tolerance.</li>
</ul>
<p>But these are refinements of the basic principle: size proportional to edge. The core idea is simple. The implementations can be complex.</p>
<p>For most retail traders, the practical approach is:</p>
<ol>
    <li>Establish a base risk (typically 1%).</li>
    <li>Have two or three tiers of trade quality (standard, high, premium).</li>
    <li>Adjust risk by 25% up or down based on tier.</li>
    <li>Respect portfolio heat limits.</li>
    <li>Track results to refine the tiers.</li>
</ol>
<p>This simple structure captures most of the benefit of expectancy-based sizing without excessive complexity. Simplicity and consistency beat complexity and optimisation.</p>
HTML,
        ],

        [
            'slug'   => 'expectancy-across-timeframes',
            'title'  => 'Expectancy Across Timeframes and Markets',
            'difficulty' => 'professional',
            'estimated_duration" => 11',
            'learning_objectives' =>
                "• Understand how expectancy varies across timeframes\n" .
                "• Compare expectancy across markets\n" .
                "• Choose the best timeframe/market for your strategy",
            'prerequisites' => 'Position Sizing by Expectancy',
            'sort_order' => 5,
            'summary' => 'Expectancy varies significantly across timeframes and markets. A strategy that works on the H4 chart may fail on the M5. A strategy that works on EUR/USD may fail on AUD/NZD. This lesson explores how to identify the best fit for your strategy.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a fishing rod that catches fish in a lake but not in the ocean. The rod isn't bad — it's just suited for a specific environment. Strategies work the same way. A trend-following system that works on the daily chart may fail on the M5.</p>
<p>Expectancy analysis reveals which timeframe and market produce the best results for each strategy.</p>

<h2>Real-world analogy</h2>
<p>Think of a chef whose recipes work in a French restaurant but not in a Mexican one. The chef isn't bad; the cuisine is simply different. Trading strategies have the same specificity.</p>

<h2>Professional explanation</h2>

<h3>Expectancy by timeframe</h3>
<p>The same strategy usually has different expectancy across timeframes:</p>
<table>
    <thead><tr><th>Timeframe</th><th>Typical Expectancy Range</th><th>Best For</th></tr></thead>
    <tbody>
        <tr><td>Monthly/Weekly</td><td>+0.5R to +1.5R</td><td>Position trading, macro trends</td></tr>
        <tr><td>Daily</td><td>+0.3R to +0.8R</td><td>Swing trading, trend following</td></tr>
        <tr><td>H4</td><td>+0.2R to +0.6R</td><td>Swing trading, intraday swings</td></tr>
        <tr><td>H1</td><td>+0.15R to +0.4R</td><td>Day trading, intraday trends</td></tr>
        <tr><td>M15</td><td>+0.1R to +0.3R</td><td>Day trading, scalping</td></tr>
        <tr><td>M5</td><td>+0.05R to +0.2R</td><td>Scalping</td></tr>
        <tr><td>M1</td><td>-0.1R to +0.1R</td><td>Very difficult to profit</td></tr>
    </tbody>
</table>
<p>The pattern: expectancy generally decreases as timeframe decreases. This is because:</p>
<ul>
    <li><strong>Costs are proportionally larger on small timeframes.</strong> A 1-pip spread is 1% of a 100-pip stop but 20% of a 5-pip stop.</li>
    <li><strong>Noise is higher on small timeframes.</strong> Random price fluctuations dominate on the M1/M5.</li>
    <li><strong>Institutional edge is smaller on small timeframes.</strong> Large orders are executed over time, not on the M5.</li>
    <li><strong>Signal-to-noise ratio is worse.</strong> The same pattern on the M5 has more false signals than on the daily.</li>
</ul>

<h3>Visual reference — Expectancy by timeframe</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Grid -->
  <line x1="60" y1="200" x2="470" y2="200" stroke="#8b93a7" stroke-width="0.5"/>
  <line x1="60" y1="120" x2="470" y2="120" stroke="#8b93a7" stroke-width="0.5" stroke-dasharray="3,3"/>

  <!-- Zero line -->
  <line x1="60" y1="150" x2="470" y2="150" stroke="#f97316" stroke-width="0.8"/>
  <text x="475" y="154" fill="#f97316" font-size="10" font-family="Inter,sans-serif">0R</text>

  <!-- Bars showing expectancy by timeframe -->
  <rect x="80" y="60" width="30" height="90" fill="#4ade80" fill-opacity="0.8"/>
  <text x="95" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Monthly</text>

  <rect x="140" y="70" width="30" height="80" fill="#4ade80" fill-opacity="0.8"/>
  <text x="155" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Weekly</text>

  <rect x="200" y="85" width="30" height="65" fill="#4ade80" fill-opacity="0.7"/>
  <text x="215" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Daily</text>

  <rect x="260" y="100" width="30" height="50" fill="#4ade80" fill-opacity="0.6"/>
  <text x="275" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">H4</text>

  <rect x="320" y="110" width="30" height="40" fill="#eab308" fill-opacity="0.7"/>
  <text x="335" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">H1</text>

  <rect x="380" y="130" width="30" height="20" fill="#f97316" fill-opacity="0.7"/>
  <text x="395" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">M15</text>

  <rect x="440" y="145" width="30" height="5" fill="#ef4444" fill-opacity="0.7"/>
  <text x="455" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">M5</text>

  <text x="250" y="30" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle">Expectancy decreases as timeframe decreases</text>
</svg>

<h3>Expectancy by market</h3>
<p>Different markets have different characteristics that affect expectancy:</p>
<table>
    <thead><tr><th>Market</th><th>Characteristics</th><th>Best For</th></tr></thead>
    <tbody>
        <tr><td>Major FX pairs</td><td>Tight spreads, high liquidity, trending</td><td>Trend following, breakout</td></tr>
        <tr><td>Minor FX pairs</td><td>Slightly wider spreads, less liquid</td><td>Swing trading</td></tr>
        <tr><td>Exotic FX pairs</td><td>Wide spreads, thin liquidity</td><td>Position trading only</td></tr>
        <tr><td>Indices</td><td>Strong trends, gap risk</td><td>Trend following</td></tr>
        <tr><td>Commodities</td><td>Volatile, mean-reverting</td><td>Mean reversion, momentum</td></tr>
        <tr><td>Crypto</td><td>Extremely volatile, 24/7</td><td>Trend following, breakout</td></tr>
    </tbody>
</table>

<h3>How to identify the best fit</h3>
<ol>
    <li><strong>Backtest your strategy on multiple timeframes.</strong> Use at least 100 trades per timeframe.</li>
    <li><strong>Calculate expectancy for each.</strong> Focus on net expectancy (after costs).</li>
    <li><strong>Note the variance.</strong> Two timeframes with the same expectancy can have very different variance.</li>
    <li><strong>Compare risk-adjusted metrics.</strong> Sharpe ratio or MAR ratio normalises for variance.</li>
    <li><strong>Focus on the top 1–2 combinations.</strong> Don't try to trade everything.</li>
</ol>

<h3>Trade-off: frequency vs expectancy</h3>
<p>Higher timeframes have higher expectancy per trade but fewer trades per year. Lower timeframes have lower expectancy per trade but more trades. The total annual return is the product:</p>
<p><code>Annual Return = Expectancy per trade × Trades per year × Risk per trade</code></p>
<p>Example:</p>
<ul>
    <li><strong>Daily strategy:</strong> +0.5R expectancy × 100 trades/year × 1% = +50% annual return.</li>
    <li><strong>H1 strategy:</strong> +0.3R expectancy × 500 trades/year × 1% = +150% annual return (theoretical).</li>
</ul>
<p>The H1 strategy produces higher theoretical returns but requires 5× more trades. The extra trades mean more time commitment, more exposure to costs, and more psychological stress.</p>

<h3>The cost consideration</h3>
<p>Trading costs are fixed per trade. On lower timeframes, costs consume a larger proportion of expectancy:</p>
<table>
    <thead><tr><th>Timeframe</th><th>Avg Stop</th><th>Spread Cost as % of Stop</th></tr></thead>
    <tbody>
        <tr><td>Daily</td><td>100 pips</td><td>0.5%</td></tr>
        <tr><td>H4</td><td>50 pips</td><td>1.0%</td></tr>
        <tr><td>H1</td><td>30 pips</td><td>1.7%</td></tr>
        <tr><td>M15</td><td>15 pips</td><td>3.3%</td></tr>
        <tr><td>M5</td><td>8 pips</td><td>6.3%</td></tr>
        <tr><td>M1</td><td>4 pips</td><td>12.5%</td></tr>
    </tbody>
</table>
<p>This is why scalping is so difficult. The spread consumes 6%+ of the average trade on the M5, and 12%+ on the M1. Only very high-frequency strategies with minimal slippage can overcome this.</p>

<h3>Finding your combination</h3>
<p>There is no universal "best" timeframe or market. The best is the one that:</p>
<ol>
    <li><strong>Matches your available time.</strong> If you can only trade 1 hour per day, the M5 is not practical.</li>
    <li><strong>Produces positive expectancy</strong> on your backtest.</li>
    <li><strong>Fits your psychological profile.</strong> Some traders handle frequent small wins; others prefer fewer large wins.</li>
    <li><strong>Works across market conditions.</strong> A strategy that only works in trending markets is not viable long-term.</li>
    <li><strong>Has acceptable variance.</strong> High expectancy with high variance is harder to trade than moderate expectancy with low variance.</li>
</ol>

<h2>Factual context</h2>
<p>Research on timeframe expectancy has been conducted by academic researchers and practitioner research firms. The consistent finding: expectancy decreases as timeframe decreases, primarily due to increasing cost proportion and noise.</p>
<p>Studies by the Bank for International Settlements and other research organisations have found that intraday trading is significantly harder than swing or position trading. The BIS has estimated that 70–90% of retail day traders lose money, compared to 60–80% of swing traders.</p>
<p>The higher failure rate on lower timeframes is not a matter of skill — it is a matter of mathematics. Costs and noise consume a larger proportion of returns, and the edge for retail traders is smaller.</p>
<p>Al Brooks, who trades price action on multiple timeframes, has emphasised:</p>
<blockquote><strong>\"The higher the timeframe, the more reliable the signal. A setup on the daily chart is worth ten on the 5-minute chart.\"</strong></blockquote>
<p>Brooks' preference for higher timeframes reflects the mathematics. Higher timeframes have less noise, lower cost proportion, and more reliable patterns.</p>
<p>Paul Tudor Jones, whose trading career often involved position trading:</p>
<blockquote><strong>\"I can't make money trading the 1-minute. There is too much noise, too much cost, too little edge. I need a bigger timeframe to see the market clearly.\"</strong></blockquote>
<p>Jones' observation aligns with the research. Lower timeframes make the game significantly harder.</p>
<p>Bruce Kovner, describing his preference:</p>
<blockquote><strong>\"I trade the daily and weekly charts. Below that, I can't see the market clearly enough to make good decisions.\"</strong></blockquote>
<p>Kovner's preference for higher timeframes is common among professional traders. It reflects the mathematical reality: higher timeframes offer better signal-to-noise ratios.</p>
<p>Larry Hite, describing his approach:</p>
<blockquote><strong>\"The best trades are the ones where you can see the setup on multiple timeframes. If it only works on the M5, it's probably not a real trade.\"</strong></blockquote>
<p>Hite's point combines timeframe and confluence. Multi-timeframe agreement is a strong signal, especially when higher timeframes confirm the setup.</p>
<p>Ed Seykota, describing his preference:</p>
<blockquote><strong>\"The longer the timeframe, the better I do. Trends on the monthly and weekly charts are the most reliable.\"</strong></blockquote>
<p>Seykota's preference for higher timeframes has produced decades of strong returns. His experience reflects the mathematics: higher timeframes are more reliable.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Assuming lower timeframes = more money.</strong> More trades does not mean more profit. Costs and noise often eliminate the edge.</li>
    <li><strong>Using the same strategy across all timeframes.</strong> Strategies are timeframe-specific. A trend-following system works on higher timeframes but not lower.</li>
    <li><strong>Ignoring cost proportion.</strong> A 5-pip stop with a 1-pip spread has 20% cost ratio. This is unprofitable.</li>
    <li><strong>Trading exotic pairs on low timeframes.</strong> Wide spreads make low-timeframe trading on exotics nearly impossible.</li>
    <li><strong>Comparing different markets with the same expectancy.</strong> Two markets can have the same expectancy but very different variance.</li>
    <li><strong>Not tracking expectancy by market.</strong> Your strategy may work on EUR/USD but fail on GBP/JPY. Track separately.</li>
    <li><strong>Over-diversifying.</strong> Trading too many markets makes monitoring difficult and reduces focus.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often specialise in a specific timeframe and market combination. A trader might specialise in daily swings on major FX pairs, or weekly trends on indices, or H4 setups on commodities.</p>
<p>The specialisation allows depth. A specialist knows the typical behaviour of their market, the specific patterns that work, the typical volatility, and the seasonal characteristics. This depth is difficult to achieve across multiple timeframes and markets.</p>
<p>Another advanced concept: <strong>timeframe-specific strategies</strong>. Some strategies work better on specific timeframes:</p>
<ul>
    <li><strong>Trend following</strong> — daily, weekly, monthly.</li>
    <li><strong>Breakout</strong> — H4, daily.</li>
    <li><strong>Pullback trading</strong> — H1, H4.</li>
    <li><strong>Range trading</strong> — H1, H4.</li>
    <li><strong>Scalping</strong> — M1, M5 (for experienced traders only).</li>
</ul>
<p>The general rule: the more specific the setup, the more reliable on higher timeframes. Complex patterns on the M1 are usually noise; the same patterns on the daily are meaningful.</p>
<p>For most retail traders, the practical recommendation is to trade on higher timeframes (H4 and above). The mathematics of cost, noise, and edge all favour higher timeframes. The lower timeframes are for specialists with exceptional discipline and infrastructure.</p>
HTML,
        ],

        [
            'slug'   => 'setting-realistic-targets',
            'title'  => 'Setting Realistic Targets',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Set realistic performance targets\n" .
                "• Avoid common target-setting mistakes\n" .
                "• Project returns based on expectancy",
            'prerequisites' => 'Expectancy Across Timeframes and Markets',
            'sort_order' => 6,
            'summary' => 'Realistic targets are essential for long-term success. Unrealistic targets lead to over-trading, over-sizing, and eventual blow-ups. This lesson covers how to project returns from expectancy and set targets that align with your strategy and psychology.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine planning a road trip. You estimate the distance, the speed, and the number of stops. Then you set a realistic arrival time. Trading works the same way. Expectancy tells you what to expect per trade. Trades per year tells you how many opportunities you have. The combination tells you the projected return.</p>
<p>Setting realistic targets starts with this math.</p>

<h2>Real-world analogy</h2>
<p>Think of a farmer planning the harvest. The farmer knows the yield per acre, the number of acres, and the season length. Combining these gives a realistic projection. Trading works the same way.</p>

<h2>Professional explanation</h2>

<h3>The projection formula</h3>
<p><code>Expected Annual Return = Expectancy × Trades per Year × Risk per Trade</code></p>
<p>Example:</p>
<ul>
    <li>Expectancy: +0.5R</li>
    <li>Trades per year: 200</li>
    <li>Risk per trade: 1%</li>
</ul>
<p><code>Expected Annual Return = 0.5 × 200 × 0.01 = 1.0 = 100%</code></p>
<p>This is a theoretical projection before costs and slippage. Real returns are usually lower.</p>

<h3>The role of compounding</h3>
<p>The projection above assumes fixed position sizes. With compounding (position size based on current equity), returns can be higher:</p>
<p><code>Compounded Return = (1 + Expectancy × Risk)^Trades − 1</code></p>
<p>For the example above:</p>
<p><code>Compounded Return = (1 + 0.005)^200 − 1 = 1.005^200 − 1 = 2.71 − 1 = 1.71 = 171%</code></p>
<p>Compounding roughly doubles the return for this scenario. But it also increases drawdowns proportionally.</p>

<h3>Realistic return ranges</h3>
<p>What is realistic for retail traders? The honest answer varies, but here are benchmarks:</p>
<table>
    <thead><tr><th>Tier</th><th>Annual Return</th><th>Trader Type</th></tr></thead>
    <tbody>
        <tr><td>Exceptional</td><td>50%+</td><td>Top 1% of retail traders</td></tr>
        <tr><td>Very Good</td><td>30–50%</td><td>Skilled traders with discipline</td></tr>
        <tr><td>Good</td><td>15–30%</td><td>Consistent profitable traders</td></tr>
        <tr><td>Acceptable</td><td>5–15%</td><td>Most profitable retail traders</td></tr>
        <tr><td>Breakeven</td><td>0–5%</td><td>Learning or struggling</td></tr>
        <tr><td>Loss</td><td>Negative</td><td>Most retail traders</td></tr>
    </tbody>
</table>
<p>Research consistently shows that 70–80% of retail traders lose money. Of those who are profitable, most fall into the "acceptable" tier. Returns above 50% annually are exceptional and usually come with high variance.</p>

<h3>Visual reference — Return distribution</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Distribution curve -->
  <polyline points="40,200 100,180 160,120 220,60 280,40 340,60 400,120 460,200"
            fill="none" stroke="#5b7cfa" stroke-width="2.5"/>

  <!-- Axis -->
  <line x1="30" y1="200" x2="470" y2="200" stroke="#8b93a7" stroke-width="0.5"/>
  <text x="250" y="225" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Annual Return</text>

  <!-- Labels -->
  <text x="40" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">-50%</text>
  <text x="150" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">-10%</text>
  <text x="250" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">0%</text>
  <text x="350" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">+10%</text>
  <text x="440" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">+30%</text>

  <!-- Marker -->
  <line x1="250" y1="40" x2="250" y2="200" stroke="#f97316" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="250" y="30" fill="#f97316" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Median return ≈ 0%</text>

  <text x="400" y="80" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">Long tail</text>
</svg>

<h3>The unrealistic target problem</h3>
<p>Many traders set unrealistic targets, such as "50% return this month." These targets create pressure that leads to:</p>
<ul>
    <li><strong>Over-trading.</strong> Taking trades that don't meet criteria to "hit the target."</li>
    <li><strong>Over-sizing.</strong> Risking more than planned to accelerate returns.</li>
    <li><strong>Revenge trading.</strong> After a loss, trying to "make it back" quickly.</li>
    <li><strong>Abandoning the strategy.</strong> Switching approaches when results lag targets.</li>
    <li><strong>Burnout.</strong> Constant pressure to hit unrealistic numbers.</li>
</ul>
<p>Unrealistic targets are one of the leading causes of trader blow-ups. The target itself creates the behaviour that causes failure.</p>

<h3>Setting realistic targets</h3>
<ol>
    <li><strong>Establish your expectancy.</strong> Backtest and forward test to estimate it.</li>
    <li><strong>Count your trades per year.</strong> How many setups do you actually take?</li>
    <li><strong>Apply the formula.</strong> Expectancy × Trades × Risk = expected return.</li>
    <li><strong>Discount for costs.</strong> Subtract spreads, commissions, and slippage.</li>
    <li><strong>Discount for reality.</strong> Reduce by 30–50% to account for variance and market changes.</li>
    <li><strong>Set the target at the discounted number.</strong> Anything above is a bonus.</li>
</ol>

<h3>Worked example</h3>
<p>Suppose your backtest shows:</p>
<ul>
    <li>Expectancy: +0.4R</li>
    <li>Trades per year: 150</li>
    <li>Risk per trade: 1%</li>
</ul>
<p>Projection: 0.4 × 150 × 0.01 = 60% (theoretical).</p>
<p>Discount for costs: subtract 5% = 55%.</p>
<p>Discount for reality: subtract 30% = 38%.</p>
<p>Realistic target: 30–40% annual return.</p>
<p>If your actual return is 25%, you're still in the "good" tier. If it's 45%, you're in the "very good" tier. Either way, you're succeeding — the target was set at a realistic level.</p>

<h3>Monthly and weekly targets</h3>
<p>Annual targets can be broken down:</p>
<ul>
    <li><strong>Monthly:</strong> 2–3% average for a 30% annual target. But monthly variance is high — some months will be negative.</li>
    <li><strong>Weekly:</strong> 0.5–0.75% average for a 30% annual target. Weekly variance is even higher.</li>
</ul>
<p>Setting monthly or weekly targets can be counterproductive. Variance at these scales is high, and hitting a target becomes a matter of luck as much as skill. Annual targets are more meaningful.</p>

<h3>Capital preservation as target</h3>
<p>For developing traders, the target should be different. Instead of returns:</p>
<ul>
    <li><strong>First 6 months:</strong> Don't lose money.</li>
    <li><strong>Next 6 months:</strong> Achieve breakeven after costs.</li>
    <li><strong>Year 2:</strong> Achieve modest profitability (5–10%).</li>
    <li><strong>Year 3+:</strong> Scale toward 15–30% annually.</li>
</ul>
<p>This progression is realistic. Most traders fail because they expect year-3 returns in year 1.</p>

<h2>Factual context</h2>
<p>Return expectations are a well-studied topic in both academia and practitioner research. The consistent findings:</p>
<ul>
    <li><strong>Retail traders underperform.</strong> Studies by ESMA (European Securities and Markets Authority) and other regulators have found that 70–90% of retail traders lose money.</li>
    <li><strong>Returns are modest even for profitable traders.</strong> The median profitable trader earns 5–15% annually.</li>
    <li><strong>High returns come with high risk.</strong> Traders reporting 50%+ returns usually have very high drawdowns.</li>
    <li><strong>Survivorship bias inflates perceptions.</strong> The traders who blew up are not counted in success statistics.</li>
</ul>
<p>Ray Dalio, founder of Bridgewater Associates — one of the most successful hedge funds in history — has produced approximately 12% annual returns net of fees over 40+ years. This is considered exceptional performance.</p>
<p>Warren Buffett's long-term track record is approximately 20% annual returns over 60 years. This is widely regarded as the greatest investment record in modern history.</p>
<p>Paul Tudor Jones' flagship fund has averaged around 19% annual returns over 30+ years. Again, exceptional by any standard.</p>
<p>The point: even the greatest investors and traders in history produce annual returns in the 15–25% range. If a new trader expects 50%+ annually, they are setting themselves up for disappointment and over-trading.</p>
<p>Warren Buffett, describing realistic expectations:</p>
<blockquote><strong>\"The market is a device for transferring money from the impatient to the patient.\"</strong></blockquote>
<p>Buffett's point applies to returns. Patience and consistent modest returns beat aggressive and erratic performance.</p>
<p>Ed Seykota, describing his approach:</p>
<blockquote><strong>\"I don't target specific returns. I focus on following my process. The returns are the outcome of the process, not the goal.\"</strong></blockquote>
<p>Seykota's point captures the essence of realistic target-setting. Focus on the process; let the returns follow.</p>
<p>Paul Tudor Jones, describing his mindset:</p>
<blockquote><strong>\"I try to make a little bit every day. Not a lot — a little. If I can do that consistently, the returns take care of themselves.\"</strong></blockquote>
<p>Jones' humility reflects reality. Consistent small returns compound into significant long-term performance.</p>
<p>Bruce Kovner, describing his approach:</p>
<blockquote><strong>\"I don't have return targets. I have risk targets. I focus on limiting risk. The returns are whatever the market provides.\"</strong></blockquote>
<p>Kovner's risk-focused approach is the professional standard. Risk management determines survival; returns are the result.</p>
<p>Larry Hite, describing realistic expectations:</p>
<blockquote><strong>\"If you can make 20% a year with low drawdowns, you're doing better than 95% of traders. Don't chase 100%.\"</strong></blockquote>
<p>Hite's point is honest and practical. Most traders would be delighted with 20% annually. Chasing higher returns usually produces worse results.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Setting monthly targets.</strong> Variance is too high. Set annual targets.</li>
    <li><strong>Assuming smooth returns.</strong> Returns are lumpy. Some months are down, some are up.</li>
    <li><strong>Chasing high returns.</strong> Higher returns require higher risk. Higher risk increases probability of ruin.</li>
    <li><strong>Comparing to others.</strong> Focus on your own process. Others' returns may be exaggerated or from different strategies.</li>
    <li><strong>Ignoring costs.</strong> Spreads and commissions reduce net returns. Always project net, not gross.</li>
    <li><strong>Assuming stable performance.</strong> Year 1 returns may be very different from year 3.</li>
    <li><strong>Letting targets drive behaviour.</strong> Targets should be outputs of analysis, not inputs that drive over-trading.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often measure performance in R-multiples rather than percentages. This is because R-multiples are strategy-specific, while percentage returns depend on account size and risk management.</p>
<p>A trader might have a strong expectancy of +0.5R but a small account. The R-multiple is the same regardless of account size. This allows objective evaluation of the strategy separate from the account.</p>
<p>Another advanced concept: <strong>time-weighted vs money-weighted returns</strong>. Time-weighted returns remove the effect of deposits and withdrawals. Money-weighted returns include them. For performance evaluation, time-weighted returns are more accurate.</p>
<p>Most professional funds report time-weighted returns. Retail traders often measure money-weighted returns, which can be misleading if deposits and withdrawals are irregular.</p>
<p>Finally, targets should be reviewed periodically. As your skill develops and market conditions change, the realistic return range shifts. A target that was appropriate in year 1 may be too conservative in year 3.</p>
<p>The best approach: focus on the process, not the returns. Track expectancy. Follow the risk management plan. Let the returns be whatever they are. Over time, if the process is sound, the returns will be positive.</p>
HTML,
        ],

        [
            'slug'   => 'putting-risk-reward-expectancy-together',
            'title'  => 'Putting Risk/Reward & Expectancy Together',
            'difficulty' => 'professional',
            'estimated_duration' => 13,
            'learning_objectives' =>
                "• Combine all concepts into a complete expectancy framework\n" .
                "• Build a personal expectancy-based trading process\n" .
                "• Apply the framework to every trade",
            'prerequisites' => 'Setting Realistic Targets',
            'sort_order' => 7,
            'summary' => 'This final lesson brings together all concepts from the module: risk-to-reward ratios, expectancy formulas, the win rate / payoff matrix, expectancy-based sizing, timeframe and market analysis, and realistic target-setting. The goal is a complete expectancy-based trading process.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>You now have all the pieces of expectancy-based trading. This lesson puts them into a coherent framework — a checklist that guides every trade from setup to exit based on expectancy math.</p>

<h2>The complete expectancy framework</h2>

<h3>Step 1: Understand your strategy's profile</h3>
<p>Before you trade, know your strategy:</p>
<ul>
    <li><strong>Win rate</strong> — the percentage of winning trades.</li>
    <li><strong>Payoff ratio</strong> — the average win divided by the average loss.</li>
    <li><strong>Expectancy</strong> — the average R-multiple per trade.</li>
    <li><strong>Variance</strong> — the distribution of outcomes.</li>
    <li><strong>Trades per year</strong> — your realistic trade frequency.</li>
</ul>
<p>These metrics come from backtesting and forward testing. The more data, the more reliable.</p>

<h3>Step 2: Set position sizing based on expectancy</h3>
<p>Standard position sizing: 1% risk per trade. For higher-expectancy trades, adjust upward by 25–50%. For lower-expectancy trades, adjust downward.</p>
<p>But always respect portfolio heat limits (5% maximum total).</p>

<h3>Step 3: Filter trades by expectancy</h3>
<p>Only take trades with positive expectancy. Skip trades with:</p>
<ul>
    <li>Unclear setups.</li>
    <li>Poor reward-to-risk ratios (below 2:1).</li>
    <li>Against the higher-timeframe trend.</li>
    <li>During low-liquidity periods.</li>
    <li>Not aligned with your strategy's profile.</li>
</ul>

<h3>Step 4: Execute with defined risk</h3>
<p>For each trade:</p>
<ul>
    <li>Stop at structural invalidation.</li>
    <li>Target at realistic level.</li>
    <li>R:R minimum 2:1.</li>
    <li>Position size calculated.</li>
</ul>

<h3>Step 5: Track R-multiples</h3>
<p>Every trade has an R-multiple:</p>
<ul>
    <li>+2R = win equal to twice the risk.</li>
    <li>-1R = full loss (stop hit).</li>
    <li>0R = breakeven.</li>
    <li>+0.5R = partial profit.</li>
</ul>
<p>Track every trade's R-multiple. This is your data for expectancy analysis.</p>

<h3>Step 6: Review expectancy weekly</h3>
<p>After every week:</p>
<ul>
    <li>Count wins and losses.</li>
    <li>Calculate average win and average loss in R.</li>
    <li>Update running expectancy.</li>
    <li>Note any patterns in what worked and what didn't.</li>
</ul>

<h3>Step 7: Adjust strategy based on data</h3>
<p>Over time, expectancy analysis reveals:</p>
<ul>
    <li>Which setups produce the best results.</li>
    <li>Which timeframes are most profitable.</li>
    <li>Which markets fit your style.</li>
    <li>Which conditions are favourable.</li>
</ul>
<p>Adjust your strategy based on this data. Trade more in favourable conditions; trade less in unfavourable ones.</p>

<h3>Step 8: Set realistic targets</h3>
<p>Based on expectancy and trade frequency, set realistic annual targets. Remember:</p>
<ul>
    <li>15–30% annually is excellent for retail traders.</li>
    <li>Compounding can boost returns but also drawdowns.</li>
    <li>Discount projections by 30–50% for reality.</li>
</ul>

<h3>Visual reference — Complete framework</h3>
<svg viewBox="0 0 500 320" width="500" height="320" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Steps -->
  <rect x="50" y="15" width="400" height="24" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5"/>
  <text x="250" y="31" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1. Know your strategy profile</text>

  <rect x="50" y="44" width="400" height="24" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5"/>
  <text x="250" y="60" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">2. Size by expectancy</text>

  <rect x="50" y="73" width="400" height="24" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5"/>
  <text x="250" y="89" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">3. Filter trades by expectancy</text>

  <rect x="50" y="102" width="400" height="24" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5"/>
  <text x="250" y="118" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">4. Execute with defined risk</text>

  <rect x="50" y="131" width="400" height="24" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5"/>
  <text x="250" y="147" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">5. Track R-multiples</text>

  <rect x="50" y="160" width="400" height="24" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5"/>
  <text x="250" y="176" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">6. Review expectancy weekly</text>

  <rect x="50" y="189" width="400" height="24" fill="#ef4444" fill-opacity="0.15" stroke="#ef4444" stroke-width="1.5"/>
  <text x="250" y="205" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">7. Adjust strategy by data</text>

  <rect x="50" y="218" width="400" height="24" fill="#ef4444" fill-opacity="0.3" stroke="#ef4444" stroke-width="2"/>
  <text x="250" y="234" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">8. Set realistic targets</text>

  <text x="250" y="285" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Expectancy framework: from strategy to execution to review</text>
</svg>

<h2>Worked example — Complete process</h2>
<p>Let's run through the process for a trader's full year.</p>

<h3>Before trading</h3>
<ul>
    <li>Backtested strategy: pullback to H4 demand zone with H1 CHoCH confirmation.</li>
    <li>Win rate: 45%</li>
    <li>Payoff ratio: 2.5:1</li>
    <li>Expectancy: (0.45 × 2.5) − (0.55 × 1) = 1.125 − 0.55 = +0.575R</li>
    <li>Trades per year: 120</li>
    <li>Risk per trade: 1%</li>
    <li>Projected return: 0.575 × 120 × 0.01 = +69% (theoretical)</li>
    <li>Discounted return: ~40% (after costs and reality check)</li>
</ul>

<h3>During trading</h3>
<p>Over the year, the trader takes 120 trades. Some observations:</p>
<ul>
    <li>Consecutive losses: maximum 6 in a row (not unusual for 45% win rate)</li>
    <li>Best streak: 8 consecutive wins</li>
    <li>Longest winning trade: +6.5R</li>
    <li>Worst losing trade: -1R (stopped out every time)</li>
    <li>Average win: +2.7R</li>
    <li>Average loss: -1R</li>
</ul>

<h3>After the year</h3>
<ul>
    <li>Actual win rate: 42% (slightly below backtest)</li>
    <li>Actual average win: +2.4R</li>
    <li>Actual average loss: -1R</li>
    <li>Actual expectancy: (0.42 × 2.4) − (0.58 × 1) = 1.008 − 0.58 = +0.428R</li>
    <li>Total: 0.428 × 120 = +51.4R</li>
    <li>Actual return at 1% risk: +51.4% (before costs)</li>
    <li>Actual return after costs: ~+45%</li>
</ul>
<p>The trader beat expectations. The process worked.</p>

<h3>What if the year was bad?</h3>
<p>Suppose instead:</p>
<ul>
    <li>Actual win rate: 38%</li>
    <li>Actual average win: +2.1R</li>
    <li>Actual expectancy: (0.38 × 2.1) − (0.62 × 1) = 0.798 − 0.62 = +0.178R</li>
    <li>Total: 0.178 × 120 = +21.4R</li>
    <li>Actual return at 1% risk: +21.4%</li>
</ul>
<p>Still profitable, just less than expected. The strategy's positive expectancy held even with slightly worse metrics.</p>

<h3>What if the strategy broke down?</h3>
<p>Suppose:</p>
<ul>
    <li>Actual win rate: 32%</li>
    <li>Actual average win: +1.8R</li>
    <li>Actual expectancy: (0.32 × 1.8) − (0.68 × 1) = 0.576 − 0.68 = -0.104R</li>
    <li>Total: -0.104 × 120 = -12.5R</li>
    <li>Actual return: -12.5%</li>
</ul>
<p>The strategy lost money. This is the critical scenario — the trader must:</p>
<ol>
    <li><strong>Recognise the problem.</strong> Expectancy is negative.</li>
    <li><strong>Reduce risk.</strong> Cut position size to 0.5%.</li>
    <li><strong>Analyse the cause.</strong> Why has win rate and payoff dropped?</li>
    <li><strong>Decide.</strong> Adjust the strategy or abandon it.</li>
</ol>

<h2>Applying the framework to every trade</h2>

<h3>Before the trade</h3>
<ul>
    <li>Does this setup match my strategy?</li>
    <li>What is my estimated expectancy for this setup?</li>
    <li>Is the R:R at least 2:1?</li>
    <li>Does it align with higher-timeframe context?</li>
    <li>Is the position size appropriate?</li>
    <li>Is portfolio heat within limits?</li>
</ul>

<h3>During the trade</h3>
<ul>
    <li>Follow the management plan.</li>
    <li>Move stop to break-even after 1R.</li>
    <li>Take partial profits at target 1.</li>
    <li>Let the rest run to target 2.</li>
</ul>

<h3>After the trade</h3>
<ul>
    <li>Log the R-multiple.</li>
    <li>Update running expectancy.</li>
    <li>Note any mistakes or lessons.</li>
</ul>

<h3>Weekly</h3>
<ul>
    <li>Review all trades from the week.</li>
    <li>Update expectancy by setup, market, timeframe.</li>
    <li>Identify patterns.</li>
    <li>Adjust strategy as needed.</li>
</ul>

<h3>Monthly</h3>
<ul>
    <li>Comprehensive review.</li>
    <li>Compile statistics.</li>
    <li>Assess alignment with risk management plan.</li>
    <li>Adjust targets based on actual performance.</li>
</ul>

<h2>Factual context</h2>
<p>The complete expectancy framework described here reflects the approach used by professional traders and quant funds. Every aspect — trade selection, position sizing, performance evaluation — is driven by the mathematics of expectancy.</p>
<p>The framework has been validated by decades of research and practitioner experience. Ed Thorp's hedge fund, Renaissance Technologies, AQR Capital, and other top-performing funds use variations of this framework.</p>
<p>The essence: trade with positive expectancy, size positions based on edge, and evaluate performance in R-multiples. Everything else follows from these three principles.</p>
<p>Ed Thorp, describing the essence:</p>
<blockquote><strong>\"The key to long-term success is positive expectancy. Everything else is secondary.\"</strong></blockquote>
<p>Thorp's point is the foundation. Without positive expectancy, no amount of discipline or position sizing produces profits.</p>
<p>Paul Tudor Jones, describing his approach:</p>
<blockquote><strong>\"I want every trade to have a positive expectancy. If the math isn't in my favour, I don't take the trade, no matter how good it looks.\"</strong></blockquote>
<p>Jones' discipline is the professional standard. Trades are taken when the math is favourable, skipped when it isn't.</p>
<p>Larry Hite, describing his framework:</p>
<blockquote><strong>\"The best traders are not the ones who win the most trades. They are the ones who make the most money per trade. That is expectancy.\"</strong></blockquote>
<p>Hite's point captures the essence. Win rate alone is meaningless. Expectancy is the metric that matters.</p>
<p>Bruce Kovner, describing his own discipline:</p>
<blockquote><strong>\"I size my bets based on how much I believe in the trade. Not on my hope — on my analysis and the historical performance of the setup.\"</strong></blockquote>
<p>Kovner's approach combines conviction with evidence. Position size follows from analysis, not emotion.</p>
<p>Warren Buffett, describing his approach:</p>
<blockquote><strong>\"The stock market is a device for transferring money from the impatient to the patient.\"</strong></blockquote>
<p>Buffett's patience is the essence of expectancy-based trading. Consistent positive expectancy over many trades produces significant returns. No shortcut exists.</p>
<p>Ed Seykota, describing his philosophy:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules produce high expectancy when applied consistently. Cut losses short (small losses), ride winners (large wins), keep bets small (survive). The expectancy takes care of itself.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Not calculating expectancy.</strong> Without calculation, you don't know if your strategy works.</li>
    <li><strong>Focussing on win rate.</strong> Win rate alone tells you nothing about profitability.</li>
    <li><strong>Ignoring costs.</strong> Net expectancy (after costs) is what matters.</li>
    <li><strong>Using small samples.</strong> Fewer than 100 trades gives unreliable expectancy.</li>
    <li><strong>Not adapting.</strong> Expectancy changes with market conditions. Monitor and adjust.</li>
    <li><strong>Overtrading.</strong> Taking trades that don't meet criteria dilutes expectancy.</li>
    <li><strong>Chasing losses.</strong> Emotional trades have negative expectancy. They should be skipped.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use expectancy analysis at multiple levels:</p>
<ul>
    <li><strong>Trade-level</strong> — expectancy of each individual setup type.</li>
    <li><strong>Strategy-level</strong> — expectancy of the overall approach.</li>
    <li><strong>Portfolio-level</strong> — combined expectancy across multiple strategies.</li>
    <li><strong>Time-conditional</strong> — expectancy by market regime, session, or day.</li>
</ul>
<p>The most sophisticated traders combine these to build robust portfolios that produce positive expectancy across a wide range of market conditions.</p>
<p>The framework isn't complicated. It's the discipline that's difficult. Most traders know they should trade with positive expectancy, size positions based on edge, and evaluate performance in R-multiples. But few actually do it consistently.</p>
<p>The traders who succeed are those who apply these principles day after day, year after year. The mathematics are on their side. The discipline is what makes the mathematics matter.</p>
<p>With the risk/reward and expectancy framework complete, you have the mathematical foundation for professional trading. The next module — Trading Psychology — covers the psychological dimension that determines whether you can actually apply these principles under pressure.</p>
HTML,
        ],

    ],
];