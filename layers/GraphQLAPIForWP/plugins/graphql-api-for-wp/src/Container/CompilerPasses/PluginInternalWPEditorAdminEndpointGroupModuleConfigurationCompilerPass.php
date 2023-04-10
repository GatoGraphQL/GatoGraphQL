<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Constants\EndpointGroups;
use PoP\Root\Module\ModuleInterface;

class PluginInternalWPEditorAdminEndpointGroupModuleConfigurationCompilerPass extends AbstractAdminEndpointGroupModuleConfigurationCompilerPass
{
    /**
     * @return array<string,array<string,mixed>>
     * @phpstan-return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected function getModuleConfiguration(): array
    {
        return [];
    }

    protected function getEndpointGroup(): string
    {
        return EndpointGroups::PLUGIN_INTERNAL_WP_EDITOR;
    }
}
