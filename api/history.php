<?php
/**
 * GET /api/history.php?symbol=BTC&range=7d&interval=1h
 *
 * Range:    1d, 7d, 30d, 90d, 1y, 2y, 5y, max
 * Interval: 1m, 5m, 15m, 30m, 1h, 4h, 1d, 1w
 *
 * Sources:
 *   Crypto  → CryptoCompare (proper intraday) → CoinGecko fallback
 *   Stocks  → Yahoo Finance
 *   Indices → Yahoo Finance
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$symbol   = strtoupper(trim((string)($_GET['symbol'] ?? 'BTC')));
$range    = strtolower(trim((string)($_GET['range'] ?? '7d')));
$interval = strtolower(trim((string)($_GET['interval'] ?? '')));

$CRYPTO = [
    'BTC'=>'bitcoin','ETH'=>'ethereum','SOL'=>'solana','BNB'=>'binancecoin',
    'XRP'=>'ripple','DOGE'=>'dogecoin','ADA'=>'cardano','PEPE'=>'pepe',
];

$STOCKS = [
    'AAPL'=>'AAPL','MSFT'=>'MSFT','NVDA'=>'NVDA','TSLA'=>'TSLA','AMD'=>'AMD',
    'META'=>'META','GOOGL'=>'GOOGL','AMZN'=>'AMZN','NFLX'=>'NFLX',
];

$INDICES = [
    'SPX'=>'^GSPC','NDX'=>'^NDX','DJI'=>'^DJI','VIX'=>'^VIX',
];

$VALID_INTERVALS = ['1m','5m','15m','30m','1h','4h','1d','1w'];
$VALID_RANGES = ['1d','7d','30d','90d','1y','2y','5y','max'];

if (!in_array($range, $VALID_RANGES, true)) $range = '7d';

// If no interval supplied, derive from range (backward compatible)
if (!in_array($interval, $VALID_INTERVALS, true)) {
    $interval = match ($range) {
        '1d'  => '5m',
        '7d'  => '30m',
        '30d' => '1h',
        '90d' => '1h',
        '1y'  => '1d',
        '2y'  => '1d',
        '5y'  => '1w',
        'max' => '1w',
        default => '1h',
    };
}

/* ---------- Cache ---------- */
$cacheDir = __DIR__ . '/../cache';
if (!is_dir($cacheDir)) @mkdir($cacheDir, 0755, true);
$cacheKey  = 'hist_' . preg_replace('/[^A-Z0-9]/', '', $symbol) . '_' . $range . '_' . $interval . '.json';
$cachePath = $cacheDir . '/' . $cacheKey;
$cacheTTL  = 900;

if (is_file($cachePath) && (time() - filemtime($cachePath) < $cacheTTL)) {
    header('X-Cache: HIT');
    readfile($cachePath);
    exit;
}

/* ---------- Route ---------- */
$source = '';
if (isset($CRYPTO[$symbol])) {
    $candles = fetch_crypto($symbol, $interval, $range);
    $source = 'crypto';
} elseif (isset($STOCKS[$symbol])) {
    $candles = fetch_yahoo($STOCKS[$symbol], $interval, $range);
    $source = 'yahoo';
} elseif (isset($INDICES[$symbol])) {
    $candles = fetch_yahoo($INDICES[$symbol], $interval, $range);
    $source = 'yahoo';
} else {
    echo json_encode(['error' => 'Symbol not tracked']); exit;
}

if ($candles === null || count($candles) === 0) {
    echo json_encode(['error' => 'Failed to fetch candles']); exit;
}

$payload = json_encode([
    'symbol'   => $symbol,
    'range'    => $range,
    'interval' => $interval,
    'source'   => $source,
    'count'    => count($candles),
    'candles'  => $candles,
]);

@file_put_contents($cachePath, $payload);
header('X-Cache: MISS');
echo $payload;

/* ============================================================
 *  CRYPTO — try CryptoCompare first, fall back to CoinGecko
 * ============================================================ */
function fetch_crypto(string $symbol, string $interval, string $range): ?array
{
    // CryptoCompare gives us real 1m, 5m, 1h, 1d candles
    $cc = fetch_cryptocompare($symbol, $interval);
    if ($cc !== null && count($cc) >= 10) return $cc;

    // Fallback to CoinGecko with aggregation
    global $CRYPTO;
    $id = $CRYPTO[$symbol] ?? null;
    if (!$id) return null;
    return fetch_coingecko($id, $interval);
}

