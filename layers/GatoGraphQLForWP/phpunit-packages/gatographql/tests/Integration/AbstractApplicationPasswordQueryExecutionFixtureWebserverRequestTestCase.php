<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait;

abstract class AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait;

    protected static string $applicationPassword;

    /**
     * Retrieve the admin's application password
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        /**
         * Modify the post data before executing the ":enabled" test
         */
        self::$applicationPassword = static::executeRESTEndpointToGetApplicationPassword();
    }

    protected static function executeRESTEndpointToGetApplicationPassword(): string
    {
        $client = static::getClient();
        $endpointURL = static::getUserRESTEndpointURL();
        $options = [];
        $response = $client->get(
            $endpointURL,
            $options,
        );
        $body = $response->getBody()->__toString();
        $content = json_decode($body, true);
        /**
         * Same as UserMetaKeys::APP_PASSWORD
         *
         * @see layers/GatoGraphQLForWP/phpunit-plugins/gatographql-testing/src/Constants/UserMetaKeys.php
         */
        return $content['app_password'] ?? '';
    }

    protected static function getUserRESTEndpointURL(): string
    {
        /**
         * The app_password is also stored under this (non-admin) user.
         *
         * @see webservers/gatographql/setup/add-user-application-passwords.sh
         */
        $userID = 2;
        return static::getWebserverHomeURL() . '/wp-json/wp/v2/users/' . $userID;
    }

    protected static function getApplicationPassword(): string
    {
        return sprintf(
            '%s:%s',
            Environment::getIntegrationTestsAuthenticatedAdminUserUsername(),
            self::$applicationPassword
        );
    }
}
