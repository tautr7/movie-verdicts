<?php

/**
 * For security, especially when handling form submissions like adding favorites and submitting reviews, implement Cross-Site Request Forgery (CSRF) protection.
 * This function generates a CSRF token and stores it in the session.
 * @return string The CSRF token
 * @throws Exception
 * @see https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html
 * @see https://owasp.org/www-community/attacks/csrf
 */
function generateCsrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(string: random_bytes(length: 32));
    }
    return $_SESSION['csrf_token'];
}

// Function to verify CSRF token
function verifyCsrfToken($token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals(known_string: $_SESSION['csrf_token'], user_string: $token);
}