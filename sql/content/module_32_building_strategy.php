<?php
/**
 * Module 32 — Building Your Own Strategy
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_32_building_strategy.php
 */

return [
    'module' => [
        'level_slug' => 'professional',
        'slug'       => 'building-your-own-strategy',
        'title'      => 'Building Your Own Strategy',
        'description'=> 'Every trader eventually reaches the same conclusion: the best strategy is one you designed yourself. This module walks you through the process — market selection, timeframe choice, entry rules, exit rules, position sizing, filters, and no-trade conditions. By the end, you will have a complete, written trading plan that fits your analysis, psychology, and lifestyle.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Understand why building your own strategy beats copying others\n" .
            "• Design each component of a complete trading plan\n" .
            "• Write specific, testable rules for entries and exits\n" .
            "• Define no-trade conditions and filters\n" .
            "• Document your strategy in a form you can actually follow",
        'sort_order' => 32,
    ],

    'lessons' => [

        [
            'slug'   => 'why-build-your-own-strategy',
            'title'  => 'Why Build Your Own Strategy',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Understand the limits of copying someone else's strategy\n" .
                "• Recognise the three pillars of a personal strategy\n" .
                "• Commit to the process of building rather than borrowing",
            'prerequisites' => 'Putting Trading Strategies Together',
            'sort_order' => 1,
            'summary' => 'Copying another trader\'s strategy rarely works. The strategy may not fit your timeframe, your risk tolerance, your schedule, or your psychology. Building your own strategy — even if it borrows concepts — creates ownership, which produces consistency under pressure.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine buying a suit off the rack and expecting it to fit perfectly. Sometimes it does. Usually it doesn't. The shoulders are too wide, the sleeves are too short, the waist is wrong. You can wear it, but you'll never feel comfortable in it.</p>
<p>Copying another trader's strategy is like buying their suit. It might look good on them, but it won't fit you. Building your own strategy is like tailoring — it fits because it was made for you.</p>

<h2>Real-world analogy</h2>
<p>Think of cooking. You can follow a recipe from a cookbook, and sometimes you'll get a good meal. But the best cooks adapt recipes to their ingredients, their tastes, and their equipment. Over time, the recipe becomes theirs. Trading strategies work the same way.</p>

<h2>Professional explanation</h2>

<h3>Why copying fails</h3>
<p>Trader A finds a strategy online. It has a documented 60% win rate and 2:1 payoff. Trader A starts using it. Six months later, Trader A has lost money. Why?</p>
<p>Five reasons:</p>
<ol>
    <li><strong>Different personality.</strong> The strategy might require sitting through 10-trade losing streaks — which Trader A cannot tolerate psychologically.</li>
    <li><strong>Different timeframe.</strong> The strategy might be designed for the H4 chart — but Trader A can only trade on weekends.</li>
    <li><strong>Different risk tolerance.</strong> The strategy might require 2% risk per trade — but Trader A is only comfortable with 0.5%.</li>
    <li><strong>Different execution.</strong> The strategy might require waiting for specific confirmations — but Trader A is impatient and enters early.</li>
    <li><strong>Different market conditions.</strong> The strategy might have been profitable during a trending period — but the current market is ranging.</li>
</ol>
<p>Each of these differences breaks the copy. Even a good strategy can fail in the wrong hands.</p>

<h3>What ownership provides</h3>
<p>When you build your own strategy, you gain three things:</p>
<ol>
    <li><strong>Fit.</strong> The strategy matches your personality, schedule, and risk tolerance.</li>
    <li><strong>Understanding.</strong> You know why each rule exists. When a rule feels hard to follow, you understand the trade-off.</li>
    <li><strong>Commitment.</strong> You're not following someone else's rules — you're following your own. This is easier to sustain.</li>
</ol>

<h3>Visual reference — The copy trap</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Trader A (source) -->
  <rect x="40" y="30" width="160" height="80" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="120" y="55" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">TRADER A</text>
  <text x="120" y="72" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Built the strategy</text>
  <text x="120" y="88" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Fits their profile</text>
  <text x="120" y="100" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Consistent results</text>

  <!-- Arrow copying -->
  <line x1="200" y1="70" x2="280" y2="70" stroke="#8b93a7" stroke-width="2" stroke-dasharray="4,3"/>
  <text x="240" y="60" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">copies</text>

  <!-- Trader B (copier) -->
  <rect x="280" y="30" width="180" height="80" fill="#ef4444" fill-opacity="0.15" stroke="#ef4444" stroke-width="2" rx="6"/>
  <text x="370" y="55" fill="#ef4444" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">TRADER B</text>
  <text x="370" y="72" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Different personality</text>
  <text x="370" y="88" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Different schedule</text>
  <text x="370" y="100" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Different risk tolerance</text>

  <!-- Result -->
  <rect x="120" y="150" width="260" height="50" fill="#ef4444" fill-opacity="0.3" stroke="#ef4444" stroke-width="2" rx="6"/>
  <text x="250" y="180" fill="#ef4444" font-size="13" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">STRATEGY FAILS IN TRADER B's HANDS</text>

  <text x="250" y="230" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">The strategy wasn't bad. The fit was wrong.</text>
</svg>

<h3>The three pillars of a personal strategy</h3>
<p>Every personal strategy rests on three pillars:</p>

<h4>1. Analytical fit</h4>
<p>The strategy uses the analysis methods you understand and trust. If you don't believe in moving averages, don't build a strategy around them. If you trust structure and liquidity, build around those.</p>

<h4>2. Psychological fit</h4>
<p>The strategy matches your emotional tolerance. If losing streaks of 5+ trades make you panic, don't use a strategy with a 35% win rate. If waiting for pullbacks feels boring, don't build a patient strategy.</p>

<h4>3. Lifestyle fit</h4>
<p>The strategy fits your available time. If you work 9-5, don't build a strategy requiring constant screen time. If you have evenings free, don't build a strategy that requires 3 AM monitoring.</p>

<h3>Borrowing vs building</h3>
<p>Building your own strategy doesn't mean inventing something from nothing. The best personal strategies borrow concepts from many sources — trend following from the Turtles, liquidity concepts from SMC, structure from Wyckoff. What makes it personal is that you have selected the concepts that resonate with you and combined them into a coherent whole.</p>
<p>Think of it like cooking. You might use a French technique, an Italian ingredient, and a Japanese presentation. The dish is yours because of how you combined them.</p>

<h3>Visual reference — The three pillars</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Roof -->
  <polygon points="250,30 450,110 50,110" fill="#5b7cfa" fill-opacity="0.3" stroke="#5b7cfa" stroke-width="2"/>
  <text x="250" y="80" fill="#5b7cfa" font-size="14" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">PERSONAL STRATEGY</text>

  <!-- Pillar 1 -->
  <rect x="80" y="130" width="100" height="100" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="130" y="160" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Analytical</text>
  <text x="130" y="180" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Methods you</text>
  <text x="130" y="195" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">understand and</text>
  <text x="130" y="210" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">trust</text>

  <!-- Pillar 2 -->
  <rect x="200" y="130" width="100" height="100" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="4"/>
  <text x="250" y="160" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Psychological</text>
  <text x="250" y="180" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Matches your</text>
  <text x="250" y="195" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">emotional</text>
  <text x="250" y="210" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">tolerance</text>

  <!-- Pillar 3 -->
  <rect x="320" y="130" width="100" height="100" fill="#ef4444" fill-opacity="0.15" stroke="#ef4444" stroke-width="1.5" rx="4"/>
  <text x="370" y="160" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Lifestyle</text>
  <text x="370" y="180" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Fits your</text>
  <text x="370" y="195" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">available time</text>
  <text x="370" y="210" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">and energy</text>
</svg>

<h3>The cost of a bad fit</h3>
<p>When a strategy doesn't fit, three things happen:</p>
<ol>
    <li><strong>You abandon it after losses.</strong> The strategy's losing streaks feel unbearable. You switch to something else. The original strategy might have worked, but you never gave it time.</li>
    <li><strong>You modify rules mid-trade.</strong> You second-guess entries, move stops, or take profits early. The strategy's edge disappears through inconsistent execution.</li>
    <li><strong>You trade with resentment.</strong> Every trade feels like a struggle. Trading becomes draining rather than engaging. Burnout follows.</li>
</ol>
<p>A strategy that fits you produces different outcomes. Losing streaks are tolerable because you understand them. Rules are followed because you designed them. Trading becomes sustainable.</p>

<h2>Factual context</h2>
<p>The idea that strategies must be personally designed is central to modern trading education. Key sources:</p>
<p><strong>Van Tharp</strong> — in <em>Trade Your Way to Financial Freedom</em>, argues that success comes from aligning trading with your personality and beliefs, not from finding a "holy grail" strategy.</p>
<p><strong>Brett Steenbarger</strong> — describes how professional traders customise their approaches to fit their cognitive styles and emotional profiles.</p>
<p><strong>Mark Douglas</strong> — emphasises that traders must develop their own rules, not simply adopt someone else's, because rules only work when they are internally motivated.</p>
<p><strong>Richard Dennis</strong> — while he proved trading could be taught, the Turtles' rules were simple and explicit. He also noted that many who received the rules failed because they couldn't follow them.</p>
<p>Ed Seykota, on this point:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Rule 4 — follow the rules without question — is only possible if the rules are yours. Following someone else's rules is much harder.</p>
<p>Paul Tudor Jones, on personal ownership:</p>
<blockquote><strong>\"I've always had my own approach. I never tried to copy anyone. I developed my methods through experience and refined them over time.\"</strong></blockquote>
<p>Jones' success came from building, not borrowing. He learned from other traders but built his own approach.</p>
<p>Bruce Kovner, describing his own development:</p>
<blockquote><strong>\"I read everything I could, talked to traders, then built something that was my own. The process took years. But the result was a strategy that I could follow under any pressure.\"</strong></blockquote>
<p>Kovner's point: personal strategies take longer to build but last longer.</p>
<p>Warren Buffett, on the same theme:</p>
<blockquote><strong>\"You don't need to be a rocket scientist. Investing is not a game where the guy with the 160 IQ beats the guy with the 130 IQ.\"</strong></blockquote>
<p>Buffett's point: success is not about intelligence. It's about doing what works for you, consistently.</p>
<p>Jesse Livermore, on the personal dimension:</p>
<blockquote><strong>\"The market does not beat them. They beat themselves, because though they have brains they cannot sit tight.\"</strong></blockquote>
<p>Livermore's observation: the challenge is psychological. Personal strategies reduce this challenge by aligning with your nature.</p>
<p>Mark Douglas, describing the ultimate goal:</p>
<blockquote><strong>\"The best traders are not afraid. They are not afraid of being wrong, losing money, or missing out. They have learned to trade without emotional pain.\"</strong></blockquote>
<p>Douglas' goal is achievable only when the strategy fits the trader. Misfit strategies produce emotional pain. Fit strategies produce calm execution.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Copying a strategy and expecting it to work.</strong> Even good strategies fail in the wrong hands.</li>
    <li><strong>Building a strategy that requires a lifestyle you don't have.</strong> The best strategy is useless if you can't follow it.</li>
    <li><strong>Ignoring your psychological profile.</strong> If a strategy feels wrong, it will fail — regardless of its statistics.</li>
    <li><strong>Trying to be original.</strong> You don't need to invent new concepts. Combine existing concepts into a strategy that fits you.</li>
    <li><strong>Building a strategy without testing it.</strong> Untested strategies are guesses. Test first.</li>
    <li><strong>Changing the strategy after losses.</strong> Every strategy has losing streaks. Change based on data, not emotion.</li>
    <li><strong>Not writing the strategy down.</strong> An unwritten strategy is not a strategy — it's a collection of intentions.</li>
    <li><strong>Building too many strategies at once.</strong> Master one before building another.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often revisit their strategies annually. They review performance data, market changes, and their own evolving psychology. The strategy evolves with the trader.</p>
<p>Some professional traders maintain two or three complementary strategies — one for trending markets, one for ranges, one for special setups. This toolkit approach (covered in the previous module) is a natural extension of building your own strategy.</p>
<p>The most important insight: the process of building a strategy is as valuable as the strategy itself. By designing each component yourself, you understand why it exists. When the strategy encounters difficulty, you can diagnose the problem and adjust intelligently — rather than abandoning it for the next shiny object.</p>
<p>Building a strategy is a multi-month process. There's no shortcut. But the trader who goes through this process develops a level of understanding and confidence that cannot be borrowed.</p>
HTML,
        ],

        [
            'slug'   => 'defining-your-objectives',
            'title'  => 'Defining Your Objectives',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define clear trading objectives\n" .
                "• Set realistic expectations\n" .
                "• Choose metrics that matter\n" .
                "• Build a foundation for strategy design",
            'prerequisites' => 'Why Build Your Own Strategy',
            'sort_order' => 2,
            'summary' => 'Before designing a strategy, you must define what you want from trading. Objectives determine every design choice — from timeframe to entry rules to risk management. Vague objectives produce vague strategies that fail to deliver. Specific objectives produce focused strategies that do.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine booking a flight without deciding where you want to go. The pilot would ask: "Where to?" Without an answer, the plane stays on the ground. Trading works the same way. Without clear objectives, you don't know what to build.</p>

<h2>Real-world analogy</h2>
<p>Think of designing a house. Before picking materials, you must know: is this a family home, a holiday cottage, or an office? The purpose determines every design decision. Trading objectives work the same way.</p>

<h2>Professional explanation</h2>

<h3>The five objective questions</h3>
<p>Before designing anything, answer these five questions:</p>

<h4>1. What is the purpose?</h4>
<ul>
    <li><strong>Income</strong> — replace or supplement a salary.</li>
    <li><strong>Growth</strong> — compound capital over years.</li>
    <li><strong>Learning</strong> — develop skills without pressure for returns.</li>
    <li><strong>Hobby</strong> — engage with markets for interest, not income.</li>
</ul>
<p>The purpose determines the risk profile. Income-focused traders may need higher win rates and more frequent trades. Growth-focused traders can accept longer holding periods and drawdowns.</p>

<h4>2. What is the target return?</h4>
<p>Realistic annual return targets:</p>
<table>
    <thead><tr><th>Tier</th><th>Annual Return</th><th>Who Achieves It</th></tr></thead>
    <tbody>
        <tr><td>Exceptional</td><td>50%+</td><td>Top 1% of traders</td></tr>
        <tr><td>Very good</td><td>30–50%</td><td>Skilled, disciplined traders</td></tr>
        <tr><td>Good</td><td>15–30%</td><td>Consistent profitable traders</td></tr>
        <tr><td>Acceptable</td><td>5–15%</td><td>Most profitable retail traders</td></tr>
        <tr><td>Breakeven</td><td>0–5%</td><td>Developing traders</td></tr>
        <tr><td>Learning</td><td>Negative</td><td>Most retail traders</td></tr>
    </tbody>
</table>
<p>Most traders target returns that are unrealistic. Setting a 100% annual target guarantees disappointment and over-trading. Setting a 15% target allows patience and consistency.</p>

<h4>3. What is the maximum acceptable drawdown?</h4>
<p>Drawdown tolerance determines risk per trade and position sizing. Common guidelines:</p>
<ul>
    <li><strong>10% drawdown tolerance</strong> — conservative. Risk 0.5% per trade.</li>
    <li><strong>20% drawdown tolerance</strong> — moderate. Risk 1% per trade.</li>
    <li><strong>30% drawdown tolerance</strong> — aggressive. Risk 1.5% per trade.</li>
    <li><strong>50%+ drawdown tolerance</strong> — extreme. Risk 2%+ per trade. Not recommended.</li>
</ul>
<p>Most traders overestimate their drawdown tolerance. A 15% drawdown feels much worse in reality than it does in theory.</p>

<h4>4. What is the time commitment?</h4>
<ul>
    <li><strong>Full-time</strong> — 6+ hours per day available.</li>
    <li><strong>Part-time</strong> — 2–4 hours per day.</li>
    <li><strong>Evenings/weekends only</strong> — 1 hour per day, more on weekends.</li>
    <li><strong>Sporadic</strong> — less than 30 minutes per day.</li>
</ul>
<p>Time commitment determines the trading style. Full-time traders can scalp or day trade. Part-time traders should swing trade. Sporadic traders should position trade.</p>

<h4>5. What is the capital base?</h4>
<ul>
    <li><strong>Under $1,000</strong> — difficult to trade effectively. Build capital first.</li>
    <li><strong>$1,000–$10,000</strong> — swing trading on higher timeframes.</li>
    <li><strong>$10,000–$50,000</strong> — full range of styles possible.</li>
    <li><strong>$50,000+</strong> — no restrictions from capital.</li>
</ul>
<p>Small accounts struggle with scalping because costs consume too much. Larger accounts can trade any style.</p>

<h3>Visual reference — Objective alignment</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Objective categories -->
  <rect x="30" y="30" width="200" height="40" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="130" y="55" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">YOUR OBJECTIVES</text>

  <!-- Individual objectives -->
  <rect x="30" y="90" width="200" height="30" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="130" y="110" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Purpose: Income</text>

  <rect x="30" y="128" width="200" height="30" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="130" y="148" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Target return: 20% annually</text>

  <rect x="30" y="166" width="200" height="30" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="130" y="186" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Drawdown: 15% maximum</text>

  <rect x="30" y="204" width="200" height="30" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="130" y="224" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Time: 2 hours/day</text>

  <rect x="30" y="242" width="200" height="30" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="130" y="262" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Capital: $10,000</text>

  <!-- Arrow -->
  <line x1="240" y1="150" x2="280" y2="150" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="280,150 270,145 270,155" fill="#8b93a7"/>

  <!-- Derived design choices -->
  <rect x="290" y="60" width="190" height="40" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="385" y="85" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">DERIVED CHOICES</text>

  <rect x="290" y="110" width="190" height="30" fill="#f97316" fill-opacity="0.08" stroke="#f97316" stroke-width="1" rx="4"/>
  <text x="385" y="130" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Style: Swing trading</text>

  <rect x="290" y="148" width="190" height="30" fill="#f97316" fill-opacity="0.08" stroke="#f97316" stroke-width="1" rx="4"/>
  <text x="385" y="168" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Timeframe: H4 / Daily</text>

  <rect x="290" y="186" width="190" height="30" fill="#f97316" fill-opacity="0.08" stroke="#f97316" stroke-width="1" rx="4"/>
  <text x="385" y="206" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Risk per trade: 1%</text>

  <rect x="290" y="224" width="190" height="30" fill="#f97316" fill-opacity="0.08" stroke="#f97316" stroke-width="1" rx="4"/>
  <text x="385" y="244" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Trades/month: 8–12</text>
</svg>

<h3>The metrics that matter</h3>
<p>Once objectives are set, they define the metrics to track:</p>

<h4>For income-focused traders</h4>
<ul>
    <li><strong>Monthly income</strong> — consistent positive cash flow.</li>
    <li><strong>Win rate</strong> — higher to reduce variance in income.</li>
    <li><strong>Average trade</strong> — enough to justify the time.</li>
</ul>

<h4>For growth-focused traders</h4>
<ul>
    <li><strong>Compounded annual return</strong> — long-term growth rate.</li>
    <li><strong>Maximum drawdown</strong> — the worst peak-to-trough decline.</li>
    <li><strong>Sharpe ratio</strong> — return per unit of risk.</li>
</ul>

<h4>For learning traders</h4>
<ul>
    <li><strong>Rule adherence</strong> — percentage of trades following the plan.</li>
    <li><strong>Expectancy</strong> — average R per trade.</li>
    <li><strong>Journal completion</strong> — consistency of review.</li>
</ul>

<h3>Realistic expectations</h3>
<p>Setting realistic expectations is the difference between satisfaction and frustration. Realistic expectations:</p>
<ul>
    <li><strong>Year 1</strong> — don't lose money. Aim for breakeven.</li>
    <li><strong>Year 2</strong> — modest profitability (5–15%).</li>
    <li><strong>Year 3</strong> — consistent profitability (15–30%).</li>
    <li><strong>Year 4+</strong> — scale returns with experience.</li>
</ul>
<p>Most retail traders fail because they expect year-3 returns in year 1. The mathematics of development make this impossible.</p>

<h3>Defining your metrics dashboard</h3>
<p>Before trading, define what you will track:</p>
<ul>
    <li><strong>Daily:</strong> Trades taken, R-multiples, rule adherence.</li>
    <li><strong>Weekly:</strong> Net R, win rate, average win/loss.</li>
    <li><strong>Monthly:</strong> Expectancy, total return, drawdown.</li>
    <li><strong>Quarterly:</strong> Sharpe ratio, consistency metrics.</li>
    <li><strong>Yearly:</strong> Compounded return, strategy evolution.</li>
</ul>
<p>Tracking these metrics from day one creates a feedback loop. Without tracking, improvement is guesswork.</p>

<h3>Common objective-setting mistakes</h3>
<ul>
    <li><strong>Setting unrealistic targets.</strong> 100% returns per year is not achievable for most traders. Set 15–30% targets.</li>
    <li><strong>Not defining drawdown tolerance.</strong> Without this, risk per trade is arbitrary.</li>
    <li><strong>Overestimating time commitment.</strong> Be honest about available hours. Design to your reality.</li>
    <li><strong>Trading with capital you can't afford to lose.</strong> This produces emotional decisions. Only trade with risk capital.</li>
    <li><strong>Mixing purposes.</strong> Trying to build skills while also needing income creates pressure that undermines learning.</li>
    <li><strong>Not revisiting objectives.</strong> Objectives evolve. Review them every six months.</li>
</ul>

<h2>Factual context</h2>
<p>The importance of clear objectives is emphasised across trading literature:</p>
<p><strong>Van Tharp</strong> — argues that traders fail because they haven't defined their objectives clearly. His "peak performance" framework begins with objective definition.</p>
<p><strong>Brett Steenbarger</strong> — writes extensively on the need for explicit goals and metrics. Traders without clear targets cannot improve systematically.</p>
<p><strong>Mark Douglas</strong> — describes the "consistent winner's objective" as following the rules, not making money. This reframes objectives away from returns and toward process.</p>
<p><strong>Brett Steenbarger, on this point:</strong></p>
<blockquote><strong>\"The goal is not to be right. The goal is to follow your process. If you follow your process, the results take care of themselves.\"</strong></blockquote>
<p>Steenbarger's point: objective-setting should focus on process metrics, not just outcome metrics.</p>
<p>Van Tharp, describing his framework:</p>
<blockquote><strong>\"You must know what you want from trading. Without that, no strategy will satisfy you.\"</strong></blockquote>
<p>Tharp's point is the essence of this lesson. Strategy design starts with knowing what you want.</p>
<p>Paul Tudor Jones, on setting the right targets:</p>
<blockquote><strong>\"I try to make a little bit every day. Not a lot — a little. If I can do that consistently, the returns take care of themselves.\"</strong></blockquote>
<p>Jones' humility is the professional standard. Small consistent returns beat heroic attempts at big returns.</p>
<p>Warren Buffett, on realistic expectations:</p>
<blockquote><strong>\"The stock market is a device for transferring money from the impatient to the patient.\"</strong></blockquote>
<p>Buffett's patience is a form of realistic objective-setting. Long-term, sustainable growth beats short-term speculation.</p>
<p>Bruce Kovner, on the discipline that follows from objectives:</p>
<blockquote><strong>\"I know where I'm getting out before I get in. And I size my positions so that a loss doesn't affect my ability to trade tomorrow.\"</strong></blockquote>
<p>Kovner's discipline is only possible with clear objectives about drawdown tolerance. Without them, position sizing is arbitrary.</p>
<p>Ed Seykota, on the goal:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules assume a clear objective: longevity. His objective determines his approach — survival first, returns second.</p>
<p>Jesse Livermore, on the same theme:</p>
<blockquote><strong>\"The big money is not in the individual fluctuations but in the main movements.\"</strong></blockquote>
<p>Livermore's objective was clear: capture large moves, not small fluctuations. This objective drove his entire approach.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Setting goals without timelines.</strong> "I want to be profitable" is not an objective. "10% return in 12 months with max 15% drawdown" is.</li>
    <li><strong>Copying someone else's objectives.</strong> Your objectives should reflect your life, not someone else's.</li>
    <li><strong>Ignoring drawdown tolerance.</strong> Most traders discover their true tolerance only after a major drawdown. Define it in advance.</li>
    <li><strong>Treating objectives as permanent.</strong> They should evolve with experience. Review every six months.</li>
    <li><strong>Confusing process metrics with outcome metrics.</strong> Both matter, but process metrics are more controllable.</li>
    <li><strong>Not committing to realistic timelines.</strong> Two years is realistic to become consistently profitable. Expecting six months is not.</li>
    <li><strong>Setting objectives that conflict.</strong> High returns require high risk, which produces large drawdowns. Objectives must be internally consistent.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often define objectives in three layers:</p>
<ol>
    <li><strong>Strategic</strong> — 3–5 year vision. Example: "Build a portfolio generating $100,000/year in passive income from trading."</li>
    <li><strong>Tactical</strong> — annual goals. Example: "Achieve 20% net return with max 15% drawdown."</li>
    <li><strong>Operational</strong> — daily and weekly metrics. Example: "Follow my plan on 95% of trades this week."</li>
</ol>
<p>The three-layer approach keeps long-term vision grounded in short-term actions. Strategic goals provide direction; tactical goals measure progress; operational goals drive daily behaviour.</p>
<p>The most important insight: objectives are not just about money. The best objectives include process dimensions — becoming a more disciplined trader, understanding markets more deeply, building sustainable habits. These process objectives produce the outcome objectives as a byproduct.</p>
<p>With clear objectives defined, you are ready to design the specific components of your strategy — markets, timeframes, entries, exits, and risk management. Each design decision should trace back to your objectives.</p>
HTML,
        ],

        [
            'slug'   => 'choosing-your-market-and-timeframe',
            'title'  => 'Choosing Your Market and Timeframe',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Select the right markets for your trading style\n" .
                "• Choose timeframes that match your schedule\n" .
                "• Understand the trade-offs of each choice\n" .
                "• Build a focused trading universe",
            'prerequisites' => 'Defining Your Objectives',
            'sort_order' => 3,
            'summary' => 'Markets and timeframes are the canvas on which strategies are painted. Different markets have different behaviours, and different timeframes produce different opportunity-to-noise ratios. Choosing the right combination is one of the most important decisions in strategy design.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine you want to fish. Some fishermen fish in lakes, some in rivers, some in the ocean. Each environment requires different equipment and skills. Trading markets work the same way. Each market has characteristics that suit different styles.</p>

<h2>Real-world analogy</h2>
<p>Think of choosing a sport. Some people excel at sprinting (short, intense effort), others at marathon running (long, sustained effort). The sport must match the athlete. Markets and timeframes must match the trader.</p>

<h2>Professional explanation</h2>

<h3>Market selection criteria</h3>
<p>When choosing markets, consider these factors:</p>

<h4>1. Liquidity</h4>
<p>Liquidity determines how easily you can enter and exit positions. High liquidity means:</p>
<ul>
    <li>Tight spreads (lower costs).</li>
    <li>Minimal slippage.</li>
    <li>Reliable technical patterns.</li>
</ul>
<p>The most liquid markets are major FX pairs (EUR/USD, USD/JPY, GBP/USD), major indices (S&P 500, DAX), and top commodities (gold, crude oil).</p>

<h4>2. Volatility</h4>
<p>Volatility determines how much prices move. The best markets have:</p>
<ul>
    <li>Enough movement to produce profitable trades.</li>
    <li>Not so much movement that risk becomes unmanageable.</li>
</ul>
<p>Moderate volatility is ideal. Extreme volatility (like in some crypto pairs) produces large profits but also large losses.</p>

<h4>3. Trading hours</h4>
<p>When markets are active determines when you can trade. Major FX pairs trade nearly 24/5. Indices trade during their local sessions. Commodities have specific session windows.</p>
<p>Choose markets whose active hours align with your available time.</p>

<h4>4. Behaviour characteristics</h4>
<p>Different markets have different personalities:</p>
<ul>
    <li><strong>EUR/USD</strong> — trends cleanly, respects levels, low noise.</li>
    <li><strong>GBP/USD</strong> — more volatile, faster moves, wider spreads.</li>
    <li><strong>USD/JPY</strong> — trends with US rates, active in Asia.</li>
    <li><strong>Gold</strong> — trends on macro, safe-haven flows.</li>
    <li><strong>S&P 500</strong> — trends upward over time, responsive to news.</li>
</ul>

<h3>Timeframe selection criteria</h3>
<p>Timeframes determine how often you trade and how much noise you encounter:</p>

<h4>1. Available time</h4>
<ul>
    <li><strong>Full-time trader</strong> — can trade M1–M15 (scalping) or H1 (day trading).</li>
    <li><strong>Part-time (2–4 hours)</strong> — H1–H4 (day trading or swing trading).</li>
    <li><strong>Evenings/weekends</strong> — H4–Daily (swing trading).</li>
    <li><strong>Sporadic</strong> — Daily–Weekly (position trading).</li>
</ul>

<h4>2. Noise vs signal</h4>
<p>Lower timeframes have more noise:</p>
<ul>
    <li><strong>M1–M5</strong> — 70–80% noise. Very difficult.</li>
    <li><strong>M15–H1</strong> — 50–60% noise. Manageable with practice.</li>
    <li><strong>H4–Daily</strong> — 30–40% noise. Reasonable signal clarity.</li>
    <li><strong>Weekly–Monthly</strong> — 20–30% noise. High signal clarity.</li>
</ul>
<p>Higher timeframes provide cleaner signals but fewer opportunities.</p>

<h4>3. Opportunity frequency</h4>
<ul>
    <li><strong>M1–M5</strong> — dozens of setups per day.</li>
    <li><strong>M15–H1</strong> — 3–10 setups per day.</li>
    <li><strong>H4</strong> — 1–3 setups per week.</li>
    <li><strong>Daily</strong> — 2–4 setups per month.</li>
    <li><strong>Weekly</strong> — 4–12 setups per year.</li>
</ul>

<h4>4. Cost impact</h4>
<p>Spread and commission consume a larger percentage of small timeframes:</p>
<table>
    <thead><tr><th>Timeframe</th><th>Typical Stop</th><th>1-Pip Spread Cost</th></tr></thead>
    <tbody>
        <tr><td>M5</td><td>8 pips</td><td>12.5%</td></tr>
        <tr><td>M15</td><td>15 pips</td><td>6.7%</td></tr>
        <tr><td>H1</td><td>30 pips</td><td>3.3%</td></tr>
        <tr><td>H4</td><td>50 pips</td><td>2.0%</td></tr>
        <tr><td>Daily</td><td>100 pips</td><td>1.0%</td></tr>
    </tbody>
</table>
<p>This cost difference is one reason why higher timeframes are more forgiving for retail traders.</p>

<h3>Visual reference — Timeframe trade-offs</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Axes -->
  <line x1="60" y1="250" x2="470" y2="250" stroke="#8b93a7" stroke-width="1"/>
  <line x1="60" y1="40" x2="60" y2="250" stroke="#8b93a7" stroke-width="1"/>

  <!-- Axis labels -->
  <text x="265" y="280" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Timeframe (Low → High)</text>
  <text x="30" y="145" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" transform="rotate(-90 30 145)">Value</text>

  <!-- Signal clarity line (increases with timeframe) -->
  <polyline points="80,220 150,180 220,140 290,100 360,70 430,55"
            fill="none" stroke="#4ade80" stroke-width="2.5"/>
  <text x="420" y="50" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Signal clarity</text>

  <!-- Noise line (decreases with timeframe) -->
  <polyline points="80,60 150,100 220,140 290,180 360,210 430,230"
            fill="none" stroke="#ef4444" stroke-width="2.5"/>
  <text x="420" y="225" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">Noise</text>

  <!-- Opportunity line (decreases with timeframe) -->
  <polyline points="80,80 150,110 220,150 290,190 360,215 430,235"
            fill="none" stroke="#f97316" stroke-width="2" stroke-dasharray="4,3"/>
  <text x="420" y="250" fill="#f97316" font-size="10" font-family="Inter,sans-serif">Opportunities</text>

  <!-- Optimal zone -->
  <rect x="270" y="40" width="100" height="210" fill="#5b7cfa" fill-opacity="0.08"/>
  <text x="320" y="270" fill="#5b7cfa" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Sweet spot</text>
  <text x="320" y="285" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">(H4–Daily)</text>
</svg>

<h3>Recommended combinations</h3>
<p>For most traders, these combinations work well:</p>

<h4>Conservative beginner</h4>
<ul>
    <li><strong>Market:</strong> EUR/USD only.</li>
    <li><strong>Timeframe:</strong> Daily for bias, H4 for entries.</li>
    <li><strong>Style:</strong> Swing trading.</li>
    <li><strong>Trades/month:</strong> 4–8.</li>
</ul>

<h4>Intermediate swing trader</h4>
<ul>
    <li><strong>Markets:</strong> EUR/USD, GBP/USD, USD/JPY (three pairs).</li>
    <li><strong>Timeframe:</strong> H4 for setups, H1 for entries.</li>
    <li><strong>Style:</strong> Swing trading.</li>
    <li><strong>Trades/month:</strong> 10–15.</li>
</ul>

<h4>Day trader</h4>
<ul>
    <li><strong>Markets:</strong> EUR/USD, GBP/USD.</li>
    <li><strong>Timeframe:</strong> H1 for setups, M15 for entries.</li>
    <li><strong>Style:</strong> Day trading during London/NY.</li>
    <li><strong>Trades/month:</strong> 40–60.</li>
</ul>

<h4>Multi-market swing trader</h4>
<ul>
    <li><strong>Markets:</strong> 2 FX pairs, 1 index, 1 commodity.</li>
    <li><strong>Timeframe:</strong> Daily for bias, H4 for entries.</li>
    <li><strong>Style:</strong> Swing trading.</li>
    <li><strong>Trades/month:</strong> 15–25.</li>
</ul>

<h3>Market behaviour comparison</h3>
<table>
    <thead><tr><th>Market</th><th>Volatility</th><th>Trends</th><th>Best For</th></tr></thead>
    <tbody>
        <tr><td>EUR/USD</td><td>Moderate</td><td>Clean</td><td>All styles</td></tr>
        <tr><td>GBP/USD</td><td>High</td><td>Aggressive</td><td>Experienced swing</td></tr>
        <tr><td>USD/JPY</td><td>Moderate</td><td>Rate-driven</td><td>Swing, position</td></tr>
        <tr><td>AUD/USD</td><td>Moderate</td><td>Commodity-linked</td><td>Swing</td></tr>
        <tr><td>Gold</td><td>High</td><td>Macro-driven</td><td>Swing, position</td></tr>
        <tr><td>S&P 500</td><td>Moderate</td><td>Long-term up</td><td>Buy-and-hold, swing</td></tr>
        <tr><td>Bitcoin</td><td>Extreme</td><td>Strong cycles</td><td>Position, high risk</td></tr>
    </tbody>
</table>

<h3>Specialisation vs diversification</h3>
<p>Traders must choose between focus and breadth:</p>

<h4>Specialisation (1 market, 1 timeframe)</h4>
<ul>
    <li><strong>Pros:</strong> Deep expertise, cleaner patterns, fewer decisions.</li>
    <li><strong>Cons:</strong> Fewer opportunities, higher boredom, single-market risk.</li>
</ul>

<h4>Diversification (3–5 markets)</h4>
<ul>
    <li><strong>Pros:</strong> More opportunities, smoother equity curve, less dependence on one market.</li>
    <li><strong>Cons:</strong> More analysis, more screens, more decisions.</li>
</ul>
<p>For most retail traders, 2–4 markets on one or two timeframes provides the optimal balance.</p>

<h3>Visual reference — Market/timeframe matrix</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Matrix grid -->
  <!-- Column headers: timeframes -->
  <text x="140" y="30" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">H1</text>
  <text x="230" y="30" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">H4</text>
  <text x="320" y="30" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Daily</text>
  <text x="410" y="30" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Weekly</text>

  <!-- Row 1: EUR/USD -->
  <text x="80" y="70" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">EUR/USD</text>
  <rect x="110" y="55" width="60" height="30" fill="#4ade80" fill-opacity="0.5"/>
  <rect x="200" y="55" width="60" height="30" fill="#4ade80" fill-opacity="0.7"/>
  <rect x="290" y="55" width="60" height="30" fill="#4ade80" fill-opacity="0.9"/>
  <rect x="380" y="55" width="60" height="30" fill="#4ade80" fill-opacity="0.6"/>

  <!-- Row 2: GBP/USD -->
  <text x="80" y="110" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">GBP/USD</text>
  <rect x="110" y="95" width="60" height="30" fill="#eab308" fill-opacity="0.5"/>
  <rect x="200" y="95" width="60" height="30" fill="#4ade80" fill-opacity="0.7"/>
  <rect x="290" y="95" width="60" height="30" fill="#4ade80" fill-opacity="0.9"/>
  <rect x="380" y="95" width="60" height="30" fill="#4ade80" fill-opacity="0.6"/>

  <!-- Row 3: USD/JPY -->
  <text x="80" y="150" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">USD/JPY</text>
  <rect x="110" y="135" width="60" height="30" fill="#4ade80" fill-opacity="0.5"/>
  <rect x="200" y="135" width="60" height="30" fill="#4ade80" fill-opacity="0.7"/>
  <rect x="290" y="135" width="60" height="30" fill="#4ade80" fill-opacity="0.9"/>
  <rect x="380" y="135" width="60" height="30" fill="#4ade80" fill-opacity="0.6"/>

  <!-- Row 4: Gold -->
  <text x="80" y="190" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Gold</text>
  <rect x="110" y="175" width="60" height="30" fill="#eab308" fill-opacity="0.5"/>
  <rect x="200" y="175" width="60" height="30" fill="#4ade80" fill-opacity="0.7"/>
  <rect x="290" y="175" width="60" height="30" fill="#4ade80" fill-opacity="0.9"/>
  <rect x="380" y="175" width="60" height="30" fill="#4ade80" fill-opacity="0.6"/>

  <!-- Row 5: Crypto -->
  <text x="80" y="230" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Crypto</text>
  <rect x="110" y="215" width="60" height="30" fill="#ef4444" fill-opacity="0.4"/>
  <rect x="200" y="215" width="60" height="30" fill="#eab308" fill-opacity="0.5"/>
  <rect x="290" y="215" width="60" height="30" fill="#4ade80" fill-opacity="0.7"/>
  <rect x="380" y="215" width="60" height="30" fill="#4ade80" fill-opacity="0.9"/>

  <!-- Legend -->
  <rect x="150" y="270" width="12" height="12" fill="#4ade80" fill-opacity="0.9"/>
  <text x="170" y="280" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">Good fit</text>
  <rect x="250" y="270" width="12" height="12" fill="#eab308" fill-opacity="0.7"/>
  <text x="270" y="280" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">Moderate</text>
  <rect x="350" y="270" width="12" height="12" fill="#ef4444" fill-opacity="0.5"/>
  <text x="370" y="280" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">Not recommended</text>
</svg>

<h3>Building your trading universe</h3>
<ol>
    <li><strong>Choose 1 primary market.</strong> Master it before adding others.</li>
    <li><strong>Add 1–2 secondary markets.</strong> Only after primary market is profitable.</li>
    <li><strong>Choose 1–2 timeframes.</strong> Typically bias on higher TF, entries on lower TF.</li>
    <li><strong>Set a schedule.</strong> When will you analyse? When will you trade?</li>
    <li><strong>Track performance by market.</strong> Some markets may fit better than others.</li>
    <li><strong>Adjust over time.</strong> Drop markets that don't work; add ones that do.</li>
</ol>

<h2>Factual context</h2>
<p>Market and timeframe selection is a foundational topic in trading education:</p>
<p><strong>Al Brooks</strong> — recommends focusing on a single timeframe and a few markets to develop depth. His framework assumes familiarity with one market's behaviour.</p>
<p><strong>Mark Douglas</strong> — emphasises the importance of simplicity. Fewer markets and timeframes reduce the number of decisions.</p>
<p><strong>Van Tharp</strong> — argues that different markets suit different systems. Testing a system across markets is essential for validating its robustness.</p>
<p><strong>Brett Steenbarger</strong> — notes that successful traders often specialise deeply in a few markets rather than trading broadly.</p>
<p>Ed Seykota, on market focus:</p>
<blockquote><strong>\"I like to trade markets where I can see clear trends. Some markets are better than others. I stick to the ones that work.\"</strong></blockquote>
<p>Seykota's point: not all markets suit all approaches. Choose markets where your strategy works.</p>
<p>Paul Tudor Jones, on timeframes:</p>
<blockquote><strong>\"I look at the daily and weekly charts. Below that, I can't see the market clearly enough.\"</strong></blockquote>
<p>Jones' preference for higher timeframes is common among professional traders. Clarity is more important than frequency.</p>
<p>Bruce Kovner, describing his markets:</p>
<blockquote><strong>\"I trade major currencies, bonds, and commodities. They have enough liquidity and volatility to be interesting without being chaotic.\"</strong></blockquote>
<p>Kovner's choice reflects the market selection criteria in this lesson. Liquidity and volatility are the key factors.</p>
<p>Larry Hite, on the importance of choosing the right markets:</p>
<blockquote><strong>\"The market you choose is as important as your strategy. Some markets are simply easier to trade.\"</strong></blockquote>
<p>Hite's point: market selection matters. Don't just trade whatever is popular.</p>
<p>Warren Buffett, on specialisation:</p>
<blockquote><strong>\"You don't have to be an expert on everything. You just have to know what you're doing in one area.\"</strong></blockquote>
<p>Buffett's wisdom applies to markets. Specialising beats spreading thin.</p>
<p>Jesse Livermore, on focus:</p>
<blockquote><strong>\"I don't try to know everything. I just try to know what I'm doing.\"</strong></blockquote>
<p>Livermore's focus is the professional standard. Depth in a few markets beats breadth in many.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading too many markets.</strong> Trying to master 10 markets means mastering none. Start with 1–3.</li>
    <li><strong>Choosing markets based on excitement.</strong> Crypto is exciting but difficult. Choose based on fit, not flash.</li>
    <li><strong>Trading low timeframes without the time to monitor.</strong> Scalping requires full-screen attention. If you can't give it, don't.</li>
    <li><strong>Ignoring spread impact.</strong> Scalping with a wide-spread broker is a losing proposition.</li>
    <li><strong>Switching markets when one underperforms.</strong> Give markets time. Switching after a few losses resets the sample size.</li>
    <li><strong>Trading markets you don't understand.</strong> If you don't understand what moves a market, don't trade it.</li>
    <li><strong>Confusing correlated markets with diversification.</strong> EUR/USD and GBP/USD are correlated. Trading both is not diversification.</li>
    <li><strong>Not testing markets before trading them live.</strong> Test each market in demo before committing capital.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often use the following framework for market selection:</p>
<ol>
    <li><strong>Primary market</strong> — where 60–70% of trades occur.</li>
    <li><strong>Secondary market</strong> — where 20–30% of trades occur.</li>
    <li><strong>Opportunistic market</strong> — where 10% of trades occur, only in clear conditions.</li>
</ol>
<p>This structure provides diversification while maintaining focus. The primary market is the core; the others are supplements.</p>
<p>Timeframe selection often follows a similar pattern:</p>
<ol>
    <li><strong>Bias timeframe</strong> — higher timeframe for direction (daily or weekly).</li>
    <li><strong>Setup timeframe</strong> — intermediate for identifying setups (H4 or H1).</li>
    <li><strong>Entry timeframe</strong> — lower for precise entries (H1 or M15).</li>
</ol>
<p>The three-timeframe framework (covered in earlier modules) applies directly to strategy design.</p>
<p>Another advanced technique: <strong>session-focused trading</strong>. Some traders focus only on specific sessions (London open, NY open) and trade only during those windows. This reduces exposure to low-liquidity hours and concentrates on the most active periods.</p>
<p>The most important insight: no market or timeframe is universally best. The best choice is the one that fits your objectives, availability, and psychology. A trader who trades EUR/USD on the daily chart will have a very different experience than one who trades Bitcoin on the M5. Both can be profitable — but only if the choice fits the trader.</p>
<p>With markets and timeframes chosen, you are ready to design the specific components of your strategy. The next lessons cover entry rules, exit rules, position sizing, and filters.</p>
HTML,
        ],

        [
            'slug'   => 'defining-entry-rules',
            'title'  => 'Defining Entry Rules',
            'difficulty' => 'professional',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Write specific entry rules\n" .
                "• Balance precision with practicality\n" .
                "• Understand setup vs trigger\n" .
                "• Test entry rules against historical data",
            'prerequisites' => 'Choosing Your Market and Timeframe',
            'sort_order' => 4,
            'summary' => 'Entry rules are the specific conditions that trigger a trade. Vague entries produce inconsistent results. Specific entries produce measurable performance. This lesson teaches you to write entries that are precise, testable, and practical.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine telling a pilot to "fly toward the city." Without specific directions, the pilot could end up anywhere. Now imagine saying "fly heading 245, altitude 10,000 feet, at 250 knots." The pilot knows exactly what to do.</p>
<p>Entry rules work the same way. Vague entries ("buy when it looks bullish") produce inconsistent results. Specific entries ("buy when price closes above the 20 EMA in an uptrend") can be tested and refined.</p>

<h2>Real-world analogy</h2>
<p>Think of a recipe. "Add some flour" is vague. "Add 250 grams of flour" is specific. Recipes with specific instructions produce consistent results. Recipes with vague instructions produce unpredictable ones.</p>

<h2>Professional explanation</h2>

<h3>Setup vs trigger</h3>
<p>An entry has two parts:</p>
<ol>
    <li><strong>Setup</strong> — the context. What conditions must exist before the trade?</li>
    <li><strong>Trigger</strong> — the specific event that initiates the trade.</li>
</ol>
<p>Both are necessary. A setup without a trigger produces no entry. A trigger without a setup produces random entries.</p>

<h3>Visual reference — Setup vs trigger</h3>
<svg viewBox="0 0 500 280" width="500" height="280" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Setup zone -->
  <rect x="30" y="30" width="440" height="60" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="2" rx="6"/>
  <text x="250" y="55" fill="#5b7cfa" font-size="14" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">SETUP (Context)</text>
  <text x="250" y="75" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Trend + pullback to a level + confirmation zone</text>

  <!-- Arrow -->
  <line x1="250" y1="90" x2="250" y2="130" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,130 245,120 255,120" fill="#8b93a7"/>

  <!-- Trigger zone -->
  <rect x="30" y="140" width="440" height="60" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="250" y="165" fill="#4ade80" font-size="14" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">TRIGGER (Event)</text>
  <text x="250" y="185" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Bullish engulfing close above the level</text>

  <!-- Arrow -->
  <line x1="250" y1="200" x2="250" y2="240" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,240 245,230 255,230" fill="#8b93a7"/>

  <!-- Execution -->
  <rect x="120" y="245" width="260" height="30" fill="#f97316" fill-opacity="0.2" stroke="#f97316" stroke-width="2" rx="6"/>
  <text x="250" y="265" fill="#f97316" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">EXECUTE AT CLOSE OF TRIGGER CANDLE</text>
</svg>

<h3>How to write specific entry rules</h3>
<p>Good entry rules answer these questions:</p>
<ol>
    <li><strong>What setup conditions must exist?</strong> (trend, level, pattern)</li>
    <li><strong>What is the exact trigger?</strong> (candle close, structure break, indicator signal)</li>
    <li><strong>What invalidates the setup?</strong> (structure break, indicator flip)</li>
    <li><strong>When can the entry occur?</strong> (session, time window)</li>
    <li><strong>When should the entry be skipped?</strong> (news, conditions)</li>
</ol>

<h3>Examples of good vs bad rules</h3>
<table>
    <thead><tr><th>Bad Rule (Vague)</th><th>Good Rule (Specific)</th></tr></thead>
    <tbody>
        <tr><td>Buy when it looks bullish</td><td>Buy when price closes above the 20 EMA with a bullish engulfing candle</td></tr>
        <tr><td>Enter on a pullback</td><td>Enter on a pullback to the 50 MA when a bullish pin bar forms</td></tr>
        <tr><td>Take a trade after news</td><td>Enter 30 minutes after high-impact news when direction is established</td></tr>
        <tr><td>Trade strong trends</td><td>Trade when 50 MA is above 200 MA and price makes higher highs and higher lows</td></tr>
    </tbody>
</table>
<p>Specific rules can be tested. Vague rules cannot.</p>

<h3>The four entry rule components</h3>

<h4>1. Trend filter</h4>
<p>Defines the direction bias:</p>
<ul>
    <li>Structure-based: HH/HL = bullish; LH/LL = bearish.</li>
    <li>MA-based: price above 50 MA = bullish; below = bearish.</li>
    <li>Multi-timeframe: daily trend defines allowed direction.</li>
</ul>

<h4>2. Location filter</h4>
<p>Defines where entries can occur:</p>
<ul>
    <li>Support/resistance levels.</li>
    <li>Moving average pullback zones.</li>
    <li>Fibonacci retracement levels.</li>
    <li>Order blocks and FVGs.</li>
    <li>Round numbers.</li>
</ul>

<h4>3. Confirmation filter</h4>
<p>Defines the specific signal:</p>
<ul>
    <li>Candle patterns (engulfing, pin bar, inside bar).</li>
    <li>Structure breaks (CHoCH, BOS).</li>
    <li>Indicator signals (RSI turning, MACD crossover).</li>
    <li>Volume or displacement.</li>
</ul>

<h4>4. Timing filter</h4>
<p>Defines when entries are allowed:</p>
<ul>
    <li>Session-specific (London, NY).</li>
    <li>Day-specific (avoid Fridays).</li>
    <li>News-filtered (no trades before major events).</li>
    <li>Condition-specific (only in clear trends).</li>
</ul>

<h3>Worked example — A pullback entry</h3>
<p>Let's write a complete pullback entry rule:</p>

<h4>Trend filter</h4>
<ul>
    <li>Daily chart: price above 50 MA.</li>
    <li>H4 chart: HH/HL structure intact.</li>
</ul>

<h4>Location filter</h4>
<ul>
    <li>Pullback to H4 20 EMA or 50% Fibonacci retracement.</li>
    <li>Or retest of a prior resistance-turned-support.</li>
</ul>

<h4>Confirmation filter</h4>
<ul>
    <li>Bullish engulfing candle OR bullish pin bar at the level.</li>
    <li>The candle must close above the level (not just wick).</li>
</ul>

<h4>Timing filter</h4>
<ul>
    <li>Entry only during London or NY kill zones.</li>
    <li>No entries within 30 minutes of high-impact news.</li>
</ul>

<h4>Invalidation</h4>
<ul>
    <li>Stop below the pullback low.</li>
    <li>Skip if the pullback exceeds 78.6% of the prior impulse.</li>
</ul>

<p>This rule is complete. Every entry that satisfies these conditions is taken. Every setup that doesn't is skipped.</p>

<h3>Visual reference — Entry rule flow</h3>
<svg viewBox="0 0 500 400" width="500" height="400" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Start -->
  <ellipse cx="250" cy="30" rx="80" ry="20" fill="#5b7cfa" fill-opacity="0.3" stroke="#5b7cfa" stroke-width="2"/>
  <text x="250" y="35" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Start</text>

  <!-- Decision 1: Trend filter -->
  <polygon points="250,70 400,110 250,150 100,110" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="2"/>
  <text x="250" y="105" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Trend filter met?</text>
  <text x="250" y="122" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Daily &amp; H4 trend aligned</text>

  <!-- Arrow down -->
  <line x1="250" y1="150" x2="250" y2="180" stroke="#8b93a7" stroke-width="1.5"/>
  <text x="270" y="170" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Yes</text>

  <!-- Arrow to no -->
  <line x1="100" y1="110" x2="50" y2="110" stroke="#ef4444" stroke-width="1.5"/>
  <text x="70" y="105" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">No</text>
  <text x="50" y="130" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">Skip</text>

  <!-- Decision 2: Location filter -->
  <polygon points="250,220 400,260 250,300 100,260" fill="#f97316" fill-opacity="0.2" stroke="#f97316" stroke-width="2"/>
  <text x="250" y="250" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Location reached?</text>
  <text x="250" y="270" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">At pullback level</text>

  <!-- Arrow down -->
  <line x1="250" y1="300" x2="250" y2="330" stroke="#8b93a7" stroke-width="1.5"/>
  <text x="270" y="320" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Yes</text>

  <!-- Decision 3: Confirmation -->
  <polygon points="250,370 400,400 250,430 100,400" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="2"/>
  <text x="250" y="400" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Confirmation met?</text>
  <text x="250" y="418" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Bullish engulfing / pin bar</text>

  <!-- Result -->
  <text x="450" y="400" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Execute</text>
</svg>

<h3>Testing entry rules</h3>
<p>Before committing real capital, entry rules must be tested:</p>
<ol>
    <li><strong>Backtest manually.</strong> Go through historical charts and identify every setup that met the rules.</li>
    <li><strong>Record outcomes.</strong> For each setup, note the result (win/loss) and R-multiple.</li>
    <li><strong>Calculate expectancy.</strong> (Win rate × Avg win) − (Loss rate × Avg loss).</li>
    <li><strong>Refine if needed.</strong> If expectancy is negative, adjust rules.</li>
    <li><strong>Forward test.</strong> Run the rules on live charts (demo) for 30+ trades.</li>
    <li><strong>Compare to backtest.</strong> If results match, proceed to live. If not, investigate.</li>
</ol>

<h3>Common entry rule mistakes</h3>
<ul>
    <li><strong>Too vague.</strong> "Buy bullish setups" is not a rule.</li>
    <li><strong>Too many filters.</strong> If rules require 10 conditions, no trade ever qualifies.</li>
    <li><strong>Rules that conflict.</strong> If one rule says "wait for pullback" and another says "enter on breakout," you'll never trade.</li>
    <li><strong>Ignoring session context.</strong> A rule that works in London may fail in Asia.</li>
    <li><strong>Not testing.</strong> Untested rules are guesses.</li>
    <li><strong>Optimising for past data.</strong> Rules that fit history perfectly often fail in the future.</li>
    <li><strong>Changing rules after losses.</strong> Refine only after significant sample size (100+ trades).</li>
    <li><strong>Confusing entries with outcomes.</strong> A good entry rule can produce losing trades. Judge the rule, not individual trades.</li>
</ul>

<h3>Visual reference — Entry quality checklist</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Checklist items -->
  <rect x="30" y="30" width="440" height="35" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="45" y="53" fill="#4ade80" font-size="13" font-family="Inter,sans-serif">✓</text>
  <text x="70" y="53" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif">Trend filter aligned with higher timeframe</text>

  <rect x="30" y="75" width="440" height="35" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="45" y="98" fill="#4ade80" font-size="13" font-family="Inter,sans-serif">✓</text>
  <text x="70" y="98" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif">Location reached (level, MA, or Fibonacci zone)</text>

  <rect x="30" y="120" width="440" height="35" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="45" y="143" fill="#4ade80" font-size="13" font-family="Inter,sans-serif">✓</text>
  <text x="70" y="143" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif">Confirmation candle close (not just wick)</text>

  <rect x="30" y="165" width="440" height="35" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="45" y="188" fill="#4ade80" font-size="13" font-family="Inter,sans-serif">✓</text>
  <text x="70" y="188" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif">Session timing correct (kill zone)</text>

  <rect x="30" y="210" width="440" height="35" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="45" y="233" fill="#4ade80" font-size="13" font-family="Inter,sans-serif">✓</text>
  <text x="70" y="233" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif">No news imminent</text>
</svg>

<h2>Factual context</h2>
<p>The importance of specific entry rules is a consistent theme in professional trading:</p>
<p><strong>The Turtle Traders</strong> — used extremely specific rules (20-day and 55-day breakout). The rules could be taught because they were unambiguous.</p>
<p><strong>Richard Dennis</strong> — famously said trading could be taught because rules were specific. His experiment proved that novices could learn and apply explicit rules.</p>
<p><strong>Van Tharp</strong> — emphasises that trading systems must be completely specified to be testable.</p>
<p><strong>Mark Douglas</strong> — describes the discipline of following specific rules as the foundation of consistent results.</p>
<p>Richard Dennis, describing the Turtle system:</p>
<blockquote><strong>\"I have always maintained that one could train a group of people to be successful traders using a small set of rules. The rules must be specific. Nonspecific rules cannot be followed consistently.\"</strong></blockquote>
<p>Dennis' point: specificity is essential for consistency. Vague rules produce vague results.</p>
<p>Ed Seykota, on rule clarity:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules are simple but specific. They can be followed without ambiguity.</p>
<p>Paul Tudor Jones, on entry discipline:</p>
<blockquote><strong>\"I know my entry conditions before the market opens. When they occur, I trade. When they don't, I don't.\"</strong></blockquote>
<p>Jones' point: entries should be decided in advance, not in the heat of the moment.</p>
<p>Bruce Kovner, on the value of specific entries:</p>
<blockquote><strong>\"I look for a specific setup. If I don't see it, I don't trade. If I see it, I take it. There's no middle ground.\"</strong></blockquote>
<p>Kovner's clarity is the model. Entries are binary — either the setup exists or it doesn't.</p>
<p>Larry Hite, on simplicity:</p>
<blockquote><strong>\"Simple rules work. Complex rules fail. Keep your entries simple enough that you can follow them under pressure.\"</strong></blockquote>
<p>Hite's point captures an important truth. Complex entry rules are harder to follow than simple ones.</p>
<p>Jesse Livermore, on the essence:</p>
<blockquote><strong>\"The market always has its pivotal points. If a stock doesn't act right at the pivotal point, don't touch it.\"</strong></blockquote>
<p>Livermore's pivotal points were specific conditions. When they occurred, he traded. When they didn't, he waited.</p>
<p>Mark Douglas, on why specific rules matter psychologically:</p>
<blockquote><strong>\"When you have specific rules, you don't have to think in the moment. You just follow the rules. This is how professionals trade.\"</strong></blockquote>
<p>Douglas' insight is critical. Specific rules remove the need for in-the-moment decisions, which reduces emotional interference.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Writing rules from memory instead of from data.</strong> Rules should be tested before being adopted.</li>
    <li><strong>Too many entry conditions.</strong> Simpler is better. 3–5 conditions is usually enough.</li>
    <li><strong>Confusing the setup with the trigger.</strong> Both are necessary. Neither alone is sufficient.</li>
    <li><strong>Not specifying invalidation.</strong> Without invalidation, rules are incomplete.</li>
    <li><strong>Ignoring session timing.</strong> Rules that work in one session may fail in another.</li>
    <li><strong>Never testing the rules.</strong> Untested rules are guesses.</li>
    <li><strong>Testing with too few trades.</strong> 30 trades is the minimum for a rough estimate. 100+ is better.</li>
    <li><strong>Refining endlessly.</strong> Perfection is impossible. A good-enough rule set is better than no rule set.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use variations of entry rules:</p>
<ul>
    <li><strong>Primary and secondary entries</strong> — two entries for the same setup, increasing size at the second.</li>
    <li><strong>Scaling entries</strong> — entering in thirds, with each entry requiring additional confirmation.</li>
    <li><strong>Conditional entries</strong> — different entries for different market conditions.</li>
    <li><strong>Time-based entries</strong> — entries only during specific windows.</li>
    <li><strong>Volatility-conditioned entries</strong> — different entries for high vs low volatility.</li>
</ul>
<p>Another advanced technique: <strong>the two-strike rule</strong>. If a setup appears and fails, the second appearance of the same setup (after a structure reset) is often higher probability. This is Al Brooks' "second entry" concept.</p>
<p>The most important insight: entry rules are the heart of a strategy. Well-designed rules produce consistent results. Poorly-designed rules produce random outcomes. Spend the time to design them properly.</p>
<p>With entry rules defined, the next step is to define exit rules — where to place stops, targets, and how to manage open positions. This is covered in the next lesson.</p>
HTML,
        ],

        [
            'slug'   => 'defining-exit-rules',
            'title'  => 'Defining Exit Rules',
            'difficulty' => 'professional',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Design stop-loss rules\n" .
                "• Design take-profit rules\n" .
                "• Choose trade management approach\n" .
                "• Balance letting winners run vs locking profits",
            'prerequisites' => 'Defining Entry Rules',
            'sort_order' => 5,
            'summary' => 'Exit rules determine where a trade ends — either as a loss, a profit, or a break-even. Well-designed exits preserve capital on losers and capture profits on winners. This lesson teaches the three types of exits and how to combine them.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine opening a shop. You have to decide: when will I close? What prices will I sell at? What if inventory isn't selling? Without exit rules, you'd sit in the shop indefinitely, not knowing when to call it a day. Trading works the same way.</p>
<p>Exits determine the outcome of every trade. Without clear exits, trades become emotional decisions.</p>

<h2>Real-world analogy</h2>
<p>Think of a fire safety plan. Every building has rules: when to evacuate, where to meet, how to exit. The plan exists before the emergency. Trading exits work the same way — pre-decided, not improvised.</p>

<h2>Professional explanation</h2>

<h3>The three types of exits</h3>
<p>Every trade has three possible exits:</p>
<ol>
    <li><strong>Stop-loss</strong> — the maximum acceptable loss.</li>
    <li><strong>Take-profit</strong> — the target profit.</li>
    <li><strong>Trailing stop or time exit</strong> — dynamic management of winners.</li>
</ol>

<h3>Visual reference — Trade lifecycle with exits</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Entry -->
  <circle cx="60" cy="180" r="10" fill="none" stroke="#5b7cfa" stroke-width="2.5"/>
  <text x="60" y="205" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Entry</text>
  <text x="60" y="220" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">1.0850</text>

  <!-- Stop loss line -->
  <line x1="30" y1="240" x2="470" y2="240" stroke="#ef4444" stroke-width="2" stroke-dasharray="4,3"/>
  <text x="480" y="244" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">Stop (-1R)</text>

  <!-- Take profit line -->
  <line x1="30" y1="80" x2="470" y2="80" stroke="#4ade80" stroke-width="2" stroke-dasharray="4,3"/>
  <text x="480" y="84" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Target (+3R)</text>

  <!-- Price action -->
  <polyline points="60,180 100,150 140,170 180,130 240,90 300,100 360,70 420,50"
            fill="none" stroke="#e6e9ef" stroke-width="2"/>

  <!-- Move to break-even -->
  <circle cx="200" cy="120" r="8" fill="none" stroke="#f97316" stroke-width="2"/>
  <text x="200" y="105" fill="#f97316" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Move stop</text>
  <text x="200" y="95" fill="#f97316" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">to BE</text>

  <!-- Target reached -->
  <circle cx="420" cy="50" r="8" fill="none" stroke="#4ade80" stroke-width="2.5"/>
  <text x="420" y="35" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Exit at target</text>

  <!-- R-multiple labels -->
  <text x="250" y="270" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Risk 1R, target 3R = 3:1 reward-to-risk</text>
</svg>

<h3>Stop-loss rules</h3>
<p>Stop-loss placement determines risk on every trade. There are four common approaches:</p>

<h4>1. Structural stop</h4>
<p>Place the stop beyond the structure that would invalidate the trade:</p>
<ul>
    <li><strong>Long trades:</strong> below the recent swing low or the level of the setup.</li>
    <li><strong>Short trades:</strong> above the recent swing high or the level of the setup.</li>
</ul>
<p>This is the most reliable approach. Stops make sense because they're at levels where the trade's thesis is invalidated.</p>

<h4>2. ATR-based stop</h4>
<p>Place the stop at a multiple of ATR (typically 1.5× or 2×) from entry:</p>
<ul>
    <li>Adjusts automatically to volatility.</li>
    <li>Wider stops in volatile markets; tighter in quiet markets.</li>
    <li>Combine with structural analysis for best results.</li>
</ul>

<h4>3. Fixed pip stop</h4>
<p>Use a fixed pip distance (e.g., 30 pips).</p>
<ul>
    <li>Simple but inflexible.</li>
    <li>Doesn't adapt to volatility.</li>
    <li>Not recommended as a primary approach.</li>
</ul>

<h4>4. Percentage stop</h4>
<p>Place stop at a percentage away from entry (e.g., 1%).</p>
<ul>
    <li>Common in stock trading.</li>
    <li>Less common in FX.</li>
</ul>

<h3>Take-profit rules</h3>
<p>Take-profit placement determines the reward on winning trades. There are four common approaches:</p>

<h4>1. Fixed R-multiple</h4>
<p>Target a multiple of the risk (e.g., 2R or 3R).</p>
<ul>
    <li>Simple and clear.</li>
    <li>Produces consistent R:R across trades.</li>
    <li>May miss larger moves in strong trends.</li>
</ul>

<h4>2. Structural target</h4>
<p>Target the next significant level (prior swing high, round number, resistance).</p>
<ul>
    <li>Aligns targets with market structure.</li>
    <li>Variable R:R across trades.</li>
    <li>More realistic in nature.</li>
</ul>

<h4>3. Measured move</h4>
<p>Target the projection of a prior move or pattern.</p>
<ul>
    <li>Based on market geometry.</li>
    <li>Effective in patterns (flags, ranges, triangles).</li>
</ul>

<h4>4. Fibonacci extension</h4>
<p>Target 127.2% or 161.8% extensions of the prior impulse.</p>
<ul>
    <li>Popular in trending markets.</li>
    <li>Produces variable R:R.</li>
    <li>Works well with pullback entries.</li>
</ul>

<h3>Trade management approaches</h3>

<h4>1. Fixed exits (set and forget)</h4>
<p>Place stop and target at entry. Don't touch either.</p>
<ul>
    <li><strong>Pros:</strong> Simplest. No decisions after entry. Consistent R:R.</li>
    <li><strong>Cons:</strong> Misses trailing opportunities. Full loss or full win.</li>
</ul>

<h4>2. Move to break-even</h4>
<p>After the trade moves 1R in your favour, move stop to entry.</p>
<ul>
    <li><strong>Pros:</strong> Eliminates loss on trades that have moved into profit.</li>
    <li><strong>Cons:</strong> Often stops out on normal pullbacks before the trade develops.</li>
</ul>

<h4>3. Partial profits</h4>
<p>Take some profit at 1R, let the rest run.</p>
<ul>
    <li><strong>Pros:</strong> Locks in gains while keeping exposure to large moves.</li>
    <li><strong>Cons:</strong> Reduces the average win, especially when trades run.</li>
</ul>

<h4>4. Trailing stop</h4>
<p>Move the stop with price as it moves in your favour.</p>
<ul>
    <li><strong>Pros:</strong> Captures trends, protects profits.</li>
    <li><strong>Cons:</strong> Requires active management. Can exit too early in choppy trends.</li>
</ul>

<h4>5. Time-based exit</h4>
<p>Exit after a certain number of bars if the trade hasn't progressed.</p>
<ul>
    <li><strong>Pros:</strong> Frees capital from stale trades.</li>
    <li><strong>Cons:</strong> May exit just before the trade starts moving.</li>
</ul>

<h3>Visual reference — Management approaches comparison</h3>
<svg viewBox="0 0 500 320" width="500" height="320" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Fixed exits -->
  <text x="125" y="25" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">FIXED EXITS</text>
  <line x1="40" y1="70" x2="210" y2="70" stroke="#4ade80" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="220" y="74" fill="#4ade80" font-size="9" font-family="Inter,sans-serif">Target</text>
  <line x1="40" y1="200" x2="210" y2="200" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="220" y="204" fill="#ef4444" font-size="9" font-family="Inter,sans-serif">Stop</text>
  <polyline points="40,140 80,120 120,130 160,100 200,70" fill="none" stroke="#e6e9ef" stroke-width="2"/>
  <circle cx="200" cy="70" r="5" fill="#4ade80"/>

  <!-- Move to break-even -->
  <text x="375" y="25" fill="#f97316" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">MOVE TO BE</text>
  <line x1="290" y1="70" x2="460" y2="70" stroke="#4ade80" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="470" y="74" fill="#4ade80" font-size="9" font-family="Inter,sans-serif">Target</text>
  <line x1="290" y1="140" x2="460" y2="140" stroke="#5b7cfa" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="470" y="144" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif">BE</text>
  <line x1="290" y1="200" x2="460" y2="200" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="4,3"/>
  <polyline points="290,180 330,150 370,160 410,130 440,120" fill="none" stroke="#e6e9ef" stroke-width="2"/>
  <circle cx="440" cy="120" r="5" fill="#f97316"/>
  <text x="440" y="105" fill="#f97316" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Stopped at BE</text>

  <!-- Trailing stop -->
  <text x="125" y="250" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">TRAILING STOP</text>
  <polyline points="40,290 80,270 120,280 160,240 200,220 210,200" fill="none" stroke="#e6e9ef" stroke-width="2"/>
  <line x1="40" y1="320" x2="100" y2="310" stroke="#ef4444" stroke-width="1" stroke-dasharray="3,2"/>
  <line x1="100" y1="310" x2="160" y2="280" stroke="#ef4444" stroke-width="1" stroke-dasharray="3,2"/>
  <line x1="160" y1="280" x2="210" y2="250" stroke="#ef4444" stroke-width="1" stroke-dasharray="3,2"/>
  <text x="220" y="320" fill="#ef4444" font-size="9" font-family="Inter,sans-serif">Trailing stop</text>

  <!-- Partial profits -->
  <text x="375" y="250" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">PARTIAL PROFITS</text>
  <polyline points="290,290 330,270 370,240 410,220 440,180" fill="none" stroke="#e6e9ef" stroke-width="2"/>
  <circle cx="370" cy="240" r="5" fill="#4ade80"/>
  <text x="370" y="230" fill="#4ade80" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">+1R (close 50%)</text>
  <circle cx="440" cy="180" r="5" fill="#4ade80"/>
  <text x="440" y="170" fill="#4ade80" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">+2R (close rest)</text>
</svg>

<h3>Combining approaches</h3>
<p>The most effective approach combines multiple methods:</p>
<ol>
    <li><strong>Structural stop</strong> — for logical stop placement.</li>
    <li><strong>Partial profit at 1R</strong> — locks in some gains.</li>
    <li><strong>Trailing stop for the rest</strong> — captures large moves.</li>
    <li><strong>Time exit</strong> — for stale trades that don't progress.</li>
</ol>
<p>This hybrid approach balances capital preservation with profit maximisation.</p>

<h3>Setting R:R expectations</h3>
<p>Minimum R:R depends on strategy win rate:</p>
<table>
    <thead><tr><th>Win Rate</th><th>Minimum R:R</th><th>Comment</th></tr></thead>
    <tbody>
        <tr><td>35%</td><td>2:1</td><td>Trend following typical</td></tr>
        <tr><td>40%</td><td>1.75:1</td><td>Balanced</td></tr>
        <tr><td>45%</td><td>1.5:1</td><td>Balanced</td></tr>
        <tr><td>50%</td><td>1.25:1</td><td>Moderate</td></tr>
        <tr><td>55%</td><td>1:1</td><td>Range trading typical</td></tr>
        <tr><td>60%</td><td>0.9:1</td><td>High win rate</td></tr>
    </tbody>
</table>
<p>Higher win rate allows lower R:R. Lower win rate requires higher R:R. The product determines expectancy.</p>

<h3>Exit rule errors</h3>
<ul>
    <li><strong>No stop-loss.</strong> The most dangerous error. Every trade must have a predefined stop.</li>
    <li><strong>Moving the stop further away.</strong> Once the stop is set, it should only move in the trade's favour.</li>
    <li><strong>Taking profits too early.</strong> Fear causes premature exits. This kills payoff ratios.</li>
    <li><strong>Not taking profits at all.</strong> Greed causes traders to hold for "one more pip," giving back profits.</li>
    <li><strong>Changing management mid-trade.</strong> The plan was made before entry. Stick to it.</li>
    <li><strong>Not accounting for costs.</strong> Spread and commission reduce net R:R.</li>
    <li><strong>Inconsistency.</strong> Same setup should produce same exit strategy every time.</li>
</ul>

<h3>Worked example — Exit rules</h3>
<p>For a swing trading strategy on H4:</p>

<h4>Stop-loss rules</h4>
<ul>
    <li>Place stop just beyond the swing low (longs) or swing high (shorts), with a 10-pip buffer.</li>
    <li>Use the wider of: structural stop or 1.5× ATR.</li>
</ul>

<h4>Take-profit rules</h4>
<ul>
    <li>First target: 1R (partial close of 50%).</li>
    <li>Second target: 2R (partial close of 25%).</li>
    <li>Remaining 25% trailed below each new higher low.</li>
</ul>

<h4>Move to break-even</h4>
<p>After price moves 1R in favour, move stop to entry.</p>

<h4>Time-based exit</h4>
<p>If the trade hasn't moved 0.5R in 5 bars, close at market.</p>

<p>This rule set balances capital protection with profit maximisation. It handles all possible outcomes systematically.</p>

<h2>Factual context</h2>
<p>The importance of exit rules is emphasised throughout trading literature:</p>
<p><strong>Ed Seykota</strong> — his first trading rule is "cut losses." Exit rules begin with stops.</p>
<p><strong>Larry Hite</strong> — insists that cutting losses is the primary discipline. Stops are non-negotiable.</p>
<p><strong>Paul Tudor Jones</strong> — famously emphasises protecting capital over making profits. Exits protect capital.</p>
<p><strong>Van Tharp</strong> — describes exits as the most important component of a trading system. Without them, gains cannot be locked in.</p>
<p>Ed Seykota, on exits:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Rules 1 and 2 are exit rules. Cutting losses limits damage. Riding winners captures profits.</p>
<p>Paul Tudor Jones, on protecting capital:</p>
<blockquote><strong>\"I'm always thinking about losing money as opposed to making money. Don't focus on making money, focus on protecting what you have.\"</strong></blockquote>
<p>Jones' point: exits that protect capital are more important than entries that generate profits.</p>
<p>Bruce Kovner, on stop discipline:</p>
<blockquote><strong>\"I know where I'm getting out before I get in. If I can't define the risk, I don't take the trade.\"</strong></blockquote>
<p>Kovner's discipline is the model. Stops are defined before entries.</p>
<p>Larry Hite, on the importance of cutting losses:</p>
<blockquote><strong>\"I have two basic rules about winning in trading as well as in life: (1) If you don't bet, you can't win. (2) If you lose all your chips, you can't bet.\"</strong></blockquote>
<p>Hite's second rule is enforced by stop-losses. They prevent catastrophic losses that eliminate future opportunities.</p>
<p>Jesse Livermore, on cut losses:</p>
<blockquote><strong>\"The market does not beat them. They beat themselves, because though they have brains they cannot sit tight.\"</strong></blockquote>
<p>Livermore's observation applies directly to exits. Traders who can't cut losses lose. Those who can survive.</p>
<p>Mark Douglas, on the psychological dimension of exits:</p>
<blockquote><strong>\"The best traders are not afraid. They are not afraid of being wrong, losing money, or missing out.\"</strong></blockquote>
<p>Douglas' point: emotional detachment from exits is essential. Stops are not failures — they are business expenses.</p>
<p>Warren Buffett, on the same theme:</p>
<blockquote><strong>\"Rule No. 1: Never lose money. Rule No. 2: Never forget Rule No. 1.\"</strong></blockquote>
<p>Buffett's rules apply to exits. Cutting losses quickly is the primary mechanism for avoiding large losses.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>No stop-loss.</strong> The single most dangerous mistake.</li>
    <li><strong>Moving stops away from price.</strong> This turns small losses into large ones.</li>
    <li><strong>Taking profits too early.</strong> Fear kills payoff ratios.</li>
    <li><strong>Holding past targets.</strong> Greed gives back profits.</li>
    <li><strong>Inconsistent management.</strong> Same setup should always be managed the same way.</li>
    <li><strong>Not accounting for spread.</strong> Net R:R is what matters, not gross.</li>
    <li><strong>Exit rules that don't match entry rules.</strong> If entries are on H4 pullbacks, exits should be structural, not arbitrary pip targets.</li>
    <li><strong>Not testing exit rules.</strong> Exits should be backtested just like entries.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use sophisticated exit techniques:</p>
<ul>
    <li><strong>Dynamic stops based on structure</strong> — trailing stops below each new higher low.</li>
    <li><strong>ATR-based trailing stops</strong> — trailing by 2× ATR from the highest point.</li>
    <li><strong>Time-based partial exits</strong> — closing portions of a position at specific times.</li>
    <li><strong>Volatility-adjusted targets</strong> — wider targets in volatile markets.</li>
    <li><strong>Correlation-based exits</strong> — exiting when correlated markets reverse.</li>
    <li><strong>News-based exits</strong> — closing positions before major events.</li>
    <li><strong>Maximum holding time</strong> — exiting positions held too long regardless of outcome.</li>
</ul>
<p>The most important insight: exits determine the R-multiple of every trade. A trade that could have been +3R but is closed at +1R is a trade where the exit rule was suboptimal. Conversely, holding a losing trade for -3R when the stop should have been at -1R is a rule violation.</p>
<p>Consistent exits produce consistent results. The exact rules matter less than applying them consistently. A mediocre exit rule followed rigorously beats an excellent exit rule followed inconsistently.</p>
<p>With entry and exit rules defined, the next step is position sizing — how much to risk per trade. This is covered in the next lesson.</p>
HTML,
        ],

        [
            'slug'   => 'position-sizing-and-risk-management',
            'title'  => 'Position Sizing and Risk Management',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Choose risk per trade\n" .
                "• Calculate position size accurately\n" .
                "• Set portfolio heat limits\n" .
                "• Define daily and weekly loss limits",
            'prerequisites' => 'Defining Exit Rules',
            'sort_order' => 6,
            'summary' => 'Position sizing determines how much capital is at risk on each trade. It is the most important risk management decision in any strategy. This lesson covers risk per trade, position sizing calculation, portfolio heat, and loss limits.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine playing poker without knowing the bet size. You can't calculate expected value. You can't manage your bankroll. Position sizing in trading is the equivalent of knowing your bet size — it determines everything about risk and reward.</p>

<h2>Real-world analogy</h2>
<p>Think of a restaurant determining portion sizes. Too large and customers waste food (and the restaurant loses money). Too small and customers feel cheated. The right portion creates value for everyone. Position sizing is the portion size of trading.</p>

<h2>Professional explanation</h2>

<h3>Risk per trade</h3>
<p>The primary decision in risk management is: what percentage of your account do you risk per trade?</p>

<h4>Standard risk levels</h4>
<table>
    <thead><tr><th>Risk %</th><th>Profile</th><th>Notes</th></tr></thead>
    <tbody>
        <tr><td>0.25–0.5%</td><td>Conservative</td><td>Very safe, slower growth</td></tr>
        <tr><td>1%</td><td>Standard</td><td>Most professional traders</td></tr>
        <tr><td>1.5%</td><td>Moderate-aggressive</td><td>Experienced traders</td></tr>
        <tr><td>2%</td><td>Aggressive</td><td>Maximum recommended</td></tr>
        <tr><td>Above 2%</td><td>Not recommended</td><td>Ruin risk becomes significant</td></tr>
    </tbody>
</table>
<p>For most traders, 1% per trade is optimal. It allows compounding, survives realistic losing streaks, and keeps risk of ruin near zero.</p>

<h3>Position size calculation</h3>
<p>The standard formula:</p>
<p><code>Position size = (Account × Risk %) / (Stop pips × Pip value)</code></p>

<h4>Worked example</h4>
<ul>
    <li><strong>Account:</strong> $10,000</li>
    <li><strong>Risk per trade:</strong> 1% = $100</li>
    <li><strong>Stop distance:</strong> 40 pips</li>
    <li><strong>Pip value per standard lot:</strong> $10</li>
</ul>
<p>Position size = $100 / (40 × $10) = 0.25 standard lots.</p>
<p>If the stop is hit, the loss is $100 (1% of account).</p>

<h3>Visual reference — Position sizing flow</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Steps -->
  <rect x="30" y="30" width="100" height="50" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="80" y="52" fill="#5b7cfa" font-size="10" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Account</text>
  <text x="80" y="68" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">$10,000</text>

  <text x="150" y="60" fill="#8b93a7" font-size="14" font-family="Inter,sans-serif">→</text>

  <rect x="170" y="30" width="100" height="50" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="220" y="52" fill="#5b7cfa" font-size="10" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Risk %</text>
  <text x="220" y="68" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">1% = $100</text>

  <text x="290" y="60" fill="#8b93a7" font-size="14" font-family="Inter,sans-serif">→</text>

  <rect x="310" y="30" width="100" height="50" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="360" y="52" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Stop distance</text>
  <text x="360" y="68" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">40 pips</text>

  <!-- Arrow down -->
  <line x1="250" y1="80" x2="250" y2="130" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,130 245,120 255,120" fill="#8b93a7"/>

  <!-- Formula -->
  <rect x="130" y="140" width="240" height="35" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="250" y="163" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">$100 / (40 × $10) = 0.25 lots</text>

  <!-- Arrow down -->
  <line x1="250" y1="175" x2="250" y2="210" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,210 245,200 255,200" fill="#8b93a7"/>

  <!-- Result -->
  <rect x="150" y="220" width="200" height="35" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="250" y="243" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Position size: 0.25 lots</text>
</svg>

<h3>Portfolio heat</h3>
<p>Portfolio heat is the total risk across all open positions. If you have three trades each risking 1%, your portfolio heat is 3%.</p>

<h4>Heat limits</h4>
<ul>
    <li><strong>Conservative:</strong> 3% maximum heat.</li>
    <li><strong>Standard:</strong> 5% maximum heat.</li>
    <li><strong>Aggressive:</strong> 6–8% maximum heat.</li>
</ul>
<p>Above 5%, a bad day can produce a significant drawdown. Heat limits prevent this.</p>

<h4>Correlation adjustment</h4>
<p>Correlated positions share risk. Three long USD pairs are effectively one larger position. Adjust heat accordingly:</p>
<ul>
    <li>Treat correlated trades as one position.</li>
    <li>Total exposure per theme (USD weakness, JPY strength, etc.) should not exceed 2%.</li>
</ul>

<h3>Visual reference — Portfolio heat limits</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Bar chart of heat -->
  <text x="250" y="25" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Portfolio Heat Management</text>

  <!-- Bars -->
  <rect x="80" y="160" width="60" height="60" fill="#4ade80" fill-opacity="0.6"/>
  <text x="110" y="150" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1%</text>
  <text x="110" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Trade 1</text>

  <rect x="160" y="160" width="60" height="60" fill="#4ade80" fill-opacity="0.6"/>
  <text x="190" y="150" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1%</text>
  <text x="190" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Trade 2</text>

  <rect x="240" y="160" width="60" height="60" fill="#f97316" fill-opacity="0.6"/>
  <text x="270" y="150" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1%</text>
  <text x="270" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Trade 3</text>

  <rect x="320" y="160" width="60" height="60" fill="#f97316" fill-opacity="0.6"/>
  <text x="350" y="150" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1%</text>
  <text x="350" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Trade 4</text>

  <rect x="400" y="160" width="60" height="60" fill="#ef4444" fill-opacity="0.6"/>
  <text x="430" y="150" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1%</text>
  <text x="430" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Trade 5</text>

  <!-- Max heat line -->
  <line x1="60" y1="160" x2="480" y2="160" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="490" y="164" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">5% Max</text>

  <!-- Total label -->
  <text x="250" y="30" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Total: 5% (at maximum)</text>
</svg>

<h3>Daily and weekly loss limits</h3>
<p>Loss limits prevent emotional spirals:</p>

<h4>Daily loss limit</h4>
<ul>
    <li><strong>Conservative:</strong> 1–2%.</li>
    <li><strong>Standard:</strong> 2–3%.</li>
    <li><strong>Aggressive:</strong> 4–5%.</li>
</ul>
<p>When the daily limit is hit, stop trading for the day.</p>

<h4>Weekly loss limit</h4>
<ul>
    <li><strong>Standard:</strong> 2× daily limit (4–6%).</li>
</ul>
<p>When the weekly limit is hit, stop trading for the week.</p>

<h4>Consecutive loss limit</h4>
<p>After 3 consecutive losses, stop trading regardless of the dollar amount. The psychological reset is more important than the loss itself.</p>

<h3>Position sizing adjustments</h3>
<p>Adjust position size based on context:</p>

<h4>Higher risk (up to 1.5×)</h4>
<ul>
    <li>Very high-quality setup with multiple confluence factors.</li>
    <li>Strong trend alignment on higher timeframe.</li>
    <li>Clear momentum.</li>
</ul>

<h4>Standard risk (1×)</h4>
<ul>
    <li>Setup meets criteria but not exceptional.</li>
    <li>Default for most trades.</li>
</ul>

<h4>Lower risk (0.5×)</h4>
<ul>
    <li>Setup meets criteria but with concerns.</li>
    <li>During high-volatility periods.</li>
    <li>During low-liquidity windows.</li>
    <li>After a losing streak.</li>
</ul>

<h4>No trade (0×)</h4>
<ul>
    <li>Setup doesn't meet criteria.</li>
    <li>Emotional state compromised.</li>
    <li>Daily/weekly limits hit.</li>
    <li>News imminent.</li>
</ul>

<h3>Drawdown management</h3>
<p>When the account enters drawdown, reduce risk:</p>
<table>
    <thead><tr><th>Drawdown</th><th>Action</th></tr></thead>
    <tbody>
        <tr><td>0–5%</td><td>Normal risk</td></tr>
        <tr><td>5–10%</td><td>Reduce risk by 50%</td></tr>
        <tr><td>10–15%</td><td>Stop trading for 1 week</td></tr>
        <tr><td>15–20%</td><td>Full review, resume at half risk</td></tr>
        <tr><td>20%+</td><td>Stop trading, deep review</td></tr>
    </tbody>
</table>
<p>These thresholds prevent deep drawdowns from spiraling. Reducing risk in drawdown is counterintuitive but essential.</p>

<h3>Worked example — Complete position sizing</h3>
<p>For a swing trader with $20,000 account:</p>

<h4>Standard trade</h4>
<ul>
    <li>Risk: 1% = $200</li>
    <li>Stop: 50 pips</li>
    <li>Pip value: $10 per standard lot</li>
    <li>Position size: $200 / (50 × $10) = 0.40 lots</li>
</ul>

<h4>If heat is already 3%</h4>
<ul>
    <li>New trade would bring heat to 4%.</li>
    <li>Within the 5% max, so take the trade at standard size.</li>
</ul>

<h4>If heat is already 4.5%</h4>
<ul>
    <li>New trade would bring heat to 5.5%.</li>
    <li>Exceeds 5% max. Either reduce size (to 0.20 lots) or wait.</li>
</ul>

<h4>If daily loss is 2%</h4>
<ul>
    <li>Approaching the 3% daily limit.</li>
    <li>Reduce risk to 0.5% for the next trade.</li>
    <li>If the next trade loses, hit the daily limit and stop.</li>
</ul>

<p>This framework ensures risk is always controlled.</p>

<h2>Factual context</h2>
<p>The importance of position sizing is well documented:</p>
<p><strong>Van Tharp</strong> — research shows that position sizing accounts for 90%+ of performance variation between traders using the same entry system.</p>
<p><strong>Ralph Vince</strong> — his work on optimal f demonstrated that position sizing determines long-term growth rate and risk of ruin.</p>
<p><strong>Ed Thorp</strong> — his success with blackjack and hedge funds was based on mathematical position sizing.</p>
<p><strong>The Turtle Traders</strong> — used volatility-based position sizing (1% risk adjusted for volatility) as the core of their risk management.</p>
<p>Ed Seykota, on position sizing:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Rule 3 — keep bets small — is the essence of position sizing.</p>
<p>Larry Hite, on the mathematics of survival:</p>
<blockquote><strong>\"I have two basic rules about winning in trading as well as in life: (1) If you don't bet, you can't win. (2) If you lose all your chips, you can't bet.\"</strong></blockquote>
<p>Hite's second rule is enforced by position sizing. Small positions prevent the loss of all chips.</p>
<p>Paul Tudor Jones, on risk control:</p>
<blockquote><strong>\"Risk control is the most important thing in trading. If you have a losing position that is making you uncomfortable, the solution is very simple: get out.\"</strong></blockquote>
<p>Jones' point assumes proper position sizing. If a position feels uncomfortable, it's too large.</p>
<p>Bruce Kovner, on the same theme:</p>
<blockquote><strong>\"I try to keep my bets small enough that I can be wrong many times in a row without being forced to change my approach.\"</strong></blockquote>
<p>Kovner's discipline is the practical application of position sizing. Small bets = survival.</p>
<p>Warren Buffett, on protecting capital:</p>
<blockquote><strong>\"Rule No. 1: Never lose money. Rule No. 2: Never forget Rule No. 1.\"</strong></blockquote>
<p>Buffett's rule is enforced by position sizing. Small risk per trade prevents significant capital loss.</p>
<p>Jesse Livermore, on the cost of oversizing:</p>
<blockquote><strong>\"The market does not beat them. They beat themselves.\"</strong></blockquote>
<p>Livermore's observation applies to position sizing. Oversizing is a self-inflicted wound.</p>
<p>Mark Douglas, on the psychological benefit of proper sizing:</p>
<blockquote><strong>\"The best traders are not afraid. They are not afraid of losing money.\"</strong></blockquote>
<p>Douglas' point: proper position sizing removes the fear of loss. Fear comes from positions that are too large.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Fixed lot sizes.</strong> Trading the same lot size regardless of account or stop distance.</li>
    <li><strong>Risking too much per trade.</strong> Above 2% per trade, risk of ruin becomes material.</li>
    <li><strong>Ignoring portfolio heat.</strong> Opening multiple trades without checking total exposure.</li>
    <li><strong>Not adjusting for correlation.</strong> Correlated trades are effectively one larger position.</li>
    <li><strong>Increasing size after losses.</strong> The worst possible response to a losing streak.</li>
    <li><strong>Decreasing size too much after losses.</strong> Prevents recovery. Reduce modestly, not dramatically.</li>
    <li><strong>Not having daily/weekly limits.</strong> Without limits, losses compound unchecked.</li>
    <li><strong>Ignoring drawdown thresholds.</strong> Continuing full risk in drawdown is dangerous.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use advanced position sizing techniques:</p>
<ul>
    <li><strong>Kelly-optimal sizing</strong> — mathematical optimal based on win rate and payoff.</li>
    <li><strong>Fractional Kelly</strong> — 1/10 to 1/4 of full Kelly, more practical.</li>
    <li><strong>Volatility-adjusted sizing</strong> — scaling by ATR for consistent risk across conditions.</li>
    <li><strong>Dynamic sizing based on expectancy</strong> — larger positions on higher-expectancy setups.</li>
    <li><strong>Correlation-weighted sizing</strong> — adjusting for portfolio-level risk.</li>
</ul>
<p>But for most retail traders, standard fixed fractional sizing (1% per trade, 5% max heat) is optimal. Complex sizing techniques add marginal value and increase the risk of errors.</p>
<p>The most important insight: position sizing is not optional. Without it, no strategy works. With it, even simple strategies become profitable.</p>
<p>With position sizing defined, the final component of strategy design is filters — the conditions under which trading should not occur. This is covered in the next lesson.</p>
HTML,
        ],

        [
            'slug'   => 'filters-and-no-trade-conditions',
            'title'  => 'Filters and No-Trade Conditions',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define filters that improve strategy performance\n" .
                "• Identify no-trade conditions\n" .
                "• Build checklists for filtering trades\n" .
                "• Recognise when to stand aside",
            'prerequisites' => 'Position Sizing and Risk Management',
            'sort_order' => 7,
            'summary' => 'Filters remove low-quality trades from a strategy. No-trade conditions define when trading is prohibited entirely. Together, they improve expectancy by avoiding trades where the edge is absent or negative. This lesson teaches how to design effective filters.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a restaurant that only serves customers who meet certain criteria — they must arrive before 9 PM, wear appropriate attire, and not be overly intoxicated. These filters protect the restaurant's atmosphere and margins. Trading filters work the same way — they protect the strategy's edge.</p>
<p>No-trade conditions are the strongest filters. They say: under these conditions, don't trade at all.</p>

<h2>Real-world analogy</h2>
<p>Think of a pilot's go/no-go checklist. Before takeoff, the pilot evaluates weather, aircraft condition, and crew readiness. If any condition fails, the flight is grounded. Trading filters work the same way.</p>

<h2>Professional explanation</h2>

<h3>What are filters?</h3>
<p>Filters are additional criteria applied to setups before entry. They don't change the setup itself — they determine which setups are taken and which are skipped.</p>

<h3>Categories of filters</h3>

<h4>1. Market state filters</h4>
<p>Only trade when the market is in a state that suits your strategy:</p>
<ul>
    <li><strong>Trending markets</strong> — for trend-following and pullback strategies.</li>
    <li><strong>Ranging markets</strong> — for range trading and mean reversion.</li>
    <li><strong>Compression</strong> — prepare for breakout; don't trade yet.</li>
    <li><strong>Volatile/choppy</strong> — reduce size or stand aside.</li>
</ul>
<p>Identify market state using:
</p>
<ul>
    <li>ADX (above 25 = trending, below 20 = ranging).</li>
    <li>Structure (HH/HL, LH/LL, or oscillating).</li>
    <li>Bollinger Band width (narrow = compression).</li>
</ul>

<h4>2. Volatility filters</h4>
<p>Adjust to current volatility:</p>
<ul>
    <li><strong>Low volatility</strong> — smaller targets, tighter stops.</li>
    <li><strong>Normal volatility</strong> — standard approach.</li>
    <li><strong>Extreme volatility</strong> — reduce size or skip.</li>
</ul>
<p>Use ATR to measure volatility. Compare current ATR to its 20-period average.</p>

<h4>3. Session filters</h4>
<p>Trade only during active windows:</p>
<ul>
    <li><strong>London kill zone</strong> — 07:00–10:00 UTC.</li>
    <li><strong>NY kill zone</strong> — 12:00–15:00 UTC.</li>
    <li><strong>Avoid:</strong> Asian session for European pairs; late NY for everything.</li>
</ul>
<p>Session filters improve reliability because institutional activity is concentrated.</p>

<h4>4. News filters</h4>
<p>Avoid trading around major news events:</p>
<ul>
    <li><strong>30 minutes before</strong> — no new trades.</li>
    <li><strong>30 minutes after</strong> — wait for the dust to settle.</li>
    <li><strong>High-impact events</strong> — CPI, NFP, FOMC, central bank meetings.</li>
</ul>
<p>News filters prevent getting caught in whipsaws.</p>

<h4>5. Correlation filters</h4>
<p>Avoid trades that conflict with correlated markets:</p>
<ul>
    <li>If long EUR/USD, don't short GBP/USD (they're correlated).</li>
    <li>If long USD/CHF, don't long EUR/USD (they're inversely correlated).</li>
    <li>Check correlation matrices before opening positions.</li>
</ul>

<h4>6. Technical filters</h4>
<p>Additional technical conditions that improve setups:</p>
<ul>
    <li><strong>Volume confirmation</strong> — breakouts with volume are more reliable.</li>
    <li><strong>Momentum alignment</strong> — RSI or MACD confirming direction.</li>
    <li><strong>Structure alignment</strong> — multiple timeframes agreeing.</li>
    <li><strong>Level confluence</strong> — setup at multiple technical factors.</li>
</ul>

<h3>Visual reference — Filter types</h3>
<svg viewBox="0 0 500 320" width="500" height="320" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Setup -->
  <rect x="200" y="20" width="100" height="40" fill="#5b7cfa" fill-opacity="0.2" stroke="#5b7cfa" stroke-width="2" rx="6"/>
  <text x="250" y="45" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">SETUP</text>

  <!-- Arrow down -->
  <line x1="250" y1="60" x2="250" y2="90" stroke="#8b93a7" stroke-width="2"/>

  <!-- Filters layer 1 -->
  <rect x="50" y="90" width="400" height="35" fill="#f97316" fill-opacity="0.1" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="250" y="113" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Market State + Volatility Filters</text>

  <!-- Arrow down -->
  <line x1="250" y1="125" x2="250" y2="155" stroke="#8b93a7" stroke-width="2"/>

  <!-- Filters layer 2 -->
  <rect x="50" y="155" width="400" height="35" fill="#eab308" fill-opacity="0.1" stroke="#eab308" stroke-width="1.5" rx="6"/>
  <text x="250" y="178" fill="#eab308" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Session + News Filters</text>

  <!-- Arrow down -->
  <line x1="250" y1="190" x2="250" y2="220" stroke="#8b93a7" stroke-width="2"/>

  <!-- Filters layer 3 -->
  <rect x="50" y="220" width="400" height="35" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="250" y="243" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Correlation + Technical Filters</text>

  <!-- Arrow down -->
  <line x1="250" y1="255" x2="250" y2="285" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,285 245,275 255,275" fill="#8b93a7"/>

  <!-- Result -->
  <rect x="180" y="285" width="140" height="30" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="250" y="305" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">EXECUTE OR SKIP</text>
</svg>

<h3>No-trade conditions</h3>
<p>No-trade conditions are situations where trading is prohibited entirely, regardless of the setup. These override all other rules.</p>

<h4>1. Daily loss limit hit</h4>
<p>If the daily loss limit is reached (typically 2–3%), no more trades today. Wait for tomorrow.</p>

<h4>2. Weekly loss limit hit</h4>
<p>If the weekly loss limit is reached (typically 4–6%), no more trades this week. Wait for next week.</p>

<h4>3. Consecutive loss streak</h4>
<p>After 3 consecutive losses, stop for the day. Psychological reset is essential.</p>

<h4>4. Emotional state compromised</h4>
<p>If you're tired, angry, euphoric, distracted, or physically unwell, don't trade. Emotionally compromised traders make bad decisions.</p>

<h4>5. Major news pending</h4>
<p>Within 30 minutes of a high-impact news release, no new trades. Existing trades may be managed.</p>

<h4>6. Market state unclear</h4>
<p>If you can't identify whether the market is trending or ranging, don't trade. Ambiguity is a no-trade condition.</p>

<h4>7. Daily/weekly loss limit not yet defined</h4>
<p>If you haven't calculated your loss limits for the day, don't trade. Know your numbers before starting.</p>

<h4>8. Technical platform issues</h4>
<p>If your internet is unstable or your platform is malfunctioning, don't trade. Technical issues cause bad fills and missed stops.</p>

<h4>9. Personal life stress</h4>
<p>Divorce, illness, financial strain, or other major life events impair trading judgment. Trade smaller or not at all during these periods.</p>

<h4>10. No clear setup</h4>
<p>If no setup meets your criteria, don't force a trade. Waiting is a valid decision.</p>

<h3>Visual reference — No-trade conditions</h3>
<svg viewBox="0 0 500 340" width="500" height="340" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Warning symbol -->
  <polygon points="250,20 320,140 180,140" fill="#ef4444" fill-opacity="0.2" stroke="#ef4444" stroke-width="2"/>
  <text x="250" y="110" fill="#ef4444" font-size="30" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">!</text>
  <text x="250" y="160" fill="#ef4444" font-size="14" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">STOP TRADING IF:</text>

  <!-- Items -->
  <rect x="40" y="180" width="200" height="30" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1" rx="4"/>
  <text x="55" y="200" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Daily loss limit hit</text>

  <rect x="260" y="180" width="200" height="30" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1" rx="4"/>
  <text x="275" y="200" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Weekly loss limit hit</text>

  <rect x="40" y="220" width="200" height="30" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1" rx="4"/>
  <text x="55" y="240" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">3 consecutive losses</text>

  <rect x="260" y="220" width="200" height="30" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1" rx="4"/>
  <text x="275" y="240" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Emotional state compromised</text>

  <rect x="40" y="260" width="200" height="30" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1" rx="4"/>
  <text x="55" y="280" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Major news imminent</text>

  <rect x="260" y="260" width="200" height="30" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1" rx="4"/>
  <text x="275" y="280" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Market state unclear</text>

  <rect x="40" y="300" width="200" height="30" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1" rx="4"/>
  <text x="55" y="320" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Technical issues</text>

  <rect x="260" y="300" width="200" height="30" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1" rx="4"/>
  <text x="275" y="320" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Personal life stress</text>
</svg>

<h3>Why filters matter</h3>
<p>Filters improve expectancy by removing low-quality trades. Without filters:</p>
<ul>
    <li>Trades are taken in poor market conditions.</li>
    <li>Trades are taken during low-liquidity windows.</li>
    <li>Trades are taken before major events.</li>
    <li>Trades conflict with correlated markets.</li>
</ul>
<p>Each of these reduces the probability of success. Filters remove them.</p>
<p>Studies on trading systems consistently show that adding filters improves performance — as long as the filters don't eliminate too many trades. A balance is required.</p>

<h3>Balancing filters</h3>
<p>Too few filters → too many low-quality trades.
Too many filters → too few trades to be statistically meaningful.
The right balance depends on the strategy. In general:</p>
<ul>
    <li><strong>1–2 filters</strong> — most strategies benefit from this.</li>
    <li><strong>3–4 filters</strong> — for very selective strategies.</li>
    <li><strong>5+ filters</strong> — usually produces too few trades.</li>
</ul>

<h3>Building a filter checklist</h3>
<p>For every trade, run this checklist:</p>
<ol>
    <li>Is the market state appropriate for my strategy? (trend, range, compression)</li>
    <li>Is current volatility acceptable? (ATR check)</li>
    <li>Am I within a valid trading session? (kill zone check)</li>
    <li>Is there a major news event within 30 minutes? (calendar check)</li>
    <li>Does this trade conflict with any correlated open positions?</li>
    <li>Is my emotional state neutral? (self-check)</li>
    <li>Have I hit my daily or weekly loss limit?</li>
    <li>Is the setup actually meeting my criteria? (not just close)</li>
</ol>
<p>If any answer is "no," skip the trade.</p>

<h3>Worked example — Filters in action</h3>
<p>A swing trader is considering a long EUR/USD trade at a pullback setup.</p>

<h4>Setup analysis</h4>
<ul>
    <li>Daily trend: bullish (HH/HL).</li>
    <li>H4 pullback to 50 MA with bullish engulfing.</li>
    <li>Looks like a valid setup.</li>
</ul>

<h4>Filter checks</h4>
<ul>
    <li><strong>Market state:</strong> ADX at 28 — trending. ✓</li>
    <li><strong>Volatility:</strong> ATR at 1.2× average. Normal. ✓</li>
    <li><strong>Session:</strong> London kill zone. ✓</li>
    <li><strong>News:</strong> US CPI in 2 hours. Not within 30 minutes. ✓</li>
    <li><strong>Correlation:</strong> No existing GBP/USD position. ✓</li>
    <li><strong>Emotional state:</strong> Neutral. ✓</li>
    <li><strong>Daily loss limit:</strong> Zero loss so far today. ✓</li>
    <li><strong>Setup meets criteria:</strong> Yes. ✓</li>
</ul>
<p>All filters pass. Take the trade.</p>

<p>Now imagine the same setup with two hours until CPI:</p>
<ul>
    <li><strong>News filter:</strong> CPI in 2 hours. ✓ (Not imminent, but approaching.)</li>
</ul>
<p>Some traders would take the trade with a plan to exit before CPI. Others would skip entirely. The rule must be pre-defined.</p>

<h3>Adjusting filters over time</h3>
<p>Filters should be reviewed and refined like other rules:</p>
<ol>
    <li><strong>Start with the core filters</strong> — market state, session, news.</li>
    <li><strong>Track performance</strong> — which filters improve expectancy, which don't.</li>
    <li><strong>Adjust after 100+ trades</strong> — not after a few losses.</li>
    <li><strong>Add filters when performance drops</strong> — identify the cause and filter it out.</li>
    <li><strong>Remove filters that don't help</strong> — unnecessary filters reduce trade frequency without improving quality.</li>
</ol>

<h3>Interaction with strategy</h3>
<p>Filters should be designed alongside the strategy, not added as an afterthought. A filter that conflicts with the strategy's logic produces inconsistent results.</p>
<ul>
    <li>Trend strategy + trend filter = aligned.</li>
    <li>Trend strategy + range filter = conflicting.</li>
    <li>Range strategy + session filter = aligned (ranges often occur during quiet sessions).</li>
</ul>
<p>Filters that align with the strategy's assumptions improve performance. Filters that conflict with them reduce it.</p>

<h2>Factual context</h2>
<p>The concept of filters has been formalised in systematic trading:</p>
<p><strong>Richard Donchian</strong> — added a moving average filter to his breakout system to avoid trading against the larger trend.</p>
<p><strong>The Turtle Traders</strong> — used filters to avoid trading in choppy markets.</p>
<p><strong>Modern systematic funds</strong> — use sophisticated filters combining volatility, correlation, and regime detection.</p>
<p><strong>Al Brooks</strong> — describes filters as context: "Location is more important than the signal."</p>
<p>Ed Seykota, on the importance of selectivity:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules are simple but effective. Filters extend these rules by specifying when to apply them.</p>
<p>Paul Tudor Jones, on patience and selectivity:</p>
<blockquote><strong>\"I wait for the pitch I can hit. If it's not my pitch, I don't swing.\"</strong></blockquote>
<p>Jones' baseball metaphor captures filters. Only trade when conditions match your criteria.</p>
<p>Bruce Kovner, on the value of discipline:</p>
<blockquote><strong>\"I have my setups. When they appear, I trade. When they don't, I don't. There's no middle ground.\"</strong></blockquote>
<p>Kovner's clarity is the model. Filters enforce this discipline.</p>
<p>Larry Hite, on avoiding bad trades:</p>
<blockquote><strong>\"The trades you don't take are as important as the trades you do take.\"</strong></blockquote>
<p>Hite's point is the essence of filters. Skipping low-quality trades improves overall performance.</p>
<p>Warren Buffett, on selectivity:</p>
<blockquote><strong>\"The stock market is a device for transferring money from the impatient to the patient.\"</strong></blockquote>
<p>Buffett's patience is a form of filtering. Wait for the right opportunities.</p>
<p>Jesse Livermore, on the same theme:</p>
<blockquote><strong>\"There is a time to go long, a time to go short, and a time to go fishing.\"</strong></blockquote>
<p>Livermore's "time to go fishing" is a filter. When conditions don't match, don't trade.</p>
<p>Mark Douglas, on the psychological benefit of filters:</p>
<blockquote><strong>\"The market is not the enemy. Your own mind is. Filters protect you from yourself.\"</strong></blockquote>
<p>Douglas' observation captures the deeper purpose of filters. They remove bad trades before they happen.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Too few filters.</strong> Trading every setup produces whipsaws.</li>
    <li><strong>Too many filters.</strong> Overly restrictive filters eliminate too many trades.</li>
    <li><strong>Filters that conflict with the strategy.</strong> Trend strategy + range filter is a contradiction.</li>
    <li><strong>Ignoring filters when eager.</strong> FOMO causes traders to skip filters. The result is bad trades.</li>
    <li><strong>Not tracking filter performance.</strong> Without data, filters can't be evaluated.</li>
    <li><strong>Adding filters reactively.</strong> Adding a new filter after every loss creates overfitting.</li>
    <li><strong>Not having no-trade conditions.</strong> The most important filters are the ones that say "don't trade at all."</li>
    <li><strong>Abandoning filters after a winning streak.</strong> Winning streaks tempt traders to skip filters. This is a mistake.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use advanced filtering techniques:</p>
<ul>
    <li><strong>Regime detection algorithms</strong> — automatically identify market states.</li>
    <li><strong>Volatility filters</strong> — dynamically adjust based on VIX or ATR.</li>
    <li><strong>Correlation matrices</strong> — real-time monitoring of portfolio correlations.</li>
    <li><strong>Sentiment filters</strong> — avoiding trades against extreme sentiment.</li>
    <li><strong>Calendar integration</strong> — automated news awareness.</li>
    <li><strong>Time-of-day filters</strong> — restricting trades to specific windows.</li>
</ul>
<p>For retail traders, simpler filters are sufficient:</p>
<ol>
    <li>Trade only in appropriate market states.</li>
    <li>Trade only during kill zones.</li>
    <li>Avoid major news windows.</li>
    <li>Respect daily and weekly loss limits.</li>
    <li>Don't trade when emotional.</li>
</ol>
<p>The most important filter is the emotional one. If you're not in the right mental state, no technical filter will save you.</p>
<p>With filters and no-trade conditions defined, the strategy has all its components. The next lesson covers documentation — how to write the strategy down so you can follow it consistently.</p>
HTML,
        ],

        [
            'slug'   => 'documenting-your-strategy',
            'title'  => 'Documenting Your Strategy',
            'difficulty' => 'professional',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Understand why documentation matters\n" .
                "• Write a complete trading plan\n" .
                "• Create actionable checklists\n" .
                "• Build a living document",
            'prerequisites' => 'Filters and No-Trade Conditions',
            'sort_order' => 8,
            'summary' => 'A strategy that exists only in your head is not a strategy — it is a collection of intentions. Documentation transforms intentions into rules. This lesson teaches you to write a complete trading plan that you can actually follow, review, and refine.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a pilot flying without a checklist. They might remember most things, but under pressure, they'll forget some. A written checklist ensures nothing is missed. Trading documentation serves the same purpose — it ensures your strategy is applied consistently under pressure.</p>

<h2>Real-world analogy</h2>
<p>Think of a recipe in a cookbook. A written recipe lets any cook produce the same dish. An unwritten family recipe depends on one person's memory. Trading strategies work the same way — written versions are usable by anyone (including future you); unwritten versions are fragile.</p>

<h2>Professional explanation</h2>

<h3>Why documentation matters</h3>
<p>Five reasons:</p>
<ol>
    <li><strong>Consistency.</strong> Written rules produce consistent execution. Unwritten rules drift over time.</li>
    <li><strong>Accountability.</strong> You can review past decisions against written rules. Without documentation, review is subjective.</li>
    <li><strong>Memory.</strong> You will forget details. Documentation preserves them.</li>
    <li><strong>Evolution.</strong> You can refine a written strategy based on data. An unwritten strategy is difficult to improve.</li>
    <li><strong>Psychology.</strong> Written rules are easier to follow under pressure. They remove in-the-moment decisions.</li>
</ol>

<h3>The complete trading plan</h3>
<p>A complete trading plan has ten sections:</p>

<h4>1. Mission and objectives</h4>
<ul>
    <li>Why do you trade?</li>
    <li>What are your return targets?</li>
    <li>What is your maximum acceptable drawdown?</li>
    <li>What is your time commitment?</li>
    <li>What is your capital base?</li>
</ul>

<h4>2. Markets and timeframes</h4>
<ul>
    <li>Which markets do you trade?</li>
    <li>Which timeframes for bias, setup, and entry?</li>
    <li>Why these choices?</li>
</ul>

<h4>3. Trading style</h4>
<ul>
    <li>Scalping, day trading, swing trading, or position trading?</li>
    <li>How long do trades typically last?</li>
    <li>How many trades per week/month?</li>
</ul>

<h4>4. Analysis framework</h4>
<ul>
    <li>What determines your bias? (structure, trend, news)</li>
    <li>What is your setup? (pullback, breakout, reversal)</li>
    <li>What confirms entries?</li>
    <li>What invalidates setups?</li>
</ul>

<h4>5. Entry rules</h4>
<ul>
    <li>What is the exact setup condition?</li>
    <li>What is the exact trigger?</li>
    <li>When can entries occur (session, timing)?</li>
    <li>What disqualifies entries?</li>
</ul>

<h4>6. Exit rules</h4>
<ul>
    <li>Where is the stop-loss placed?</li>
    <li>Where is the take-profit placed?</li>
    <li>How is the trade managed?</li>
    <li>What are the trailing stop rules?</li>
</ul>

<h4>7. Position sizing</h4>
<ul>
    <li>Risk per trade?</li>
    <li>Maximum portfolio heat?</li>
    <li>Correlation adjustments?</li>
    <li>Adjustments for conviction?</li>
</ul>

<h4>8. Loss limits</h4>
<ul>
    <li>Daily loss limit?</li>
    <li>Weekly loss limit?</li>
    <li>Consecutive loss limit?</li>
    <li>Drawdown reduction rules?</li>
</ul>

<h4>9. Filters and no-trade conditions</h4>
<ul>
    <li>Market state filters?</li>
    <li>Session filters?</li>
    <li>News filters?</li>
    <li>Correlation filters?</li>
    <li>No-trade conditions?</li>
</ul>

<h4>10. Review process</h4>
<ul>
    <li>Daily review routine?</li>
    <li>Weekly review routine?</li>
    <li>Monthly analysis?</li>
    <li>Quarterly deep dive?</li>
</ul>

<h3>Visual reference — Trading plan document</h3>
<svg viewBox="0 0 500 400" width="500" height="400" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Document -->
  <rect x="60" y="20" width="380" height="360" fill="#f5f5f5" stroke="#8b93a7" stroke-width="1.5" rx="4"/>

  <!-- Title -->
  <text x="250" y="50" fill="#1a1a1a" font-size="14" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">TRADING PLAN</text>
  <line x1="100" y1="60" x2="400" y2="60" stroke="#8b93a7" stroke-width="1"/>

  <!-- Section 1 -->
  <text x="80" y="85" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" font-weight="600">1. MISSION &amp; OBJECTIVES</text>
  <text x="90" y="102" fill="#333" font-size="9" font-family="Inter,sans-serif">Purpose: Income supplement</text>
  <text x="90" y="116" fill="#333" font-size="9" font-family="Inter,sans-serif">Target: 20% annually</text>
  <text x="90" y="130" fill="#333" font-size="9" font-family="Inter,sans-serif">Drawdown: 15% max</text>

  <!-- Section 2 -->
  <text x="80" y="155" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" font-weight="600">2. MARKETS &amp; TIMEFRAMES</text>
  <text x="90" y="172" fill="#333" font-size="9" font-family="Inter,sans-serif">EUR/USD, GBP/USD, USD/JPY</text>
  <text x="90" y="186" fill="#333" font-size="9" font-family="Inter,sans-serif">Daily bias, H4 setup, H1 entry</text>

  <!-- Section 3 -->
  <text x="80" y="211" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" font-weight="600">3. TRADING STYLE</text>
  <text x="90" y="228" fill="#333" font-size="9" font-family="Inter,sans-serif">Swing trading, 1-3 day holds</text>

  <!-- Section 4 -->
  <text x="80" y="253" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" font-weight="600">4. ANALYSIS FRAMEWORK</text>
  <text x="90" y="270" fill="#333" font-size="9" font-family="Inter,sans-serif">Structure + liquidity + pullback</text>

  <!-- Section 5 -->
  <text x="80" y="295" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" font-weight="600">5. ENTRY RULES</text>
  <text x="90" y="312" fill="#333" font-size="9" font-family="Inter,sans-serif">Trend + pullback + engulfing</text>

  <!-- Section 6 -->
  <text x="80" y="337" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" font-weight="600">6. EXIT RULES</text>
  <text x="90" y="354" fill="#333" font-size="9" font-family="Inter,sans-serif">Structural stop, 3R target, partial at 1R</text>

  <!-- Side labels -->
  <text x="30" y="200" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" transform="rotate(-90 30 200)">Full Plan</text>
</svg>

<h3>Example trading plan template</h3>
<p>Here is a fill-in-the-blank template for your own plan:</p>

<h4>1. Mission &amp; objectives</h4>
<ul>
    <li>Purpose of trading: ____________</li>
    <li>Target annual return: ____%</li>
    <li>Maximum drawdown: ____%</li>
    <li>Time available per day: ____ hours</li>
    <li>Capital base: $____</li>
</ul>

<h4>2. Markets &amp; timeframes</h4>
<ul>
    <li>Primary market: ____________</li>
    <li>Secondary markets: ____________</li>
    <li>Bias timeframe: ____________</li>
    <li>Setup timeframe: ____________</li>
    <li>Entry timeframe: ____________</li>
</ul>

<h4>3. Trading style</h4>
<ul>
    <li>Style: ____________ (scalp/day/swing/position)</li>
    <li>Expected hold time: ____________</li>
    <li>Expected trades per month: ____</li>
</ul>

<h4>4. Analysis framework</h4>
<ul>
    <li>Bias determination: ____________</li>
    <li>Setup criteria: ____________</li>
    <li>Confirmation: ____________</li>
    <li>Invalidation: ____________</li>
</ul>

<h4>5. Entry rules</h4>
<ul>
    <li>Setup conditions: ____________</li>
    <li>Trigger event: ____________</li>
    <li>Allowed sessions: ____________</li>
    <li>Disqualifiers: ____________</li>
</ul>

<h4>6. Exit rules</h4>
<ul>
    <li>Stop placement: ____________</li>
    <li>Target 1: ____________</li>
    <li>Target 2: ____________</li>
    <li>Management: ____________</li>
</ul>

<h4>7. Position sizing</h4>
<ul>
    <li>Risk per trade: ____%</li>
    <li>Maximum portfolio heat: ____%</li>
    <li>Concentration limit per theme: ____%</li>
</ul>

<h4>8. Loss limits</h4>
<ul>
    <li>Daily loss limit: ____%</li>
    <li>Weekly loss limit: ____%</li>
    <li>Consecutive losses trigger: ____</li>
    <li>Drawdown reductions: ____________</li>
</ul>

<h4>9. Filters &amp; no-trade conditions</h4>
<ul>
    <li>Market state filter: ____________</li>
    <li>Session filter: ____________</li>
    <li>News filter: ____________</li>
    <li>No-trade conditions: ____________</li>
</ul>

<h4>10. Review process</h4>
<ul>
    <li>Daily routine: ____________</li>
    <li>Weekly review: ____________</li>
    <li>Monthly analysis: ____________</li>
    <li>Quarterly deep dive: ____________</li>
</ul>

<h3>Creating actionable checklists</h3>
<p>Documentation must be actionable. Checklists convert plans into daily actions.</p>

<h4>Pre-trade checklist</h4>
<ol>
    <li>Is the setup aligned with the higher-timeframe bias?</li>
    <li>Is the price at the correct entry zone?</li>
    <li>Is the confirmation signal present?</li>
    <li>Is the R:R at least 2:1?</li>
    <li>Is position size calculated correctly?</li>
    <li>Will portfolio heat remain within limits?</li>
    <li>Is there any conflict with correlated positions?</li>
    <li>Is the session appropriate?</li>
    <li>Is there any major news within 30 minutes?</li>
    <li>Is my emotional state neutral?</li>
</ol>
<p>All items must be checked. If any is unchecked, no trade.</p>

<h4>Post-trade checklist</h4>
<ol>
    <li>Was the entry rule followed exactly?</li>
    <li>Was the stop placed correctly?</li>
    <li>Was position size calculated per rules?</li>
    <li>Was the trade managed per plan?</li>
    <li>What was the outcome in R-multiples?</li>
    <li>Any rule violations?</li>
    <li>Any lessons learned?</li>
</ol>

<h4>Daily routine checklist</h4>
<ol>
    <li>Pre-session: review higher-timeframe bias (15 min)</li>
    <li>Pre-session: mark key levels (10 min)</li>
    <li>Pre-session: check calendar (5 min)</li>
    <li>During session: execute per rules</li>
    <li>Post-session: log trades (10 min)</li>
    <li>Post-session: review decisions (5 min)</li>
</ol>

<h3>Visual reference — Plan to execution flow</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Plan -->
  <rect x="30" y="30" width="130" height="60" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="95" y="55" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">TRADING PLAN</text>
  <text x="95" y="72" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Written document</text>

  <!-- Arrow -->
  <line x1="160" y1="60" x2="200" y2="60" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="200,60 190,55 190,65" fill="#8b93a7"/>

  <!-- Checklists -->
  <rect x="200" y="30" width="130" height="60" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="265" y="55" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">CHECKLISTS</text>
  <text x="265" y="72" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Daily actions</text>

  <!-- Arrow -->
  <line x1="330" y1="60" x2="370" y2="60" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="370,60 360,55 360,65" fill="#8b93a7"/>

  <!-- Execution -->
  <rect x="370" y="30" width="110" height="60" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="425" y="55" fill="#f97316" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">EXECUTION</text>
  <text x="425" y="72" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">In-market</text>

  <!-- Arrow down -->
  <line x1="250" y1="90" x2="250" y2="130" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,130 245,120 255,120" fill="#8b93a7"/>

  <!-- Journal -->
  <rect x="140" y="140" width="220" height="50" fill="#eab308" fill-opacity="0.15" stroke="#eab308" stroke-width="1.5" rx="6"/>
  <text x="250" y="162" fill="#eab308" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">JOURNAL &amp; REVIEW</text>
  <text x="250" y="180" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Daily logging, weekly analysis</text>

  <!-- Arrow down -->
  <line x1="250" y1="190" x2="250" y2="230" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,230 245,220 255,220" fill="#8b93a7"/>

  <!-- Refinement -->
  <rect x="140" y="240" width="220" height="50" fill="#4ade80" fill-opacity="0.25" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="250" y="262" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">REFINEMENT</text>
  <text x="250" y="280" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Update plan based on data</text>
</svg>

<h3>Format options</h3>
<p>Documentation can take many forms:</p>
<ul>
    <li><strong>Written document</strong> — a Word or Google Doc.</li>
    <li><strong>Spreadsheet</strong> — with tabs for each component.</li>
    <li><strong>Wiki or notes app</strong> — Notion, Obsidian, Evernote.</li>
    <li><strong>Printed checklist</strong> — physically visible near the trading station.</li>
    <li><strong>Personal trading journal</strong> — combined documentation and journal.</li>
</ul>
<p>Choose the format that you'll actually use. A simple text file that you read daily beats a fancy system you never open.</p>

<h3>Making it a living document</h3>
<p>The plan is not static. It should evolve with your experience:</p>
<ol>
    <li><strong>Review quarterly</strong> — assess performance and identify areas for improvement.</li>
    <li><strong>Update based on data</strong> — data reveals what works and what doesn't.</li>
    <li><strong>Keep version history</strong> — preserve old versions to track evolution.</li>
    <li><strong>Annotate changes</strong> — note why each change was made and its result.</li>
    <li><strong>Don't change mid-trade</strong> — the plan governs trades. Changes happen between sessions.</li>
</ol>

<h3>Worked example — Sample plan</h3>
<p>Let's write a complete trading plan for a part-time swing trader:</p>

<h4>1. Mission &amp; objectives</h4>
<ul>
    <li>Purpose: Supplement income, build trading skill.</li>
    <li>Target: 15–20% annually.</li>
    <li>Max drawdown: 15%.</li>
    <li>Time available: 2 hours/day (evenings).</li>
    <li>Capital: $15,000.</li>
</ul>

<h4>2. Markets &amp; timeframes</h4>
<ul>
    <li>Primary: EUR/USD.</li>
    <li>Secondary: GBP/USD, USD/JPY.</li>
    <li>Bias: Daily.</li>
    <li>Setup: H4.</li>
    <li>Entry: H4 close.</li>
</ul>

<h4>3. Trading style</h4>
<ul>
    <li>Swing trading.</li>
    <li>Hold 1–5 days.</li>
    <li>6–12 trades per month.</li>
</ul>

<h4>4. Analysis framework</h4>
<ul>
    <li>Bias: structure (HH/HL or LH/LL) + 50/200 MA.</li>
    <li>Setup: pullback to 20 EMA or 50–61.8% Fibonacci.</li>
    <li>Confirmation: engulfing or pin bar.</li>
    <li>Invalidation: pullback exceeds 78.6% or structure breaks.</li>
</ul>

<h4>5. Entry rules</h4>
<ul>
    <li>Setup conditions: trend confirmed on daily, pullback to level on H4.</li>
    <li>Trigger: bullish/bearish engulfing or pin bar close.</li>
    <li>Sessions: any (positions held overnight).</li>
    <li>Disqualifiers: pullback over 78.6%, R:R below 2:1.</li>
</ul>

<h4>6. Exit rules</h4>
<ul>
    <li>Stop: beyond recent swing.</li>
    <li>Target 1: prior swing high (partial 50%).</li>
    <li>Target 2: measured move (remaining 50%).</li>
    <li>Management: move stop to break-even at 1R.</li>
</ul>

<h4>7. Position sizing</h4>
<ul>
    <li>Risk: 1% per trade.</li>
    <li>Max heat: 4%.</li>
    <li>Concentration: 2% per theme.</li>
</ul>

<h4>8. Loss limits</h4>
<ul>
    <li>Daily: 2%.</li>
    <li>Weekly: 5%.</li>
    <li>Consecutive: 3 losses → stop for the day.</li>
    <li>Drawdown reductions: at 10%, reduce risk to 0.5%.</li>
</ul>

<h4>9. Filters &amp; no-trade conditions</h4>
<ul>
    <li>Market state: only trade in clear trends.</li>
    <li>Session: any (swing trading).</li>
    <li>News: avoid major events within 4 hours of entry.</li>
    <li>No-trade: emotional state compromised, daily/weekly limit hit, unclear market state.</li>
</ul>

<h4>10. Review process</h4>
<ul>
    <li>Daily: 15-min review of trades and set-ups.</li>
    <li>Weekly: 1-hour performance analysis.</li>
    <li>Monthly: 2-hour deep dive.</li>
    <li>Quarterly: full plan review.</li>
</ul>

<p>This plan is complete, specific, and executable. Every trading decision is guided by the plan. The plan evolves based on data.</p>

<h2>Factual context</h2>
<p>The importance of written plans is universally emphasised in professional development:</p>
<p><strong>Van Tharp</strong> — argues that traders without written plans are not trading — they're gambling.</p>
<p><strong>Brett Steenbarger</strong> — describes written plans as "the only way to build and maintain consistency."</p>
<p><strong>Atul Gawande</strong> — in <em>The Checklist Manifesto</em>, describes how checklists reduce errors in complex fields.</p>
<p><strong>Mark Douglas</strong> — emphasises that written rules are essential to trading without emotion.</p>
<p>Van Tharp, on written plans:</p>
<blockquote><strong>\"Traders who don't have written plans are not traders. They're gamblers. The plan distinguishes between the two.\"</strong></blockquote>
<p>Tharp's point is direct. Documentation transforms intention into action.</p>
<p>Brett Steenbarger, on the value of writing:</p>
<blockquote><strong>\"Writing forces clarity. You cannot write vague rules and expect to follow them. Writing is the first step to consistency.\"</strong></blockquote>
<p>Steenbarger's observation is critical. The act of writing forces you to think through every detail.</p>
<p>Atul Gawande, on checklists:</p>
<blockquote><strong>\"The volume and complexity of what we know has exceeded our individual ability to deliver its benefits correctly, safely, or reliably.\"</strong></blockquote>
<p>Gawande's point applies to trading. Complex strategies require checklists to execute consistently.</p>
<p>Ed Seykota, on documentation:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules are simple enough to be remembered — but even they benefit from being written down.</p>
<p>Paul Tudor Jones, on planning:</p>
<blockquote><strong>\"I know my levels, my stops, and my targets before the market opens. The plan is written before the trading starts.\"</strong></blockquote>
<p>Jones' discipline reflects proper documentation. Trading is planned, not improvised.</p>
<p>Bruce Kovner, on the value of structure:</p>
<blockquote><strong>\"My approach is structured. I know what I'm looking for, and I know when I find it. Without a plan, there's chaos.\"</strong></blockquote>
<p>Kovner's structure is documented structure. The plan prevents chaos.</p>
<p>Warren Buffett, on process:</p>
<blockquote><strong>\"You don't need to be a rocket scientist. You need a sound process and the discipline to follow it.\"</strong></blockquote>
<p>Buffett's process is documented, repeatable, and consistent. Trading plans follow the same logic.</p>
<p>Jesse Livermore, on written rules:</p>
<blockquote><strong>\"A man must know what he is doing. If he doesn't know, he will fail. If he knows but doesn't follow his rules, he will fail too.\"</strong></blockquote>
<p>Livermore's point: written rules alone are insufficient. They must be followed. But writing is the prerequisite for following.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>No documentation.</strong> Trading from memory produces inconsistency.</li>
    <li><strong>Vague documentation.</strong> "Buy bullish setups" is not a plan.</li>
    <li><strong>Too long.</strong> A 50-page plan won't be read. Keep it concise and actionable.</li>
    <li><strong>Not updating.</strong> Plans should evolve. Static plans become obsolete.</li>
    <li><strong>Not referring to the plan.</strong> Writing it is not enough. Read it daily.</li>
    <li><strong>Changing the plan mid-trade.</strong> Plans govern trades. Changes happen between sessions.</li>
    <li><strong>Not tracking versions.</strong> Without version history, you can't trace your evolution.</li>
    <li><strong>Copying templates without customisation.</strong> The plan must fit your objectives, markets, and personality.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use sophisticated documentation systems:</p>
<ul>
    <li><strong>Digital dashboards</strong> — Notion or similar tools for tracking plan and performance.</li>
    <li><strong>Version control</strong> — Git or similar for tracking document evolution.</li>
    <li><strong>Automated metrics</strong> — spreadsheets pulling trade data for real-time analysis.</li>
    <li><strong>Peer review</strong> — sharing plans with trading partners for feedback.</li>
    <li><strong>Annual retros</strong> — formal reviews of the year's plan evolution.</li>
</ul>
<p>For most retail traders, simpler systems work:</p>
<ol>
    <li>A written plan in a document (or notebook).</li>
    <li>Printed checklists for daily use.</li>
    <li>A journal for trade logging.</li>
    <li>Monthly reviews.</li>
    <li>Quarterly plan updates.</li>
</ol>
<p>The key is not sophistication. It's consistency. A simple plan, followed consistently, beats a sophisticated plan that's ignored.</p>
<p>The most important insight: documentation transforms trading from reactive to proactive. Instead of deciding in the moment, you follow pre-made decisions. This is what makes consistency possible.</p>
<p>With your strategy documented, you have a complete trading plan. The next modules cover backtesting, forward testing, and refinement — the process of validating and improving the plan over time.</p>
HTML,
        ],

        [
            'slug'   => 'putting-strategy-building-together',
            'title'  => 'Putting Strategy Building Together',
            'difficulty' => 'professional',
            'estimated_duration' => 13,
            'learning_objectives' =>
                "• Review all components of a personal strategy\n" .
                "• Build your own complete trading plan\n" .
                "• Prepare for backtesting and forward testing\n" .
                "• Commit to the process of refinement",
            'prerequisites' => 'Documenting Your Strategy',
            'sort_order' => 9,
            'summary' => 'This final lesson brings together everything in the module: objectives, markets, timeframes, entry rules, exit rules, position sizing, filters, no-trade conditions, and documentation. The goal is a complete, written, testable trading plan that you can execute, review, and refine.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>You've now learned every component of strategy design. This lesson puts them together into a single coherent plan — one that you can actually trade, review, and improve over time.</p>
<p>The process is not complex. But it requires commitment. The traders who succeed are the ones who complete the process and follow through.</p>

<h2>The complete strategy design process</h2>

<h3>Step 1: Define objectives</h3>
<p>Answer the five questions:</p>
<ol>
    <li>What is the purpose? (income, growth, learning)</li>
    <li>What is the target return? (realistic range)</li>
    <li>What is the maximum drawdown tolerance?</li>
    <li>How much time is available?</li>
    <li>What is the capital base?</li>
</ol>

<h3>Step 2: Choose markets and timeframes</h3>
<p>Based on objectives:</p>
<ul>
    <li>Primary market (start with 1).</li>
    <li>Secondary markets (add later, 1–2).</li>
    <li>Bias timeframe (higher).</li>
    <li>Setup timeframe (intermediate).</li>
    <li>Entry timeframe (execution).</li>
</ul>

<h3>Step 3: Define trading style</h3>
<ul>
    <li>Scalp, day, swing, or position?</li>
    <li>Match style to available time.</li>
    <li>Expected trade frequency.</li>
    <li>Expected hold time.</li>
</ul>

<h3>Step 4: Design entry rules</h3>
<ul>
    <li>Trend filter (what determines direction).</li>
    <li>Location filter (where entries can occur).</li>
    <li>Confirmation filter (what triggers entries).</li>
    <li>Timing filter (when entries allowed).</li>
    <li>Invalidation (what disqualifies entries).</li>
</ul>

<h3>Step 5: Design exit rules</h3>
<ul>
    <li>Stop-loss placement (structural or ATR).</li>
    <li>Take-profit (fixed R or structural).</li>
    <li>Trade management (move BE, partials, trailing).</li>
    <li>Time-based exits.</li>
</ul>

<h3>Step 6: Define position sizing</h3>
<ul>
    <li>Risk per trade (typically 1%).</li>
    <li>Maximum portfolio heat (typically 5%).</li>
    <li>Concentration limits per theme (typically 2%).</li>
    <li>Adjustments for conviction.</li>
</ul>

<h3>Step 7: Set loss limits</h3>
<ul>
    <li>Daily limit (2–3%).</li>
    <li>Weekly limit (4–6%).</li>
    <li>Consecutive loss trigger (3 losses).</li>
    <li>Drawdown reduction rules.</li>
</ul>

<h3>Step 8: Define filters and no-trade conditions</h3>
<ul>
    <li>Market state filter.</li>
    <li>Session filter.</li>
    <li>News filter.</li>
    <li>Correlation filter.</li>
    <li>No-trade conditions.</li>
</ul>

<h3>Step 9: Document the strategy</h3>
<ul>
    <li>Write the complete plan.</li>
    <li>Create daily checklists.</li>
    <li>Build the journal template.</li>
    <li>Set up metrics tracking.</li>
</ul>

<h3>Step 10: Test and refine</h3>
<ul>
    <li>Backtest manually (100+ historical setups).</li>
    <li>Forward test (30+ live demo trades).</li>
    <li>Compare results to expectations.</li>
    <li>Refine based on data.</li>
    <li>Begin live trading with small size.</li>
    <li>Scale up as performance is confirmed.</li>
</ul>

<h2>Visual reference — The complete process</h2>
<svg viewBox="0 0 500 500" width="500" height="500" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Circular flow -->
  <circle cx="250" cy="250" r="180" fill="none" stroke="#2c3140" stroke-width="1" stroke-dasharray="4,3"/>

  <!-- Step 1 -->
  <circle cx="250" cy="70" r="40" fill="#5b7cfa" fill-opacity="0.2" stroke="#5b7cfa" stroke-width="2"/>
  <text x="250" y="65" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">1</text>
  <text x="250" y="82" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Objectives</text>

  <!-- Step 2 -->
  <circle cx="380" cy="120" r="40" fill="#5b7cfa" fill-opacity="0.2" stroke="#5b7cfa" stroke-width="2"/>
  <text x="380" y="115" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">2</text>
  <text x="380" y="132" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Markets</text>

  <!-- Step 3 -->
  <circle cx="450" cy="230" r="40" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="2"/>
  <text x="450" y="225" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">3</text>
  <text x="450" y="242" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Style</text>

  <!-- Step 4 -->
  <circle cx="430" cy="350" r="40" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="2"/>
  <text x="430" y="345" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">4</text>
  <text x="430" y="362" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Entry rules</text>

  <!-- Step 5 -->
  <circle cx="340" cy="440" r="40" fill="#f97316" fill-opacity="0.2" stroke="#f97316" stroke-width="2"/>
  <text x="340" y="435" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">5</text>
  <text x="340" y="452" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Exit rules</text>

  <!-- Step 6 -->
  <circle cx="200" cy="450" r="40" fill="#f97316" fill-opacity="0.2" stroke="#f97316" stroke-width="2"/>
  <text x="200" y="445" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">6</text>
  <text x="200" y="462" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Sizing</text>

  <!-- Step 7 -->
  <circle cx="90" cy="380" r="40" fill="#ef4444" fill-opacity="0.2" stroke="#ef4444" stroke-width="2"/>
  <text x="90" y="375" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">7</text>
  <text x="90" y="392" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Loss limits</text>

  <!-- Step 8 -->
  <circle cx="60" cy="260" r="40" fill="#ef4444" fill-opacity="0.2" stroke="#ef4444" stroke-width="2"/>
  <text x="60" y="255" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">8</text>
  <text x="60" y="272" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Filters</text>

  <!-- Step 9 -->
  <circle cx="110" cy="140" r="40" fill="#eab308" fill-opacity="0.2" stroke="#eab308" stroke-width="2"/>
  <text x="110" y="135" fill="#eab308" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">9</text>
  <text x="110" y="152" fill="#e6e9ef" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Document</text>

  <!-- Step 10 -->
  <circle cx="250" cy="250" r="55" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80" stroke-width="2.5"/>
  <text x="250" y="240" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">10</text>
  <text x="250" y="260" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Test &amp; Refine</text>
</svg>

<h2>Your complete trading plan</h2>
<p>Now it's time to write your own. Here's a template you can copy and fill in:</p>

<h4>1. Objectives</h4>
<ul>
    <li>Purpose: ____________</li>
    <li>Target annual return: ____%</li>
    <li>Maximum drawdown: ____%</li>
    <li>Time available: ____ hours/day</li>
    <li>Capital: $____</li>
</ul>

<h4>2. Markets &amp; timeframes</h4>
<ul>
    <li>Primary market: ____________</li>
    <li>Secondary markets: ____________</li>
    <li>Bias timeframe: ____________</li>
    <li>Setup timeframe: ____________</li>
    <li>Entry timeframe: ____________</li>
</ul>

<h4>3. Trading style</h4>
<ul>
    <li>Style: ____________</li>
    <li>Expected trades/month: ____</li>
    <li>Expected hold time: ____________</li>
</ul>

<h4>4. Entry rules</h4>
<ul>
    <li>Trend filter: ____________</li>
    <li>Location filter: ____________</li>
    <li>Confirmation filter: ____________</li>
    <li>Timing filter: ____________</li>
</ul>

<h4>5. Exit rules</h4>
<ul>
    <li>Stop placement: ____________</li>
    <li>Target: ____________</li>
    <li>Management: ____________</li>
</ul>

<h4>6. Position sizing</h4>
<ul>
    <li>Risk per trade: ____%</li>
    <li>Max heat: ____%</li>
    <li>Concentration: ____%</li>
</ul>

<h4>7. Loss limits</h4>
<ul>
    <li>Daily: ____%</li>
    <li>Weekly: ____%</li>
    <li>Consecutive: ____</li>
</ul>

<h4>8. Filters</h4>
<ul>
    <li>Market state: ____________</li>
    <li>Session: ____________</li>
    <li>News: ____________</li>
    <li>No-trade: ____________</li>
</ul>

<h4>9. Routines</h4>
<ul>
    <li>Pre-session: ____________</li>
    <li>During session: ____________</li>
    <li>Post-session: ____________</li>
</ul>

<h4>10. Review</h4>
<ul>
    <li>Daily: ____________</li>
    <li>Weekly: ____________</li>
    <li>Monthly: ____________</li>
</ul>

<h2>Testing before trading</h2>
<p>Before trading real money, your strategy must be validated:</p>

<h3>Phase 1: Manual backtesting</h3>
<ul>
    <li>Go through historical charts.</li>
    <li>Identify every setup that met your rules.</li>
    <li>Record each outcome (win/loss) and R-multiple.</li>
    <li>Target: 100+ historical setups.</li>
    <li>Calculate expectancy.</li>
</ul>

<h3>Phase 2: Forward testing (demo)</h3>
<ul>
    <li>Trade the strategy live (demo account).</li>
    <li>Record every trade.</li>
    <li>Target: 30+ trades.</li>
    <li>Compare results to backtest.</li>
</ul>

<h3>Phase 3: Live trading (small size)</h3>
<ul>
    <li>Begin with 0.25% risk per trade.</li>
    <li>Target: 30+ trades at small size.</li>
    <li>Confirm results match demo.</li>
</ul>

<h3>Phase 4: Scale up</h3>
<ul>
    <li>Gradually increase risk per trade.</li>
    <li>Maximum: 1% per trade (standard).</li>
    <li>Monitor performance closely.</li>
</ul>

<h3>Visual reference — Testing progression</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Phase 1 -->
  <rect x="30" y="80" width="100" height="80" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="2" rx="6"/>
  <text x="80" y="110" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Phase 1</text>
  <text x="80" y="128" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Backtest</text>
  <text x="80" y="145" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">100+ setups</text>

  <!-- Arrow -->
  <line x1="130" y1="120" x2="150" y2="120" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="150,120 140,115 140,125" fill="#8b93a7"/>

  <!-- Phase 2 -->
  <rect x="150" y="80" width="100" height="80" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="200" y="110" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Phase 2</text>
  <text x="200" y="128" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Demo</text>
  <text x="200" y="145" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">30+ trades</text>

  <!-- Arrow -->
  <line x1="250" y1="120" x2="270" y2="120" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="270,120 260,115 260,125" fill="#8b93a7"/>

  <!-- Phase 3 -->
  <rect x="270" y="80" width="100" height="80" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="2" rx="6"/>
  <text x="320" y="110" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Phase 3</text>
  <text x="320" y="128" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Live small</text>
  <text x="320" y="145" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">0.25% risk</text>

  <!-- Arrow -->
  <line x1="370" y1="120" x2="390" y2="120" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="390,120 380,115 380,125" fill="#8b93a7"/>

  <!-- Phase 4 -->
  <rect x="390" y="80" width="90" height="80" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80" stroke-width="2.5" rx="6"/>
  <text x="435" y="110" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Phase 4</text>
  <text x="435" y="128" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Full size</text>
  <text x="435" y="145" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">1% risk</text>

  <!-- Overall label -->
  <text x="250" y="30" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">TESTING PROGRESSION</text>
  <text x="250" y="50" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Validate before scaling</text>
</svg>

<h2>Refinement is forever</h2>
<p>Strategy building doesn't end. It's an ongoing process:</p>
<ul>
    <li><strong>Monthly</strong> — review metrics, identify patterns.</li>
    <li><strong>Quarterly</strong> — assess strategy fit, consider adjustments.</li>
    <li><strong>Annually</strong> — major review, possible changes to approach.</li>
    <li><strong>Continuously</strong> — learn from every trade, refine rules as needed.</li>
</ul>
<p>But refinement must be data-driven, not emotional. Refine based on 100+ trade samples, not after losing streaks.</p>

<h3>Common refinement triggers</h3>
<ul>
    <li><strong>Expectancy drops below target</strong> — investigate why.</li>
    <li><strong>Win rate changes significantly</strong> — market conditions may have shifted.</li>
    <li><strong>New tools or concepts emerge</strong> — consider integrating them.</li>
    <li><strong>Personal circumstances change</strong> — adjust strategy to new lifestyle.</li>
    <li><strong>Annual review</strong> — formal assessment of strategy effectiveness.</li>
</ul>

<h3>What not to change</h3>
<ul>
    <li><strong>Core principles</strong> — risk management, position sizing.</li>
    <li><strong>Entry filters based on market state</strong> — don't remove these after winning streaks.</li>
    <li><strong>Loss limits</strong> — these are non-negotiable.</li>
    <li><strong>Emotional response to drawdowns</strong> — don't adjust rules during drawdown.</li>
</ul>

<h2>Factual context</h2>
<p>The complete process described here reflects how professional traders develop strategies:</p>
<p><strong>Van Tharp</strong> — his "peak performance" framework begins with objectives and ends with continuous refinement.</p>
<p><strong>Mark Douglas</strong> — emphasises the importance of a complete, written plan as the foundation of consistency.</p>
<p><strong>Brett Steenbarger</strong> — describes strategy development as a continuous cycle of design, testing, execution, and refinement.</p>
<p><strong>Ed Seykota</strong> — built and refined his trend-following approach over decades, always improving the framework.</p>
<p>Van Tharp, on completing the process:</p>
<blockquote><strong>\"A complete trading plan is worth more than any indicator. It's the difference between a system and a scheme.\"</strong></blockquote>
<p>Tharp's point captures the essence. Completing the process creates a real system.</p>
<p>Ed Seykota, on continuous refinement:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Rule 5 — know when to break the rules — reflects the need for ongoing refinement. Rules serve you; they don't enslave you.</p>
<p>Paul Tudor Jones, on personal ownership:</p>
<blockquote><strong>\"I've always had my own approach. I learned from others, but I built something that was mine. The process took years.\"</strong></blockquote>
<p>Jones' point: strategy building is a multi-year process. There's no shortcut.</p>
<p>Bruce Kovner, on the value of testing:</p>
<blockquote><strong>\"I test everything before I trade it. If it doesn't work in the test, it won't work live. If it works in the test, it might work live.\"</strong></blockquote>
<p>Kovner's discipline is essential. Testing separates real strategies from hopeful guesses.</p>
<p>Warren Buffett, on process:</p>
<blockquote><strong>\"The best investment is yourself. The more you learn, the more you earn.\"</strong></blockquote>
<p>Buffett's point applies to strategy building. The process of designing your own strategy teaches you far more than copying someone else's.</p>
<p>Jesse Livermore, on the trader's journey:</p>
<blockquote><strong>\"A man must know what he is doing. If he doesn't know, he will fail.\"</strong></blockquote>
<p>Livermore's point: understanding your strategy is essential. You can only understand what you have designed yourself.</p>
<p>Mark Douglas, on the ultimate goal:</p>
<blockquote><strong>\"The goal of a successful trader is to make the best trades. Money is secondary.\"</strong></blockquote>
<p>Douglas' point: the process of building a good strategy produces good trades. The money follows.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Skipping steps.</strong> Each component matters. Skipping steps produces incomplete strategies.</li>
    <li><strong>Rushing to trade.</strong> Testing takes time. Rushing past testing produces losses.</li>
    <li><strong>Building without data.</strong> Every component should be tested. Untested rules are guesses.</li>
    <li><strong>Refining based on emotion.</strong> Refinement happens after 100+ trades, not after every loss.</li>
    <li><strong>Abandoning the plan mid-stream.</strong> Give the strategy time. Switching resets the sample size.</li>
    <li><strong>Not adjusting to life changes.</strong> Strategies must evolve with your circumstances.</li>
    <li><strong>Over-optimising.</strong> Perfection is impossible. Good enough is sufficient.</li>
    <li><strong>Not committing.</strong> The best strategy is useless if not followed. Commitment is essential.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders build multiple strategies over their careers:</p>
<ul>
    <li><strong>First strategy</strong> — simple, tested, refined.</li>
    <li><strong>Second strategy</strong> — complementary (different conditions).</li>
    <li><strong>Third strategy</strong> — fills remaining gaps.</li>
    <li><strong>Portfolio approach</strong> — combining strategies for smoother returns.</li>
</ul>
<p>But each strategy is built one at a time. Master one before building another.</p>
<p>The most important insight: strategy building is not about finding a "holy grail." It's about designing a system that fits you, testing it thoroughly, and committing to execution. The edge comes from the combination of design, testing, and discipline — not from any single component.</p>
<p>With the strategy-building module complete, you have a complete framework for designing your own trading approach. The next modules — Backtesting, Forward Testing, and the rest — will guide you through validating and refining your strategy over time.</p>
<p>Your trading plan is now ready to be tested. That's the next step.</p>
HTML,
        ],

    ],
];