<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGatoGraphQL\WebserverRequests\Environment;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait;

abstract class AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use ExecuteRESTWebserverRequestTestCaseTrait;
    use WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait;

    protected string $applicationPassword;

    /**
     * Retrieve the admin's application password
     */
    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Modify the post data before executing the ":enabled" test
         */
        $dataName = $this->getDataName();
        $this->applicationPassword = $this->executeRESTEndpointToGetApplicationPassword($dataName);
    }

    /**
     * @return array<string,mixed>
     */
    protected function executeRESTEndpointToGetApplicationPassword(
        string $dataName,
    ): array {
        $client = static::getClient();
        $endpointURL = $this->getUserRESTEndpointURL($dataName);
        $options = [];
        $response = $client->get(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTGetCallIsSuccessful($response);
        $body = $response->getBody()->__toString();
        return json_decode($body, true);
    }

    protected function getUserRESTEndpointURL(string $dataName): string
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
            ''
        );
    }
}
