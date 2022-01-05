<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

interface ExtensionInterface extends PluginInterface
{
    /**
     * PluginInfo class for the Plugin
     */
    public function getInfo(): ?ExtensionInfoInterface;
}
