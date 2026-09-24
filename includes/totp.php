<?php
/**
 * AlphaEdge · TOTP (RFC 6238)
 * Self-contained implementation of Time-based One-Time Passwords.
 * Compatible with Google Authenticator, Authy, 1Password, Microsoft Authenticator.
 *
 * No external dependencies. Uses PHP's built-in hash_hmac + random_bytes.
 *
 * Typical flow:
 *   $secret = TOTP::generateSecret();          // show to user as QR
 *   TOTP::verifyCode($secret, $userInput);     // validate 6-digit code
 */

class TOTP
{
    /** Number of digits in generated codes (Google Auth default: 6). */
    public const DIGITS = 6;

    /** Time step in seconds (RFC 6238 recommends 30). */
    public const PERIOD = 30;

    /** HMAC algorithm (SHA1 is standard for Google Auth). */
    public const ALGO = 'sha1';

    /** How many time-windows in the past/future are accepted (clock drift). */
    public const WINDOW = 1;

    /** Base32 alphabet (no padding char in generated secret). */
    private const B32 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /* ============================================================
     *  SECRET MANAGEMENT
     * ============================================================ */

    /**
     * Generate a random Base32 secret of the given length.
     * 32 chars = 160 bits of entropy, matching Google Auth convention.
     */
    public static function generateSecret(int $length = 32): string
    {
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= self::B32[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Generate N one-time recovery codes (e.g. "a7f3-9d21").
     * Returns plaintext codes — hash them before storing.
     */
    public static function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $raw = bin2hex(random_bytes(4)); // 8 hex chars
            $codes[] = substr($raw, 0, 4) . '-' . substr($raw, 4, 4);
        }
        return $codes;
    }

    /* ============================================================
     *  CODE GENERATION
     * ============================================================ */

    /**
     * Generate the 6-digit code for a secret at a given Unix timestamp.
     * If $timestamp is null, uses the current time.
     */
    public static function getCode(string $secret, ?int $timestamp = null): string
    {
        $timestamp = $timestamp ?? time();
        $counter   = (int)floor($timestamp / self::PERIOD);

        // 8-byte big-endian counter
        $binCounter = pack('N*', 0) . pack('N*', $counter);

        // HMAC-SHA1
        $key    = self::base32Decode($secret);
        $hash   = hash_hmac(self::ALGO, $binCounter, $key, true);

        // Dynamic truncation (RFC 4226 §5.4)
        $offset = ord($hash[strlen($hash) - 1]) & 0x0F;
        $part   = substr($hash, $offset, 4);
        $value  = unpack('N', $part)[1] & 0x7FFFFFFF;

        $mod = 10 ** self::DIGITS;
        return str_pad((string)($value % $mod), self::DIGITS, '0', STR_PAD_LEFT);
    }

    /**
     * Verify a code against a secret, allowing a small clock-drift window.
     * Returns true if the code matches any of the accepted time windows.
     */
    public static function verifyCode(string $secret, string $code, int $window = self::WINDOW): bool
    {
        $code = preg_replace('/\s+/', '', $code);
        if (!preg_match('/^\d{' . self::DIGITS . '}$/', $code)) return false;

        $now = time();
        for ($i = -$window; $i <= $window; $i++) {
            $check = self::getCode($secret, $now + ($i * self::PERIOD));
            if (hash_equals($check, $code)) return true;
        }
        return false;
    }

    /* ============================================================
     *  OTPAUTH URI (for QR codes)
     * ============================================================ */

    /**
     * Build the otpauth:// URI that Google Auth will encode into a QR.
     */
    public static function getProvisioningUri(
        string $secret,
        string $accountName,
        string $issuer = 'AlphaEdge',
        int $digits = self::DIGITS,
        int $period = self::PERIOD
    ): string {
        $label = rawurlencode($issuer) . ':' . rawurlencode($accountName);
        $params = http_build_query([
            'secret'    => $secret,
            'issuer'    => $issuer,
            'algorithm' => strtoupper(self::ALGO),
            'digits'    => $digits,
            'period'    => $period,
        ]);
        return 'otpauth://totp/' . $label . '?' . $params;
    }

    /* ============================================================
     *  BASE32 (RFC 4648, no padding)
     * ============================================================ */

    /**
     * Encode raw bytes as Base32. Used only if you need to convert
     * a custom key; generateSecret() already produces Base32 directly.
     */
    public static function base32Encode(string $data): string
    {
        $bits = '';
        foreach (str_split($data) as $c) {
            $bits .= str_pad(decbin(ord($c)), 8, '0', STR_PAD_LEFT);
        }
        $out = '';
        for ($i = 0; $i + 5 <= strlen($bits); $i += 5) {
            $out .= self::B32[bindec(substr($bits, $i, 5))];
        }
        return $out;
    }

    /**
     * Decode a Base32 string to raw bytes. Handles spacing, casing,
     * and optional '=' padding gracefully.
     */
    public static function base32Decode(string $data): string
    {
        $data = strtoupper(preg_replace('/[^A-Z2-7]/i', '', $data));
        if ($data === '') return '';

        $bits = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $pos = strpos(self::B32, $data[$i]);
            if ($pos === false) continue;
            $bits .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }

        $out = '';
        for ($i = 0; $i + 8 <= strlen($bits); $i += 8) {
            $out .= chr(bindec(substr($bits, $i, 8)));
        }
        return $out;
    }
}