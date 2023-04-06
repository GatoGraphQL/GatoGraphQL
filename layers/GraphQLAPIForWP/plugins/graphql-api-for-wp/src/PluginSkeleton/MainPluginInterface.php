<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\AppObjects\ContainerCacheConfiguration;

interface MainPluginInterface extends PluginInterface
{
    public function getContainerCacheConfiguration(): ContainerCacheConfiguration;
}
