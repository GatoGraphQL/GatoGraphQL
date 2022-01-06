<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\AppObjects\ContainerCacheConfiguration;

interface MainPluginInitializationConfigurationInterface extends PluginInitializationConfigurationInterface
{
    /**
     * Provide the configuration to cache the container
     */
    public function getContainerCacheConfiguration(): ContainerCacheConfiguration;
}
