<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

use GatoGraphQL\GatoGraphQL\Plugin;

class CommercialPluginUpdatedPluginData
{
    public function __construct(
        public readonly Plugin $plugin,
        public readonly string $licenseKey,
        public readonly string $cacheKey,
    ) {
    }
}
