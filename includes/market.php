<?php
/**
 * Market data provider.
 *
 * Uses Finnhub for live quotes when FINNHUB_API_KEY is set.
 * Falls back to a deterministic simulated price when the key is
 * missing or the API call fails, so the whole app still works offline.
 */

require_once __DIR__ . '/../config/config.php';

function tracked_assets(): array
{
    return [
        ['symbol' => 'AAPL', 'name' => 'Apple Inc',           'type' => 'stock',  'base' => 228.30, 'vol' => 0.018],
        ['symbol' => 'MSFT', 'name' => 'Microsoft Corp',      'type' => 'stock',  'base' => 418.90, 'vol' => 0.016],
        ['symbol' => 'NVDA', 'name' => 'NVIDIA Corp',         'type' => 'stock',  'base' => 128.50, 'vol' => 0.028],
        ['symbol' => 'TSLA', 'name' => 'Tesla Inc',           'type' => 'stock',  'base' => 342.10, 'vol' => 0.035],
        ['symbol' => 'AMD',  'name' => 'Adv. Micro Devices',  'type' => 'stock',  'base' => 162.40, 'vol' => 0.032],
        ['symbol' => 'META', 'name' => 'Meta Platforms',      'type' => 'stock',  'base' => 512.00, 'vol' => 0.022],
        ['symbol' => 'GOOGL','name' => 'Alphabet Inc',        'type' => 'stock',  'base' => 178.40, 'vol' => 0.020],
        ['symbol' => 'AMZN', 'name' => 'Amazon.com Inc',      'type' => 'stock',  'base' => 198.70, 'vol' => 0.024],
    ];
}

/**
 * Get the current price for a symbol.
 * Cached for 20 seconds per request to avoid blowing API quotas.
 */
function get_price(string $symbol): ?float
{
    static $cache = [];
    $key = strtoupper($symbol);
    $now = time();

    if (isset($cache[$key]) && $now - $cache[$key]['t'] < 20) {
        return $cache[$key]['p'];
    }

    $assets = tracked_assets();
    $found = null;
    foreach ($assets as $a) {
        if ($a['symbol'] === $key) { $found = $a; break; }
    }
    if (!$found) return null;

    $live  = finnhub_quote($key);
    $price = $live ?? simulated_price($key, (float)$found['base'], (float)$found['vol']);

    $cache[$key] = ['p' => $price, 't' => $now];
    return $price;
}

/**
 * Call Finnhub /quote. Returns null on any failure.
 */
function finnhub_quote(string $symbol): ?float
{
    if (FINNHUB_API_KEY === '') return null;

    $url = 'https://finnhub.io/api/v1/quote?symbol=' . urlencode($symbol)
         . '&token=' . urlencode(FINNHUB_API_KEY);

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body && $code === 200) {
            $json = json_decode($body, true);
            if (isset($json['c']) && $json['c'] > 0) return (float)$json['c'];
        }
        return null;
    }

    $ctx = stream_context_create([
        'http' => ['timeout' => 5, 'ignore_errors' => true],
        'ssl'  => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);
    $body = @file_get_contents($url, false, $ctx);
    if ($body === false) return null;

    $json = json_decode($body, true);
    if (isset($json['c']) && $json['c'] > 0) return (float)$json['c'];
    return null;
}

/**
 * Deterministic pseudo-price with two waves:
 *   - slow: multi-day drift, moves every few hours
 *   - fast: 15-minute-resolution wiggle, smooth across calls
 * No hour-boundary jumps, no flat spots.
 */
function simulated_price(string $symbol, float $base, float $vol): float
{
    $seed = crc32($symbol);
    $t    = time();

    $slow = sin(($t / 86400 + $seed % 100) * 0.35) * 0.6;
    $fast = sin(($t / 900   + $seed % 50)  * 1.10) * 0.15;

    $wave  = $slow + $fast;
    $price = $base * (1.0 + $wave * $vol * 3.0);
    return round(max($price, 0.0000001), 8);
}

/**
 * Synthetic price history with the same wave function, in 15-minute steps
 * so it lines up with simulated_price().
 */
function synthetic_history(string $symbol, int $points = 60): array
{
    $assets = tracked_assets();
    $base = 100.0; $vol = 0.02;
    foreach ($assets as $a) {
        if ($a['symbol'] === strtoupper($symbol)) { $base = $a['base']; $vol = $a['vol']; break; }
    }

    $seed = crc32($symbol);
    $now  = time();
    $out  = [];

    for ($i = $points - 1; $i >= 0; $i--) {
        $t    = $now - $i * 900;
        $slow = sin(($t / 86400 + $seed % 100) * 0.35) * 0.6;
        $fast = sin(($t / 900   + $seed % 50)  * 1.10) * 0.15;
        $wave = $slow + $fast;
        $out[] = round($base * (1.0 + $wave * $vol * 3.0), 8);
    }
    return $out;
}