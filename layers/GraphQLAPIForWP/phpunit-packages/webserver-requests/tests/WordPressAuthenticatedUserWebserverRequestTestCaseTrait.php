<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

trait WordPressAuthenticatedUserWebserverRequestTestCaseTrait
{
    abstract protected static function getWebserverHomeURL(): string;
    
    protected static function getWebserverPingURL(): string
    {
        return static::getWebserverHomeURL() . '/wp-login.php';
    }

    /**
     * Must re-use the cookies received when logging in
     *
     * @see https://docs.guzzlephp.org/en/stable/quickstart.html#cookies
     */
    protected static function shareCookies(): bool
    {
        return true;
    }
    
    protected function getMethod(): string
    {
        return 'POST';
    }
}
