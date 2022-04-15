<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

trait WordPressAuthenticatedUserWebserverRequestTestCaseTrait
{
    abstract protected static function getWebserverHomeURL(): string;
    
    /**
     * To login a user in WordPress, request the following URL by post:
     *
     *   curl 'http://graphql-api.lndo.site/wp-login.php' -i -X POST -H 'Content-Type: application/x-www-form-urlencoded' -H 'Cookie: wordpress_test_cookie=WP%20Cookie%20check; wp_lang=en_US' --data-raw 'log=admin&pwd=admin&rememberme=forever&wp-submit=Log+In'
     */
    protected static function getWebserverPingURL(): string
    {
        return static::getWebserverHomeURL() . '/wp-login.php';
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getWebserverPingOptions(): array
    {
        return [
            'form_params' => [
                'log' => 'admin',
                'pwd' => 'admin',
                'rememberme' => 'forever',
                'wp-submit' => 'Log+In',
            ],
        ];
    }

    protected static function getWebserverPingMethod(): string
    {
        return 'POST';
    }

    /**
     * @return array<string,string>
     */
    protected static function getCookies(): array
    {
        return [
            'wordpress_test_cookie' => 'WP%20Cookie%20check'
        ];
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
