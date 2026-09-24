<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Terms & Conditions';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/legal.css">

<div class="legal-page">
  <div class="legal-hero">
    <h1>Terms &amp; Conditions</h1>
    <p>The rules that govern your use of AlphaEdge.</p>
    <div class="legal-updated">Last updated: <?= e(date('F j, Y')) ?></div>
  </div>

  <div class="legal-callout" style="border-left-color:#f6465d;background:rgba(246,70,93,0.06);">
    <strong style="color:#f6465d;">⚠️ IMPORTANT RISK DISCLOSURE — READ FIRST:</strong><br><br>
    Trading stocks, cryptocurrencies, derivatives, and other financial instruments involves
    <strong>substantial risk of loss</strong> and is not suitable for everyone. You may lose
    <strong>some or all</strong> of your invested capital. Past performance is not indicative
    of future results. Prices can move rapidly against you.<br><br>
    By using AlphaEdge, you acknowledge and accept that <strong>you alone are responsible
    for your trading decisions</strong> and any financial outcome. AlphaEdge, its operators,
    employees, affiliates, and partners shall <strong>not be held liable</strong> for any
    trading losses, missed opportunities, or financial damages you may incur.<br><br>
    <strong>You must never trade with money you cannot afford to lose.</strong>
    Nothing on AlphaEdge constitutes financial, investment, legal, or tax advice.
  </div>

  <h2>1. Acceptance of terms</h2>
  <p>
    By creating an account or using any part of AlphaEdge (the "Platform"), you agree to be
    bound by these Terms &amp; Conditions ("Terms"). If you do not agree, you must not use
    the Platform.
  </p>

  <h2>2. Educational demo status</h2>
  <p>
    AlphaEdge is a <strong>student demonstration project</strong> built for educational and
    portfolio purposes. All balances, trades, prices, deposits, and withdrawals on the
    Platform are <strong>virtual and simulated</strong>. No real money changes hands
    through this Platform. The Platform is not a licensed broker, bank, exchange, or
    financial institution.
  </p>

  <h2>3. Eligibility</h2>
  <p>You may use AlphaEdge only if you:</p>
  <ul>
    <li>Are at least 18 years of age</li>
    <li>Have the legal capacity to enter into a binding agreement</li>
    <li>Are not prohibited from using the Platform under the laws of your jurisdiction</li>
    <li>Have not been previously suspended or removed from the Platform</li>
  </ul>

  <h2>4. Your account</h2>
  <ul>
    <li>You are responsible for maintaining the confidentiality of your password.</li>
    <li>You are responsible for all activity that occurs under your account.</li>
    <li>You agree to provide accurate information and to keep it up to date.</li>
    <li>You must notify us immediately of any unauthorized use.</li>
    <li>We may suspend or terminate accounts that violate these Terms.</li>
  </ul>

  <h2>5. Risk disclosure</h2>

  <h3>5.1 General market risk</h3>
  <p>
    Financial markets are volatile. Asset prices can change dramatically in short periods
    due to economic events, news, regulation, and market sentiment. You may lose money
    rapidly.
  </p>

  <h3>5.2 No guarantee of profit</h3>
  <p>
    Signals, "top picks", AI recommendations, technical indicators, and any other analysis
    provided on the Platform are <strong>informational only</strong>. They are not
    guarantees and do not constitute financial advice. Historical patterns do not predict
    future performance.
  </p>

  <h3>5.3 You bear full responsibility</h3>
  <p>
    You acknowledge that <strong>all trading decisions are yours alone</strong>. You
    understand that you may incur losses and that AlphaEdge cannot and will not be held
    responsible for any financial outcome — positive or negative — resulting from your
    use of the Platform.
  </p>

  <h3>5.4 No fiduciary duty</h3>
  <p>
    AlphaEdge does not act as your financial advisor, broker, agent, or fiduciary. We do
    not owe you any duty of care regarding your trading activities.
  </p>

  <h3>5.5 Cryptocurrency-specific risk</h3>
  <p>
    Cryptocurrencies are particularly volatile and may be subject to regulatory changes,
    hacks, exchange failures, and extreme price swings. Meme coins and low-cap tokens
    can lose 100% of their value.
  </p>

  <h2>6. Prohibited activities</h2>
  <p>You agree not to:</p>
  <ul>
    <li>Use the Platform for any illegal purpose</li>
    <li>Attempt to gain unauthorized access to other accounts or our systems</li>
    <li>Scrape, copy, or redistribute our data without permission</li>
    <li>Interfere with the Platform's operation, security, or availability</li>
    <li>Impersonate another person or entity</li>
    <li>Upload malware, viruses, or harmful code</li>
    <li>Use automated bots to manipulate signals, chat, or activity metrics</li>
    <li>Harass, threaten, or abuse other users</li>
  </ul>

  <h2>7. Intellectual property</h2>
  <p>
    All content on AlphaEdge — including the design, code, logos, text, and graphics —
    is owned by us or our licensors and is protected by applicable intellectual property
    laws. You may not copy, modify, or distribute it without written permission.
  </p>

  <h2>8. Third-party content</h2>
  <p>
    Market data, news articles, and AI-generated responses are provided by third parties.
    We do not guarantee their accuracy, completeness, or timeliness. You use such content
    at your own risk.
  </p>

  <h2>9. Disclaimers</h2>
  <p>
    THE PLATFORM IS PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND,
    EITHER EXPRESS OR IMPLIED. TO THE MAXIMUM EXTENT PERMITTED BY LAW, WE DISCLAIM ALL
    WARRANTIES, INCLUDING BUT NOT LIMITED TO MERCHANTABILITY, FITNESS FOR A PARTICULAR
    PURPOSE, AND NON-INFRINGEMENT.
  </p>

  <h2>10. Limitation of liability</h2>
  <p>
    TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, ALPHAEDGE, ITS OPERATORS,
    EMPLOYEES, AFFILIATES, AND PARTNERS SHALL NOT BE LIABLE FOR:
  </p>
  <ul>
    <li>Any trading losses or missed gains</li>
    <li>Any indirect, incidental, special, consequential, or punitive damages</li>
    <li>Any loss of data, profits, revenue, or business opportunity</li>
    <li>Any inaccuracy in market data, signals, or AI outputs</li>
    <li>Any service interruption, technical failure, or security breach</li>
  </ul>
  <p>
    Where liability cannot be excluded by law, our total aggregate liability shall not
    exceed <strong>$0 USD</strong>, given the Platform is provided free of charge and
    without any real-money transactions.
  </p>

  <h2>11. Indemnification</h2>
  <p>
    You agree to indemnify and hold harmless AlphaEdge, its operators, employees, and
    affiliates from any claim, demand, loss, or expense (including legal fees) arising from:
  </p>
  <ul>
    <li>Your use of the Platform</li>
    <li>Your violation of these Terms</li>
    <li>Your violation of any third-party rights</li>
    <li>Any content or messages you post</li>
  </ul>

  <h2>12. Termination</h2>
  <p>
    We may suspend, restrict, or terminate your access to the Platform at any time,
    with or without notice, for any reason — including but not limited to violations of
    these Terms. You may also delete your account at any time.
  </p>

  <h2>13. Governing law and disputes</h2>
  <p>
    These Terms are governed by the laws of the jurisdiction where AlphaEdge is operated.
    Any dispute shall be resolved through good-faith negotiation, and if unresolved,
    through the courts of that jurisdiction. You waive any right to participate in a
    class action.
  </p>

  <h2>14. Changes to these Terms</h2>
  <p>
    We may update these Terms at any time. Material changes will be communicated by
    email or a prominent notice on the Platform. Continued use after changes means
    you accept the revised Terms.
  </p>

  <h2>15. Severability</h2>
  <p>
    If any provision of these Terms is found to be unenforceable, the remaining
    provisions shall remain in full force and effect.
  </p>

  <h2>16. Entire agreement</h2>
  <p>
    These Terms, together with our Privacy Policy and Cookie Policy, constitute the
    entire agreement between you and AlphaEdge regarding the Platform.
  </p>

  <h2>17. Contact</h2>
  <p>
    Questions about these Terms? Email <strong>legal@alphaedge.demo</strong>.
  </p>

  <div class="legal-callout" style="margin-top:40px;border-left-color:#f6465d;background:rgba(246,70,93,0.06);">
    <strong style="color:#f6465d;">REMINDER:</strong> AlphaEdge is an educational
    demonstration project. No real money is ever at risk. The risk disclosures above
    describe how a real trading platform would operate and are included for educational
    and professional completeness.
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>