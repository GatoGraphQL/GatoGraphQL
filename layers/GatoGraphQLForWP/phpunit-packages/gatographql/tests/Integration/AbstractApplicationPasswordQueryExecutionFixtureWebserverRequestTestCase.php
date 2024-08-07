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

    /** @var array<string,string> Key: role, Value: application password */
    protected static array $applicationPasswords;

    /**
     * Retrieve the admin's application password
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        /**
         * Modify the post data before executing the ":enabled" test
         */
        self::$applicationPasswords = static::executeRESTEndpointToGetApplicationPasswords();
    }

    /**
     * @return array<string,string>
     */
    protected static function executeRESTEndpointToGetApplicationPasswords(): array
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
        return static::getUserApplicationPasswordsFromResponse($content);
    }

    /**
     * Same as UserMetaKeys::APP_PASSWORD and the UserRole specific meta keys
     *
     * @see layers/GatoGraphQLForWP/phpunit-plugins/gatographql-testing/src/Constants/UserMetaKeys.php
     *
     * @param array<string,mixed> $content
     * @return array<string,string>
     */
    protected static function getUserApplicationPasswordsFromResponse(array $content): array
    {
        $appPasswords = [];
        $users = [
            self::USER_ADMIN,
            self::USER_EDITOR,
            self::USER_AUTHOR,
            self::USER_CONTRIBUTOR,
            self::USER_SUBSCRIBER,
        ];
        foreach ($users as $user) {
            $appPasswordByUserRoleMetaKey = sprintf(
                'app_password:%s',
                $user
            );
            $appPasswords[$user] = $content[$appPasswordByUserRoleMetaKey] ?? '';
        }
        return $appPasswords;
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

    protected static function getApplicationPassword(string $usernameToLogin): string
    {
        return sprintf(
            '%s:%s',
            $usernameToLogin,
            self::$applicationPasswords[$usernameToLogin] ?? ''
        );
    }

    protected static function getUsernameToLogin(): ?string
    {
        $userToLogin = static::getUserToLogin();
        return match ($userToLogin) {
            null => null,
            self::USER_ADMIN => Environment::getIntegrationTestsAuthenticatedAdminUserUsername(),
            self::USER_EDITOR => Environment::getIntegrationTestsAuthenticatedEditorUserUsername(),
            self::USER_AUTHOR => Environment::getIntegrationTestsAuthenticatedAuthorUserUsername(),
            self::USER_CONTRIBUTOR => Environment::getIntegrationTestsAuthenticatedContributorUserUsername(),
            self::USER_SUBSCRIBER => Environment::getIntegrationTestsAuthenticatedSubscriberUserUsername(),
            default => throw new RuntimeException(sprintf('Unexpected user \'%s\'', $userToLogin)),
        };
    }

    protected static function getUserToLogin(): ?string
    {
        return self::USER_ADMIN;
    }
}
