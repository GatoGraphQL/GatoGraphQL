<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GuzzleHttp\RequestOptions;

trait WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait
{
    /**
     * @return array<string,mixed>
     */
    protected static function getRequestBasicOptions(): array
    {
        $options = parent::getRequestBasicOptions();
        $usernameToLogin = static::getUsernameToLogin();
        if ($usernameToLogin !== null) {
            $options[RequestOptions::HEADERS]['Authorization'] = static::getApplicationPasswordAuthorizationHeader($usernameToLogin);
        }
        return $options;
    }

    protected static function getApplicationPasswordAuthorizationHeader(string $usernameToLogin): string
    {
        return sprintf(
            'Basic %s',
            base64_encode(static::getApplicationPassword($usernameToLogin))
        );
    }

    abstract protected static function getApplicationPassword(string $usernameToLogin): string;
}
