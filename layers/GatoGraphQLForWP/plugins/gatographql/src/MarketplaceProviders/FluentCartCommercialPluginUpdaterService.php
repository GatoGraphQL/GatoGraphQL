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
        $url = 'https://store.gatoplugins.com';
        $fullUrl = add_query_arg([
            'fluent-cart' => 'get_license_version',
        ], $url);

        $payload = [
            // @todo Fix passing ID here
            'item_id'          => 14,//$pluginData->itemID,
            'current_version'  => $pluginData->pluginVersion,
            'site_url'         => home_url(),
            'platform_version' => get_bloginfo('version'),
            'server_version'   => PHP_VERSION,
            'license_key'      => $pluginData->licenseKey,
        ];

        return wp_remote_post($fullUrl, [
            'timeout'   => 15,
            'body'      => $payload
        ]);
    }
}
