<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class UrlEncryption
{
    /**
     * Encrypt an ID to create an obfuscated URL parameter
     * Example: 1 -> "eyJpdiI6IkM4RVdVaXU4aHRCQmtVTWNZRW9WQXc9PSIsInZhbHVlIjoiL..."
     * 
     * @param int|string $id
     * @return string Encrypted ID safe for URLs
     */
    public static function encryptId($id)
    {
        try {
            $encrypted = Crypt::encryptString((string)$id);
            // Make URL-safe by replacing base64 characters
            return str_replace(['/', '+', '='], ['-', '_', ''], $encrypted);
        } catch (\Exception $e) {
            return (string)$id;
        }
    }

    /**
     * Decrypt an obfuscated URL parameter back to original ID
     * 
     * @param string $encrypted
     * @return int|null Original ID or null if decryption fails
     */
    public static function decryptId($encrypted)
    {
        try {
            // Reverse URL-safe conversion
            $encrypted = str_replace(['-', '_'], ['+', '/'], $encrypted);
            // Add padding if needed
            $encrypted = $encrypted . str_repeat('=', (4 - strlen($encrypted) % 4) % 4);
            
            $decrypted = Crypt::decryptString($encrypted);
            return intval($decrypted);
        } catch (DecryptException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate random hash for URL fragments (like #news, #dashboard)
     * This hash changes on every page load, making URLs non-guessable
     * 
     * @param string $prefix Optional prefix for the hash
     * @return string Random hash (e.g., "news-a7f8d9c3e2b1")
     */
    public static function generateFragmentHash($prefix = '')
    {
        $randomPart = substr(bin2hex(random_bytes(8)), 0, 12);
        if ($prefix) {
            return $prefix . '-' . $randomPart;
        }
        return $randomPart;
    }

    /**
     * Generate a session-based random hash for navigation
     * Same hash throughout the session, but different for each user/session
     * 
     * @param string $prefix
     * @return string Consistent hash for this session
     */
    public static function getSessionHash($prefix = '')
    {
        $sessionKey = 'url_hash_' . $prefix;
        
        if (!session()->has($sessionKey)) {
            session([$sessionKey => self::generateFragmentHash($prefix)]);
        }
        
        return session($sessionKey);
    }

    /**
     * Create an obfuscated resource slug
     * Combines encrypted ID with random hash
     * Example: resource-1 -> resource-eyJ2YWxv...a7f8d9
     * 
     * @param string $type Resource type (news, dashboard, etc)
     * @param int $id Resource ID
     * @return string Obfuscated slug
     */
    public static function createObfuscatedSlug($type, $id)
    {
        $encrypted = self::encryptId($id);
        $hash = substr(bin2hex(random_bytes(4)), 0, 8);
        return "{$type}-{$encrypted}-{$hash}";
    }

    /**
     * Extract and decrypt ID from obfuscated slug
     * Example: resource-eyJ2YWxv...a7f8d9 -> extracts encrypted part and decrypts to ID
     * 
     * @param string $slug Obfuscated slug
     * @return int|null Original ID or null if invalid
     */
    public static function extractIdFromSlug($slug)
    {
        // Slug format: type-encrypted-hash
        $parts = explode('-', $slug, 3);
        
        if (count($parts) < 2) {
            return null;
        }
        
        $encrypted = $parts[1];
        return self::decryptId($encrypted);
    }
}
