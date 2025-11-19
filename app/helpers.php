<?php

use Illuminate\Support\Facades\Session;
use App\Helpers\UrlEncryption;

if (!function_exists('csp_nonce')) {
    /**
     * Generate a Content Security Policy nonce
     *
     * @return string
     */
    function csp_nonce() {
        if (!Session::has('csp-nonce')) {
            Session::put('csp-nonce', base64_encode(random_bytes(16)));
        }
        return Session::get('csp-nonce');
    }
}

if (!function_exists('encrypt_id')) {
    /**
     * Encrypt an ID for use in URLs
     * 
     * @param int|string $id
     * @return string Encrypted ID
     */
    function encrypt_id($id)
    {
        return UrlEncryption::encryptId($id);
    }
}

if (!function_exists('decrypt_id')) {
    /**
     * Decrypt an ID from URL
     * 
     * @param string $encrypted
     * @return int|null
     */
    function decrypt_id($encrypted)
    {
        return UrlEncryption::decryptId($encrypted);
    }
}

if (!function_exists('random_hash')) {
    /**
     * Generate session-based random hash for URL fragments
     * 
     * @param string $prefix
     * @return string
     */
    function random_hash($prefix = '')
    {
        return UrlEncryption::getSessionHash($prefix);
    }
}

if (!function_exists('obfuscated_slug')) {
    /**
     * Create obfuscated resource slug
     * 
     * @param string $type Resource type
     * @param int $id Resource ID
     * @return string
     */
    function obfuscated_slug($type, $id)
    {
        return UrlEncryption::createObfuscatedSlug($type, $id);
    }
}

if (!function_exists('route_encrypted')) {
    /**
     * Generate encrypted route URL
     * 
     * @param string $name Route name
     * @param array $parameters Route parameters
     * @param bool $absolute Generate absolute URL
     * @return string Encrypted route URL
     */
    function route_encrypted($name, $parameters = [], $absolute = true)
    {
        return \App\Services\RouteEncryption::route($name, $parameters, $absolute);
    }
}

if (!function_exists('link_encrypted')) {
    /**
     * Generate encrypted route link
     * 
     * @param string $text Link text
     * @param string $name Route name
     * @param array $parameters Route parameters
     * @param array $attributes HTML attributes
     * @return string HTML link
     */
    function link_encrypted($text, $name, $parameters = [], $attributes = [])
    {
        return \App\Services\RouteEncryption::link($text, $name, $parameters, $attributes);
    }
}