function fetch_cryptocompare(string $symbol, string $interval): ?array
{
    // CryptoCompare wants the FSym (e.g. BTC, ETH) — same as our display symbol
    $map = [
        '1m'  => ['endpoint' => 'histominute', 'aggregate' => 1,  'limit' => 2000],
        '5m'  => ['endpoint' => 'histominute', 'aggregate' => 5,  'limit' => 2000],
        '15m' => ['endpoint' => 'histominute', 'aggregate' => 15, 'limit' => 2000],
        '30m' => ['endpoint' => 'histominute', 'aggregate' => 30, 'limit' => 2000],
        '1h'  => ['endpoint' => 'histohour',   'aggregate' => 1,  'limit' => 2000],
        '4h'  => ['endpoint' => 'histohour',   'aggregate' => 4,  'limit' => 2000],
        '1d'  => ['endpoint' => 'histoday',    'aggregate' => 1,  'limit' => 2000],
        '1w'  => ['endpoint' => 'histoday',    'aggregate' => 7,  'limit' => 2000],
    ];
    if (!isset($map[$interval])) return null;
    $cfg = $map[$interval];

    $url = "https://min-api.cryptocompare.com/data/v2/{$cfg['endpoint']}"
         . '?fsym=' . urlencode($symbol)
         . '&tsym=USD'
         . '&limit=' . $cfg['limit']
         . '&aggregate=' . $cfg['aggregate'];

    $body = http_get($url);
    if (!$body) return null;
    $json = json_decode($body, true);
    if (($json['Response'] ?? '') !== 'Success') return null;
    $rows = $json['Data']['Data'] ?? [];
    if (!is_array($rows) || !$rows) return null;

    $out = [];
    foreach ($rows as $r) {
        if (!isset($r['time'], $r['open'], $r['high'], $r['low'], $r['close'])) continue;
        $out[] = [
            't' => (int)$r['time'] * 1000,
            'o' => (float)$r['open'],
            'h' => (float)$r['high'],
            'l' => (float)$r['low'],
            'c' => (float)$r['close'],
            'v' => (float)($r['volumeto'] ?? 0),
        ];
    }
    return $out;
}

function fetch_coingecko(string $coinId, string $interval): ?array
{
    // CoinGecko limits: days=1 → ~5min, 2-90 → hourly, >90 → daily
    $days = '30';
    $aggregateSec = 0;

    switch ($interval) {
        case '1m':  $days = '1';   $aggregateSec = 300;   break; // 5 min (1m not available)
        case '5m':  $days = '1';   $aggregateSec = 300;   break;
        case '15m': $days = '1';   $aggregateSec = 900;   break;
        case '30m': $days = '1';   $aggregateSec = 1800;  break;
        case '1h':  $days = '30';  break;
        case '4h':  $days = '30';  $aggregateSec = 14400; break;
        case '1d':  $days = '365'; break;
        case '1w':  $days = '365'; $aggregateSec = 604800; break;
    }

    $url = "https://api.coingecko.com/api/v3/coins/{$coinId}/market_chart?vs_currency=usd&days={$days}";
    $body = http_get($url);
    if (!$body) return null;
    $data = json_decode($body, true);
    $prices = $data['prices'] ?? [];
    $vols = $data['total_volumes'] ?? [];
    if (!is_array($prices) || !$prices) return null;

    $candles = [];
    $prev = null;
    foreach ($prices as $i => $p) {
        $t = (int)$p[0];
        $c = (float)$p[1];
        if ($c <= 0) continue;
        if ($prev === null) $prev = $c;

        $body2 = abs($c - $prev);
        $wick = max($body2 * 0.6, $c * 0.0008);
        $candles[] = [
            't' => $t,
            'o' => round($prev, 8),
            'h' => round(max($prev, $c) + $wick, 8),
            'l' => round(min($prev, $c) - $wick, 8),
            'c' => round($c, 8),
            'v' => isset($vols[$i][1]) ? (float)$vols[$i][1] : 0,
        ];
        $prev = $c;
    }

    if ($aggregateSec > 0) {
        $candles = aggregate_by_seconds($candles, $aggregateSec);
    }

    return $candles;
}

function aggregate_by_seconds(array $candles, int $intervalSec): array
{
    if (!$candles) return [];
    $intervalMs = $intervalSec * 1000;

    $out = [];
    $bucket = [];
    $bucketStart = null;

    foreach ($candles as $c) {
        $b = (int)floor($c['t'] / $intervalMs) * $intervalMs;
        if ($bucketStart === null) $bucketStart = $b;

        if ($b !== $bucketStart && $bucket) {
            $out[] = [
                't' => $bucketStart,
                'o' => $bucket[0]['o'],
                'h' => max(array_column($bucket, 'h')),
                'l' => min(array_column($bucket, 'l')),
                'c' => end($bucket)['c'],
                'v' => array_sum(array_column($bucket, 'v')),
            ];
            $bucket = [];
            $bucketStart = $b;
        }
        $bucket[] = $c;
    }
    if ($bucket) {
        $out[] = [
            't' => $bucketStart,
            'o' => $bucket[0]['o'],
            'h' => max(array_column($bucket, 'h')),
            'l' => min(array_column($bucket, 'l')),
            'c' => end($bucket)['c'],
            'v' => array_sum(array_column($bucket, 'v')),
        ];
    }
    return $out;
}

