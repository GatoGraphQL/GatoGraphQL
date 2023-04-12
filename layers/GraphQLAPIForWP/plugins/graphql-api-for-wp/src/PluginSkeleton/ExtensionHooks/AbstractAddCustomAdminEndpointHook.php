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

    abstract protected function getAdminEndpointGroup(): string;

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
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $predefinedAdminEndpointModuleClassConfiguration [key]: Module class, [value]: Configuration
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected function getPredefinedAdminEndpointModuleClassConfiguration(
        array $predefinedAdminEndpointModuleClassConfiguration,
        string $endpointGroup,
    ): array {
        return $predefinedAdminEndpointModuleClassConfiguration;
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @param array<class-string<ModuleInterface>> $schemaModuleClassesToSkip List of `Module` class which must not initialize their Schema services
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    protected function getSchemaModuleClassesToSkip(
        array $schemaModuleClassesToSkip,
        string $endpointGroup,
    ): array {
        return $schemaModuleClassesToSkip;
    }
}
