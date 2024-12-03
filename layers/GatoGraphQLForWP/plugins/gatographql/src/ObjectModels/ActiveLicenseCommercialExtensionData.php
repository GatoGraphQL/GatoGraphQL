<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

class ActiveLicenseCommercialExtensionData
{
    public function __construct(
        public readonly string $productName,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $baseName,
        public readonly string $version,
    ) {
    }
}
