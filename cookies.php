<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Cookie Policy';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/legal.css">

<div class="legal-page">
  <div class="legal-hero">
    <h1>Cookie Policy</h1>
    <p>How AlphaEdge uses cookies and similar technologies.</p>
    <div class="legal-updated">Last updated: <?= e(date('F j, Y')) ?></div>
  </div>

  <div class="legal-callout">
    <strong>Educational demo notice:</strong> AlphaEdge is a student project built for
    learning and demonstration purposes. It does not process real payments or hold real funds.
    This policy describes our intended behavior as if the platform were a live product.
  </div>

  <h2>1. What are cookies?</h2>
  <p>
    Cookies are small text files that websites place on your device to store information
    about your visit. They allow the site to remember your actions and preferences
    (such as keeping you signed in) over time. Cookies are widely used to make websites
    work more efficiently and to provide useful reporting information to site owners.
  </p>

  <h2>2. How we use cookies</h2>
  <p>
    AlphaEdge uses cookies for the following purposes:
  </p>

  <table>
    <thead>
      <tr>
        <th>Cookie</th>
        <th>Category</th>
        <th>Purpose</th>
        <th>Duration</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>alphaedge_session</code></td>
        <td>Strictly necessary</td>
        <td>Keeps you signed in and maintains your session state. Without this, the site cannot function.</td>
        <td>Session</td>
      </tr>
      <tr>
        <td><code>alphaedge_cookie_consent</code></td>
        <td>Strictly necessary</td>
        <td>Remembers your cookie consent choice so we do not show the banner on every page.</td>
        <td>365 days</td>
      </tr>
      <tr>
        <td><code>alphaedge_tz</code></td>
        <td>Functional</td>
        <td>Remembers your preferred timezone for displaying dates and times.</td>
        <td>365 days</td>
      </tr>
      <tr>
        <td><code>alphaedge_theme</code></td>
        <td>Functional</td>
        <td>Remembers your theme preference (dark/light).</td>
        <td>365 days</td>
      </tr>
    </tbody>
  </table>

  <h2>3. What we do <em>not</em> use cookies for</h2>
  <p>
    AlphaEdge does <strong>not</strong> use cookies for:
  </p>
  <ul>
    <li>Advertising or retargeting</li>
    <li>Cross-site tracking</li>
    <li>Selling data to third parties</li>
    <li>Any purpose unrelated to the operation of this platform</li>
  </ul>

  <h2>4. Third-party cookies</h2>
  <p>
    Some content on AlphaEdge loads resources from third parties. These third parties may set
    their own cookies when their content is loaded:
  </p>
  <ul>
    <li><strong>Google Fonts</strong> — for typography. See <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google's Privacy Policy</a>.</li>
    <li><strong>CDN providers (unpkg, jsdelivr, cdnjs)</strong> — for serving JavaScript libraries such as charting tools.</li>
    <li><strong>Market data sources (CoinGecko, Yahoo Finance)</strong> — for real-time and historical prices. These are called server-side from AlphaEdge and do not set cookies in your browser.</li>
  </ul>

  <h2>5. Managing cookies</h2>
  <p>
    You can control and delete cookies through your browser settings. Below are links to
    the cookie management pages for common browsers:
  </p>
  <ul>
    <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
    <li><a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer" target="_blank" rel="noopener">Mozilla Firefox</a></li>
    <li><a href="https://support.apple.com/guide/safari/manage-cookies-sfri11471/mac" target="_blank" rel="noopener">Safari</a></li>
    <li><a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener">Microsoft Edge</a></li>
  </ul>
  <p>
    Please note that disabling strictly necessary cookies may prevent you from logging in
    and using the platform.
  </p>

  <h2>6. Your choices</h2>
  <p>
    When you first visit AlphaEdge, a banner is shown asking you to accept or decline
    non-essential cookies. You can change your mind at any time by clearing your browser
    cookies and refreshing the page.
  </p>

  <h2>7. Changes to this policy</h2>
  <p>
    We may update this Cookie Policy from time to time. Any changes will be reflected by
    the "Last updated" date at the top of this page.
  </p>

  <h2>8. Contact</h2>
  <p>
    If you have questions about this policy, contact us at
    <strong>privacy@alphaedge.demo</strong>.
  </p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>