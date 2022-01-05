<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

interface MainPluginInterface extends PluginInterface
{
    /**
     * PluginInfo class for the Plugin
     */
    public function getInfo(): ?MainPluginInfoInterface;
}
