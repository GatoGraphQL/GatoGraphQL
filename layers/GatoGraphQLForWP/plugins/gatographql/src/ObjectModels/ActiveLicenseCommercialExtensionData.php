<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

class ActiveLicenseCommercialExtensionData
{
    /**
     * @param array<string,string|int> $marketplaceProductIDs Key: marketplaceVersion, value: ID
     */
    public function __construct(
        public readonly string $productName,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $baseName,
        public readonly string $version,
        public readonly string $changelogURL,
        public readonly string $homepageURL,
        public readonly array $marketplaceProductIDs,
    ) {
    }
}
