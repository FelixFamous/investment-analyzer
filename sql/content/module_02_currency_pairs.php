<?php
/**
 * Module 02 — Currency Pairs
 * Run via:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_02_currency_pairs.php
 */

return [
    'module' => [
        'level_slug' => 'beginner',
        'slug'       => 'currency-pairs',
        'title'      => 'Currency Pairs',
        'description'=> 'Every Forex trade is a bet on the relationship between two currencies. Learn how pairs are structured, what the base and quote currencies mean, and why EUR/USD behaves differently from USD/TRY.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Read any currency pair and know exactly what you're buying and selling\n" .
            "• Distinguish base currency from quote currency\n" .
            "• Understand majors, minors, and exotics\n" .
            "• Read currency correlations and know why they matter",
        'sort_order' => 2,
    ],

    'lessons' => [

        [
            'slug'   => 'what-is-a-currency-pair',
            'title'  => 'What Is a Currency Pair?',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define a currency pair\n" .
                "• Explain why currencies must be quoted in pairs\n" .
                "• Read a pair correctly (EUR/USD, USD/JPY, etc.)",
            'prerequisites' => 'What Is Forex?',
            'sort_order' => 1,
            'summary' => 'A currency pair is the quotation of two currencies relative to each other. You can never buy "a dollar" in isolation — you always buy dollars with something else. The pair tells you how much of the quote currency is needed to buy one unit of the base currency.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine walking into a currency exchange at an airport. You hand over 100 US dollars and receive Japanese yen. The clerk doesn't say "the dollar is worth X" — they say "one dollar buys Y yen." That ratio is the price of a <strong>currency pair</strong>.</p>
<p>Every Forex trade involves <em>two</em> currencies. You're never just buying — you're always buying one currency <em>with</em> another. The pair tells you which two, and in what order.</p>

<h2>Real-world analogy</h2>
<p>Think of it like trading sports cards. You don't say "this card is worth $50." You say "this card trades for 50 units of cash." The card's value only exists relative to something else. Currencies work the same way — the dollar's value only exists relative to another currency.</p>

<h2>Professional explanation</h2>
<p>A <strong>currency pair</strong> (also called an FX pair or forex pair) is a quotation of two currencies where one currency's value is expressed in terms of the other. The format is:</p>
<p><code>BASE/QUOTE</code></p>
<p>For example, in <strong>EUR/USD</strong>:</p>
<ul>
    <li><strong>EUR</strong> is the base currency (the one you're buying or selling)</li>
    <li><strong>USD</strong> is the quote currency (the one you're pricing it in)</li>
</ul>
<p>If EUR/USD = 1.0850, that means <strong>1 euro buys 1.0850 US dollars</strong>.</p>

<h3>Reading a pair out loud</h3>
<p>When a trader says "EUR/USD is bid at 1.0850," they mean: the market will pay you $1.0850 for every euro you sell. When they say "the offer is 1.0852," they mean: the market will sell you euros at $1.0852 each. The difference — 2 pips — is the spread.</p>

<h3>Why pairs are always quoted the same way</h3>
<p>Markets are convention-driven. EUR/USD is always EUR first, USD second — never reversed. This is a market-standard ordering, and every quote you see anywhere follows it. If you ever see USD/EUR quoted, that's an unusual broker showing an inverted pair (rare and typically more expensive).</p>

<h2>What a trade actually does</h2>
<p>When you click "Buy EUR/USD" for 1 standard lot (100,000 units):</p>
<ol>
    <li>You buy <strong>100,000 euros</strong></li>
    <li>You simultaneously sell <strong>108,500 US dollars</strong> (at 1.0850)</li>
    <li>You now hold a long EUR / short USD position</li>
</ol>
<p>If EUR/USD rises to 1.0900, your euros are worth more dollars. If it falls to 1.0800, they're worth less. You're never buying euros with nothing — you're always funding the purchase by selling dollars.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Thinking you only buy one currency.</strong> Every long is also a short. If EUR/USD goes up, it's because EUR strengthened, USD weakened, or both.</li>
    <li><strong>Reading the pair backwards.</strong> In USD/JPY, if the price rises, the <em>dollar</em> is strengthening against the yen. In EUR/USD, if the price rises, the <em>euro</em> is strengthening against the dollar. The direction of "strength" depends on which currency is base.</li>
    <li><strong>Assuming all pairs are quoted the same way.</strong> The base/quote convention differs by pair — and it matters.</li>
</ul>

<h2>Advanced notes</h2>
<p>Currency pairs are quoted in <strong>pips</strong> — the smallest standardised price move. For most pairs a pip is 0.0001; for JPY pairs it's 0.01. We cover pips in full detail in the next module. For now, know that a move of 1.0850 to 1.0851 is "one pip" on EUR/USD.</p>
HTML,
        ],

        [
            'slug'   => 'base-and-quote-currency',
            'title'  => 'Base Currency and Quote Currency',
            'difficulty' => 'beginner',
            'estimated_duration' => 9,
            'learning_objectives' =>
                "• Define base currency and quote currency\n" .
                "• Determine what each represents in a trade\n" .
                "• Calculate profit/loss using base and quote logic",
            'prerequisites' => 'What Is a Currency Pair?',
            'sort_order' => 2,
            'summary' => 'The base currency is the first currency in a pair — the one you buy or sell. The quote currency is the second — the one you price the base in. Every pair has exactly one of each, and their roles never swap.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Every price tag has two parts: what you're buying, and what you're paying with. "3 apples for $2" tells you the apples are the item and the dollars are the payment. Currency pairs work the same way.</p>
<p>In <strong>GBP/USD</strong>, the pound is the item, and the dollar is the payment. The quote "GBP/USD = 1.27" means one pound costs $1.27.</p>

<h2>Real-world analogy</h2>
<p>Think of a grocery store shelf. The item on the left is what you're buying. The price on the right is what you pay. Base currency is the item; quote currency is the price.</p>

<h2>Professional explanation</h2>
<h3>Base currency</h3>
<p>The <strong>base currency</strong> is always the first currency in the pair. It is:</p>
<ul>
    <li>The currency you are buying (if you go long) or selling (if you go short)</li>
    <li>Always expressed in units of exactly <strong>one</strong> (1 EUR, 1 GBP, 1 USD)</li>
    <li>What determines whether your position is long or short</li>
</ul>

<h3>Quote currency</h3>
<p>The <strong>quote currency</strong> (also called the <em>counter currency</em>, <em>secondary currency</em>, or <em>term currency</em>) is always the second currency. It is:</p>
<ul>
    <li>The currency you use to pay for the base</li>
    <li>The currency your profit or loss is initially denominated in</li>
    <li>What you receive or give up when the trade closes</li>
</ul>

<h3>Putting it together</h3>
<p>For <strong>EUR/USD = 1.0850</strong>:</p>
<ul>
    <li>Buying 1 lot (100,000 EUR) means you pay 100,000 × 1.0850 = <strong>108,500 USD</strong></li>
    <li>If EUR/USD rises to 1.0900 and you close, you receive 100,000 × 1.0900 = <strong>109,000 USD</strong></li>
    <li>Your profit = 109,000 − 108,500 = <strong>500 USD</strong> (before costs)</li>
</ul>
<p>The profit or loss is always calculated in the <em>quote</em> currency, then converted to your account currency if needed.</p>

<h3>Why this ordering matters</h3>
<p>The convention isn't arbitrary. It reflects the market's historical quoting habits and liquidity structure. GBP/USD is called "cable" because quotes used to travel by transatlantic telegraph cable between London and New York. USD/JPY exists because Japan is a major trading partner of the US. These orderings are baked into every platform and API.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Thinking "long" means buying the quote currency.</strong> Going long EUR/USD means buying <em>euros</em> — the base. Going long USD/JPY means buying <em>dollars</em> — also the base. It's always the base you're buying.</li>
    <li><strong>Assuming profit is in the base currency.</strong> It's in the quote currency first. If your account is in USD and you trade USD/JPY, your JPY profit gets converted back to USD at the prevailing rate.</li>
    <li><strong>Confusing the order on different platforms.</strong> Some platforms display pairs with USD first when USD is base; others don't. Always read the pair symbol carefully.</li>
</ul>

<h2>Advanced notes</h2>
<p>The base/quote convention becomes critical when you trade crosses. In EUR/GBP, the euro is base and the pound is quote. Your profit will be in pounds. If your account is in USD, your broker converts GBP profit back to USD at the prevailing GBP/USD rate — which introduces a small extra layer of currency exposure. This is why many retail traders stick to pairs that include their account currency.</p>
HTML,
        ],

        [
            'slug'   => 'majors-minors-exotics',
            'title'  => 'Majors, Minors, and Exotics',
            'difficulty' => 'beginner',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Categorise any pair as major, minor, or exotic\n" .
                "• Explain why majors have tighter spreads\n" .
                "• Understand the risk trade-off between the three groups",
            'prerequisites' => 'Base Currency and Quote Currency',
            'sort_order' => 3,
            'summary' => 'The Forex market divides pairs into three tiers: majors (always include USD, tightest spreads), minors or crosses (no USD, still liquid), and exotics (a major paired with a smaller economy, wide spreads and high risk). Knowing which tier you are trading tells you what to expect from execution.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Not all currency pairs are equal. Some are traded by everyone, all the time, with tiny costs. Others are traded by few, with big costs and wild swings. Traders group them into three tiers so you know what you're getting into.</p>

<h2>Real-world analogy</h2>
<p>Think of airports. A major international airport handles millions of passengers with dozens of airlines. A regional airport handles thousands with a handful of airlines. A private airstrip handles a few planes a month. Same sky, different scale. Currencies work the same way.</p>

<h2>Professional explanation</h2>

<h3>1. Major pairs</h3>
<p>Major pairs always include the US dollar on one side, paired with another major world currency. There are seven of them, and they account for the majority of global FX volume:</p>
<ul>
    <li><strong>EUR/USD</strong> — euro / US dollar. The most traded pair on earth.</li>
    <li><strong>USD/JPY</strong> — US dollar / Japanese yen. Second-most traded.</li>
    <li><strong>GBP/USD</strong> — British pound / US dollar. Known as "cable."</li>
    <li><strong>USD/CHF</strong> — US dollar / Swiss franc. Called "the swissy."</li>
    <li><strong>AUD/USD</strong> — Australian dollar / US dollar. A "commodity currency" pair.</li>
    <li><strong>USD/CAD</strong> — US dollar / Canadian dollar. Called "the loonie."</li>
    <li><strong>NZD/USD</strong> — New Zealand dollar / US dollar. Called "the kiwi."</li>
</ul>
<p>Characteristics: tightest spreads (often under 1 pip on EUR/USD), highest liquidity, most predictable behaviour, best for beginners.</p>

<h3>2. Minor pairs (crosses)</h3>
<p>Minor pairs do not include the US dollar. They combine two other major currencies:</p>
<ul>
    <li><strong>EUR/GBP</strong> — euro / pound</li>
    <li><strong>EUR/JPY</strong> — euro / yen</li>
    <li><strong>GBP/JPY</strong> — pound / yen. Nicknamed "the beast" for its volatility.</li>
    <li><strong>AUD/NZD</strong> — Australian dollar / New Zealand dollar</li>
    <li><strong>EUR/CHF</strong> — euro / Swiss franc</li>
</ul>
<p>Characteristics: slightly wider spreads than majors, still very liquid, often driven by regional economic factors. A good middle ground once you're comfortable with a major.</p>

<h3>3. Exotic pairs</h3>
<p>Exotic pairs combine a major currency with the currency of a smaller or emerging economy:</p>
<ul>
    <li><strong>USD/TRY</strong> — US dollar / Turkish lira</li>
    <li><strong>USD/ZAR</strong> — US dollar / South African rand</li>
    <li><strong>USD/MXN</strong> — US dollar / Mexican peso</li>
    <li><strong>USD/NGN</strong> — US dollar / Nigerian naira (limited availability)</li>
    <li><strong>USD/TRY</strong>, <strong>USD/BRL</strong>, <strong>USD/INR</strong>, <strong>USD/PLN</strong></li>
</ul>
<p>Characteristics: wide spreads (often 5–50+ pips), lower liquidity, higher volatility, more vulnerable to political and central-bank interventions. Not recommended for beginners.</p>

<h2>Factual context</h2>
<p>According to the Bank for International Settlements (BIS) 2025 Triennial Survey, global FX turnover hit <strong>$9.6 trillion per day</strong> in April 2025 — up 28% from $7.5 trillion in 2022[reference:0]. The vast majority of that volume flows through the seven major pairs. EUR/USD alone accounts for roughly 23% of all FX trading, and USD/JPY around 14%[reference:1].</p>
<p>The BIS has been running this survey every three years since 1989. The growth from below $100 billion per day in the 1970s to $9.6 trillion today reflects the explosion of global trade and capital flows[reference:2].</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Jumping straight to exotics for "bigger moves."</strong> Bigger moves mean bigger losses too, and wider spreads eat into any edge.</li>
    <li><strong>Assuming minors are always cheaper than exotics.</strong> They usually are, but volatility in GBP/JPY can rival anything on the exotic list.</li>
    <li><strong>Ignoring session timing.</strong> EUR/USD is most liquid during London/New York overlap; AUD/NZD is most liquid during the Sydney/Tokyo session. Trading a pair outside its natural session means wider spreads.</li>
</ul>

<h2>Advanced notes</h2>
<p>The classification isn't formal — it's market convention. Some traders consider AUD/USD and NZD/USD "commodity majors" because their currencies are heavily influenced by commodity exports (iron ore and gold for AUD, dairy for NZD). Others treat EUR/CHF as a hybrid. The practical upshot: know your pair's liquidity profile, its natural trading session, and what drives it before you trade it.</p>
HTML,
        ],

        [
            'slug'   => 'currency-correlations',
            'title'  => 'Currency Correlations',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define positive and negative currency correlation\n" .
                "• Identify the most important correlations in FX\n" .
                "• Explain why correlations matter for risk management",
            'prerequisites' => 'Majors, Minors, and Exotics',
            'sort_order' => 4,
            'summary' => 'Currency pairs move in relationship to each other. Positive correlation means two pairs tend to move in the same direction. Negative correlation means they tend to move in opposite directions. Understanding these relationships prevents accidental double-exposure and helps you read the market in context.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Some pairs are like twins — when one goes up, the other usually goes up too. Others are like rivals — when one rises, the other usually falls. These relationships are called <strong>correlations</strong>, and they matter because they tell you when you're accidentally making the same bet twice.</p>

<h2>Real-world analogy</h2>
<p>Think of two shops on the same street. When one has a big sale, the other often sees more foot traffic too. They move together. Now think of two competing shops — when one drops prices, the other loses customers. They move in opposite directions. Currency pairs behave the same way.</p>

<h2>Professional explanation</h2>
<p><strong>Correlation</strong> is a statistical measure of how two things move relative to each other. In FX, it's expressed as a number between −1 and +1:</p>
<ul>
    <li><strong>+1.0</strong> — perfect positive correlation (pairs move in lockstep)</li>
    <li><strong>+0.7 to +1.0</strong> — strong positive correlation</li>
    <li><strong>0</strong> — no correlation (random relationship)</li>
    <li><strong>−0.7 to −1.0</strong> — strong negative correlation</li>
    <li><strong>−1.0</strong> — perfect negative correlation (pairs move exactly opposite)</li>
</ul>
<p>Anything above +0.7 or below −0.7 is considered a strong relationship worth tracking.</p>

<h3>Key FX correlations</h3>

<h4>Positive correlations</h4>
<ul>
    <li><strong>EUR/USD and GBP/USD</strong> — both represent European currencies against the dollar. When the dollar weakens, both tend to rise together.</li>
    <li><strong>AUD/USD and NZD/USD</strong> — both are commodity-linked "risk-on" currencies from the Asia-Pacific region.</li>
    <li><strong>USD/CHF and USD/JPY</strong> — both are "safe-haven" pairs where the dollar is base. When risk sentiment sours, both tend to rise.</li>
</ul>

<h4>Negative correlations</h4>
<ul>
    <li><strong>EUR/USD and USD/CHF</strong> — if EUR/USD rises, USD/CHF usually falls. Both represent euro-dollar and dollar-franc dynamics that mirror each other.</li>
    <li><strong>USD/CAD and crude oil</strong> — Canada is a major oil exporter. When oil rises, the Canadian dollar strengthens, so USD/CAD falls. This is one of the most-watched inverse relationships in FX[reference:3].</li>
    <li><strong>Gold (XAU/USD) and the US dollar</strong> — gold is priced in dollars, so a stronger dollar usually means lower gold prices. This is a classic negative correlation[reference:4].</li>
</ul>

<h2>Why correlations matter for risk</h2>
<p>Imagine you go long EUR/USD and short USD/CHF at the same time. You think you've made two different trades. But because these pairs are strongly negatively correlated, you've actually made the <em>same bet twice</em>. If one wins, both win. If one loses, both lose. Your risk is doubled, not diversified.</p>
<p>Conversely, if you go long EUR/USD and long USD/CHF, you've inadvertently hedged yourself — the two positions partially cancel out, meaning neither can produce a big win or loss on its own.</p>

<h2>Factual context</h2>
<p>Legendary investor George Soros built his reputation partly on understanding correlation and reflexivity — the idea that "prices do in fact influence fundamentals," and that market participants' beliefs shape the very reality they are trying to predict[reference:5]. His famous 1992 trade against the British pound was not a random bet — it was a carefully constructed thesis about how the pound's peg to the European Exchange Rate Mechanism would break under pressure. Soros understood that currencies don't move in isolation; they move in relation to interest rates, capital flows, and each other.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading two correlated pairs without realising it.</strong> This is the most common error. Always check the correlation before opening a second position.</li>
    <li><strong>Assuming correlations are permanent.</strong> They shift over time. A strong correlation in one quarter might weaken or reverse in the next.</li>
    <li><strong>Using correlation as a trading signal.</strong> Correlation tells you how things relate, not what to do. It's a risk-management tool, not an entry trigger.</li>
</ul>

<h2>Advanced notes</h2>
<p>Correlations are usually calculated over rolling windows — 30 days, 90 days, or 1 year. Short windows capture recent behaviour; longer windows smooth out noise. Most brokers publish a correlation matrix in their research section. If you trade multiple pairs at once, checking this matrix once a week is a good habit. It will tell you when you're overexposed to a single theme — like "short dollar" — even if you think you're diversified.</p>
HTML,
        ],

        [
            'slug'   => 'reading-currency-strength',
            'title'  => 'Reading Currency Strength',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
                            'learning_objectives' =>
                "• Explain what \"currency strength\" means\n" .
                "• Distinguish between individual currency strength and pair movement\n" .
                "• Use a simple framework to rank currency strength",
            'prerequisites' => 'Currency Correlations',
            'sort_order' => 5,
            'summary' => 'A currency pair can move because the base strengthened, the quote weakened, or both. Reading individual currency strength — rather than just watching pair prices — gives you a clearer picture of what is actually happening in the market.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>If EUR/USD goes up, is it because Europe is doing well, or because America is doing badly? Looking at a single pair can't tell you. To answer that, you have to look at how each currency behaves against <em>everything else</em>.</p>

<h2>Real-world analogy</h2>
<p>Imagine two football teams play and one wins 3–0. Did the winner play brilliantly, or did the loser play terribly? You can't know from one match. You'd have to see how each team performs against other opponents. Currency strength works the same way.</p>

<h2>Professional explanation</h2>
<p><strong>Currency strength</strong> is a measure of how a single currency is performing against a basket of other currencies, rather than against just one. A strong currency tends to rise against most of its peers. A weak currency tends to fall against most of them.</p>

<h3>Why pairs can mislead</h3>
<p>Consider EUR/USD rising from 1.0800 to 1.0900. Three scenarios produce the same result:</p>
<ol>
    <li><strong>EUR strengthened</strong> — the euro gained across the board.</li>
    <li><strong>USD weakened</strong> — the dollar fell against everything.</li>
    <li><strong>Both</strong> — the euro rose, the dollar fell, and the pair moved accordingly.</li>
</ol>
<p>If you only look at the pair, you can't distinguish these. If you look at EUR/GBP, EUR/JPY, and EUR/CHF, you can see whether the euro itself is strong. If those are also rising, EUR is genuinely strong. If they're flat or falling, EUR/USD's move was likely driven by dollar weakness.</p>

<h3>A simple strength framework</h3>
<p>You don't need a complex algorithm. A simple method:</p>
<ol>
    <li>Pick the 8 major currencies: USD, EUR, GBP, JPY, CHF, AUD, NZD, CAD.</li>
    <li>For each currency, look at how it performs against the others over the past day or week.</li>
    <li>Rank them from strongest to weakest.</li>
    <li>Trade the strongest against the weakest.</li>
</ol>
<p>For example, if the ranking shows USD strongest and JPY weakest, then USD/JPY is your pair — the strongest currency against the weakest. This simple discipline alone can improve trade selection for beginners.</p>

<h2>Factual context</h2>
<p>Warren Buffett's partner Charlie Munger once said that Buffett's greatest advantage was that he was "a learning machine." Buffett himself famously warned: <strong>"Risk comes from not knowing what you're doing."</strong>[reference:6]. Reading currency strength is one of the first steps out of "not knowing" — it replaces guesswork with an observable, repeatable process.</p>
<p>Buffett also gave us one of the most quoted lines in trading: <strong>"The market is a device for transferring money from the impatient to the patient."</strong>[reference:7]. Strength analysis takes patience — you wait for the strongest-vs-weakest setup rather than forcing trades on random pairs.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Trading a pair without checking the other side.</strong> If you're long EUR/USD, at least glance at EUR/GBP and USD/CHF to see whether the euro is leading or the dollar is falling.</li>
    <li><strong>Assuming strength is permanent.</strong> A currency can be strong for weeks, then reverse on a single central-bank statement. Strength is a snapshot, not a destiny.</li>
    <li><strong>Chasing yesterday's strongest currency.</strong> By the time a move is obvious, much of it may already be priced in.</li>
</ul>

<h2>Advanced notes</h2>
<p>Institutional desks run "strength meters" that rank all major currencies in real time. You can approximate the same thing with a spreadsheet: for each currency, average its percentage change against the 7 other majors over the same period. The result is a strength score. Some retail platforms include this as a built-in indicator — if your platform does, it's worth checking once a day before your session.</p>
HTML,
        ],

    ],
];