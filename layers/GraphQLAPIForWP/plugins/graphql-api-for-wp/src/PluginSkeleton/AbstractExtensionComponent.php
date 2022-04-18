<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

abstract class AbstractExtensionComponent extends AbstractPluginComponent
{
    /**
     * Do not disable the plugin if one of its required
     * 3rd parties is not installed/activated.
     */
    public function onlyEnableIfAllDependenciesAreEnabled(): bool
    {
        return false;
    }
}
