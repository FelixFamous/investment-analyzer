<?php
/**
 * Module 33 — Backtesting
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_33_backtesting.php
 */

return [
    'module' => [
        'level_slug' => 'professional',
        'slug'       => 'backtesting',
        'title'      => 'Backtesting',
        'description'=> 'Backtesting is the process of testing a strategy against historical data. It answers one crucial question before you risk real money: does this strategy have a positive expectancy? This module teaches manual backtesting methodology, sample size requirements, common biases to avoid, and how to interpret results honestly.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Understand why backtesting is essential before live trading\n" .
            "• Perform manual backtesting on historical charts\n" .
            "• Determine adequate sample size for statistical reliability\n" .
            "• Identify and avoid the five backtesting biases\n" .
            "• Calculate expectancy from backtest data\n" .
            "• Distinguish genuine edge from curve-fitted noise",
        'sort_order' => 33,
    ],

    'lessons' => [

        [
            'slug'   => 'what-is-backtesting',
            'title'  => 'What Is Backtesting?',
            'difficulty' => 'professional',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Define backtesting and its purpose\n" .
                "• Understand why it must be done before live trading\n" .
                "• Recognise what backtesting can and cannot tell you\n" .
                "• Choose the right backtesting method",
            'prerequisites' => 'Putting Strategy Building Together',
            'sort_order' => 1,
            'summary' => 'Backtesting is the process of applying a trading strategy to historical price data to determine how it would have performed. It is the only way to validate a strategy before risking capital, and it separates traders who have an edge from those who merely hope they do.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine you've designed a new type of bridge. Before building it over a real river with real traffic, you'd test it. You'd simulate weight loads, weather conditions, and wear over time. If the simulation fails, you fix the design. If it succeeds, you build with confidence.</p>
<p>Backtesting is the trader's version of simulation. Before risking real money, you test the strategy against historical data. If it fails, you fix the strategy. If it succeeds, you trade with confidence.</p>

<h2>Real-world analogy</h2>
<p>Think of a chef developing a new dish. Before putting it on the menu, they cook it many times, tasting at each stage, adjusting ingredients. Only when they're confident does it go to customers. Backtesting is the same — you cook the strategy many times on historical data before serving it live.</p>

<h2>Professional explanation</h2>

<h3>What backtesting is</h3>
<p><strong>Backtesting</strong> is the systematic process of applying a trading strategy's rules to historical price data and recording the results. It produces a set of statistics that describe how the strategy would have performed.</p>
<p>The core question backtesting answers: <em>Does this strategy have a positive expectancy?</em></p>

<h3>What backtesting tells you</h3>
<ul>
    <li><strong>Win rate</strong> — the percentage of trades that won.</li>
    <li><strong>Payoff ratio</strong> — the average win divided by the average loss.</li>
    <li><strong>Expectancy</strong> — the average R-multiple per trade.</li>
    <li><strong>Maximum drawdown</strong> — the worst peak-to-trough decline.</li>
    <li><strong>Trade frequency</strong> — how many opportunities the strategy produces.</li>
    <li><strong>Longest losing streak</strong> — the worst run of consecutive losses.</li>
    <li><strong>Profit factor</strong> — total profits divided by total losses.</li>
</ul>

<h3>What backtesting does NOT tell you</h3>
<ul>
    <li><strong>Future performance</strong> — past results do not guarantee future results.</li>
    <li><strong>Exact outcomes</strong> — individual trades will vary from historical averages.</li>
    <li><strong>Slippage and execution quality</strong> — backtests assume fills at specific prices.</li>
    <li><strong>Emotional difficulty</strong> — backtests don't reflect the psychology of live trading.</li>
    <li><strong>Market regime changes</strong> — markets evolve. Historical conditions may not persist.</li>
</ul>
<p>Backtesting is necessary but not sufficient. It's the starting point, not the finish line.</p>

<h3>Visual reference — What backtesting provides</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Historical data -->
  <rect x="30" y="20" width="440" height="50" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="2" rx="6"/>
  <text x="250" y="42" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">HISTORICAL DATA</text>
  <text x="250" y="60" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Past price charts across multiple years</text>

  <!-- Arrow down -->
  <line x1="250" y1="70" x2="250" y2="100" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,100 245,90 255,90" fill="#8b93a7"/>

  <!-- Apply rules -->
  <rect x="30" y="105" width="440" height="50" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="250" y="127" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">APPLY STRATEGY RULES</text>
  <text x="250" y="145" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Identify setups, record outcomes</text>

  <!-- Arrow down -->
  <line x1="250" y1="155" x2="250" y2="185" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,185 245,175 255,175" fill="#8b93a7"/>

  <!-- Results -->
  <rect x="30" y="190" width="440" height="50" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="2" rx="6"/>
  <text x="250" y="212" fill="#f97316" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">STATISTICAL RESULTS</text>
  <text x="250" y="230" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Win rate, expectancy, drawdown, streaks</text>

  <!-- Decision -->
  <rect x="120" y="260" width="260" height="35" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="250" y="283" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">DECISION: PROCEED, REFINE, OR REJECT</text>
</svg>

<h3>Backtesting methods</h3>
<p>Three approaches to backtesting, each with trade-offs:</p>

<h4>1. Manual backtesting</h4>
<ul>
    <li><strong>How:</strong> Scroll through historical charts, identify setups, record outcomes by hand.</li>
    <li><strong>Pros:</strong> Deep understanding of the strategy, no coding required, catches nuances automated systems miss.</li>
    <li><strong>Cons:</strong> Time-consuming (100 trades may take 20+ hours), prone to human error and bias.</li>
    <li><strong>Best for:</strong> Discretionary traders, pattern-based strategies, first-time backtesting.</li>
</ul>

<h4>2. Spreadsheet-assisted backtesting</h4>
<ul>
    <li><strong>How:</strong> Use Excel/Google Sheets to calculate statistics from manually logged trades.</li>
    <li><strong>Pros:</strong> Combines manual identification with automated statistics.</li>
    <li><strong>Cons:</strong> Still requires manual trade identification.</li>
    <li><strong>Best for:</strong> Traders who want to track performance systematically.</li>
</ul>

<h4>3. Automated backtesting</h4>
<ul>
    <li><strong>How:</strong> Code the strategy (Python, TradingView Pine, MT4/5, etc.) and run against historical data.</li>
    <li><strong>Pros:</strong> Fast (thousands of trades in minutes), consistent, no human bias.</li>
    <li><strong>Cons:</strong> Requires coding skills, hard to code discretionary rules, easy to overfit.</li>
    <li><strong>Best for:</strong> Rule-based strategies, quantitative traders, large sample sizes.</li>
</ul>

<h3>Visual reference — Backtesting methods comparison</h3>
<svg viewBox="0 0 500 280" width="500" height="280" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Manual -->
  <rect x="30" y="30" width="140" height="220" fill="#5b7cfa" fill-opacity="0.1" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="100" y="55" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">MANUAL</text>
  <text x="45" y="85" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Deep understanding</text>
  <text x="45" y="105" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ No coding needed</text>
  <text x="45" y="125" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Catches nuance</text>
  <text x="45" y="150" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">✗ Very slow</text>
  <text x="45" y="170" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">✗ Human bias</text>
  <text x="45" y="190" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">✗ Hard to scale</text>
  <text x="45" y="220" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" font-style="italic">Best for beginners</text>

  <!-- Spreadsheet -->
  <rect x="180" y="30" width="140" height="220" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="250" y="55" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">SPREADSHEET</text>
  <text x="195" y="85" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Semi-automated</text>
  <text x="195" y="105" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Easy statistics</text>
  <text x="195" y="125" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Good for tracking</text>
  <text x="195" y="150" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">✗ Still manual entry</text>
  <text x="195" y="170" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">✗ Some bias risk</text>
  <text x="195" y="220" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" font-style="italic">Best for intermediate</text>

  <!-- Automated -->
  <rect x="330" y="30" width="140" height="220" fill="#f97316" fill-opacity="0.1" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="400" y="55" fill="#f97316" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">AUTOMATED</text>
  <text x="345" y="85" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Very fast</text>
  <text x="345" y="105" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ No human bias</text>
  <text x="345" y="125" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Massive samples</text>
  <text x="345" y="150" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">✗ Coding required</text>
  <text x="345" y="170" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">✗ Hard for discretion</text>
  <text x="345" y="190" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">✗ Overfitting risk</text>
  <text x="345" y="220" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" font-style="italic">Best for quant</text>
</svg>

<h3>Why backtesting is essential</h3>
<p>Without backtesting, you're trading on hope. With backtesting, you're trading on data. The difference:</p>
<table>
    <thead><tr><th>Without Backtesting</th><th>With Backtesting</th></tr></thead>
    <tbody>
        <tr><td>Hope the strategy works</td><td>Know the historical expectancy</td></tr>
        <tr><td>Guess at position size</td><td>Calculate position size from data</td></tr>
        <tr><td>React emotionally to losses</td><td>Expect losing streaks, prepare</td></tr>
        <tr><td>Blame the market for failure</td><td>Adjust the strategy based on data</td></tr>
        <tr><td>No baseline for review</td><td>Compare live to historical</td></tr>
        <tr><td>Chase new strategies</td><td>Refine the proven strategy</td></tr>
    </tbody>
</table>

<h3>Backtesting discipline</h3>
<p>Backtesting requires discipline. Common failures:</p>
<ul>
    <li><strong>Skipping it</strong> — starting live without validation. Almost always ends in losses.</li>
    <li><strong>Rushing it</strong> — taking shortcuts, not testing enough trades. Produces unreliable results.</li>
    <li><strong>Cherry-picking</strong> — only testing markets or periods where the strategy works. Produces fake confidence.</li>
    <li><strong>Overfitting</strong> — tweaking rules to improve backtest results. Produces curves that fail live.</li>
    <li><strong>Not updating</strong> — running one backtest and never revisiting. Markets change; backtests should be refreshed.</li>
</ul>

<h2>Factual context</h2>
<p>Backtesting has been central to systematic trading for decades:</p>
<p><strong>Richard Donchian (1940s–1950s)</strong> — hand-tested his trend-following systems against decades of price data. His approach pioneered backtesting.</p>
<p><strong>The Turtle Traders (1983)</strong> — Dennis and Eckhardt backtested their breakout rules extensively before teaching them. The success of the Turtles demonstrated the value of validated rules.</p>
<p><strong>Modern quant funds</strong> — use sophisticated backtesting frameworks with walk-forward analysis, out-of-sample testing, and Monte Carlo simulation.</p>
<p><strong>Retail platforms</strong> — TradingView, MT4/5, and other platforms provide built-in backtesting tools, making the practice accessible.</p>
<p>Ed Seykota, on testing before trading:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules assume testing. Without backtesting, you don't know if the rules work.</p>
<p>Richard Dennis, on the Turtle experiment:</p>
<blockquote><strong>\"I have always maintained that one could train a group of people to be successful traders using a small set of rules.\"</strong></blockquote>
<p>Dennis' confidence came from backtesting. He knew the rules worked before teaching them.</p>
<p>Van Tharp, on system validation:</p>
<blockquote><strong>\"You cannot know whether a system works until you've tested it. Hope is not a strategy.\"</strong></blockquote>
<p>Tharp's point: testing is the difference between trading and gambling.</p>
<p>Larry Hite, on the importance of data:</p>
<blockquote><strong>\"I have two basic rules about winning in trading as well as in life: (1) If you don't bet, you can't win. (2) If you lose all your chips, you can't bet.\"</strong></blockquote>
<p>Hite's rules imply backtesting. You can't know when to bet without data.</p>
<p>Paul Tudor Jones, on preparation:</p>
<blockquote><strong>\"The best traders do their homework. They know the historical behaviour of their markets, their setups, their risk. Nothing is left to chance.\"</strong></blockquote>
<p>Jones' preparation includes backtesting. Understanding historical behaviour is essential.</p>
<p>Mark Douglas, on the probabilistic mindset:</p>
<blockquote><strong>\"The market is a probabilistic environment. Any single trade can have any outcome. The probability of a specific outcome is what matters.\"</strong></blockquote>
<p>Backtesting provides those probabilities. Without it, probabilities are guesses.</p>
<p>Bruce Kovner, on validation:</p>
<blockquote><strong>\"I test everything before I trade it. If it doesn't work in the test, it won't work live.\"</strong></blockquote>
<p>Kovner's discipline is the model. Test first, trade second.</p>
<p>Warren Buffett, on process:</p>
<blockquote><strong>\"You don't need to be a rocket scientist. You need a sound process and the discipline to follow it.\"</strong></blockquote>
<p>Backtesting is the process. Following it is the discipline.</p>
<p>Nassim Nicholas Taleb, on the limits of historical data:</p>
<blockquote><strong>\"The problem with historical data is that it only contains what has happened, not what could happen. Extreme events are systematically underestimated.\"</strong></blockquote>
<p>Taleb's warning: backtesting has limits. Extreme events are not well represented. Robustness matters.</p>
<p>Jesse Livermore, on preparation:</p>
<blockquote><strong>\"There is a time to go long, a time to go short, and a time to go fishing.\"</strong></blockquote>
<p>Livermore's timing came from experience. Backtesting captures some of that experience in advance.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Skipping backtesting entirely.</strong> Trading live without validation is gambling.</li>
    <li><strong>Testing too few trades.</strong> Under 30 trades provides no statistical significance.</li>
    <li><strong>Only testing favourable periods.</strong> Cherry-picking produces fake confidence.</li>
    <li><strong>Ignoring costs.</strong> Backtests must include spread, commission, and slippage.</li>
    <li><strong>Overfitting to historical data.</strong> Curves that fit history perfectly fail live.</li>
    <li><strong>Not documenting the backtest.</strong> Without records, results can't be reviewed.</li>
    <li><strong>Confusing backtest with forward test.</strong> Backtest uses historical data; forward test uses new data.</li>
    <li><strong>Assuming past = future.</strong> Backtests project probabilities, not certainties.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional backtesting involves sophisticated techniques:</p>
<ul>
    <li><strong>Walk-forward analysis</strong> — testing the strategy on rolling windows of data, then validating on the next window.</li>
    <li><strong>Out-of-sample testing</strong> — reserving a portion of historical data (e.g., 30%) that the strategy was never tuned on. If it works out-of-sample, the edge is more likely genuine.</li>
    <li><strong>Monte Carlo simulation</strong> — randomly shuffling trade order to estimate distribution of outcomes.</li>
    <li><strong>Cross-market validation</strong> — testing the same strategy on multiple markets.</li>
    <li><strong>Regime analysis</strong> — testing how the strategy performs in trending vs ranging markets.</li>
    <li><strong>Sensitivity analysis</strong> — checking whether small changes in parameters (e.g., 20 MA vs 21 MA) significantly affect results.</li>
</ul>
<p>For retail traders, basic backtesting is sufficient. The key is to test rigorously, with sufficient sample size, and to avoid the common biases (covered in a later lesson).</p>
<p>The most important insight: backtesting is not about proving you're right. It's about discovering whether the strategy works. If it doesn't, you've saved money by finding out before going live.</p>
HTML,
        ],

        [
            'slug'   => 'manual-backtesting-methodology',
            'title'  => 'Manual Backtesting Methodology',
            'difficulty' => 'professional',
            'estimated_duration' => 13,
            'learning_objectives' =>
                "• Set up a manual backtest environment\n" .
                "• Navigate historical charts efficiently\n" .
                "• Record trades systematically\n" .
                "• Calculate statistics from your backtest",
            'prerequisites' => 'What Is Backtesting?',
            'sort_order' => 2,
            'summary' => 'Manual backtesting is the foundational skill for validating any strategy. It involves scrolling through historical charts, identifying setups that match your rules, recording outcomes, and calculating statistics. This lesson covers the complete manual backtesting process.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine you're a detective reviewing old case files. You look at each file one at a time, identify the pattern, and record the outcome. Manual backtesting works the same way — you review historical charts one at a time, identify setups, and record what happened.</p>
<p>It's slow, but it builds deep understanding. You'll see the strategy in action hundreds of times, learning its nuances.</p>

<h2>Real-world analogy</h2>
<p>Think of an athlete reviewing game footage. They watch past games, studying each play, learning what worked and what didn't. Manual backtesting is the trader's version of film study.</p>

<h2>Professional explanation</h2>

<h3>Setting up your environment</h3>
<p>Before starting, prepare:</p>

<h4>1. Charting platform</h4>
<ul>
    <li><strong>TradingView</strong> — free tier works, has replay mode.</li>
    <li><strong>MetaTrader 4/5</strong> — free, includes strategy tester.</li>
    <li><strong>Forex Tester</strong> — paid, designed for manual backtesting.</li>
    <li><strong>Soft4FX</strong> — paid, similar to Forex Tester.</li>
</ul>
<p>For manual backtesting, look for a "replay" or "bar replay" feature that hides future price.</p>

<h4>2. Spreadsheet for recording</h4>
<p>Create a spreadsheet with columns:</p>
<ul>
    <li>Trade number</li>
    <li>Date/time</li>
    <li>Market</li>
    <li>Direction (long/short)</li>
    <li>Entry price</li>
    <li>Stop price</li>
    <li>Target price</li>
    <li>Exit price</li>
    <li>R-multiple (win/loss in R)</li>
    <li>Notes (setup quality, mistakes)</li>
</ul>

<h4>3. Rules document</h4>
<p>Print or display your written strategy rules where you can see them during testing. Every trade decision references the rules.</p>

<h4>4. Time block</h4>
<p>Manual backtesting takes time. Budget:
</p>
<ul>
    <li><strong>5–10 minutes per trade</strong> for setup identification and recording.</li>
    <li><strong>3–5 minutes per trade</strong> for quick scenarios.</li>
    <li><strong>20+ hours</strong> for 100 trades (a common target).</li>
</ul>
<p>Break it into sessions of 1–2 hours to avoid fatigue.</p>

<h3>Visual reference — Backtesting environment setup</h3>
<svg viewBox="0 0 500 300" width="500" height="300" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Platform -->
  <rect x="20" y="30" width="140" height="80" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="90" y="55" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">CHARTING</text>
  <text x="90" y="70" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">PLATFORM</text>
  <text x="90" y="90" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">With replay feature</text>
  <text x="90" y="103" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">TradingView / MT4</text>

  <!-- Spreadsheet -->
  <rect x="180" y="30" width="140" height="80" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="250" y="55" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">SPREADSHEET</text>
  <text x="250" y="70" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">FOR TRADES</text>
  <text x="250" y="90" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Columns defined</text>
  <text x="250" y="103" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Auto-calc R-multiple</text>

  <!-- Rules -->
  <rect x="340" y="30" width="140" height="80" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="410" y="55" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">WRITTEN</text>
  <text x="410" y="70" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">RULES</text>
  <text x="410" y="90" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Visible during test</text>
  <text x="410" y="103" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Referenced always</text>

  <!-- Time block -->
  <rect x="20" y="140" width="140" height="80" fill="#5b7cfa" fill-opacity="0.1" stroke="#5b7cfa" stroke-width="1" rx="6"/>
  <text x="90" y="165" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">TIME BLOCK</text>
  <text x="90" y="185" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">1-2 hour sessions</text>
  <text x="90" y="200" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Regular schedule</text>
  <text x="90" y="215" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Avoid fatigue</text>

  <!-- Focus indicator -->
  <rect x="180" y="140" width="300" height="80" fill="#4ade80" fill-opacity="0.05" stroke="#4ade80" stroke-width="1" stroke-dasharray="3,2" rx="6"/>
  <text x="330" y="165" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">THE GOAL</text>
  <text x="330" y="185" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">100+ historical trades per strategy</text>
  <text x="330" y="200" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Record every setup that met your rules</text>
  <text x="330" y="215" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Calculate statistics to determine edge</text>

  <!-- Bottom label -->
  <text x="250" y="270" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Start with setup, end with statistics</text>
</svg>

<h3>The manual backtesting process</h3>

<h4>Step 1: Choose your starting point</h4>
<p>Pick a historical period that includes different market conditions:</p>
<ul>
    <li><strong>Trending markets</strong> — for trend strategies.</li>
    <li><strong>Ranging markets</strong> — for range strategies.</li>
    <li><strong>High volatility</strong> — for event-driven strategies.</li>
    <li><strong>Low volatility</strong> — for range-bound markets.</li>
</ul>
<p>A good backtest includes at least 2–3 years of data covering multiple conditions.</p>

<h4>Step 2: Enable replay mode</h4>
<p>Turn on your platform's replay feature. This hides future bars so you're not biased by knowing what happens next.</p>
<p>Set the starting point to the beginning of your chosen period. Advance the chart one bar at a time.</p>

<h4>Step 3: Analyse the current bar</h4>
<p>For each new bar:</p>
<ol>
    <li>Check whether a setup is forming.</li>
    <li>Does it meet all your entry rules?</li>
    <li>If yes, note the entry price (typically the close of the trigger bar).</li>
    <li>Identify the stop-loss price (structural).</li>
    <li>Identify the target price (structural or R-multiple).</li>
</ol>

<h4>Step 4: Advance to outcome</h4>
<p>Continue advancing bars until the trade closes:</p>
<ul>
    <li><strong>Winner:</strong> price reaches target.</li>
    <li><strong>Loser:</strong> price hits stop.</li>
    <li><strong>Breakeven:</strong> moved to BE and closed there.</li>
    <li><strong>Manual exit:</strong> if the strategy has discretion.</li>
</ul>

<h4>Step 5: Record the trade</h4>
<p>Record in your spreadsheet:</p>
<ul>
    <li>Entry/stop/target/exit prices.</li>
    <li>R-multiple (calculate as (exit − entry) / (entry − stop) for longs).</li>
    <li>Notes on setup quality.</li>
</ul>

<h4>Step 6: Continue</h4>
<p>Move to the next bar. Repeat the process.</p>
<p>The goal is 100+ trades recorded. This takes hours, but it builds deep understanding.</p>

<h3>Visual reference — Backtesting flow</h3>
<svg viewBox="0 0 500 400" width="500" height="400" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Start -->
  <ellipse cx="250" cy="30" rx="70" ry="18" fill="#5b7cfa" fill-opacity="0.3" stroke="#5b7cfa" stroke-width="2"/>
  <text x="250" y="35" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Start</text>

  <!-- Arrow -->
  <line x1="250" y1="48" x2="250" y2="75" stroke="#8b93a7" stroke-width="2"/>

  <!-- Advance bar -->
  <rect x="150" y="75" width="200" height="35" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="250" y="97" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Advance one bar</text>

  <!-- Arrow -->
  <line x1="250" y1="110" x2="250" y2="135" stroke="#8b93a7" stroke-width="2"/>

  <!-- Decision: setup? -->
  <polygon points="250,135 400,170 250,205 100,170" fill="#f97316" fill-opacity="0.2" stroke="#f97316" stroke-width="1.5"/>
  <text x="250" y="168" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Setup matches</text>
  <text x="250" y="182" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">rules?</text>

  <!-- No arrow -->
  <line x1="100" y1="170" x2="50" y2="170" stroke="#8b93a7" stroke-width="2"/>
  <text x="70" y="165" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">No</text>
  <text x="50" y="190" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Continue</text>

  <!-- Yes arrow -->
  <line x1="250" y1="205" x2="250" y2="235" stroke="#8b93a7" stroke-width="2"/>
  <text x="270" y="225" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Yes</text>

  <!-- Record entry -->
  <rect x="140" y="235" width="220" height="35" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="250" y="257" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Record entry, stop, target</text>

  <!-- Arrow -->
  <line x1="250" y1="270" x2="250" y2="295" stroke="#8b93a7" stroke-width="2"/>

  <!-- Advance to outcome -->
  <rect x="140" y="295" width="220" height="35" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="250" y="317" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Advance until trade closes</text>

  <!-- Arrow -->
  <line x1="250" y1="330" x2="250" y2="355" stroke="#8b93a7" stroke-width="2"/>

  <!-- Record outcome -->
  <rect x="140" y="355" width="220" height="35" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="250" y="377" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Record outcome in R</text>
</svg>

<h3>Recording conventions</h3>
<p>Consistency in recording is essential:</p>

<h4>R-multiple calculation</h4>
<ul>
    <li><strong>Long trade:</strong> R = (exit − entry) / (entry − stop)</li>
    <li><strong>Short trade:</strong> R = (entry − exit) / (stop − entry)</li>
    <li><strong>Winner:</strong> R is positive (e.g., +2.0R).</li>
    <li><strong>Loser:</strong> R is negative (e.g., −1.0R).</li>
    <li><strong>Breakeven:</strong> R is zero.</li>
</ul>

<h4>Entry price conventions</h4>
<ul>
    <li><strong>Close of trigger candle</strong> — most common.</li>
    <li><strong>Next candle open</strong> — more realistic for live execution.</li>
    <li><strong>Limit price</strong> — if the strategy uses limit orders.</li>
</ul>
<p>Pick one convention and use it consistently across all trades.</p>

<h4>Costs</h4>
<p>Subtract costs from every trade:</p>
<ul>
    <li><strong>Spread</strong> — typically 1–2 pips on majors.</li>
    <li><strong>Commission</strong> — if your broker charges it.</li>
    <li><strong>Slippage</strong> — assume 0.5–1 pip on entries/exits.</li>
</ul>
<p>Adjust R-multiple by costs. A +2R winner becomes +1.95R after costs.</p>

<h3>Calculating statistics</h3>
<p>Once you have 100+ trades, calculate:</p>

<h4>Win rate</h4>
<p>Win rate = (Number of winning trades) / (Total trades)</p>

<h4>Average win and loss</h4>
<p>Average win = Sum of winning R-multiples / Number of winners</p>
<p>Average loss = Sum of losing R-multiples / Number of losers</p>

<h4>Expectancy</h4>
<p>Expectancy = (Win rate × Average win) − (Loss rate × Average loss)</p>

<h4>Maximum drawdown</h4>
<p>Track cumulative R over the backtest. The maximum decline from any peak is the drawdown.</p>

<h4>Longest losing streak</h4>
<p>The longest run of consecutive losses. This indicates the psychological difficulty of the strategy.</p>

<h4>Profit factor</h4>
<p>Profit factor = Total profits / Total losses</p>
<p>Above 1.5 is good; above 2.0 is excellent.</p>

<h3>Visual reference — Statistics dashboard</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Total trades -->
  <rect x="30" y="30" width="100" height="80" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="80" y="55" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Total Trades</text>
  <text x="80" y="85" fill="#5b7cfa" font-size="22" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">142</text>
  <text x="80" y="102" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Sample size</text>

  <!-- Win rate -->
  <rect x="140" y="30" width="100" height="80" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="190" y="55" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Win Rate</text>
  <text x="190" y="85" fill="#4ade80" font-size="22" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">44%</text>
  <text x="190" y="102" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">63 wins / 79 losses</text>

  <!-- Expectancy -->
  <rect x="250" y="30" width="100" height="80" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80" stroke-width="2"/>
  <text x="300" y="55" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Expectancy</text>
  <text x="300" y="85" fill="#4ade80" font-size="22" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">+0.42R</text>
  <text x="300" y="102" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Avg per trade</text>

  <!-- Max drawdown -->
  <rect x="360" y="30" width="100" height="80" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="410" y="55" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Max DD</text>
  <text x="410" y="85" fill="#f97316" font-size="22" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">-14R</text>
  <text x="410" y="102" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Worst peak-trough</text>

  <!-- Longest streak -->
  <rect x="30" y="130" width="140" height="80" fill="#ef4444" fill-opacity="0.15" stroke="#ef4444" stroke-width="1.5" rx="6"/>
  <text x="100" y="155" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Longest Loss Streak</text>
  <text x="100" y="185" fill="#ef4444" font-size="22" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">7 losses</text>
  <text x="100" y="202" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Prepare mentally</text>

  <!-- Profit factor -->
  <rect x="180" y="130" width="140" height="80" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="250" y="155" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Profit Factor</text>
  <text x="250" y="185" fill="#4ade80" font-size="22" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">1.78</text>
  <text x="250" y="202" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Good (&gt; 1.5)</text>

  <!-- Avg R -->
  <rect x="330" y="130" width="130" height="80" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="395" y="155" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Avg Win / Avg Loss</text>
  <text x="395" y="185" fill="#5b7cfa" font-size="20" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">2.4R / 1R</text>
  <text x="395" y="202" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">2.4:1 payoff</text>

  <!-- Bottom summary -->
  <text x="250" y="240" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Positive expectancy: strategy has an edge</text>
</svg>

<h3>Common recording mistakes</h3>
<ul>
    <li><strong>Not recording losing trades.</strong> All trades must be recorded. Ignoring losers is survivorship bias.</li>
    <li><strong>Recording only perfect setups.</strong> Record all trades that met the criteria, not just the best ones.</li>
    <li><strong>Adjusting entry prices after the fact.</strong> Use the convention you chose at the start.</li>
    <li><strong>Ignoring costs.</strong> Adjust R-multiples for spread and commission.</li>
    <li><strong>Noting outcomes without context.</strong> Record market conditions at the time.</li>
    <li><strong>Inconsistent stop placement.</strong> Use the same rules for all stops.</li>
    <li><strong>Recording "what could have been."</strong> Record only what the strategy would have done, not what you wish it had done.</li>
    <li><strong>Not tracking setup quality.</strong> Record how well the setup met criteria. Quality may correlate with outcome.</li>
</ul>

<h3>Tips for efficiency</h3>
<ul>
    <li><strong>Use keyboard shortcuts.</strong> Learn the replay controls on your platform for fast advancement.</li>
    <li><strong>Focus on one timeframe.</strong> Don't switch between timeframes mid-session.</li>
    <li><strong>Take notes on patterns.</strong> If you notice something interesting, note it for later.</li>
    <li><strong>Review every 25 trades.</strong> Spot-check your work for consistency.</li>
    <li><strong>Use templates.</strong> Pre-fill your spreadsheet with formulas.</li>
    <li><strong>Avoid changing rules mid-test.</strong> Finish the test, then adjust if needed.</li>
    <li><strong>Take breaks.</strong> Fatigue leads to mistakes.</li>
</ul>

<h2>Factual context</h2>
<p>Manual backtesting has a long history in trading:</p>
<p><strong>Richard Donchian</strong> — manually tested his trend systems against decades of data before computers made it easier.</p>
<p><strong>The Turtle Traders</strong> — Dennis and Eckhardt ran manual and semi-automated backtests before teaching their system.</p>
<p><strong>Pre-computer era</strong> — traders like Livermore and Wyckoff relied on manual chart review, which is essentially manual backtesting.</p>
<p><strong>Modern tools</strong> — TradingView, MT4/5, and others provide replay features that make manual backtesting more efficient.</p>
<p>Ed Seykota, on the value of manual testing:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules assume testing. Manual backtesting is the foundation.</p>
<p>Mark Douglas, on consistency:</p>
<blockquote><strong>\"Consistency comes from doing the same thing every time. In backtesting, this means following the same process for every trade.\"</strong></blockquote>
<p>Douglas' point captures the discipline of manual backtesting. Same process, every time.</p>
<p>Van Tharp, on the value of the process:</p>
<blockquote><strong>\"Even if the strategy fails, you learn from the process. Manual backtesting teaches you to see the market through your rules.\"</strong></blockquote>
<p>Tharp's insight: the process of backtesting is valuable even if the strategy is rejected.</p>
<p>Paul Tudor Jones, on preparation:</p>
<blockquote><strong>\"I do the work. I know the historical behaviour of my markets. There is no substitute for preparation.\"</strong></blockquote>
<p>Jones' preparation includes extensive historical review.</p>
<p>Jesse Livermore, on learning from history:</p>
<blockquote><strong>\"There is nothing new in Wall Street. There can't be because speculation is as old as the hills.\"</strong></blockquote>
<p>Livermore's point: history repeats. Manual backtesting captures patterns that recur.</p>
<p>Bruce Kovner, on the discipline of testing:</p>
<blockquote><strong>\"I test everything before I trade it. It takes time, but it saves money.\"</strong></blockquote>
<p>Kovner's discipline is the model. Manual backtesting requires time investment, but pays off.</p>
<p>Warren Buffett, on learning:</p>
<blockquote><strong>\"The best investment is yourself. The more you learn, the more you earn.\"</strong></blockquote>
<p>Buffett's point applies to manual backtesting. Every hour spent reviewing history teaches you something.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Testing too few trades.</strong> Under 100 trades is insufficient.</li>
    <li><strong>Skipping the replay mode.</strong> Without it, you're biased by knowing the future.</li>
    <li><strong>Changing rules mid-test.</strong> Complete a full test before adjusting.</li>
    <li><strong>Not recording losing trades.</strong> All trades must be recorded.</li>
    <li><strong>Ignoring costs.</strong> Spread and commission must be subtracted.</li>
    <li><strong>Not documenting the setup.</strong> Notes on quality help later analysis.</li>
    <li><strong>Rushing through trades.</strong> Speed produces errors.</li>
    <li><strong>Fatigue.</strong> Long sessions produce mistakes. Take breaks.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional manual backtesters use several techniques to improve efficiency and reliability:</p>
<ul>
    <li><strong>Randomized sampling</strong> — testing random periods, not just favourable ones.</li>
    <li><strong>Blind testing</strong> — using platform replay that hides future bars entirely.</li>
    <li><strong>Session logging</strong> — recording the conditions of each session (market state, volatility).</li>
    <li><strong>Filter validation</strong> — testing whether filters improve the strategy.</li>
    <li><strong>Cross-market testing</strong> — the same rules on 3–4 markets.</li>
    <li><strong>Period segmentation</strong> — testing 2018–2019 separately from 2021–2022.</li>
</ul>
<p>These techniques strengthen the backtest's reliability and reveal whether results are robust across conditions.</p>
<p>The most important insight: manual backtesting is not just validation — it's education. By reviewing hundreds of setups, you internalise the strategy's behaviour. You learn to recognise good and bad setups in real time. This intuition is what separates successful traders from those who follow rules mechanically.</p>
HTML,
        ],

        [
            'slug'   => 'sample-size-and-significance',
            'title'  => 'Sample Size and Statistical Significance',
            'difficulty' => 'professional',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Understand why sample size matters\n" .
                "• Determine minimum sample for reliability\n" .
                "• Recognise the statistical trap of small samples\n" .
                "• Calculate confidence intervals for expectancy",
            'prerequisites' => 'Manual Backtesting Methodology',
            'sort_order' => 3,
            'summary' => 'Small samples produce unreliable results. A strategy that wins 8 of 10 trades might have a genuine edge — or it might be luck. This lesson teaches how to determine whether your backtest sample is large enough to trust.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine flipping a coin 10 times and getting 7 heads. Does that mean the coin is biased? Not necessarily — with only 10 flips, 7 heads is common even for a fair coin. Now imagine flipping the coin 10,000 times and getting 7,000 heads. That would clearly be a biased coin.</p>
<p>Sample size matters. Small samples produce unreliable results. This applies directly to trading backtests.</p>

<h2>Real-world analogy</h2>
<p>Think of a poll. A poll of 10 people tells you little. A poll of 10,000 tells you a lot. The larger the sample, the more reliable the estimate. Backtesting works the same way.</p>

<h2>Professional explanation</h2>

<h3>Why sample size matters</h3>
<p>A backtest with 10 trades produces a win rate estimate that could be off by 30% or more. A backtest with 200 trades produces a win rate estimate within 5%.</p>
<p>The mathematics of statistics show that the reliability of an estimate scales with the square root of the sample size. To halve the error, you need 4× the sample.</p>

<h3>Visual reference — Sample size vs reliability</h3>
<svg viewBox="0 0 500 280" width="500" height="280" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Axes -->
  <line x1="60" y1="220" x2="470" y2="220" stroke="#8b93a7" stroke-width="1"/>
  <line x1="60" y1="40" x2="60" y2="220" stroke="#8b93a7" stroke-width="1"/>

  <!-- Axis labels -->
  <text x="265" y="250" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Sample Size (number of trades)</text>
  <text x="30" y="130" fill="#8b93a7" font-size="11" font-family="Inter,sans-serif" transform="rotate(-90 30 130)">Error (%)</text>

  <!-- Y labels -->
  <text x="55" y="70" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="end">30%</text>
  <text x="55" y="130" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="end">15%</text>
  <text x="55" y="190" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="end">5%</text>

  <!-- X labels -->
  <text x="100" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">10</text>
  <text x="170" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">30</text>
  <text x="240" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">50</text>
  <text x="320" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">100</text>
  <text x="400" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">200</text>
  <text x="460" y="235" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">500</text>

  <!-- Curve (error decreases with sample) -->
  <polyline points="100,60 170,120 240,150 320,175 400,192 460,200"
            fill="none" stroke="#ef4444" stroke-width="2.5"/>

  <!-- Zones -->
  <rect x="60" y="40" width="160" height="180" fill="#ef4444" fill-opacity="0.05"/>
  <text x="140" y="55" fill="#ef4444" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">UNRELIABLE</text>

  <rect x="220" y="40" width="120" height="180" fill="#eab308" fill-opacity="0.05"/>
  <text x="280" y="55" fill="#eab308" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">MODERATE</text>

  <rect x="340" y="40" width="130" height="180" fill="#4ade80" fill-opacity="0.05"/>
  <text x="405" y="55" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">RELIABLE</text>

  <!-- Target line -->
  <line x1="60" y1="190" x2="470" y2="190" stroke="#4ade80" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="470" y="185" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="end">5% error</text>
</svg>

<h3>Minimum sample size guidelines</h3>
<table>
    <thead><tr><th>Sample Size</th><th>Reliability</th><th>Use For</th></tr></thead>
    <tbody>
        <tr><td>Under 20</td><td>Unreliable</td><td>Not usable for decisions</td></tr>
        <tr><td>20–49</td><td>Very rough estimate</td><td>Initial impression only</td></tr>
        <tr><td>50–99</td><td>Rough estimate</td><td>Preliminary validation</td></tr>
        <tr><td>100–199</td><td>Reasonable estimate</td><td>Minimum for decisions</td></tr>
        <tr><td>200–499</td><td>Good estimate</td><td>Reliable validation</td></tr>
        <tr><td>500+</td><td>Very reliable</td><td>Strong statistical confidence</td></tr>
    </tbody>
</table>
<p>The commonly cited minimum is 100 trades. For high-confidence decisions, 200+ is better.</p>

<h3>Why 30 trades is not enough</h3>
<p>Many traders evaluate strategies on 30–50 trades. This is insufficient. Consider:</p>
<ul>
    <li>With a true 50% win rate, a 30-trade sample has a 10% chance of showing 60% or more wins.</li>
    <li>With a true 40% win rate, a 30-trade sample has a 5% chance of showing 55% or more wins.</li>
    <li>With a true 60% win rate, a 30-trade sample has a 10% chance of showing 45% or fewer wins.</li>
</ul>
<p>These variations are purely luck. The strategy hasn't changed — the sample is just too small to see the true rate.</p>

<h3>Visual reference — Sample size and win rate estimation</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Charts -->
  <text x="120" y="30" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">n = 30</text>
  <text x="280" y="30" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">n = 100</text>
  <text x="420" y="30" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">n = 500</text>

  <!-- Chart 1 -->
  <polyline points="40,180 80,120 120,170 160,130 200,180" fill="none" stroke="#ef4444" stroke-width="2"/>
  <text x="120" y="210" fill="#ef4444" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Wildly variable</text>

  <!-- Chart 2 -->
  <polyline points="220,170 250,150 280,140 310,145 340,135" fill="none" stroke="#eab308" stroke-width="2"/>
  <text x="280" y="210" fill="#eab308" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Moderate</text>

  <!-- Chart 3 -->
  <polyline points="380,145 400,140 420,142 440,138 460,140" fill="none" stroke="#4ade80" stroke-width="2"/>
  <text x="420" y="210" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Stable</text>

  <text x="250" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">As sample size grows, results converge on true win rate</text>
</svg>

<h3>Confidence intervals</h3>
<p>For a backtested win rate, you can calculate a confidence interval — the range in which the true win rate likely falls.</p>
<p>Approximate formula:</p>
<p><code>95% CI = p ± 1.96 × √(p × (1 − p) / n)</code></p>
<p>Where:</p>
<ul>
    <li>p = observed win rate (as decimal).</li>
    <li>n = sample size.</li>
</ul>

<h4>Examples</h4>
<ul>
    <li><strong>n = 30, win rate = 50%:</strong> CI = 50% ± 17.9% → true rate could be 32% to 68%.</li>
    <li><strong>n = 100, win rate = 50%:</strong> CI = 50% ± 9.8% → true rate could be 40% to 60%.</li>
    <li><strong>n = 500, win rate = 50%:</strong> CI = 50% ± 4.4% → true rate could be 45% to 55%.</li>
</ul>
<p>Notice how much narrower the confidence interval becomes with a larger sample. This is why small samples are unreliable.</p>

<h3>Reliability across different metrics</h3>
<p>Different metrics require different sample sizes for reliability:</p>
<ul>
    <li><strong>Win rate</strong> — reasonably reliable at 100 trades.</li>
    <li><strong>Average win/loss</strong> — needs 200+ trades due to variance in individual trade sizes.</li>
    <li><strong>Maximum drawdown</strong> — needs 500+ trades to see the full range of drawdowns.</li>
    <li><strong>Longest losing streak</strong> — needs 500+ trades; extremes are rare.</li>
</ul>
<p>This means a backtest of 100 trades tells you the win rate reliably, but doesn't tell you the worst drawdown you'll experience.</p>

<h3>Sample size for different strategies</h3>
<p>The required sample varies by strategy frequency:</p>
<ul>
    <li><strong>High-frequency strategies</strong> (scalping) — 500+ trades easily achievable; use 500+.</li>
    <li><strong>Day trading strategies</strong> — 200–500 trades; aim for 300+.</li>
    <li><strong>Swing trading strategies</strong> — 100–200 trades; use the max available.</li>
    <li><strong>Position trading strategies</strong> — 50–100 trades; still try for 100+.</li>
</ul>
<p>Lower-frequency strategies are harder to validate. For these, use multiple markets or longer periods.</p>

<h3>Visual reference — Sample size by strategy frequency</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Bars -->
  <rect x="50" y="60" width="80" height="160" fill="#4ade80" fill-opacity="0.6"/>
  <text x="90" y="45" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">500+</text>
  <text x="90" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Scalping</text>

  <rect x="150" y="90" width="80" height="130" fill="#4ade80" fill-opacity="0.5"/>
  <text x="190" y="75" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">300+</text>
  <text x="190" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Day trading</text>

  <rect x="250" y="130" width="80" height="90" fill="#eab308" fill-opacity="0.5"/>
  <text x="290" y="115" fill="#eab308" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">150+</text>
  <text x="290" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Swing</text>

  <rect x="350" y="170" width="80" height="50" fill="#f97316" fill-opacity="0.5"/>
  <text x="390" y="155" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">100+</text>
  <text x="390" y="240" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Position</text>

  <!-- Goal line -->
  <line x1="30" y1="130" x2="470" y2="130" stroke="#5b7cfa" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="475" y="134" fill="#5b7cfa" font-size="10" font-family="Inter,sans-serif" text-anchor="end">100 min</text>
</svg>

<h3>Realistic expectations from small samples</h3>
<p>Because most retail traders can't easily produce 500+ trades, they operate with less certainty. This is fine, but they must:</p>
<ul>
    <li><strong>Discount optimistic results.</strong> If a 30-trade backtest shows +1.5R expectancy, expect it to be lower in reality.</li>
    <li><strong>Focus on positive vs negative.</strong> At small samples, the key question is "positive or negative expectancy," not "exactly how much."</li>
    <li><strong>Re-test periodically.</strong> As real trades accumulate, the true expectancy emerges.</li>
    <li><strong>Adjust as data comes in.</strong> If 200 live trades show different expectancy than the backtest, update.</li>
</ul>

<h3>When to stop backtesting</h3>
<p>Stop when:</p>
<ul>
    <li>You've reached the target sample size (100+ minimum).</li>
    <li>You've covered multiple market conditions (trending and ranging).</li>
    <li>Results are consistent enough to give confidence in expectancy.</li>
    <li>Further testing is unlikely to change the conclusion.</li>
</ul>
<p>Don't stop when:</p>
<ul>
    <li>You have fewer than 50 trades.</li>
    <li>All trades came from one market condition.</li>
    <li>You're "close" to a positive result but not quite there.</li>
    <li>You want to "just test a few more" to confirm the negative result is really negative.</li>
</ul>

<h2>Factual context</h2>
<p>The mathematics of sample size and statistical significance is well-established:</p>
<p><strong>Central Limit Theorem</strong> — as sample size grows, the distribution of sample means approaches a normal distribution, regardless of the underlying distribution.</p>
<p><strong>Standard Error</strong> — decreases with the square root of sample size. To halve the error, quadruple the sample.</p>
<p><strong>Academic research</strong> — studies of trader behaviour consistently show that strategies validated on small samples fail more often than those validated on large samples.</p>
<p>Van Tharp, on the importance of sample size:</p>
<blockquote><strong>\"You cannot judge a strategy on 10 trades. You need a large enough sample to draw meaningful conclusions.\"</strong></blockquote>
<p>Tharp's point is the foundation of statistical testing. Small samples are unreliable.</p>
<p>Larry Hite, on the mathematics of trading:</p>
<blockquote><strong>\"I have two basic rules about winning in trading as well as in life: (1) If you don't bet, you can't win. (2) If you lose all your chips, you can't bet.\"</strong></blockquote>
<p>Hite's rules imply proper sample size. You can't know when to bet without sufficient data.</p>
<p>Nassim Nicholas Taleb, on the dangers of small samples:</p>
<blockquote><strong>\"The problem with small samples is that they can produce wildly misleading results. You need enough data to distinguish signal from noise.\"</strong></blockquote>
<p>Taleb's warning is essential. Small samples are noise-dominated.</p>
<p>Ed Seykota, on the value of testing:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules are simple, but validating them requires substantial testing.</p>
<p>Paul Tudor Jones, on preparation:</p>
<blockquote><strong>\"I do the work. I know the historical behaviour of my markets. Nothing is left to chance.\"</strong></blockquote>
<p>Jones' preparation includes large-sample testing. Knowledge comes from data.</p>
<p>Warren Buffett, on learning from data:</p>
<blockquote><strong>\"The best investment is yourself. The more you learn, the more you earn.\"</strong></blockquote>
<p>Buffett's point applies to sample size. More data produces better learning.</p>
<p>Mark Douglas, on probabilistic thinking:</p>
<blockquote><strong>\"The market is a probabilistic environment. Any single trade can have any outcome. The probability of a specific outcome is what matters.\"</strong></blockquote>
<p>Douglas' point requires sufficient sample to estimate probability. Small samples can't.</p>
<p>Bruce Kovner, on testing:</p>
<blockquote><strong>\"I test everything before I trade it. If it doesn't work in the test, it won't work live.\"</strong></blockquote>
<p>Kovner's testing includes large samples. Small tests are unreliable.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Testing too few trades.</strong> Under 100 is insufficient for reliable conclusions.</li>
    <li><strong>Assuming small sample is representative.</strong> Random variation dominates in small samples.</li>
    <li><strong>Making changes based on 30-trade results.</strong> Changes based on small samples usually make things worse.</li>
    <li><strong>Ignoring confidence intervals.</strong> Even a 100-trade backtest has ±10% uncertainty on win rate.</li>
    <li><strong>Not accounting for market conditions.</strong> A sample from one market regime may not represent all.</li>
    <li><strong>Over-trusting backtested performance.</strong> Backtests always overstate performance; reality is worse.</li>
    <li><strong>Not updating as live trades accumulate.</strong> Expectancy from backtest should be validated on live data.</li>
    <li><strong>Refusing to accept negative results.</strong> If 200 trades show negative expectancy, the strategy doesn't work.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional backtesting uses more sophisticated statistical methods:</p>
<ul>
    <li><strong>Confidence intervals</strong> — estimating the range of likely values.</li>
    <li><strong>Hypothesis testing</strong> — statistically testing whether expectancy is truly positive.</li>
    <li><strong>Bootstrapping</strong> — randomly resampling trades to estimate the distribution of outcomes.</li>
    <li><strong>Monte Carlo simulation</strong> — generating thousands of alternate scenarios to estimate risk.</li>
    <li><strong>Out-of-sample testing</strong> — reserving part of data to test the strategy without tuning.</li>
    <li><strong>Cross-validation</strong> — testing on multiple markets and periods to assess robustness.</li>
</ul>
<p>For most retail traders, basic sample size awareness is sufficient. Aim for 100+ trades. Understand that smaller samples overstate performance. Adjust expectations as live data accumulates.</p>
<p>The most important insight: sample size determines reliability. A great result on 20 trades means nothing. A modest result on 500 trades means a lot. Respect the mathematics.</p>
HTML,
        ],

        [
            'slug'   => 'backtesting-biases',
            'title'  => 'Backtesting Biases',
            'difficulty' => 'professional',
            'estimated_duration' => 13,
            'learning_objectives' =>
                "• Identify the five most common backtesting biases\n" .
                "• Recognise bias in your own backtests\n" .
                "• Apply debiasing techniques\n" .
                "• Interpret backtest results critically",
            'prerequisites' => 'Sample Size and Statistical Significance',
            'sort_order' => 4,
            'summary' => 'Backtesting is only useful if it is honest. Five biases systematically distort results: hindsight bias, survivorship bias, look-ahead bias, data mining bias, and overfitting. This lesson teaches you to recognise each one and apply corrections.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine judging a horse race after it's been run. You know which horse won, so your prediction seems obvious. This is hindsight bias. In trading, it makes historical trades seem easier than they were in real time.</p>
<p>Five biases systematically distort backtests. Recognising them separates honest validation from wishful thinking.</p>

<h2>Real-world analogy</h2>
<p>Think of a photographer reviewing their work. If they know which photos turned out well, they'll only show those. This is survivorship bias — showing only the successes. Backtesting has the same temptation.</p>

<h2>Professional explanation</h2>

<h3>Bias 1: Hindsight bias</h3>
<p><strong>What it is:</strong> Using knowledge of what actually happened to judge what would have been done. Every historical trade looks obvious in retrospect.</p>
<p><strong>How it affects backtests:</strong></p>
<ul>
    <li>You mark obvious highs and lows that weren't obvious at the time.</li>
    <li>You assume you would have taken trades you would have avoided.</li>
    <li>You interpret ambiguous setups as clearly bullish or bearish.</li>
    <li>You underestimate the difficulty of real-time decision-making.</li>
</ul>
<p><strong>How to correct:</strong> Use replay mode that hides future bars. Advance one bar at a time. Mark entries and exits based on the current bar only. If you can see the future, you're biased.</p>

<h3>Visual reference — Hindsight bias</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- "Real time" chart -->
  <text x="125" y="25" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Real time (partial)</text>
  <polyline points="40,150 70,120 100,140 130,110 160,130 190,100 210,120"
            fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <text x="125" y="180" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Unclear which way next</text>

  <!-- Arrow -->
  <line x1="220" y1="120" x2="260" y2="120" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="260,120 250,115 250,125" fill="#8b93a7"/>

  <!-- Hindsight chart -->
  <text x="380" y="25" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">With hindsight (full chart)</text>
  <polyline points="280,150 310,120 340,140 370,110 400,130 430,100 460,60 490,40"
            fill="none" stroke="#4ade80" stroke-width="2"/>

  <!-- Entry marker -->
  <circle cx="400" cy="130" r="6" fill="none" stroke="#4ade80" stroke-width="2"/>
  <text x="400" y="115" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Obvious buy!</text>

  <text x="250" y="210" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">In hindsight, the trade looks obvious. In real time, it wasn't.</text>
</svg>

<h3>Bias 2: Survivorship bias</h3>
<p><strong>What it is:</strong> Only studying survivors, ignoring failures. In trading, this means only testing markets, periods, or setups that "worked."</p>
<p><strong>How it affects backtests:</strong></p>
<ul>
    <li>Only testing markets where the strategy performed well.</li>
    <li>Skipping periods where the strategy failed.</li>
    <li>Ignoring trades that didn't complete (still open at end of backtest).</li>
    <li>Removing "bad" trades from the analysis.</li>
</ul>
<p><strong>How to correct:</strong> Test all markets, all periods, all setups. Include unfinished trades in the analysis. Never remove trades from the record, no matter how bad.</p>

<h3>Bias 3: Look-ahead bias</h3>
<p><strong>What it is:</strong> Using information that wasn't available at the time of the trade.</p>
<p><strong>How it affects backtests:</strong></p>
<ul>
    <li>Indicators calculated with future data (rare in manual testing, common in automated).</li>
    <li>Knowing today's close affects yesterday's decisions.</li>
    <li>Using current economic data to analyse historical trades.</li>
    <li>Assuming you'd know news before it happened.</li>
</ul>
<p><strong>How to correct:</strong> Use replay mode. Only use data available at the moment. Use lagged indicators. Assume no knowledge of future events.</p>

<h3>Bias 4: Data mining bias (p-hacking)</h3>
<p><strong>What it is:</strong> Running many tests and only reporting the ones that look good. With enough tests, random results appear significant.</p>
<p><strong>How it affects backtests:</strong></p>
<ul>
    <li>Testing 100 different parameter combinations and reporting the best one.</li>
    <li>Trying different timeframes until one works.</li>
    <li>Testing many markets and reporting only the winners.</li>
    <li>Reporting the best period while ignoring poor periods.</li>
</ul>
<p><strong>How to correct:</strong> Limit the number of tests. Use out-of-sample data. Test on multiple markets. Report all results, not just good ones. Use a validation protocol and stick to it.</p>

<h3>Bias 5: Overfitting (curve fitting)</h3>
<p><strong>What it is:</strong> Tuning strategy rules so precisely to historical data that the strategy fails in live markets. The rules fit the past, not the future.</p>
<p><strong>How it affects backtests:</strong></p>
<ul>
    <li>Adding too many rules to fit historical quirks.</li>
    <li>Optimising parameters to past data (e.g., 17 MA instead of 20).</li>
    <li>Rules that only work in specific periods.</li>
    <li>Complex strategies that fail when small inputs change.</li>
</ul>
<p><strong>How to correct:</strong> Prefer simple rules. Avoid over-optimisation. Test on out-of-sample data. Check robustness across parameter changes. If small changes break the strategy, it's overfit.</p>

<h3>Visual reference — Overfitting</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Simple fit -->
  <text x="125" y="25" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Simple model</text>
  <polyline points="40,140 70,120 100,130 130,110 160,120 190,100 220,110"
            fill="none" stroke="#4ade80" stroke-width="2.5"/>
  <text x="125" y="180" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Generalises to new data</text>

  <!-- Overfit -->
  <text x="375" y="25" fill="#ef4444" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Overfit model</text>
  <polyline points="290,140 300,100 310,150 320,80 330,155 340,85 350,150 360,90 370,155 380,95 390,150 400,100 410,150 420,105 430,140 440,120 450,140 460,125"
            fill="none" stroke="#ef4444" stroke-width="2"/>
  <text x="375" y="180" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Fits history perfectly</text>
  <text x="375" y="200" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Fails on new data</text>
</svg>

<h3>Interaction of biases</h3>
<p>These biases compound each other:</p>
<ul>
    <li><strong>Hindsight + data mining</strong> — You find the "perfect" strategy from historical data, but it only works because you looked at many options.</li>
    <li><strong>Data mining + overfitting</strong> — You test many rules, pick the ones that work best, and end up with a curve-fit strategy.</li>
    <li><strong>Survivorship + hindsight</strong> — You only test markets where the strategy looks good, and only remember trades that worked.</li>
</ul>
<p>The combined effect: a backtest that looks amazing but has no real edge.</p>

<h3>Visual reference — Bias interactions</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Center -->
  <circle cx="250" cy="130" r="50" fill="#ef4444" fill-opacity="0.2" stroke="#ef4444" stroke-width="2.5"/>
  <text x="250" y="125" fill="#ef4444" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">INFLATED</text>
  <text x="250" y="142" fill="#ef4444" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">RESULTS</text>

  <!-- Bias 1 -->
  <rect x="30" y="30" width="130" height="40" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="95" y="55" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Hindsight</text>

  <!-- Bias 2 -->
  <rect x="340" y="30" width="130" height="40" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="405" y="55" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Survivorship</text>

  <!-- Bias 3 -->
  <rect x="30" y="190" width="130" height="40" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="95" y="215" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Look-ahead</text>

  <!-- Bias 4 -->
  <rect x="340" y="190" width="130" height="40" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="405" y="215" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Data mining</text>

  <!-- Bias 5 -->
  <rect x="185" y="220" width="130" height="35" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="6"/>
  <text x="250" y="242" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Overfitting</text>

  <!-- Connecting lines -->
  <line x1="95" y1="70" x2="210" y2="110" stroke="#8b93a7" stroke-width="1" stroke-dasharray="3,2"/>
  <line x1="405" y1="70" x2="290" y2="110" stroke="#8b93a7" stroke-width="1" stroke-dasharray="3,2"/>
  <line x1="95" y1="190" x2="210" y2="150" stroke="#8b93a7" stroke-width="1" stroke-dasharray="3,2"/>
  <line x1="405" y1="190" x2="290" y2="150" stroke="#8b93a7" stroke-width="1" stroke-dasharray="3,2"/>
  <line x1="250" y1="220" x2="250" y2="180" stroke="#8b93a7" stroke-width="1" stroke-dasharray="3,2"/>
</svg>

<h3>Debiasing checklist</h3>
<p>For every backtest, verify:</p>
<ol>
    <li>Did I use replay mode or a blinded chart?</li>
    <li>Did I only use information available at the time?</li>
    <li>Did I test all markets (not just the best ones)?</li>
    <li>Did I test all periods (trending and ranging)?</li>
    <li>Did I record every trade (wins and losses)?</li>
    <li>Did I include unfinished trades?</li>
    <li>Did I limit the number of parameter tests?</li>
    <li>Did I use out-of-sample validation?</li>
    <li>Are the rules simple or complex?</li>
    <li>Do small parameter changes break the strategy?</li>
</ol>
<p>If all answers are correct, the backtest is likely debiased.</p>

<h3>Validation approaches</h3>

<h4>Out-of-sample testing</h4>
<p>Split your data into two portions:</p>
<ul>
    <li><strong>In-sample (70%)</strong> — used for developing and tuning the strategy.</li>
    <li><strong>Out-of-sample (30%)</strong> — untouched during development; used only for validation.</li>
</ul>
<p>If the strategy works on out-of-sample data, the edge is more likely genuine. If it fails out-of-sample, the in-sample results were overfit.</p>

<h4>Walk-forward analysis</h4>
<p>Test the strategy on rolling windows:</p>
<ol>
    <li>Optimise on year 1, test on year 2.</li>
    <li>Optimise on year 2, test on year 3.</li>
    <li>Continue through the data.</li>
</ol>
<p>This mimics how the strategy would have performed if deployed at each point in time.</p>

<h4>Cross-market validation</h4>
<p>Test the same rules on multiple markets:</p>
<ul>
    <li>EUR/USD, GBP/USD, USD/JPY, AUD/USD.</li>
    <li>Different indices (S&P, DAX, Nikkei).</li>
    <li>Different commodities (gold, oil).</li>
</ul>
<p>If the strategy works across all, the edge is more likely genuine. If it only works on one, it's likely overfit.</p>

<h3>Visual reference — Out-of-sample testing</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Timeline -->
  <line x1="30" y1="120" x2="470" y2="120" stroke="#8b93a7" stroke-width="1"/>

  <!-- In-sample -->
  <rect x="50" y="90" width="280" height="60" fill="#5b7cfa" fill-opacity="0.2" stroke="#5b7cfa" stroke-width="2" rx="4"/>
  <text x="190" y="115" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">IN-SAMPLE (70%)</text>
  <text x="190" y="135" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Used for developing &amp; tuning</text>

  <!-- Out-of-sample -->
  <rect x="330" y="90" width="120" height="60" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="2" rx="4"/>
  <text x="390" y="115" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">OUT-OF-SAMPLE</text>
  <text x="390" y="135" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Untouched</text>

  <!-- Result labels -->
  <text x="190" y="180" fill="#5b7cfa" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Expected: good results</text>
  <text x="390" y="180" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Real test: will it work?</text>

  <text x="250" y="215" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">If OOS results are similar to IS, strategy is validated</text>
</svg>

<h3>Red flags in backtests</h3>
<p>Warning signs that a backtest may be biased:</p>
<ul>
    <li><strong>Too-good-to-be-true results.</strong> Sharpe ratios above 3, win rates above 70%, expectancy above +2R — these are suspicious.</li>
    <li><strong>Very specific rules.</strong> Rules like "buy when RSI is between 42 and 47" suggest overfitting.</li>
    <li><strong>Perfect timing.</strong> Trades that consistently enter at exact lows or exit at exact highs.</li>
    <li><strong>Small samples.</strong> Fewer than 100 trades.</li>
    <li><strong>Short backtest period.</strong> Under 2 years.</li>
    <li><strong>No costs assumed.</strong> Zero spread, zero slippage.</li>
    <li><strong>Testing only favourable conditions.</strong> Only trending markets, only bullish periods.</li>
    <li><strong>Discretionary adjustments.</strong> Rules changed mid-test to improve results.</li>
</ul>
<p>Any of these should trigger deeper investigation.</p>

<h2>Factual context</h2>
<p>Backtesting biases have been studied extensively in quantitative finance:</p>
<p><strong>Bailey, Borwein, López de Prado, Zhu (2014)</strong> — research on backtest overfitting shows that most published strategies are overfit.</p>
<p><strong>Harvey, Liu, Zhu (2016)</strong> — study of 316 factors found that most were likely false discoveries due to data mining.</p>
<p><strong>Lo (2002)</strong> — research on the statistics of Sharpe ratios and the role of sample size.</p>
<p><strong>López de Prado (2018)</strong> — book <em>Advances in Financial Machine Learning</em> covers debiasing techniques extensively.</p>
<p>Van Tharp, on the importance of validation:</p>
<blockquote><strong>\"A strategy that hasn't been validated out-of-sample is a hypothesis, not a system.\"</strong></blockquote>
<p>Tharp's point: without proper validation, the strategy is untested.</p>
<p>Nassim Nicholas Taleb, on overfitting:</p>
<blockquote><strong>\"The problem with backtests is that they fit the past. But the past is a sample, not the population. Fitting the sample is easy; generalising to the population is hard.\"</strong></blockquote>
<p>Taleb's warning is essential. Historical data is a sample, not the universe of possibilities.</p>
<p>Ed Seykota, on robustness:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules are simple, which makes them robust. Complex rules are usually overfit.</p>
<p>Richard Dennis, on simple rules:</p>
<blockquote><strong>\"I have always maintained that one could train a group of people to be successful traders using a small set of rules.\"</strong></blockquote>
<p>Dennis' rules were simple, not curve-fit. Simplicity is a sign of robustness.</p>
<p>Warren Buffett, on honesty:</p>
<blockquote><strong>\"The most important quality for an investor is temperament, not intellect.\"</strong></blockquote>
<p>Buffett's point applies to backtesting. Honest assessment requires discipline, not intelligence.</p>
<p>Mark Douglas, on self-honesty:</p>
<blockquote><strong>\"The best traders are not afraid of being wrong. They accept the results of their testing, even when the results are negative.\"</strong></blockquote>
<p>Douglas' point: accepting negative results is essential. Denying them leads to losses.</p>
<p>Paul Tudor Jones, on realistic assessment:</p>
<blockquote><strong>\"The market doesn't care about your feelings. It rewards the traders who see it clearly.\"</strong></blockquote>
<p>Jones' point: honest assessment of backtests is required. Feelings distort perception.</p>
<p>Bruce Kovner, on testing:</p>
<blockquote><strong>\"I test everything before I trade it. If it doesn't work in the test, it won't work live.\"</strong></blockquote>
<p>Kovner's testing is rigorous. Biases would invalidate the test.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Not using replay mode.</strong> Hindsight bias dominates.</li>
    <li><strong>Only testing "good" periods.</strong> Survivorship bias.</li>
    <li><strong>Using future data.</strong> Look-ahead bias.</li>
    <li><strong>Testing many combinations and reporting the best.</strong> Data mining bias.</li>
    <li><strong>Over-optimising parameters.</strong> Overfitting.</li>
    <li><strong>Not validating out-of-sample.</strong> Results may not generalize.</li>
    <li><strong>Ignoring red flags.</strong> Too-good results are usually biased.</li>
    <li><strong>Not documenting the testing process.</strong> Without documentation, biases are invisible.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional quant traders use sophisticated debiasing techniques:</p>
<ul>
    <li><strong>Purged K-fold cross-validation</strong> — sophisticated out-of-sample technique with purging.</li>
    <li><strong>Combinatorial purged CV</strong> — generates many train-test splits for robust validation.</li>
    <li><strong>Deflated Sharpe Ratio</strong> — adjusts Sharpe for multiple testing.</li>
    <li><strong>Probability of Backtest Overfitting (PBO)</strong> — measures how likely the backtest is overfit.</li>
    <li><strong>White's Reality Check</strong> — tests whether the best of many strategies is significantly better than chance.</li>
    <li><strong>Monte Carlo permutations</strong> — randomly shuffling trade order to test robustness.</li>
</ul>
<p>For retail traders, simpler methods are sufficient:</p>
<ol>
    <li>Use replay mode always.</li>
    <li>Test all markets and periods.</li>
    <li>Aim for 100+ trades.</li>
    <li>Use out-of-sample validation.</li>
    <li>Prefer simple rules.</li>
    <li>Check robustness to small parameter changes.</li>
    <li>Be honest about negative results.</li>
</ol>
<p>The most important insight: biases are systematic. They affect everyone. Recognising them is the first step. Applying corrections is the second. Trader honesty is the third — and most important.</p>
HTML,
        ],

        [
            'slug'   => 'calculating-expectancy-from-backtests',
            'title'  => 'Calculating Expectancy from Backtests',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Calculate expectancy from backtest data\n" .
                "• Interpret expectancy in context\n" .
                "• Compare strategies using expectancy\n" .
                "• Project future returns from expectancy",
            'prerequisites' => 'Backtesting Biases',
            'sort_order' => 5,
            'summary' => 'Expectancy is the single most important metric from a backtest. It combines win rate and payoff ratio into one number that describes the average result per trade. This lesson teaches how to calculate and interpret expectancy.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine you've recorded 100 trades. Some won, some lost. Expectancy tells you the average outcome across all 100 trades. If expectancy is +0.5R, you made half your risk per trade on average. If it's +2R, you made twice your risk on average.</p>
<p>Expectancy is the number that determines whether a strategy is profitable.</p>

<h2>Real-world analogy</h2>
<p>Think of a casino. Every game has an expectancy — a small edge for the house. Over thousands of hands, the house's expectancy ensures profit. Trading works the same way. Positive expectancy means the strategy makes money over time.</p>

<h2>Professional explanation</h2>

<h3>The expectancy formula</h3>
<p><code>Expectancy = (Win Rate × Avg Win) − (Loss Rate × Avg Loss)</code></p>
<p>Where all values are expressed in R-multiples.</p>

<h3>Calculating from backtest data</h3>
<p>Let's use a real backtest example:</p>
<ul>
    <li><strong>Total trades:</strong> 142</li>
    <li><strong>Winning trades:</strong> 63</li>
    <li><strong>Losing trades:</strong> 79</li>
    <li><strong>Total R from wins:</strong> +151.2R</li>
    <li><strong>Total R from losses:</strong> −80.5R</li>
</ul>

<h4>Step 1: Win rate</h4>
<p>Win rate = 63 / 142 = 44.4%</p>
<p>Loss rate = 79 / 142 = 55.6%</p>

<h4>Step 2: Average win and loss</h4>
<p>Average win = 151.2 / 63 = +2.4R</p>
<p>Average loss = 80.5 / 79 = −1.02R (approximately −1R)</p>

<h4>Step 3: Expectancy</h4>
<p>Expectancy = (0.444 × 2.4) − (0.556 × 1.02)</p>
<p>Expectancy = 1.066 − 0.567 = +0.499R</p>
<p>The strategy produces +0.5R per trade on average.</p>

<h3>Visual reference — Expectancy calculation flow</h3>
<svg viewBox="0 0 500 320" width="500" height="320" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Row 1: Inputs -->
  <rect x="30" y="30" width="100" height="60" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="80" y="55" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">142 trades</text>
  <text x="80" y="75" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Total sample</text>

  <rect x="150" y="30" width="100" height="60" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="200" y="55" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">63 wins</text>
  <text x="200" y="75" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">44.4% win rate</text>

  <rect x="270" y="30" width="100" height="60" fill="#ef4444" fill-opacity="0.15" stroke="#ef4444" stroke-width="1.5" rx="6"/>
  <text x="320" y="55" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">79 losses</text>
  <text x="320" y="75" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">55.6% loss rate</text>

  <rect x="390" y="30" width="90" height="60" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="435" y="55" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">+151.2R</text>
  <text x="435" y="75" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Total from wins</text>

  <!-- Arrow down -->
  <line x1="250" y1="90" x2="250" y2="130" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,130 245,120 255,120" fill="#8b93a7"/>

  <!-- Middle: Averages -->
  <rect x="100" y="135" width="140" height="60" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="170" y="160" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Avg win: +2.4R</text>
  <text x="170" y="180" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">151.2 / 63</text>

  <rect x="260" y="135" width="140" height="60" fill="#ef4444" fill-opacity="0.2" stroke="#ef4444" stroke-width="2" rx="6"/>
  <text x="330" y="160" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Avg loss: −1.02R</text>
  <text x="330" y="180" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">80.5 / 79</text>

  <!-- Arrow down -->
  <line x1="250" y1="195" x2="250" y2="235" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,235 245,225 255,225" fill="#8b93a7"/>

  <!-- Formula -->
  <rect x="60" y="240" width="380" height="60" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="2" rx="6"/>
  <text x="250" y="265" fill="#f97316" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Expectancy = (0.444 × 2.4) − (0.556 × 1.02)</text>
  <text x="250" y="290" fill="#4ade80" font-size="14" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">= +0.499R per trade</text>
</svg>

<h3>Interpreting expectancy</h3>
<table>
    <thead><tr><th>Expectancy</th><th>Interpretation</th><th>Action</th></tr></thead>
    <tbody>
        <tr><td>+1R or more</td><td>Exceptional</td><td>Trade immediately; rare in practice</td></tr>
        <tr><td>+0.5R to +1R</td><td>Very good</td><td>Strong strategy</td></tr>
        <tr><td>+0.3R to +0.5R</td><td>Good</td><td>Viable strategy</td></tr>
        <tr><td>+0.1R to +0.3R</td><td>Modest</td><td>Tradeable but requires discipline</td></tr>
        <tr><td>0R to +0.1R</td><td>Marginal</td><td>Vulnerable to costs</td></tr>
        <tr><td>0R</td><td>Breakeven</td><td>Not viable after costs</td></tr>
        <tr><td>Negative</td><td>Losing</td><td>Do not trade</td></tr>
    </tbody>
</table>
<p>Most profitable strategies have expectancy between +0.2R and +0.5R. Higher expectations usually indicate overfitting or small samples.</p>

<h3>Comparing strategies using expectancy</h3>
<p>Two strategies with very different profiles can have similar expectancy:</p>

<h4>Strategy A — High win rate, low payoff</h4>
<ul>
    <li>Win rate: 70%</li>
    <li>Average win: +1R</li>
    <li>Average loss: −1.5R</li>
    <li>Expectancy: (0.70 × 1) − (0.30 × 1.5) = +0.25R</li>
</ul>

<h4>Strategy B — Low win rate, high payoff</h4>
<ul>
    <li>Win rate: 35%</li>
    <li>Average win: +3R</li>
    <li>Average loss: −1R</li>
    <li>Expectancy: (0.35 × 3) − (0.65 × 1) = +0.40R</li>
</ul>
<p>Strategy B has higher expectancy despite the lower win rate. The payoff ratio makes the difference.</p>

<h3>Visual reference — Strategy comparison by expectancy</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Strategy A -->
  <rect x="30" y="30" width="200" height="200" fill="#5b7cfa" fill-opacity="0.1" stroke="#5b7cfa" stroke-width="1.5" rx="6"/>
  <text x="130" y="55" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Strategy A</text>
  <text x="45" y="85" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">Win rate: 70%</text>
  <text x="45" y="105" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">Avg win: +1R</text>
  <text x="45" y="125" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">Avg loss: −1.5R</text>
  <line x1="45" y1="145" x2="215" y2="145" stroke="#8b93a7" stroke-width="0.5"/>
  <text x="45" y="170" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" font-weight="600">Expectancy: +0.25R</text>
  <text x="45" y="195" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">Frequent small wins</text>
  <text x="45" y="210" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">Emotionally easier</text>

  <!-- Strategy B -->
  <rect x="270" y="30" width="200" height="200" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="370" y="55" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Strategy B</text>
  <text x="285" y="85" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">Win rate: 35%</text>
  <text x="285" y="105" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">Avg win: +3R</text>
  <text x="285" y="125" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">Avg loss: −1R</text>
  <line x1="285" y1="145" x2="455" y2="145" stroke="#8b93a7" stroke-width="0.5"/>
  <text x="285" y="170" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" font-weight="600">Expectancy: +0.40R</text>
  <text x="285" y="195" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">Less frequent, larger wins</text>
  <text x="285" y="210" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">Emotionally harder</text>
</svg>

<h3>Projecting returns from expectancy</h3>
<p>Expectancy projects returns over many trades:</p>
<p><code>Expected Return = Expectancy × Trades per Period × Risk per Trade</code></p>

<h4>Worked example</h4>
<ul>
    <li>Expectancy: +0.5R</li>
    <li>Trades per year: 120</li>
    <li>Risk per trade: 1%</li>
</ul>
<p>Expected annual return = 0.5 × 120 × 0.01 = 0.60 = 60% (theoretical)</p>
<p>Discount for costs (5%) and reality (30%): ~40% realistic.</p>

<h3>Realistic projections</h3>
<p>Actual returns usually fall short of projections because:</p>
<ul>
    <li><strong>Backtest overstates.</strong> Real fills are worse than assumed.</li>
    <li><strong>Costs are underestimated.</strong> Spread, commission, slippage.</li>
    <li><strong>Emotional errors.</strong> Real trading isn't perfect execution.</li>
    <li><strong>Market changes.</strong> Conditions differ from the backtest period.</li>
    <li><strong>Drawdowns.</strong> Extended losing streaks reduce compounding.</li>
</ul>
<p>Discount backtested expectancy by 30–50% for realistic projections.</p>

<h3>Drawdown impact</h3>
<p>Even a positive-expectancy strategy produces drawdowns. The relationship between expectancy and maximum drawdown:</p>
<ul>
    <li>Higher expectancy → smaller drawdowns (relative to returns).</li>
    <li>Lower expectancy → larger drawdowns.</li>
    <li>Higher variance → larger drawdowns.</li>
</ul>
<p>A +0.5R expectancy strategy with low variance might see 10R drawdowns. A +0.5R expectancy strategy with high variance might see 30R drawdowns.</p>

<h3>Visual reference — Expectancy and drawdown</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Equity curves -->
  <text x="125" y="25" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Low variance</text>
  <polyline points="40,200 80,180 120,160 160,140 200,120 240,100"
            fill="none" stroke="#4ade80" stroke-width="2"/>

  <text x="375" y="25" fill="#f97316" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">High variance</text>
  <polyline points="280,180 310,200 340,150 370,180 400,120 430,160 460,80"
            fill="none" stroke="#f97316" stroke-width="2"/>

  <!-- Text -->
  <text x="125" y="225" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Same expectancy, smoother journey</text>
  <text x="375" y="225" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Same expectancy, rougher journey</text>
</svg>

<h3>Expectancy across different conditions</h3>
<p>Calculate expectancy for subgroups of trades:</p>
<ul>
    <li><strong>By market</strong> — does the strategy work better on EUR/USD than GBP/USD?</li>
    <li><strong>By session</strong> — better in London than NY?</li>
    <li><strong>By direction</strong> — better for longs than shorts?</li>
    <li><strong>By market state</strong> — better in trends than ranges?</li>
    <li><strong>By day of week</strong> — better on certain days?</li>
</ul>
<p>This analysis reveals where the edge is strongest, allowing specialisation.</p>

<h3>Worked example — Condition analysis</h3>
<p>Same backtest split by session:</p>
<table>
    <thead><tr><th>Session</th><th>Trades</th><th>Win Rate</th><th>Expectancy</th></tr></thead>
    <tbody>
        <tr><td>London</td><td>72</td><td>48%</td><td>+0.72R</td></tr>
        <tr><td>New York</td><td>48</td><td>40%</td><td>+0.35R</td></tr>
        <tr><td>Asian</td><td>22</td><td>32%</td><td>−0.10R</td></tr>
    </tbody>
</table>
<p>The strategy works best in London. It's marginally positive in NY. It loses money in Asia.</p>
<p><strong>Action:</strong> Focus the strategy on London, consider skipping Asia.</p>

<h3>Common interpretation mistakes</h3>
<ul>
    <li><strong>Confusing expectancy with average return.</strong> Average return is total R divided by number of trades. Expectancy weights by probability.</li>
    <li><strong>Ignoring variance.</strong> Two strategies with the same expectancy can have very different drawdowns.</li>
    <li><strong>Assuming stable expectancy.</strong> Market conditions change. Expectancy from the past may not persist.</li>
    <li><strong>Over-optimising.</strong> Adding rules to maximize expectancy produces overfitting.</li>
    <li><strong>Not accounting for costs.</strong> Gross expectancy minus costs equals net expectancy.</li>
    <li><strong>Ignoring the sample size issue.</strong> Small samples give unreliable expectancy estimates.</li>
    <li><strong>Chasing higher expectancy.</strong> Higher expectancy often comes with higher variance and lower sample reliability.</li>
</ul>

<h2>Factual context</h2>
<p>Expectancy is central to system evaluation in professional trading:</p>
<p><strong>Van Tharp</strong> — introduced expectancy to systematic traders as the primary metric for evaluating systems.</p>
<p><strong>Ralph Vince</strong> — extended expectancy analysis with optimal f and portfolio-level metrics.</p>
<p><strong>Ed Thorp</strong> — used expectancy calculations in both blackjack and hedge fund management.</p>
<p><strong>Modern quant funds</strong> — use expectancy-based analysis combined with risk metrics (Sharpe, Sortino).</p>
<p>Van Tharp, on expectancy:</p>
<blockquote><strong>\"Expectancy is the single most important number in trading. It tells you how much you can expect to make per trade, on average.\"</strong></blockquote>
<p>Tharp's point: expectancy determines profitability. Without positive expectancy, no strategy works.</p>
<p>Ed Thorp, on the value of expectancy:</p>
<blockquote><strong>\"The key to long-term success is positive expectancy. Everything else is secondary.\"</strong></blockquote>
<p>Thorp's framework is based entirely on expectancy. Positive expectancy + proper position sizing = long-term wealth.</p>
<p>Larry Hite, on metrics that matter:</p>
<blockquote><strong>\"The best traders are not the ones who win the most trades. They are the ones who make the most money per trade. That is expectancy.\"</strong></blockquote>
<p>Hite's point: win rate alone is meaningless. Expectancy is the metric that matters.</p>
<p>Ed Seykota, on the essence:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules produce positive expectancy when followed. Cutting losses limits negative R; riding winners maximizes positive R.</p>
<p>Paul Tudor Jones, on what matters:</p>
<blockquote><strong>\"I want to make money per trade. Not on every trade — on average. That's expectancy.\"</strong></blockquote>
<p>Jones' point: expectancy is the goal. Individual trades vary; the average produces profit.</p>
<p>Mark Douglas, on thinking in probabilities:</p>
<blockquote><strong>\"The market is a probabilistic environment. The probability of a specific outcome is what matters, not the outcome itself.\"</strong></blockquote>
<p>Douglas' point: expectancy describes those probabilities. Individual trade outcomes are irrelevant; the average matters.</p>
<p>Warren Buffett, on process:</p>
<blockquote><strong>\"You don't need to be a rocket scientist. You need a sound process and the discipline to follow it.\"</strong></blockquote>
<p>Buffett's process produces positive expectancy. Following it consistently is the discipline.</p>
<p>Bruce Kovner, on validation:</p>
<blockquote><strong>\"I test everything before I trade it. If it doesn't work in the test, it won't work live.\"</strong></blockquote>
<p>Kovner's testing produces expectancy estimates. Positive expectancy precedes live trading.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Confusing expectancy with win rate.</strong> Win rate alone doesn't determine profitability.</li>
    <li><strong>Not deducting costs.</strong> Gross expectancy minus costs equals net expectancy.</li>
    <li><strong>Ignoring variance.</strong> Two strategies with the same expectancy may have very different drawdowns.</li>
    <li><strong>Over-optimising.</strong> Chasing higher expectancy often produces overfitting.</li>
    <li><strong>Not segmenting by condition.</strong> Overall expectancy hides session, market, and directional differences.</li>
    <li><strong>Assuming stable expectancy.</strong> Market changes affect expectancy.</li>
    <li><strong>Trusting small samples.</strong> Small samples give unreliable expectancy estimates.</li>
    <li><strong>Not projecting realistically.</strong> Backtested expectancy is usually 30–50% higher than live performance.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use additional metrics alongside expectancy:</p>
<ul>
    <li><strong>Profit factor</strong> — total profits divided by total losses (above 1.5 is good).</li>
    <li><strong>Sharpe ratio</strong> — return per unit of total risk.</li>
    <li><strong>Sortino ratio</strong> — return per unit of downside risk.</li>
    <li><strong>MAR ratio</strong> — annual return divided by maximum drawdown.</li>
    <li><strong>Recovery factor</strong> — total return divided by maximum drawdown.</li>
    <li><strong>Calmar ratio</strong> — similar to MAR.</li>
</ul>
<p>These metrics provide complementary views of performance. Expectancy tells you the average trade; the others tell you about the risk profile.</p>
<p>The most important insight: expectancy is necessary but not sufficient. A strategy with +0.5R expectancy but extreme variance may still produce unacceptable drawdowns. A strategy with +0.3R expectancy but low variance may be more tradeable in practice.</p>
<p>Evaluate expectancy alongside variance and drawdown. The best strategy has positive expectancy and manageable drawdowns.</p>
HTML,
        ],

        [
            'slug'   => 'validating-your-backtest',
            'title'  => 'Validating Your Backtest',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Apply out-of-sample testing\n" .
                "• Conduct walk-forward analysis\n" .
                "• Check robustness to parameter changes\n" .
                "• Decide whether to proceed to live trading",
            'prerequisites' => 'Calculating Expectancy from Backtests',
            'sort_order' => 6,
            'summary' => 'A single backtest is not enough. Validation requires out-of-sample testing, walk-forward analysis, and robustness checks. This lesson teaches the techniques that separate genuinely validated strategies from overfit ones.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a student who studies only the exact questions on the exam. They score perfectly, but haven't learned the subject. A strategy that's only tested on the same data it was developed on is the same — it scores perfectly but has no real edge.</p>
<p>Validation tests the strategy on data it hasn't seen before. If it works, the edge is real. If it fails, the strategy was memorising, not learning.</p>

<h2>Real-world analogy</h2>
<p>Think of a chef who develops a recipe. They cook it for themselves, tweaking ingredients until it's perfect. But the real test is serving it to customers who haven't tasted it before. Validation is serving the strategy to fresh data.</p>

<h2>Professional explanation</h2>

<h3>The validation framework</h3>
<p>Validation involves three steps:</p>
<ol>
    <li><strong>In-sample testing</strong> — develop and tune the strategy on historical data.</li>
    <li><strong>Out-of-sample testing</strong> — test on data the strategy hasn't seen.</li>
    <li><strong>Walk-forward analysis</strong> — test on rolling windows to simulate live deployment.</li>
</ol>

<h3>Visual reference — Validation framework</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- In-sample -->
  <rect x="30" y="40" width="200" height="80" fill="#5b7cfa" fill-opacity="0.2" stroke="#5b7cfa" stroke-width="2" rx="6"/>
  <text x="130" y="65" fill="#5b7cfa" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">IN-SAMPLE</text>
  <text x="130" y="85" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Develop &amp; tune rules</text>
  <text x="130" y="105" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">70% of data</text>

  <!-- Arrow -->
  <line x1="230" y1="80" x2="270" y2="80" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="270,80 260,75 260,85" fill="#8b93a7"/>

  <!-- Out-of-sample -->
  <rect x="270" y="40" width="200" height="80" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="370" y="65" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">OUT-OF-SAMPLE</text>
  <text x="370" y="85" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Test untouched rules</text>
  <text x="370" y="105" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">30% of data</text>

  <!-- Decision -->
  <line x1="250" y1="120" x2="250" y2="160" stroke="#8b93a7" stroke-width="2"/>
  <polygon points="250,160 245,150 255,150" fill="#8b93a7"/>

  <!-- Decision box -->
  <rect x="100" y="165" width="300" height="70" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="2" rx="6"/>
  <text x="250" y="190" fill="#f97316" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">RESULTS COMPARABLE?</text>

  <!-- Yes path -->
  <text x="150" y="215" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">YES → Proceed</text>
  <text x="350" y="215" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">NO → Reject or refine</text>
</svg>

<h3>Out-of-sample testing</h3>
<p>Split your historical data:</p>
<ul>
    <li><strong>In-sample (70%)</strong> — used for developing the strategy.</li>
    <li><strong>Out-of-sample (30%)</strong> — not used until the strategy is finished.</li>
</ul>

<h4>The rule</h4>
<p>Never touch the out-of-sample data during development. If you tune the strategy based on out-of-sample results, it's no longer out-of-sample.</p>

<h4>The test</h4>
<p>After developing on the in-sample data, run the strategy on out-of-sample data. The results should be similar. If out-of-sample expectancy is significantly worse (e.g., positive in-sample, negative out-of-sample), the strategy was overfit.</p>

<h4>Acceptable differences</h4>
<ul>
    <li><strong>Similar expectancy</strong> — ideal.</li>
    <li><strong>Slightly worse OOS</strong> — normal; some overfitting always occurs.</li>
    <li><strong>Much worse OOS</strong> — strategy is overfit; reject.</li>
    <li><strong>Negative OOS</strong> — strategy has no edge; reject.</li>
</ul>

<h3>Walk-forward analysis</h3>
<p>An alternative to in-sample/out-of-sample split:</p>
<ol>
    <li>Optimise on Year 1, test on Year 2.</li>
    <li>Optimise on Year 2, test on Year 3.</li>
    <li>Optimise on Year 3, test on Year 4.</li>
    <li>Continue through available data.</li>
</ol>
<p>This mimics how the strategy would have been deployed in real time. Results from all test periods are combined for overall performance.</p>

<h4>Advantages</h4>
<ul>
    <li>Uses all data for both training and testing.</li>
    <li>Simulates real-time deployment.</li>
    <li>Reveals whether the strategy adapts to changing conditions.</li>
</ul>

<h4>Disadvantages</h4>
<ul>
    <li>Requires more historical data.</li>
    <li>Complex to set up manually.</li>
    <li>Results may still be optimistic if rules are retuned too often.</li>
</ul>

<h3>Visual reference — Walk-forward analysis</h3>
<svg viewBox="0 0 500 280" width="500" height="280" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Timeline -->
  <line x1="30" y1="40" x2="470" y2="40" stroke="#8b93a7" stroke-width="1"/>
  <text x="60" y="30" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">2018</text>
  <text x="140" y="30" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">2019</text>
  <text x="220" y="30" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">2020</text>
  <text x="300" y="30" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">2021</text>
  <text x="380" y="30" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">2022</text>
  <text x="450" y="30" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif">2023</text>

  <!-- Row 1 -->
  <rect x="30" y="55" width="80" height="25" fill="#5b7cfa" fill-opacity="0.4"/>
  <text x="70" y="72" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Train</text>
  <rect x="110" y="55" width="80" height="25" fill="#4ade80" fill-opacity="0.4"/>
  <text x="150" y="72" fill="#4ade80" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Test</text>

  <!-- Row 2 -->
  <rect x="110" y="90" width="80" height="25" fill="#5b7cfa" fill-opacity="0.4"/>
  <text x="150" y="107" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Train</text>
  <rect x="190" y="90" width="80" height="25" fill="#4ade80" fill-opacity="0.4"/>
  <text x="230" y="107" fill="#4ade80" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Test</text>

  <!-- Row 3 -->
  <rect x="190" y="125" width="80" height="25" fill="#5b7cfa" fill-opacity="0.4"/>
  <text x="230" y="142" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Train</text>
  <rect x="270" y="125" width="80" height="25" fill="#4ade80" fill-opacity="0.4"/>
  <text x="310" y="142" fill="#4ade80" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Test</text>

  <!-- Row 4 -->
  <rect x="270" y="160" width="80" height="25" fill="#5b7cfa" fill-opacity="0.4"/>
  <text x="310" y="177" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Train</text>
  <rect x="350" y="160" width="80" height="25" fill="#4ade80" fill-opacity="0.4"/>
  <text x="390" y="177" fill="#4ade80" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Test</text>

  <!-- Row 5 -->
  <rect x="350" y="195" width="80" height="25" fill="#5b7cfa" fill-opacity="0.4"/>
  <text x="390" y="212" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Train</text>
  <rect x="430" y="195" width="40" height="25" fill="#4ade80" fill-opacity="0.4"/>
  <text x="450" y="212" fill="#4ade80" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Test</text>

  <!-- Note -->
  <text x="250" y="260" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Combined test results = simulated live performance</text>
</svg>

<h3>Robustness checks</h3>
<p>A strategy should be robust — small changes in inputs should not break it:</p>

<h4>Parameter sensitivity</h4>
<p>Change one parameter slightly and see if the results hold:</p>
<ul>
    <li>20 MA → 19 or 21 MA.</li>
    <li>RSI 30 → RSI 28 or 32.</li>
    <li>ATR multiplier 2.0 → 1.8 or 2.2.</li>
</ul>
<p>If small changes produce large result differences, the strategy is overfit. If results are similar, the strategy is robust.</p>

<h4>Timeframe sensitivity</h4>
<p>Test the strategy on nearby timeframes:</p>
<ul>
    <li>H4 strategy → test on H1 and Daily.</li>
    <li>Daily strategy → test on H4 and Weekly.</li>
</ul>
<p>Similar results across timeframes suggest robustness. Very different results suggest overfitting to the specific timeframe.</p>

<h4>Market sensitivity</h4>
<p>Test on multiple markets:</p>
<ul>
    <li>If the strategy works on EUR/USD, does it work on GBP/USD, USD/JPY, AUD/USD?</li>
    <li>If it works on FX, does it work on indices or commodities?</li>
</ul>
<p>Broader applicability suggests a real edge. Narrow applicability suggests overfitting.</p>

<h3>Visual reference — Robustness checks</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Robust strategy -->
  <text x="125" y="25" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Robust</text>
  <polyline points="40,130 70,120 100,125 130,115 160,120 190,110 220,115"
            fill="none" stroke="#4ade80" stroke-width="2.5"/>
  <text x="125" y="180" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Small parameter changes</text>
  <text x="125" y="195" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Small result changes</text>

  <!-- Overfit strategy -->
  <text x="375" y="25" fill="#ef4444" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Overfit</text>
  <polyline points="280,180 310,120 330,60 350,130 370,150 390,100 410,80 430,140 460,120"
            fill="none" stroke="#ef4444" stroke-width="2"/>
  <text x="375" y="210" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Small parameter changes</text>
  <text x="375" y="225" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Large result changes</text>
</svg>

<h3>Decision framework</h3>
<p>After validation, decide whether to proceed:</p>

<h4>Proceed to live if:</h4>
<ul>
    <li>Positive expectancy in-sample.</li>
    <li>Positive expectancy out-of-sample.</li>
    <li>Walk-forward results are positive.</li>
    <li>Results are robust to small parameter changes.</li>
    <li>Results are consistent across markets.</li>
    <li>Sample size is adequate (100+ trades).</li>
</ul>

<h4>Refine if:</h4>
<ul>
    <li>In-sample positive, out-of-sample negative.</li>
    <li>Results are sensitive to parameter changes.</li>
    <li>Some markets work, others don't.</li>
    <li>One condition dominates (e.g., only trending markets).</li>
</ul>

<h4>Reject if:</h4>
<ul>
    <li>Negative expectancy across all tests.</li>
    <li>Only works with very specific parameters.</li>
    <li>Fails on out-of-sample and walk-forward.</li>
    <li>Fundamental logic is questionable.</li>
</ul>

<h3>Forward testing after validation</h3>
<p>After backtest validation, the strategy moves to forward testing (demo trading):</p>
<ul>
    <li><strong>30+ live demo trades</strong> — confirm results match backtest.</li>
    <li><strong>No rule changes during forward test</strong> — consistency is essential.</li>
    <li><strong>Compare metrics</strong> — win rate, expectancy, drawdown.</li>
    <li><strong>If results match</strong> — proceed to small live size.</li>
    <li><strong>If results differ significantly</strong> — investigate.</li>
</ul>
<p>Forward testing is covered in detail in the next module.</p>

<h2>Factual context</h2>
<p>Backtest validation is central to professional quantitative finance:</p>
<p><strong>López de Prado (2018)</strong> — developed the Combinatorial Purged Cross-Validation (CPCV) method.</p>
<p><strong>Bailey et al. (2014)</strong> — developed the Deflated Sharpe Ratio for multiple-testing bias.</p>
<p><strong>Harvey and Liu (2015)</strong> — research on multiple testing and the "backtesting protocol."</p>
<p><strong>Practitioner books</strong> — Ernie Chan, Marcos López de Prado, and others cover validation extensively.</p>
<p>Van Tharp, on validation:</p>
<blockquote><strong>\"A strategy that hasn't been validated out-of-sample is a hypothesis, not a system.\"</strong></blockquote>
<p>Tharp's point: validation is what turns a hypothesis into a strategy.</p>
<p>Ed Seykota, on testing:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules have survived decades of testing. They're robust across markets and conditions.</p>
<p>Nassim Nicholas Taleb, on out-of-sample:</p>
<blockquote><strong>\"The only test that matters is the one that hasn't happened yet. If the strategy works on new data, it might work in the future.\"</strong></blockquote>
<p>Taleb's point: out-of-sample data is the closest we can get to future data.</p>
<p>Mark Douglas, on honesty:</p>
<blockquote><strong>\"The best traders are not afraid of being wrong. They accept the results of their testing, even when the results are negative.\"</strong></blockquote>
<p>Douglas' point: honest validation requires accepting negative results.</p>
<p>Warren Buffett, on learning:</p>
<blockquote><strong>\"The best investment is yourself. The more you learn, the more you earn.\"</strong></blockquote>
<p>Buffett's point: validation is learning. Learning reduces future losses.</p>
<p>Paul Tudor Jones, on preparation:</p>
<blockquote><strong>\"I do the work. I know the historical behaviour of my markets. Nothing is left to chance.\"</strong></blockquote>
<p>Jones' preparation includes validation. Knowing historical behaviour requires thorough testing.</p>
<p>Bruce Kovner, on testing:</p>
<blockquote><strong>\"I test everything before I trade it. If it doesn't work in the test, it won't work live.\"</strong></blockquote>
<p>Kovner's testing includes validation. Simple testing isn't enough; rigorous validation is required.</p>
<p>Richard Dennis, on the Turtle experiment:</p>
<blockquote><strong>\"I have always maintained that one could train a group of people to be successful traders using a small set of rules.\"</strong></blockquote>
<p>Dennis' confidence came from validation. His rules worked on historical data AND on new data during the experiment.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Not using out-of-sample data.</strong> Without it, overfitting is invisible.</li>
    <li><strong>Tuning on out-of-sample data.</strong> This destroys its purpose.</li>
    <li><strong>Skipping walk-forward analysis.</strong> It's the closest proxy for live deployment.</li>
    <li><strong>Not checking robustness.</strong> Fragile strategies fail in live conditions.</li>
    <li><strong>Proceeding with negative results.</strong> Hope is not a strategy.</li>
    <li><strong>Ignoring the decision framework.</strong> Without clear criteria, decisions are emotional.</li>
    <li><strong>Validating only on favourable markets.</strong> Cross-market testing is essential.</li>
    <li><strong>Over-interpreting small differences.</strong> Small OOS differences are normal; large ones are concerning.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional validation uses sophisticated techniques:</p>
<ul>
    <li><strong>Combinatorial Purged Cross-Validation (CPCV)</strong> — many train/test splits with purging.</li>
    <li><strong>Deflated Sharpe Ratio</strong> — adjusts Sharpe for multiple testing.</li>
    <li><strong>Probability of Backtest Overfitting (PBO)</strong> — measures overfitting probability.</li>
    <li><strong>Reality Check</strong> — tests whether best strategy is significant.</li>
    <li><strong>Monte Carlo permutations</strong> — tests robustness to trade order.</li>
</ul>
<p>For retail traders, simpler methods are sufficient:</p>
<ol>
    <li>Split data into in-sample and out-of-sample.</li>
    <li>Test parameter sensitivity.</li>
    <li>Test across multiple markets.</li>
    <li>Validate the OOS results are comparable.</li>
    <li>Proceed to forward testing if validated.</li>
</ol>
<p>The most important insight: validation is about honesty. Anyone can build a strategy that works on historical data. The challenge is building a strategy that works on data it hasn't seen — and that's what separates real strategies from overfit ones.</p>
HTML,
        ],

        [
            'slug'   => 'refining-based-on-backtest-data',
            'title'  => 'Refining Based on Backtest Data',
            'difficulty' => 'professional',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Extract insights from backtest results\n" .
                "• Distinguish real signals from noise\n" .
                "• Refine without overfitting\n" .
                "• Document changes systematically",
            'prerequisites' => 'Validating Your Backtest',
            'sort_order' => 7,
            'summary' => 'After a validated backtest, the strategy can be refined based on observed data. But refinement must be careful — over-refinement creates overfitting. This lesson teaches how to extract real insights from backtest results and how to apply them without destroying the strategy.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine you've finished your backtest. You've identified strengths and weaknesses. Now you want to improve the strategy. But how do you know what to change without breaking the strategy?</p>
<p>Refinement is the careful process of improving a strategy based on data. It requires discipline to avoid the temptation to "fix" every problem.</p>

<h2>Real-world analogy</h2>
<p>Think of a chef receiving customer feedback. Some feedback is meaningful (too salty), some isn't (one customer was in a bad mood). The chef must distinguish real signals from noise. Trading refinement works the same way.</p>

<h2>Professional explanation</h2>

<h3>What to look for in backtest data</h3>
<p>Analyse these aspects of your backtest:</p>

<h4>1. Overall expectancy</h4>
<ul>
    <li>Positive and stable — proceed.</li>
    <li>Positive but variable — investigate conditions.</li>
    <li>Negative — reject or overhaul.</li>
</ul>

<h4>2. Win rate and payoff</h4>
<ul>
    <li>Both within expectations — good.</li>
    <li>One significantly different — investigate.</li>
    <li>Low win rate with high payoff — trend strategy.</li>
    <li>High win rate with low payoff — range strategy.</li>
</ul>

<h4>3. Maximum drawdown</h4>
<ul>
    <li>Within tolerable range — good.</li>
    <li>Larger than expected — reduce risk or tighten rules.</li>
    <li>Very large — the strategy may not be tradeable.</li>
</ul>

<h4>4. Longest losing streak</h4>
<ul>
    <li>Short (3–5) — psychologically manageable.</li>
    <li>Moderate (6–8) — requires discipline.</li>
    <li>Long (9+) — challenging psychologically.</li>
</ul>

<h4>5. Performance by condition</h4>
<ul>
    <li>Consistent across conditions — robust.</li>
    <li>Varies by condition — opportunity to specialise.</li>
    <li>Only works in one condition — fragile.</li>
</ul>

<h4>6. Performance by market</h4>
<ul>
    <li>Consistent across markets — robust.</li>
    <li>Works on some, not others — the strategy may have market-specific characteristics.</li>
    <li>Works only on one — possibly overfit.</li>
</ul>

<h3>Visual reference — Backtest analysis dashboard</h3>
<svg viewBox="0 0 500 320" width="500" height="320" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Header -->
  <text x="250" y="25" fill="#e6e9ef" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">BACKTEST ANALYSIS DASHBOARD</text>

  <!-- Row 1 -->
  <rect x="30" y="50" width="100" height="60" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="80" y="70" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Expectancy</text>
  <text x="80" y="95" fill="#4ade80" font-size="16" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">+0.42R</text>

  <rect x="140" y="50" width="100" height="60" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="190" y="70" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Win Rate</text>
  <text x="190" y="95" fill="#4ade80" font-size="16" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">44%</text>

  <rect x="250" y="50" width="100" height="60" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="300" y="70" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Payoff</text>
  <text x="300" y="95" fill="#4ade80" font-size="16" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">2.4:1</text>

  <rect x="360" y="50" width="110" height="60" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1" rx="4"/>
  <text x="415" y="70" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Max DD</text>
  <text x="415" y="95" fill="#f97316" font-size="16" font-family="Inter,sans-serif" text-anchor="middle" font-weight="700">−14R</text>

  <!-- Row 2 -->
  <rect x="30" y="130" width="210" height="80" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1" rx="4"/>
  <text x="135" y="150" fill="#5b7cfa" font-size="10" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">By Session</text>
  <text x="45" y="172" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">London: +0.72R</text>
  <text x="45" y="188" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">New York: +0.35R</text>
  <text x="45" y="204" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">Asian: −0.10R</text>

  <rect x="250" y="130" width="220" height="80" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1" rx="4"/>
  <text x="360" y="150" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">By Market</text>
  <text x="265" y="172" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">EUR/USD: +0.51R</text>
  <text x="265" y="188" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">GBP/USD: +0.44R</text>
  <text x="265" y="204" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">USD/JPY: +0.38R</text>

  <!-- Row 3 -->
  <rect x="30" y="230" width="440" height="70" fill="#f97316" fill-opacity="0.1" stroke="#f97316" stroke-width="1" rx="4"/>
  <text x="250" y="250" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">INSIGHT</text>
  <text x="250" y="270" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Strategy works best in London session, across all FX pairs.</text>
  <text x="250" y="288" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Consider skipping Asian session; continue testing NY.</text>
</svg>

<h3>Types of refinements</h3>

<h4>1. Filter refinement</h4>
<p>Adding or removing filters based on data:</p>
<ul>
    <li><strong>Add filter</strong> — if trades in a specific condition underperform.</li>
    <li><strong>Remove filter</strong> — if a filter doesn't improve results.</li>
    <li><strong>Modify filter</strong> — if the current threshold seems suboptimal.</li>
</ul>

<h4>2. Stop-loss refinement</h4>
<ul>
    <li><strong>Tighten stops</strong> — if many trades go against you before hitting stops.</li>
    <li><strong>Widen stops</strong> — if trades often get stopped out then reverse.</li>
    <li><strong>Use ATR-based stops</strong> — if fixed stops don't adapt to volatility.</li>
</ul>

<h4>3. Target refinement</h4>
<ul>
    <li><strong>Closer targets</strong> — if many trades reverse just before target.</li>
    <li><strong>Further targets</strong> — if trades frequently exceed targets.</li>
    <li><strong>Multiple targets</strong> — taking partials at different levels.</li>
</ul>

<h4>4. Entry refinement</h4>
<ul>
    <li><strong>Additional confirmation</strong> — if entries are too early.</li>
    <li><strong>Earlier entries</strong> — if you're missing good setups.</li>
    <li><strong>Better location</strong> — if entries are in the wrong zones.</li>
</ul>

<h4>5. Position sizing refinement</h4>
<ul>
    <li><strong>Scale by conviction</strong> — larger positions on higher-quality setups.</li>
    <li><strong>Scale by volatility</strong> — ATR-based sizing.</li>
    <li><strong>Scale by market</strong> — different sizes for different markets.</li>
</ul>

<h3>The overfitting danger</h3>
<p>Every refinement risks overfitting. The more adjustments you make based on historical data, the more likely you're fitting the past rather than finding real edges.</p>

<h3>Visual reference — Overfitting spectrum</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Spectrum bar -->
  <defs>
    <linearGradient id="overfitGradient" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#4ade80"/>
      <stop offset="50%" style="stop-color:#eab308"/>
      <stop offset="100%" style="stop-color:#ef4444"/>
    </linearGradient>
  </defs>
  <rect x="50" y="100" width="400" height="30" fill="url(#overfitGradient)" rx="4"/>

  <text x="75" y="90" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">NO REFINEMENT</text>
  <text x="250" y="90" fill="#eab308" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">BALANCED</text>
  <text x="425" y="90" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">OVERFIT</text>

  <text x="75" y="155" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Rigid, misses</text>
  <text x="75" y="170" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">opportunities</text>

  <text x="250" y="155" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Adapts to real</text>
  <text x="250" y="170" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">patterns</text>

  <text x="425" y="155" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Fits noise,</text>
  <text x="425" y="170" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">fails live</text>

  <text x="250" y="210" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Goal: refine only when changes are robust across tests</text>
</svg>

<h3>Rules for safe refinement</h3>
<ol>
    <li><strong>Wait for sufficient sample.</strong> Refine after 100+ trades, not 20.</li>
    <li><strong>Identify the pattern.</strong> Was the issue consistent across multiple markets and periods?</li>
    <li><strong>Test the change.</strong> Does the refinement improve results in-sample?</li>
    <li><strong>Validate out-of-sample.</strong> Does the refinement improve out-of-sample results too?</li>
    <li><strong>Check robustness.</strong> Does the refinement survive small parameter changes?</li>
    <li><strong>Keep the change simple.</strong> Avoid complex rules that only fit specific conditions.</li>
    <li><strong>Document the change.</strong> Record why the refinement was made and its impact.</li>
    <li><strong>Continue monitoring.</strong> Even after refinement, watch for degradation.</li>
</ol>

<h3>Specific refinements that usually work</h3>

<h4>1. Session filtering</h4>
<p>If backtest shows consistent underperformance in a specific session, exclude that session.</p>
<p><strong>Example:</strong> Strategy works in London and NY, but loses in Asia. Skip Asian session trades.</p>
<p><strong>Why safe:</strong> Session effects are well-documented. Institutional activity is genuinely concentrated.</p>

<h4>2. Trend filter</h4>
<p>If backtest shows the strategy fails in ranging markets, add a trend filter.</p>
<p><strong>Example:</strong> Only take setups when ADX &gt; 25.</p>
<p><strong>Why safe:</strong> Trend-following strategies require trends. This isn't overfitting — it's logic.</p>

<h4>3. Volatility filter</h4>
<p>If backtest shows the strategy fails during high volatility, add a volatility filter.</p>
<p><strong>Example:</strong> Only trade when ATR is within normal range.</p>
<p><strong>Why safe:</strong> High volatility often produces whipsaws. Filtering makes logical sense.</p>

<h4>4. Correlation filter</h4>
<p>If backtest shows simultaneous losses on correlated pairs, add a correlation filter.</p>
<p><strong>Example:</strong> Only one USD-direction trade open at a time.</p>
<p><strong>Why safe:</strong> Correlation effects are documented. The filter prevents concentrated risk.</p>

<h3>Specific refinements that usually overfit</h3>

<h4>1. Optimising exact thresholds</h4>
<p>Changing RSI from 30 to 28 or 32 based on which produces better results. This is fine-tuning to historical noise.</p>

<h4>2. Adding specific date ranges</h4>
<p>Excluding certain weeks or months because the strategy didn't work then. This is data mining.</p>

<h4>3. Complex multi-condition entries</h4>
<p>Adding conditions like "RSI below 40 AND MACD positive AND price above 50 MA AND volume above average" because it produces better results.</p>
<p><strong>Why dangerous:</strong> The more conditions, the more likely you're fitting noise.</p>

<h4>4. Adjusting stops to the pip</h4>
<p>Setting stops at 27 pips instead of 30 because backtest showed 27 works best. The 3-pip difference is noise.</p>

<h3>Visual reference — Safe vs unsafe refinements</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Safe refinements -->
  <rect x="30" y="30" width="220" height="200" fill="#4ade80" fill-opacity="0.1" stroke="#4ade80" stroke-width="1.5" rx="6"/>
  <text x="140" y="55" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">SAFE REFINEMENTS</text>
  <text x="45" y="85" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Session filters</text>
  <text x="45" y="105" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Trend filters</text>
  <text x="45" y="125" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Volatility filters</text>
  <text x="45" y="145" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Correlation filters</text>
  <text x="45" y="165" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Broad market selection</text>
  <text x="45" y="185" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✓ Adding one condition</text>
  <text x="45" y="205" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" font-style="italic">Logic-based changes</text>

  <!-- Unsafe refinements -->
  <rect x="260" y="30" width="210" height="200" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="1.5" rx="6"/>
  <text x="365" y="55" fill="#ef4444" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">UNSAFE (OVERFIT)</text>
  <text x="275" y="85" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✗ Pip-perfect stops</text>
  <text x="275" y="105" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✗ Exact indicator values</text>
  <text x="275" y="125" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✗ Specific date exclusions</text>
  <text x="275" y="145" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✗ Multi-condition rules</text>
  <text x="275" y="165" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✗ Optimised parameters</text>
  <text x="275" y="185" fill="#e6e9ef" font-size="10" font-family="Inter,sans-serif">✗ Market cherry-picking</text>
  <text x="275" y="205" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" font-style="italic">Noise-fitting changes</text>
</svg>

<h3>Documenting refinements</h3>
<p>Every refinement should be documented:</p>
<ul>
    <li><strong>Date of change</strong></li>
    <li><strong>What was changed</strong></li>
    <li><strong>Why it was changed</strong> (evidence from backtest)</li>
    <li><strong>Expected impact</strong></li>
    <li><strong>Actual impact</strong> (after re-testing)</li>
    <li><strong>Which data was used</strong> (in-sample, out-of-sample)</li>
</ul>
<p>This log allows you to review changes and understand their effects.</p>

<h3>When to stop refining</h3>
<p>Refinement has diminishing returns. Stop when:</p>
<ul>
    <li>Further changes produce small improvements.</li>
    <li>Changes require increasingly specific rules.</li>
    <li>Out-of-sample results aren't improving.</li>
    <li>You're spending more time refining than trading.</li>
    <li>The strategy is "good enough" — remember, perfect doesn't exist.</li>
</ul>
<p>A good-enough strategy applied consistently beats a perfect strategy that's never traded.</p>

<h3>Worked example — Refinement process</h3>
<p>Let's walk through a real refinement:</p>

<h4>Initial backtest</h4>
<ul>
    <li>142 trades across EUR/USD, GBP/USD, USD/JPY.</li>
    <li>Expectancy: +0.42R overall.</li>
    <li>Session breakdown: London +0.72R, NY +0.35R, Asia −0.10R.</li>
</ul>

<h4>Analysis</h4>
<p>Asian session is consistently negative. Is this a real pattern or noise?</p>
<p>Check:
</p>
<ul>
    <li>Consistency across markets? Yes, negative for all three pairs.</li>
    <li>Consistency across periods? Yes, negative for 2018, 2019, 2020.</li>
    <li>Sample size? 22 Asian trades — small but consistent.</li>
</ul>
<p>The pattern is likely real.</p>

<h4>Refinement</h4>
<p>Add session filter: only trade during London or NY sessions.</p>

<h4>Re-test</h4>
<p>Remove Asian trades from the sample: 120 trades remain, expectancy rises to +0.51R.</p>
<p>Validate on out-of-sample data: expectancy remains positive (+0.44R).</p>

<h4>Decision</h4>
<p>Adopt the refinement. Document the change and its impact.</p>

<h3>When NOT to refine</h3>
<ul>
    <li><strong>After a losing streak.</strong> Losing streaks are normal. Don't change rules because of a few losses.</li>
    <li><strong>After a winning streak.</strong> Don't add rules that seem to improve already-good results.</li>
    <li><strong>Based on one trade.</strong> Individual trades are noise.</li>
    <li><strong>Based on one market.</strong> Cross-market validation is essential.</li>
    <li><strong>To make the backtest look better.</strong> Improving backtest results doesn't improve live results.</li>
    <li><strong>Before validating the original strategy.</strong> Refine only after confirming the strategy has a real edge.</li>
</ul>

<h2>Factual context</h2>
<p>Refinement is a documented phase in professional strategy development:</p>
<p><strong>López de Prado</strong> — describes a rigorous framework for identifying robust refinements vs overfitting.</p>
<p><strong>Bailey et al.</strong> — research on distinguishing real improvements from data mining.</p>
<p><strong>Practitioner experience</strong> — professional traders refine strategies iteratively over years, always watching for overfitting.</p>
<p>Van Tharp, on refinement:</p>
<blockquote><strong>\"Refinement should be based on structural logic, not data fitting. If you can't explain why a change works, don't make it.\"</strong></blockquote>
<p>Tharp's point: refinements need logical justification, not just improved backtest numbers.</p>
<p>Ed Seykota, on simplicity:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules are simple. They've survived decades without complex refinement.</p>
<p>Richard Dennis, on simple rules:</p>
<blockquote><strong>\"I have always maintained that one could train a group of people to be successful traders using a small set of rules.\"</strong></blockquote>
<p>Dennis' rules were simple. Simple rules are more robust and less prone to overfitting.</p>
<p>Nassim Nicholas Taleb, on refinement dangers:</p>
<blockquote><strong>\"The more you refine, the more you fit. The more you fit, the less you generalise. Simplicity is the ultimate sophistication.\"</strong></blockquote>
<p>Taleb's point: refinement has diminishing returns and increasing risks.</p>
<p>Warren Buffett, on simplicity:</p>
<blockquote><strong>\"You don't need to be a rocket scientist. Investing is not a game where the guy with the 160 IQ beats the guy with the 130 IQ.\"</strong></blockquote>
<p>Buffett's approach is simple. Complex refinements are rarely necessary.</p>
<p>Mark Douglas, on discipline:</p>
<blockquote><strong>\"The best traders are not afraid of being wrong. They accept the results of their testing, even when the results are negative.\"</strong></blockquote>
<p>Douglas' point applies to refinement. Don't keep refining to avoid accepting a negative result.</p>
<p>Paul Tudor Jones, on focus:</p>
<blockquote><strong>\"I focus on the few things that matter. I don't chase every possible improvement.\"</strong></blockquote>
<p>Jones' focus is essential. Not every refinement is worth pursuing.</p>
<p>Bruce Kovner, on simplicity:</p>
<blockquote><strong>\"I keep things simple. Complexity is the enemy of execution.\"</strong></blockquote>
<p>Kovner's point: simple strategies are easier to follow. Refinements should simplify, not complicate.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Refining too often.</strong> Small samples produce noise; refinements based on noise create overfitting.</li>
    <li><strong>Refining based on recent results.</strong> Recent trades are a small sample. Refine on longer data.</li>
    <li><strong>Adding rules to avoid losses.</strong> Every rule adds complexity. Complex rules are harder to follow.</li>
    <li><strong>Optimising exact values.</strong> Small differences in parameters are noise.</li>
    <li><strong>Not validating refinements.</strong> Every change should be tested out-of-sample.</li>
    <li><strong>Changing too much at once.</strong> Change one thing, test, then decide.</li>
    <li><strong>Refining in isolation.</strong> Cross-market validation ensures the refinement is general.</li>
    <li><strong>Not documenting changes.</strong> Without documentation, changes can't be reviewed.</li>
    <li><strong>Refining forever.</strong> Perfection doesn't exist. Good enough is sufficient.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional strategy refinement uses sophisticated techniques:</p>
<ul>
    <li><strong>Feature importance analysis</strong> — determining which rules matter most.</li>
    <li><strong>Parameter stability analysis</strong> — checking if optimal parameters are stable.</li>
    <li><strong>Robustness testing</strong> — stress-testing refinements across conditions.</li>
    <li><strong>Bayesian updating</strong> — gradually updating beliefs as new data arrives.</li>
    <li><strong>Ensemble methods</strong> — combining multiple versions of a strategy.</li>
</ul>
<p>For most retail traders, simple refinement is sufficient:</p>
<ol>
    <li>Analyse backtest by condition.</li>
    <li>Identify patterns (with sample size 100+).</li>
    <li>Check pattern across markets and periods.</li>
    <li>Add a simple filter.</li>
    <li>Validate on out-of-sample data.</li>
    <li>Document the change.</li>
</ol>
<p>The most important insight: refinement is about improving the strategy, not making it perfect. Every change adds complexity, which reduces robustness. Refine sparingly, based on strong evidence, and stop when improvements become marginal.</p>
<p>With backtesting and refinement complete, the strategy is ready for forward testing. That's the next module.</p>
HTML,
        ],

        [
            'slug'   => 'putting-backtesting-together',
            'title'  => 'Putting Backtesting Together',
            'difficulty' => 'professional',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Review the complete backtesting process\n" .
                "• Apply the validation framework\n" .
                "• Make informed decisions about your strategy\n" .
                "• Prepare for forward testing",
            'prerequisites' => 'Refining Based on Backtest Data',
            'sort_order' => 8,
            'summary' => 'This final lesson brings together the entire backtesting process: manual testing, sample size considerations, bias avoidance, expectancy calculation, validation, and refinement. The goal is a validated strategy ready for the next phase — forward testing.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>You've now learned every aspect of backtesting. This lesson puts it all together into a coherent process that takes you from strategy design to validated strategy ready for live markets.</p>
<p>The process is not complex. It's a sequence of steps, each building on the previous.</p>

<h2>The complete backtesting process</h2>

<h3>Step 1: Prepare your environment</h3>
<ul>
    <li>Charting platform with replay mode.</li>
    <li>Spreadsheet for recording.</li>
    <li>Written strategy rules.</li>
    <li>Time blocks for sessions.</li>
</ul>

<h3>Step 2: Choose your test period</h3>
<ul>
    <li>At least 2 years of data.</li>
    <li>Includes trending and ranging markets.</li>
    <li>Includes high and low volatility periods.</li>
    <li>Recent enough to be relevant.</li>
</ul>

<h3>Step 3: Apply rules consistently</h3>
<ul>
    <li>Use replay mode.</li>
    <li>Advance one bar at a time.</li>
    <li>Record every setup that matches rules.</li>
    <li>Record every outcome.</li>
    <li>Don't change rules mid-test.</li>
</ul>

<h3>Step 4: Record data systematically</h3>
<ul>
    <li>Entry, stop, target, exit.</li>
    <li>R-multiple.</li>
    <li>Session, market, direction.</li>
    <li>Market conditions.</li>
    <li>Setup quality.</li>
</ul>

<h3>Step 5: Reach sufficient sample</h3>
<ul>
    <li>Minimum 100 trades.</li>
    <li>Ideally 200+ trades.</li>
    <li>Across multiple market conditions.</li>
</ul>

<h3>Step 6: Calculate statistics</h3>
<ul>
    <li>Win rate.</li>
    <li>Average win and loss.</li>
    <li>Expectancy.</li>
    <li>Maximum drawdown.</li>
    <li>Longest losing streak.</li>
    <li>Profit factor.</li>
</ul>

<h3>Step 7: Analyse by condition</h3>
<ul>
    <li>By market.</li>
    <li>By session.</li>
    <li>By direction.</li>
    <li>By market state.</li>
    <li>By day of week.</li>
</ul>

<h3>Step 8: Check for biases</h3>
<ul>
    <li>Hindsight bias.</li>
    <li>Survivorship bias.</li>
    <li>Look-ahead bias.</li>
    <li>Data mining bias.</li>
    <li>Overfitting.</li>
</ul>

<h3>Step 9: Validate</h3>
<ul>
    <li>Out-of-sample testing.</li>
    <li>Walk-forward analysis.</li>
    <li>Robustness checks.</li>
</ul>

<h3>Step 10: Refine carefully</h3>
<ul>
    <li>Based on consistent patterns.</li>
    <li>Only simple filters.</li>
    <li>Validate refinements.</li>
    <li>Document changes.</li>
</ul>

<h3>Step 11: Decide</h3>
<ul>
    <li>Proceed to forward testing.</li>
    <li>Refine further.</li>
    <li>Reject and redesign.</li>
</ul>

<h2>Visual reference — Complete process</h2>
<svg viewBox="0 0 500 400" width="500" height="400" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Steps -->
  <rect x="50" y="20" width="400" height="30" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="4"/>
  <text x="250" y="40" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">1. Prepare environment</text>

  <rect x="50" y="60" width="400" height="30" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5" rx="4"/>
  <text x="250" y="80" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">2. Choose test period</text>

  <rect x="50" y="100" width="400" height="30" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="250" y="120" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">3. Apply rules consistently</text>

  <rect x="50" y="140" width="400" height="30" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="250" y="160" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">4. Record data systematically</text>

  <rect x="50" y="180" width="400" height="30" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5" rx="4"/>
  <text x="250" y="200" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">5. Reach sufficient sample (100+ trades)</text>

  <rect x="50" y="220" width="400" height="30" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="4"/>
  <text x="250" y="240" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">6. Calculate statistics</text>

  <rect x="50" y="260" width="400" height="30" fill="#f97316" fill-opacity="0.15" stroke="#f97316" stroke-width="1.5" rx="4"/>
  <text x="250" y="280" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">7. Analyse by condition</text>

  <rect x="50" y="300" width="400" height="30" fill="#ef4444" fill-opacity="0.15" stroke="#ef4444" stroke-width="1.5" rx="4"/>
  <text x="250" y="320" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">8. Check for biases + Validate</text>

  <rect x="50" y="340" width="400" height="30" fill="#ef4444" fill-opacity="0.3" stroke="#ef4444" stroke-width="2" rx="4"/>
  <text x="250" y="360" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">9. Refine + Decide + Proceed</text>
</svg>

<h3>Sample size strategy</h3>
<p>The sample size for your backtest should match the strategy's frequency:</p>
<table>
    <thead><tr><th>Strategy Frequency</th><th>Target Sample</th><th>Time Required</th></tr></thead>
    <tbody>
        <tr><td>Scalping (10+/day)</td><td>500+</td><td>1–2 months of trades</td></tr>
        <tr><td>Day trading (3–8/day)</td><td>300+</td><td>2–3 months of trades</td></tr>
        <tr><td>Swing trading (1–3/week)</td><td>150+</td><td>1+ year of trades</td></tr>
        <tr><td>Position trading (1–3/month)</td><td>100+</td><td>3–5 years of trades</td></tr>
    </tbody>
</table>
<p>Manual backtesting is slow. For lower-frequency strategies, you may need multiple years of historical data.</p>

<h3>Documentation template</h3>
<p>For each backtest, maintain a document with:</p>

<h4>Strategy details</h4>
<ul>
    <li>Strategy name and version.</li>
    <li>Markets and timeframes tested.</li>
    <li>Test period.</li>
    <li>Total trades.</li>
</ul>

<h4>Results</h4>
<ul>
    <li>Win rate.</li>
    <li>Average win / loss.</li>
    <li>Expectancy.</li>
    <li>Maximum drawdown.</li>
    <li>Longest losing streak.</li>
    <li>Profit factor.</li>
</ul>

<h4>By condition</h4>
<ul>
    <li>By market.</li>
    <li>By session.</li>
    <li>By direction.</li>
    <li>By market state.</li>
</ul>

<h4>Validation</h4>
<ul>
    <li>In-sample results.</li>
    <li>Out-of-sample results.</li>
    <li>Walk-forward results.</li>
    <li>Robustness checks.</li>
</ul>

<h4>Refinements</h4>
<ul>
    <li>What was changed.</li>
    <li>Why.</li>
    <li>Impact.</li>
</ul>

<h4>Decision</h4>
<ul>
    <li>Proceed to forward test?</li>
    <li>Refine further?</li>
    <li>Reject?</li>
</ul>

<h3>Decision criteria</h3>
<p>After backtesting, make an informed decision:</p>

<h4>Proceed to forward testing if:</h4>
<ul>
    <li>Positive expectancy in-sample.</li>
    <li>Positive expectancy out-of-sample.</li>
    <li>Walk-forward results positive.</li>
    <li>Robust to parameter changes.</li>
    <li>Consistent across markets.</li>
    <li>Sample size 100+.</li>
    <li>Logic of the strategy is sound.</li>
</ul>

<h4>Refine if:</h4>
<ul>
    <li>Positive but with clear weaknesses.</li>
    <li>Specific conditions consistently underperform.</li>
    <li>Simple refinements could improve expectancy.</li>
</ul>

<h4>Reject if:</h4>
<ul>
    <li>Negative expectancy across tests.</li>
    <li>Fails out-of-sample validation.</li>
    <li>Results depend on specific parameter values.</li>
    <li>Only works in one market.</li>
    <li>Logic is questionable.</li>
</ul>

<h3>Preparing for forward testing</h3>
<p>Before moving to forward testing:</p>
<ol>
    <li><strong>Document the strategy</strong> — final rules, entry/exit criteria, filters.</li>
    <li><strong>Set expectations</strong> — expected win rate, expectancy, drawdown.</li>
    <li><strong>Plan the test</strong> — demo account, target trade count (30+).</li>
    <li><strong>Set up tracking</strong> — journal, metrics, screenshots.</li>
    <li><strong>Commit to no changes</strong> — forward test without modifying rules.</li>
</ol>

<h3>Visual reference — Decision flow</h3>
<svg viewBox="0 0 500 320" width="500" height="320" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Backtest complete -->
  <ellipse cx="250" cy="30" rx="100" ry="18" fill="#5b7cfa" fill-opacity="0.3" stroke="#5b7cfa" stroke-width="2"/>
  <text x="250" y="35" fill="#e6e9ef" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Backtest complete</text>

  <!-- Arrow -->
  <line x1="250" y1="48" x2="250" y2="75" stroke="#8b93a7" stroke-width="2"/>

  <!-- Decision 1 -->
  <polygon points="250,75 400,110 250,145 100,110" fill="#f97316" fill-opacity="0.2" stroke="#f97316" stroke-width="1.5"/>
  <text x="250" y="108" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Positive expectancy</text>
  <text x="250" y="122" fill="#f97316" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">in-sample?</text>

  <!-- No -->
  <line x1="100" y1="110" x2="50" y2="110" stroke="#ef4444" stroke-width="2"/>
  <text x="70" y="105" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">No</text>
  <text x="50" y="135" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">Reject</text>

  <!-- Yes arrow -->
  <line x1="250" y1="145" x2="250" y2="175" stroke="#8b93a7" stroke-width="2"/>
  <text x="270" y="165" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Yes</text>

  <!-- Decision 2 -->
  <polygon points="250,175 400,210 250,245 100,210" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="1.5"/>
  <text x="250" y="208" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">Positive expectancy</text>
  <text x="250" y="222" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">out-of-sample?</text>

  <!-- No -->
  <line x1="400" y1="210" x2="450" y2="210" stroke="#ef4444" stroke-width="2"/>
  <text x="430" y="205" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">No</text>
  <text x="450" y="230" fill="#ef4444" font-size="10" font-family="Inter,sans-serif">Refine</text>

  <!-- Yes arrow -->
  <line x1="250" y1="245" x2="250" y2="275" stroke="#8b93a7" stroke-width="2"/>
  <text x="270" y="265" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Yes</text>

  <!-- Result -->
  <rect x="140" y="280" width="220" height="35" fill="#4ade80" fill-opacity="0.3" stroke="#4ade80" stroke-width="2" rx="6"/>
  <text x="250" y="303" fill="#4ade80" font-size="12" font-family="Inter,sans-serif" text-anchor="middle" font-weight="600">PROCEED TO FORWARD TEST</text>
</svg>

<h3>Common patterns</h3>

<h4>Pattern 1: Strong in-sample, weak out-of-sample</h4>
<p><strong>Diagnosis:</strong> Overfitting.</p>
<p><strong>Action:</strong> Simplify rules. Reduce parameter tuning. Test on more markets.</p>

<h4>Pattern 2: Positive expectancy but large drawdowns</h4>
<p><strong>Diagnosis:</strong> High variance strategy.</p>
<p><strong>Action:</strong> Reduce position size. Accept longer, slower growth.</p>

<h4>Pattern 3: Works in trending markets only</h4>
<p><strong>Diagnosis:</strong> Trend-dependent strategy.</p>
<p><strong>Action:</strong> Add trend filter. Skip ranging periods. Accept fewer trades.</p>

<h4>Pattern 4: Works in one market only</h4>
<p><strong>Diagnosis:</strong> Market-specific overfitting.</p>
<p><strong>Action:</strong> Test on more markets. If it fails on all others, reject.</p>

<h4>Pattern 5: Expectancy shifts over time</h4>
<p><strong>Diagnosis:</strong> Market conditions changing.</p>
<p><strong>Action:</strong> Segment by period. Update strategy for current conditions.</p>

<h3>Preparing for the next phase</h3>
<p>Backtesting is complete. You now have:</p>
<ul>
    <li><strong>A validated strategy</strong> — positive expectancy in-sample and out-of-sample.</li>
    <li><strong>Expected metrics</strong> — win rate, expectancy, drawdown.</li>
    <li><strong>By-condition insights</strong> — where the strategy works best.</li>
    <li><strong>A documented plan</strong> — written rules and refinements.</li>
</ul>
<p>The strategy is ready for forward testing (demo trading) and, eventually, live trading.</p>

<h2>Factual context</h2>
<p>Backtesting is a standard phase in professional strategy development:</p>
<p><strong>Professional funds</strong> — every strategy is backtested before live deployment. Testing is mandatory.</p>
<p><strong>Academic research</strong> — extensive literature on backtesting methodology and biases.</p>
<p><strong>Retail education</strong> — backtesting is universally taught as a prerequisite for live trading.</p>
<p>Van Tharp, on the process:</p>
<blockquote><strong>\"A complete backtest is worth more than any indicator. It's the difference between a system and a scheme.\"</strong></blockquote>
<p>Tharp's point: backtesting transforms ideas into systems.</p>
<p>Ed Seykota, on testing:</p>
<blockquote><strong>\"The trading rules I live by are: (1) Cut losses, (2) Ride winners, (3) Keep bets small, (4) Follow the rules without question, (5) Know when to break the rules.\"</strong></blockquote>
<p>Seykota's rules were validated through extensive testing.</p>
<p>Larry Hite, on the essence:</p>
<blockquote><strong>\"I have two basic rules about winning in trading as well as in life: (1) If you don't bet, you can't win. (2) If you lose all your chips, you can't bet.\"</strong></blockquote>
<p>Hite's rules imply testing. You can't know when to bet without data.</p>
<p>Richard Dennis, on the Turtle experiment:</p>
<blockquote><strong>\"I have always maintained that one could train a group of people to be successful traders using a small set of rules.\"</strong></blockquote>
<p>Dennis' confidence came from backtesting. He knew the rules worked.</p>
<p>Warren Buffett, on process:</p>
<blockquote><strong>\"You don't need to be a rocket scientist. You need a sound process and the discipline to follow it.\"</strong></blockquote>
<p>Buffett's process is validated. Backtesting is the process of validation.</p>
<p>Mark Douglas, on probabilistic thinking:</p>
<blockquote><strong>\"The market is a probabilistic environment. Any single trade can have any outcome. The probability of a specific outcome is what matters.\"</strong></blockquote>
<p>Douglas' point: backtesting provides probabilities. Without it, probabilities are guesses.</p>
<p>Paul Tudor Jones, on preparation:</p>
<blockquote><strong>\"I do the work. I know the historical behaviour of my markets. Nothing is left to chance.\"</strong></blockquote>
<p>Jones' preparation includes backtesting. Knowing historical behaviour requires data.</p>
<p>Bruce Kovner, on testing:</p>
<blockquote><strong>\"I test everything before I trade it. If it doesn't work in the test, it won't work live.\"</strong></blockquote>
<p>Kovner's discipline is the model. Test first, trade second.</p>
<p>Jesse Livermore, on history:</p>
<blockquote><strong>\"There is nothing new in Wall Street. There can't be because speculation is as old as the hills.\"</strong></blockquote>
<p>Livermore's point: history repeats. Backtesting captures the patterns.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Skipping backtesting.</strong> Trading live without validation is gambling.</li>
    <li><strong>Testing too few trades.</strong> Under 100 is insufficient.</li>
    <li><strong>Ignoring biases.</strong> Biased backtests produce false confidence.</li>
    <li><strong>Skipping validation.</strong> In-sample results alone are not reliable.</li>
    <li><strong>Over-refining.</strong> Too many changes create overfitting.</li>
    <li><strong>Not documenting.</strong> Without records, testing can't be reviewed.</li>
    <li><strong>Ignoring negative results.</strong> If the strategy loses, don't trade it.</li>
    <li><strong>Rushing to live.</strong> The process takes time. Don't skip steps.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional backtesting uses sophisticated frameworks:</p>
<ul>
    <li><strong>Automated backtesting software</strong> — TradeStation, Amibroker, QuantConnect.</li>
    <li><strong>Portfolio-level backtests</strong> — testing multiple strategies together.</li>
    <li><strong>Monte Carlo analysis</strong> — testing thousands of scenarios.</li>
    <li><strong>Ensemble methods</strong> — combining multiple strategies for robustness.</li>
    <li><strong>Machine learning backtesting</strong> — using AI for pattern discovery.</li>
</ul>
<p>For retail traders, the manual process is sufficient. The important thing is doing it rigorously and honestly.</p>
<p>The most important insight: backtesting is not optional. It's the difference between trading with an edge and trading on hope. Every successful trader validates their strategy before risking real capital.</p>
<p>With backtesting complete, you have a validated strategy. The next phase is forward testing — applying the strategy to live demo markets to confirm that backtest results translate to real trading.</p>
HTML,
        ],

    ],
];