<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait;

abstract class AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticateUserByApplicationPasswordWebserverRequestTestCaseTrait;

    protected static function getApplicationPassword(): string
    {
        return sprintf(
            '%s:%s',
            Environment::getIntegrationTestsAuthenticatedAdminUserUsername(),
            ''
        );
    }
}
