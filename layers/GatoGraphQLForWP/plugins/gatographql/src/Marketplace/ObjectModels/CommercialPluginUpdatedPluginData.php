<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginInterface;

class CommercialPluginUpdatedPluginData
{
    public function __construct(
        public readonly PluginInterface $plugin,
        public readonly string $licenseKey,
        public readonly string $cacheKey,
    ) {
    }
}
