<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

interface MainPluginInfoInterface extends PluginInfoInterface
{
    /**
     * Where to store the config cache,
     * for both /container and /operational
     * (config persistent cache: component model configuration + schema)
     */
    public function getCacheDir(): string;
}
