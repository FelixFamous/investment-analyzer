<?php
/**
 * Module 23 — Supply & Demand
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_23_supply_demand.php
 */

return [
    'module' => [
        'level_slug' => 'advanced',
        'slug'       => 'supply-demand',
        'title'      => 'Supply & Demand',
        'description'=> 'Supply and demand zones are specific price areas where buyers or sellers have shown decisive interest. Unlike support and resistance — which are drawn as lines — supply and demand zones are drawn as areas that reflect where institutions left footprints. Learning to read them is the next step after liquidity.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Distinguish supply/demand zones from support/resistance\n" .
            "• Identify high-quality demand and supply zones\n" .
            "• Understand the concepts of imbalance and inefficiency\n" .
            "• Trade zones with proper entry, stop, and target logic\n" .
            "• Recognise when a zone should be ignored",
        'sort_order' => 23,
    ],

    'lessons' => [

        [
            'slug'   => 'what-are-supply-and-demand-zones',
            'title'  => 'What Are Supply and Demand Zones?',
            'difficulty' => 'advanced',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Define supply and demand zones\n" .
                "• Distinguish zones from support/resistance lines\n" .
                "• Explain why zones form where they do",
            'prerequisites' => 'Putting Liquidity Together',
            'sort_order' => 1,
            'summary' => 'Supply zones are price areas where selling pressure overwhelmed buying pressure, causing a sharp move lower. Demand zones are areas where buying pressure overwhelmed selling pressure, causing a sharp move higher. Unlike support/resistance lines, zones are areas that capture the entire imbalance left by institutional activity.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Support and resistance are lines drawn at prices where the market turned. Supply and demand zones are <em>areas</em> where a big move started.</p>
<p>Imagine a shop where a hundred people are waiting to buy a limited product. The moment the doors open, everyone rushes in. That rush is what a demand zone looks like — a sudden burst of buying that launches price higher.</p>
<p>Supply zones are the opposite: a burst of selling that launches price lower.</p>

<h2>Real-world analogy</h2>
<p>Think of a dam. When the dam holds, water builds up behind it. When it breaks, the water rushes out in a single decisive move. The location of the dam is the "zone" — the place where pressure built and then released.</p>

<h2>Professional explanation</h2>

<h3>The core concept</h3>
<p><strong>Supply zones</strong> are areas on a chart where price has shown an aggressive move downward. <strong>Demand zones</strong> are areas where price has shown an aggressive move upward. The word "aggressive" is key — these aren't slow grinds, they're decisive moves.</p>
<p>The underlying idea: when institutions place large orders, they leave a visible footprint. That footprint is a zone. When price returns to the zone later, those institutions (or others like them) are likely to act again.</p>

<h3>Supply/demand zones vs support/resistance</h3>
<p>The two concepts are related but distinct:</p>
<table>
    <thead><tr><th>Feature</th><th>Support/Resistance</th><th>Supply/Demand Zones</th></tr></thead>
    <tbody>
        <tr><td>Shape</td><td>Thin line</td><td>Wide area</td></tr>
        <tr><td>Focus</td><td>Where price reversed</td><td>Where price accelerated</td></tr>
        <tr><td>Basis</td><td>Touches and reactions</td><td>Imbalance and departure</td></tr>
        <tr><td>Strength</td><td>Number of touches</td><td>Sharpness of the departure</td></tr>
        <tr><td>Location</td><td>At swing points</td><td>At the base of impulses</td></tr>
    </tbody>
</table>

<h3>How zones form</h3>
<p>A zone forms when there is a large imbalance between buyers and sellers. Specifically:</p>
<ol>
    <li><strong>Large order activity</strong> — an institution or group of institutions places large buy or sell orders.</li>
    <li><strong>Order absorption</strong> — the market absorbs those orders over a brief period (a few candles).</li>
    <li><strong>Sharp departure</strong> — once the orders are filled, price moves aggressively away from the zone.</li>
</ol>
<p>The zone is the area where the absorption happened. It represents the price where large orders were executed.</p>

<h3>Visual reference — Demand zone</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Demand zone highlight -->
  <rect x="40" y="140" width="100" height="30" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="1.5"/>
  <text x="90" y="185" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Demand zone</text>

  <!-- Price action -->
  <polyline points="40,150 60,155 80,148 100,152 120,145 140,100 180,60 240,40 320,80 400,140 460,180"
            fill="none" stroke="#e6e9ef" stroke-width="2"/>

  <!-- Sharp departure arrow -->
  <polygon points="200,70 230,50 215,75" fill="#4ade80"/>
  <text x="230" y="85" fill="#4ade80" font-size="11" font-family="Inter,sans-serif">Sharp departure</text>

  <!-- Return to zone -->
  <circle cx="400" cy="140" r="6" fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <text x="400" y="125" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Retest</text>
</svg>

<h3>Visual reference — Supply zone</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Supply zone highlight -->
  <rect x="40" y="80" width="100" height="30" fill="#ef4444" fill-opacity="0.2" stroke="#ef4444" stroke-width="1.5"/>
  <text x="90" y="65" fill="#ef4444" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Supply zone</text>

  <!-- Price action -->
  <polyline points="40,100 60,95 80,102 100,98 120,105 140,150 180,190 240,210 320,170 400,110 460,70"
            fill="none" stroke="#e6e9ef" stroke-width="2"/>

  <!-- Sharp departure arrow -->
  <polygon points="200,190 230,210 215,185" fill="#ef4444"/>
  <text x="230" y="215" fill="#ef4444" font-size="11" font-family="Inter,sans-serif">Sharp departure</text>

  <!-- Return to zone -->
  <circle cx="400" cy="110" r="6" fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <text x="400" y="95" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Retest</text>
</svg>

<h3>Why zones work</h3>
<p>Four reasons:</p>
<ol>
    <li><strong>Unfilled orders.</strong> When price moved away from a zone quickly, some large orders may not have been filled. When price returns, those orders may get filled.</li>
    <li><strong>Institutional memory.</strong> Traders who bought at the demand zone remember the decision. When price returns, they're likely to buy again.</li>
    <li><strong>Imbalance of memory.</strong> Traders who missed the original move often plan to enter on the retest. This creates a wave of orders at the zone.</li>
    <li><strong>Self-fulfilling prophecy.</strong> Because supply/demand zones are widely watched, they become self-reinforcing.</li>
</ol>

<h2>Factual context</h2>
<p>The concept of supply and demand zones was formalised by Sam Seiden, a trader and educator who developed the "Online Trading Academy" (OTA) methodology in the 2000s. Seiden's insight was that traditional support/resistance analysis — based on touches and reactions — missed the underlying mechanics of where institutions actually placed orders.</p>
<p>Seiden's approach shifted the focus from where price reacted to where price originated. His framework identified "supply" and "demand" zones as the areas where price showed a sharp, aggressive departure — evidence of large order activity.</p>
<p>Richard Wyckoff's work in the 1930s anticipated these concepts. Wyckoff described "points of support" and "points of resistance" as areas where professional money had accumulated or distributed positions. His "accumulation" and "distribution" schematics showed zones where these activities were visible on a chart.</p>
<p>The concept of imbalance is deeply rooted in market microstructure theory. Academic research by Kyle (1985), Glosten and Milgrom (1985), and others showed how informed traders affect prices through their order flow. When informed buyers absorb supply, they create a temporary imbalance — the same phenomenon supply/demand traders now identify as a "demand zone."</p>
<p>Modern SMC (Smart Money Concepts) and ICT (Inner Circle Trader) frameworks use slightly different terminology — "order blocks" and "fair value gaps" — but describe the same phenomena. A bullish order block is essentially a demand zone; a bearish order block is a supply zone.</p>
<p>Sam Seiden described his framework as follows:</p>
<blockquote><strong>"The markets move from one area of imbalance to another. Supply and demand zones are the footprints of where the imbalance began."</strong></blockquote>
<p>Al Brooks, whose price action framework predates Seiden's work by decades, described the same phenomenon in his own language:</p>
<blockquote><strong>"The market often reverses from the same area where a strong move began. The institutional traders who bought there before are likely to buy there again."</strong></blockquote>
<p>Paul Tudor Jones has spoken about the importance of understanding where orders originate:</p>
<blockquote><strong>"I try to figure out where the market has been most active. That's where the participants are, and that's where the market is likely to react."</strong></blockquote>
<p>Jones' observation captures the essence of supply/demand analysis — the areas of greatest activity become the areas of greatest reactivity.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Confusing zones with support/resistance.</strong> Zones are wider and defined by the aggressive move away from them, not by touches.</li>
    <li><strong>Marking too many zones.</strong> Only the sharpest departures create valid zones. If you mark every swing, you'll have a cluttered chart.</li>
    <li><strong>Ignoring the departure quality.</strong> A weak departure (small candles, overlaps) doesn't create a strong zone.</li>
    <li><strong>Assuming zones always hold.</strong> Zones work as areas of interest, not guarantees. Many fail.</li>
    <li><strong>Trading zones without confirmation.</strong> Wait for price to actually react at the zone before entering.</li>
    <li><strong>Using zones on low timeframes only.</strong> Higher-timeframe zones (H4, Daily) are significantly more reliable.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders distinguish between <strong>base zones</strong> and <strong>continuation zones</strong>:</p>
<ul>
    <li><strong>Base zones</strong> — form at the origin of a new trend. Price consolidates, then departs aggressively. These are the strongest zones.</li>
    <li><strong>Continuation zones</strong> — form in the middle of a trend, when price pauses briefly before continuing. These are weaker but useful.</li>
</ul>
<p>The rule of thumb: the sharper the departure and the longer the preceding consolidation, the stronger the zone. A zone that forms after a long base and produces a sharp move is the most reliable. A zone that forms in the middle of a choppy trend is likely to fail on retest.</p>
<p>Another important distinction: <strong>fresh zones</strong> (never tested) are stronger than <strong>tested zones</strong>. Each test consumes some of the orders left in the zone, making it weaker. A fresh zone on the daily chart is one of the highest-probability setups in advanced price action trading. We cover this in detail in a later lesson.</p>
HTML,
        ],

        [
            'slug'   => 'identifying-quality-zones',
            'title'  => 'Identifying Quality Zones',
            'difficulty' => 'advanced',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Identify high-quality supply and demand zones\n" .
                "• Rank zones by strength\n" .
                "• Avoid low-quality zones",
            'prerequisites' => 'What Are Supply and Demand Zones?',
            'sort_order' => 2,
            'summary' => 'Not all zones are created equal. High-quality zones have specific characteristics: sharp departure, strong preceding move, fresh (never tested), on higher timeframes, and aligned with structure. Learning to rank zones by quality is the difference between a profitable and an unprofitable use of the concept.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine two shops. Shop A had a long queue of people waiting and then a burst of buying. Shop B had a slow trickle of customers who occasionally bought. Which shop's location is more valuable? Shop A — because the demand was obvious and concentrated.</p>
<p>Zones work the same way. The sharper the burst of buying or selling at a zone, the stronger the zone. This lesson teaches you to rank them.</p>

<h2>Real-world analogy</h2>
<p>Think of a blacksmith's forge. When the iron is hot, a single strike can shape it dramatically. When it's cold, the same strike barely leaves a mark. Zones are like strikes — the "hot" ones (sharp departures) leave stronger imprints.</p>

<h2>Professional explanation</h2>

<h3>The five quality factors</h3>
<p>Every zone can be evaluated on five factors:</p>

<h4>1. Sharpness of departure</h4>
<ul>
    <li><strong>Strong</strong> — large, decisive candles leaving the zone with minimal overlap.</li>
    <li><strong>Weak</strong> — gradual movement with overlapping candles.</li>
</ul>
<p>The sharper the departure, the more aggressive the order flow. This is the single most important factor.</p>

<h4>2. Preceding base duration</h4>
<ul>
    <li><strong>Strong</strong> — long consolidation before the departure. The longer the base, the more orders accumulated.</li>
    <li><strong>Weak</strong> — short base or no base. Price just spiked from a random point.</li>
</ul>
<p>Think of a dam. The longer water builds behind it, the more powerful the release. Same with zones.</p>

<h4>3. Freshness</h4>
<ul>
    <li><strong>Strong</strong> — zone has never been tested (fresh). Full order density.</li>
    <li><strong>Moderate</strong> — zone tested once and held. Some orders remain.</li>
    <li><strong>Weak</strong> — zone tested multiple times. Most orders consumed.</li>
</ul>
<p>A fresh zone is generally the highest-probability setup. Tested zones can still work, but the odds decrease with each test.</p>

<h4>4. Timeframe</h4>
<ul>
    <li><strong>Strong</strong> — zones on daily or weekly charts. They represent significant institutional activity.</li>
    <li><strong>Moderate</strong> — H4 zones. Useful but less significant.</li>
    <li><strong>Weak</strong> — zones on M5 or M15. Often noise.</li>
</ul>
<p>The rule: <strong>the higher the timeframe, the more reliable the zone</strong>.</p>

<h4>5. Alignment with structure and context</h4>
<ul>
    <li><strong>Strong</strong> — zone aligns with the higher-timeframe trend, at a key level, near liquidity.</li>
    <li><strong>Weak</strong> — zone fights the trend or sits in the middle of nowhere.</li>
</ul>
<p>Confluence matters — a zone that aligns with multiple other factors is more reliable.</p>

<h3>Visual reference — strong vs weak zone</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Strong zone (left side) -->
  <rect x="30" y="150" width="80" height="20" fill="#4ade80" fill-opacity="0.2" stroke="#4ade80" stroke-width="1.5"/>
  <polyline points="30,160 50,155 70,158 90,153 110,150 130,80 160,40 200,30"
            fill="none" stroke="#e6e9ef" stroke-width="2"/>
  <text x="70" y="190" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Strong</text>
  <text x="70" y="205" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Sharp departure</text>

  <!-- Weak zone (right side) -->
  <rect x="280" y="150" width="80" height="20" fill="#ef4444" fill-opacity="0.2" stroke="#ef4444" stroke-width="1.5"/>
  <polyline points="280,160 300,155 320,158 340,150 360,155 380,145 400,150 420,140 440,145 460,135"
            fill="none" stroke="#e6e9ef" stroke-width="2"/>
  <text x="370" y="190" fill="#ef4444" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Weak</text>
  <text x="370" y="205" fill="#8b93a7" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Gradual, overlapping</text>
</svg>

<h3>The quality score</h3>
<p>Score each zone from 1–5 on each factor. Total scores of 18–25 indicate a premium zone; 12–17 indicate a moderate zone; below 12 indicate a low-quality zone.</p>
<table>
    <thead><tr><th>Score</th><th>Zone Quality</th><th>Action</th></tr></thead>
    <tbody>
        <tr><td>18–25</td><td>Premium</td><td>High-conviction trade</td></tr>
        <tr><td>12–17</td><td>Moderate</td><td>Standard trade (needs confirmation)</td></tr>
        <tr><td>Below 12</td><td>Low</td><td>Skip or wait for better</td></tr>
    </tbody>
</table>

<h3>Additional quality indicators</h3>
<p>Beyond the five primary factors, watch for:</p>
<ul>
    <li><strong>Volume spike at the departure</strong> — indicates genuine institutional activity.</li>
    <li><strong>Momentum candle</strong> — the departure candle has a large body and small wick.</li>
    <li><strong>Imbalance</strong> — the departure leaves a gap or imbalance zone.</li>
    <li><strong>Liquidity sweep before the zone</strong> — price swept a level before forming the zone.</li>
    <li><strong>Session timing</strong> — the zone formed during London or NY session.</li>
</ul>

<h3>What makes a zone weak</h3>
<ul>
    <li><strong>Choppy departure</strong> — small candles, overlapping bodies, no clear direction.</li>
    <li><strong>No preceding base</strong> — the departure comes from nowhere.</li>
    <li><strong>Repeatedly tested</strong> — each test weakens the zone.</li>
    <li><strong>Low timeframe</strong> — M5 and M15 zones are often noise.</li>
    <li><strong>Against the higher-timeframe trend</strong> — counter-trend zones fail frequently.</li>
    <li><strong>At a random level</strong> — no confluence with other technical factors.</li>
</ul>

<h2>Factual context</h2>
<p>The framework for ranking zone quality was formalised in the SMC and OTA communities in the 2010s. The idea — that some zones are objectively stronger than others — has been validated by statistical research.</p>
<p>Studies of order blocks (essentially supply/demand zones) by the ICT community found that zones formed with strong displacement had significantly higher success rates than zones formed with weak displacement. The effect was strongest on higher timeframes (H4 and above).</p>
<p>Research on institutional order flow (including work by Kyle, Glosten, and Milgrom in the 1980s) supports the theory behind zones. When informed traders place large orders, they create a visible imbalance in the order book. This imbalance leaves a footprint on the chart that persists after the initial move — the phenomenon supply/demand traders now identify as a zone.</p>
<p>Sam Seiden, the developer of the modern supply/demand framework, emphasised quality over quantity:</p>
<blockquote><strong>"Most traders fail because they mark every zone. The successful ones mark only the best. One high-quality zone is worth ten mediocre ones."</strong></blockquote>
<p>Al Brooks, describing the same concept in his price action framework:</p>
<blockquote><strong>"The market doesn't reverse randomly. It reverses where there was real activity. The bigger the activity, the more important the level."</strong></blockquote>
<p>Paul Tudor Jones' perspective on quality:</p>
<blockquote><strong>"I only trade when I see something that is so compelling I can't pass it up. If I'm not sure, I pass. The market always offers another opportunity."</strong></blockquote>
<p>Jones' discipline — waiting for premium setups — is the essence of zone quality analysis. Better to trade one high-quality zone than five mediocre ones.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Marking too many zones.</strong> If you have 20 zones on your chart, they're all meaningless. Focus on the 2–3 best.</li>
    <li><strong>Ignoring departure quality.</strong> A zone formed by a slow grind is not the same as one formed by a sharp spike.</li>
    <li><strong>Trading tested zones.</strong> The first test of a fresh zone is highest probability. Subsequent tests are weaker.</li>
    <li><strong>Using low timeframes.</strong> M5 zones produce noise. Focus on H4 and above.</li>
    <li><strong>Ignoring confluence.</strong> A zone without any other confirming factor is weaker than one that lines up with structure, Fibonacci, or moving averages.</li>
    <li><strong>Forgetting context.</strong> A zone in a strong trend is different from a zone in a range. Context determines reliability.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often use a "zone grading" system where each zone is assigned a letter grade (A, B, C, D). A-grade zones are the ones worth risking full position size on. B-grade zones warrant half size. C-grade zones warrant quarter size or are skipped. D-grade zones are ignored entirely.</p>
<p>The grading system formalises the quality assessment. Instead of trading every zone with the same size, you scale your risk to the quality of the setup. This is one of the most important practical applications of the framework — matching risk to opportunity.</p>
<p>Another advanced technique: tracking the "success rate" of zones you identify. Over time, you'll notice that certain types of zones (specific timeframes, specific patterns) work better for you than others. This data-driven approach — reviewing your own zone history — is how traders refine the framework to their own style.</p>
HTML,
        ],

        [
            'slug'   => 'imbalance-and-inefficiency',
            'title'  => 'Imbalance and Inefficiency',
            'difficulty' => 'advanced',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Define market imbalance and inefficiency\n" .
                "• Identify imbalance zones on a chart\n" .
                "• Trade imbalance-based setups",
            'prerequisites' => 'Identifying Quality Zones',
            'sort_order' => 3,
            'summary' => 'Imbalance is what happens when price moves too far, too fast — leaving an area where orders were not fully matched. The market often returns to these areas to "fill" the imbalance. Understanding imbalance — and its close cousin, inefficiency — is essential for advanced zone trading.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a busy restaurant where a food critic arrives unexpectedly. The staff panics, seats her immediately, and skips serving three tables of regular customers. When the critic leaves, the staff must return to those three tables to serve them properly.</p>
<p>The market does the same thing. When large orders push price fast, some areas get "skipped." Later, the market returns to fill them in. These skipped areas are imbalances.</p>

<h2>Real-world analogy</h2>
<p>Think of a highway where traffic suddenly accelerates. Some cars get left behind, creating gaps in the traffic. Later, those gaps get filled as cars catch up. Market imbalances work the same way — gaps in liquidity that eventually get filled.</p>

<h2>Professional explanation</h2>

<h3>What is imbalance?</h3>
<p><strong>Imbalance</strong> is an area on the chart where the market moved too quickly for orders to be fully matched. It appears as a gap, a large candle, or a series of aggressive candles where price jumped from one level to another without trading every price in between.</p>
<p>Because some orders were not matched at those prices, the market often returns to "balance" the area — filling those orders and creating a reaction.</p>

<h3>The three types of imbalance</h3>

<h4>1. Fair Value Gap (FVG)</h4>
<p>A FVG is a specific three-candle pattern where the middle candle's range doesn't overlap with the first and third candles. This creates a "gap" in price.</p>
<ul>
    <li>Bullish FVG — an upward move where the first candle's high is below the third candle's low.</li>
    <li>Bearish FVG — a downward move where the first candle's low is above the third candle's high.</li>
</ul>
<p>The FVG represents an area where price moved so fast that no trading occurred. When price returns to fill the FVG, it often reacts.</p>

<h4>2. Liquidity void</h4>
<p>Similar to FVG but larger. A liquidity void is a large area on the chart where very few candles exist. It typically forms after a news event or a sharp spike.</p>

<h4>3. Imbalance zone</h4>
<p>A broader area around a sharp departure. Unlike FVG, which is defined by a specific three-candle pattern, an imbalance zone captures the entire area of quick movement.</p>

<h3>Visual reference — Fair Value Gap</h3>
<svg viewBox="0 0 500 240" width="500" height="240" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Candle 1 -->
  <line x1="80" y1="140" x2="80" y2="200" stroke="#4ade80" stroke-width="2"/>
  <rect x="70" y="150" width="20" height="40" fill="#4ade80"/>
  <text x="80" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Candle 1</text>

  <!-- Candle 2 (aggressive) -->
  <line x1="200" y1="40" x2="200" y2="180" stroke="#4ade80" stroke-width="2"/>
  <rect x="190" y="50" width="20" height="120" fill="#4ade80"/>
  <text x="200" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Candle 2</text>

  <!-- Candle 3 -->
  <line x1="320" y1="30" x2="320" y2="90" stroke="#4ade80" stroke-width="2"/>
  <rect x="310" y="40" width="20" height="40" fill="#4ade80"/>
  <text x="320" y="220" fill="#8b93a7" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Candle 3</text>

  <!-- FVG zone -->
  <rect x="90" y="100" width="230" height="30" fill="#5b7cfa" fill-opacity="0.15" stroke="#5b7cfa" stroke-width="1.5"/>
  <text x="205" y="120" fill="#5b7cfa" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Fair Value Gap</text>

  <text x="250" y="30" fill="#4ade80" font-size="11" font-family="Inter,sans-serif" text-anchor="middle">Aggressive move leaves FVG behind</text>
</svg>

<h3>Why imbalance matters</h3>
<p>Imbalance matters because the market likes to balance itself. When price moves too fast, some market participants didn't get a chance to act. When price returns to the imbalanced area, they get another chance. This creates a reaction.</p>
<p>Three reasons:</p>
<ol>
    <li><strong>Unfilled orders.</strong> Institutional orders that were too large to fill at the aggressive move wait for price to return.</li>
    <li><strong>Fair value.</strong> The market sees the imbalanced price as "unfair" — too far from the true value. Returning to fill the imbalance is seen as restoring fairness.</li>
    <li><strong>Momentum exhaustion.</strong> After a sharp move, the market needs to "rest." The return to imbalance is part of this rest.</li>
</ol>

<h3>Trading imbalance</h3>

<h4>Setup 1: Trade the fill of an FVG</h4>
<ol>
    <li>Identify a bullish or bearish FVG.</li>
    <li>Wait for price to return to the FVG.</li>
    <li>Look for a reaction (bullish/bearish candle) at the FVG.</li>
    <li>Enter in the direction of the original move.</li>
    <li>Stop-loss just beyond the FVG.</li>
    <li>Target the prior high/low or the next significant level.</li>
</ol>

<h4>Setup 2: Trade the reaction in a liquidity void</h4>
<ol>
    <li>Identify a liquidity void (large gap in price).</li>
    <li>Wait for price to return to the void.</li>
    <li>Watch for the reaction as price fills the void.</li>
    <li>Enter on confirmation.</li>
</ol>

<h3>Imbalance + zone</h3>
<p>Imbalance and supply/demand zones are closely related. In fact, they often overlap:</p>
<ul>
    <li>A demand zone often contains an FVG from the sharp departure.</li>
    <li>A supply zone often contains an FVG from the sharp drop.</li>
    <li>The combination of a zone and an FVG is stronger than either alone.</li>
</ul>
<p>When you find a demand zone that also contains an FVG, the setup is significantly stronger. When price returns, it's filling both the zone and the imbalance.</p>

<h3>Imbalance + liquidity</h3>
<p>Imbalance also interacts with liquidity:</p>
<ul>
    <li>A liquidity sweep often leaves behind an FVG (the sharp reversal creates the gap).</li>
    <li>Imbalance zones often become liquidity targets for future price moves.</li>
    <li>The ICT "market efficiency paradigm" describes how the market moves through imbalance areas, filling them before continuing.</li>
</ul>

<h2>Factual context</h2>
<p>The concept of market imbalance has roots in both market microstructure theory and modern price action analysis. Academic research on market microstructure — particularly work by Kyle (1985) and subsequent researchers — showed how informed traders create temporary imbalances in the order book. These imbalances are eventually resolved as uninformed traders fill the gaps.</p>
<p>The concept of the Fair Value Gap was popularised by Michael J. Huddleston (ICT) in the 2010s. His framework described FVGs as one of the key "PD arrays" — the collection of price levels that institutional traders watch. The three-candle structure of an FVG was identified as a specific signal of institutional activity.</p>
<p>Sam Seiden's supply/demand framework predates the FVG terminology but describes similar concepts. His "imbalance" zones — areas where price moved sharply — are essentially the same as FVGs, though the specific three-candle pattern was formalised later.</p>
<p>Al Brooks describes the same phenomenon as "gaps" and "measured moves." His framework treats these areas as magnets — places where price is likely to react because of the incomplete trading that occurred there.</p>
<p>The concept of imbalance is validated by statistical research. Studies of price behaviour after sharp moves have found that price frequently returns to the "origin" of the move, reacting at the level where the imbalance occurred. This return-to-origin effect has been documented across multiple markets and timeframes.</p>
<p>Paul Tudor Jones described the phenomenon from his own experience:</p>
<blockquote><strong>"The market often comes back to test its move. When it comes back to the origin, that's where the real battle happens."</strong></blockquote>
<p>Jones' observation captures the essence of imbalance trading — the return to the origin is where the highest-probability setups occur.</p>
<p>Michael J. Huddleston (ICT) has emphasised the importance of FVGs:</p>
<blockquote><strong>"The market seeks efficiency. Fair Value Gaps are inefficiencies that the market wants to close. When price returns to them, it reacts."</strong></blockquote>
<p>Huddleston's "market efficiency paradigm" describes the market's tendency to return to imbalance areas to restore balance. This framework has been adopted by many SMC and ICT traders as the primary lens for analysing price action.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Confusing imbalance with support/resistance.</strong> Imbalance is about unfilled orders, not about price reactions.</li>
    <li><strong>Trading every FVG.</strong> FVGs on low timeframes are noise. Focus on H4 and daily FVGs.</li>
    <li><strong>Assuming every FVG will be filled immediately.</strong> Some FVGs sit unfilled for weeks or months. Don't force the trade.</li>
    <li><strong>Ignoring the trend context.</strong> An FVG against the trend is lower probability than one with the trend.</li>
    <li><strong>Chasing the return to the FVG.</strong> Wait for price to actually reach the FVG before entering. Don't anticipate.</li>
    <li><strong>Missing the reaction candle.</strong> The reaction at the FVG is the confirmation. Don't enter without it.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use a technique called "FVG clustering" — identifying multiple FVGs in the same area. When price returns to a cluster of FVGs, the reaction is typically stronger than at a single FVG. This is because the clustered area represents a larger region of imbalance.</p>
<p>The most reliable FVG setups combine three factors:</p>
<ol>
    <li><strong>A high-timeframe FVG</strong> (Daily or H4).</li>
    <li><strong>A preceding liquidity sweep</strong> (the FVG was formed after a stop hunt).</li>
    <li><strong>Alignment with market structure</strong> (the FVG sits at a structural level).</li>
</ol>
<p>When all three align, the FVG becomes a high-probability setup — one of the strongest in advanced price action trading.</p>
<p>Another advanced application: using FVGs as targets. When price trends strongly in one direction, it often leaves FVGs behind. These become targets on future retracements. Traders can identify FVGs early and use them as profit targets for trades in the direction of the trend.</p>
HTML,
        ],

        [
            'slug'   => 'fresh-vs-tested-zones',
            'title'  => 'Fresh vs Tested Zones',
            'difficulty' => 'advanced',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Distinguish fresh zones from tested zones\n" .
                "• Understand why fresh zones are stronger\n" .
                "• Trade zones appropriately based on their freshness",
            'prerequisites' => 'Imbalance and Inefficiency',
            'sort_order' => 4,
            'summary' => 'A fresh zone is one that has never been tested by price since it formed. A tested zone has already been touched once or more. Fresh zones produce the highest-probability reactions; tested zones are progressively weaker. Understanding this distinction helps you prioritise your trades.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a shop that just announced a sale. The first customers get the best deals because the shelves are full. As the day goes on, the shelves empty out. By evening, most of the deals are gone.</p>
<p>Zones work the same way. A fresh zone is like the sale at 9 AM — everything is still available. Each test consumes some of the orders. By the third or fourth test, the zone is mostly empty.</p>

<h2>Real-world analogy</h2>
<p>Think of a water balloon. The first pinprick releases a lot of water. Each subsequent pinprick releases less because there's less water inside. Zones have a similar "capacity" that depletes with each test.</p>

<h2>Professional explanation</h2>

<h3>What is a fresh zone?</h3>
<p>A <strong>fresh zone</strong> is one that has never been tested by price since it was created. Price moved away from the zone aggressively and has not returned since. The orders left in the zone remain intact.</p>

<h3>What is a tested zone?</h3>
<p>A <strong>tested zone</strong> is one that price has returned to one or more times. Each test partially consumes the orders sitting at the zone. The zone still exists, but its potential energy is reduced.</p>

<h3>How freshness affects reliability</h3>
<table>
    <thead><tr><th>Status</th><th>Expected Reaction</th><th>Confidence</th></tr></thead>
    <tbody>
        <tr><td>Fresh (never tested)</td><td>Strong</td><td>High</td></tr>
        <tr><td>Tested once and held</td><td>Moderate</td><td>Medium</td></tr>
        <tr><td>Tested twice</td><td>Weaker</td><td>Lower</td></tr>
        <tr><td>Tested 3+ times</td><td>Likely to break</td><td>Low</td></tr>
    </tbody>
</table>

<h3>Visual reference</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Zone -->
  <rect x="30" y="150" width="440" height="25" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5"/>
  <text x="470" y="170" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Zone</text>

  <!-- Price with progressive tests -->
  <polyline points="40,160 80,155 130,90 180,50 220,100 260,155 300,90 340,60 380,155 420,100 460,170"
            fill="none" stroke="#e6e9ef" stroke-width="2"/>

  <!-- Test markers -->
  <circle cx="260" cy="155" r="5" fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <text x="260" y="185" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Test 1</text>
  <circle cx="380" cy="155" r="5" fill="none" stroke="#f97316" stroke-width="2"/>
  <text x="380" y="185" fill="#f97316" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Test 2</text>
  <circle cx="460" cy="170" r="5" fill="none" stroke="#ef4444" stroke-width="2"/>
  <text x="460" y="195" fill="#ef4444" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Test 3 (break)</text>
</svg>

<h3>Why fresh zones are stronger</h3>
<ol>
    <li><strong>Intact orders.</strong> The orders that were originally placed in the zone have not been filled. When price returns, they're still waiting.</li>
    <li><strong>Institutional memory.</strong> Traders who entered at the zone remember. When price returns, they may add to their positions.</li>
    <li><strong>No expectation of failure.</strong> A zone that has never been tested has no "history of failure." Traders view it as more reliable.</li>
    <li><strong>Anticipation of the test.</strong> Traders who missed the original move prepare to enter on the first retest. This creates a wave of orders.</li>
</ol>

<h3>Trading fresh zones</h3>
<ol>
    <li>Identify a fresh zone (sharp departure, no prior tests).</li>
    <li>Wait for price to return to the zone.</li>
    <li>Look for a reaction (rejection candle, engulfing, pin bar).</li>
    <li>Enter on the confirmation.</li>
    <li>Stop-loss just beyond the zone.</li>
    <li>Target the prior swing or the next liquidity level.</li>
</ol>
<p>Fresh zones allow for tighter stops and more aggressive targets because the zone is more likely to hold.</p>

<h3>Trading tested zones</h3>
<p>Tested zones can still be traded, but with adjustments:</p>
<ul>
    <li><strong>Reduce position size.</strong> The setup is lower probability, so risk less.</li>
    <li><strong>Widen stops slightly.</strong> Tested zones are more likely to see a brief pierce before holding.</li>
    <li><strong>Require stronger confirmation.</strong> Don't enter on a single candle. Wait for a structure break or a clear reversal pattern.</li>
    <li><strong>Reduce targets.</strong> Expect smaller reactions from tested zones.</li>
</ul>

<h3>Zone invalidation</h3>
<p>A zone is "invalidated" when price closes decisively beyond it. Once invalidated, the zone no longer functions as a supply/demand area — it may become the opposite (supply becomes demand, or vice versa).</p>
<p>Invalidation is different from a "test." A test means price touched the zone and reacted. Invalidation means price broke through the zone and closed beyond it.</p>

<h3>The "zone flip" concept</h3>
<p>When a demand zone is invalidated (price breaks below it), the zone often becomes a supply zone on the retest. This is the supply/demand equivalent of the resistance-becomes-support concept from traditional technical analysis.</p>
<p>Zone flips are a powerful setup because they combine two concepts:</p>
<ol>
    <li>The failure of the original zone (bearish signal).</li>
    <li>The new role of the flipped zone (bearish confirmation).</li>
</ol>

<h2>Factual context</h2>
<p>The concept of "fresh" vs "tested" zones has been central to supply/demand analysis since Sam Seiden formalised the framework in the 2000s. Seiden emphasised that zones lose their effectiveness with each test, just like support/resistance levels.</p>
<p>Wyckoff's work in the 1930s anticipated this concept. His schematics for accumulation and distribution showed how support and resistance levels weakened with each test. He described how a level that held multiple times was more likely to eventually break — because the orders supporting it were being consumed.</p>
<p>Modern SMC and ICT frameworks use the same logic. A "fresh order block" is one that hasn't been tested. A "tested order block" has been touched and is considered weaker. The terminology is different, but the concept is identical.</p>
<p>Statistical research on support/resistance levels supports the pattern. Studies by researchers including Thomas Bulkowski have found that support/resistance levels that have been tested multiple times are more likely to break than fresh ones. The same principle applies to supply/demand zones.</p>
<p>Al Brooks has described the phenomenon:</p>
<blockquote><strong>"Every time the market tests a level and pulls back, it uses up some of the orders there. Eventually, there are no orders left, and the level breaks."</strong></blockquote>
<p>Brooks' observation is the practical essence of why fresh zones are stronger. Each test consumes orders. The first test encounters the most orders; subsequent tests encounter fewer.</p>
<p>Bruce Kovner, describing his own approach to trading zones:</p>
<blockquote><strong>"I prefer to trade the first test of a level. The subsequent tests are usually weaker."</strong></blockquote>
<p>Kovner's discipline — trading the first test only — is a practical application of the fresh zone concept. It aligns with the statistical evidence that fresh zones produce the strongest reactions.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Treating tested zones the same as fresh zones.</strong> They're not the same. Tested zones require different handling (smaller size, wider stops, more confirmation).</li>
    <li><strong>Ignoring the test count.</strong> If a zone has been tested 4 times, it's likely to break on the next test, not hold.</li>
    <li><strong>Confusing a wick with a test.</strong> A wick that barely touches the zone may or may not count as a test. Use your judgment — how deep was the touch, how long did price stay?</li>
    <li><strong>Trading invalidated zones as if they still work.</strong> Once a zone is invalidated, it must be redrawn or replaced. It no longer functions in its original role.</li>
    <li><strong>Ignoring the higher timeframe.</strong> A fresh zone on the H4 is stronger than a fresh zone on the M15 — regardless of test count.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders track the "test count" of each zone they identify. A zone with zero tests is grade A. One test is grade B. Two tests is grade C. Three or more is grade D (usually skipped).</p>
<p>Another advanced technique: <strong>zone stacking</strong>. When multiple fresh zones of the same type (all demand zones or all supply zones) are stacked at different prices, the market often reacts to the first zone that price reaches. This creates a "layered" setup where each zone provides a potential reaction point.</p>
<p>Zone stacking is especially useful in trending markets. As the trend progresses, multiple zones form at different prices. The trader identifies all of them and watches for reactions as price returns to each. This gives multiple entry opportunities within the same trend.</p>
<p>Finally, the concept of "liquidity inside a zone" is important. If a zone contains internal liquidity (like swing highs or lows inside the zone's range), the market may need to sweep that liquidity before reacting to the zone itself. Understanding this helps time entries more precisely.</p>
HTML,
        ],

        [
            'slug'   => 'mitigation-and-departure',
            'title'  => 'Mitigation and Departure',
            'difficulty' => 'advanced',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define mitigation in the context of supply/demand\n" .
                "• Understand how mitigation affects zone strength\n" .
                "• Trade zones with awareness of mitigation",
            'prerequisites' => 'Fresh vs Tested Zones',
            'sort_order' => 5,
            'summary' => 'Mitigation refers to the process by which a zone is "used up" — either by being tested (partial mitigation) or broken (full mitigation). Understanding mitigation helps you assess whether a zone is still fresh and worth trading. Departure quality is what determines the strength of the initial zone and its ability to withstand mitigation.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a storage room with a limited inventory. Every time someone takes something, the inventory reduces. Eventually, there's nothing left. Zones work the same way — every test uses up some of the orders, a process called mitigation.</p>

<h2>Real-world analogy</h2>
<p>Think of a bank account. Every withdrawal reduces the balance. When the balance hits zero, the account is empty. Zones work like this — they start with "liquidity" that gets consumed with each test.</p>

<h2>Professional explanation</h2>

<h3>What is mitigation?</h3>
<p><strong>Mitigation</strong> is the consumption of orders left in a supply or demand zone. When price returns to a zone and reacts, some of the original orders are filled. This reduces the zone's potential energy.</p>
<p>There are two types of mitigation:</p>

<h4>Partial mitigation</h4>
<p>Price touches the zone, reacts, and moves away. Some orders are filled, but the zone remains functional. This is what we called a "test" in the previous lesson.</p>

<h4>Full mitigation</h4>
<p>Price breaks through the zone decisively. All the orders are filled, and the zone no longer functions in its original role. This is invalidation.</p>

<h3>Why mitigation matters</h3>
<p>Mitigation is why fresh zones are stronger than tested zones. Each partial mitigation reduces the zone's reliability. Full mitigation ends the zone's usefulness entirely.</p>
<p>Traders who understand mitigation know:</p>
<ul>
    <li>To look for fresh zones first.</li>
    <li>To reduce size on tested zones.</li>
    <li>To avoid fully-mitigated zones.</li>
    <li>To look for zone flips after full mitigation.</li>
</ul>

<h3>Departure quality</h3>
<p>Departure quality is what determines how much energy a zone has in the first place. A zone's "capacity" (how many orders it contains) is determined by the departure.</p>
<ul>
    <li><strong>Sharp departure</strong> — high capacity. Zone withstands multiple tests.</li>
    <li><strong>Moderate departure</strong> — medium capacity. Zone withstands 1–2 tests.</li>
    <li><strong>Weak departure</strong> — low capacity. Zone may fail on the first test.</li>
</ul>
<p>This is why departure quality and freshness work together. A zone with high capacity and no tests is the strongest setup. A zone with low capacity and multiple tests is the weakest.</p>

<h3>Visual reference — Mitigation progression</h3>
<svg viewBox="0 0 500 260" width="500" height="260" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
  <!-- Zone -->
  <rect x="30" y="150" width="440" height="25" fill="#4ade80" fill-opacity="0.15" stroke="#4ade80" stroke-width="1.5"/>
  <text x="470" y="170" fill="#4ade80" font-size="10" font-family="Inter,sans-serif">Zone</text>

  <!-- Price action with progressive mitigation -->
  <polyline points="40,160 80,155 130,90 180,50 240,120 280,155 340,90 380,155 420,120 460,220"
            fill="none" stroke="#e6e9ef" stroke-width="2"/>

  <!-- Markers -->
  <text x="130" y="80" fill="#4ade80" font-size="10" font-family="Inter,sans-serif" text-anchor="middle">Departure</text>
  <circle cx="280" cy="155" r="5" fill="none" stroke="#5b7cfa" stroke-width="2"/>
  <text x="280" y="185" fill="#5b7cfa" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Partial mitigation</text>
  <circle cx="380" cy="155" r="5" fill="none" stroke="#f97316" stroke-width="2"/>
  <text x="380" y="185" fill="#f97316" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Partial 2</text>
  <circle cx="460" cy="220" r="5" fill="none" stroke="#ef4444" stroke-width="2"/>
  <text x="460" y="245" fill="#ef4444" font-size="9" font-family="Inter,sans-serif" text-anchor="middle">Full mitigation</text>
</svg>

<h3>Departure patterns</h3>
<p>The quality of a departure is judged by the shape of the move away from the zone:</p>

<h4>Strong departure (high capacity)</h4>
<ul>
    <li>One or two large candles leaving the zone.</li>
    <li>Minimal overlap between candles.</li>
    <li>Clear imbalance or FVG left behind.</li>
    <li>Often accompanied by a liquidity sweep.</li>
</ul>

<h4>Moderate departure (medium capacity)</h4>
<ul>
    <li>Three to four medium candles.</li>
    <li>Some overlap but clear direction.</li>
    <li>Small FVG or slight imbalance.</li>
</ul>

<h4>Weak departure (low capacity)</h4>
<ul>
    <li>Many small candles with heavy overlap.</li>
    <li>No clear direction, choppy movement.</li>
    <li>No FVG or imbalance.</li>
</ul>
<p>Only the first two types typically produce tradeable zones. Weak departures should be ignored — they don't have enough energy to produce reliable reactions.</p>

<h3>Zone flips after full mitigation</h3>
<p>When a zone is fully mitigated (price breaks through decisively), it often becomes the opposite type of zone:</p>
<ul>
    <li><strong>Demand zone breaks</strong> → becomes a supply zone on the next retest.</li>
    <li><strong>Supply zone breaks</strong> → becomes a demand zone on the next retest.</li>
</ul>
<p>This is the "zone flip" concept — the supply/demand equivalent of support-resistance flips. Zone flips are powerful setups because they combine the failure of the original zone with the confirmation of the new role.</p>

<h3>Trading with mitigation awareness</h3>
<ol>
    <li><strong>Rate the departure quality.</strong> Sharp, moderate, or weak.</li>
    <li><strong>Note the number of prior tests.</strong> Fresh, tested, or invalidated.</li>
    <li><strong>Adjust position size based on freshness.</strong> Full size on fresh zones, half size on tested zones, skip invalidated zones.</li>
    <li><strong>Watch for zone flips.</strong> When a zone is invalidated, look for the flip setup on the retest.</li>
    <li><strong>Track your zone inventory.</strong> Keep a list of active fresh zones and their status.</li>
</ol>

<h2>Factual context</h2>
<p>The concept of mitigation is central to both supply/demand analysis and SMC/ICT frameworks. Michael J. Huddleston (ICT) describes mitigation as the process by which institutional orders left in a zone are consumed. His framework identifies "mitigation blocks" as zones that have been partially consumed and are now weaker.</p>
<p>Sam Seiden's supply/demand framework uses similar logic. His "fresh vs tested" distinction is essentially a mitigration framework — fresh zones are unmigrated, tested zones are partially mitigated, and broken zones are fully mitigated.</p>
<p>Wyckoff's work in the 1930s anticipated these concepts. His schematics for accumulation and distribution showed how support and resistance levels weakened with each test as orders were consumed. He described the "point of least resistance" as the level with the most remaining orders — analogous to a fresh zone.</p>
<p>Modern market microstructure research supports the framework. Academic studies on order flow have shown that the effectiveness of a price level depends on the depth of orders remaining there. Levels with fresh orders produce stronger reactions; depleted levels produce weaker ones.</p>
<p>Al Brooks has described the same phenomenon in price action terms:</p>
<blockquote><strong>"The market is always seeking to balance orders. When there are no more orders at a level, the level breaks. It's that simple."</strong></blockquote>
<p>Brooks' description is the mechanical essence of mitigation. Zones break when their orders are consumed. Fresh zones have orders; mitigated zones don't.</p>
<p>Bruce Kovner's approach to trading reflected this understanding:</p>
<blockquote><strong>"I look at where the market has been most active. That's where the orders are. When those orders get filled, the level loses its power."</strong></blockquote>
<p>Kovner's framework — trading activity-based levels — is what mitigation analysis formalises. The best trades are at levels where orders remain; the worst are at levels where they've been used up.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Ignoring departure quality.</strong> A zone formed by a weak departure is likely to fail on the first test.</li>
    <li><strong>Trading zones with multiple prior tests.</strong> By the third test, the zone's orders are mostly consumed.</li>
    <li><strong>Confusing partial and full mitigation.</strong> A partial test doesn't invalidate a zone; a full break does.</li>
    <li><strong>Forgetting the flip.</strong> When a zone is fully mitigated, it often becomes the opposite type. Don't ignore the new setup.</li>
    <li><strong>Not tracking zones over time.</strong> Zones need to be monitored. A zone that was fresh last month may be fully mitigated today.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders use a "mitigation tracker" — a mental or written record of each zone's status. Zones progress from "fresh" to "tested" to "invalidated" over time. This tracking helps prioritise trades and avoid trading exhausted setups.</p>
<p>The most advanced application of mitigation is called "smart money reversal" — a setup where a zone is partially mitigated (price reacts), then the market reverses to fully mitigate it (price breaks through), then the new zone (flip) is traded on the next retest. This three-stage setup combines multiple concepts from the module.</p>
<p>The concept of mitigation also connects to liquidity. Zones that are not yet mitigated represent "unconsumed liquidity" — they're a draw on price. Zones that are fully mitigated have no remaining liquidity — they're no longer a draw. This is why tracking mitigation status is important for understanding where price is likely to move next.</p>
HTML,
        ],

        [
            'slug'   => 'trading-supply-demand-zones',
            'title'  => 'Trading Supply and Demand Zones',
            'difficulty' => 'advanced',
            'estimated_duration' => 13,
            'learning_objectives' =>
                "• Build a complete framework for trading zones\n" .
                "• Enter, stop, and target zones correctly\n" .
                "• Manage trades based on zone behaviour",
            'prerequisites' => 'Mitigation and Departure',
            'sort_order' => 6,
            'summary' => 'This lesson brings together everything in the module into a complete framework for trading supply and demand zones. It covers zone identification, entry timing, stop placement, targets, and trade management. The goal is to trade zones with discipline and consistency.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>You now know how zones form, how to identify high-quality ones, and how they evolve with mitigation. This lesson is about using that knowledge to actually trade.</p>
<p>The process is straightforward: find a high-quality zone, wait for price to reach it, look for a reaction, enter with defined risk, and manage the trade based on how the zone behaves.</p>

<h2>The complete framework</h2>

<h3>Step 1: Establish context</h3>
<p>Before looking for zones, establish the higher-timeframe context:</p>
<ul>
    <li>What's the trend on the daily or weekly?</li>
    <li>What's the current market structure?</li>
    <li>Where are the major liquidity levels?</li>
</ul>
<p>Zones that align with the higher-timeframe trend have higher success rates.</p>

<h3>Step 2: Identify zones on your trading timeframe</h3>
<p>On H4 or H1, mark:</p>
<ul>
    <li><strong>Demand zones</strong> — sharp upward departures from a base.</li>
    <li><strong>Supply zones</strong> — sharp downward departures from a base.</li>
    <li><strong>Zone status</strong> — fresh, tested, or invalidated.</li>
    <li><strong>Departure quality</strong> — sharp, moderate, or weak.</li>
</ul>

<h3>Step 3: Rank zones by quality</h3>
<p>Use the five-factor assessment from Lesson 2:</p>
<ol>
    <li>Sharpness of departure</li>
    <li>Preceding base duration</li>
    <li>Freshness</li>
    <li>Timeframe</li>
    <li>Confluence with other factors</li>
</ol>
<p>Focus on the top 2–3 zones. Ignore the rest.</p>

<h3>Step 4: Wait for price to approach</h3>
<p>Don't anticipate — wait for price to actually reach the zone. As price approaches:</p>
<ul>
    <li>Watch for momentum shift.</li>
    <li>Look for divergence on RSI/MACD.</li>
    <li>Note any liquidity sweeps happening nearby.</li>
    <li>Check the economic calendar for upcoming events.</li>
</ul>

<h3>Step 5: Wait for the reaction</h3>
<p>At the zone, look for a reaction signal:</p>
<ul>
    <li><strong>Bullish reaction (at demand zone)</strong> — bullish engulfing, pin bar, hammer, or a small structure break to the upside.</li>
    <li><strong>Bearish reaction (at supply zone)</strong> — bearish engulfing, shooting star, or a small structure break to the downside.</li>
</ul>
<p>Do not enter without a reaction. A zone without a reaction is just a level.</p>

<h3>Step 6: Enter with proper risk</h3>
<ul>
    <li><strong>Entry:</strong> on the close of the reaction candle.</li>
    <li><strong>Stop-loss:</strong> just beyond the zone, plus a small buffer (5–10 pips on majors).</li>
    <li><strong>Position size:</strong> calculated from your risk percentage and stop distance (using ATR-based sizing).</li>
    <li><strong>R:R:</strong> minimum 2:1, ideally 3:1 or better.</li>
</ul>

<h3>Step 7: Manage the trade</h3>
<ul>
    <li><strong>Move to break-even</strong> after 1× risk in your favour.</li>
    <li><strong>Trail stop</strong> below each new higher low (longs) or above each new lower high (shorts).</li>
    <li><strong>Take partial profit</strong> at the first target.</li>
    <li><strong>Exit fully</strong> at the second target or if the trade invalidates (price reclaims the zone).</li>
</ul>

<h2>Worked example — GBP/USD Demand Zone</h2>

<h3>Context</h3>
<ul>
    <li>Daily chart: GBP/USD in an uptrend. Higher highs and higher lows.</li>
    <li>H4 chart: price has pulled back toward a prior demand zone.</li>
    <li>Prior day's low: 1.2650. Prior week's high: 1.2850.</li>
</ul>

<h3>Zone identification</h3>
<p>On the H4 chart, we identify a demand zone at 1.2640–1.2660 (20 pips wide) with these characteristics:</p>
<ul>
    <li>Sharp upward departure (two large bullish candles).</li>
    <li>Preceding base of 8 candles.</li>
    <li>Fresh — never tested.</li>
    <li>H4 timeframe.</li>
    <li>Confluence: coincides with prior day's low, round number 1.2650, and 61.8% Fibonacci retracement.</li>
</ul>
<p>Quality score: 22/25 — premium zone.</p>

<h3>Setup</h3>
<ol>
    <li>Wait for price to reach the zone at 1.2650.</li>
    <li>Look for a bullish reaction (engulfing or pin bar).</li>
    <li>Bullish engulfing candle forms with the close at 1.2670.</li>
    <li>Enter long at 1.2670.</li>
    <li>Stop-loss at 1.2635 (just below the zone, 35 pips).</li>
    <li>Target 1: 1.2740 (70 pips, 2:1 R:R).</li>
    <li>Target 2: 1.2810 (140 pips, 4:1 R:R).</li>
</ol>

<h3>Management</h3>
<ul>
    <li>Move stop to break-even after price reaches 1.2705 (1× risk).</li>
    <li>Take partial profit at Target 1.</li>
    <li>Trail stop below each new H4 higher low.</li>
    <li>Exit fully at Target 2 or if H4 structure breaks.</li>
</ul>

<h2>Additional trade setups</h2>

<h3>Setup: Zone + Liquidity Sweep</h3>
<p>The strongest zone trades combine a zone with a preceding liquidity sweep. The setup:</p>
<ol>
    <li>Identify a demand zone at a level with liquidity below it.</li>
    <li>Wait for price to sweep the liquidity (spike below the zone).</li>
    <li>Look for a reversal back into the zone.</li>
    <li>Enter on the reversal confirmation.</li>
    <li>Stop below the sweep.</li>
    <li>Target the prior high or the next supply zone.</li>
</ol>

<h3>Setup: Zone + Imbalance (FVG)</h3>
<p>When a zone contains an FVG, the setup is stronger:</p>
<ol>
    <li>Identify a demand zone that contains a bullish FVG.</li>
    <li>Wait for price to return to the FVG.</li>
    <li>Enter on the reaction at the FVG.</li>
    <li>Stop below the FVG.</li>
    <li>Target the next level.</li>
</ol>

<h3>Setup: Zone Flip</h3>
<p>After a zone is invalidated, it often becomes the opposite type:</p>
<ol>
    <li>Identify an invalidated demand zone (price broke below decisively).</li>
    <li>Wait for price to return to the zone from below.</li>
    <li>Look for a bearish reaction (the zone acts as new supply).</li>
    <li>Enter short on confirmation.</li>
    <li>Stop above the zone.</li>
    <li>Target the next demand zone.</li>
</ol>

<h2>Factual context</h2>
<p>The framework in this lesson synthesises concepts from multiple schools of trading — Sam Seiden's supply/demand analysis, Michael Huddleston's ICT methodology, Al Brooks' price action framework, and traditional support/resistance theory.</p>
<p>Each framework describes the same underlying phenomenon: institutional order flow leaves visible footprints on the chart, and those footprints repeat. Supply/demand zones are the most intuitive way to describe those footprints.</p>
<p>Professional traders have used zone-based strategies since the 1990s, when Sam Seiden formalised the modern framework. His "Online Trading Academy" trained thousands of traders in the methodology, and its principles are now taught in most SMC and price action courses.</p>
<p>Statistical research supports the framework. Studies of institutional order flow have shown that levels where large orders were placed tend to produce reactions on subsequent retests. The effect is stronger for fresh zones with sharp departures — exactly as the framework predicts.</p>
<p>Sam Seiden summarised his approach:</p>
<blockquote><strong>"The market moves from one area of supply or demand to the next. Our job is to identify the highest-quality zones and trade them with discipline."</strong></blockquote>
<p>Al Brooks describes the same discipline in price action terms:</p>
<blockquote><strong>"Trade the same setups over and over. The market offers the same patterns again and again. Mastery comes from doing the simple things consistently."</strong></blockquote>
<p>Paul Tudor Jones has emphasised the psychological dimension:</p>
<blockquote><strong>"The best trades are the ones that feel the most obvious in hindsight. If the setup isn't obvious, don't take it."</strong></blockquote>
<p>Jones' point applies directly to zone trading. The best zones are visually obvious — sharp departures from clear bases at meaningful levels. If you have to squint to see the zone, it's probably not worth trading.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading every zone.</strong> Focus on high-quality zones. Not every zone deserves a trade.</li>
    <li><strong>Entering without a reaction.</strong> Wait for price to actually react at the zone before entering.</li>
    <li><strong>Placing stops too tight.</strong> The stop must be beyond the zone with a buffer. Stops inside the zone get swept.</li>
    <li><strong>Ignoring the higher timeframe.</strong> Zones that fight the higher-timeframe trend have lower success rates.</li>
    <li><strong>Over-managing the trade.</strong> Once in a trade, give it room. Tightening stops too aggressively leads to premature exits.</li>
    <li><strong>Revenge-trading after a zone fails.</strong> Zones fail regularly. Move on to the next setup.</li>
    <li><strong>Not tracking zones over time.</strong> Zones evolve. Keep track of what's fresh, tested, or invalidated.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional traders often build a "zone watchlist" — a daily updated list of the 3–5 highest-quality zones on their pairs of interest. This prevents them from trading random levels and keeps them focused on the best opportunities.</p>
<p>The most advanced zone trading combines:</p>
<ol>
    <li><strong>A high-quality zone</strong> (premium grade).</li>
    <li><strong>A preceding liquidity sweep</strong> (the sweep cleared stops before the zone formed).</li>
    <li><strong>An FVG inside the zone</strong> (imbalance adds conviction).</li>
    <li><strong>Alignment with the higher-timeframe structure.</strong></li>
    <li><strong>Session timing</strong> (London or NY session).</li>
    <li><strong>Momentum divergence</strong> at the zone.</li>
</ol>
<p>When all six align, the setup is one of the highest-probability trades in advanced price action. But these setups are rare — you might see one or two per week. The skill is patience: waiting for the best, and skipping everything else.</p>
<p>This patience is the essence of advanced trading. The concepts are not complicated — anyone can learn to identify zones. What separates successful traders is discipline: waiting for the best zones and executing them consistently.</p>
HTML,
        ],

        [
            'slug'   => 'putting-supply-demand-together',
            'title'  => 'Putting Supply and Demand Together',
            'difficulty' => 'advanced',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Combine all supply/demand concepts into a working framework\n" .
                "• Build a repeatable zone-trading process\n" .
                "• Integrate supply/demand with liquidity and structure",
            'prerequisites' => 'Trading Supply and Demand Zones',
            'sort_order' => 7,
            'summary' => 'This final lesson brings together everything in the module: supply and demand zones, quality assessment, imbalance, mitigation, and trading mechanics. The goal is a repeatable process for identifying and trading zones as one component of a layered analysis.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Supply and demand zones are one of the most intuitive concepts in technical analysis — they describe where price reacted and why. But they work best when combined with other tools: liquidity, structure, and confluence.</p>
<p>This lesson integrates zones into a complete framework.</p>

<h2>The complete framework</h2>

<h3>Step 1: Start with structure</h3>
<p>Before zones, establish:</p>
<ul>
    <li>Higher-timeframe trend (daily, weekly).</li>
    <li>Current market structure (HH/HL, LH/LL, or ranging).</li>
    <li>Key structural levels (major swing highs and lows).</li>
</ul>
<p>Zones that align with structure have higher success rates.</p>

<h3>Step 2: Identify liquidity levels</h3>
<p>Mark:</p>
<ul>
    <li>Prior day/week high/low.</li>
    <li>Equal highs and equal lows.</li>
    <li>Session ranges (Asian range, London range).</li>
    <li>Round numbers.</li>
</ul>
<p>Liquidity levels often coincide with zones — creating high-confluence setups.</p>

<h3>Step 3: Find zones</h3>
<p>On H4 or H1, mark:</p>
<ul>
    <li>Sharp demand zones (aggressive upward departures).</li>
    <li>Sharp supply zones (aggressive downward departures).</li>
    <li>Note zone quality and freshness.</li>
    <li>Identify FVGs within or around zones.</li>
</ul>

<h3>Step 4: Assess confluence</h3>
<p>Rate each zone on confluence:</p>
<ul>
    <li>Does it align with the higher-timeframe trend?</li>
    <li>Does it coincide with a liquidity level?</li>
    <li>Does it align with a Fibonacci retracement level?</li>
    <li>Does it coincide with a moving average?</li>
    <li>Does it contain an FVG or imbalance?</li>
</ul>
<p>More confluence = higher probability.</p>

<h3>Step 5: Wait for the setup</h3>
<p>Don't anticipate. Wait for:</p>
<ul>
    <li>Price to reach the zone.</li>
    <li>A liquidity sweep before the reaction (if applicable).</li>
    <li>A clear reaction signal at the zone (candle pattern, structure break).</li>
</ul>

<h3>Step 6: Execute</h3>
<p>Enter with:</p>
<ul>
    <li>Tight stop beyond the zone.</li>
    <li>Position size based on risk percentage.</li>
    <li>Targets at the next zone or liquidity level.</li>
    <li>Minimum 2:1 R:R, ideally 3:1 or better.</li>
</ul>

<h3>Step 7: Manage</h3>
<ul>
    <li>Move stop to break-even after 1× risk.</li>
    <li>Trail stop with structure.</li>
    <li>Take partial profit at Target 1.</li>
    <li>Exit at Target 2 or on invalidation.</li>
</ul>

<h2>Worked example — EUR/USD zone trade</h2>

<h3>Context</h3>
<ul>
    <li>Daily chart: EUR/USD in a downtrend. LH/LL structure.</li>
    <li>Weekly chart: price at the 200 SMA.</li>
    <li>H4 chart: price approaching a supply zone from below.</li>
</ul>

<h3>Liquidity levels</h3>
<ul>
    <li>Prior day high: 1.0930.</li>
    <li>Equal highs: 1.0935 (tested twice).</li>
    <li>Round number: 1.0900.</li>
</ul>

<h3>Zone identification</h3>
<ul>
    <li>Supply zone at 1.0920–1.0940.</li>
    <li>Sharp downward departure (three large bearish candles).</li>
    <li>Fresh — never tested.</li>
    <li>Confluence: coincides with prior day high, equal highs, round number, and the daily downtrend.</li>
</ul>

<h3>Setup</h3>
<ol>
    <li>Wait for price to rally to the zone at 1.0930.</li>
    <li>Watch for a liquidity sweep of 1.0935 (equal highs).</li>
    <li>Look for a bearish reaction after the sweep.</li>
    <li>Bearish engulfing candle forms at 1.0925.</li>
    <li>Enter short at 1.0925.</li>
    <li>Stop-loss at 1.0950 (25 pips, above the zone).</li>
    <li>Target 1: 1.0870 (55 pips, 2.2:1 R:R).</li>
    <li>Target 2: 1.0820 (105 pips, 4.2:1 R:R).</li>
</ol>

<h3>Management</h3>
<ul>
    <li>Move stop to break-even after price reaches 1.0900 (1× risk).</li>
    <li>Take partial profit at Target 1.</li>
    <li>Trail stop above each new H4 lower high.</li>
    <li>Exit fully at Target 2 or if the trade invalidates (price reclaims 1.0930).</li>
</ul>

<h2>Integration with other concepts</h2>

<h3>Supply/demand + Liquidity</h3>
<p>Zones and liquidity work together:</p>
<ul>
    <li>Zones often form after liquidity sweeps.</li>
    <li>Zones are often located at liquidity levels (PDH, PDL, equal highs/lows).</li>
    <li>The best zones are those that combine with a liquidity sweep.</li>
</ul>

<h3>Supply/demand + Structure</h3>
<p>Zones within structure are stronger:</p>
<ul>
    <li>A demand zone at the higher low of an uptrend is stronger than a random demand zone.</li>
    <li>A supply zone at the lower high of a downtrend is stronger than a random supply zone.</li>
    <li>Zones that align with BOS or MSS events are significant.</li>
</ul>

<h3>Supply/demand + Fibonacci</h3>
<p>Fibonacci levels enhance zones:</p>
<ul>
    <li>A demand zone at the 61.8% retracement is stronger than one at the 23.6%.</li>
    <li>An extension target that aligns with a supply zone is a high-probability exit.</li>
</ul>

<h3>Supply/demand + Momentum</h3>
<p>Momentum confirms zones:</p>
<ul>
    <li>Divergence at a zone suggests reversal.</li>
    <li>Strong momentum approaching a zone suggests the zone may break.</li>
    <li>Exhaustion at a zone confirms the reaction.</li>
</ul>

<h3>Supply/demand + Sessions</h3>
<p>Sessions affect zone trading:</p>
<ul>
    <li>Zones that form during London or NY sessions are stronger.</li>
    <li>Liquidity sweeps during London open or NY open often precede zone reactions.</li>
    <li>Session closes (especially the London fix at 16:00 UTC) often produce zone reactions.</li>
</ul>

<h2>Factual context</h2>
<p>Supply and demand analysis is one of the most widely used approaches in professional trading. It combines concepts from market microstructure (order flow, imbalance), behavioural finance (institutional memory, retail clustering), and classical technical analysis (support/resistance, structure).</p>
<p>The framework's origins trace back to multiple schools. Sam Seiden formalised the modern supply/demand approach in the 2000s. Michael Huddleston (ICT) developed complementary concepts in the 2010s. Al Brooks and other price action educators described the same phenomena in different language.</p>
<p>What unites these frameworks is the underlying insight: markets move because orders move. The location of orders — visible as zones, imbalances, and liquidity — determines where price will react. This is not prediction; it's inference based on the mechanical nature of order flow.</p>
<p>The approach is used by traders at hedge funds, proprietary trading firms, and retail accounts. It's not proprietary — the concepts are public and widely taught. The edge comes not from knowing the concepts but from applying them with discipline and risk management.</p>
<p>Sam Seiden's central insight:</p>
<blockquote><strong>"The market is a mechanism for transferring money from those who don't understand supply and demand to those who do. Learn the mechanism, and you'll be on the right side."</strong></blockquote>
<p>Al Brooks echoes the sentiment:</p>
<blockquote><strong>"The market is not random. It's the aggregate behaviour of millions of participants, most of whom behave predictably. Predictability is where the edge comes from."</strong></blockquote>
<p>Paul Tudor Jones' perspective:</p>
<blockquote><strong>"I don't try to predict the future. I look at what's happening now, understand where the market is likely to move, and position accordingly."</strong></blockquote>
<p>All three capture the same truth: zone trading is not about magic or secret knowledge. It's about understanding where orders sit and positioning yourself to benefit when they're triggered.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Over-relying on zones alone.</strong> Zones work best with confluence. A zone without context is a guess.</li>
    <li><strong>Trading every zone.</strong> Focus on the highest-quality zones. Skip the rest.</li>
    <li><strong>Ignoring higher-timeframe context.</strong> Zones that fight the higher-timeframe trend fail more often.</li>
    <li><strong>Skipping the reaction confirmation.</strong> Enter on the reaction, not on the touch. A touch alone is not a signal.</li>
    <li><strong>Over-managing trades.</strong> Give zone trades room to develop. Tightening stops too early leads to premature exits.</li>
    <li><strong>Revenge-trading after losses.</strong> Zones fail regularly. Move on to the next setup.</li>
    <li><strong>Not tracking zone performance.</strong> Review your zone trades regularly. Which setups worked? Which didn't? Adjust accordingly.</li>
</ul>

<h2>Advanced notes</h2>
<p>Professional zone traders develop "playbooks" — specific setups they trade repeatedly. A playbook might include:</p>
<ol>
    <li><strong>Fresh demand zone + liquidity sweep + bullish engulfing</strong> = long entry.</li>
    <li><strong>Fresh supply zone + liquidity sweep + bearish engulfing</strong> = short entry.</li>
    <li><strong>Invalidated zone + retest from opposite side</strong> = zone flip trade.</li>
    <li><strong>Zone + FVG confluence</strong> = high-probability entry.</li>
</ol>
<p>These playbooks are refined over time based on what works. Professional traders don't invent new setups every day — they execute the same high-probability setups repeatedly, refining them for their own style.</p>
<p>With the supply/demand module complete, you now have one of the most important tools in advanced trading. The next module — Advanced Market Structure — will build on these concepts by formalising the structural framework that determines when zones are likely to work and when they're likely to fail.</p>
HTML,
        ],

    ],
];