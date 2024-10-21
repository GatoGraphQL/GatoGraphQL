<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLPRO\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractEndpointFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase;

/**
 * Can't execute this test, because the `PRIVATE_ENDPOINT` module
 * is always enabled.
 *
 * @see layers/GatoGraphQLForWP/plugins/gatographql/src/ModuleResolvers/EndpointFunctionalityModuleResolver.php `isPredefinedEnabledOrDisabled`
 */
class PrivateEndpointFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest extends AbstractEndpointFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-enable-disable-modules-private-endpoint';
    }

    protected static function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gatographql&action=execute_query';
    }
}