/* ============================================================
 *  STOCKS & INDICES — Yahoo Finance
 * ============================================================ */
function fetch_yahoo(string $symbol, string $interval, string $range): ?array
{
    // Yahoo limits per interval
    $map = [
        '1m'  => ['interval' => '1m',  'maxRangeDays' => 7,     'aggregate' => 1],
        '5m'  => ['interval' => '5m',  'maxRangeDays' => 60,    'aggregate' => 1],
        '15m' => ['interval' => '15m', 'maxRangeDays' => 60,    'aggregate' => 1],
        '30m' => ['interval' => '30m', 'maxRangeDays' => 60,    'aggregate' => 1],
        '1h'  => ['interval' => '60m', 'maxRangeDays' => 730,   'aggregate' => 1],
        '4h'  => ['interval' => '60m', 'maxRangeDays' => 730,   'aggregate' => 4],
        '1d'  => ['interval' => '1d',  'maxRangeDays' => 99999, 'aggregate' => 1],
        '1w'  => ['interval' => '1wk', 'maxRangeDays' => 99999, 'aggregate' => 1],
    ];
    if (!isset($map[$interval])) $interval = '1d';
    $cfg = $map[$interval];

    // Map our range to days
    $rangeDays = [
        '1d' => 1, '7d' => 7, '30d' => 30, '90d' => 90,
        '1y' => 365, '2y' => 730, '5y' => 1825, 'max' => 99999,
    ];
    $requestedDays = $rangeDays[$range] ?? 7;

    // Clamp range to what Yahoo allows
    $effectiveRange = $range;
    if ($requestedDays > $cfg['maxRangeDays']) {
        // Pick the closest allowed range
        foreach ($rangeDays as $label => $days) {
            if ($days <= $cfg['maxRangeDays']) $effectiveRange = $label;
        }
    }

    // Yahoo's range keyword
    $yahooRangeMap = [
        '1d' => '1d', '7d' => '7d', '30d' => '1mo', '90d' => '3mo',
        '1y' => '1y', '2y' => '2y', '5y' => '5y', 'max' => 'max',
    ];
    $yahooRange = $yahooRangeMap[$effectiveRange] ?? '7d';

    $url = 'https://query1.finance.yahoo.com/v8/finance/chart/' . urlencode($symbol)
         . '?interval=' . urlencode($cfg['interval'])
         . '&range=' . urlencode($yahooRange);

    $body = http_get($url);
    if (!$body) return null;
    $data = json_decode($body, true);
    $result = $data['chart']['result'][0] ?? null;
    if (!$result) return null;

    $timestamps = $result['timestamp'] ?? [];
    $q = $result['indicators']['quote'][0] ?? null;
    if (!$q) return null;

    $out = [];
    for ($i = 0; $i < count($timestamps); $i++) {
        $o = $q['open'][$i] ?? null;
        $h = $q['high'][$i] ?? null;
        $l = $q['low'][$i] ?? null;
        $c = $q['close'][$i] ?? null;
        $v = $q['volume'][$i] ?? 0;
        if ($o === null || $h === null || $l === null || $c === null) continue;

        $out[] = [
            't' => (int)$timestamps[$i] * 1000,
            'o' => (float)$o,
            'h' => (float)$h,
            'l' => (float)$l,
            'c' => (float)$c,
            'v' => (float)$v,
        ];
    }

    if (!empty($cfg['aggregate']) && $cfg['aggregate'] > 1) {
        $out = aggregate_fixed($out, $cfg['aggregate']);
    }

    return $out;
}

function aggregate_fixed(array $candles, int $factor): array
{
    if (count($candles) < $factor) return $candles;

    $out = [];
    $chunk = [];
    foreach ($candles as $c) {
        $chunk[] = $c;
        if (count($chunk) === $factor) {
            $out[] = [
                't' => $chunk[0]['t'],
                'o' => $chunk[0]['o'],
                'h' => max(array_column($chunk, 'h')),
                'l' => min(array_column($chunk, 'l')),
                'c' => end($chunk)['c'],
                'v' => array_sum(array_column($chunk, 'v')),
            ];
            $chunk = [];
        }
    }
    return $out;
}

function http_get(string $url): ?string
{
    $headers = ['User-Agent: Mozilla/5.0', 'Accept: application/json'];
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
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
        'http' => ['timeout' => 15, 'ignore_errors' => true,
                   'header' => implode("\r\n", $headers)],
    ]);
    $body = @file_get_contents($url, false, $ctx);
    return $body === false ? null : $body;
}