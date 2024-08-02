<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GuzzleHttp\RequestOptions;
use PHPUnitForGatoGraphQL\WebserverRequests\Environment;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait;
use RuntimeException;

abstract class AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait;

    /**
     * The app_password is also stored under this (non-admin) user.
     *
     * @see webservers/gatographql/setup/add-user-application-passwords.sh
     */
    public const USER_STORING_APP_PASSWORDS_IN_META_USER_ID = 2;    

    public const USER_ADMIN = 'admin';
    public const USER_EDITOR = 'editor';
    public const USER_AUTHOR = 'author';
    public const USER_CONTRIBUTOR = 'contributor';
    public const USER_SUBSCRIBER = 'subscriber';

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
        $options = [
            RequestOptions::VERIFY => false,
        ];
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
        return static::getUserApplicationPasswordFromResponse($content);
    }

    /**
     * Same as UserMetaKeys::APP_PASSWORD and the UserRole specific meta keys
     *
     * @see layers/GatoGraphQLForWP/phpunit-plugins/gatographql-testing/src/Constants/UserMetaKeys.php
     *
     * @param array<string,mixed> $content
     */
    protected static function getUserApplicationPasswordFromResponse(array $content): string
    {
        $userToLogin = static::getUserToLogin();
        $appPasswordByUserRoleMetaKey = sprintf(
            'app_password:%s',
            $userToLogin
        );        
        return $content[$appPasswordByUserRoleMetaKey] ?? $content['app_password'] ?? '';
    }

    /**
     * The app_password is also stored under this (non-admin) user.
     *
     * @see webservers/gatographql/setup/add-user-application-passwords.sh
     */
    protected static function getUserRESTEndpointURL(): string
    {
        return sprintf(
            '%s/wp-json/wp/v2/users/%s',
            static::getWebserverHomeURL(),
            self::USER_STORING_APP_PASSWORDS_IN_META_USER_ID
        );
    }

    protected static function getApplicationPassword(): string
    {
        return sprintf(
            '%s:%s',
            static::getUsernameToLogin(),
            self::$applicationPassword
        );
    }

    protected static function getUsernameToLogin(): string
    {
        $userToLogin = static::getUserToLogin();
        return match ($userToLogin) {
            self::USER_ADMIN => Environment::getIntegrationTestsAuthenticatedAdminUserUsername(),
            self::USER_EDITOR => Environment::getIntegrationTestsAuthenticatedEditorUserUsername(),
            self::USER_AUTHOR => Environment::getIntegrationTestsAuthenticatedAuthorUserUsername(),
            self::USER_CONTRIBUTOR => Environment::getIntegrationTestsAuthenticatedContributorUserUsername(),
            self::USER_SUBSCRIBER => Environment::getIntegrationTestsAuthenticatedSubscriberUserUsername(),
            default => throw new RuntimeException(sprintf('Unexpected user \'%s\'', $userToLogin)),
        };
    }

    protected static function getUserToLogin(): string
    {
        return self::USER_ADMIN;
    }
}
