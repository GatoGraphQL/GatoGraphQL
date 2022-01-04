<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use PoP\Root\Component\ComponentInterface;

interface PluginComponentInterface extends ComponentInterface
{
    public function setPluginFolder(string $pluginFolder): void;
    public function getPluginFolder(): ?string;
}
