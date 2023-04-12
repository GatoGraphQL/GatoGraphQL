<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Hooks;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionHooks\AbstractAddCustomAdminEndpointHook;
use PoP\Root\Module\ModuleInterface;

/**
 * Test adding a custom admin endpoint
 */
class AddDummyCustomAdminEndpointHook extends AbstractAddCustomAdminEndpointHook
{
    public const ADMIN_ENDPOINT_GROUP = 'dummyCustomAdminEndpoint';

    protected function getAdminEndpointGroup(): string
    {
        return self::ADMIN_ENDPOINT_GROUP;
    }

    // /**
    //  * Override this method in the hook implementation.
    //  *
    //  * Fixed configuration for all components required in the plugin
    //  * when requesting the custom admin endpoint.
    //  *
    //  * @param array<class-string<ModuleInterface>,array<string,mixed>> $predefinedAdminEndpointModuleClassConfiguration [key]: Module class, [value]: Configuration
    //  * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
    //  */
    // protected function doGetPredefinedAdminEndpointModuleClassConfiguration(
    //     array $predefinedAdminEndpointModuleClassConfiguration,
    // ): array {
    //     return $predefinedAdminEndpointModuleClassConfiguration;
    // }

    /**
     * Do not disable any schema modules
     *
     * @param array<class-string<ModuleInterface>> $schemaModuleClassesToSkip List of `Module` class which must not initialize their Schema services
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    protected function doGetSchemaModuleClassesToSkip(
        array $schemaModuleClassesToSkip,
    ): array {
        return [];
    }
}
