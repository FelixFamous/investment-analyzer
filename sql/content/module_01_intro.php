<?php
/**
 * Module 01 — Introduction to Financial Markets
 *
 * Returns a module definition + lesson list. Run via:
 *   php bin/seed-academy.php sql/content/module_01_intro.php
 */

return [
    'module' => [
        'level_slug' => 'beginner',
        'slug'       => 'intro-financial-markets',
        'title'      => 'Introduction to Financial Markets',
        'description'=> 'Start from zero. Understand what money is, how currencies differ from it, why markets exist, and who actually moves prices.',
        'learning_objectives' =>
            "By the end of this module you will:\n" .
            "• Define money, currency, and a financial market clearly\n" .
            "• Explain what Forex is and why it exists\n" .
            "• List the major participants in the FX market and their roles\n" .
            "• Describe how a currency price is actually formed",
        'sort_order' => 1,
    ],

    'lessons' => [

        /* ============================================================
         *  LESSON 1 — WHAT IS MONEY?
         * ============================================================ */
        [
            'slug'   => 'what-is-money',
            'title'  => 'What Is Money?',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define money\n" .
                "• Name the three functions of money\n" .
                "• Explain why money replaced barter",
            'prerequisites' => 'None. This is the starting point.',
            'sort_order' => 1,
            'summary' => 'Money is any item widely accepted as a medium of exchange, a unit of account, and a store of value. Without it, trade depends on barter, which is slow and unreliable. Every financial market exists because money exists.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine you bake bread. You want a pair of shoes. The shoemaker wants milk, not bread. So you have to find a dairy farmer who wants bread, trade your bread for milk, then find the shoemaker again and trade the milk for shoes. That's exhausting, and it only gets worse as more people and more goods join the picture.</p>
<p>This system is called <strong>barter</strong>. It works in tiny villages but collapses in any complex society.</p>
<p><strong>Money solves this problem.</strong> It is a thing that everyone in a society agrees to accept as payment. You sell your bread for money, then use the money to buy shoes from anyone, any time. No double-coincidence of wants required.</p>

<h2>Real-world analogy</h2>
<p>Think of money like tokens at an arcade. You don't trade your toy car directly for a stuffed bear — you buy tokens, and everyone accepts tokens. The arcade owner sets the rules, and everyone plays along. Money is the universal token of an economy.</p>

<h2>Professional explanation</h2>
<p>Economists define money by the three jobs it performs:</p>
<ul>
    <li><strong>Medium of exchange</strong> — it is accepted as payment for goods and services.</li>
    <li><strong>Unit of account</strong> — it gives a common measure of value. A car is worth X, a coffee is worth Y, and we can compare them because both are priced in the same unit.</li>
    <li><strong>Store of value</strong> — it holds purchasing power over time so you can save today and spend tomorrow.</li>
</ul>
<p>For something to be money, it must do all three reasonably well. Gold, silver, salt, cowrie shells, and paper notes have all served as money at different times in history.</p>

<h2>Why money matters for traders</h2>
<p>Every price you see on a chart — EUR/USD, BTC, AAPL — is a ratio of one form of money against another. When EUR/USD rises, it means euros are becoming more valuable relative to dollars. Trading is, at the deepest level, the exchange of one form of value for another. Understanding this is the foundation for everything that follows.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Confusing money with currency.</strong> Currency is a physical or digital <em>form</em> of money. All currency is money, but not all money is currency. Gold is money but not currency.</li>
    <li><strong>Assuming money has intrinsic value.</strong> A $100 note is just paper. Its value comes from shared agreement and, ultimately, from the government and central bank backing it.</li>
    <li><strong>Thinking money is a fixed measure.</strong> Money's purchasing power changes over time — that's what inflation is. $100 today buys less than $100 bought 30 years ago.</li>
</ul>

<h2>Advanced notes</h2>
<p>Modern "money" is mostly not physical. Over 90% of the money supply in developed economies exists only as digital entries in bank databases. When you "have" $5,000 in a bank account, what you actually have is a legal claim on the bank — the bank owes you $5,000 and holds your deposit as a liability. This matters for understanding liquidity, credit, and central banking later in the course.</p>

<h2>Practice</h2>
<p>Ask yourself: <em>Is a video game currency like V-Bucks "money"?</em> Test it against the three functions. Is it a medium of exchange inside Fortnite? Yes. Is it a unit of account? Partly. Is it a store of value? No — it can be bought but not redeemed for real currency, and its value is entirely controlled by Epic Games. So it fails the third test. It's closer to a token than money.</p>
HTML,
        ],

        /* ============================================================
         *  LESSON 2 — WHAT IS CURRENCY?
         * ============================================================ */
        [
            'slug'   => 'what-is-currency',
            'title'  => 'What Is Currency?',
            'difficulty' => 'beginner',
            'estimated_duration' => 9,
            'learning_objectives' =>
                "• Define currency\n" .
                "• Explain what gives a currency its value\n" .
                "• Distinguish currency from money",
            'prerequisites' => 'What Is Money?',
            'sort_order' => 2,
            'summary' => 'Currency is a specific form of money issued by a government or central bank and used as legal tender within a country. Most modern currency is fiat — its value rests on trust in the issuing authority, not on any physical backing.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Money is the idea. Currency is the specific version. The US dollar, the euro, the Japanese yen, and the Nigerian naira are all currencies. Each one is issued by a government or a central bank and is the official money of a country or region.</p>
<p>When you travel from France to Japan, you can't spend euros in Tokyo — you swap them for yen. That swap is a currency exchange, and it's exactly what the foreign exchange market exists to do.</p>

<h2>Real-world analogy</h2>
<p>Think of currencies like national languages. Everyone speaks <em>some</em> language, and language lets people communicate. But a French speaker and a Japanese speaker need a translator. A currency exchange does the same job for value — it translates dollars into yen so trade can happen.</p>

<h2>Professional explanation</h2>
<p>A <strong>currency</strong> is a system of money in general use within a specific economy. Characteristics of a modern currency:</p>
<ul>
    <li>Issued or authorised by a central bank or government</li>
    <li>Declared <strong>legal tender</strong> — must be accepted for settling debts in that jurisdiction</li>
    <li>Has a currency code (USD, EUR, JPY, NGN) and usually a symbol ($, €, ¥, ₦)</li>
    <li>Exists both as physical cash and as digital bank reserves</li>
</ul>

<h3>Fiat vs commodity-backed currency</h3>
<p><strong>Commodity-backed</strong> currency can be redeemed for a fixed amount of a physical good (gold or silver). Until 1971, the US dollar was legally redeemable for gold at $35 per ounce.</p>
<p><strong>Fiat</strong> currency — the type used everywhere today — has no physical backing. Its value depends entirely on:
</p>
<ul>
    <li><strong>Trust</strong> that the issuing government will honour its obligations</li>
    <li><strong>Scarcity</strong> — the central bank restricts how much is printed</li>
    <li><strong>Demand</strong> — people need it to pay taxes, buy goods, and settle international trade in that country</li>
</ul>

<h2>What makes a currency strong or weak?</h2>
<p>When traders say a currency is "strong," they mean it buys more of other currencies than it used to. Strength comes from a combination of:</p>
<ul>
    <li>High interest rates relative to peers</li>
    <li>Low and stable inflation</li>
    <li>Strong economic growth</li>
    <li>Political stability</li>
    <li>Large trade surpluses</li>
</ul>
<p>The opposite factors weaken a currency. This is the core of <em>fundamental analysis</em>, which we cover in a later module.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Thinking currencies are backed by gold.</strong> They haven't been since 1971. Modern currencies are backed by nothing but agreement and central-bank discipline.</li>
    <li><strong>Confusing a currency's code with its symbol.</strong> EUR is the code, € is the symbol. Both are correct — they're just different representations.</li>
    <li><strong>Assuming a strong currency is always better.</strong> For exporters, a weak currency often means more competitive prices abroad. Governments sometimes prefer a weaker currency for exactly this reason.</li>
</ul>

<h2>Advanced notes</h2>
<p>The <strong>reserve currency</strong> concept matters here. A reserve currency is one held in large quantities by other central banks for international trade and debt settlement. The US dollar holds this status today — roughly 60% of global foreign exchange reserves and 90% of FX transactions involve USD. This gives the US a structural advantage: it can borrow in its own currency on a scale no other country can match.</p>
HTML,
        ],

        /* ============================================================
         *  LESSON 3 — WHAT IS A FINANCIAL MARKET?
         * ============================================================ */
        [
            'slug'   => 'what-is-a-financial-market',
            'title'  => 'What Is a Financial Market?',
            'difficulty' => 'beginner',
            'estimated_duration' => 11,
            'learning_objectives' =>
                "• Define a financial market\n" .
                "• Identify the major types of financial markets\n" .
                "• Explain what all markets have in common",
            'prerequisites' => 'What Is Money? · What Is Currency?',
            'sort_order' => 3,
            'summary' => 'A financial market is any place — physical or electronic — where buyers and sellers trade financial assets at prices agreed between them. Stocks, bonds, currencies, and commodities each have their own markets, but the mechanics are the same.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Imagine a village market where farmers bring vegetables and buyers show up with money. Prices get negotiated on the spot. If many people want tomatoes, the price goes up. If nobody wants them, the price falls.</p>
<p>A <strong>financial market</strong> works the same way, except the things being traded are not vegetables — they're financial assets: shares of companies, government bonds, currencies, or contracts tied to commodities like oil and gold.</p>
<p>Instead of a physical market square, modern financial markets are electronic networks. Buyers and sellers are computers on opposite sides of the world, matched by exchanges that operate 24 hours a day.</p>

<h2>Real-world analogy</h2>
<p>Think of a financial market like an auction house. Sellers bring goods, buyers bring money, and the auctioneer matches them at the highest price any buyer will pay. Financial markets work exactly like that, except there are millions of items, millions of participants, and the auction never closes.</p>

<h2>Professional explanation</h2>
<p>A <strong>financial market</strong> is a mechanism through which buyers and sellers determine the price of a financial asset and exchange it. It provides three core services:</p>
<ul>
    <li><strong>Price discovery</strong> — the market finds the fair price through supply and demand.</li>
    <li><strong>Liquidity</strong> — participants can buy and sell quickly without moving the price much.</li>
    <li><strong>Capital allocation</strong> — money flows from savers to borrowers, from investors to businesses.</li>
</ul>

<h3>Major types of financial markets</h3>
<ul>
    <li><strong>Stock market</strong> — shares of publicly traded companies (AAPL, MSFT, TSLA).</li>
    <li><strong>Bond market</strong> — government and corporate debt. Often larger than stock markets, especially in the US.</li>
    <li><strong>Foreign exchange market (Forex / FX)</strong> — currencies. The largest market in the world by volume.</li>
    <li><strong>Commodity market</strong> — physical goods like crude oil, gold, wheat, coffee.</li>
    <li><strong>Derivatives market</strong> — contracts whose value is derived from another asset (futures, options, swaps).</li>
    <li><strong>Cryptocurrency market</strong> — digital assets (BTC, ETH), which trade 24/7 like FX but with very different dynamics.</li>
</ul>

<h3>How a market works — the three roles</h3>
<ul>
    <li><strong>Buyers</strong> — participants who want to acquire the asset.</li>
    <li><strong>Sellers</strong> — participants who want to give it up.</li>
    <li><strong>Intermediaries</strong> — exchanges, brokers, and clearinghouses that match the two sides and guarantee settlement.</li>
</ul>
<p>Every trade in every market has exactly one buyer and one seller. Every price you see on a chart is the price at which those two agreed.</p>

<h2>What all markets have in common</h2>
<p>Whether you're trading Bitcoin or Bulgarian government bonds, four things are always true:</p>
<ol>
    <li>A <strong>price</strong> exists that both sides agreed to.</li>
    <li><strong>Supply and demand</strong> determine that price in real time.</li>
    <li>There is a <strong>bid</strong> (best price a buyer will pay) and an <strong>ask</strong> (best price a seller will take). The gap is the <strong>spread</strong>.</li>
    <li><strong>Volatility</strong> — the speed at which prices move — varies by market and by time of day.</li>
</ol>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Thinking markets are inherently fair.</strong> Markets are efficient in the long run but can be irrational, manipulated, and reflexive in the short term.</li>
    <li><strong>Believing "the market" is a person.</strong> There is no single entity. Prices emerge from millions of individual decisions.</li>
    <li><strong>Assuming more volatility means more opportunity.</strong> Volatility means opportunity <em>and</em> risk in equal measure.</li>
</ul>

<h2>Advanced notes</h2>
<p>The global Forex market trades roughly <strong>$7.5 trillion per day</strong> (BIS Triennial Survey, 2022). For comparison, the NYSE trades around $30–50 billion per day. FX is over 150 times larger. Most of that volume is not retail speculation — it's banks, corporations, and central banks managing real-world currency needs. This is why FX is considered the most liquid market on earth.</p>
HTML,
        ],

        /* ============================================================
         *  LESSON 4 — WHAT IS FOREX?
         * ============================================================ */
        [
            'slug'   => 'what-is-forex',
            'title'  => 'What Is Forex?',
            'difficulty' => 'beginner',
            'estimated_duration' => 10,
            'learning_objectives' =>
                "• Define Forex and where the name comes from\n" .
                "• Explain why Forex is the largest market in the world\n" .
                "• Understand what a currency pair represents",
            'prerequisites' => 'What Is a Financial Market?',
            'sort_order' => 4,
            'summary' => 'Forex (foreign exchange, FX) is the global market where currencies are bought and sold. It is the largest and most liquid market on earth, trading over $7 trillion a day through a decentralised network of banks, brokers, and electronic platforms.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Whenever someone in Nigeria wants to buy a phone from Japan, someone has to convert naira into yen. Whenever a French tourist spends euros in New York, someone converts euros into dollars. Every time two countries do business, currencies must be exchanged.</p>
<p>All of these exchanges happen in the same place: the <strong>foreign exchange market</strong> — or <strong>Forex</strong> for short.</p>
<p>It's not a physical building. It's a global electronic network connecting banks, brokers, corporations, and governments. It runs 24 hours a day, five days a week, and it never sleeps.</p>

<h2>Real-world analogy</h2>
<p>Imagine the world has hundreds of airports, each with its own currency. People constantly fly between them, needing local cash. Forex is the giant, all-day, all-night currency exchange desk at every airport, simultaneously, with prices updating every second.</p>

<h2>Professional explanation</h2>
<p><strong>Forex</strong> (also written <strong>FX</strong> or <strong>foreign exchange</strong>) is the market in which national currencies are exchanged for one another. Its name combines "foreign" and "exchange."</p>

<h3>What makes Forex unique</h3>
<ul>
    <li><strong>Size</strong> — roughly $7.5 trillion traded per day (BIS 2022). The largest financial market in existence.</li>
    <li><strong>Decentralised</strong> — there is no single exchange like the NYSE. Trading happens <strong>over-the-counter (OTC)</strong> across a global network of banks and brokers.</li>
    <li><strong>24/5</strong> — opens Sunday evening (Sydney) and closes Friday evening (New York).</li>
    <li><strong>Liquidity</strong> — the top pairs (EUR/USD, USD/JPY, GBP/USD) trade in sizes that would crush most other markets.</li>
    <li><strong>Low spreads</strong> — because of that liquidity, the difference between buy and sell price on major pairs is often just a fraction of a pip.</li>
</ul>

<h3>What is being traded</h3>
<p>Currencies are always traded in <strong>pairs</strong>. You can never buy "a dollar" in isolation — you buy dollars <em>with</em> something else. When you buy EUR/USD, you're simultaneously buying euros and selling dollars.</p>
<p>There are three broad categories of pairs:</p>
<ul>
    <li><strong>Majors</strong> — the most liquid pairs, always including USD. EUR/USD, USD/JPY, GBP/USD, USD/CHF, USD/CAD, AUD/USD, NZD/USD.</li>
    <li><strong>Minors / crosses</strong> — pairs without USD. EUR/GBP, EUR/JPY, GBP/JPY, AUD/NZD.</li>
    <li><strong>Exotics</strong> — a major paired with a smaller economy's currency. USD/TRY, USD/ZAR, USD/MXN. Wider spreads, more volatility, higher risk.</li>
</ul>

<h2>Why Forex is different from the stock market</h2>
<ul>
    <li>No opening bell, no closing bell. A trader in Tokyo hands the market to London, who hands it to New York, who hands it back to Sydney.</li>
    <li>No central exchange, so different brokers may show very slightly different prices for the same pair.</li>
    <li>High leverage is standard (often 50:1 to 500:1), which amplifies both wins and losses.</li>
    <li>Most retail traders focus on technical analysis rather than company fundamentals — there's no single "company" to analyse.</li>
</ul>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Assuming all Forex brokers are the same.</strong> They aren't. Spreads, execution quality, regulation, and leverage limits vary enormously.</li>
    <li><strong>Treating Forex like gambling.</strong> Without risk management and a defined edge, it is gambling. With them, it's a probability-based business.</li>
    <li><strong>Overtrading exotic pairs as a beginner.</strong> Start with majors. They have the tightest spreads and behave most predictably.</li>
</ul>

<h2>Advanced notes</h2>
<p>The Forex market is split into tiers. At the top is the <strong>interbank market</strong>, where the largest banks trade directly with each other and set the base rates you see quoted everywhere. Below that are <strong>prime brokers</strong>, then <strong>regional banks</strong>, then <strong>retail brokers</strong> who serve individual traders. When you place a trade, your broker is usually matching you against its own liquidity pool or routing your order to a larger counterparty. Understanding this hierarchy is key to understanding why spreads widen, why slippage happens, and why two brokers can show different prices.</p>
HTML,
        ],

        /* ============================================================
         *  LESSON 5 — WHY DOES FOREX EXIST?
         * ============================================================ */
        [
            'slug'   => 'why-does-forex-exist',
            'title'  => 'Why Does Forex Exist?',
            'difficulty' => 'beginner',
            'estimated_duration' => 9,
            'learning_objectives' =>
                "• Explain the three real-world needs that created Forex\n" .
                "• Distinguish commercial FX from speculative FX\n" .
                "• Understand why retail traders are a tiny fraction of the market",
            'prerequisites' => 'What Is Forex?',
            'sort_order' => 5,
            'summary' => 'Forex exists because international trade, investment, and tourism require converting one currency into another. Speculation is a much smaller — but highly visible — part of the market.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Forex exists because the world has many currencies, and those currencies need to be exchanged constantly. If every country used the same money, there would be no Forex market. Because they don't, someone has to do the converting.</p>

<h2>The three real-world drivers</h2>

<h3>1. International trade</h3>
<p>A German carmaker sells cars to the US. It receives dollars but pays its workers in euros. It must convert those dollars to euros. This happens millions of times a day across every industry. This is the bedrock of the FX market.</p>

<h3>2. International investment</h3>
<p>A Japanese pension fund wants to buy US government bonds. It must convert yen into dollars to buy them, and later convert the dollar interest and principal back into yen. Cross-border investment — stocks, bonds, real estate — creates massive currency flows.</p>

<h3>3. Tourism and personal transfers</h3>
<p>A Nigerian student studying in Canada needs Canadian dollars for tuition. A family sends money home from abroad. These smaller flows add up. Remittances alone are over $700 billion per year globally.</p>

<h2>Real-world analogy</h2>
<p>Imagine every city in the world had its own currency. You couldn't drive from Paris to Berlin without exchanging money. That's the world of international business — and Forex is the exchange desk that makes it possible.</p>

<h2>Professional explanation</h2>
<p>The FX market serves four core economic functions:</p>
<ul>
    <li><strong>Facilitating trade</strong> — enabling businesses to pay for imports and receive payment for exports.</li>
    <li><strong>Enabling investment</strong> — letting capital flow across borders to where it earns the best return.</li>
    <li><strong>Hedging currency risk</strong> — allowing companies to lock in exchange rates so future payments and receipts are predictable.</li>
    <li><strong>Price discovery</strong> — establishing the exchange rate between any two currencies at any moment.</li>
</ul>

<h3>Hedging — the invisible giant</h3>
<p>Consider an airline that agreed to buy 10 Boeing planes for delivery in 12 months, priced in USD. If the airline's home currency weakens against the dollar, the planes become more expensive. To protect itself, the airline can <strong>hedge</strong> — buying dollars now, or using a forward contract, to lock in the exchange rate.</p>
<p>This hedging activity generates enormous FX volume, far more than speculation. It's why central banks and corporations dominate the market.</p>

<h3>Speculation — the visible minority</h3>
<p>Speculators buy and sell currencies with no intention of ever taking delivery — they're trying to profit from price changes. Retail traders fall into this category, along with hedge funds and prop desks. Speculation is estimated at 10–20% of total daily FX volume. It's the most visible part because retail traders talk about it publicly, but it's the smallest slice.</p>

<h2>Why this matters for retail traders</h2>
<p>When you trade EUR/USD, you are borrowing a tiny fragment of liquidity from a market designed for institutional players. You benefit from their activity — tight spreads, deep liquidity, 24/5 access — but you're also competing against better-funded, faster, and more informed participants. The edge for retail comes from <em>discipline and process</em>, not from out-trading institutions.</p>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Assuming Forex exists mainly for speculation.</strong> Speculators are a small fraction of the total market. The bulk is trade, investment, and hedging.</li>
    <li><strong>Believing central banks are "out to get" retail traders.</strong> Central banks intervene to manage their own economies, not to run stop-losses. This myth is popular in some retail communities but is not supported by evidence.</li>
    <li><strong>Ignoring the macro context.</strong> A currency pair's long-term direction is driven by trade flows, interest rates, and capital movements — not chart patterns alone.</li>
</ul>

<h2>Advanced notes</h2>
<p>The <strong>Bretton Woods system</strong> (1944–1971) fixed most currencies to the US dollar, which was itself pegged to gold. When Nixon ended the gold convertibility in 1971, currencies began to float and their values were determined by supply and demand. The modern free-floating FX market we know today is only about 50 years old. Before that, exchange rates were set by governments, and speculation was largely impossible.</p>
HTML,
        ],

        /* ============================================================
         *  LESSON 6 — WHO TRADES FOREX?
         * ============================================================ */
        [
            'slug'   => 'who-trades-forex',
            'title'  => 'Who Trades Forex?',
            'difficulty' => 'beginner',
            'estimated_duration' => 12,
            'learning_objectives' =>
                "• Identify the major participant types in FX\n" .
                "• Explain the role of central banks and commercial banks\n" .
                "• Understand where retail traders fit in the hierarchy",
            'prerequisites' => 'Why Does Forex Exist?',
            'sort_order' => 6,
            'summary' => 'Forex participants range from central banks and commercial banks — who move the largest volume — down through hedge funds, corporations, brokers, and finally retail traders, who trade the smallest size but can still learn and profit from the market.',
            'content' => <<<'HTML'
<h2>Simple explanation</h2>
<p>Forex isn't one market with one kind of participant. It's a layered hierarchy. At the top are the institutions that move billions of dollars per trade. At the bottom are individuals like you, trading thousands. Everyone is buying and selling the same currencies, but the scale and the motive differ enormously.</p>

<h2>Real-world analogy</h2>
<p>Think of Forex like international shipping. The largest container ships move the most cargo and set the freight rates everyone else pays. Below them are smaller cargo vessels, then regional ferries, then a person in a rowing boat. All of them are on the same water, but each operates at a very different scale.</p>

<h2>The seven tiers of Forex participants</h2>

<h3>1. Central banks</h3>
<p>The Federal Reserve, European Central Bank, Bank of Japan, Bank of England, and their peers. They set interest rates, control money supply, and occasionally intervene directly in FX to influence their currency's value. When a central bank buys or sells billions of a currency in a single operation, everyone notices.</p>

<h3>2. Commercial banks</h3>
<p>JPMorgan, Deutsche Bank, Citi, UBS, Barclays. These are the <strong>market makers</strong> at the top of the pyramid. They quote the prices that everyone else builds on. The <strong>interbank market</strong> — where banks trade with each other — accounts for the majority of daily FX volume.</p>

<h3>3. Corporations</h3>
<p>Multinational companies that earn revenue in multiple currencies. Apple, Toyota, Shell — they convert revenues, pay suppliers, and hedge currency exposure. Their activity is not speculation; it's operational necessity. But the sums are enormous.</p>

<h3>4. Hedge funds and investment firms</h3>
<p>Funds that trade FX as part of a broader macro strategy. They may take enormous positions in currencies based on interest-rate differentials, economic forecasts, or momentum. Their sizes rival banks.</p>

<h3>5. Institutional asset managers</h3>
<p>Pension funds, insurance companies, and sovereign wealth funds. They trade FX primarily to manage the currency exposure of their other holdings, but sometimes also for alpha.</p>

<h3>6. Brokers and liquidity providers</h3>
<p>The intermediaries that connect retail traders to the market. A broker might be a <strong>market maker</strong> (quoting you its own prices and taking the other side of your trade) or an <strong>ECN/STP broker</strong> (routing your order to a liquidity provider). The broker's role is to provide access, execution, and leverage.</p>

<h3>7. Retail traders</h3>
<p>Individuals trading their own accounts. You and millions like you. Retail volume is estimated at 5–10% of total FX turnover. Individually, your trade is a rounding error to the market. But collectively, retail is a meaningful force — and every professional trader started here.</p>

<h2>Why the hierarchy matters</h2>
<p>When you see a price quoted on your broker's platform, that price originated upstream — through the banks and liquidity providers. Your broker either passes your order on (STP) or takes the other side (market-making). Understanding this explains:</p>
<ul>
    <li>Why spreads widen during news</li>
    <li>Why slippage happens on volatile days</li>
    <li>Why two brokers show different prices for the same pair</li>
    <li>Why regulatory oversight of brokers is important</li>
</ul>

<h2>Common mistakes</h2>
<ul>
    <li><strong>Thinking retail traders drive the market.</strong> They don't. Central banks and commercial banks do. Retail traders are price takers, not price makers.</li>
    <li><strong>Believing brokers always trade against you.</strong> Market-making brokers do take the other side, but their business model depends on volume, not on your individual loss.</li>
    <li><strong>Assuming institutional traders have a magic edge.</strong> Their edge is information speed, size, and infrastructure. Their weaknesses are the same as ours: fear, ego, and forced liquidations.</li>
</ul>

<h2>Advanced notes</h2>
<p>The <strong>BIS Triennial Central Bank Survey</strong>, published every three years, is the authoritative source on who trades FX and how much. The 2022 survey identified the UK (38%), US (19%), and Singapore (9%) as the largest FX trading centres. Understanding who trades and where they trade gives you insight into which hours are most liquid and which pairs have the tightest spreads.</p>
HTML,
        ],

    ],
];