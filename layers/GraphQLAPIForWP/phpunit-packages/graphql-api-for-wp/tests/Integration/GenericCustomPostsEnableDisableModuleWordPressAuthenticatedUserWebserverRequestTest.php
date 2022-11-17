<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest;

/**
 * Test that, when the "Pages" module is disabled, querying
 * for cpt "page" in field "customPosts" is handled by the
 * GenericCustomPost type
 */
class GenericCustomPostsEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest extends AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-generic-customposts-enable-disable-modules';
    }
}
