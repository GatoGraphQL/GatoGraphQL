<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\Cookie\CookieJar;
use Psr\Http\Message\ResponseInterface;

trait WordPressAuthenticatedUserWebserverRequestTestCaseTrait
{
    abstract protected static function getWebserverHomeURL(): string;
    
    /**
     * To login a user in WordPress, request the following URL by post:
     *
     *   curl 'http://graphql-api.lndo.site/wp-login.php' -i -X POST -H 'Content-Type: application/x-www-form-urlencoded' --data-raw 'log=admin&pwd=admin'
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
                'log' => static::getLoginUsername(),
                'pwd' => static::getLoginPassword(),
            ],
        ];
    }

    abstract protected static function getLoginUsername(): string;
    abstract protected static function getLoginPassword(): string;

    protected static function getWebserverPingMethod(): string
    {
        return 'POST';
    }

    /**
     * Make sure the user was successfully logged-in
     * 
     * @param array<string,mixed> $options
     */
    protected static function validateWebserverPingResponse(
        ResponseInterface $response,
        array $options
    ): ?string {
        /** @var CookieJar */
        $cookieJar = $options['cookies'];
        foreach ($cookieJar->getIterator() as $cookie) {
            if (str_starts_with($cookie->getName(), 'wordpress_logged_in_')) {
                return null;
            }
        }
        return sprintf('The user "%s" was not logged in', static::getLoginUsername());
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
