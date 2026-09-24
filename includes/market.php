<?php
/**
 * Market data provider.
 *
 * Uses Finnhub for live quotes when FINNHUB_API_KEY is set.
 * When the key is missing OR the API call fails, falls back to a
 * deterministic simulated price so the whole app still works offline.
 *
 * Free tier: https://finnhub.io  (60 req/min)
 */

require_once __DIR__ . '/../config/config.php';

/** Assets we track and analyze. */
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
 * Returns null only when the symbol is not in our tracked list.
 */
function get_price(string $symbol): ?float
{
    $symbol = strtoupper($symbol);
    $assets = tracked_assets();

    $found = null;
    foreach ($assets as $a) {
        if ($a['symbol'] === $symbol) { $found = $a; break; }
    }
    if (!$found) return null;

    $live = finnhub_quote($symbol);
    if ($live !== null) return $live;

    return simulated_price($symbol, (float)$found['base'], (float)$found['vol']);
}

/**
 * Call Finnhub /quote. Returns null on any failure.
 */
function finnhub_quote(string $symbol): ?float
{
    if (FINNHUB_API_KEY === '') return null;

    $url = 'https://finnhub.io/api/v1/quote?symbol=' . urlencode($symbol)
         . '&token=' . urlencode(FINNHUB_API_KEY);

    // ---- Prefer cURL if available ----
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

    // ---- Fallback: file_get_contents ----
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
 * Deterministic pseudo-price that drifts slowly and realistically,
 * seeded by symbol + hour so different requests within the same hour
 * see the same value (looks like a real quote, but is generated).
 */
function simulated_price(string $symbol, float $base, float $vol): float
{
    $seed = crc32($symbol);
    $hour = (int)floor(time() / 3600);
    $day  = (int)floor(time() / 86400);

    // Two sine waves = smooth, day-varying drift
    $slow = sin(($day  + $seed % 100) * 0.35) * 0.6;
    $fast = sin(($hour + $seed % 50)  * 1.10) * 0.4;
    $wave = ($slow + $fast) / 2.0;

    $price = $base * (1.0 + $wave * $vol * 6.0);
    return round(max($price, 0.0000001), 8);
}

/**
 * Build a tiny synthetic price history for a symbol, using the same
 * seeded generator. Used by the signal engine for indicators.
 */
function synthetic_history(string $symbol, int $points = 60): array
{
    $assets = tracked_assets();
    $base = 100.0; $vol = 0.02;
    foreach ($assets as $a) {
        if ($a['symbol'] === strtoupper($symbol)) { $base = $a['base']; $vol = $a['vol']; break; }
    }

    $seed  = crc32($symbol);
    $now   = time();
    $price = $base;
    $out   = [];

    // Build backwards from "now", then reverse
    for ($i = $points - 1; $i >= 0; $i--) {
        $t = $now - $i * 3600;
        $day  = (int)floor($t / 86400);
        $hour = (int)floor($t / 3600);
        $slow = sin(($day  + $seed % 100) * 0.35) * 0.6;
        $fast = sin(($hour + $seed % 50)  * 1.10) * 0.4;
        $wave = ($slow + $fast) / 2.0;
        $out[] = round($base * (1.0 + $wave * $vol * 6.0), 8);
    }
    return $out;
}