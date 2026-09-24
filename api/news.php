<?php
/**
 * GET /api/news.php
 * Returns crypto + stock news, both from Yahoo Finance.
 * Also computes a naive sentiment score per headline.
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$crypto = fetch_crypto_news();
$stocks = fetch_stock_news();

foreach ($crypto as &$n) $n['sentiment'] = simple_sentiment($n['title'] ?? '');
foreach ($stocks as &$n) $n['sentiment'] = simple_sentiment($n['title'] ?? '');
unset($n);

echo json_encode([
    'crypto' => array_slice($crypto, 0, 12),
    'stocks' => array_slice($stocks, 0, 12),
]);

/* ---------- Crypto news via Yahoo Finance ---------- */
function fetch_crypto_news(): array
{
    $symbols = ['BTC-USD', 'ETH-USD', 'SOL-USD', 'DOGE-USD'];
    $out = [];

    foreach ($symbols as $sym) {
        $url = 'https://query1.finance.yahoo.com/v1/finance/search?q='
             . urlencode($sym) . '&newsCount=4';
        $body = http_get($url);
        if (!$body) continue;

        $data = json_decode($body, true);
        $items = $data['news'] ?? [];
        foreach ($items as $item) {
            $title = $item['title'] ?? '';
            if (!$title) continue;

            // Skip if it's clearly not crypto related
            $lower = strtolower($title);
            $looksCrypto = preg_match('/bitcoin|ethereum|solana|crypto|btc|eth|sol|doge|blockchain|token|defi|altcoin/i', $lower);
            if (!$looksCrypto) continue;

            $out[] = [
                'title'  => $title,
                'source' => $item['publisher'] ?? 'Yahoo Finance',
                'url'    => $item['link'] ?? '#',
                'time'   => isset($item['providerPublishTime'])
                            ? date('c', $item['providerPublishTime'])
                            : date('c'),
                'type'   => 'crypto',
                'symbol' => str_replace('-USD', '', $sym),
            ];
        }
    }
    return $out;
}

/* ---------- Stock news via Yahoo Finance ---------- */
function fetch_stock_news(): array
{
    $symbols = ['AAPL', 'MSFT', 'NVDA', 'TSLA'];
    $out = [];

    foreach ($symbols as $sym) {
        $url = 'https://query1.finance.yahoo.com/v1/finance/search?q='
             . urlencode($sym) . '&newsCount=4';
        $body = http_get($url);
        if (!$body) continue;

        $data = json_decode($body, true);
        $items = $data['news'] ?? [];
        foreach ($items as $item) {
            $title = $item['title'] ?? '';
            if (!$title) continue;
            $out[] = [
                'title'  => $title,
                'source' => $item['publisher'] ?? 'Yahoo Finance',
                'url'    => $item['link'] ?? '#',
                'time'   => isset($item['providerPublishTime'])
                            ? date('c', $item['providerPublishTime'])
                            : date('c'),
                'type'   => 'stock',
                'symbol' => $sym,
            ];
        }
    }
    return $out;
}

/* ---------- Helpers ---------- */
function http_get(string $url): ?string
{
    $headers = ['User-Agent: Mozilla/5.0', 'Accept: application/json'];

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 6,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => $headers,
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($body && $code === 200) ? $body : null;
    }

    $ctx = stream_context_create([
        'http' => [
            'timeout'       => 6,
            'ignore_errors' => true,
            'header'        => implode("\r\n", $headers),
        ],
    ]);
    $body = @file_get_contents($url, false, $ctx);
    return $body === false ? null : $body;
}

/**
 * Very simple keyword sentiment. Returns -1..+1.
 */
function simple_sentiment(string $title): float
{
    $title = strtolower($title);
    $pos = ['surge', 'soar', 'rally', 'gain', 'beat', 'record', 'bullish',
            'jump', 'rise', 'up', 'high', 'buy', 'launch', 'partnership',
            'approve', 'growth', 'profit', 'breakout'];
    $neg = ['drop', 'fall', 'crash', 'plunge', 'loss', 'miss', 'bearish',
            'decline', 'down', 'low', 'sell', 'hack', 'lawsuit', 'ban',
            'fear', 'warning', 'concern', 'slump', 'risk'];

    $score = 0;
    foreach ($pos as $w) if (str_contains($title, $w)) $score++;
    foreach ($neg as $w) if (str_contains($title, $w)) $score--;

    if ($score === 0) return 0.0;
    return max(-1.0, min(1.0, $score / 3.0));
}