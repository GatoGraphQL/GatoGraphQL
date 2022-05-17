<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use PoP\Root\Module\ModuleInterface;

interface PluginModuleInterface extends ModuleInterface
{
    public function setPluginFolder(string $pluginFolder): void;
    public function getPluginFolder(): ?string;
}
