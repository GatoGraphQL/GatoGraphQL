<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

interface ExtensionInterface extends PluginInterface
{
    public function isCommercial(): bool;

    public function getInitializeExtensionPriority(): int;
}
