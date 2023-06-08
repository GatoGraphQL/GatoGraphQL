<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase;

/**
 * Test that, when the "Pages" module is disabled, querying
 * for cpt "page" in field "customPosts" is handled by the
 * GenericCustomPost type
 */
class GenericCustomPostsEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest extends AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-generic-customposts-enable-disable-modules';
    }
}
