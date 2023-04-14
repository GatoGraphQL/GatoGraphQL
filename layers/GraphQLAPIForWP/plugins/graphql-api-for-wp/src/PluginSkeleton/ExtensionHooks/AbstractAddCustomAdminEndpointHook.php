<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionHooks;

use GraphQLAPI\GraphQLAPI\Constants\HookNames;
use PoP\Root\Module\ModuleInterface;

use function add_filter;

abstract class AbstractAddCustomAdminEndpointHook
{
    public function __construct()
    {
        add_filter(
            HookNames::SUPPORTED_ADMIN_ENDPOINT_GROUPS,
            $this->addSupportedEndpointGroup(...)
        );
        add_filter(
            HookNames::ADMIN_ENDPOINT_GROUP_MODULE_CONFIGURATION,
            $this->getPredefinedAdminEndpointModuleClassConfiguration(...),
            10,
            2
        );
        add_filter(
            HookNames::ADMIN_ENDPOINT_GROUP_MODULE_CLASSES_TO_SKIP,
            $this->getSchemaModuleClassesToSkip(...),
            10,
            2
        );
    }

    /**
     * Name of the endpointGroup to support, to provide
     * in the endpoint URL:
     *
     * wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=${endpointGroupName}
     */
    abstract protected function getAdminEndpointGroup(): string;

    /**
     * Override this method in the hook implementation.
     *
     * Fixed configuration for all components required in the plugin
     * when requesting the custom admin endpoint.
     *
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected function doGetPredefinedAdminEndpointModuleClassConfiguration(
        array $moduleClassConfiguration,
    ): array {
        return $moduleClassConfiguration;
    }

    /**
     * Override this method in the hook implementation.
     *
     * Module classes to skip initializing when requesting
     * the custom admin endpoint.
     *
     * @param array<class-string<ModuleInterface>> $schemaModuleClassesToSkip List of `Module` class which must not initialize their Schema services
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    protected function doGetSchemaModuleClassesToSkip(
        array $schemaModuleClassesToSkip,
    ): array {
        return $schemaModuleClassesToSkip;
    }

    /**
     * @param string[] $supportedAdminEndpointGroups
     * @return string[]
     */
    public function addSupportedEndpointGroup(array $supportedAdminEndpointGroups): array
    {
        $supportedAdminEndpointGroups[] = $this->getAdminEndpointGroup();
        return $supportedAdminEndpointGroups;
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     * when requesting some specific group in the admin endpoint
     *
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    final public function getPredefinedAdminEndpointModuleClassConfiguration(
        array $moduleClassConfiguration,
        string $endpointGroup,
    ): array {
        if ($endpointGroup !== $this->getAdminEndpointGroup()) {
            return $moduleClassConfiguration;
        }
        return $this->doGetPredefinedAdminEndpointModuleClassConfiguration($moduleClassConfiguration);
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @param array<class-string<ModuleInterface>> $schemaModuleClassesToSkip List of `Module` class which must not initialize their Schema services
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    final public function getSchemaModuleClassesToSkip(
        array $schemaModuleClassesToSkip,
        string $endpointGroup,
    ): array {
        if ($endpointGroup !== $this->getAdminEndpointGroup()) {
            return $schemaModuleClassesToSkip;
        }
        return $this->doGetSchemaModuleClassesToSkip($schemaModuleClassesToSkip);
    }
}
