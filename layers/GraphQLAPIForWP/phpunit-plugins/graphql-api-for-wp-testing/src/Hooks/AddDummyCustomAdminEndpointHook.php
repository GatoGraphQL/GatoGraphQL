<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Hooks;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionHooks\AbstractAddCustomAdminEndpointHook;
use PoP\Root\Module\ModuleInterface;
use PoPCMSSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;

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

    /**
     * Allow querying a specific CPT, to validate in the tests
     *
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected function doGetPredefinedAdminEndpointModuleClassConfiguration(
        array $moduleClassConfiguration,
    ): array {
        $moduleClassConfiguration[CustomPostsModule::class][CustomPostsEnvironment::QUERYABLE_CUSTOMPOST_TYPES] = ['revision'];
        return $moduleClassConfiguration;
    }

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
