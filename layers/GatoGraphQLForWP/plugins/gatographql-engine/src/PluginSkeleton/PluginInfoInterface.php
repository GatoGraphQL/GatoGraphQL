<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

interface PluginInfoInterface
{
    public function get(string $key): mixed;
}
