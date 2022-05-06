<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use function getenv;

class Environment
{
    public final const INTEGRATION_TESTS_WEBSERVER_DOMAIN = 'INTEGRATION_TESTS_WEBSERVER_DOMAIN';
    public final const INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_USERNAME = 'INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_USERNAME';
    public final const INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_PASSWORD = 'INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_PASSWORD';

    public static function getIntegrationTestsWebserverDomain(): string
    {
        $envVar = self::INTEGRATION_TESTS_WEBSERVER_DOMAIN;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedAdminUserUsername(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_USERNAME;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedAdminUserPassword(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_PASSWORD;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }
}
