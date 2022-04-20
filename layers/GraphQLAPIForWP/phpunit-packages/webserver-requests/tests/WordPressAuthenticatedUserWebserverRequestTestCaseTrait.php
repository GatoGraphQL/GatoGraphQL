<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\Cookie\CookieJar;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\Constants\CustomHeaders;
use PHPUnitForGraphQLAPI\WebserverRequests\Environment;
use Psr\Http\Message\ResponseInterface;

trait WordPressAuthenticatedUserWebserverRequestTestCaseTrait
{
    protected static string $wpRESTNonce = '';

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

    protected static function getLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedUserUsername();
    }

    protected static function getLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedUserPassword();
    }

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
        $username = static::getLoginUsername();
        if ($username === '' || static::getLoginPassword() === '') {
            return 'The credentials to authenticate the user are incomplete or missing';
        }
        return sprintf('Authentication of user "%s" did not succeed', $username);
    }

    /**
     * Store the REST Nonce
     *
     * @param array<string,mixed> $options
     */
    protected static function postWebserverPingResponse(
        ResponseInterface $response,
        array $options
    ): void {
        static::$wpRESTNonce = $response->getHeaderLine(CustomHeaders::WP_REST_NONCE);
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
