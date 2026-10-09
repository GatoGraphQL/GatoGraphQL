<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use function filter_var;
use function getenv;

class Environment
{
    public final const INTEGRATION_TESTS_WEBSERVER_DOMAIN = 'INTEGRATION_TESTS_WEBSERVER_DOMAIN';
    public final const INTEGRATION_TESTS_WEBSERVER_ROUTE = 'INTEGRATION_TESTS_WEBSERVER_ROUTE';
    public final const INTEGRATION_TESTS_WEBSERVER_ROUTE_REQUIRED = 'INTEGRATION_TESTS_WEBSERVER_ROUTE_REQUIRED';
    public final const INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_USERNAME = 'INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_USERNAME';
    public final const INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_PASSWORD = 'INTEGRATION_TESTS_AUTHENTICATED_ADMIN_USER_PASSWORD';
    public final const INTEGRATION_TESTS_AUTHENTICATED_CONTRIBUTOR_USER_USERNAME = 'INTEGRATION_TESTS_AUTHENTICATED_CONTRIBUTOR_USER_USERNAME';
    public final const INTEGRATION_TESTS_AUTHENTICATED_CONTRIBUTOR_USER_PASSWORD = 'INTEGRATION_TESTS_AUTHENTICATED_CONTRIBUTOR_USER_PASSWORD';
    public final const INTEGRATION_TESTS_AUTHENTICATED_AUTHOR_USER_USERNAME = 'INTEGRATION_TESTS_AUTHENTICATED_AUTHOR_USER_USERNAME';
    public final const INTEGRATION_TESTS_AUTHENTICATED_AUTHOR_USER_PASSWORD = 'INTEGRATION_TESTS_AUTHENTICATED_AUTHOR_USER_PASSWORD';
    public final const INTEGRATION_TESTS_AUTHENTICATED_EDITOR_USER_USERNAME = 'INTEGRATION_TESTS_AUTHENTICATED_EDITOR_USER_USERNAME';
    public final const INTEGRATION_TESTS_AUTHENTICATED_EDITOR_USER_PASSWORD = 'INTEGRATION_TESTS_AUTHENTICATED_EDITOR_USER_PASSWORD';
    public final const INTEGRATION_TESTS_AUTHENTICATED_SUBSCRIBER_USER_USERNAME = 'INTEGRATION_TESTS_AUTHENTICATED_SUBSCRIBER_USER_USERNAME';
    public final const INTEGRATION_TESTS_AUTHENTICATED_SUBSCRIBER_USER_PASSWORD = 'INTEGRATION_TESTS_AUTHENTICATED_SUBSCRIBER_USER_PASSWORD';
    public final const CONTINUOUS_INTEGRATION_VALID_TESTING_DOMAINS = 'CONTINUOUS_INTEGRATION_VALID_TESTING_DOMAINS';

    public static function getIntegrationTestsWebserverDomain(): string
    {
        $envVar = self::INTEGRATION_TESTS_WEBSERVER_DOMAIN;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    /**
     * When several webservers share the same domain behind a proxy,
     * the route tells the proxy which of them must handle the request
     */
    public static function getIntegrationTestsWebserverRoute(): string
    {
        $envVar = self::INTEGRATION_TESTS_WEBSERVER_ROUTE;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    /**
     * Without a route, the requests would be handled by a different
     * webserver than the one running the code under test
     */
    public static function isIntegrationTestsWebserverRouteRequired(): bool
    {
        $envVar = self::INTEGRATION_TESTS_WEBSERVER_ROUTE_REQUIRED;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return false;
        }
        return filter_var($envVarValue, FILTER_VALIDATE_BOOLEAN);
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

    public static function getIntegrationTestsAuthenticatedContributorUserUsername(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_CONTRIBUTOR_USER_USERNAME;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedContributorUserPassword(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_CONTRIBUTOR_USER_PASSWORD;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedAuthorUserUsername(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_AUTHOR_USER_USERNAME;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedAuthorUserPassword(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_AUTHOR_USER_PASSWORD;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedEditorUserUsername(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_EDITOR_USER_USERNAME;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedEditorUserPassword(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_EDITOR_USER_PASSWORD;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedSubscriberUserUsername(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_SUBSCRIBER_USER_USERNAME;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    public static function getIntegrationTestsAuthenticatedSubscriberUserPassword(): string
    {
        $envVar = self::INTEGRATION_TESTS_AUTHENTICATED_SUBSCRIBER_USER_PASSWORD;
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return '';
        }
        return $envVarValue;
    }

    /**
     * @return string[]
     */
    public static function getContinuousIntegrationValidTestingDomains(): array
    {
        $envVar = self::CONTINUOUS_INTEGRATION_VALID_TESTING_DOMAINS;
        /** @var string|false */
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return [
                'instawp.xyz',
            ];
        }
        return explode(',', $envVarValue);
    }
}
