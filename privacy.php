<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Privacy Policy';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/legal.css">

<div class="legal-page">
  <div class="legal-hero">
    <h1>Privacy Policy</h1>
    <p>How AlphaEdge collects, uses, and protects your information.</p>
    <div class="legal-updated">Last updated: <?= e(date('F j, Y')) ?></div>
  </div>

  <div class="legal-callout">
    <strong>Educational demo notice:</strong> AlphaEdge is a student project built for
    learning and demonstration. It does not process real payments or hold real funds.
    This policy describes our intended behavior as if the platform were a live product.
  </div>

  <h2>1. Introduction</h2>
  <p>
    AlphaEdge ("we", "our", "us") respects your privacy and is committed to protecting your
    personal information. This Privacy Policy explains what data we collect, why we collect
    it, how we use it, and the choices you have.
  </p>
  <p>
    By using AlphaEdge, you agree to the collection and use of information in accordance
    with this policy. If you do not agree, please do not use the platform.
  </p>

  <h2>2. Information we collect</h2>

  <h3>2.1 Information you provide directly</h3>
  <ul>
    <li><strong>Account details:</strong> username, email address, and a securely hashed password.</li>
    <li><strong>KYC information:</strong> full legal name, date of birth, country, national tax ID, and mobile number.</li>
    <li><strong>Identity documents:</strong> uploaded images or PDFs of government-issued ID, proof of address, and a live selfie captured in-browser.</li>
    <li><strong>Bank account details:</strong> account holder name, bank name, account number or IBAN, and bank country. We do <em>not</em> collect banking usernames, passwords, or card numbers.</li>
    <li><strong>Communications:</strong> messages you send through the global chat, support requests, and AI chatbot conversations.</li>
  </ul>

  <h3>2.2 Information collected automatically</h3>
  <ul>
    <li><strong>Session data:</strong> login time, session cookies, and CSRF tokens used to protect your account.</li>
    <li><strong>Activity data:</strong> your trades, portfolio snapshots, alert history, and last-seen timestamps.</li>
    <li><strong>Preferences:</strong> selected timezone and UI settings.</li>
    <li><strong>Device data:</strong> browser type, approximate location derived from IP address, and timezone.</li>
  </ul>

  <h3>2.3 Information from third parties</h3>
  <p>
    Market prices displayed on AlphaEdge are sourced from third-party APIs (such as
    CoinGecko and Yahoo Finance). We do not receive personal data about you from these
    providers.
  </p>

  <h2>3. How we use your information</h2>
  <p>We use the information we collect to:</p>
  <ul>
    <li>Create and secure your account</li>
    <li>Verify your identity and comply with KYC requirements</li>
    <li>Process simulated trades and maintain your virtual portfolio</li>
    <li>Display accurate prices, signals, and news</li>
    <li>Send alerts and notifications you have requested</li>
    <li>Prevent fraud, abuse, and unauthorized access</li>
    <li>Respond to support requests and communicate with you</li>
    <li>Improve the platform and detect bugs</li>
  </ul>

  <h2>4. Legal basis for processing</h2>
  <p>
    If you are in a jurisdiction covered by data protection laws (such as the GDPR),
    we process your data on the following legal bases:
  </p>
  <ul>
    <li><strong>Contract:</strong> to provide the services you signed up for.</li>
    <li><strong>Legal obligation:</strong> to comply with KYC and anti-money-laundering requirements.</li>
    <li><strong>Legitimate interests:</strong> to secure our platform and improve our services.</li>
    <li><strong>Consent:</strong> for optional features such as browser notifications.</li>
  </ul>

  <h2>5. How we share your information</h2>
  <p>We do <strong>not</strong> sell your personal data. We share it only in these limited cases:</p>
  <ul>
    <li><strong>With service providers</strong> strictly necessary to operate the platform (hosting, market data APIs, AI chatbot services).</li>
    <li><strong>With regulators or law enforcement</strong> when legally required.</li>
    <li><strong>In a business transfer</strong> (merger, acquisition) — you would be notified.</li>
  </ul>

  <h2>6. How long we keep your data</h2>
  <ul>
    <li><strong>Account data:</strong> retained while your account is active, then for 90 days before deletion.</li>
    <li><strong>KYC documents:</strong> retained for the minimum period required by applicable law (typically 5 years).</li>
    <li><strong>Trade history:</strong> retained for 7 years for record-keeping.</li>
    <li><strong>Session and logs:</strong> retained for 30 days.</li>
  </ul>

  <h2>7. How we protect your data</h2>
  <ul>
    <li>Passwords are hashed using industry-standard bcrypt.</li>
    <li>Session cookies are marked <code>HttpOnly</code> and <code>SameSite=Lax</code>.</li>
    <li>All POST requests are protected by CSRF tokens.</li>
    <li>Uploaded documents are stored with randomized filenames.</li>
    <li>Database queries use prepared statements to prevent SQL injection.</li>
    <li>User inputs are escaped on output to prevent cross-site scripting (XSS).</li>
  </ul>

  <h2>8. Your rights</h2>
  <p>Depending on your location, you may have the right to:</p>
  <ul>
    <li>Access the personal data we hold about you</li>
    <li>Request correction of inaccurate data</li>
    <li>Request deletion of your account and data</li>
    <li>Object to or restrict certain processing</li>
    <li>Request a copy of your data in a portable format</li>
    <li>Withdraw consent at any time</li>
  </ul>
  <p>
    To exercise any of these rights, email <strong>privacy@alphaedge.demo</strong>.
    We will respond within 30 days.
  </p>

  <h2>9. Children's privacy</h2>
  <p>
    AlphaEdge is not intended for anyone under 18. We do not knowingly collect data from
    minors. If you believe we have, contact us and we will delete it.
  </p>

  <h2>10. International transfers</h2>
  <p>
    Our servers and service providers may be located in countries other than yours. When we
    transfer personal data across borders, we use appropriate safeguards such as standard
    contractual clauses.
  </p>

  <h2>11. Changes to this policy</h2>
  <p>
    We may update this Privacy Policy from time to time. Material changes will be
    communicated by email or by a prominent notice on the platform. The "Last updated" date
    at the top reflects the current version.
  </p>

  <h2>12. Contact us</h2>
  <p>
    If you have questions, complaints, or requests regarding this policy, contact:
  </p>
  <p>
    <strong>Email:</strong> privacy@alphaedge.demo<br>
    <strong>Data Protection Officer:</strong> dpo@alphaedge.demo
  </p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>