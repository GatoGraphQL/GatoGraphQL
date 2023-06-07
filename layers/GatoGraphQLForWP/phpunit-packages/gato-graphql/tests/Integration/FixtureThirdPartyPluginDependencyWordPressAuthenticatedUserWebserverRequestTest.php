<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractFixtureThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase;

/**
 * Test that enabling/disabling a required 3rd-party plugin works well.
 */
class FixtureThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTest extends AbstractFixtureThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-3rd-party-plugins';
    }
}
