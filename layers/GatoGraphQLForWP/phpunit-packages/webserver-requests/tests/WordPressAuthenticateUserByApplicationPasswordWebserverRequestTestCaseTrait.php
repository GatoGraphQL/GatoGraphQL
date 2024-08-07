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
        $options[RequestOptions::HEADERS]['Authorization'] = static::getApplicationPasswordAuthorizationHeader();
        return $options;
    }

    protected static function getApplicationPasswordAuthorizationHeader(?string $usernameToLogin = null): string
    {
        return sprintf(
            'Basic %s',
            base64_encode(static::getApplicationPassword($usernameToLogin))
        );
    }

    abstract protected static function getApplicationPassword(?string $usernameToLogin = null): string;
}
