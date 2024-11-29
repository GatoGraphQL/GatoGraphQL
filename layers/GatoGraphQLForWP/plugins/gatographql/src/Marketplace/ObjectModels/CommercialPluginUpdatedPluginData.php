<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

class CommercialPluginUpdatedPluginData
{
    public function __construct(
        public readonly string $basename,
        public readonly string $slug,
        public readonly string $version,
        public readonly string $licenseKey,
        public readonly string $cacheKey,
    ) {
    }
}
