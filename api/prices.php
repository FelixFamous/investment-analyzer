<?php
/**
 * GET /api/prices.php?symbols=AAPL,MSFT,NVDA
 * Server-side proxy to Yahoo Finance. Bypasses browser CORS entirely.
 * Returns clean JSON: { "AAPL": {price, change, changePct, high, low}, ... }
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$symbolsParam = $_GET['symbols'] ?? 'AAPL,MSFT,NVDA,TSLA,AMD,META,GOOGL,AMZN,NFLX';
$symbols = array_filter(array_map('trim', explode(',', $symbolsParam)));

if (!$symbols) {
    echo json_encode(['error' => 'No symbols requested']);
    exit;
}

$out = [];

foreach ($symbols as $sym) {
    $sym = strtoupper($sym);
    $url = 'https://query1.finance.yahoo.com/v8/finance/chart/'
         . urlencode($sym)
         . '?interval=1d&range=1d';

    $body = yahoo_fetch($url);
    if ($body === null) continue;

    $data = json_decode($body, true);
    $meta = $data['chart']['result'][0]['meta'] ?? null;
    if (!$meta) continue;

    $price     = (float)($meta['regularMarketPrice'] ?? 0);
    $prevClose = (float)($meta['chartPreviousClose'] ?? $meta['previousClose'] ?? $price);
    $change    = $price - $prevClose;
    $changePct = $prevClose > 0 ? ($change / $prevClose) * 100 : 0;

    $out[$sym] = [
        'price'     => $price,
        'change'    => round($change, 4),
        'changePct' => round($changePct, 4),
        'high'      => (float)($meta['regularMarketDayHigh'] ?? $price),
        'low'       => (float)($meta['regularMarketDayLow'] ?? $price),
        'volume'    => (int)($meta['regularMarketVolume'] ?? 0),
    ];
}

echo json_encode($out);

/* ---------- helper: fetch with cURL or file_get_contents ---------- */
function yahoo_fetch(string $url): ?string
{
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Accept: application/json',
    ];

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_CONNECTTIMEOUT => 4,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => $headers,
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body && $code === 200) return $body;
        return null;
    }

    $ctx = stream_context_create([
        'http' => [
            'timeout'       => 8,
            'ignore_errors' => true,
            'header'        => implode("\r\n", $headers),
        ],
    ]);
    $body = @file_get_contents($url, false, $ctx);
    return $body === false ? null : $body;
}