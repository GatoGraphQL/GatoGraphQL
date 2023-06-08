<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

trait AccessPasswordProtectedPostWebserverRequestTestCaseTrait
{
    abstract protected static function getWebserverHomeURL(): string;

    /**
     * To provide the password for the post in WordPress, request the following URL by POST:
     *
     *   curl 'https://gato-graphql.lndo.site/wp-login.php?action=postpass' -i -X POST -H 'Content-Type: application/x-www-form-urlencoded' --data-raw 'post_password=password'
     */
    protected static function getWebserverPingURL(): string
    {
        return static::getWebserverHomeURL() . '/wp-login.php?action=postpass';
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getWebserverPingOptions(): array
    {
        return array_merge(
            parent::getWebserverPingOptions(),
            [
                'form_params' => [
                    'post_password' => static::getPostPassword(),
                ],
            ]
        );
    }

    protected static function getPostPassword(): string
    {
        return 'password';
    }

    protected static function getWebserverPingMethod(): string
    {
        return 'POST';
    }

    /**
     * Make sure the password was successfully provided
     *
     * @param array<string,mixed> $options
     */
    protected static function validateWebserverPingResponse(
        ResponseInterface $response,
        array $options
    ): ?string {
        /** @var CookieJar */
        $cookieJar = $options[RequestOptions::COOKIES];
        foreach ($cookieJar->getIterator() as $cookie) {
            if (str_starts_with($cookie->getName(), 'wp-postpass_')) {
                return null;
            }
        }
        return 'Providing the password for the post did not succeed';
    }

    /**
     * Must re-use the cookies received when providing the password
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
