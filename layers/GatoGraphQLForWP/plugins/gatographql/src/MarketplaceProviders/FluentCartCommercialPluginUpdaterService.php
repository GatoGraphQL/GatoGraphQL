<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\MarketplaceVersion;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialPluginUpdatedPluginData;
use GatoGraphQL\GatoGraphQL\MarketplaceProviders\AbstractMarketplaceProviderCommercialPluginUpdaterService;
use stdClass;
use WP_Error;

/**
 * Based on code from FluentCart's `PluginUpdater` class
 *
 * @see wp-content/plugins/fluent-cart-pro/app/Services/PluginManager/PluginUpdater.php
 */
class FluentCartCommercialPluginUpdaterService extends AbstractMarketplaceProviderCommercialPluginUpdaterService
{
    use FluentCartMarketplaceProviderServiceTrait;

    /**
     * Fetch the update info from the remote server running the FluentCart plugin.
     */
    protected function getRemotePluginData(CommercialPluginUpdatedPluginData $pluginData): array|WP_Error
    {
        $url = 'https://store.gatoplugins.com';
        $fullUrl = add_query_arg([
            'fluent-cart' => 'get_license_version',
        ], $url);

        $payload = [
            'item_id'          => $pluginData->marketplaceProductIDs[MarketplaceVersion::V2_FLUENTCART] ?? null,
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

    protected function maybeAdaptPayload(string $payload): string
    {
        $decodedPayload = json_decode($payload);
        if ($decodedPayload instanceof stdClass && isset($decodedPayload->new_version)) {
            $adaptedResponse = new stdClass();
            $adaptedResponse->success = true;
            $adaptedResponse->error = '';
            $adaptedResponse->error_code = '';
            $adaptedResponse->update = $decodedPayload;
            $adaptedResponse->update->version = $decodedPayload->new_version ?? '';
            $decodedPayload = $adaptedResponse;
            $payload = json_encode($decodedPayload);
            if ($payload === false) {
                return '';
            }
        }
        return $payload;
    }
}
