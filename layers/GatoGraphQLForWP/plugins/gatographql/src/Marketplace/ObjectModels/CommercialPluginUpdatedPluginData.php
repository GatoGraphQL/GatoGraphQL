<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

class CommercialPluginUpdatedPluginData
{
    /**
     * @param array<string,string|int> $marketplaceProductIDs Key: marketplaceVersion, value: ID
     */
    public function __construct(
        public readonly string $pluginName,
        public readonly string $pluginSlug,
        public readonly string $pluginBaseName,
        public readonly string $pluginVersion,
        public readonly string $pluginChangelogURL,
        public readonly string $pluginHomepageURL,
        public readonly array $marketplaceProductIDs,
        public readonly string $licenseKey,
        public readonly string $cacheKey,
    ) {
    }
}
