<?php
namespace PoP\EditUsers\WP;

class HelperAPI extends \PoP\EditUsers\HelperAPI_Base
{
    public function sanitizeUsername(string $username)
    {
        return sanitize_user($username);
    }
}

/**
 * Initialize
 */
new HelperAPI();
