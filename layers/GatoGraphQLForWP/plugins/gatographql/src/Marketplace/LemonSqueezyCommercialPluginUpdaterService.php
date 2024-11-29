<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

/**
 * Copied code from `Make-Lemonade/lemonsqueezy-wp-updater-example`
 *
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example/blob/7c0c71876309939b07d96e270f4db8568f3148cb/includes/class-plugin-updater.php
 */
class LemonSqueezyCommercialPluginUpdaterService extends AbstractMarketplaceProviderCommercialPluginUpdaterService
{
    /**
     * Use the Marketplace provider's service to
     * update the active commercial extensions
     *
     * @param array<string,string> $licenseKeys Key: Extension Slug, Value: License Key
     */
    public function useMarketplacePluginUpdaterForExtensions(
        array $licenseKeys,
    ): void {
        
    }
}
