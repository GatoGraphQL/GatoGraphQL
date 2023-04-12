<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Hooks;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionHooks\AbstractAddCustomAdminEndpointHook;
use PoP\Root\Module\ModuleInterface;

/**
 * Test adding a custom admin endpoint
 */
class AddCustomAdminEndpointHook extends AbstractAddCustomAdminEndpointHook
{
    protected function getAdminEndpointGroup(): string
    {
        return 'dummyCustomAdminEndpoint';
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

    // /**
    //  * Override this method in the hook implementation.
    //  *
    //  * Module classes to skip initializing when requesting
    //  * the custom admin endpoint.
    //  *
    //  * @param array<class-string<ModuleInterface>> $schemaModuleClassesToSkip List of `Module` class which must not initialize their Schema services
    //  * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
    //  */
    // protected function doGetSchemaModuleClassesToSkip(
    //     array $schemaModuleClassesToSkip,
    // ): array {
    //     return $schemaModuleClassesToSkip;
    // }
}
