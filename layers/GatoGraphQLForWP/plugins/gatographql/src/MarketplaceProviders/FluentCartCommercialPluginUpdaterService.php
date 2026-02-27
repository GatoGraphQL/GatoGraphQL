<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialPluginUpdatedPluginData;
use GatoGraphQL\GatoGraphQL\MarketplaceProviders\AbstractMarketplaceProviderCommercialPluginUpdaterService;
use WP_Error;

use function wp_remote_get;

/**
 * Based on code from FluentCart's `PluginUpdater` class
 *
 * @see wp-content/plugins/fluent-cart-pro/app/Services/PluginManager/PluginUpdater.php
 */
class FluentCartCommercialPluginUpdaterService extends AbstractMarketplaceProviderCommercialPluginUpdaterService
{
    use FluentCartMarketplaceProviderServiceTrait;

    /**
     * Fetch the update info from the remote server running the Lemon Squeezy plugin.
     */
    protected function getRemotePluginData(CommercialPluginUpdatedPluginData $pluginData): array|WP_Error
    {
        $url = "https://updates.gatoplugins.com/wp-json/lsq/v1/update?license_key={$pluginData->licenseKey}";
        return wp_remote_get($url, ['timeout' => 10]);
    }
}
