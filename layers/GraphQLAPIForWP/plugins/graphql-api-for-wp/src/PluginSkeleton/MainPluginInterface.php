<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use PoP\Root\Container\ContainerCacheConfiguration;

interface MainPluginInterface extends PluginInterface
{
    public function getContainerCacheConfiguration(): ContainerCacheConfiguration;
}
